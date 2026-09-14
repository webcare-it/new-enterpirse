import { Swiper, SwiperSlide } from "swiper/react";
import { Pagination, EffectFade, Autoplay } from "swiper/modules";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IHeroSlider } from "@/type";

export const HeroSection = ({
    heroSlides,
    loading,
}: {
    heroSlides: IHeroSlider[];
    loading: boolean;
}) => {
    if (loading) {
        return <div>Loading...</div>;
    }

    if (!heroSlides?.length) {
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
                    delay: 4000,
                    disableOnInteraction: false,
                }}
                className="mySwiper rounded-3xl mt-2 md:rounded-4xl overflow-hidden"
            >
                {heroSlides?.map((item, index) => (
                    <SwiperSlide key={item?.id}>
                        <div className="relative w-full border aspect-[4/1.7] md:aspect-[16/5.5] overflow-hidden bg-neutral-100">
                            {/* MEDIA */}
                            {item?.type === "video" ? (
                                <video
                                    src={item?.url}
                                    autoPlay
                                    muted
                                    loop
                                    playsInline
                                    className="absolute inset-0 w-full h-full object-cover"
                                />
                            ) : (
                                <OptimizedImage
                                    src={item.url}
                                    alt={item.title}
                                    priority={index === 0}
                                    className="absolute inset-0 w-full h-full object-cover object-center md:object-[65%_40%]"
                                />
                            )}
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </LayoutContainer>
    );
};
