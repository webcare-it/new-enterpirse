import { useCart } from "@/hooks/useCart";
import type { ICartItem } from "@/type";
import { Minus, Plus } from "lucide-react";

export const CartQuantity = ({ item }: { item: ICartItem }) => {
    const { updateQuantity, isUpdating } = useCart();

    const quantity = item.product.quantity;

    const handleQuantity = (e: React.ChangeEvent<HTMLInputElement>) => {
        const value = Number(e.target.value);

        if (value >= 1) {
            updateQuantity(item.id, value);
        }
    };

    const handleUpdate = (newQuantity: number) => {
        if (newQuantity < 1) return;

        updateQuantity(item.id, newQuantity);
    };

    return (
        <div className="flex items-center rounded-xl bg-gray-100 px-3 py-2 gap-2">
            <button
                type="button"
                title="Decrease Quantity"
                disabled={isUpdating || quantity <= 1}
                onClick={() => handleUpdate(quantity - 1)}
                className="flex size-5 cursor-pointer items-center justify-center text-gray-900 transition-colors duration-200 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Minus className="size-4" />
            </button>

            <input
                type="number"
                title="Quantity"
                min={1}
                value={quantity}
                onChange={handleQuantity}
                disabled={isUpdating}
                className="w-8 bg-transparent p-0 text-center outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
            />

            <button
                type="button"
                title="Increase Quantity"
                disabled={isUpdating}
                onClick={() => handleUpdate(quantity + 1)}
                className="flex size-5 cursor-pointer items-center justify-center text-gray-900 transition-colors duration-200 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Plus className="size-4" />
            </button>
        </div>
    );
};
