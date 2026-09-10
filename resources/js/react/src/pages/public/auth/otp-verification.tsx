import { Button } from "@/components/ui/button";
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSeparator,
    InputOTPSlot,
} from "@/components/ui/input-otp";
import { Spinner } from "@/components/ui/spinner";
import { useState, useEffect } from "react";
import {
    useVerifyOtpMutation,
    useResendOtpMutation,
} from "@/api/otp";
import { HeaderLogo } from "@/components/common/logo";
import { ArrowLeft } from "lucide-react";

interface Props {
    userId: number;
    phone: string;
    onVerified: (token: string, userId: number) => void;
    onBack: () => void;
}

export const OtpVerification = ({
    userId,
    phone,
    onVerified,
    onBack,
}: Props) => {
    const [otp, setOtp] = useState("");
    const [timer, setTimer] = useState(60 * 10);
    const [resendTimer, setResendTimer] = useState(60 * 2);
    const { mutate: verifyOtp, isPending: verifyPending } =
        useVerifyOtpMutation();
    const { mutate: resendOtp, isPending: resendPending } =
        useResendOtpMutation();

    useEffect(() => {
        if (timer > 0) {
            const countdown = setTimeout(() => setTimer(timer - 1), 1000);
            return () => clearTimeout(countdown);
        }
    }, [timer]);

    useEffect(() => {
        if (resendTimer > 0) {
            const countdown = setTimeout(
                () => setResendTimer(resendTimer - 1),
                1000
            );
            return () => clearTimeout(countdown);
        }
    }, [resendTimer]);

    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${mins < 10 ? `0${mins}` : mins}:${secs < 10 ? `0${secs}` : secs}`;
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (otp.length !== 6) return;

        verifyOtp(
            { user_id: userId, otp },
            {
                onSuccess: (res) => {
                    if (res?.data?.token && res?.data?.user) {
                        onVerified(res.data.token, res.data.user.id);
                    }
                },
            }
        );
    };

    const handleResend = () => {
        resendOtp({ user_id: userId });
        setTimer(60 * 10);
        setResendTimer(60 * 2);
    };

    return (
        <div className="flex-1 flex items-center justify-center bg-white">
            <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                <div className="flex flex-col items-center justify-between">
                    <HeaderLogo />
                    <h2 className="text-3xl font-bold tracking-tight">
                        Phone Verification
                    </h2>
                    <p className="text-muted-foreground mt-2 text-center">
                        Enter the 6-digit code sent to{" "}
                        <span className="font-semibold text-foreground">
                            {phone}
                        </span>
                    </p>
                </div>

                <div className="text-center">
                    <span className="text-sm font-mono text-primary">
                        OTP valid for: {formatTime(timer)}
                    </span>
                </div>

                <form className="space-y-6" onSubmit={handleSubmit}>
                    <div className="flex justify-center">
                        <InputOTP maxLength={6} value={otp} onChange={setOtp}>
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

                    {resendTimer > 0 ? (
                        <div className="flex items-center justify-center text-sm text-muted-foreground">
                            Resend available in: {formatTime(resendTimer)}
                        </div>
                    ) : (
                        <div className="flex items-center justify-center">
                            <p className="text-sm text-muted-foreground">
                                Did not receive OTP?
                            </p>
                            <Button
                                disabled={resendPending}
                                variant="link"
                                type="button"
                                size="sm"
                                onClick={handleResend}
                            >
                                {resendPending ? "Sending..." : "Resend OTP"}
                            </Button>
                        </div>
                    )}

                    <Button
                        className="w-full"
                        size="lg"
                        type="submit"
                        disabled={verifyPending || otp.length !== 6}
                    >
                        {verifyPending ? (
                            <>
                                <Spinner />
                                <span>Verifying...</span>
                            </>
                        ) : (
                            <span>Verify</span>
                        )}
                    </Button>
                </form>

                <button
                    type="button"
                    onClick={onBack}
                    className="flex items-center justify-center gap-2 w-full text-sm text-muted-foreground hover:text-foreground cursor-pointer"
                >
                    <ArrowLeft className="size-4" />
                    Back to Sign Up
                </button>
            </div>
        </div>
    );
};
