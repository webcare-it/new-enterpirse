import { motion } from "framer-motion";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IBrand } from "@/type";
import { useConfig } from "@/hooks/useConfig";

export const BrandMarquee = () => {
    const config = useConfig();
    const brands = (config?.brands as IBrand[]) || [];

    const baseBrands = brands?.length > 0 ? brands : [];
    let duplicated = [...baseBrands, ...baseBrands];

    if (baseBrands?.length < 6) {
        duplicated = [...duplicated, ...duplicated, ...duplicated];
    }

    if (brands?.length === 0) return null;

    return (
        <LayoutContainer className="overflow-hidden py-4">
            <div className="text-center mb-6">
                <p className="text-sm font-mono tracking-[2px] text-gray-500 mb-2">
                    PREMIUM BRANDS
                </p>
                <h2 className="text-3xl font-semibold text-gray-900">
                    Shop Trusted Partners
                </h2>
            </div>

            <motion.div
                className="flex items-center gap-6 md:gap-10 whitespace-nowrap will-change-transform"
                animate={{ x: ["0%", "-50%"] }}
                transition={{
                    duration: 35,
                    repeat: Infinity,
                    ease: "linear",
                }}
                whileHover={{ animationPlayState: "paused" }}
            >
                {duplicated.map((brand, index) => (
                    <div
                        key={`${brand.id}-${index}`}
                        className="group flex-shrink-0 flex flex-col items-center justify-center 
                                   w-36 md:w-44 px-4 py-6 bg-white rounded-3xl shadow-lg 
                                   transition-all duration-300 hover:-translate-y-2 cursor-pointer"
                    >
                        <div className="relative h-16 w-20 md:h-24 md:w-28 mb-3">
                            <OptimizedImage
                                src={brand.logo}
                                alt={brand.name}
                                className="absolute w-full h-full object-contain"
                            />
                        </div>
                        <p className="text-xs font-medium text-gray-600 tracking-widest text-center uppercase">
                            {brand.name}
                        </p>
                    </div>
                ))}
            </motion.div>
        </LayoutContainer>
    );
};
