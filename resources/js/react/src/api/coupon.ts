import { getAuthUserId, getTempUserId } from "@/helper";
import { apiClient } from "@/lib/axios";
import { revalidateQueryFn } from "@/lib/tanstack";
import { useMutation } from "@tanstack/react-query";
import { toast } from "react-hot-toast";

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
    });

    return { mutate, isPending };
};
