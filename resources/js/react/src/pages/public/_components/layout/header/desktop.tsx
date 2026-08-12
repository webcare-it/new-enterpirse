import { Menu, ChevronLeft, ChevronRight } from "lucide-react";
import {
    useCallback,
    useEffect,
    useLayoutEffect,
    useRef,
    useState,
} from "react";
import { motion, AnimatePresence } from "framer-motion";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Link, useLocation } from "react-router-dom";
import { CartIcon } from "../../common/icon";
import { useCart } from "@/hooks/useCart";
import type { ICategory } from "@/type";
import { HeaderLogo } from "@/components/common/logo";
import { DesktopSearch } from "./desktop-search";
import { useConfig } from "@/hooks/useConfig";
import { UserComponent } from "./user";
import { useWishlist } from "@/hooks/useWishlist";

interface Props {
    categoriesOpen: boolean;
    onToggleCategories: () => void;
}

export function DesktopHeader() {
    const config = useConfig();
    const { pathname } = useLocation();
    const { items: wishlist } = useWishlist();
    const { setDrawerOpen, items } = useCart();
    const categoriesListRef = useRef<HTMLDivElement>(null);
    const categoriesScrollRef = useRef<HTMLDivElement>(null);
    const [showCategories, setShowCategories] = useState(false);
    const categories = (config?.categories as ICategory[]) || [];
    const [canScrollCategories, setCanScrollCategories] = useState(false);

    const close = () => setShowCategories(false);
    const toggle = () => setShowCategories((prev) => !prev);
    const updateCategoryScrollControls = useCallback(() => {
        const scrollEl = categoriesScrollRef.current;
        const listEl = categoriesListRef.current;
        if (!scrollEl || !listEl) return;

        setCanScrollCategories(listEl.scrollWidth > scrollEl.clientWidth + 1);
    }, []);

    useLayoutEffect(() => {
        const frame = requestAnimationFrame(updateCategoryScrollControls);

        return () => cancelAnimationFrame(frame);
    }, [updateCategoryScrollControls]);

    useEffect(() => {
        const scrollEl = categoriesScrollRef.current;
        const listEl = categoriesListRef.current;
        if (!scrollEl || !listEl) return;

        const resizeObserver =
            typeof ResizeObserver !== "undefined"
                ? new ResizeObserver(updateCategoryScrollControls)
                : null;

        resizeObserver?.observe(scrollEl);
        resizeObserver?.observe(listEl);
        window.addEventListener("resize", updateCategoryScrollControls);

        return () => {
            resizeObserver?.disconnect();
            window.removeEventListener("resize", updateCategoryScrollControls);
        };
    }, [updateCategoryScrollControls]);

    return (
        <div className="hidden md:block bg-white border-b border-border">
            {/* Main Header */}
            <div className="w-[95%] mx-auto py-1.5 flex items-center justify-between gap-6">
                <HeaderLogo />

                {/* Search Bar */}
                <DesktopSearch />

                {/* Right Icons */}
                <div className="flex items-center gap-4">
                    <Link
                        to="/my-wishlist"
                        aria-label="View wishlist"
                        className="relative text-gray-900 cursor-pointer hover:text-primary transition p-1.5"
                    >
                        <svg
                            className="icon icon-wishlist icon-lg"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            strokeWidth="1.8"
                            width="24"
                            height="24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            role="presentation"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                            />
                        </svg>
                        {wishlist?.length > 0 && (
                            <span className="absolute -top-1 -right-0.5 bg-text-primary text-xs font-medium w-4 h-4 flex items-center justify-center">
                                {wishlist?.length}
                            </span>
                        )}
                    </Link>
                    <button
                        onClick={() => setDrawerOpen(true)}
                        aria-label={`Open cart${items?.length > 0 ? `, ${items.length} items` : ""}`}
                        className="relative text-gray-900 hover:text-primary transition p-1.5 cursor-pointer"
                    >
                        <CartIcon className="size-6" />
                        {items?.length > 0 && (
                            <span className="absolute -top-1 -right-0.5 bg-text-primary text-xs font-medium w-4 h-4 flex items-center justify-center">
                                {items?.length}
                            </span>
                        )}
                    </button>
                    <UserComponent type="header" />
                </div>
            </div>

            {/* Categories Bar */}
            <div className="border-t border-border bg-muted/20">
                <div className="w-[95%] mx-auto flex items-center h-10">
                    <button
                        onClick={toggle}
                        className={`flex items-center gap-2 h-full px-4 font-medium text-sm transition border-r border-border flex-shrink-0 cursor-pointer bg-primary text-primary-foreground`}
                    >
                        <Menu className="w-4 h-4" />
                        All Categories
                    </button>
                    <div className="relative flex-1 min-w-0 h-full overflow-hidden">
                        {canScrollCategories && (
                            <button
                                onClick={() => {
                                    categoriesScrollRef.current?.scrollBy({
                                        left: -200,
                                        behavior: "smooth",
                                    });
                                }}
                                className="absolute left-0 top-0 bottom-0 z-10 size-10 flex justify-center items-center bg-white text-gray-900 transition cursor-pointer border rounded-full"
                                aria-label="Scroll categories left"
                            >
                                <ChevronLeft className="w-4 h-4" />
                            </button>
                        )}
                        <div
                            ref={categoriesScrollRef}
                            id="categories-scroll"
                            className={`w-full min-w-0 overflow-x-auto scrollbar-hide h-full ${
                                canScrollCategories ? "pl-7 pr-7" : ""
                            }`}
                        >
                            <div
                                ref={categoriesListRef}
                                className="flex min-w-max items-center gap-1 h-full"
                            >
                                {categories?.map((cat, index) => (
                                    <Link
                                        key={`${cat?.id}-${index}`}
                                        to={`/categories/${cat?.slug}`}
                                        className={`text-sm hover:text-primary transition whitespace-nowrap px-3 h-full flex items-center flex-shrink-0 ${
                                            pathname.includes(cat?.slug)
                                                ? "text-primary"
                                                : "text-gray-900"
                                        }`}
                                    >
                                        {cat?.name}
                                    </Link>
                                ))}
                            </div>
                        </div>
                        {canScrollCategories && (
                            <button
                                onClick={() => {
                                    categoriesScrollRef.current?.scrollBy({
                                        left: 200,
                                        behavior: "smooth",
                                    });
                                }}
                                className="absolute right-0 top-0 bottom-0 z-10 size-10 flex justify-center items-center bg-white text-gray-900 transition cursor-pointer border rounded-full"
                                aria-label="Scroll categories right"
                            >
                                <ChevronRight className="w-4 h-4" />
                            </button>
                        )}
                    </div>
                </div>
            </div>
            <CategoryDropdown
                categories={categories}
                categoriesOpen={showCategories}
                onToggleCategories={close}
            />
        </div>
    );
}

