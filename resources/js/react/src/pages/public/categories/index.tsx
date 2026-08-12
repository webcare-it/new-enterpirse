import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { ProductPageLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { useState, useEffect, useRef } from "react";
import { useParams, useSearchParams } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { FiltersDrawerMobile } from "../_components/common/mobile-filter";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { useGetCategoryProducts } from "@/api/product";
import type { IFilters } from "@/type";
import { Filters, type ISubCategory } from "../_components/common/filter";
import { LoaderIcon } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const CategoryProductsPage = () => {
    const { slug } = useParams();
    const [searchParams, setSearchParams] = useSearchParams();
    const [filters, setFilters] = useState<IFilters>({
        minPrice: Number(searchParams.get("minPrice")) || 0,
        maxPrice: Number(searchParams.get("maxPrice")) || 50000,
        rating: Number(searchParams.get("rating")) || 0,
        sort: (searchParams.get("sort") as IFilters["sort"]) || "select",
        brands: searchParams.get("brands")?.split(",").filter(Boolean) || [],
        subCategories: searchParams.get("sub")?.split(",") || [],
    });
    const [debouncedFilters, setDebouncedFilters] = useState(filters);

    useEffect(() => {
        const timer = setTimeout(() => {
            setDebouncedFilters(filters);
        }, 500);
        return () => clearTimeout(timer);
    }, [filters]);

    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
        useGetCategoryProducts(debouncedFilters);

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

    const sub = Array.from(
        new Map(
            (data?.pages ?? [])
                .flatMap((page) => {
                    const d = page as {
                        data?: { sub_categories?: ISubCategory[] };
                    };
                    return (
                        (d?.data?.sub_categories as ISubCategory[]) ||
                        ([] as ISubCategory[])
                    );
                })
                .map((item) => [item?.slug || String(item?.id || ""), item]),
        ).values(),
    ) as ISubCategory[];

    useEffect(() => {
        const params = new URLSearchParams();
        if (filters.minPrice > 0)
            params.set("minPrice", filters.minPrice.toString());
        if (filters.maxPrice < 50000)
            params.set("maxPrice", filters.maxPrice.toString());
        if (filters.rating > 0) params.set("rating", filters.rating.toString());
        if (filters.sort !== "select") params.set("sort", filters.sort);
        if (filters.brands.length > 0)
            params.set("brands", filters.brands.join(","));
        if (filters?.subCategories && filters?.subCategories?.length > 0)
            params.set("sub", filters?.subCategories.join(","));
        setSearchParams(params, { replace: true });
    }, [filters, setSearchParams]);

    const updateFilter = (key: string, value: unknown) => {
        setFilters((prev) => ({ ...prev, [key]: value }) as IFilters);
    };

    const clearAllFilters = () => {
        setFilters({
            minPrice: 0,
            maxPrice: 50000,
            rating: 0,
            sort: "select",
            brands: [],
            subCategories: [],
        });
    };

    const activeCount = [
        filters.minPrice > 0 || filters.maxPrice < 50000,
        filters.rating > 0,
        filters.brands.length > 0,
        filters?.subCategories && filters?.subCategories?.length > 0,
        filters.sort !== "select",
    ].filter(Boolean).length;

    return (
        <>
            <SeoWrapper
                title={`${(slug as string) || "Products"} of Products`}
                description="Browse products by category"
            />
            <BaseLayout>
                <BreadcrumbBackground
                    title={(slug as string) || "Products"}
                    breadcrumb={[
                        {
                            title: "Categories",
                            path: `/categories/${slug}`,
                        },
                        {
                            title: (slug as string) || "Products",
                        },
                    ]}
                />
                <LayoutContainer>
                    <div className="pt-4 pb-24 md:pb-32">
                        <div className="flex justify-end mb-4 md:hidden ">
                            <FiltersDrawerMobile>
                                <Filters
                                    subCategories={sub}
                                    filters={filters}
                                    updateFilter={updateFilter}
                                    activeCount={activeCount}
                                    clearAllFilters={clearAllFilters}
                                />
                            </FiltersDrawerMobile>
                        </div>

                        <div className="flex flex-col md:flex-row gap-4">
                            <div className="hidden md:block md:w-[280px] lg:w-xs flex-shrink-0">
                                <div className="sticky top-28 border rounded-2xl p-5 max-h-[calc(100vh-8rem)] flex flex-col">
                                    <div
                                        className="overflow-y-auto flex-1 space-y-5"
                                        style={{
                                            scrollbarWidth: "none",
                                            msOverflowStyle: "none",
                                        }}
                                    >
                                        <Filters
                                            subCategories={sub}
                                            filters={filters}
                                            updateFilter={updateFilter}
                                            activeCount={activeCount}
                                            clearAllFilters={clearAllFilters}
                                        />
                                    </div>
                                </div>
                            </div>
                            <div className="flex-1 min-w-0">
                                {isLoading ? (
                                    <div className="flex items-center justify-center min-h-[250px]">
                                        <LoaderIcon
                                            role="status"
                                            aria-label="Loading"
                                            className={"size-4 animate-spin"}
                                        />
                                    </div>
                                ) : products?.length > 0 ? (
                                    <ProductPageLayout>
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
                                    </ProductPageLayout>
                                ) : (
                                    <div className="text-center py-20">
                                        <p className="text-muted-foreground text-lg">
                                            No products match your filters.
                                        </p>
                                        <Button
                                            variant="link"
                                            onClick={clearAllFilters}
                                            className="mt-2"
                                        >
                                            Clear all filters
                                        </Button>
                                    </div>
                                )}
                                {hasNextPage && (
                                    <div
                                        ref={sentinelRef}
                                        className="col-span-full flex justify-center py-4"
                                    >
                                        {isFetchingNextPage && (
                                            <LoaderIcon
                                                role="status"
                                                aria-label="Loading"
                                                className={
                                                    "size-4 animate-spin"
                                                }
                                            />
                                        )}
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
