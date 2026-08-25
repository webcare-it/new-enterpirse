import { useCart } from "@/hooks/useCart";
import type { ICartItem } from "@/type";
import { Trash2 } from "lucide-react";

export const RemoveToCart = ({
    item,
    type,
}: {
    item: ICartItem;
    type?: string;
}) => {
    const { removeItem, removingItemId } = useCart();
    const isRemoving = removingItemId === item.id;
    const handleRemove = () => {
        removeItem(item.id);
    };

    if (type === "cart")
        return (
            <div className="flex justify-end mt-2 md:mt-0">
                <button
                    onClick={handleRemove}
                    type="button"
                    disabled={isRemoving}
                    title="Remove Item"
                    className=" bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors duration-200 cursor-pointer p-1.5 md:p-3 rounded-md md:rounded-lg"
                >
                    {isRemoving ? "Removing..." : <Trash2 className="size-5" />}
                </button>
            </div>
        );

    if (type === "drawer")
        return (
            <div className="text-end">
                <button
                    onClick={handleRemove}
                    disabled={isRemoving}
                    type="button"
                    title="Remove Item"
                    className={`text-xs font-medium text-gray-900 hover:text-red-600  transition-all duration-100 cursor-pointer animated-underline ${
                        isRemoving && "pointer-events-none"
                    }`}
                >
                    {isRemoving ? "Removing..." : "Remove"}
                </button>
            </div>
        );
};
