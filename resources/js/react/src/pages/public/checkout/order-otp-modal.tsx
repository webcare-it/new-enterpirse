import { useEffect, useState } from "react";
import { Button } from "@/components/ui/button";
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from "@/components/ui/input-otp";
import { HeaderLogo } from "@/components/common/logo";
import { Loader2 } from "lucide-react";

interface OrderOtpVerificationProps {
    orderCode: string;
    onVerified: () => void;
    onResend: (orderCode: string) => void;
    verifyPending: boolean;
    resendPending: boolean;
    onVerify: (orderCode: string, otp: string) => void;
}

export const OrderOtpVerification = ({
    orderCode,
    onResend,
    verifyPending,
    resendPending,
    onVerify,
}: OrderOtpVerificationProps) => {
    const [otp, setOtp] = useState("");
    const [timer, setTimer] = useState(60);

    useEffect(() => {
        setOtp("");
        setTimer(60);
    }, [orderCode]);

    useEffect(() => {
        if (timer <= 0) return;
        const interval = setInterval(() => setTimer((t) => t - 1), 1000);
        return () => clearInterval(interval);
    }, [timer]);

    const handleVerify = () => {
        if (otp.length !== 6) return;
        onVerify(orderCode, otp);
    };

    const handleResend = () => {
        onResend(orderCode);
        setTimer(60);
        setOtp("");
    };

    return (
        <div className="flex-1 flex items-center justify-center bg-white">
            <div className="w-full max-w-md space-y-8 border p-4 md:p-6 rounded-3xl">
                <div className="flex flex-col items-center justify-between">
                    <HeaderLogo />
                    <h2 className="text-3xl font-bold tracking-tight">
                        Verify Order
                    </h2>
                    <p className="text-muted-foreground mt-2 text-center">
                        Enter the 6-digit code sent to your phone
                    </p>
                </div>

                <div className="space-y-6">
                    <div className="flex justify-center">
                        <InputOTP maxLength={6} value={otp} onChange={setOtp}>
                            <InputOTPGroup>
                                <InputOTPSlot index={0} />
                                <InputOTPSlot index={1} />
                                <InputOTPSlot index={2} />
                            </InputOTPGroup>
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
                        onClick={handleVerify}
                        disabled={verifyPending || otp.length !== 6}
                    >
                        {verifyPending ? (
                            <>
                                <Loader2 className="size-4 animate-spin" />
                                Verifying...
                            </>
                        ) : (
                            "Verify"
                        )}
                    </Button>

                    <div className="text-center">
                        {timer > 0 ? (
                            <span className="text-sm text-muted-foreground">
                                Resend OTP in {timer}s
                            </span>
                        ) : (
                            <button
                                onClick={handleResend}
                                disabled={resendPending}
                                className="text-sm text-primary font-medium hover:underline cursor-pointer"
                            >
                                {resendPending ? "Sending..." : "Resend OTP"}
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};
