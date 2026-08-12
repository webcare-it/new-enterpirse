import { apiClient } from "@/lib/axios";
import type { IFilters } from "@/type";
import {
    useInfiniteQuery,
    useMutation,
    useQuery,
    type QueryFunctionContext,
} from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";

export const useGetProducts = (filters: IFilters) => {
    const navigate = useNavigate();
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["get_products", JSON.stringify(filters)],
        queryFn: async (
            context: QueryFunctionContext<readonly string[], number>,
        ) => {
            const pageParam = context.pageParam ?? 1;
            const params = new URLSearchParams({
                page: String(pageParam),
                per_page: "15",
            });

            if (filters.minPrice > 0) {
                params.set("min_price", String(filters.minPrice));
            }

            if (filters.maxPrice < 50000) {
                params.set("max_price", String(filters.maxPrice));
            }

            if (filters.rating > 0) {
                params.set("rating", String(filters.rating));
            }

            if (filters.sort && filters.sort !== "select") {
                params.set("sort", filters.sort);
            }

            if (filters.brands?.length) {
                params.set("brands", filters.brands.join(","));
            }

            const response = await apiClient.get(
                `/products?${params.toString()}`,
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

    if (error) navigate("/");

    return {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    };
};
export const useGetCollections = () => {
    const { slug } = useParams();
    const navigate = useNavigate();
    if (!slug) navigate("/products");

    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["get_collections", slug],
        queryFn: async (context) => {
            const pageParam = context.pageParam ?? 1;
            const params = new URLSearchParams({
                page: String(pageParam),
                per_page: "18",
            });

            if (slug === "new-arrivals") {
                params.set("is_new_arrival", "1");
            }
            if (slug === "todays-deal") {
                params.set("todays_deal", "1");
            }
            if (slug === "best-selling") {
                params.set("best_selling", "1");
            }
            if (slug === "featured") {
                params.set("is_featured", "1");
            }

            const response = await apiClient.get(
                `/collections?${params.toString()}`,
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

    if (error) navigate("/products");

    return {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    };
};

export const useGetCategoryProducts = (filters: IFilters) => {
    const { slug } = useParams();
    const navigate = useNavigate();
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["get_products", JSON.stringify({ filters, slug })],
        queryFn: async (
            context: QueryFunctionContext<readonly string[], number>,
        ) => {
            const pageParam = context.pageParam ?? 1;
            const params = new URLSearchParams({
                page: String(pageParam),
                per_page: "15",
            });

            if (filters.minPrice > 0) {
                params.set("min_price", String(filters.minPrice));
            }

            if (filters.maxPrice < 50000) {
                params.set("max_price", String(filters.maxPrice));
            }

            if (filters.rating > 0) {
                params.set("rating", String(filters.rating));
            }

            if (filters.sort && filters.sort !== "select") {
                params.set("sort", filters.sort);
            }

            if (filters.brands?.length) {
                params.set("brands", filters.brands.join(","));
            }

            if (filters.subCategories?.length) {
                params.set("sub", filters.subCategories.join(","));
            }

            const response = await apiClient.get(
                `/categories/${slug}?${params.toString()}`,
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

    if (error) navigate("/products");

    return {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    };
};

export const useProductDetails = () => {
    const { slug } = useParams();
    const navigate = useNavigate();
    if (!slug) navigate("/products");

    const { data, isLoading, error } = useQuery({
        queryKey: ["get_single_product", slug],
        queryFn: async () => {
            const response = await apiClient.get(`/products/${slug}`);

            return response.data;
        },
    });

    if (error) navigate("/products");

    return { data, isLoading, error };
};

export const useCampaignProducts = () => {
    const { slug } = useParams();
    const navigate = useNavigate();
    if (!slug) navigate("/products");

    const { data, isLoading, error } = useQuery({
        queryKey: ["get_campaign_product", slug],
        queryFn: async () => {
            const response = await apiClient.get(`/campaigns/${slug}`);

            return response.data;
        },
    });

    if (error) navigate("/products");

    return { data, isLoading, error };
};

export const useGetSearchProducts = (q: string, c: string) => {
    const navigate = useNavigate();
    const { data, isLoading, error } = useQuery({
        queryKey: ["search-products", JSON.stringify({ q, c })],
        enabled: Boolean((q ?? "").trim() || (c ?? "").trim()),
        retry: false,
        queryFn: async () => {
            const params = new URLSearchParams();
            if (q) params.set("q", q);
            if (c) params.set("c", c);

            const response = await apiClient.get(
                `/products/search?${params.toString()}`,
            );

            return response.data;
        },
    });

    if (error) navigate("/products");

    return { data, isLoading, error };
};

export const useGetSearchSuggestions = (query: string) => {
    return useQuery({
        queryKey: ["search-suggestions", query],
        enabled: Boolean((query ?? "").trim()),
        retry: false,
        staleTime: 60_000,
        queryFn: async () => {
            const params = new URLSearchParams();
            if (query) params.set("query", query);

            const response = await apiClient.get(
                `/search?${params.toString()}`,
            );

            return response.data;
        },
    });
};

export const useSearchProducts = (q: string, c: string) => {
    const navigate = useNavigate();
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["search-products-infinite", JSON.stringify({ q, c })],
        enabled: Boolean((q ?? "").trim() || (c ?? "").trim()),
        queryFn: async (
            context: QueryFunctionContext<readonly string[], number>,
        ) => {
            const pageParam = context.pageParam ?? 1;
            const params = new URLSearchParams({
                page: String(pageParam),
                per_page: "15",
            });

            if (q) params.set("q", q);
            if (c) params.set("c", c);

            const response = await apiClient.get(
                `/products/search?${params.toString()}`,
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

    if (error) navigate("/products");

    return {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    };
};

export const useReviewStoreMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["review_store"],
        mutationFn: async (data: {
            product_id: number;
            rating: number;
            comment?: string;
        }) => {
            const response = await apiClient.post("/review-store", data);
            return response.data;
        },
    });

    return { mutate, isPending };
};
