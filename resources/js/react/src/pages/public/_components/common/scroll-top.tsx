import { useEffect, useState } from "react";

interface ScrollToTopProps {
    className?: string;
}

export function ScrollToTop({ className = "" }: ScrollToTopProps) {
    const [isVisible, setIsVisible] = useState(false);

    useEffect(() => {
        const toggleVisibility = () => {
            if (window.scrollY > 300) {
                setIsVisible(true);
            } else {
                setIsVisible(false);
            }
        };

        window.addEventListener("scroll", toggleVisibility);
        return () => window.removeEventListener("scroll", toggleVisibility);
    }, []);

    const scrollToTop = () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth",
        });
    };

    if (!isVisible) return null;

    return (
        <button
            onClick={scrollToTop}
            aria-label="Scroll to top"
            className={`
       hidden fixed bottom-4 right-4 z-50
        md:flex h-12 w-12 items-center justify-center
        rounded-full bg-primary text-primary-foreground
        transition-all duration-300 border border-primary
         hover:scale-110
        active:scale-95
        ${className}
      `}
        >
            <svg
                width="20"
                height="20"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                strokeWidth="2.5"
                strokeLinecap="round"
                strokeLinejoin="round"
            >
                <path d="M12 19V5M5 12l7-7 7 7" />
            </svg>
        </button>
    );
}
