import { useEffect, type ReactNode } from "react";
import { createPortal } from "react-dom";
import { motion, AnimatePresence } from "framer-motion";

interface QuickViewModalProps {
    isOpen: boolean;
    onClose: () => void;
    children: ReactNode;
}

const backdrop = {
    initial: { opacity: 0 },
    animate: {
        opacity: 1,
        transition: { duration: 0.3, ease: [0.25, 0.1, 0.25, 1] as const },
    },
    exit: {
        opacity: 0,
        transition: { duration: 0.2, ease: [0.4, 0, 1, 1] as const },
    },
};

const panel = {
    initial: { opacity: 0, scale: 0.92 },
    animate: {
        opacity: 1,
        scale: 1,
        transition: {
            duration: 0.35,
            ease: [0.22, 1, 0.36, 1] as const,
        },
    },
    exit: {
        opacity: 0,
        scale: 0.94,
        transition: {
            duration: 0.2,
            ease: [0.4, 0, 1, 1] as const,
        },
    },
};

export const QuickViewModal = ({
    isOpen,
    onClose,
    children,
}: QuickViewModalProps) => {
    useEffect(() => {
        document.body.style.overflow = isOpen ? "hidden" : "";
        return () => {
            document.body.style.overflow = "";
        };
    }, [isOpen]);

    useEffect(() => {
        if (!isOpen) return;
        const handler = (e: KeyboardEvent) => {
            if (e.key === "Escape") onClose();
        };
        window.addEventListener("keydown", handler);
        return () => window.removeEventListener("keydown", handler);
    }, [isOpen, onClose]);

    const target = document.getElementById("modal");
    if (!target) return null;

    return createPortal(
        <AnimatePresence>
            {isOpen && (
                <div className="fixed inset-0 z-[9999]">
                    {/* Backdrop */}
                    <motion.div
                        className="absolute inset-0 bg-black/60"
                        style={{
                            cursor: `url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'%3E%3Ccircle cx='12' cy='12' r='11' fill='%23000' fill-opacity='0.45'/%3E%3Cpath d='M8 8l8 8M16 8l-8 8' stroke='white' stroke-width='2' strokeLinecap='round'/%3E%3C/svg%3E") 12 12, pointer`,
                        }}
                        initial="initial"
                        animate="animate"
                        exit="exit"
                        variants={backdrop}
                        onClick={onClose}
                    />

                    {/* Panel */}
                    <div className="absolute inset-0 flex items-center justify-center pointer-events-none p-4">
                        <motion.div
                            className="pointer-events-auto w-full max-w-5xl max-h-[90vh] overflow-y-auto overflow-x-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 will-change-transform"
                            initial="initial"
                            animate="animate"
                            exit="exit"
                            variants={panel}
                            onClick={(e) => e.stopPropagation()}
                        >
                            {children}
                        </motion.div>
                    </div>
                </div>
            )}
        </AnimatePresence>,
        target,
    );
};
