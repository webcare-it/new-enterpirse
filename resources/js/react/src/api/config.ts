import { apiClient } from "@/lib/axios";
import { useQuery } from "@tanstack/react-query";

export const useGetConfig = () => {
    const { data, isLoading, error } = useQuery({
        queryKey: ["get_config"],
        queryFn: async () => {
            const response = await apiClient.get("/business-settings");

            return response.data;
        },
    });

    return { data, isLoading, error };
};
