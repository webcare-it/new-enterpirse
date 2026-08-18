import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { useGetPage } from "@/api/page";
import { Loading } from "../_components/common/loading";
import { RenderHtml } from "@/components/html";
import type { IPage } from "@/type";

export const AboutPage = () => {
    const { data, isLoading } = useGetPage("about_us");
    const page = (data?.data as IPage) || {};

    return (
        <>
            <SeoWrapper
                title="About Us"
                description="Learn more about our story and mission"
            />
            <BaseLayout>
                <BreadcrumbBackground
                    title="About Us"
                    breadcrumb={[{ title: "About Us" }]}
                />

                {isLoading ? (
                    <div className="flex items-center h-screen justify-center">
                        <Loading />
                    </div>
                ) : (
                    <section className="py-16 md:py-24">
                        <LayoutContainer>
                            <div className="max-w-4xl mx-auto">
                                <h1 className="text-3xl md:text-4xl font-bold text-center mb-8 text-foreground">
                                    {page?.title}
                                </h1>

                                <RenderHtml html={page?.content} />
                            </div>
                        </LayoutContainer>
                    </section>
                )}
            </BaseLayout>
        </>
    );
};
