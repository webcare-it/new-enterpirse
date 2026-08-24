import { useEffect, useRef } from "react";
import { createPortal } from "react-dom";
import { motion, AnimatePresence } from "framer-motion";
import { X, LockKeyhole } from "lucide-react";
import { useCart, type ICartSummary } from "@/hooks/useCart";
import { Link } from "react-router-dom";
import { CartIcon } from "@/pages/public/_components/common/icon";
import type { ICartItem } from "@/type";
import { CartItem } from "./cart-item";
import { usePrice } from "@/hooks/usePrice";
import { EmptyCart } from "./empty-cart";
import {
    useGtmTracker,
    type IViewCartTrackerType,
} from "@/hooks/useGtmTracker";
import { renderVariation } from "@/helper";

export const CartDrawer = () => {
    const { items, drawerOpen, setDrawerOpen, summary } = useCart();

    useEffect(() => {
        document.body.style.overflow = drawerOpen ? "hidden" : "";
        return () => {
            document.body.style.overflow = "";
        };
    }, [drawerOpen]);

    useEffect(() => {
        if (!drawerOpen) return;
        const handler = (e: KeyboardEvent) => {
            if (e.key === "Escape") setDrawerOpen(false);
        };
        window.addEventListener("keydown", handler);
        return () => window.removeEventListener("keydown", handler);
    }, [drawerOpen, setDrawerOpen]);

    const target = document.getElementById("modal");
    if (!target) return null;

    return createPortal(
        <AnimatePresence>
            {drawerOpen && (
                <motion.div
                    key="cart-drawer"
                    className="fixed inset-0 z-[9999]"
                >
                    {/* Overlay */}
                    <motion.div
                        key="overlay"
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
                        key="panel"
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
                            items={items}
                            summary={summary}
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
    items,
    summary,
    onClose,
}: {
    items: ICartItem[];
    summary: ICartSummary;
    onClose: () => void;
}) => {
    const firedRef = useRef(false);
    const { viewCartTracker } = useGtmTracker();
    const { getPriceWithCurrency } = usePrice();

    useEffect(() => {
        if (firedRef.current) return;
        if (items?.length > 0) {
            firedRef.current = true;
            const d: IViewCartTrackerType = {
                value: summary?.subtotal || 0,
                items: items?.map((item, i) => ({
                    item_id: item?.product?.id?.toString() || "",
                    item_name: item?.product?.name || "",
                    item_price: item?.product?.price || 0,
                    item_quantity: item?.product?.quantity || 0,
                    item_variant:
                        renderVariation(item?.product?.variation) || "",
                    index: i || 0,
                })),
            };
            viewCartTracker(d);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [items]);

    return (
        <>
            <div
                style={{
                    scrollbarWidth: "none",
                    msOverflowStyle: "none",
                }}
                className="flex items-center justify-between px-3 md:px-5 h-16 md:h-24 border-b border-gray-200 shrink-0 overflow-x-hidden"
            >
                <div className="flex items-start gap-3 relative py-2">
                    <span className="text-2xl md:text-4xl font-bold text-gray-900">
                        Your Cart
                    </span>
                    <AnimatePresence>
                        {items?.length > 0 && (
                            <motion.span
                                key="count"
                                initial={{ opacity: 0, scale: 0.5 }}
                                animate={{ opacity: 1, scale: 1 }}
                                exit={{ opacity: 0, scale: 0.5 }}
                                className="text-sm font-medium"
                            >
                                {items?.length}
                            </motion.span>
                        )}
                    </AnimatePresence>
                </div>
                <button
                    onClick={onClose}
                    className="size-12 rounded-full flex items-center justify-center text-gray-900 hover:bg-gray-100 border transition-all duration-200 cursor-pointer"
                >
                    <X className="size-5" strokeWidth={2} />
                </button>
            </div>

            <div className="flex-1 overflow-y-auto overflow-x-hidden px-3 md:px-5 py-4 space-y-4 relative">
                <AnimatePresence mode="wait">
                    {items?.length === 0 ? (
                        <motion.div
                            key="empty"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            exit={{ opacity: 0, y: -20 }}
                            transition={{ duration: 0.3 }}
                            className="absolute inset-0 flex flex-col items-center justify-center gap-4 px-5"
                        >
                            <EmptyCart onClose={onClose} />
                        </motion.div>
                    ) : (
                        <motion.div
                            key="items"
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            className="space-y-4"
                        >
                            <AnimatePresence>
                                {items?.map((item) => (
                                    <motion.div
                                        style={{
                                            scrollbarWidth: "none",
                                            msOverflowStyle: "none",
                                        }}
                                        key={item.product.id}
                                        layout
                                        initial={{ opacity: 0, x: 40 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        exit={{
                                            opacity: 0,
                                            x: 40,
                                            height: 0,
                                            marginBottom: 0,
                                            paddingBottom: 0,
                                        }}
                                        transition={{
                                            duration: 0.3,
                                            ease: "easeInOut",
                                        }}
                                    >
                                        <CartItem item={item} />
                                    </motion.div>
                                ))}
                            </AnimatePresence>
                        </motion.div>
                    )}
                </AnimatePresence>
            </div>

            <AnimatePresence>
                {items?.length > 0 && (
                    <motion.div
                        key="footer"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: 20 }}
                        transition={{ duration: 0.3 }}
                        className="border-t border-gray-200 px-3 md:px-5 py-4 space-y-3 shrink-0"
                    >
                        <div className="flex items-center justify-between">
                            <span className="text-base font-medium text-gray-900">
                                Subtotal
                            </span>
                            <span className="text-xl font-semibold text-gray-950">
                                {getPriceWithCurrency(summary.subtotal || 0)}
                            </span>
                        </div>
                        <Link
                            to="/my-cart"
                            onClick={onClose}
                            className="w-full h-11 text-primary border border-primary rounded-full text-sm font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
                        >
                            <CartIcon className="size-4" /> View Cart
                        </Link>
                        <Link
                            to="/checkout"
                            onClick={onClose}
                            className="w-full h-11 bg-primary text-primary-foreground hover:bg-primary/80 rounded-full text-sm font-medium transition-all duration-300 cursor-pointer flex items-center justify-center gap-2"
                        >
                            <LockKeyhole className="size-4" />
                            Checkout
                        </Link>
                    </motion.div>
                )}
            </AnimatePresence>
        </>
    );
};

export const FloatingCart = () => {
    const { getPriceWithCurrency } = usePrice();
    const { items, drawerOpen, setDrawerOpen, summary } = useCart();

    return (
        <div
            onClick={() => setDrawerOpen(!drawerOpen)}
            className="fixed right-0 top-1/2 -translate-y-1/2 z-50 px-3 md:px-4 py-3 rounded-l-2xl bg-primary/95 text-primary-foreground shadow-lg flex flex-col items-center justify-center hover:bg-primary transition-all duration-200 cursor-pointer"
        >
            <div className="relative">
                <CartIcon className="size-6" />

                <span
                    className="
                            absolute -top-2 -right-2
                            min-w-4 h-4 px-1
                            rounded-full
                            bg-red-600
                            text-[10px] font-semibold
                            flex items-center justify-center
                        "
                >
                    {items?.length}
                </span>
            </div>
            <span className="text-xs md:text-sm font-semibold">
                {getPriceWithCurrency(summary.subtotal || 0)}
            </span>
        </div>
    );
};
