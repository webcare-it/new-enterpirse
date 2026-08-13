import { Swiper, SwiperSlide } from "swiper/react";
import { ArrowRight } from "lucide-react";
import { Pagination, EffectFade, Autoplay } from "swiper/modules";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IHeroSlider } from "@/type";
import { Link } from "react-router-dom";

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
                className="mySwiper rounded-2xl mt-2 md:rounded-4xl overflow-hidden"
            >
                {heroSlides?.map((item, index) => (
                    <SwiperSlide key={item?.id}>
                        <div className="relative w-full border aspect-[4/2] md:aspect-[16/5.5] overflow-hidden bg-neutral-100">
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
                            {/* CONTENT */}
                            <div className="absolute inset-0 flex flex-col justify-end items-end pr-4 md:pr-8 pb-4 md:pb-8 text-white bg-black/20">
                                <h2 className="text-lg md:text-3xl font-bold max-w-xl">
                                    {item?.title}
                                </h2>

                                <Link
                                    to={item?.link || "/"}
                                    className="mt-2 inline-flex items-center gap-2 text-xs md:text-sm px-2 md:px-4 py-1 md:py-2 rounded-full bg-primary text-primary-foreground w-fit"
                                >
                                    {item?.button || "Shop Now"}
                                    <ArrowRight className="size-3 md:size-4" />
                                </Link>
                            </div>
                        </div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </LayoutContainer>
    );
};
