import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import type { IOrderFrom } from "@/type";
import { Mail, Phone, User } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import { useIncompleteOrderMutation } from "@/api/checkout";
import {
    getLocalStorage,
    isValidEmail,
    removeLocalStorage,
    setLocalStorage,
} from "@/helper";
import { CHECKOUT_DRAFT_KEY } from "@/constant";
import { useConfig } from "@/hooks/useConfig";

const loadDraft = (): Partial<IOrderFrom> | null => {
    try {
        const raw = getLocalStorage(CHECKOUT_DRAFT_KEY);
        return raw ? JSON.parse(raw) : null;
    } catch {
        removeLocalStorage(CHECKOUT_DRAFT_KEY);
        return null;
    }
};

interface Props {
    form: IOrderFrom;
    setForm: React.Dispatch<React.SetStateAction<IOrderFrom>>;
}

const isValid = (form: IOrderFrom) => {
    if (form.phone && form.phone.length >= 8) return true;
    if (form.email && isValidEmail(form.email)) return true;
    return false;
};

type Errors = Partial<Record<keyof IOrderFrom, string>>;

const validateField = (name: string, value: string): string => {
    switch (name) {
        case "name":
            if (!value.trim()) return "Name is required";
            break;
        case "phone":
            if (!value.trim()) return "Phone number is required";
            if (value.replace(/[^\d]/g, "").length < 11)
                return "Phone number must be at least 11 digits";
            break;
        case "address":
            if (!value.trim()) return "Address is required";
            break;
    }
    return "";
};

export const OrderFrom = ({ form, setForm }: Props) => {
    const [errors, setErrors] = useState<Errors>({});
    const [touched, setTouched] = useState<
        Partial<Record<keyof IOrderFrom, boolean>>
    >({});

    const config = useConfig();
    const isActive = String(config?.is_active_in_co_oder) === "1";

    const onChange = (
        e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
    ) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...prev, [name]: value }));
        if (touched[name as keyof IOrderFrom]) {
            setErrors((prev) => ({
                ...prev,
                [name]: validateField(name, value),
            }));
        }
    };

    const handleBlur = (
        e: React.FocusEvent<HTMLInputElement | HTMLTextAreaElement>,
    ) => {
        const { name, value } = e.target;
        setTouched((prev) => ({ ...prev, [name]: true }));
        setErrors((prev) => ({
            ...prev,
            [name]: validateField(name, value),
        }));
    };

    const isFirstRender = useRef(true);
    const { mutate } = useIncompleteOrderMutation();

    useEffect(() => {
        const draft = loadDraft();
        if (draft) {
            setForm((prev) => ({ ...prev, ...draft }));
        }
        isFirstRender.current = false;
    }, [setForm]);

    useEffect(() => {
        if (isFirstRender.current) return;
        if (!config) return;
        const timer = setTimeout(() => {
            setLocalStorage(CHECKOUT_DRAFT_KEY, JSON.stringify(form));
            if (isActive && isValid(form)) {
                mutate(form);
            }
        }, 500);
        return () => clearTimeout(timer);
    }, [form, mutate, isActive, config]);

    return (
        <form className="space-y-2 ">
            <div className="space-y-0.5">
                <Label htmlFor="name">
                    Full Name
                    <sub className="text-red-500 text-base">*</sub>
                </Label>
                <div className="relative">
                    <User className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                    <Input
                        id="name"
                        name="name"
                        type="text"
                        placeholder="John Doe"
                        className="pl-10 h-11"
                        value={form.name}
                        onChange={onChange}
                        onBlur={handleBlur}
                        required
                    />
                </div>
                {touched.name && errors.name && (
                    <p className="text-xs text-destructive px-1">
                        {errors.name}
                    </p>
                )}
            </div>

            <div className="space-y-1 pt-1.5">
                <Label htmlFor="email" className="pb-1">
                    Email
                </Label>
                <div className="relative">
                    <Mail className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                    <Input
                        id="email"
                        name="email"
                        type="email"
                        placeholder="mike@example.com"
                        className="pl-10 h-11"
                        value={form.email}
                        onChange={onChange}
                        onBlur={handleBlur}
                    />
                </div>
                {touched.email && errors.email && (
                    <p className="text-xs text-destructive px-1">
                        {errors.email}
                    </p>
                )}
            </div>
            <div className="space-y-0.5">
                <Label htmlFor="phone">
                    Phone
                    <sub className="text-red-500 text-base">*</sub>
                </Label>
                <div className="relative">
                    <Phone className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                    <Input
                        id="phone"
                        name="phone"
                        type="tel"
                        placeholder="+880 1XXX-XXXXXX"
                        className="pl-10 h-11"
                        value={form.phone}
                        onChange={onChange}
                        onBlur={handleBlur}
                        required
                    />
                </div>
                {touched.phone && errors.phone && (
                    <p className="text-xs text-destructive px-1">
                        {errors.phone}
                    </p>
                )}
            </div>
            <div className="space-y-0.5">
                <Label htmlFor="address">
                    Address
                    <sub className="text-red-500 text-base">*</sub>
                </Label>

                <Textarea
                    id="address"
                    name="address"
                    placeholder="Dhaka, Bangladesh"
                    value={form.address}
                    onChange={onChange}
                    onBlur={handleBlur}
                    required
                />
                {touched.address && errors.address && (
                    <p className="text-xs text-destructive px-1">
                        {errors.address}
                    </p>
                )}
            </div>

            <div className="space-y-1 pt-1.5">
                <Label htmlFor="notes" className="pb-1">
                    Notes
                </Label>

                <Textarea
                    id="notes"
                    name="notes"
                    placeholder="Enter your notes"
                    value={form.notes}
                    onChange={onChange}
                />
            </div>
        </form>
    );
};
