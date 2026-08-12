import { apiClient } from "@/lib/axios";
import type {
    ICampaign,
    ICategoryWithProducts,
    IHeroSlider,
    IProduct,
} from "@/type";
import { useQuery } from "@tanstack/react-query";

export const useGetHome = () => {
    const { data, isLoading, error } = useQuery<IRes>({
        queryKey: ["get_home"],
        queryFn: async () => {
            const response = await apiClient.get("/home");

            return response.data;
        },
    });

    return { data, isLoading, error };
};

interface IRes {
    data: {
        sliders: IHeroSlider[];
        todays_deal: IProduct[];
        best_selling: IProduct[];
        new_arrivals: IProduct[];
        featured: IProduct[];
        categories: ICategoryWithProducts[];
        campaigns: ICampaign[];
    };
    isLoading: boolean;
    error: unknown;
}
