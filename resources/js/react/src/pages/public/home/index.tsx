import React from "react";
import { HeroSection } from "./hero-section";
import { BaseLayout } from "../_components/layout/base-layout";
import { ProductsSection, TodaysDealSection } from "./product-section";
import { FeatureHighlights } from "./badge-section";
import { BlogSection } from "./blog-section";
import { CampaignSection } from "./campaign-section";
import { useGetHome } from "@/api/home";
import { CategorySection } from "./category-section";
import { useConfig } from "@/hooks/useConfig";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { product_collection_path } from "@/data";

interface ISection {
    key: string;
    order: number;
    isActive: boolean;
}

const defaultSec = [
    {
        key: "sliders",
        order: 1,
        isActive: true,
    },
    {
        key: "campaigns",
        order: 2,
        isActive: true,
    },
    {
        key: "new_arrivals",
        order: 3,
        isActive: true,
    },
    {
        key: "todays_deal",
        order: 4,
        isActive: true,
    },
    {
        key: "best_selling",
        order: 5,
        isActive: true,
    },
    {
        key: "featured",
        order: 6,
        isActive: true,
    },
    {
        key: "categories",
        order: 7,
        isActive: true,
    },
    {
        key: "blogs",
        order: 8,
        isActive: true,
    },
];

export const HomePage = () => {
    const config = useConfig();
    const sec = config?.sections as ISection[];
    const { data, isLoading } = useGetHome();

    const sliders = data?.data?.sliders || [];
    const categories = data?.data?.categories || [];
    const products_featured = data?.data?.featured || [];
    const products_todays = data?.data?.todays_deal || [];
    const products_new_arrivals = data?.data?.new_arrivals || [];
    const products_best_selling = data?.data?.best_selling || [];
    const campaigns = data?.data?.campaigns || [];

    const sections: ISection[] = sec?.length > 0 ? sec : defaultSec;

    const sectionComponents = {
        sliders: <HeroSection heroSlides={sliders} loading={false} />,
        campaigns: <CampaignSection campaigns={campaigns} />,
        features: <FeatureHighlights />,
        new_arrivals: (
            <ProductsSection
                title="New Arrivals"
                products={products_new_arrivals}
                href={product_collection_path.new_arrivals}
                loading={false}
            />
        ),
        todays_deal: (
            <TodaysDealSection
                title="Today's Deal"
                products={products_todays}
                href={product_collection_path.todys_deal}
                loading={false}
            />
        ),
        best_selling: (
            <ProductsSection
                title="Best Selling"
                products={products_best_selling}
                href={product_collection_path.best_selling}
                loading={false}
            />
        ),
        featured: (
            <ProductsSection
                title="Featured Products"
                products={products_featured}
                href={product_collection_path.featured}
                loading={false}
            />
        ),
        categories: (
            <CategorySection categories={categories} loading={isLoading} />
        ),
        blogs: <BlogSection blogs={data?.data?.blogs || []} />,
    };

    const renderSections = sections
        ?.filter((section) => section.isActive)
        ?.sort((a, b) => a.order - b.order)
        ?.map((section) => ({
            ...section,
            component:
                sectionComponents[
                    section?.key as keyof typeof sectionComponents
                ],
        }))
        ?.filter((section) => section.component);

    return (
        <>
            <SeoWrapper />
            <BaseLayout>
                {isLoading ? (
                    <section className="flex items-center justify-center h-screen">
                        <Loading />
                    </section>
                ) : (
                    <section className="space-y-12 pb-12 md:mb-16">
                        {renderSections.map((section) => (
                            <React.Fragment key={section.key}>
                                {section.component}
                            </React.Fragment>
                        ))}
                    </section>
                )}
            </BaseLayout>
        </>
    );
};
