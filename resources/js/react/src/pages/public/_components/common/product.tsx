import { ChartNoAxesCombined } from "lucide-react";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { ICampaign, IProduct } from "@/type";
import { Link } from "react-router-dom";
import { AddToCart, AddToCartLink } from "./add-to-cart";
import { usePrice } from "@/hooks/usePrice";
import type { IItemTracker } from "@/hooks/useGtmTracker";
import { WishlistToggle } from "./wishlist-toggle";

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
                className="bg-white rounded-lg md:rounded-xl overflow-hidden border border-gray-300 transition shadow md:shadow-lg"
            >
                <div className="relative bg-muted/20 aspect-[16/18] flex items-center justify-center overflow-hidden group/product">
                    <Link to={link} className="absolute z-0 inset-0">
                        <OptimizedImage
                            src={p?.image || ""}
                            className="absolute w-full h-full transition duration-700 ease-out z-0"
                            alt="Product Image"
                        />
                    </Link>

                    {campaign ? (
                        <CampaignDiscount c={campaign} p={p} />
                    ) : (
                        <RegularDiscount p={p} />
                    )}

                    <WishlistToggle id={Number(p?.id)} />

                    <div
                        aria-label={`Rating: ${p?.rating} out of 5`}
                        className="absolute top-2 right-2 bg-white backdrop-blur px-1.5 transition-all duration-300 z-10 text-gray-900 flex items-center gap-1 rounded-sm opacity-100 scale-100 pointer-events-none group-hover/product:opacity-0 group-hover/product:scale-75"
                    >
                        <span className="text-sm font-semibold text-yellow-400">
                            ★
                        </span>
                        <span className="text-xs">{p?.rating}</span>
                    </div>

                    {/* <div className="absolute inset-x-0 bottom-0 h-full translate-y-full bg-gradient-to-t from-black/30 via-black/15 to-transparent opacity-0 transition-all duration-500 ease-out group-hover/product:translate-y-0 group-hover/product:opacity-100" /> */}

                    {/* <div className=" hidden md:inline-flex items-center justify-center opacity-0 translate-y-8 transition-all duration-500 ease-out group-hover/product:opacity-100 group-hover/product:translate-y-0">
                        <button
                            title="Quick View"
                            onClick={() => setQuickViewOpen(true)}
                            className="size-10 md:size-12 rounded-full bg-white/90 backdrop-blur p-1.5 cursor-pointer text-gray-900 flex items-center justify-center transition duration-300 hover:bg-white hover:scale-105"
                        >
                            <Eye className="size-5 md:size-6" />
                        </button>
                    </div> */}

                    {p?.has_variants ? (
                        <AddToCartLink slug={link} />
                    ) : (
                        <AddToCart
                            p={{
                                product_id: p?.id,
                                quantity: 1,
                                campaign_id: campaign?.id,
                            }}
                            trackerData={trackerData}
                        />
                    )}
                </div>

                <div className="p-1.5 md:p-3 select-none">
                    <Link
                        to={link}
                        className="relative inline-block max-w-full"
                    >
                        <h3 className="text-sm font-medium md:text-base md:font-semibold text-gray-900 truncate after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[1px] after:w-0 after:bg-gray-900 after:transition-all after:duration-300 hover:after:w-full">
                            {p?.name}
                        </h3>
                    </Link>

                    <div className="flex items-center justify-between gap-1 md:gap-2">
                        <RegularPrice p={p} />

                        <div className="flex items-center gap-0.5 md:gap-1.5 px-1 md:px-2 py-0.5 md:py-1 rounded md:rounded-md bg-primary/10 text-[9px] md:text-xs font-medium text-primary border">
                            <ChartNoAxesCombined className="size-2.5 md:size-3.5" />
                            <span className="font-semibold">{p?.sold}</span>
                            sold
                        </div>
                    </div>
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
                    {" "}
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

    return (
        <div className="absolute top-2 left-2 bg-red-500  text-white px-1.5 py-0.5 rounded-sm font-semibold text-xs">
            {amount}
        </div>
    );
};
const CampaignDiscount = ({ c }: ICP) => {
    const { getCurrencySymbol } = usePrice();
    if (!c || c?.status !== "active") return null;

    const label =
        c.discount_type === "percent"
            ? `-${c.discount_amount}%`
            : `-${getCurrencySymbol()}${c?.discount_amount}`;

    return (
        <div className="absolute top-2 left-2 rounded-sm bg-red-500 px-1.5 py-0.5 text-xs font-semibold text-white">
            {label}
        </div>
    );
};
