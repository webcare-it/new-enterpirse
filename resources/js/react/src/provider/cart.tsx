import React, { useState, useCallback } from "react";
import {
    CartContext,
    type ICartAddToCart,
    type ICartSummary,
} from "@/hooks/useCart";
import { CartDrawer } from "@/pages/public/_components/common/cart-drawer";
import {
    useAddToCartMutation,
    useRemoveCartMutation,
    useUpdateCartMutation,
    useGetCart,
} from "@/api/cart";
import type { ICartItem } from "@/type";
import { useNavigate } from "react-router-dom";

interface CartProviderProps {
    children: React.ReactNode;
}

export const CartProvider: React.FC<CartProviderProps> = ({ children }) => {
    const { data } = useGetCart();
    const navigate = useNavigate();
    const [drawerOpen, setDrawerOpen] = useState(false);
    const items = (data?.data?.items as ICartItem[]) || [];
    const summary = (data?.data?.summary as ICartSummary) || {};

    const addToCartMutation = useAddToCartMutation();
    const removeCartMutation = useRemoveCartMutation();
    const updateCartMutation = useUpdateCartMutation();
    const [addingProductId, setAddingProductId] = useState<number | null>(null);

    const addItem = useCallback(
        (p: ICartAddToCart, type?: string) => {
            setAddingProductId(p.product_id);
            addToCartMutation.mutate(p, {
                onSuccess: () => {
                    if (type && type === "CHECKOUT") {
                        navigate("/checkout");
                    } else {
                        setDrawerOpen(true);
                    }
                },
                onSettled: () => {
                    setAddingProductId(null);
                },
            });
        },
        [addToCartMutation, navigate],
    );

    const removeItem = useCallback(
        (cartId: number) => {
            removeCartMutation.mutate({ id: cartId });
        },
        [removeCartMutation],
    );

    const updateQuantity = useCallback(
        (cartId: number, quantity: number) => {
            if (quantity <= 0) return;
            updateCartMutation.mutate({ id: cartId, quantity });
        },
        [updateCartMutation],
    );

    return (
        <CartContext.Provider
            value={{
                addItem,
                removeItem,
                updateQuantity,
                drawerOpen,
                setDrawerOpen,
                items,
                summary,
                addingProductId,
                isRemoving: removeCartMutation.isPending,
                isUpdating: updateCartMutation.isPending,
            }}
        >
            {children}
            <CartDrawer />
        </CartContext.Provider>
    );
};
