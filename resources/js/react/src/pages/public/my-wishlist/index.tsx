import { MoveRight, Eye, Trash2 } from "lucide-react";
import { Link } from "react-router-dom";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { OptimizedImage } from "@/components/common/optimized-image";
import { NoDataFound } from "@/components/common/no-data-found";
import { CartIcon } from "../_components/common/icon";
import { ProductLayout } from "../_components/common/layout";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import { useWishlist } from "@/hooks/useWishlist";
import { useCart, type ICartAddToCart } from "@/hooks/useCart";
import { usePrice } from "@/hooks/usePrice";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { getAuthUserId, getTempUserId, getUUID, setCookie } from "@/helper";
import { TEMP_USER_ID } from "@/constant";
import type { IProduct } from "@/type";

export const MyWishlistPage = () => {
    const { getPriceWithCurrency } = usePrice();
    const { items, toggleItem, isPending } = useWishlist();

    return (
        <>
            <SeoWrapper title="My Wishlist" description="View your wishlist" />
            <BaseLayout>
                <LayoutContainer className="mb-16 md:mb-24">
                    <div className="flex justify-between items-center">
                        <BreadcrumbWrapper
                            className="my-4"
                            items={[
                                {
                                    title: "My Wishlist",
                                    path: "/my-wishlist",
                                },
                                { title: "My Cart", path: "/my-cart" },
                            ]}
                        />
                        {items?.length > 0 && (
                            <Link to="/products">
                                <Button variant="outline" className="gap-2">
                                    Continue Shopping
                                    <MoveRight className="size-4" />
                                </Button>
                            </Link>
                        )}
                    </div>

                    {items?.length === 0 ? (
                        <NoDataFound
                            title="Your wishlist is empty"
                            description="Save items you love and come back to them anytime!"
                            height="min-h-[400px]"
                        />
                    ) : (
                        <ProductLayout>
                            {items?.map((item) => {
                                const p = item?.product || {};
                                return (
                                    <div
                                        key={item?.id}
                                        className="group bg-white shadow-lg rounded-xl border overflow-hidden flex flex-col transition-all duration-300"
                                    >
                                        <div className="relative aspect-square bg-muted/20 overflow-hidden">
                                            <Link to={`/products/${p?.slug}`}>
                                                <OptimizedImage
                                                    src={p?.image}
                                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                />
                                            </Link>

                                            {p?.discount > 0 && (
                                                <Badge className="absolute top-2 left-2 bg-red-500 text-white border-0">
                                                    -{p?.discount}%
                                                </Badge>
                                            )}

                                            <div className="absolute top-1 right-1 md:top-2 md:right-2 flex flex-col gap-1.5 md:opacity-0 opacity-100 md:translate-x-2 md:group-hover:opacity-100 md:group-hover:translate-x-0 transition-all duration-300 z-10">
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        toggleItem({
                                                            product_id: p?.id,
                                                        })
                                                    }
                                                    disabled={isPending}
                                                    title="Remove from wishlist"
                                                    className="size-8 rounded-full bg-white/90 backdrop-blur flex items-center justify-center text-muted-foreground hover:text-red-500 transition-all cursor-pointer hover:bg-red-100 disabled:pointer-events-none"
                                                >
                                                    {isPending ? (
                                                        <span className="text-[10px]">
                                                            ...
                                                        </span>
                                                    ) : (
                                                        <Trash2 className="size-3.5" />
                                                    )}
                                                </button>
                                            </div>

                                            {!p?.in_stock && (
                                                <div className="absolute inset-0 bg-black/50 backdrop-blur-[2px] flex items-center justify-center">
                                                    <Badge
                                                        variant="destructive"
                                                        className="text-xs rounded-2xl"
                                                    >
                                                        Out of Stock
                                                    </Badge>
                                                </div>
                                            )}
                                        </div>

                                        <div className="p-2 md:p-3 flex flex-col flex-1">
                                            <Link to={`/products/${p?.slug}`}>
                                                <h3 className="text-xs md:text-sm font-medium text-foreground line-clamp-1 hover:text-primary transition-colors leading-snug">
                                                    {p?.name}
                                                </h3>
                                            </Link>

                                            <div className="flex items-center gap-1.5 mt-1.5">
                                                <span className="text-sm md:text-base font-bold text-foreground">
                                                    {getPriceWithCurrency(
                                                        p?.price,
                                                    )}
                                                </span>
                                                {p?.original > p?.price && (
                                                    <span className="text-[10px] md:text-xs text-muted-foreground line-through">
                                                        {getPriceWithCurrency(
                                                            p?.original,
                                                        )}
                                                    </span>
                                                )}
                                            </div>

                                            <div className="mt-1.5">
                                                {p?.in_stock ? (
                                                    <AddToCartWhishList p={p} />
                                                ) : (
                                                    <Link
                                                        to={`/products/${p?.slug}`}
                                                        className="flex w-full items-center justify-center gap-1.5 rounded-full h-8 md:h-10 font-medium bg-primary/10 text-primary hover:bg-primary/20 transition-colors text-sm md:text-base"
                                                    >
                                                        <Eye className="size-5" />
                                                        View Product
                                                    </Link>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </ProductLayout>
                    )}
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};

const AddToCartWhishList = ({ p }: { p: IProduct }) => {
    const { addItem, addingProductId } = useCart();
    const isAdding = addingProductId === p.id;

    const handleAddToCart = (product_id: number) => {
        const data: ICartAddToCart = {
            product_id,
            quantity: 1,
        };

        const authUserId = getAuthUserId();

        if (authUserId) {
            data.user_id = authUserId;
        } else {
            let tempId = getTempUserId();

            if (!tempId) {
                const newUuid = getUUID();
                setCookie(TEMP_USER_ID, newUuid);
                tempId = newUuid;
            }

            data.temp_user_id = tempId;
        }

        addItem(data);
    };

    return p?.has_variants ? (
        <Link
            to={`/products/${p?.slug}`}
            className="w-full h-8 md:h-10 backdrop-blur rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base font-normal bg-primary/90 hover:bg-primary text-primary-foreground disabled:pointer-events-none"
        >
            <CartIcon />
            Add to cart
        </Link>
    ) : (
        <button
            type="button"
            onClick={() => handleAddToCart(p?.id)}
            disabled={isAdding}
            className="w-full h-8 md:h-10 backdrop-blur rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-sm md:text-base font-normal bg-primary/90 hover:bg-primary text-primary-foreground disabled:pointer-events-none"
        >
            <CartIcon />
            {isAdding ? "Adding..." : "Add to cart"}
        </button>
    );
};
