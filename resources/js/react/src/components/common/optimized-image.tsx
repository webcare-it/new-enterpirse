import { useState, useEffect } from "react";
import { cn } from "@/lib/utils";

interface Props {
    src: string;
    alt?: string;
    className?: string;
    /**
     * Mark the image as the LCP / above-the-fold asset so it loads eagerly
     * with high priority instead of being lazy-loaded through a JS preloader.
     */
    priority?: boolean;
}

export const OptimizedImage = ({
    src,
    alt,
    className,
    priority = false,
}: Props) => {
    const [isLoaded, setIsLoaded] = useState(false);
    const [hasError, setHasError] = useState(false);

    useEffect(() => {
        if (priority) return;

        const img = new Image();

        img.onload = () => {
            setIsLoaded(true);
            setHasError(false);
        };

        img.onerror = () => {
            setHasError(true);
        };

        img.src = src;
    }, [priority, src]);

    if (priority) {
        return (
            <img
                src={src}
                alt={alt}
                className={cn(
                    "transition-opacity duration-300 opacity-100 relative w-full h-full",
                    className,
                )}
                loading="eager"
                decoding="async"
                fetchPriority="high"
            />
        );
    }

    if (!isLoaded || hasError) {
        return (
            <div
                className={cn(
                    "bg-gray-100 flex items-center justify-center w-full h-full object-cover relative rounded-2xl",
                    className,
                )}
            >
                <svg
                    className="size-6 md:size-8 text-gray-400"
                    fill="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path d="M8.5 13.5l2.5 3 3.5-4.5 4.5 6H5l3.5-4.5z" />
                    <rect
                        x="2"
                        y="2"
                        width="20"
                        height="20"
                        rx="2"
                        stroke="currentColor"
                        strokeWidth="1"
                        fill="none"
                    />
                </svg>
            </div>
        );
    }

    return (
        <img
            src={src}
            alt={alt}
            className={cn(
                "transition-opacity duration-300 opacity-100 relative w-full h-full",
                className,
            )}
            loading="lazy"
            decoding="async"
        />
    );
};
