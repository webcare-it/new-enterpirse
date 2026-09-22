import { getAuthUserId, getTempUserId } from "@/helper";
import { apiClient } from "@/lib/axios";
import { revalidateQueryFn } from "@/lib/tanstack";
import { useMutation } from "@tanstack/react-query";
import { toast } from "react-hot-toast";

// Backend sends 4xx for invalid/expired coupons, which axios throws as an error
const getErrorMessage = (error: unknown, fallback: string) => {
    const err = error as {
        response?: {
            data?: { message?: string; errors?: Record<string, string[]> };
        };
    };
    const data = err?.response?.data;
    const firstValidationError = data?.errors
        ? Object.values(data.errors)?.[0]?.[0]
        : undefined;
    return data?.message || firstValidationError || fallback;
};

export const useCouponApply = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["coupon_apply"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/coupon-apply", data, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
        onSuccess: (res) => {
            if (res?.success) {
                toast.success(res?.message || "Coupon applied successfully");
                revalidateQueryFn("get_cart");
            } else {
                toast.error(res?.message || "Failed to apply coupon");
            }
        },
        onError: (error) => {
            toast.error(getErrorMessage(error, "Failed to apply coupon"));
        },
    });

    return { mutate, isPending };
};

export const useCouponRemove = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["coupon_remove"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/coupon-remove", data, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
        onSuccess: (res) => {
            if (res?.success) {
                toast.success(res?.message || "Coupon removed successfully");
                revalidateQueryFn("get_cart");
            } else {
                toast.error(res?.message || "Failed to remove coupon");
            }
        },
        onError: (error) => {
            toast.error(getErrorMessage(error, "Failed to remove coupon"));
        },
    });

    return { mutate, isPending };
};
