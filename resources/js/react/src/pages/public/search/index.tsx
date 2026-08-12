import { useSearchParams } from "react-router-dom";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { useSearchProducts } from "@/api/product";
import { ProductLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { NoDataFound } from "@/components/common/no-data-found";
import { useEffect, useRef } from "react";
import type { IProduct } from "@/type";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const ProductsSearchPage = () => {
    const [searchParams] = useSearchParams();
    const q = searchParams.get("q") ?? "";
    const c = searchParams.get("c") ?? "";

    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
        useSearchProducts(q, c);

    const sentinelRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        let lastScrollY = window.scrollY;
        const handleScroll = () => {
            const currentY = window.scrollY;
            const isScrollingDown = currentY > lastScrollY;
            lastScrollY = currentY;

            if (!isScrollingDown || !hasNextPage || isFetchingNextPage) return;

            const sentinel = sentinelRef.current;
            if (!sentinel) return;

            const rect = sentinel.getBoundingClientRect();
            if (rect.top <= window.innerHeight + 100) {
                fetchNextPage();
            }
        };
        window.addEventListener("scroll", handleScroll, { passive: true });
        return () => window.removeEventListener("scroll", handleScroll);
    }, [hasNextPage, isFetchingNextPage, fetchNextPage]);

    const pages = data?.pages || [];
    const products =
        pages?.flatMap((page) => (page?.data?.products as IProduct[]) || []) ||
        [];

    return (
        <>
            <SeoWrapper
                title="Search Products"
                description="Search for products"
            />
            <BaseLayout>
                <BreadcrumbBackground
                    title={q ? `Search: ${q}` : "Search Products"}
                    breadcrumb={[
                        {
                            title: "Products",
                            path: "/products",
                        },
                        {
                            title: q ? `Search: ${q}` : "Search Products",
                        },
                    ]}
                />
                <LayoutContainer>
                    <div className="pt-4 pb-24 md:pb-32">
                        {isLoading ? (
                            <div className="flex items-center justify-center min-h-[250px]">
                                <Loading />
                            </div>
                        ) : products?.length > 0 ? (
                            <ProductLayout>
                                {products?.map((p, i) => (
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
                                            delay: i * 0.01,
                                        }}
                                    >
                                        <ProductCard p={p} />
                                    </AnimationWrapper>
                                ))}
                            </ProductLayout>
                        ) : (
                            <div className="text-center py-20">
                                <NoDataFound title="No products found" />
                            </div>
                        )}
                        {hasNextPage && (
                            <div
                                ref={sentinelRef}
                                className="col-span-full flex justify-center py-4"
                            >
                                {isFetchingNextPage && <Loading />}
                            </div>
                        )}
                    </div>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
