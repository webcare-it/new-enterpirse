import { useRef, useState, useEffect, useCallback } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import type { ICategory } from "@/type";
import { useConfig } from "@/hooks/useConfig";
import { LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { ArrowRight, ChevronLeft, ChevronRight } from "lucide-react";

export const CategorySection = () => {
    const config = useConfig();
    const scrollRef = useRef<HTMLDivElement>(null);
    const categories = config?.categories as ICategory[];
    const [canScrollLeft, setCanScrollLeft] = useState(false);
    const [canScrollRight, setCanScrollRight] = useState(true);

    const checkScroll = useCallback(() => {
        const el = scrollRef.current;
        if (!el) return;
        setCanScrollLeft(el.scrollLeft > 10);
        setCanScrollRight(el.scrollLeft < el.scrollWidth - el.clientWidth - 10);
    }, []);

    useEffect(() => {
        const el = scrollRef.current;
        if (!el) return;
        checkScroll();
        el.addEventListener("scroll", checkScroll, { passive: true });
        window.addEventListener("resize", checkScroll);
        return () => {
            el.removeEventListener("scroll", checkScroll);
            window.removeEventListener("resize", checkScroll);
        };
    }, [checkScroll, categories]);

    const scrollByAmount = (direction: "left" | "right") => {
        const el = scrollRef.current;
        if (!el) return;
        const cardWidth = el.children[0]?.getBoundingClientRect().width || 200;
        const gap = 20;
        const amount =
            direction === "left" ? -(cardWidth + gap) : cardWidth + gap;
        const target = Math.max(
            0,
            Math.min(el.scrollLeft + amount, el.scrollWidth - el.clientWidth),
        );
        el.scrollTo({ left: target, behavior: "smooth" });
    };

    if (!categories?.length) return null;

    return (
        <LayoutContainer>
            <div className="space-y-6">
                <div className="flex items-center justify-between gap-4">
                    <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground transition-all duration-500 ease-out hover:tracking-wide hover:text-primary whitespace-nowrap">
                        Categories
                    </h2>

                    <div className="flex items-center gap-2">
                        <button
                            onClick={() => scrollByAmount("left")}
                            disabled={!canScrollLeft}
                            className="flex size-10 md:size-12 items-center justify-center rounded-full border border-gray-800 bg-background text-foreground transition-all duration-300 hover:border-primary hover:bg-primary hover:text-primary-foreground disabled:opacity-30 disabled:pointer-events-none cursor-pointer"
                        >
                            <ChevronLeft className="h-5 w-5" />
                        </button>
                        <button
                            onClick={() => scrollByAmount("right")}
                            disabled={!canScrollRight}
                            className="flex size-10 md:size-12 items-center justify-center rounded-full border border-gray-800 bg-background text-foreground transition-all duration-300 hover:border-primary hover:bg-primary hover:text-primary-foreground disabled:opacity-30 disabled:pointer-events-none cursor-pointer"
                        >
                            <ChevronRight className="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <div className="relative">
                    <div
                        ref={scrollRef}
                        className="
                            flex gap-4 md:gap-5
                            overflow-x-auto
                            overflow-y-hidden
                            scrollbar-hide
                            scroll-smooth
                            touch-pan-x
                            overscroll-x-contain
                            [-webkit-overflow-scrolling:touch]
                        "
                        style={{ WebkitOverflowScrolling: "touch" }}
                    >
                        {categories?.map((category, index) => (
                            <motion.div
                                key={category.id}
                                initial={{ opacity: 0, scale: 0.9 }}
                                whileInView={{ opacity: 1, scale: 1 }}
                                viewport={{ once: true }}
                                transition={{
                                    delay: index * 0.05,
                                    duration: 0.4,
                                    ease: [0.25, 0.46, 0.45, 0.94],
                                }}
                                className="shrink-0 w-[150px] sm:w-[170px] md:w-[190px]"
                            >
                                <Link
                                    to={`/categories/${category?.slug}`}
                                    className="group relative block aspect-[3/4] rounded-2xl overflow-hidden bg-muted"
                                >
                                    <OptimizedImage
                                        src={category?.image}
                                        alt={category?.name}
                                        className="absolute inset-0 h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-110"
                                    />

                                    <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent opacity-80 transition-opacity duration-300 group-hover:opacity-90" />

                                    <div className="absolute inset-0 flex flex-col items-center justify-end p-4 text-white group-hover:text-primary">
                                        <div className="flex justify-center items-center gap-2">
                                            <span className="font-semibold text-sm md:text-base text-center leading-tight drop-shadow-md">
                                                {category?.name}
                                            </span>
                                            <ArrowRight className="w-4 h-4" />
                                        </div>
                                    </div>
                                </Link>
                            </motion.div>
                        ))}
                    </div>
                </div>
            </div>
        </LayoutContainer>
    );
};
