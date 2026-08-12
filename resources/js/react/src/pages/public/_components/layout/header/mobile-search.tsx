import { useEffect, useState } from "react";
import { createPortal } from "react-dom";
import { motion, AnimatePresence } from "framer-motion";
import { X, Loader2 } from "lucide-react";
import { Link } from "react-router-dom";
import type { ICategory, IProduct } from "@/type";
import { CustomSearchIcon } from "../../common/icon";
import { OptimizedImage } from "@/components/common/optimized-image";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { useGetSearchProducts } from "@/api/product";
import { useConfig } from "@/hooks/useConfig";
import { usePrice } from "@/hooks/usePrice";

export const SearchDrawerMobile = ({
    drawerOpen,
    setDrawerOpen,
}: {
    drawerOpen: boolean;
    setDrawerOpen: (open: boolean) => void;
}) => {
    const [searchTerm, setSearchTerm] = useState("");
    const [debouncedTerm, setDebouncedTerm] = useState("");
    const { data, isLoading } = useGetSearchProducts(debouncedTerm, "");

    useEffect(() => {
        if (!searchTerm.trim()) {
            setDebouncedTerm("");
            return;
        }
        const timer = setTimeout(() => setDebouncedTerm(searchTerm), 500);
        return () => clearTimeout(timer);
    }, [searchTerm]);

    useEffect(() => {
        document.body.style.overflow = drawerOpen ? "hidden" : "";
        return () => {
            document.body.style.overflow = "";
        };
    }, [drawerOpen]);

    useEffect(() => {
        if (!drawerOpen) {
            setSearchTerm("");
            setDebouncedTerm("");
        }
    }, [drawerOpen]);

    const results: IProduct[] = data?.data?.products ?? [];

    const target = document.getElementById("modal");
    if (!target) return null;

    return createPortal(
        <AnimatePresence>
            {drawerOpen && (
                <motion.div
                    key="search-drawer"
                    className="fixed inset-0 z-[9999]"
                >
                    {/* Overlay */}
                    <motion.div
                        variants={{
                            hidden: { opacity: 0 },
                            visible: { opacity: 1 },
                        }}
                        initial="hidden"
                        animate="visible"
                        exit="hidden"
                        transition={{ duration: 0.8 }}
                        className="absolute inset-0 bg-black/50"
                        onClick={() => setDrawerOpen(false)}
                    />

                    {/* Drawer Panel */}
                    <motion.div
                        variants={{
                            hidden: {
                                x: "100%",
                                transition: {
                                    duration: 0.8,
                                    ease: [0.7, 0, 0.2, 1],
                                },
                            },
                            visible: {
                                x: 0,
                                transition: {
                                    duration: 0.8,
                                    ease: [0.7, 0, 0.2, 1],
                                },
                            },
                        }}
                        initial="hidden"
                        animate="visible"
                        exit="hidden"
                        className="fixed top-0 right-0 h-full w-full sm:w-[580px] bg-white shadow-2xl md:rounded-l-3xl flex flex-col"
                    >
                        <DrawerContent
                            searchTerm={searchTerm}
                            setSearchTerm={setSearchTerm}
                            results={results}
                            loading={isLoading}
                            onClose={() => setDrawerOpen(false)}
                        />
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>,
        target,
    );
};

const DrawerContent = ({
    searchTerm,
    setSearchTerm,
    results,
    loading,
    onClose,
}: {
    searchTerm: string;
    setSearchTerm: (term: string) => void;
    results: IProduct[];
    loading: boolean;
    onClose: () => void;
}) => {
    const config = useConfig();
    const { getPriceWithCurrency } = usePrice();
    const categories = (config?.categories as ICategory[]) || [];

    return (
        <>
            {/* Header */}
            <div className="flex items-center justify-between px-3 md:px-5 h-16 md:h-24 border-b border-gray-200 shrink-0">
                <div className="flex items-center gap-3">
                    <span className="text-2xl md:text-4xl font-bold text-gray-900">
                        Search
                    </span>
                </div>
                <button
                    onClick={onClose}
                    className="size-12 rounded-full flex items-center justify-center text-gray-900 hover:bg-gray-100 border transition-all duration-200"
                >
                    <X className="size-5" strokeWidth={2} />
                </button>
            </div>

            {/* Search Input */}
            <div className="px-2 md:px-4 py-3">
                <div className="relative">
                    <div className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">
                        <CustomSearchIcon />
                    </div>
                    <input
                        type="text"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        placeholder="Search products..."
                        className="w-full pl-10 pr-5 py-2.5 border border-gray-400 rounded-lg focus:outline-none text-base "
                        autoFocus
                    />
                </div>
            </div>

            {/* Results Area */}
            <div className="flex-1 overflow-y-auto px-3 md:px-4 py-2">
                <AnimatePresence mode="wait">
                    {searchTerm.trim() === "" ? (
                        <div className="flex flex-col gap-3">
                            <p className="text-gray-700 uppercase text-xs font-normal tracking-widest border-b  border-gray-400">
                                Popular Categories
                            </p>
                            <div className="flex flex-col gap-2">
                                {categories?.map((category) => (
                                    <Link
                                        key={category?.slug}
                                        to={`/categories/${category?.slug}`}
                                        className="group"
                                    >
                                        <span className="text-sm font-medium text-gray-900 truncate relative inline-block after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[1px] after:w-0 after:bg-gray-900 after:transition-all after:duration-300 group-hover:after:w-full">
                                            {category?.name}
                                        </span>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    ) : loading ? (
                        <div className="flex justify-center py-12">
                            <Loader2 className="size-8 animate-spin text-gray-400" />
                        </div>
                    ) : results.length > 0 ? (
                        <div className="space-y-4">
                            {results?.map((product, i) => (
                                <AnimationWrapper
                                    key={product?.id}
                                    initial={{
                                        opacity: 0,
                                        y: 30,
                                    }}
                                    whileInView={{
                                        opacity: 1,
                                        y: 0,
                                    }}
                                    transition={{
                                        duration: 0.35,
                                        delay: i * 0.03,
                                    }}
                                >
                                    <div
                                        key={product?.id}
                                        className="flex gap-3 md:gap-4 pb-4 border-b border-gray-100 last:border-0"
                                    >
                                        <Link to={`/products/${product?.slug}`}>
                                            <div className="relative size-20 md:size-24 rounded-lg bg-gray-50 overflow-hidden shrink-0">
                                                <OptimizedImage
                                                    src={product?.image || ""}
                                                    className="absolute hover:scale-105 w-full h-full object-cover transition-all duration-200"
                                                    alt={product?.name}
                                                />
                                            </div>
                                        </Link>
                                        <div className="flex-1 min-w-0">
                                            <Link
                                                to={`/products/${product?.slug}`}
                                                className="group relative inline-block max-w-full"
                                            >
                                                <h3 className="text-sm font-medium text-gray-900 truncate after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[1px] after:w-0 after:bg-gray-900 after:transition-all after:duration-300 group-hover:after:w-full">
                                                    {product?.name}
                                                </h3>
                                            </Link>
                                            <p className="text-xs text-gray-700 mt-1">
                                                Black XL
                                            </p>
                                            <p className="text-base font-semibold text-gray-950 mt-2">
                                                {getPriceWithCurrency(
                                                    product?.price,
                                                )}
                                            </p>
                                        </div>
                                    </div>
                                </AnimationWrapper>
                            ))}

                            {results?.length > 0 && (
                                <Link
                                    to={`/search?q=${encodeURIComponent(searchTerm)}`}
                                    onClick={onClose}
                                    className="block text-center text-primary font-medium py-4 hover:underline"
                                >
                                    View all results →
                                </Link>
                            )}
                        </div>
                    ) : (
                        <div className="text-center py-12 text-gray-500">
                            No products found for{" "}
                            <span className="font-medium">"{searchTerm}"</span>
                        </div>
                    )}
                </AnimatePresence>
            </div>
        </>
    );
};
