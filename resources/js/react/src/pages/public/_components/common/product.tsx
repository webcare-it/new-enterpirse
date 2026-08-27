import { ChartNoAxesCombined } from "lucide-react";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { ICampaign, IProduct } from "@/type";
import { Link } from "react-router-dom";
import { AddToCart, AddToCartLink } from "./add-to-cart";
import { usePrice } from "@/hooks/usePrice";
import type { IItemTracker } from "@/hooks/useGtmTracker";
import { WishlistToggle } from "./wishlist-toggle";
import { StockOutIcon } from "./icon";

interface Props {
    p: IProduct;
    campaign?: ICampaign | null;
}

export const ProductCard = ({ p, campaign = null }: Props) => {
    const trackerData: IItemTracker = {
        item_id: p?.id.toString() || "",
        item_name: p?.name || "",
        item_price: p?.price || 0,
        item_quantity: 1,
        item_brand: p?.brand || "",
        item_category: p?.category || "",
    };

    const link = `/products/${p?.slug}${campaign ? "?c=" + campaign?.id : ""}`;

    return (
        <>
            <div
                key={p?.id}
                className="bg-white rounded-xl md:rounded-2xl overflow-hidden border border-gray-300 transition shadow md:shadow-lg"
            >
                <div className="relative bg-muted/20 aspect-[16/17] flex items-center justify-center overflow-hidden group/product">
                    <Link to={link} className="absolute z-0 inset-0">
                        <OptimizedImage
                            src={p?.image || ""}
                            className="absolute w-full h-full transition-transform duration-500 ease-out hover:scale-110 z-0"
                            alt="Product Image"
                        />
                    </Link>

                    {campaign ? (
                        <CampaignDiscount c={campaign} p={p} />
                    ) : (
                        <RegularDiscount p={p} />
                    )}

                    <WishlistToggle id={Number(p?.id)} />
                </div>

                <div className="p-1.5 md:p-3 select-none">
                    <Link to={link}>
                        <div className="flex items-center justify-between gap-2">
                            <div
                                aria-label={`Rating: ${p?.rating} out of 5`}
                                className="flex items-center gap-0.5 md:gap-1.5 py-1 px-2 rounded-full bg-gray-100 text-xs font-medium text-yellow-400"
                            >
                                <svg
                                    className="size-3 md:size-4"
                                    viewBox="0 0 16 16"
                                    stroke="none"
                                    fill="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    role="presentation"
                                >
                                    <path d="M8 0L9.88914 5.81283H16L11.056 9.40604L12.9452 15.2177L8 11.6245L3.05603 15.2177L4.94397 9.40484L0 5.81163H6.11086L8 0Z" />
                                </svg>
                                <span className="text-sm text-gray-900">
                                    {p?.rating}
                                </span>
                            </div>
                            <div className="flex items-center gap-0.5 md:gap-1.5 px-2 py-1 rounded-full bg-primary/10 text-xs font-medium text-primary">
                                <ChartNoAxesCombined className="size-3 md:size-4" />
                                <span className="font-semibold">{p?.sold}</span>
                                sold
                            </div>
                        </div>
                        <div className="relative inline-block max-w-full">
                            <h3 className="text-sm font-medium md:text-base md:font-semibold text-gray-900 truncate after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[1px] after:w-0 after:bg-gray-900 after:transition-all after:duration-300 hover:after:w-full mt-1">
                                {p?.name}
                            </h3>
                        </div>
                    </Link>

                    <RegularPrice p={p} />

                    {!p?.in_stock ? (
                        <OutOfStock />
                    ) : p?.has_variants ? (
                        <AddToCartLink slug={link} />
                    ) : (
                        <AddToCart
                            p={{
                                product_id: p?.id,
                                quantity: 1,
                                campaign_id: campaign?.id,
                            }}
                            isInStock={p?.in_stock}
                            trackerData={trackerData}
                        />
                    )}
                </div>
            </div>
        </>
    );
};

const RegularPrice = ({ p }: { p: IProduct }) => {
    const { getPriceWithCurrency } = usePrice();

    return (
        <div className="flex items-baseline flex-wrap gap-1 md:gap-2">
            {p?.price_range && Object.keys(p?.price_range).length > 0 ? (
                <span className="text-[15px] md:text-lg font-bold text-primary">
                    {getPriceWithCurrency(p?.price_range?.min)} -{" "}
                    {getPriceWithCurrency(p?.price_range?.max)}
                </span>
            ) : (
                <>
                    <span className="text-[15px] md:text-lg font-bold text-primary">
                        {getPriceWithCurrency(p?.price)}
                    </span>
                    {p?.original !== p?.price && (
                        <span className="text-xs md:text-sm font-medium text-gray-600 line-through">
                            {getPriceWithCurrency(p?.original)}
                        </span>
                    )}{" "}
                </>
            )}
        </div>
    );
};

interface ICP {
    p: IProduct;
    c: ICampaign;
}

const RegularDiscount = ({ p }: { p: IProduct }) => {
    const { getCurrencySymbol } = usePrice();
    if (!p?.discount) return null;
    if (p?.discount === 0) return null;

    const amount =
        p?.discount_type === "percent"
            ? `-${p?.discount}%`
            : `-${getCurrencySymbol()}${p?.discount}`;

    return <DiscountLabel>{amount}</DiscountLabel>;
};
const CampaignDiscount = ({ c }: ICP) => {
    const { getCurrencySymbol } = usePrice();
    if (!c || c?.status !== "active") return null;

    const label =
        c.discount_type === "percent"
            ? `-${c.discount_amount}%`
            : `-${getCurrencySymbol()}${c?.discount_amount}`;

    return <DiscountLabel>{label}</DiscountLabel>;
};

const DiscountLabel = ({ children }: { children: string }) => {
    return (
        <div className="absolute top-2 left-2 bg-red-500  text-white px-1.5 py-0.5 rounded-sm font-semibold text-xs">
            {children}
        </div>
    );
};

const OutOfStock = () => (
    <button className="rounded-3xl w-full transition-all duration-300 cursor-not-allowed h-10 md:h-12 flex items-center justify-center bg-red-100 text-red-600 hover:text-red-600 border text-sm md:text-base border-red-600 gap-1 md:gap-2 mt-1">
        <StockOutIcon />
        Out of stock
    </button>
);
