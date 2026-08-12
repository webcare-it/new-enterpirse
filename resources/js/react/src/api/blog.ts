import { apiClient } from "@/lib/axios";
import { useInfiniteQuery, useQuery } from "@tanstack/react-query";
import type { QueryFunctionContext } from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";

export const useGetBlogs = () => {
    const {
        data,
        fetchNextPage,
        hasNextPage,
        isFetchingNextPage,
        isLoading,
        error,
    } = useInfiniteQuery({
        queryKey: ["get_blogs"],
        queryFn: async (
            context: QueryFunctionContext<readonly string[], number>,
        ) => {
            const pageParam = context.pageParam ?? 1;
            const response = await apiClient.get(`/blogs?page=${pageParam}`);
            return response.data;
        },
        getNextPageParam: (lastPage) => {
            const current = lastPage?.data?.pagination?.current_page;
            const total = lastPage?.data?.pagination?.total_pages;
            return current && total && current < total
                ? current + 1
                : undefined;
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

export const useBlogDetails = () => {
    const { slug } = useParams();
    const navigate = useNavigate();

    if (!slug) navigate("/blogs");

    const { data, isLoading, error } = useQuery({
        queryKey: ["get_single_blog", slug],
        queryFn: async () => {
            const response = await apiClient.get(`/blogs/${slug}`);
            return response.data;
        },
    });

    return { data, isLoading, error };
};
