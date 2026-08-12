import { getAuthUserId, getTempUserId } from "@/helper";
import { apiClient } from "@/lib/axios";
import {
    useInfiniteQuery,
    useMutation,
    useQuery,
    type QueryFunctionContext,
} from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";

export const useGetOrderDetails = () => {
    const { id } = useParams();
    const navigate = useNavigate();

    if (!id) navigate("/");

    const { data, isLoading, error } = useQuery({
        queryKey: ["get_order_details", id],
        queryFn: async () => {
            const response = await apiClient.get(`/orders/${id}`, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });

            return response.data;
        },
    });

    return { data, isLoading, error };
};

export const useGetOrderList = () => {
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["get_order_list"],
        queryFn: async (
            context: QueryFunctionContext<readonly string[], number>,
        ) => {
            const pageParam = context.pageParam ?? 1;
            const params = new URLSearchParams({
                page: String(pageParam),
                per_page: "15",
            });

            const response = await apiClient.get(
                `/auth/purchase-history?${params.toString()}`,
            );
            return response.data;
        },
        getNextPageParam: (lastPage) => {
            const hasMore = lastPage?.data?.pagination?.has_more;
            const current = lastPage?.data?.pagination?.current_page;
            const total = lastPage?.data?.pagination?.total_pages;

            if (hasMore === false) {
                return undefined;
            }

            if (typeof current === "number" && typeof total === "number") {
                return current < total ? current + 1 : undefined;
            }

            return undefined;
        },
        initialPageParam: 1,
    });

    return {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    };
};

export const useTrackOrder = () => {
    const { mutate, isPending, data, error, isSuccess, reset } = useMutation({
        mutationKey: ["get_track_order"],
        mutationFn: async (id: string | number) => {
            const response = await apiClient.get(`/orders/${id}`, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
    });

    return { mutate, isPending, data, error, isSuccess, reset };
};

export const useDownloadInvoice = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["get_download_invoice"],
        mutationFn: async (id: string | number) => {
            const response = await apiClient.get(`/orders/invoice/${id}`, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
                responseType: "blob",
            });
            return response;
        },
    });

    return { mutate, isPending };
};