interface Props {
    categories: ICategory[];
    categoriesOpen: boolean;
    onToggleCategories: () => void;
}

const CategoryDropdown = ({
    categories,
    categoriesOpen,
    onToggleCategories,
}: Props) => {
    const ref = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!categoriesOpen) return;

        const handleEscape = (e: KeyboardEvent) => {
            if (e.key === "Escape") onToggleCategories();
        };

        document.addEventListener("keydown", handleEscape);
        return () => document.removeEventListener("keydown", handleEscape);
    }, [categoriesOpen, onToggleCategories]);

    return (
        <AnimatePresence>
            {categoriesOpen && (
                <motion.div
                    ref={ref}
                    initial={{ opacity: 0, y: -8 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -8 }}
                    transition={{ duration: 0.2, ease: "easeOut" }}
                    className="hidden md:block absolute top-full left-0 w-full bg-white border-b border-border z-50 shadow-2xl"
                >
                    <div className="w-[95%] mx-auto py-6">
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            {categories?.map((cat, index) => (
                                <Link
                                    key={`${cat?.name}-${index}`}
                                    onClick={onToggleCategories}
                                    to={`/categories/${cat?.slug}`}
                                    className="flex items-center gap-4 rounded-xl border border-border hover:border-primary/30 transition cursor-pointer hover:bg-primary/5 overflow-hidden"
                                >
                                    <div
                                        className={`size-24 rounded-l-lg  flex items-center justify-center text-base font-bold flex-shrink-0 relative overflow-hidden`}
                                    >
                                        <OptimizedImage
                                            src={cat?.image}
                                            alt={cat?.name}
                                            className="absolute w-full h-full object-contain "
                                        />
                                    </div>
                                    <span className="text-base font-medium text-foreground">
                                        {cat?.name}
                                    </span>
                                </Link>
                            ))}
                        </div>
                    </div>
                </motion.div>
            )}
        </AnimatePresence>
    );
};
