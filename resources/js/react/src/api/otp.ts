import { apiClient } from "@/lib/axios";
import { useMutation } from "@tanstack/react-query";
import toast from "react-hot-toast";

interface ApiError {
    response: {
        data: {
            message?: string;
            errors?: Record<string, string[]>;
        };
    };
}

export const useVerifyOtpMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_verify_otp"],
        mutationFn: async (data: { user_id: number; otp: string }) => {
            const response = await apiClient.post("/auth/verify-otp", data);
            return response.data;
        },
        onError: (error: ApiError) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;

            if (apiError?.errors) {
                const firstError = Object.values(apiError.errors)[0]?.[0];
                toast.error(firstError || "Invalid OTP.");
                return;
            }

            toast.error(apiError?.message || "OTP verification failed.");
        },
    });

    return { mutate, isPending };
};

export const useResendOtpMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_resend_otp"],
        mutationFn: async (data: { user_id: number }) => {
            const response = await apiClient.post("/auth/resend-otp", data);
            return response.data;
        },
        onSuccess: () => {
            toast.success("OTP resent successfully.");
        },
        onError: (error: ApiError) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;
            toast.error(apiError?.message || "Failed to resend OTP.");
        },
    });

    return { mutate, isPending };
};
