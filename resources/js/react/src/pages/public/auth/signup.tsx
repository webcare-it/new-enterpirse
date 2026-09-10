import { useState } from "react";
import { Link } from "react-router-dom";
import {
    Eye,
    EyeOff,
    Lock,
    User,
    UserPlus,
    Phone,
    Loader2,
} from "lucide-react";
import { Separator } from "@/components/ui/separator";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { sessionRemove, useRegisterMutation } from "@/api/auth";
import { usePhoneValidation } from "@/hooks/usePhoneValidation";
import { SocialLogin } from "./social";
import { HeaderLogo } from "@/components/common/logo";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { useConfig } from "@/hooks/useConfig";

export const SignUpPage = () => {
    const config = useConfig();
    const isVerification = (config?.phone_verification_otp as string) === "1";
    console.log({ isVerification });

    return (
        <>
            <SeoWrapper title="Sign Up" description="Create a new account" />

            <BaseLayout>
                <LayoutContainer className="mt-4 mb-10">
                    <Form />
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};

const Form = () => {
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [form, setForm] = useState({
        name: "",
        password: "",
        password_confirmation: "",
    });
    const {
        phone,
        error: phoneError,
        handleBlur: onPhoneBlur,
        handlePhoneChange,
        validateBangladeshiPhone,
    } = usePhoneValidation();
    const { mutate, isPending: registerPending } = useRegisterMutation();

    const passwordError =
        form.password.length > 0 && form.password.length < 6
            ? "Password must be at least 6 characters"
            : "";

    const confirmError =
        form.password_confirmation.length > 0
            ? form.password !== form.password_confirmation
                ? "Passwords do not match"
                : "Passwords match"
            : "";

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const validation = validateBangladeshiPhone(phone);
        if (!validation.isValid) return;

        if (form.password !== form.password_confirmation) {
            return;
        }
        sessionRemove();
        mutate({
            name: form.name,
            phone: validation.formattedNumber,
            password: form.password,
            password_confirmation: form.password_confirmation,
        });
    };

    return (
        <div className="flex-1 flex items-center justify-center  bg-white">
            <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                {/* Header */}
                <div className="flex flex-col items-center justify-between">
                    <HeaderLogo />
                    <h2 className="text-3xl font-bold tracking-tight">
                        Create account
                    </h2>
                    <p className="text-muted-foreground mt-2">
                        Sign up to get started with your account
                    </p>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="space-y-4">
                    <div className="space-y-2">
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
                                value={form.name}
                                onChange={handleChange}
                                className="pl-10 h-11"
                                required
                            />
                        </div>
                    </div>
                    <div className="space-y-2">
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
                                placeholder="01XXXXXXXXX"
                                value={phone}
                                onChange={(e) =>
                                    handlePhoneChange(e.target.value)
                                }
                                onBlur={onPhoneBlur}
                                className="pl-10 h-11"
                            />
                        </div>
                        {phoneError && (
                            <p className="text-xs text-destructive px-1">
                                {phoneError}
                            </p>
                        )}
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="password">
                            Password
                            <sub className="text-red-500 text-base">*</sub>
                        </Label>
                        <div className="relative">
                            <Lock className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="password"
                                name="password"
                                type={showPassword ? "text" : "password"}
                                placeholder="••••••••"
                                value={form.password}
                                onChange={handleChange}
                                className="pl-10 pr-10 h-11"
                                required
                            />
                            <button
                                type="button"
                                onClick={() => setShowPassword(!showPassword)}
                                className="absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground cursor-pointer"
                            >
                                {showPassword ? (
                                    <EyeOff className="size-4" />
                                ) : (
                                    <Eye className="size-4" />
                                )}
                            </button>
                        </div>
                        {passwordError && (
                            <p className="text-xs text-destructive px-1">
                                {passwordError}
                            </p>
                        )}
                    </div>

                    <div className="space-y-2">
                        <Label htmlFor="password_confirmation">
                            Confirm Password
                            <sub className="text-red-500 text-base">*</sub>
                        </Label>
                        <div className="relative">
                            <Lock className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="password_confirmation"
                                name="password_confirmation"
                                type={showConfirmPassword ? "text" : "password"}
                                placeholder="••••••••"
                                value={form.password_confirmation}
                                onChange={handleChange}
                                className="pl-10 pr-10 h-11"
                                required
                            />
                            <button
                                type="button"
                                onClick={() =>
                                    setShowConfirmPassword(!showConfirmPassword)
                                }
                                className="absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground cursor-pointer"
                            >
                                {showConfirmPassword ? (
                                    <EyeOff className="size-4" />
                                ) : (
                                    <Eye className="size-4" />
                                )}
                            </button>
                        </div>
                        {confirmError && (
                            <p
                                className={`text-xs px-1 ${confirmError === "Passwords match" ? "text-green-600" : "text-destructive"}`}
                            >
                                {confirmError}
                            </p>
                        )}
                    </div>

                    <div className="pt-1">
                        <Button
                            type="submit"
                            size="xl"
                            className="w-full gap-2"
                            disabled={registerPending}
                        >
                            {registerPending ? (
                                <Loader2 className="size-4 animate-spin" />
                            ) : (
                                <UserPlus className="size-4" />
                            )}
                            {registerPending ? "Submitting..." : "Sign Up"}
                        </Button>
                    </div>
                </form>

                {/* Social Login */}
                <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                        <Separator />
                    </div>
                    <div className="relative flex justify-center text-xs uppercase">
                        <span className="bg-background px-3 text-muted-foreground">
                            Or sign up with
                        </span>
                    </div>
                </div>

                <SocialLogin />
                {/* Footer */}
                <p className="text-center text-sm text-muted-foreground">
                    Already have an account?
                    <Link
                        to="/signin"
                        className="text-primary font-semibold hover:underline"
                    >
                        Sign in
                    </Link>
                </p>
            </div>
        </div>
    );
};
