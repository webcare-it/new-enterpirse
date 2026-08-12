import { useEffect, useRef } from "react";
import PhotoSwipeLightbox from "photoswipe/lightbox";
import "photoswipe/style.css";
import type { IProductImage } from "./type";

interface Props {
    images: IProductImage[];
    isOpen: boolean;
    index: number;
    onClose: () => void;
}

export const PhotoSwipeGallery = ({
    images,
    isOpen,
    index,
    onClose,
}: Props) => {
    const lightboxRef = useRef<PhotoSwipeLightbox | null>(null);

    useEffect(() => {
        const lightbox = new PhotoSwipeLightbox({
            pswpModule: () => import("photoswipe"),
        });

        lightbox.on("close", () => {
            onClose();
        });

        lightbox.init();
        lightboxRef.current = lightbox;

        return () => {
            lightbox.destroy();
            lightboxRef.current = null;
        };
    }, []);

    useEffect(() => {
        if (isOpen && lightboxRef.current) {
            const clampedIndex = Math.min(
                index,
                Math.max(0, images.length - 1),
            );

            const dataSource = images.map((img) => ({
                src: img.src,
                alt: img.alt,
                w: 1920,
                h: 1920,
            }));

            lightboxRef.current.loadAndOpen(clampedIndex, dataSource);
        }
    }, [isOpen, index, images]);

    return null;
};
