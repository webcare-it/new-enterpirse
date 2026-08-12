import { apiClient } from "@/lib/axios";
import { useMutation, useQuery } from "@tanstack/react-query";
import { setCookie, removeCookie, getAuthUserId } from "@/helper";
import { revalidateQueryFn } from "@/lib/tanstack";
import toast from "react-hot-toast";
import { TEMP_USER_ID, TOKEN, USER_ID } from "@/constant";

interface ApiError {
    response: {
        data: {
            message?: string;
            error?: string;
            errors?: Record<string, string[]>;
        };
    };
}

interface ProfileUpdateData {
    name?: string;
    email?: string;
    phone?: string;
    address?: string;
    city?: string;
    state?: string;
    country?: string;
    postal_code?: string;
    avatar?: string;
}

interface ChangePasswordData {
    current_password: string;
    new_password: string;
    new_password_confirmation: string;
}

export const useRegisterMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_user_register"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/auth/register", data);
            return response.data;
        },
        onSuccess: (res) => {
            const token = res?.data?.token;
            const user = res?.data?.user;
            if (token && user) {
                sessionStore(token, user.id);
            }
        },
        onError: (error: unknown) => {
            const message =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data?.message ||
                      (error as ApiError).response?.data?.error
                    : null;
            toast.error(message || "Unable to register.");
        },
    });

    return { mutate, isPending };
};

export const sessionStore = (token: string, user_id: number | string) => {
    if (token) {
        setCookie(TOKEN, token);
    }
    if (user_id) {
        setCookie(USER_ID, String(user_id));
    }
    toast.success("Sign in successful.");
    window.location.assign("/");
};

export const sessionRemove = () => {
    removeCookie(USER_ID);
    removeCookie(TOKEN);
    removeCookie(TEMP_USER_ID);
};

export const useLoginMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_login_user"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/auth/login", data);
            return response.data;
        },
        onSuccess: (res) => {
            const token = res?.data?.token;
            const user = res?.data?.user;
            if (token && user) sessionStore(token, user?.id);
        },
        onError: (error: unknown) => {
            const message =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data?.message ||
                      (error as ApiError).response?.data?.error
                    : null;
            toast.error(message || "Unable to sign in.");
        },
    });

    return { mutate, isPending };
};

export const useGetUser = () => {
    const { isLoading, data, error } = useQuery({
        queryKey: ["auth_user_me"],
        queryFn: async () => {
            const response = await apiClient.get("/auth/profile");
            return response.data;
        },
        enabled: !!getAuthUserId(),
        retry: false,
        staleTime: 5 * 60 * 1000,
    });

    return { isLoading, data, error };
};

export const useProfileUpdateMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_profile_update"],
        mutationFn: async (data: ProfileUpdateData) => {
            const response = await apiClient.put("/auth/profile-update", data);
            return response.data;
        },
        onSuccess: (res) => {
            toast.success(res?.message || "Profile updated successfully.");
            revalidateQueryFn("auth_user_me");
        },
        onError: (error: unknown) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;

            if (apiError?.errors) {
                const firstError = Object.values(apiError.errors)[0]?.[0];
                toast.error(firstError || "Validation error.");
                return;
            }

            toast.error(apiError?.message || "Unable to update profile.");
        },
    });

    return { mutate, isPending };
};

export const useChangePasswordMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_change_password"],
        mutationFn: async (data: ChangePasswordData) => {
            const response = await apiClient.put("/auth/change-password", data);
            return response.data;
        },
        onSuccess: (res) => {
            toast.success(res?.message || "Password changed successfully.");
        },
        onError: (error: unknown) => {
            const apiError =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data
                    : null;

            if (apiError?.errors) {
                const firstError = Object.values(apiError.errors)[0]?.[0];
                toast.error(firstError || "Validation error.");
                return;
            }

            toast.error(apiError?.message || "Unable to change password.");
        },
    });

    return { mutate, isPending };
};

export const useLogoutMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["auth_user_signout"],
        mutationFn: async () => {
            const response = await apiClient.post("/auth/logout");
            return response.data;
        },
        onSuccess: () => {
            removeCookie(TOKEN);
            removeCookie(USER_ID);
            window.location.assign("/");
        },
        onError: (error: unknown) => {
            removeCookie(TOKEN);
            removeCookie(USER_ID);
            const message =
                typeof error === "object" &&
                error !== null &&
                "response" in error
                    ? (error as ApiError).response?.data?.message ||
                      (error as ApiError).response?.data?.error
                    : null;
            toast.error(message || "Signed out.");
            window.location.assign("/");
        },
    });

    return { mutate, isPending };
};
