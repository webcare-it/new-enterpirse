import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { ProductLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import { MyCartItems } from "./items";
import type { IProduct } from "@/type";
import { useCart } from "@/hooks/useCart";
import { useCartRelatedProducts } from "@/api/cart";
import { MyCartSummary } from "./summary";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { EmptyCart } from "../_components/common/empty-cart";
import { useEffect, useRef } from "react";
import {
    useGtmTracker,
    type IViewCartTrackerType,
} from "@/hooks/useGtmTracker";
import { renderVariation } from "@/helper";

export const MyCartPage = () => {
    const firedRef = useRef(false);
    const { items, summary } = useCart();
    const { viewCartTracker } = useGtmTracker();
    const { data, isLoading } = useCartRelatedProducts();
    const products = (data?.data?.related_products as IProduct[]) || [];

    useEffect(() => {
        if (firedRef.current) return;
        if (items?.length > 0) {
            firedRef.current = true;
            const d: IViewCartTrackerType = {
                value: summary?.subtotal || 0,
                items: items?.map((item, i) => ({
                    item_id: item?.product?.id?.toString() || "",
                    item_name: item?.product?.name || "",
                    item_price: item?.product?.price || 0,
                    item_quantity: item?.product?.quantity || 0,
                    item_variant:
                        renderVariation(item?.product?.variation) || "",
                    index: i || 0,
                })),
            };
            viewCartTracker(d);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [items]);

    return (
        <>
            <SeoWrapper title="My Cart" description="View your shopping cart" />
            <BaseLayout>
                <LayoutContainer className="pb-6 md:pb-10">
                    <BreadcrumbWrapper
                        className="my-4"
                        items={[
                            {
                                title: "My Cart",
                                path: "/my-cart",
                            },
                            { title: "Checkout", path: "/checkout" },
                        ]}
                    />

                    {items?.length === 0 ? (
                        <EmptyCart />
                    ) : (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-6">
                                <MyCartItems products={items} />

                                <MyCartSummary />
                            </div>
                            {/* You may also like */}
                            {isLoading ? (
                                <div className="my-16 md:my-20 flex justify-center items-center h-[250px]">
                                    <div className="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary"></div>
                                </div>
                            ) : (
                                products?.length > 0 && (
                                    <div className="my-16 md:my-20 ">
                                        <div className="border-t pb-6" />
                                        <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground mt-1 transition-all duration-500 ease-out hover:tracking-wide hover:text-primary capitalize text-center mb-6 md:mb-8">
                                            You may also like
                                        </h2>
                                        <ProductLayout>
                                            {products?.map((p, i: number) => (
                                                <AnimationWrapper
                                                    key={p.id}
                                                    initial={{
                                                        opacity: 0,
                                                        y: 40,
                                                    }}
                                                    whileInView={{
                                                        opacity: 1,
                                                        y: 0,
                                                    }}
                                                    transition={{
                                                        duration: 0.35,
                                                        delay: i * 0.03,
                                                    }}
                                                >
                                                    <ProductCard
                                                        key={p.id}
                                                        p={p}
                                                    />
                                                </AnimationWrapper>
                                            ))}
                                        </ProductLayout>
                                    </div>
                                )
                            )}
                        </>
                    )}
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
