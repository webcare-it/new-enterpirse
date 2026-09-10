import { useState } from "react";
import { Link } from "react-router-dom";
import {
    Eye,
    EyeOff,
    Lock,
    Phone,
    Loader2,
    ArrowLeft,
    KeyRound,
} from "lucide-react";
import { Label } from "@/components/ui/label";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSeparator,
    InputOTPSlot,
} from "@/components/ui/input-otp";
import { usePhoneValidation } from "@/hooks/usePhoneValidation";
import { HeaderLogo } from "@/components/common/logo";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import {
    useForgotPasswordMutation,
    useVerifyForgotOtpMutation,
    useResetPasswordMutation,
} from "@/api/forgot-password";
import { setCookie } from "@/helper";
import { TOKEN, USER_ID } from "@/constant";
import toast from "react-hot-toast";

type Step = "phone" | "otp" | "reset";

export const ForgetPasswordPage = () => {
    return (
        <>
            <SeoWrapper
                title="Forgot Password"
                description="Reset your password"
            />
            <BaseLayout>
                <LayoutContainer className="mt-4 mb-24">
                    <Form />
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};

const Form = () => {
    const [step, setStep] = useState<Step>("phone");
    const [userId, setUserId] = useState<number | null>(null);
    const [phone, setPhoneState] = useState("");
    const [otp, setOtp] = useState("");
    const [newPassword, setNewPassword] = useState("");
    const [confirmPassword, setConfirmPassword] = useState("");
    const [showPassword, setShowPassword] = useState(false);

    const {
        phone: phoneInput,
        error: phoneError,
        handleBlur: onPhoneBlur,
        handlePhoneChange,
        validateBangladeshiPhone,
    } = usePhoneValidation();

    const { mutate: sendOtp, isPending: sendOtpPending } =
        useForgotPasswordMutation();
    const { mutate: verifyOtp, isPending: verifyOtpPending } =
        useVerifyForgotOtpMutation();
    const { mutate: resetPassword, isPending: resetPending } =
        useResetPasswordMutation();

    const handleSendOtp = (e: React.FormEvent) => {
        e.preventDefault();
        if (!phoneInput) {
            toast.error("Please enter your phone number.");
            return;
        }
        const validation = validateBangladeshiPhone(phoneInput);
        if (!validation.isValid) {
            toast.error("Please enter a valid Bangladeshi phone number.");
            return;
        }

        sendOtp(
            { phone: validation.formattedNumber as string },
            {
                onSuccess: (res) => {
                    if (res?.data?.user_id && res?.data?.phone) {
                        setUserId(res.data.user_id);
                        setPhoneState(res.data.phone);
                        setStep("otp");
                        toast.success("OTP sent to your phone.");
                    }
                },
            },
        );
    };

    const handleVerifyOtp = (e: React.FormEvent) => {
        e.preventDefault();
        if (otp.length !== 6 || !userId) return;

        verifyOtp(
            { user_id: userId, otp },
            {
                onSuccess: () => {
                    setStep("reset");
                    toast.success("OTP verified. Set your new password.");
                },
            },
        );
    };

    const handleResetPassword = (e: React.FormEvent) => {
        e.preventDefault();
        if (!userId) return;

        if (newPassword !== confirmPassword) {
            toast.error("Passwords do not match.");
            return;
        }

        resetPassword(
            {
                user_id: userId,
                password: newPassword,
                password_confirmation: confirmPassword,
            },
            {
                onSuccess: (res) => {
                    if (res?.data?.token && res?.data?.user) {
                        setCookie(TOKEN, res?.data?.token);
                        setCookie(USER_ID, String(res?.data?.user?.id));
                        toast.success("Password reset successfully.");
                        window.location.assign("/");
                    }
                },
            },
        );
    };

    if (step === "otp") {
        return (
            <div className="flex-1 flex items-center justify-center bg-white">
                <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                    <div className="flex flex-col items-center justify-between">
                        <HeaderLogo />
                        <h2 className="text-3xl font-bold tracking-tight">
                            Enter OTP
                        </h2>
                        <p className="text-muted-foreground mt-2 text-center">
                            Enter the 6-digit code sent to{" "}
                            <span className="font-semibold text-foreground">
                                {phone}
                            </span>
                        </p>
                    </div>

                    <form className="space-y-6" onSubmit={handleVerifyOtp}>
                        <div className="flex justify-center">
                            <InputOTP
                                maxLength={6}
                                value={otp}
                                onChange={setOtp}
                            >
                                <InputOTPGroup>
                                    <InputOTPSlot index={0} />
                                    <InputOTPSlot index={1} />
                                    <InputOTPSlot index={2} />
                                </InputOTPGroup>
                                <InputOTPSeparator />
                                <InputOTPGroup>
                                    <InputOTPSlot index={3} />
                                    <InputOTPSlot index={4} />
                                    <InputOTPSlot index={5} />
                                </InputOTPGroup>
                            </InputOTP>
                        </div>

                        <Button
                            className="w-full"
                            size="lg"
                            type="submit"
                            disabled={verifyOtpPending || otp.length !== 6}
                        >
                            {verifyOtpPending ? (
                                <>
                                    <Loader2 className="size-4 animate-spin" />
                                    <span>Verifying...</span>
                                </>
                            ) : (
                                <span>Verify</span>
                            )}
                        </Button>
                    </form>

                    <button
                        type="button"
                        onClick={() => setStep("phone")}
                        className="flex items-center justify-center gap-2 w-full text-sm text-muted-foreground hover:text-foreground cursor-pointer"
                    >
                        <ArrowLeft className="size-4" />
                        Back
                    </button>
                </div>
            </div>
        );
    }

    if (step === "reset") {
        return (
            <div className="flex-1 flex items-center justify-center bg-white">
                <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                    <div className="flex flex-col items-center justify-between">
                        <HeaderLogo />
                        <h2 className="text-3xl font-bold tracking-tight">
                            Reset Password
                        </h2>
                        <p className="text-muted-foreground mt-2 text-center">
                            Enter your new password below
                        </p>
                    </div>

                    <form className="space-y-4" onSubmit={handleResetPassword}>
                        <div className="space-y-2">
                            <Label htmlFor="new-password">
                                New Password
                                <sub className="text-red-500 text-base">*</sub>
                            </Label>
                            <div className="relative">
                                <Lock className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                                <Input
                                    id="new-password"
                                    type={showPassword ? "text" : "password"}
                                    placeholder="••••••••"
                                    value={newPassword}
                                    onChange={(e) =>
                                        setNewPassword(e.target.value)
                                    }
                                    className="pl-10 pr-10 h-11"
                                    required
                                    minLength={6}
                                />
                                <button
                                    type="button"
                                    onClick={() =>
                                        setShowPassword(!showPassword)
                                    }
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

                        <div className="space-y-2">
                            <Label htmlFor="confirm-password">
                                Confirm Password
                                <sub className="text-red-500 text-base">*</sub>
                            </Label>
                            <div className="relative">
                                <Lock className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                                <Input
                                    id="confirm-password"
                                    type="password"
                                    placeholder="••••••••"
                                    value={confirmPassword}
                                    onChange={(e) =>
                                        setConfirmPassword(e.target.value)
                                    }
                                    className="pl-10 h-11"
                                    required
                                    minLength={6}
                                />
                            </div>
                        </div>

                        {newPassword &&
                            confirmPassword &&
                            newPassword !== confirmPassword && (
                                <p className="text-xs text-destructive px-1">
                                    Passwords do not match
                                </p>
                            )}

                        <Button
                            type="submit"
                            size="xl"
                            className="w-full gap-2"
                            disabled={
                                resetPending ||
                                !newPassword ||
                                !confirmPassword ||
                                newPassword !== confirmPassword
                            }
                        >
                            {resetPending ? (
                                <Loader2 className="size-4 animate-spin" />
                            ) : (
                                <KeyRound className="size-4" />
                            )}
                            {resetPending ? "Resetting..." : "Reset Password"}
                        </Button>
                    </form>

                    <button
                        type="button"
                        onClick={() => setStep("otp")}
                        className="flex items-center justify-center gap-2 w-full text-sm text-muted-foreground hover:text-foreground cursor-pointer"
                    >
                        <ArrowLeft className="size-4" />
                        Back to OTP
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="flex-1 flex items-center justify-center bg-white">
            <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                <div className="flex flex-col items-center justify-between">
                    <HeaderLogo />
                    <h2 className="text-3xl font-bold tracking-tight">
                        Forgot Password
                    </h2>
                    <p className="text-muted-foreground mt-2 text-center">
                        Enter your phone number to receive a verification code
                    </p>
                </div>

                <form onSubmit={handleSendOtp} className="space-y-4">
                    <div className="space-y-2">
                        <Label htmlFor="phone">
                            Phone
                            <sub className="text-red-500 text-base">*</sub>
                        </Label>
                        <div className="relative">
                            <Phone className="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted-foreground" />
                            <Input
                                id="phone"
                                type="tel"
                                placeholder="01XXXXXXXXX"
                                value={phoneInput}
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

                    <Button
                        type="submit"
                        size="xl"
                        className="w-full gap-2"
                        disabled={sendOtpPending}
                    >
                        {sendOtpPending ? (
                            <Loader2 className="size-4 animate-spin" />
                        ) : (
                            <Phone className="size-4" />
                        )}
                        {sendOtpPending ? "Sending..." : "Send OTP"}
                    </Button>
                </form>

                <p className="text-center text-sm text-muted-foreground">
                    Remember your password?{" "}
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
