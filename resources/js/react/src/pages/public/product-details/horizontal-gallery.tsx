import { useRef, useState, useEffect } from "react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IProductImage } from "./type";

interface Props {
    images: IProductImage[];
    onOpenModal: (index: number) => void;
    selectedImageIndex: number;
}

export const HorizontalGalleryStrip = ({
    images,
    onOpenModal,
    selectedImageIndex,
}: Props) => {
    const scrollRef = useRef<HTMLDivElement>(null);
    const [showButtons, setShowButtons] = useState(false);
    const [canScrollLeft, setCanScrollLeft] = useState(false);
    const [canScrollRight, setCanScrollRight] = useState(false);

    const checkScroll = () => {
        const el = scrollRef.current;
        if (!el) return;

        const hasOverflow = el.scrollWidth > el.clientWidth + 5;
        setShowButtons(hasOverflow);

        setCanScrollLeft(el.scrollLeft > 5);
        setCanScrollRight(el.scrollLeft + el.clientWidth < el.scrollWidth - 5);
    };

    useEffect(() => {
        checkScroll();

        const el = scrollRef.current;
        if (!el) return;

        el.addEventListener("scroll", checkScroll);
        window.addEventListener("resize", checkScroll);

        const timer = setTimeout(checkScroll, 100);

        return () => {
            el.removeEventListener("scroll", checkScroll);
            window.removeEventListener("resize", checkScroll);
            clearTimeout(timer);
        };
    }, [images]);

    const scrollByDir = (dir: "left" | "right") => {
        if (!scrollRef.current) return;
        const amount = scrollRef.current.clientWidth * 0.7;
        scrollRef.current.scrollBy({
            left: dir === "left" ? -amount : amount,
            behavior: "smooth",
        });
    };

    return (
        <div className="relative mt-4 md:block">
            {showButtons && canScrollLeft && (
                <button
                    type="button"
                    onClick={() => scrollByDir("left")}
                    className="absolute left-0 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-white transition cursor-pointer"
                    aria-label="Scroll left"
                >
                    <ChevronLeft className="size-4" />
                </button>
            )}

            <div
                ref={scrollRef}
                className="flex gap-2 lg:gap-3 overflow-x-auto scroll-smooth snap-x snap-mandatory px-1 py-1 [&::-webkit-scrollbar]:hidden [scrollbar-width:none]"
            >
                {images?.map((img, idx) => (
                    <button
                        key={img.id}
                        onClick={() => onOpenModal(idx)}
                        className={`snap-start shrink-0 w-16 lg:w-20 xl:w-24 aspect-square rounded-xl overflow-hidden bg-white border cursor-zoom-in transition-all ${
                            idx === selectedImageIndex
                                ? "ring-2 ring-primary border-primary"
                                : "border-gray-200 opacity-80 hover:opacity-100 hover:border-gray-400"
                        }`}
                    >
                        <OptimizedImage
                            src={img.src}
                            alt={img.alt}
                            className="w-full h-full object-contain p-1 rounded-none bg-white"
                        />
                    </button>
                ))}
            </div>

            {showButtons && canScrollRight && (
                <button
                    type="button"
                    onClick={() => scrollByDir("right")}
                    className="absolute right-0 top-1/2 -translate-y-1/2 z-10 w-8 h-8 rounded-full bg-white/90 shadow flex items-center justify-center hover:bg-white transition cursor-pointer"
                    aria-label="Scroll right"
                >
                    <ChevronRight className="size-4" />
                </button>
            )}
        </div>
    );
};
