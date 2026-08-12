import React, { useCallback } from "react";
import { WishlistContext, type IAddToWishlist } from "@/hooks/useWishlist";
import { useGetWishlist, useToggleWishlistMutation } from "@/api/wishlist";
import type { IWishlistItem } from "@/type";

interface Props {
    children: React.ReactNode;
}

export const WishListProvider: React.FC<Props> = ({ children }) => {
    const { data } = useGetWishlist();
    const toggleWishlistMutation = useToggleWishlistMutation();
    const items = (data?.data?.items as IWishlistItem[]) || [];

    const toggleItem = useCallback(
        (p: IAddToWishlist) => {
            toggleWishlistMutation.mutate(p);
        },
        [toggleWishlistMutation],
    );

    return (
        <WishlistContext.Provider
            value={{
                toggleItem,
                items,
                isPending: toggleWishlistMutation.isPending,
            }}
        >
            {children}
        </WishlistContext.Provider>
    );
};
