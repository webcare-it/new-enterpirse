import { Link } from "react-router-dom";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { ICartItem } from "@/type";
import { CartQuantity } from "../_components/common/cart-quantity";
import { RemoveToCart } from "../_components/common/remove-to-cart";
import { usePrice } from "@/hooks/usePrice";
import { renderVariation } from "@/helper";

export const MyCartItems = ({ products }: { products: ICartItem[] }) => {
    const { getPriceWithCurrency } = usePrice();

    return (
        <div className="md:col-span-2 space-y-0">
            {products?.map((item) => (
                <div
                    key={item.id}
                    className="flex items-start md:items-center justify-between gap-3 py-4 md:py-5 border-gray-200 border-b last:border-b-0"
                >
                    <div className="flex items-start flex-1 gap-3">
                        <div className="size-[72px] md:size-[88px] rounded-lg bg-muted/30 overflow-hidden shrink-0">
                            <OptimizedImage
                                src={item?.product?.image}
                                className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        <div className="min-w-0">
                            <Link
                                to={`/products/${item?.product?.slug}`}
                                className="text-sm md:text-base font-medium text-gray-900 hover:text-primary transition-colors duration-200 line-clamp-1 hover:underline"
                            >
                                {item?.product?.name}
                            </Link>
                            {item?.product?.variation && (
                                <p className="text-sm text-gray-800 py-1 font-medium">
                                    {renderVariation(item?.product?.variation)}
                                </p>
                            )}
                            <span className="text-base font-semibold text-gray-900">
                                {getPriceWithCurrency(item?.product?.price)}
                            </span>
                        </div>
                    </div>

                    <div className="flex flex-col md:flex-row md:gap-4 md:items-center justify-between">
                        <CartQuantity item={item} />
                        <RemoveToCart item={item} type="cart" />
                    </div>

                    <div className="hidden md:block w-[90px] text-right shrink-0">
                        <div className="text-sm md:text-base font-bold text-foreground">
                            {getPriceWithCurrency(
                                item?.product?.price * item?.product?.quantity,
                            )}
                        </div>
                    </div>
                </div>
            ))}
        </div>
    );
};
