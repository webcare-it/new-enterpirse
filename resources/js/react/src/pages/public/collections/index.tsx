import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { ProductLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { useEffect, useRef } from "react";
import { useParams } from "react-router-dom";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { useGetCollections } from "@/api/product";
import { keyToValue } from "@/helper";
import { NoDataFound } from "@/components/common/no-data-found";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const CollectionsPage = () => {
    const { slug } = useParams();

    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
        useGetCollections();

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
    const products = pages?.flatMap((page) => page?.data?.products || []) || [];

    return (
        <>
            <SeoWrapper
                title={`${keyToValue(slug as string) || "Products"} | Collections`}
                description="Browse our collections"
            />
            <BaseLayout>
                <BreadcrumbBackground
                    title={keyToValue(slug as string) || "Collections"}
                    breadcrumb={[
                        {
                            title: "Collections",
                            path: `/collections/${slug}`,
                        },
                        {
                            title: keyToValue(slug as string) || "Collections",
                        },
                    ]}
                />
                <LayoutContainer className="mt-4 md:mt-6">
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
                        <div className="my-16 md:my-20">
                            <NoDataFound title="No Collections Found" />
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
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
