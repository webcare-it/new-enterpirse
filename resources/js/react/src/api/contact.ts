import { apiClient } from "@/lib/axios";
import { useMutation } from "@tanstack/react-query";
import { toast } from "react-hot-toast";

export const useContactStoreMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["contact_store"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/contact-store", data);
            return response.data;
        },
        onSuccess: (res) => {
            if (res?.status) {
                toast.success(
                    res?.message || "Contact form submitted successfully",
                );
            } else {
                const mgs =
                    res?.errors?.email?.[0] || "Failed to submit contact form";
                toast.error(mgs);
            }
        },
    });

    return { mutate, isPending };
};
