import { apiClient } from "@/lib/axios";
import { revalidateQueryFn } from "@/lib/tanstack";
import { useMutation, useQuery } from "@tanstack/react-query";
import { getAuthUserId, getTempUserId } from "@/helper";
import { toast } from "react-hot-toast";

interface IError {
    response: {
        data: {
            message: string;
        };
    };
}

export const useAddToCartMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["add_to_cart"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/cart/add", data, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
        onSuccess: (res) => {
            if (res?.success) {
                revalidateQueryFn("get_cart");
            } else {
                toast.error(res?.message || "Failed to add to cart");
            }
        },
        onError: (error: IError) => {
            toast.error(
                error?.response?.data?.message || "Failed to add to cart",
            );
        },
    });

    return { mutate, isPending };
};

export const useRemoveCartMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["remove_cart"],
        mutationFn: async (data: unknown) => {
            const payload = data as { id?: number | string } | undefined;
            const response = await apiClient.delete(
                `/cart/remove/${payload?.id ?? ""}`,
                {
                    headers: {
                        "User-Id": getAuthUserId() || getTempUserId(),
                    },
                },
            );
            return response.data;
        },
        onSuccess: () => {
            revalidateQueryFn("get_cart");
        },
        onError: (error: IError) => {
            toast.error(
                error?.response?.data?.message || "Failed to remove from cart",
            );
        },
    });

    return { mutate, isPending };
};

export const useUpdateCartMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["update_cart"],
        mutationFn: async (data: unknown) => {
            const payload = data as
                | { id?: number | string; quantity?: number }
                | undefined;
            const response = await apiClient.put(
                `/cart/update/${payload?.id ?? ""}`,
                {
                    quantity: payload?.quantity ?? 1,
                },
                {
                    headers: {
                        "User-Id": getAuthUserId() || getTempUserId(),
                    },
                },
            );
            return response.data;
        },
        onSuccess: () => {
            revalidateQueryFn("get_cart");
        },
        onError: (error: IError) => {
            toast.error(
                error?.response?.data?.message || "Failed to update cart",
            );
        },
    });

    return { mutate, isPending };
};

export const useGetCart = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_cart"],
        queryFn: async () => {
            const response = await apiClient.get("/cart", {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });

            return response.data;
        },
        retry: false,
        enabled: !!getAuthUserId() || !!getTempUserId(),
    });

    return { data, isLoading, error };
};

export const useCartRelatedProducts = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_cart_related_products"],
        enabled: !!getAuthUserId() || !!getTempUserId(),
        queryFn: async () => {
            const response = await apiClient.get("/cart/related-products", {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });

            return response.data;
        },
    });

    return { data, isLoading, error };
};
