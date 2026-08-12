import { useEffect, useRef, useState, useCallback } from "react";

interface UseIntersectionObserverOptions {
    threshold?: number;
    rootMargin?: string;
    triggerOnce?: boolean;
}

export const useIntersectionObserver = (
    options: UseIntersectionObserverOptions = {},
) => {
    const [isIntersecting, setIsIntersecting] = useState(false);
    const [hasIntersected, setHasIntersected] = useState(false);
    const observerRef = useRef<IntersectionObserver | null>(null);
    const elementRef = useRef<HTMLDivElement | null>(null);

    const { threshold = 0.1, rootMargin = "0px" } = options;

    const ref = useCallback(
        (node: HTMLDivElement | null) => {
            elementRef.current = node;

            if (observerRef.current) {
                observerRef.current.disconnect();
            }

            if (node) {
                observerRef.current = new IntersectionObserver(
                    ([entry]) => {
                        const isElementIntersecting = entry.isIntersecting;
                        setIsIntersecting(isElementIntersecting);

                        if (isElementIntersecting && !hasIntersected) {
                            setHasIntersected(true);
                        }
                    },
                    {
                        threshold,
                        rootMargin,
                    },
                );
                observerRef.current.observe(node);
            }
        },
        [threshold, rootMargin, hasIntersected],
    );

    useEffect(() => {
        return () => {
            if (observerRef.current) {
                observerRef.current.disconnect();
            }
        };
    }, []);

    return { ref, isIntersecting, hasIntersected };
};
