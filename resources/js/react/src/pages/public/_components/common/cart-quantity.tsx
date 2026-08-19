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
        <div className="flex items-center gap-.5 rounded-full bg-gray-100">
            <button
                type="button"
                title="Decrease Quantity"
                disabled={isUpdating || quantity <= 1}
                onClick={() => handleUpdate(quantity - 1)}
                className="flex size-8 cursor-pointer items-center justify-center rounded-l-full bg-gray-300 text-gray-900 transition-all duration-100 hover:bg-gray-400 disabled:cursor-not-allowed disabled:opacity-50"
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
                className="w-6 bg-transparent p-0 text-center font-medium outline-none [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none"
            />

            <button
                type="button"
                title="Increase Quantity"
                disabled={isUpdating}
                onClick={() => handleUpdate(quantity + 1)}
                className="flex size-8 cursor-pointer items-center justify-center rounded-r-full bg-gray-300 text-gray-900 transition-all duration-100 hover:bg-gray-400 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <Plus className="size-4" />
            </button>
        </div>
    );
};
