import { getSplitName, sha256 } from "@/helper";
import { useConfig } from "./useConfig";
import { usePrice } from "./usePrice";

export interface IItemTracker {
    item_id: string;
    item_name: string;
    item_price: number;
    item_quantity: number;
    item_variant?: string | null;
    index?: number;
    coupon?: string | null;
    item_brand?: string | null;
    item_category?: string | null;
}

export interface IPurchaseTracker {
    transaction_id: string;
    value: number;
    shipping?: number;
    coupon?: string;
    tax?: number;
    customer_type?: "new" | "returning";
    items: IItemTracker[];
}

export interface IPersonalInfoTracker {
    email: string;
    phone: string;
    name: string;
    address: string;
}

export interface IViewCartTrackerType {
    value: number;
    items: IItemTracker[];
}

export const useGtmTracker = () => {
    const config = useConfig();
    const { getCurrencyExtension } = usePrice();
    const name = (config?.website_name as string) || "App";
    const session_id = (config?.session_id as string) || crypto.randomUUID();
    const customer_ip =
        (config?.customer_ip as string) || window.navigator.userAgent;
    const viewItemTracker = (data: IItemTracker, slug: string) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "view_item",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.item_price * data?.item_quantity,
                    items: [{ ...data }],
                    page_data: [
                        {
                            page_title: "Product Details",
                            page_url: `${window.location.origin}/products/${slug}`,
                            page_path: `/products/${slug}`,
                            referrer: document.referrer,
                            user_agent: window.navigator.userAgent,
                            session_id: session_id,
                            visitor_ip: customer_ip,
                        },
                    ],
                },
            });
        }
    };

    const addToCartTracker = (data: IItemTracker) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "add_to_cart",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.item_price * data?.item_quantity || 1,
                    items: [
                        {
                            item_id: data?.item_id,
                            item_name: data?.item_name,
                            price: data?.item_price,
                            quantity: data?.item_quantity,
                            index: data?.index,
                            item_variant: data?.item_variant || undefined,
                            item_brand: data?.item_brand || undefined,
                            item_category: data?.item_category || undefined,
                        },
                    ],
                },
            });
        }
    };
    const viewCartTracker = (data: IViewCartTrackerType) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "view_cart",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.value,
                    items: data?.items,
                    page_data: [
                        {
                            page_title: "My Cart",
                            page_url: `${window.location.origin}/my-cart`,
                            page_path: `/my-cart`,
                            referrer: document.referrer,
                            user_agent: window.navigator.userAgent,
                            session_id: session_id,
                            visitor_ip: customer_ip,
                        },
                    ],
                },
            });
        }
    };

    const beginCheckoutTracker = (data: IPurchaseTracker) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "begin_checkout",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.value,
                    coupon: data?.coupon,
                    items: data?.items,
                    customer_data: [
                        {
                            page_post_author: name,
                            customer_first_name: null,
                            customer_last_name: null,
                            customer_billing_first_name: null,
                            customer_billing_last_name: null,
                            customer_billing_address: null,
                            customer_billing_city: null,
                            customer_billing_email: null,
                            customer_billing_phone: null,
                            customer_billing_country: "Bangladesh",
                        },
                    ],
                    page_data: [
                        {
                            page_title: "Checkout",
                            page_url: `${window.location.origin}/checkout`,
                            page_path: "/checkout",
                            referrer: document.referrer,
                            user_agent: window.navigator.userAgent,
                            session_id: session_id,
                            visitor_ip: customer_ip,
                        },
                    ],
                },
            });
        }
    };

    const purchaseTracker = async (
        data: IPurchaseTracker,
        info: IPersonalInfoTracker,
        code: string,
    ) => {
        if (window.dataLayer) {
            const customer = getSplitName(info?.name);
            window.dataLayer.push({
                event: "purchase",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    customer_type: data?.customer_type || "new",
                    coupon: data?.coupon || "",
                    value: data?.value,
                    shipping: data?.shipping || 0,
                    transaction_id: data?.transaction_id,
                    items: data?.items,
                },
                customer_data: [
                    {
                        page_post_author: name,
                        customer_first_name: customer?.firstName,
                        customer_last_name: customer?.lastName,
                        customer_billing_first_name: customer?.firstName,
                        customer_billing_last_name: customer?.lastName,
                        customer_billing_address: info?.address,
                        customer_billing_city: null,
                        customer_billing_email: info?.email,
                        customer_billing_phone: info?.phone,
                        customer_billing_country: "Bangladesh",
                    },
                ],
                page_data: [
                    {
                        page_title: "TrackOrder",
                        page_url: `${window.location.origin}/orders/${code}`,
                        page_path: `/orders/${code}`,
                        referrer: document.referrer,
                        user_agent: window.navigator.userAgent,
                        session_id: session_id,
                        visitor_ip: customer_ip,
                    },
                ],
                fbq: [
                    {
                        first_name: customer?.firstName,
                        first_name_hash: await sha256(
                            customer?.firstName ?? "",
                        ),

                        last_name: customer?.lastName,
                        last_name_hash: await sha256(customer?.lastName ?? ""),

                        email: info?.email,
                        email_hash: await sha256(info?.email ?? ""),

                        phone: info?.phone,
                        phone_hash: await sha256(info?.phone ?? ""),
                    },
                ],
            });
        }
    };

    return {
        addToCartTracker,
        beginCheckoutTracker,
        purchaseTracker,
        viewItemTracker,
        viewCartTracker,
    };
};
