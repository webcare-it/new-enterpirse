import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination, Autoplay, EffectFade } from "swiper/modules";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Link } from "react-router-dom";
import { usePrice } from "@/hooks/usePrice";
import type { ICampaign } from "@/type";
import { getBaseUrl } from "@/helper";

export const CampaignSection = ({ campaigns }: { campaigns: ICampaign[] }) => {
    if (campaigns?.length === 0) {
        return null;
    }

    return (
        <LayoutContainer>
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
                className="mySwiper rounded-xl md:rounded-3xl overflow-hidden"
            >
                {campaigns?.map((campaign) => (
                    <SwiperSlide key={campaign?.id}>
                        <Link to={`/campaigns/${campaign?.slug}`}>
                            <div className="aspect-[4/1.5] sm:aspect-[16/5] md:aspect-[16/2.5] overflow-hidden rounded-xl md:rounded-3xl">
                                <OptimizedImage
                                    src={campaign?.image}
                                    alt={campaign?.name}
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

export const SVG = ({ c }: { c: ICampaign }) => {
    const { getCurrencySymbol } = usePrice();
    const getPercentageOrFlat = () => {
        const discountType = c?.discount_type;
        if (discountType === "percent") {
            return `${Math.round(Number(c?.discount_amount))}%`;
        } else if (discountType === "flat") {
            return `${getCurrencySymbol()}${Math.round(Number(c?.discount_amount))}`;
        }
    };
    const img = `${getBaseUrl()}/assets/img/offer.png`;

    return (
        <div className="absolute top-1 right-1 md:top-4 md:right-4 size-20 md:size-40">
            <OptimizedImage
                src={img}
                alt="Offer"
                className="absolute inset-0 h-full w-full object-contain"
            />

            <div className="absolute inset-0 z-10 flex flex-col items-center justify-center text-center">
                <span className="text-[9px] md:text-sm font-bold uppercase tracking-wider text-white">
                    UP TO
                </span>

                <span className="text-xl md:text-3xl font-extrabold leading-none text-white">
                    {getPercentageOrFlat()}
                </span>
                <span className="text-sm md:text-2xl font-extrabold leading-none text-white">
                    OFF
                </span>
            </div>
        </div>
    );
};
