import { Link } from "react-router-dom";
import { CartQuantity } from "./cart-quantity";
import type { ICartItem } from "@/type";
import { RemoveToCart } from "./remove-to-cart";
import { OptimizedImage } from "@/components/common/optimized-image";
import { usePrice } from "@/hooks/usePrice";
import { renderVariation } from "@/helper";

export const CartItem = ({ item }: { item: ICartItem }) => {
    const { getPriceWithCurrency } = usePrice();

    return (
        <div
            key={item?.product?.id}
            className="flex gap-3 md:gap-4 pb-4 border-b border-gray-100 last:border-0"
        >
            <Link to={`/products/${item?.product?.slug}`}>
                <div className="relative size-20 md:size-24 rounded-lg bg-gray-50 overflow-hidden shrink-0">
                    <OptimizedImage
                        src={item?.product?.image || ""}
                        className="absolute hover:scale-105 w-full h-full object-cover transition-all duration-200"
                        alt={item?.product?.name}
                    />
                </div>
            </Link>
            <div className="flex-1 min-w-0">
                <Link
                    to={`/products/${item?.product?.slug}`}
                    className="group relative inline-block max-w-full"
                >
                    <h3 className="text-sm font-medium text-gray-900 truncate after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[1px] after:w-0 after:bg-gray-900 after:transition-all after:duration-300 group-hover:after:w-full">
                        {item?.product?.name}
                    </h3>
                </Link>
                {item?.product?.variation && (
                    <p className="text-xs text-gray-800 py-0.5 font-medium">
                        {renderVariation(item.product.variation)}
                    </p>
                )}
                <p className="text-base font-semibold text-gray-950 mt-2">
                    {getPriceWithCurrency(item?.product?.price)}
                </p>
            </div>
            <div className="flex flex-col justify-between">
                <CartQuantity item={item} />
                <RemoveToCart item={item} type="drawer" />
            </div>
        </div>
    );
};
