import { useState, useEffect, useMemo, useRef } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { AddToCart, BuyItNow } from "../_components/common/add-to-cart";
import { ProductAttributes } from "./attributes";
import { SocialShare } from "./social";
import { ProgressBar } from "./progress-bar";
import type {
    IAttribute,
    IAttributeOption,
    IProductDetails,
    IVariant,
} from "./type";
import { usePrice } from "@/hooks/usePrice";
import { useGtmTracker, type IItemTracker } from "@/hooks/useGtmTracker";
import { WishlistToggle } from "../_components/common/wishlist-toggle";
import { useSearchParams } from "react-router-dom";
import { renderStars } from "@/helper";
import { SocialMessage } from "../_components/common/social-contact";

interface Props {
    product: IProductDetails;
    onVariantImage: (src: string, alt: string) => void;
}

export const ProductInfo = ({ product, onVariantImage }: Props) => {
    const [params] = useSearchParams();
    const { getPriceWithCurrency } = usePrice();
    const { viewItemTracker } = useGtmTracker();
    const firedRef = useRef<string | null>(null);
    const [quantity, setQuantity] = useState(1);
    const [select, setSelect] = useState<Record<string, string>>(() => {
        const initial: Record<string, string> = {};
        if (product?.variants?.length) {
            const first = product?.variants?.[0];
            if (
                first?.attribute_value &&
                typeof first?.attribute_value === "object"
            ) {
                Object.entries(first?.attribute_value).forEach(
                    ([name, value]) => {
                        if (
                            value !== "" &&
                            value !== null &&
                            value !== undefined
                        ) {
                            initial[name] = value;
                        }
                    },
                );
            }
        }
        return initial;
    });

    const handleAttributeSelect = (
        attrName: string,
        option: IAttributeOption,
    ) => {
        setSelect((prev) => ({
            ...prev,
            [attrName]: option?.value,
        }));
    };

    const attributes = useMemo<IAttribute[]>(() => {
        if (!product?.variants?.length) return [];
        const map = new Map<string, Map<string, string | null>>();
        product?.variants?.forEach((v) => {
            if (!v?.attribute_value || typeof v?.attribute_value !== "object")
                return;
            Object.entries(v.attribute_value).forEach(([name, value]) => {
                if (value === "" || value === null || value === undefined)
                    return;
                if (!map.has(name)) map.set(name, new Map());
                const inner = map.get(name);
                if (!inner) return;
                if (!inner.has(value)) inner.set(value, v.image ?? null);
            });
        });
        return Array.from(map.entries()).map(([name, inner]) => ({
            name,
            values: Array.from(inner.entries()).map(([value, image]) => ({
                value,
                image,
            })),
        }));
    }, [product?.variants]);

    const currentVariant = useMemo<IVariant | null>(() => {
        if (!product?.variants?.length) return null;
        const selectedNames = Object.keys(select);
        if (!selectedNames.length) return null;
        return (
            product?.variants?.find((v) => {
                if (
                    !v?.attribute_value ||
                    typeof v?.attribute_value !== "object"
                )
                    return false;
                return selectedNames.every(
                    (name) => v?.attribute_value[name] === select[name],
                );
            }) ?? null
        );
    }, [product?.variants, select]);

    const displayPrice =
        currentVariant?.price || product?.price?.current || "0.00";
    const discountPrice = product?.price?.regular || "0.00";

    useEffect(() => {
        if (currentVariant?.image) {
            onVariantImage(currentVariant?.image, currentVariant?.sku || "");
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [currentVariant?.id, currentVariant?.image]);

    const maxStock = currentVariant?.stock ?? product?.inventory?.stock ?? 0;

    useEffect(() => {
        setQuantity((prev) => Math.min(prev, Math.max(1, maxStock)));
    }, [maxStock]);

    const handleDecrease = () => {
        setQuantity((prev) => Math.max(1, prev - 1));
    };

    const handleIncrease = () => {
        setQuantity((prev) => Math.min(maxStock, prev + 1));
    };

    const handleQuantityChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const val = parseInt(e.target.value, 10);
        if (isNaN(val) || val < 1) {
            setQuantity(1);
        } else {
            setQuantity(Math.min(val, maxStock));
        }
    };

    useEffect(() => {
        if (!product?.id) return;
        if (firedRef.current === product.id?.toString()) return;
        firedRef.current = product?.id?.toString();
        const trackerData: IItemTracker = {
            item_id: product?.id.toString() || "",
            item_name: product?.name || "",
            item_price: Number(displayPrice),
            item_quantity: quantity,
            item_brand: product?.brand?.name || "",
            item_category: product?.category?.name || "",
            item_variant: Object.values(select)?.join(" - ") || "",
        };
        viewItemTracker(trackerData);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [product?.id]);

    return (
        <div className="md:col-span-1 lg:col-span-6 md:sticky md:top-28 lg:sticky lg:top-28 h-fit space-y-4">
            <div>
                <h1 className="flex-1 text-3xl md:text-4xl font-semibold leading-tight">
                    {product?.name}
                </h1>

                <div className="flex items-center gap-4">
                    <div className="flex text-amber-500 text-2xl">
                        {renderStars(product?.review?.rating)}
                    </div>
                    <span className="text-gray-700 text-base">
                        {product?.review?.reviews_count || 0} • reviews
                    </span>
                </div>
            </div>
            <div className="flex items-center gap-3">
                <div className="text-2xl md:text-3xl font-semibold">
                    {getPriceWithCurrency(Number(displayPrice))}
                </div>
                {Number(discountPrice) > 0 &&
                    Number(discountPrice) > Number(displayPrice) && (
                        <div className="text-lg md:text-xl text-gray-400 line-through">
                            {getPriceWithCurrency(
                                Number(product?.price?.regular),
                            )}
                        </div>
                    )}
            </div>

            <Discount product={product} />

            {product?.short_description && (
                <p className="text-base text-gray-700">
                    {product?.short_description}
                </p>
            )}

            {product?.brand && (
                <div className="flex gap-2 text-base text-gray-700">
                    Brand:
                    <span className="font-semibold">
                        {product?.brand?.name}
                    </span>
                </div>
            )}

            {attributes?.length > 0 && (
                <ProductAttributes
                    attributes={attributes}
                    select={select}
                    onSelect={handleAttributeSelect}
                />
            )}

            {product?.category?.name && (
                <BoxItem label="Category" value={product?.category?.name} />
            )}

            <ProgressBar
                stock={currentVariant?.stock ?? product?.inventory?.stock}
                sold={product?.inventory?.total_sold ?? 0}
                unit={product?.inventory?.unit || "piece"}
            />

            <div className="pt-4 border-t border-gray-200 space-y-3">
                <div className="flex items-center gap-2">
                    <div className="flex items-center py-1.5  border border-gray-300 rounded-full gap-3">
                        <button
                            type="button"
                            title="Decrease Quantity"
                            onClick={handleDecrease}
                            disabled={quantity <= 1}
                            className="flex items-center justify-center text-gray-900 p-2 transition-colors duration-200 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <ChevronLeft className="size-5" />
                        </button>
                        <input
                            type="number"
                            title="Quantity"
                            min={1}
                            max={maxStock}
                            value={quantity}
                            onChange={handleQuantityChange}
                            className="w-7 text-center bg-transparent border-none outline-none p-0 focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        />

                        <button
                            type="button"
                            title="Increase Quantity"
                            onClick={handleIncrease}
                            disabled={quantity >= maxStock}
                            className="p-2 flex items-center justify-center text-gray-900 transition-colors duration-200 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed"
                        >
                            <ChevronRight className="size-5" />
                        </button>
                    </div>
                    <AddToCart
                        p={{
                            product_id: product?.id,
                            quantity: quantity,
                            campaign_id: params?.get("c") ?? null,
                            variation: currentVariant?.sku
                                ? { sku: currentVariant?.sku }
                                : undefined,
                        }}
                        isInStock={
                            (currentVariant && currentVariant?.stock > 0) ||
                            product?.inventory?.stock > 0
                        }
                        type="DETAILS"
                        trackerData={{
                            item_id: product?.id.toString() || "",
                            item_name: product?.name || "",
                            item_price: Number(displayPrice) || 0,
                            item_quantity: quantity,
                            item_brand: product?.brand?.name || "",
                            item_category: product?.category?.name || "",
                            item_variant:
                                Object.values(select)?.join(" - ") || "",
                        }}
                    />
                </div>
                <div className="flex justify-center items-center gap-2">
                    <WishlistToggle id={product?.id} type={true} />
                    <BuyItNow
                        p={{
                            product_id: product?.id,
                            quantity: quantity,
                            campaign_id: params?.get("c") ?? null,
                            variation: currentVariant?.sku
                                ? { sku: currentVariant?.sku }
                                : undefined,
                        }}
                    />
                </div>
                <SocialMessage type="details" />
            </div>

            <div className="grid grid-cols-3 gap-2">
                <div className="flex flex-col items-center gap-2 rounded-xl border border-orange-100 bg-orange-50 px-2 py-3 text-center">
                    <div className="text-2xl md:text-3xl">🚚</div>
                    <span className="text-xs font-medium text-gray-700">
                        Fast Shipping
                    </span>
                </div>

                <div className="flex flex-col items-center gap-2 rounded-xl border border-blue-100 bg-blue-50 px-2 py-3 text-center">
                    <div className="text-2xl md:text-3xl">🛡️</div>
                    <span className="text-xs font-medium text-gray-700">
                        Secure Payment
                    </span>
                </div>

                <div className="flex flex-col items-center gap-2 rounded-xl border border-green-100 bg-green-50 px-2 py-3 text-center">
                    <div className="text-2xl md:text-3xl">🏅</div>
                    <span className="text-xs font-medium text-gray-700">
                        100% Authentic
                    </span>
                </div>
            </div>
            <SocialShare title={product?.name} image={product?.thumbnail} />
        </div>
    );
};

const BoxItem = ({ label, value }: { label: string; value: string }) => {
    return (
        <div className="px-4 py-2 rounded-lg border flex items-center justify-between gap-1">
            {label}
            <span className="text-green-500">{value}</span>
        </div>
    );
};

const Discount = ({ product }: { product: IProductDetails }) => {
    const { getPriceWithCurrency } = usePrice();
    if (!product?.price?.discount) return null;
    if (Number(product?.price?.discount) === 0) return null;

    return (
        <div className="flex items-center gap-2">
            You save{" "}
            {product?.price?.discount_type === "percent" ? (
                <div className="text-lg md:text-xl font-semibold text-primary">
                    {product?.price?.discount}%
                </div>
            ) : (
                <div className="text-lg md:text-xl font-semibold text-primary">
                    {getPriceWithCurrency(Number(product?.price?.discount))}
                </div>
            )}
        </div>
    );
};
