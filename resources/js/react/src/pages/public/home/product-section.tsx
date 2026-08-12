import { Link } from "react-router-dom";
import { ArrowRight, ChevronLeft, ChevronRight } from "lucide-react";
import { LayoutContainer } from "../_components/layout/base-layout";
import type { IProduct } from "@/type";
import { ProductCard } from "../_components/common/product";
import { useEffect, useRef, useState } from "react";
import { motion } from "framer-motion";
import { useDailyCountdown } from "@/hooks/useCounter";
import { useProductLayout } from "@/hooks/useProductLayout";

const ProductsGrid = ({
    products,
    className,
}: {
    products: IProduct[];
    className?: string;
}) => {
    const { homePage } = useProductLayout();

    const touchStartX = useRef(0);
    const touchStartY = useRef(0);
    const isHorizontalSwipe = useRef(false);
    const [dragOffset, setDragOffset] = useState(0);
    const [isDragging, setIsDragging] = useState(false);
    const [currentIndex, setCurrentIndex] = useState(0);
    const [itemsPerView, setItemsPerView] = useState(2);

    useEffect(() => {
        const updateItemsPerView = () => {
            const width = window.innerWidth;
            const { mobile, tablet, laptop, desktop, ultrawide } = homePage;

            if (width >= 1536) setItemsPerView(ultrawide);
            else if (width >= 1280) setItemsPerView(desktop);
            else if (width >= 1024) setItemsPerView(laptop);
            else if (width >= 768) setItemsPerView(tablet);
            else setItemsPerView(mobile);
        };
        updateItemsPerView();
        window.addEventListener("resize", updateItemsPerView);
        return () => window.removeEventListener("resize", updateItemsPerView);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    useEffect(() => {
        setCurrentIndex(0);
    }, [itemsPerView]);

    if (!products?.length) return null;

    const maxIndex = Math.max(0, products.length - itemsPerView);
    const canScrollLeft = currentIndex > 0;
    const canScrollRight = currentIndex < maxIndex;

    const handleTouchStart = (e: React.TouchEvent) => {
        touchStartX.current = e.touches[0].clientX;
        touchStartY.current = e.touches[0].clientY;
        isHorizontalSwipe.current = false;
        setIsDragging(true);
    };

    const handleTouchMove = (e: React.TouchEvent) => {
        if (!isDragging) return;

        const currentX = e.touches[0].clientX;
        const currentY = e.touches[0].clientY;
        const deltaX = currentX - touchStartX.current;
        const deltaY = currentY - touchStartY.current;

        if (!isHorizontalSwipe.current) {
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 8) {
                isHorizontalSwipe.current = true;
            } else if (Math.abs(deltaY) > 8) {
                setIsDragging(false);
                setDragOffset(0);
                return;
            }
        }

        if (isHorizontalSwipe.current) {
            e.preventDefault();

            let offset = deltaX;
            if (
                (currentIndex === 0 && deltaX > 0) ||
                (currentIndex === maxIndex && deltaX < 0)
            ) {
                offset = deltaX * 0.3; // rubber-band effect
            }
            setDragOffset(offset);
        }
    };

    const handleTouchEnd = () => {
        if (!isDragging) return;

        const threshold = 50; // px needed to change slide

        if (dragOffset > threshold && canScrollLeft) {
            setCurrentIndex((p) => Math.max(p - 1, 0));
        } else if (dragOffset < -threshold && canScrollRight) {
            setCurrentIndex((p) => Math.min(p + 1, maxIndex));
        }

        setDragOffset(0);
        setIsDragging(false);
        isHorizontalSwipe.current = false;
    };

    // Convert pixel drag into percentage of one item width
    const dragPercentage =
        isDragging && itemsPerView > 0
            ? (dragOffset / window.innerWidth) * 100
            : 0;

    return (
        <div className={`relative group ${className || ""}`}>
            {canScrollLeft && (
                <button
                    onClick={() => setCurrentIndex((p) => Math.max(p - 1, 0))}
                    aria-label="Scroll products left"
                    className="absolute left-1 md:left-0 top-1/2 z-20 -translate-x-1/2 -translate-y-1/2 size-9 md:size-11 rounded-full bg-primary text-primary-foreground flex items-center justify-center transition-all duration-300 hover:scale-110 md:opacity-0 md:group-hover:opacity-100 cursor-pointer"
                >
                    <ChevronLeft className="size-4 md:size-5" />
                </button>
            )}

            {canScrollRight && (
                <button
                    onClick={() =>
                        setCurrentIndex((p) => Math.min(p + 1, maxIndex))
                    }
                    aria-label="Scroll products right"
                    className="absolute right-1 md:right-0 top-1/2 z-20 translate-x-1/2 -translate-y-1/2 size-9 md:size-11 rounded-full bg-primary text-primary-foreground flex items-center justify-center transition-all duration-300 hover:scale-110 md:opacity-0 md:group-hover:opacity-100 cursor-pointer"
                >
                    <ChevronRight className="size-4 md:size-5" />
                </button>
            )}

            <div
                className="overflow-hidden touch-pan-y"
                onTouchStart={handleTouchStart}
                onTouchMove={handleTouchMove}
                onTouchEnd={handleTouchEnd}
                onTouchCancel={handleTouchEnd}
            >
                <motion.div
                    className="flex"
                    animate={{
                        x: `calc(-${currentIndex * (100 / itemsPerView)}% + ${dragPercentage}%)`,
                    }}
                    transition={
                        isDragging
                            ? { type: "tween", duration: 0 } // instant follow while dragging
                            : {
                                  type: "spring",
                                  stiffness: 180,
                                  damping: 24,
                                  mass: 0.8,
                              }
                    }
                >
                    {products?.map((p, index) => (
                        <motion.div
                            key={p.id}
                            className="flex-shrink-0"
                            style={{ width: `${100 / itemsPerView}%` }}
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.35, delay: index * 0.03 }}
                        >
                            <div className="mx-0.5 md:mx-2 my-4">
                                <ProductCard p={p} />
                            </div>
                        </motion.div>
                    ))}
                </motion.div>
            </div>
        </div>
    );
};

