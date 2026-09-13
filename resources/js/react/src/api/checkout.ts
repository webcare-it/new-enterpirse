import { CHECKOUT_DRAFT_KEY, TEMP_USER_ID, USER_ID } from "@/constant";
import {
    getAuthUserId,
    getTempUserId,
    removeCookie,
    removeLocalStorage,
    renderVariation,
    setCookie,
} from "@/helper";
import type {
    IItemTracker,
    IPersonalInfoTracker,
    IPurchaseTracker,
} from "@/hooks/useGtmTracker";
import { apiClient } from "@/lib/axios";
import { revalidateQueryFn } from "@/lib/tanstack";
import type { ICartItem } from "@/type";
import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";

export const useIncompleteOrderMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["incomplete_order_store"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post(
                "/orders/incomplete-order",
                data,
                {
                    headers: {
                        "User-Id": getAuthUserId() || getTempUserId(),
                    },
                },
            );
            return response.data;
        },
    });

    return { mutate, isPending };
};

interface ICompleteSummary {
    subtotal: number;
    shipping_cost: number;
    coupon_discount: number;
    coupon_code: string | null;
    discount: number;
    tax: number;
    grand_total: number;
}

interface ICompleteCustomer {
    user_id: number;
    customer_type: "new" | "returning";
    name: string;
    email: string;
    phone: string;
    address: string;
}

interface ICompleteOrder {
    date: string;
    code: string;
    summary: ICompleteSummary;
    customer: ICompleteCustomer;
    items: ICartItem[];
}

export type IOrderSuccess = ICompleteSummary & {
    date: string;
    code: string;
};

export const useCheckoutMutation = () => {
    const navigate = useNavigate();

    const mutation = useMutation({
        mutationKey: ["complete_order_store_checkout"],
        mutationFn: async (data: unknown) => {
            const response = await apiClient.post("/orders/place", data, {
                headers: {
                    "User-Id": getAuthUserId() || getTempUserId(),
                },
            });
            return response.data;
        },
        onSuccess: (data) => {
            const res =
                (data?.data as ICompleteOrder & {
                    otp_sent?: boolean;
                    order_code?: string;
                }) || {};

            if (res?.otp_sent) {
                return;
            }

            removeCookie(TEMP_USER_ID);
            revalidateQueryFn("get_cart");
            removeLocalStorage(CHECKOUT_DRAFT_KEY);
            setCookie(USER_ID, String(res?.customer?.user_id));

            const items: IItemTracker[] = res?.items?.map((item, i) => ({
                item_id: item?.product?.id?.toString() || "",
                item_name: item?.product?.name || "",
                item_price: item?.product?.price || 0,
                item_quantity: item?.product?.quantity || 0,
                item_variant: renderVariation(item?.product?.variation) || "",
                index: i || 0,
            }));

            const tracker: IPurchaseTracker = {
                transaction_id: res?.code || "",
                value: res?.summary?.grand_total || 0,
                shipping: res?.summary?.shipping_cost || 0,
                coupon: res?.summary?.coupon_code || "",
                tax: res?.summary?.tax || 0,
                customer_type: res?.customer?.customer_type || "new",
                items,
            };
            const user: IPersonalInfoTracker = {
                name: res?.customer?.name || "",
                email: res?.customer?.email || "",
                phone: res?.customer?.phone || "",
                address: res?.customer?.address || "",
            };
            const or: IOrderSuccess = {
                code: res?.code || "",
                date: res?.date || "",
                ...res?.summary,
            };

            const params = new URLSearchParams({
                order: JSON.stringify(or),
                user: JSON.stringify(user),
                tracker: JSON.stringify(tracker),
            });

            navigate(`/checkout/success?${params.toString()}`);
        },
    });

    return {
        mutate: mutation.mutate,
        isPending: mutation.isPending,
        data: mutation.data,
    };
};

export const useVerifyOrderOtpMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["verify_order_otp"],
        mutationFn: async (data: { order_code: string; otp: string }) => {
            const response = await apiClient.post(
                "/orders/verify-order-otp",
                data,
            );
            return response.data;
        },
    });

    return { mutate, isPending };
};

export const useResendOrderOtpMutation = () => {
    const { mutate, isPending } = useMutation({
        mutationKey: ["resend_order_otp"],
        mutationFn: async (data: { order_code: string }) => {
            const response = await apiClient.post(
                "/orders/resend-order-otp",
                data,
            );
            return response.data;
        },
    });

    return { mutate, isPending };
};
