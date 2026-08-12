import { SeoWrapper } from "@/components/common/seo-wrapper";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { useGetPage } from "@/api/page";
import { RenderHtml } from "@/components/html";
import { useParams } from "react-router-dom";
import { keyToValue } from "@/helper";
import type { IPage } from "@/type";

export const PolicyPage = () => {
    const { slug } = useParams();
    const { data } = useGetPage();
    const page = (data?.data as IPage) || {};

    const title = keyToValue((slug as string) || "");

    return (
        <>
            <SeoWrapper
                title={page.meta_title || title}
                description={page.meta_description || ""}
            />
            <BaseLayout>
                <LayoutContainer className="pb-16 pt-4 md:pt-10 md:pb-24">
                    <div className="max-w-4xl mx-auto">
                        <h1 className="text-3xl md:text-4xl font-bold text-center mb-8 text-foreground">
                            {page?.title || title}
                        </h1>

                        <RenderHtml html={page?.content} />
                    </div>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
