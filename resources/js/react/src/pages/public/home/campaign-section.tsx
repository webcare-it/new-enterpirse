import { useState } from "react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination, Autoplay, EffectFade } from "swiper/modules";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Link } from "react-router-dom";
import type { ICampaign } from "@/type";
import { ArrowRight } from "lucide-react";

export const CampaignSection = ({ campaigns }: { campaigns: ICampaign[] }) => {
    const [activeIndex, setActiveIndex] = useState(0);

    if (campaigns?.length === 0) {
        return null;
    }

    const currentCampaign = campaigns?.[activeIndex];

    return (
        <LayoutContainer>
            <div className="flex items-center justify-between gap-2 pb-6">
                <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground transition-all duration-500 ease-out hover:tracking-wide hover:text-primary">
                    Campaign
                </h2>

                <Link
                    to={`/campaigns/${currentCampaign?.slug}`}
                    className="group/view-all text-sm font-medium text-primary flex items-center gap-1.5 hover:translate-x-1 transition-all duration-300 ease-out"
                >
                    View details
                    <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/view-all:translate-x-1" />
                </Link>
            </div>

            <Swiper
                modules={[Pagination, EffectFade, Autoplay]}
                slidesPerView={1}
                loop={true}
                speed={800}
                effect="fade"
                pagination={{ clickable: true }}
                autoplay={{
                    delay: 6000,
                    disableOnInteraction: false,
                }}
                onSlideChange={(swiper) => {
                    setActiveIndex(swiper.realIndex);
                }}
                className="mySwiper rounded-xl md:rounded-3xl overflow-hidden"
            >
                {campaigns.map((campaign) => (
                    <SwiperSlide key={campaign.id}>
                        <Link to={`/campaigns/${campaign.slug}`}>
                            <div className="aspect-[4/1.5] sm:aspect-[16/5] md:aspect-[16/2.5] overflow-hidden rounded-xl md:rounded-3xl">
                                <OptimizedImage
                                    src={campaign.image}
                                    alt={campaign.name}
                                    className="w-full h-full object-cover"
                                />
                            </div>
                        </Link>
                    </SwiperSlide>
                ))}
            </Swiper>
        </LayoutContainer>
    );
};
