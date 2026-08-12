import {
    forwardRef,
    useState,
    useEffect,
    type ReactNode,
    type MouseEvent,
    useImperativeHandle,
} from "react";
import { createPortal } from "react-dom";
import { motion, AnimatePresence } from "framer-motion";
import { X } from "lucide-react";

type ModalWrapperProps = {
    title?: string;
    onHide?: () => void;
    width?: string;
    children: ReactNode;
};

export type ModalWrapperRef = {
    open: () => void;
    close: () => void;
};

export const ModalWrapper = forwardRef<ModalWrapperRef, ModalWrapperProps>(
    ({ title, onHide, width = "max-w-md", children }, ref) => {
        const [isOpen, setIsOpen] = useState(false);
        const [mounted, setMounted] = useState(false);

        useEffect(() => {
            setMounted(true);
            return () => setMounted(false);
        }, []);

        useImperativeHandle(ref, () => ({
            open: () => setIsOpen(true),
            close: () => setIsOpen(false),
        }));

        const handleClose = () => {
            setIsOpen(false);
            if (onHide) onHide();
        };

        const handlePropagation = (e: MouseEvent) => {
            e.stopPropagation();
        };

        if (!mounted) return null;

        const portalTarget = document.getElementById("modal");
        if (!portalTarget) return null;

        return createPortal(
            <AnimatePresence>
                {isOpen && (
                    <motion.div
                        style={{ zIndex: 9999 }}
                        className="fixed inset-0 flex items-center justify-center bg-black/10 backdrop-blur-xs"
                        onClick={handleClose}
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.25 }}
                    >
                        <motion.div
                            className={`bg-white dark:bg-gray-800 p-2 md:p-4 shadow-sm rounded-lg w-full relative ${width}`}
                            onClick={handlePropagation}
                            initial={{ opacity: 0, scale: 0.9, y: -30 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.9, y: -30 }}
                            transition={{ duration: 0.25 }}
                        >
                            <div className="flex justify-between items-center mb-2 md:mb-4">
                                <h2 className="text-base md:text-xl font-semibold text-foreground dark:text-gray-200 line-clamp-1">
                                    {title}
                                </h2>
                                <button
                                    onClick={handleClose}
                                    className="text-muted-foreground dark:text-gray-300 hover:text-foreground dark:hover:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition-colors duration-200 w-8 h-8 cursor-pointer flex items-center justify-center"
                                >
                                    <svg
                                        className="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <div
                                className="bg-background dark:bg-gray-800 h-auto overflow-y-auto"
                                style={{ maxHeight: "80vh" }}
                            >
                                {children}
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>,
            portalTarget,
        );
    },
);

ModalWrapper.displayName = "ModalWrapper";

export const Modal = ({ children }: { children: ReactNode }) => {
    const [isModalOpen, setIsModalOpen] = useState(false);

    const closeModal = () => setIsModalOpen(false);

    return (
        <>
            <AnimatePresence>
                {isModalOpen && (
                    <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                        {/* Animated Overlay */}
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            transition={{ duration: 0.4 }}
                            className="absolute inset-0 bg-black/50"
                            onClick={closeModal}
                        />

                        {/* Animated Modal Content */}
                        <motion.div
                            initial={{ opacity: 0, scale: 0.85, y: 30 }}
                            animate={{ opacity: 1, scale: 1, y: 0 }}
                            exit={{ opacity: 0, scale: 0.85, y: 30 }}
                            transition={{
                                duration: 0.4,
                                ease: [0.23, 1.0, 0.32, 1.0],
                            }}
                            className="relative w-full max-w-5xl h-fit z-10"
                        >
                            <div>{children}</div>
                        </motion.div>

                        {/* Close Button */}
                        <motion.button
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            onClick={closeModal}
                            className="absolute top-4 cursor-pointer md:top-8 right-4 md:right-8 text-white z-50"
                        >
                            <X size={36} />
                        </motion.button>
                    </div>
                )}
            </AnimatePresence>
        </>
    );
};
