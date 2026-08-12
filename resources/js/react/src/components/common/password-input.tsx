import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { EyeOff, Eye, LockKeyhole } from "lucide-react";
import { useState } from "react";
import { cn } from "@/lib/utils";
import { Link } from "react-router-dom";

interface Props {
    id: string;
    name: string;
    label: string;
    placeholder: string;
    value: string;
    onChange: (value: string) => void;
    required?: boolean;
    className?: string;
    isForgetPassword?: boolean;
}

export const PasswordInput = ({
    id,
    name,
    label,
    placeholder,
    value,
    onChange,
    required = false,
    className = "",
    isForgetPassword = false,
}: Props) => {
    const [showPassword, setShowPassword] = useState<boolean>(false);

    return (
        <div className="space-y-2">
            <Label
                htmlFor={id}
                className="flex items-center justify-between gap-2"
            >
                <span>
                    {label}{" "}
                    {required && <span className="text-red-500">*</span>}
                </span>
                {isForgetPassword && (
                    <Link
                        to="/forgotten-password"
                        className="hover:underline cursor-pointer mr-0.5"
                    >
                        Forgotten password?
                    </Link>
                )}
            </Label>
            <div className="relative">
                <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <LockKeyhole className="text-gray-400 size-5" />
                </div>
                <Input
                    id={id}
                    name={name}
                    type={showPassword ? "text" : "password"}
                    placeholder={placeholder}
                    value={value}
                    onChange={(e) => onChange(e.target.value)}
                    className={cn("pl-10 w-full h-11", className)}
                    required={required}
                />
                <button
                    type="button"
                    className="absolute inset-y-0 right-0 flex items-center pr-3 cursor-pointer"
                    onClick={() => setShowPassword(!showPassword)}
                    aria-label={
                        showPassword ? "Hide password" : "Show password"
                    }
                >
                    {showPassword ? (
                        <EyeOff className="text-gray-500 size-5" />
                    ) : (
                        <Eye className="text-gray-500 size-5" />
                    )}
                </button>
            </div>
        </div>
    );
};
