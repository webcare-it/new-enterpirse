import { useState } from "react";
import { Link } from "react-router-dom";
import { Eye, EyeOff, Lock, LogIn, Loader2, Phone } from "lucide-react";
import { Separator } from "@/components/ui/separator";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { sessionRemove, useLoginMutation } from "@/api/auth";
import { usePhoneValidation } from "@/hooks/usePhoneValidation";
import { SocialLogin } from "./social";
import { HeaderLogo } from "@/components/common/logo";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";

export const SignInPage = () => {
    return (
        <>
            <SeoWrapper title="Sign In" description="Sign in to your account" />
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
    const [form, setForm] = useState({ password: "" });
    const {
        phone,
        error: phoneError,
        handleBlur: onPhoneBlur,
        handlePhoneChange,
        validateBangladeshiPhone,
    } = usePhoneValidation();
    const { mutate: login, isPending: loginPending } = useLoginMutation();

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const validation = validateBangladeshiPhone(phone);
        if (!validation.isValid) return;
        sessionRemove();
        login({
            phone: validation.formattedNumber,
            password: form.password,
        });
    };

    return (
        <div className="flex-1 flex items-center justify-center  bg-white">
            <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                {/* Header */}
                <div className="flex flex-col items-center justify-between">
                    <HeaderLogo />
                    <h2 className="text-3xl font-bold tracking-tight">
                        Welcome back
                    </h2>
                    <p className="text-muted-foreground mt-2">
                        Sign in to your account to continue
                    </p>
                </div>

                {/* Form */}
                <form onSubmit={handleSubmit} className="space-y-5">
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
                                placeholder="Enter your password"
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
                    </div>

                    <div className="flex items-center justify-between">
                        <div className="flex items-center gap-2">
                            <Checkbox id="remember" />
                            <Label
                                htmlFor="remember"
                                className="text-sm font-normal cursor-pointer"
                            >
                                Remember me
                            </Label>
                        </div>
                        {/* <Link
                            to="#"
                            className="text-sm text-primary font-medium hover:underline"
                        >
                            Forgot password?
                        </Link> */}
                    </div>

                    <Button
                        type="submit"
                        size="xl"
                        className="w-full gap-2"
                        disabled={loginPending}
                    >
                        {loginPending ? (
                            <Loader2 className="size-4 animate-spin" />
                        ) : (
                            <LogIn className="size-4" />
                        )}
                        {loginPending ? "Submitting..." : "Sign In"}
                    </Button>
                </form>

                {/* Social Login */}
                <div className="relative">
                    <div className="absolute inset-0 flex items-center">
                        <Separator />
                    </div>
                    <div className="relative flex justify-center text-xs uppercase">
                        <span className="bg-background px-3 text-muted-foreground">
                            Or continue with
                        </span>
                    </div>
                </div>

                <SocialLogin />

                {/* Footer */}
                <p className="text-center text-sm text-muted-foreground">
                    Don&apos;t have an account?{" "}
                    <Link
                        to="/signup"
                        className="text-primary font-semibold hover:underline"
                    >
                        Create one
                    </Link>
                </p>
            </div>
        </div>
    );
};