interface SectionProductProps {
    products: IProduct[];
    loading: boolean;
    title: string;
    href?: string;
    background?: string;
}

export const ProductsSection = ({
    products,
    loading,
    title,
    href,
    background = "",
}: SectionProductProps) => {
    if (loading) {
        return <div>Loading...</div>;
    }

    if (products?.length === 0) return null;

    return (
        <section className={`${background || ""}`}>
            <LayoutContainer>
                <SectionTitleWithLink title={title} href={href} />

                <ProductsGrid products={products} />
            </LayoutContainer>
        </section>
    );
};

export const TodaysDealSection = ({
    products,
    loading,
    title,
    href,
    background = "",
}: SectionProductProps) => {
    const { hours, minutes, seconds } = useDailyCountdown();
    if (loading) {
        return <div>Loading...</div>;
    }

    if (products?.length === 0) return null;

    return (
        <section className={`${background || ""}`}>
            <LayoutContainer>
                <div className="bg-primary/5 p-0 md:p-4 rounded-2xl">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-2 md:gap-6 pb-4 pt-4 md:pt-0 md:pb-6">
                        <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground mt-1 transition-all duration-500 ease-out hover:tracking-wide hover:text-primary text-center md:text-left">
                            {title}
                        </h2>

                        <div className="flex flex-col items-center">
                            <h3 className="text-center text-base md:text-xl text-gray-800 font-semibold">
                                Time Left
                            </h3>

                            <div className="flex items-center gap-2">
                                <TimeBox value={hours} label="Hours" />

                                <span className="text-4xl font-bold text-red-600">
                                    :
                                </span>

                                <TimeBox value={minutes} label="Minutes" />

                                <span className="text-4xl font-bold text-red-600">
                                    :
                                </span>

                                <TimeBox value={seconds} label="Seconds" />
                            </div>
                        </div>
                    </div>

                    {products?.length > 0 && (
                        <ProductsGrid products={products} />
                    )}
                    <div className="flex justify-center pt-4 pb-4 md:pb-0">
                        <Link
                            to={href ? href : "/products"}
                            className="group/view-all flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium text-gray-900 transition-all duration-300 hover:-translate-y-0.5 hover:text-primary"
                        >
                            View all
                            <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/view-all:translate-x-1" />
                        </Link>
                    </div>
                </div>
            </LayoutContainer>
        </section>
    );
};

const TimeBox = ({ value, label }: { value: number; label: string }) => {
    return (
        <div className="flex flex-col items-center gap-0.5">
            <div className="size-12 md:size-14 rounded-lg bg-red-600 text-white border border-red-600 flex items-center justify-center text-2xl font-bold">
                {String(value)?.padStart(2, "0")}
            </div>
            <span className="text-xs font-medium text-gray-700">{label}</span>
        </div>
    );
};

export const SectionTitleWithLink = ({
    title,
    href,
}: {
    title: string;
    href?: string;
}) => {
    return (
        <div className="mb-6">
            <div className="flex items-center justify-between gap-2 flex-wrap mb-3">
                <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground mt-1 transition-all duration-500 ease-out hover:tracking-wide hover:text-primary">
                    {title}
                </h2>

                {href && (
                    <Link
                        to={href}
                        className="group/view-all flex items-center gap-2 rounded-full px-3 py-1.5 text-sm font-medium text-gray-900 transition-all duration-300  hover:-translate-y-0.5 hover:text-primary"
                    >
                        View all
                        <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/view-all:translate-x-1" />
                    </Link>
                )}
            </div>
        </div>
    );
};
