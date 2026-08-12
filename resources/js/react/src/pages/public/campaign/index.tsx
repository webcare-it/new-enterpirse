import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { ProductLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { useCampaignProducts } from "@/api/product";
import type { ICampaign, IProduct } from "@/type";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { Loading } from "../_components/common/loading";
import { NoDataFound } from "@/components/common/no-data-found";
import { CampaignCountdown } from "./countdown";
import { OptimizedImage } from "@/components/common/optimized-image";

export const CampaignPage = () => {
    const { data, isLoading } = useCampaignProducts();
    const products = (data?.data?.products as IProduct[]) || [];
    const campaign = (data?.data?.campaign as ICampaign) || {};

    return (
        <>
            <SeoWrapper
                title={campaign?.name || "Campaign Products"}
                description={
                    campaign?.description || "Browse Campaign Products"
                }
            />
            <BaseLayout>
                <LayoutContainer className="pb-24 md:pb-32">
                    <div className="aspect-[4/1.5] sm:aspect-[16/5] md:aspect-[16/2.5] overflow-hidden rounded-xl md:rounded-3xl md:rounded-t-none mt-2 md:mt-0">
                        {isLoading ? (
                            "..."
                        ) : (
                            <OptimizedImage
                                src={campaign?.image}
                                alt={campaign?.name}
                                className="w-full h-full object-cover"
                                priority={true}
                            />
                        )}
                    </div>

                    <div className="mt-4">
                        {isLoading ? (
                            <div className="flex justify-center h-screen items-center">
                                <Loading />
                            </div>
                        ) : products?.length > 0 ? (
                            <>
                                <CampaignCountdown campaign={campaign} />
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
                                                delay: i * 0.03,
                                            }}
                                        >
                                            <ProductCard
                                                key={p.id}
                                                p={p}
                                                campaign={campaign}
                                            />
                                        </AnimationWrapper>
                                    ))}
                                </ProductLayout>
                            </>
                        ) : (
                            <NoDataFound />
                        )}
                    </div>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
