import { getAuthUserId, getTempUserId } from "@/helper";
import { apiClient } from "@/lib/axios";
import { useMutation } from "@tanstack/react-query";

export const useShippingMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["shipping_store"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/cart/shipping", data, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
    });

    return { mutate, isPending };
};
