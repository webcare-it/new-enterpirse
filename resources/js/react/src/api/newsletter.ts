import { apiClient } from "@/lib/axios";
import { useMutation } from "@tanstack/react-query";
import { toast } from "react-hot-toast";

export const useNewsletterMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["newsletter_store"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/newsletter", data);
            return response.data;
        },
        onSuccess: (res) => {
            if (res?.success) {
                toast.success(
                    res?.message || "Newsletter submitted successfully",
                );
            } else {
                const mgs =
                    res?.errors?.email?.[0] || "Failed to submit newsletter";
                toast.error(mgs);
            }
        },
    });

    return { mutate, isPending };
};
