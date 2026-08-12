import { TOKEN } from "@/constant";
import { getAuthUserId, getCookie } from "@/helper";
import { apiClient } from "@/lib/axios";
import { revalidateQueryFn } from "@/lib/tanstack";
import { useMutation, useQuery } from "@tanstack/react-query";

export const useToggleWishlistMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["toggle_wishlist"],
        mutationFn: async (data: { product_id: number }) => {
            const response = await apiClient.post("/wishlist/toggle", data, {
                headers: {
                    "User-Id": getAuthUserId(),
                },
            });
            return response.data;
        },
        onSuccess: () => {
            revalidateQueryFn("get_wishlist");
        },
    });

    return { mutate, isPending };
};

export const useGetWishlist = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_wishlist"],
        queryFn: async () => {
            const response = await apiClient.get("/wishlist", {
                headers: {
                    "User-Id": getAuthUserId(),
                },
            });

            return response.data;
        },
        retry: false,
        enabled: !!getCookie(TOKEN),
    });

    return { data, isLoading, error };
};
