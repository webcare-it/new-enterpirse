import { Heart } from "lucide-react";
import { useWishlist } from "@/hooks/useWishlist";
import { isAuthenticated } from "@/helper";
import toast from "react-hot-toast";

export const WishlistToggle = ({
    id,
    type,
}: {
    id: number;
    type?: boolean;
}) => {
    const { items, isPending, toggleItem } = useWishlist();
    const isWishlisted = items?.some((item) => item?.product?.id === id);

    const handleToggle = () => {
        if (!isAuthenticated()) {
            toast.error("Please login to add to wishlist");
            return;
        }
        toggleItem({ product_id: id });
    };

    if (type) {
        return (
            <button
                onClick={handleToggle}
                disabled={isPending}
                aria-label={`${isWishlisted ? "Remove" : "Add"} ${id} ${isWishlisted ? "from" : "to"} wishlist`}
                className="bg-gray-100 backdrop-blur p-3 rounded-full transition-all duration-300 cursor-pointer hover:bg-red-100 text-gray-900 hover:text-red-600"
            >
                <Heart
                    className={`size-5 ${isWishlisted ? "fill-red-500 text-red-500" : ""}`}
                />
            </button>
        );
    }

    return (
        <button
            onClick={handleToggle}
            disabled={isPending}
            aria-label={`${isWishlisted ? "Remove" : "Add"} ${id} ${isWishlisted ? "from" : "to"} wishlist`}
            className="absolute top-2 right-2 bg-white backdrop-blur p-1.5 rounded-full transition-all duration-300 z-20 cursor-pointer hover:bg-red-100 text-gray-900 hover:text-red-600 opacity-0 scale-75 group-hover/product:opacity-100 group-hover/product:scale-100"
        >
            <Heart
                className={`size-4 ${isWishlisted ? "fill-red-500 text-red-500" : ""}`}
            />
        </button>
    );
};
