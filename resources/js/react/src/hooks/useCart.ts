import type { ICartItem } from "@/type";
import { createContext, useContext } from "react";

export interface ICartAddToCart {
    product_id: number;
    quantity: number;
    temp_user_id?: string;
    campaign_id?: number | string | null;
    user_id?: string;
    variation?: {
        sku: string;
    };
}

export interface ICartSummary {
    subtotal: number;
    total_discount: number;
    total_tax: number;
    shipping_cost: number;
    total_item: number;
    total: number;
    coupon_discount: number;
    coupon_code: string;
    shipping_id: number;
    is_has_shipping?: boolean;
    needs_shipping_area?: boolean;
}

export interface CartContextType {
    setDrawerOpen: (open: boolean) => void;
    removeItem: (productId: number) => void;
    updateQuantity: (productId: number, quantity: number) => void;
    addItem: (p: ICartAddToCart, type?: string) => void;
    items: ICartItem[];
    summary: ICartSummary;
    drawerOpen: boolean;
    addingProductId: number | null;
    removingItemId: number | null;
    isRemoving: boolean;
    isUpdating: boolean;
}

export const CartContext = createContext<CartContextType | undefined>(
    undefined,
);

export const useCart = () => {
    const context = useContext(CartContext);
    if (!context) {
        throw new Error("useCart must be used within a CartProvider");
    }
    return context;
};
