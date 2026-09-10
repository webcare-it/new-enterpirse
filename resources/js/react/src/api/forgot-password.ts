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

export const useForgotPasswordMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_forgot_password"],
        mutationFn: async (data: { phone: string }) => {
            const response = await apiClient.post(
                "/auth/forgot-password",
                data
            );
            return response.data;
        },
        onError: (error: unknown) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;

            if (apiError?.errors) {
                const firstError = Object.values(apiError.errors)[0]?.[0];
                toast.error(firstError || "Phone not found.");
                return;
            }

            toast.error(apiError?.message || "Failed to send OTP.");
        },
    });

    return { mutate, isPending };
};

export const useVerifyForgotOtpMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_verify_forgot_otp"],
        mutationFn: async (data: { user_id: number; otp: string }) => {
            const response = await apiClient.post(
                "/auth/verify-forgot-otp",
                data
            );
            return response.data;
        },
        onError: (error: unknown) => {
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

export const useResetPasswordMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_reset_password"],
        mutationFn: async (data: {
            user_id: number;
            password: string;
            password_confirmation: string;
        }) => {
            const response = await apiClient.post(
                "/auth/reset-password",
                data
            );
            return response.data;
        },
        onError: (error: unknown) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;

            if (apiError?.errors) {
                const firstError = Object.values(apiError.errors)[0]?.[0];
                toast.error(firstError || "Failed to reset password.");
                return;
            }

            toast.error(apiError?.message || "Failed to reset password.");
        },
    });

    return { mutate, isPending };
};
