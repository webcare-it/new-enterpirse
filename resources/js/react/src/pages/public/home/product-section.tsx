import { Link } from "react-router-dom";
import { ArrowRight, ChevronLeft, ChevronRight } from "lucide-react";
import { LayoutContainer } from "../_components/layout/base-layout";
import type { ICategoryWithProducts, IProduct } from "@/type";
import { ProductCard } from "../_components/common/product";
import { useEffect, useRef, useState } from "react";
import { motion } from "framer-motion";
import { useProductLayout } from "@/hooks/useProductLayout";
import { Button } from "@/components/ui/button";

interface Props {
    products: IProduct[];
    title: string;
    href?: string;
    className?: string;
}

export const ProductsSection = ({
    products,
    className,
    title,
    href,
}: Props) => {
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

    const scrollLeft = () => {
        setCurrentIndex((p) => Math.max(p - 1, 0));
    };

    const scrollRight = () => {
        setCurrentIndex((p) => Math.min(p + 1, maxIndex));
    };

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
                offset = deltaX * 0.3;
            }
            setDragOffset(offset);
        }
    };

    const handleTouchEnd = () => {
        if (!isDragging) return;

        const threshold = 50;

        if (dragOffset > threshold && canScrollLeft) {
            setCurrentIndex((p) => Math.max(p - 1, 0));
        } else if (dragOffset < -threshold && canScrollRight) {
            setCurrentIndex((p) => Math.min(p + 1, maxIndex));
        }

        setDragOffset(0);
        setIsDragging(false);
        isHorizontalSwipe.current = false;
    };

    const dragPercentage =
        isDragging && itemsPerView > 0
            ? (dragOffset / window.innerWidth) * 100
            : 0;

    return (
        <div className={`relative group ${className || ""}`}>
            <LayoutContainer>
                <div className="flex items-center justify-between gap-2 mb-3">
                    <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground transition-all duration-500 ease-out hover:tracking-wide hover:text-primary">
                        {title}
                    </h2>
                    <div className="flex items-center gap-2">
                        <button
                            onClick={scrollLeft}
                            disabled={!canScrollLeft}
                            className="flex size-10 md:size-12 items-center justify-center rounded-full border border-gray-800 bg-background text-foreground transition-all duration-300 hover:border-primary hover:bg-primary hover:text-primary-foreground disabled:opacity-30 disabled:pointer-events-none cursor-pointer"
                        >
                            <ChevronLeft className="h-5 w-5" />
                        </button>
                        <button
                            onClick={scrollRight}
                            disabled={!canScrollRight}
                            className="flex size-10 md:size-12 items-center justify-center rounded-full border border-gray-800 bg-background text-foreground transition-all duration-300 hover:border-primary hover:bg-primary hover:text-primary-foreground disabled:opacity-30 disabled:pointer-events-none cursor-pointer"
                        >
                            <ChevronRight className="h-5 w-5" />
                        </button>
                    </div>
                </div>

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
                                ? { type: "tween", duration: 0 }
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
                                transition={{
                                    duration: 0.35,
                                    delay: index * 0.03,
                                }}
                            >
                                <div className="mx-0.5 md:mx-2 my-4">
                                    <ProductCard p={p} />
                                </div>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>

                {href && (
                    <div className="flex justify-center mt-2 md:mt-4">
                        <Link to={href}>
                            <Button>
                                View all
                                <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/view-all:translate-x-1" />{" "}
                            </Button>
                        </Link>
                    </div>
                )}
            </LayoutContainer>
        </div>
    );
};

export const CategoryProductSection = ({
    categories,
}: {
    categories: ICategoryWithProducts[];
}) => {
    if (categories?.length === 0) return null;

    return (
        <>
            {categories?.map((category, index) => (
                <ProductsSection
                    key={category?.id + "_" + index}
                    title={category?.name}
                    products={category?.products || []}
                    href={`/categories/${category?.slug}`}
                />
            ))}
        </>
    );
};
