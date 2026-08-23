import { Link, useSearchParams } from "react-router-dom";
import { motion } from "framer-motion";
import confetti from "canvas-confetti";
import { useEffect, useRef } from "react";
import { BaseLayout } from "../_components/layout/base-layout";
import { Button } from "@/components/ui/button";
import { ArrowLeft, ArrowRight } from "lucide-react";
import { usePrice } from "@/hooks/usePrice";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import {
    convertJsonToObject,
    getLocalStorage,
    setLocalStorage,
} from "@/helper";
import {
    useGtmTracker,
    type IPersonalInfoTracker,
    type IPurchaseTracker,
} from "@/hooks/useGtmTracker";
import type { IOrderSuccess } from "@/api/checkout";
import { GTM_PURCHASE_TRACKED } from "@/constant";

const formatDate = (date: string) => {
    return new Date(date).toLocaleString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
        timeZone: "Asia/Dhaka",
    });
};

export function SuccessPage() {
    const firedRef = useRef(false);
    const [searchParams] = useSearchParams();
    const { purchaseTracker } = useGtmTracker();
    const { getPriceWithCurrency } = usePrice();

    const o =
        (convertJsonToObject(
            searchParams.get("order") || "{}",
        ) as IOrderSuccess) || {};
    const c =
        (convertJsonToObject(
            searchParams.get("user") || "{}",
        ) as IPersonalInfoTracker) || {};
    const t =
        (convertJsonToObject(
            searchParams.get("tracker") || "{}",
        ) as IPurchaseTracker) || {};

    const fadeUp = {
        hidden: { opacity: 0, y: 12 },
        visible: { opacity: 1, y: 0 },
    };

    useEffect(() => {
        const duration = 2 * 1000;
        const animationEnd = Date.now() + duration;
        const defaults = {
            startVelocity: 30,
            spread: 360,
            ticks: 60,
            zIndex: 0,
        };

        const randomInRange = (min: number, max: number) =>
            Math.random() * (max - min) + min;

        const interval = window.setInterval(() => {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                return clearInterval(interval);
            }

            const particleCount = 50 * (timeLeft / duration);
            confetti({
                ...defaults,
                particleCount,
                origin: {
                    x: randomInRange(0.1, 0.3),
                    y: Math.random() - 0.2,
                },
            });
            confetti({
                ...defaults,
                particleCount,
                origin: {
                    x: randomInRange(0.7, 0.9),
                    y: Math.random() - 0.2,
                },
            });
        }, 250);

        return () => clearInterval(interval);
    }, []);

    useEffect(() => {
        if (firedRef.current) return;
        if (!o?.code) return;

        const trackedId = (() => {
            try {
                return getLocalStorage(GTM_PURCHASE_TRACKED);
            } catch {
                return null;
            }
        })();

        if (trackedId === o?.code) {
            firedRef.current = true;
            return;
        }

        firedRef.current = true;
        void purchaseTracker(t, c, o?.code);
        try {
            setLocalStorage(GTM_PURCHASE_TRACKED, o?.code);
        } catch {
            /* ignore */
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [t?.transaction_id]);

    return (
        <>
            <SeoWrapper
                title="Order Success"
                description="Your order has been placed successfully"
            />
            <BaseLayout>
                <div className="max-w-2xl mx-auto px-4 md:px-0 mt-4">
                    <div className="flex justify-center mb-8">
                        <div className="relative">
                            <div className="absolute inset-0 bg-green-200 rounded-full blur-xl opacity-50"></div>
                            <motion.div
                                initial={{ scale: 0, opacity: 0 }}
                                animate={{ scale: 1, opacity: 1 }}
                                transition={{
                                    type: "spring",
                                    stiffness: 200,
                                    damping: 15,
                                }}
                                className="relative bg-green-500 rounded-full p-4 w-20 h-20 flex items-center justify-center"
                            >
                                <svg
                                    className="w-10 h-10 text-white"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    strokeWidth={3}
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </motion.div>
                        </div>
                    </div>

                    {/* Heading Section */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={fadeUp}
                        transition={{ delay: 0.15, duration: 0.4 }}
                        className="text-center mb-6"
                    >
                        <h1 className="text-4xl sm:text-5xl font-bold text-gray-900 mb-3">
                            Order Confirmed!
                        </h1>
                        <p className="text-lg text-gray-600">
                            Thank you for your purchase. Your order has been
                            successfully placed.
                        </p>
                    </motion.div>

                    {/* Order Details Card */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={fadeUp}
                        transition={{ delay: 0.25, duration: 0.4 }}
                        className="bg-white rounded-2xl md:rounded-3xl border border-gray-200 p-4 md:p-8 mb-8"
                    >
                        {/* Order Number & Date */}
                        <div className="grid grid-cols-1 gap-4 mb-4">
                            <div className="text-center">
                                <p className="text-sm font-medium text-gray-500">
                                    Order Number
                                </p>
                                <p className="text-2xl sm:text-3xl font-bold text-gray-900">
                                    {o?.code}
                                </p>
                            </div>
                            <div className="text-center">
                                <p className="text-sm font-medium text-gray-500">
                                    Order Date
                                </p>
                                <p className="text-xl font-bold text-gray-900">
                                    {formatDate(o?.date as string)}
                                </p>
                            </div>
                        </div>

                        <hr className="my-4" />

                        {/* Order Summary */}
                        <div className="mb-5">
                            <h2 className="text-sm font-semibold text-gray-900 mb-5 uppercase tracking-wide">
                                Order Summary
                            </h2>
                            <div className="space-y-4">
                                <LabelValue
                                    label="Subtotal"
                                    value={getPriceWithCurrency(
                                        Number(o?.subtotal) || 0,
                                    )}
                                />
                                <LabelValue
                                    label="Shipping Cost"
                                    value={getPriceWithCurrency(
                                        Number(o?.shipping_cost) || 0,
                                    )}
                                />
                                <LabelValue
                                    label="Discount"
                                    value={getPriceWithCurrency(
                                        Number(o?.discount) || 0,
                                    )}
                                />
                                <LabelValue
                                    label="Coupon Discount"
                                    value={getPriceWithCurrency(
                                        Number(o?.coupon_discount) || 0,
                                    )}
                                />
                            </div>
                        </div>

                        <hr className="my-4" />

                        {/* Total */}
                        <div className="flex justify-between items-center">
                            <span className="text-lg font-semibold text-gray-900">
                                Total
                            </span>
                            <span className="text-3xl md:text-4xl font-bold text-primary">
                                {getPriceWithCurrency(
                                    Number(o?.grand_total) || 0,
                                )}
                            </span>
                        </div>
                    </motion.div>

                    {/* Action Buttons */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={fadeUp}
                        transition={{ delay: 0.35, duration: 0.4 }}
                        className="flex flex-col sm:flex-row gap-4 mb-8"
                    >
                        <Link to={`/orders/${o?.code}`} className="w-full">
                            <Button size="xl" className="w-full">
                                View Order Details
                                <ArrowRight className="w-4 h-4" />
                            </Button>
                        </Link>
                        <Link to="/" className="w-full">
                            <Button
                                size="xl"
                                variant="outline"
                                className="w-full"
                            >
                                <ArrowLeft className="w-4 h-4" />
                                Continue Shopping
                            </Button>
                        </Link>
                    </motion.div>

                    {/* Support Footer */}
                    <motion.div
                        initial="hidden"
                        animate="visible"
                        variants={fadeUp}
                        transition={{ delay: 0.45, duration: 0.4 }}
                        className="text-center pt-8 border-t border-gray-200"
                    >
                        <p className="text-sm text-gray-600 pb-4">
                            Questions? Contact our{" "}
                            <Link
                                to="/contact-us"
                                className="text-green-600 hover:text-green-700 font-semibold"
                            >
                                customer support
                            </Link>
                        </p>
                    </motion.div>
                </div>
            </BaseLayout>
        </>
    );
}

const LabelValue = ({ label, value }: { label: string; value: string }) => {
    return (
        <div className="flex justify-between items-center text-base">
            <span className="text-gray-700 font-medium">{label}</span>
            <span className=" text-gray-900 font-semibold">{value}</span>
        </div>
    );
};
