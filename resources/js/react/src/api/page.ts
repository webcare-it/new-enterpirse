import { apiClient } from "@/lib/axios";
import { useQuery } from "@tanstack/react-query";
import { useNavigate, useParams } from "react-router-dom";

export const useGetPage = (s?: string) => {
    const { slug } = useParams();
    const navigate = useNavigate();

    const { data, isLoading, error } = useQuery({
        queryKey: ["get_page", slug || s],
        queryFn: async () => {
            if (!slug && slug?.trim()) navigate("/");
            const params = new URLSearchParams();
            if (s) {
                params.set("type", String(s));
            } else {
                params.set("type", String(slug));
            }
            const response = await apiClient.get("/pages", { params });

            return response.data;
        },
    });

    return { data, isLoading, error };
};

export const useGetFaqs = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_page_faqs"],
        queryFn: async () => {
            const response = await apiClient.get("/faqs");

            return response.data;
        },
    });

    return { data, isLoading, error };
};

export const useGetAbout = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_page_about_us"],
        queryFn: async () => {
            const response = await apiClient.get("/about");

            return response.data;
        },
    });

    return { data, isLoading, error };
};
