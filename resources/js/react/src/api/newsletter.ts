import { apiClient } from "@/lib/axios";
import { useMutation } from "@tanstack/react-query";
import { toast } from "react-hot-toast";

type NewsletterApiError = {
    response?: {
        data?: {
            message?: string;
            errors?: {
                email?: string[];
            };
        };
    };
};

export const useNewsletterMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["newsletter_store"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/newsletter", data);
            console.log(response);
            return response.data;
        },
        onSuccess: (res) => {
            console.log(res);
            if (res?.success) {
                toast.success(
                    res?.message || "Newsletter submitted successfully",
                );
            } else {
                console.log(res);
                const mgs =
                    res?.errors?.email?.[0] || "Failed to submit newsletter";
                toast.error(mgs);
            }
        },
        onError: (error: NewsletterApiError) => {
            const message =
                error.response?.data?.message ||
                error.response?.data?.errors?.email?.[0] ||
                "Failed to submit newsletter";

            toast.error(message);
        },
    });

    return { mutate, isPending };
};
