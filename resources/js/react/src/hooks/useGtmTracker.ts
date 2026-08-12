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
    const { getCurrencyExtension } = usePrice();

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

    const beginCheckoutTracker = (data: IPurchaseTracker) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "begin_checkout",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.value,
                    coupon: data?.coupon,
                    items: data?.items,
                },
            });
        }
    };

    const purchaseTracker = (
        data: IPurchaseTracker,
        info: IPersonalInfoTracker,
    ) => {
        if (window.dataLayer) {
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
                personal_data: info,
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
                },
            });
        }
    };

    const viewItemTracker = (data: IItemTracker) => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "view_item",
                ecommerce: {
                    currency: getCurrencyExtension(),
                    value: data?.item_price * data?.item_quantity,
                    items: [{ ...data }],
                },
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
