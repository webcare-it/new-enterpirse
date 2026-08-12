import { useState, useCallback } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronLeft, ChevronRight, Maximize2 } from "lucide-react";
import type { IProductImage } from "./type";

interface Props {
    onOpenModal: (index: number) => void;
    productImages: IProductImage[];
    selectedImageIndex: number;
    setSelectedImageIndex: (index: number) => void;
}

export const ProductImageSliderMobile = ({
    onOpenModal,
    productImages,
    selectedImageIndex: current,
    setSelectedImageIndex,
}: Props) => {
    const [direction, setDirection] = useState(0);

    const goTo = useCallback(
        (next: number) => {
            setDirection(next > current ? 1 : -1);
            setSelectedImageIndex(next);
        },
        [current, setSelectedImageIndex],
    );

    const prev = () =>
        goTo((current - 1 + productImages.length) % productImages.length);
    const next = () => goTo((current + 1) % productImages.length);

    return (
        <div className="md:hidden flex flex-col gap-3">
            <div className="relative aspect-square rounded-2xl overflow-hidden bg-gray-100">
                <AnimatePresence custom={direction} mode="popLayout">
                    <motion.img
                        key={current}
                        src={productImages[current].src}
                        alt={productImages[current].alt}
                        onClick={() => {
                            onOpenModal(current);
                        }}
                        custom={direction}
                        variants={{
                            enter: (dir: number) => ({
                                x: dir > 0 ? 60 : -60,
                                opacity: 0,
                            }),
                            center: {
                                x: 0,
                                opacity: 1,
                                transition: {
                                    duration: 0.35,
                                    ease: [0.25, 0.1, 0.25, 1],
                                },
                            },
                            exit: (dir: number) => ({
                                x: dir > 0 ? -60 : 60,
                                opacity: 0,
                                transition: {
                                    duration: 0.25,
                                    ease: [0.4, 0, 1, 1],
                                },
                            }),
                        }}
                        initial="enter"
                        animate="center"
                        exit="exit"
                        className="absolute inset-0 w-full h-full object-cover"
                    />
                </AnimatePresence>
                <button
                    type="button"
                    onClick={() => onOpenModal(current)}
                    className="absolute flex items-center justify-center right-2 top-2 bg-gray-100 rounded-full z-20 p-2 hover:bg-gray-200"
                >
                    <Maximize2 className="size-4 text-gray-900" />
                </button>

                {/* Arrow buttons */}
                <button
                    onClick={prev}
                    className="absolute left-1 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow hover:bg-white transition"
                >
                    <ChevronLeft className="size-5" />
                </button>
                <button
                    onClick={next}
                    className="absolute right-1 top-1/2 -translate-y-1/2 z-10 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow hover:bg-white transition"
                >
                    <ChevronRight className="size-5" />
                </button>
            </div>

            {/* Dot pagination */}
            <div className="flex justify-center gap-2">
                {productImages?.map((_: IProductImage, i: number) => (
                    <button
                        key={i}
                        onClick={() => goTo(i)}
                        className="p-1"
                        aria-label={`Go to slide ${i + 1}`}
                    >
                        <motion.span
                            className="block h-2 rounded-full bg-gray-800 dark:bg-white"
                            animate={{
                                width: i === current ? 22 : 8,
                                opacity: i === current ? 1 : 0.35,
                            }}
                            transition={{
                                duration: 0.3,
                                ease: "easeInOut",
                            }}
                        />
                    </button>
                ))}
            </div>
        </div>
    );
};
