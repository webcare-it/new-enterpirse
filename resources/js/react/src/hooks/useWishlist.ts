import type { IWishlistItem } from "@/type";
import { createContext, useContext } from "react";

export interface IAddToWishlist {
    product_id: number;
}

export interface IWishlistContext {
    isPending: boolean;
    items: IWishlistItem[];
    toggleItem: (p: IAddToWishlist) => void;
}

export const WishlistContext = createContext<IWishlistContext | undefined>(
    undefined,
);

export const useWishlist = () => {
    const context = useContext(WishlistContext);
    if (!context) {
        throw new Error("useWishlist must be used within a WishListProvider");
    }
    return context;
};
