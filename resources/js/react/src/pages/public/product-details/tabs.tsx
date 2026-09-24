import { useEffect, useRef, useState } from "react";
import { motion, AnimatePresence } from "framer-motion";
import { ChevronDown, ChevronUp, Play } from "lucide-react";
import { RenderHtml } from "@/components/html";
import type { IProductDetails } from "./type";
import { getYouTubeEmbedUrl } from "@/helper";
import { ReviewItem, ReviewSummary, Reviews } from "./review";

type TabId = "details" | "reviews" | "shipping" | "video";

// const shippingInfo = [
//     {
//         icon: <Truck className="size-5" />,
//         title: "Standard delivery",
//         desc: "3–5 business days · Free on orders over $50",
//         badge: { label: "Free", cls: "bg-green-100 text-green-800" },
//     },
//     {
//         icon: <Zap className="size-5" />,
//         title: "Express delivery",
//         desc: "1–2 business days · Order before 2 PM",
//         badge: { label: "$8.99", cls: "bg-gray-100 text-gray-600" },
//     },
//     {
//         icon: <RefreshCw className="size-5" />,
//         title: "Returns",
//         desc: "30-day hassle-free returns. Item must be unused and in original packaging.",
//     },
//     {
//         icon: <ShieldCheck className="size-5" />,
//         title: "Secure packaging",
//         desc: "Every order is packed with care and fully insured during transit.",
//     },
// ];

export const ProductInfoTabs = ({ product }: { product: IProductDetails }) => {
    const [active, setActive] = useState<TabId>("details");
    const [expanded, setExpanded] = useState(false);
    const [isOverflowing, setIsOverflowing] = useState(false);
    const descriptionRef = useRef<HTMLDivElement>(null);
    const tabsRef = useRef<HTMLDivElement>(null);

    const previewReviews = product.review?.items?.slice(0, 2) ?? [];

    useEffect(() => {
        if (active !== "details") return;
        const el = descriptionRef.current;
        if (!el) return;

        const check = () => {
            if (!expanded) {
                setIsOverflowing(el.scrollHeight > el.clientHeight + 1);
            }
        };
        check();

        const observer =
            typeof ResizeObserver !== "undefined"
                ? new ResizeObserver(check)
                : null;
        observer?.observe(el);

        return () => observer?.disconnect();
    }, [active, expanded, product?.description]);

    const showAllReviews = () => {
        setActive("reviews");
        tabsRef.current?.scrollIntoView({ behavior: "smooth", block: "start" });
    };

    const TABS: { id: TabId; label: string; count?: number }[] = [
        { id: "details", label: "Details" },
        {
            id: "reviews",
            label: "Reviews",
            count: product.review?.reviews_count ?? 0,
        },
        { id: "video", label: "Video" },
    ];

    return (
        <div
            ref={tabsRef}
            className="pt-4 border-t border-gray-200 pb-16 md:pb-20 scroll-mt-24"
        >
            {/* Tab buttons */}
            <div className="flex border-b border-gray-200 overflow-x-auto">
                {TABS?.map((tab) => (
                    <button
                        key={tab.id}
                        onClick={() => setActive(tab.id)}
                        className={`relative cursor-pointer px-4 py-2.5 text-sm font-medium whitespace-nowrap transition-colors ${
                            active === tab.id
                                ? "text-gray-900"
                                : "text-gray-700 hover:text-gray-800"
                        }`}
                    >
                        {tab.label}
                        {tab.count !== undefined && (
                            <span className="ml-1.5 text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                {tab.count}
                            </span>
                        )}
                        {active === tab.id && (
                            <motion.div
                                layoutId="tab-underline"
                                className="absolute bottom-0 left-0 right-0 h-0.5 bg-gray-900"
                            />
                        )}
                    </button>
                ))}
            </div>

            {/* Tab panels */}
            <AnimatePresence mode="wait">
                <motion.div
                    key={active}
                    initial={{ opacity: 0, y: 6 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -6 }}
                    transition={{ duration: 0.2 }}
                    className="pt-5"
                >
                    {/* Details */}
                    {active === "details" && (
                        <div className="space-y-6">
                            <div>
                                <div
                                    ref={descriptionRef}
                                    className={`relative overflow-hidden transition-[max-height] duration-300 ${
                                        expanded ? "" : "max-h-48"
                                    }`}
                                >
                                    <RenderHtml
                                        html={
                                            (product?.description as string) ||
                                            ""
                                        }
                                    />
                                    {isOverflowing && !expanded && (
                                        <div className="pointer-events-none absolute inset-x-0 bottom-0 h-12 bg-gradient-to-t from-white to-transparent" />
                                    )}
                                </div>
                                {isOverflowing && (
                                    <button
                                        onClick={() =>
                                            setExpanded((prev) => !prev)
                                        }
                                        className="mt-2 inline-flex items-center gap-1 text-sm font-medium text-primary cursor-pointer hover:underline"
                                    >
                                        {expanded ? "See less" : "See more"}
                                        {expanded ? (
                                            <ChevronUp className="size-4" />
                                        ) : (
                                            <ChevronDown className="size-4" />
                                        )}
                                    </button>
                                )}
                            </div>

                            <div className="space-y-3">
                                <h3 className="text-base font-semibold text-gray-900">
                                    Customer Reviews
                                </h3>
                                <ReviewSummary product={product} />
                                {previewReviews.map((item) => (
                                    <ReviewItem key={item?.id} item={item} />
                                ))}
                                <button
                                    onClick={showAllReviews}
                                    className="w-full rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-900 cursor-pointer hover:bg-gray-50 transition"
                                >
                                    {(product.review?.items?.length ?? 0) > 0
                                        ? "See all reviews"
                                        : "Write a review"}
                                </button>
                            </div>
                        </div>
                    )}

                    {/* Reviews */}
                    {active === "reviews" && <Reviews product={product} />}

                    {/* Shipping */}
                    {/* {active === "shipping" && (
                        <div className="divide-y divide-gray-100">
                            {shippingInfo.map((s) => (
                                <div
                                    key={s.title}
                                    className="flex items-start gap-3 py-3"
                                >
                                    <div className="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 flex-shrink-0">
                                        {s.icon}
                                    </div>
                                    <div className="flex-1">
                                        <p className="text-sm font-medium text-gray-900">
                                            {s.title}
                                        </p>
                                        <p className="text-sm text-gray-500 leading-relaxed">
                                            {s.desc}
                                        </p>
                                    </div>
                                    {s.badge && (
                                        <span
                                            className={`text-xs px-2.5 py-1 rounded-full font-medium ${s.badge.cls}`}
                                        >
                                            {s.badge.label}
                                        </span>
                                    )}
                                </div>
                            ))}
                        </div>
                    )} */}

                    {/* Video */}
                    {active === "video" && (
                        <>
                            {product?.yt_video_id ? (
                                <div className="aspect-video bg-muted rounded-lg overflow-hidden">
                                    <iframe
                                        src={
                                            getYouTubeEmbedUrl(
                                                product?.yt_video_id,
                                            ) || ""
                                        }
                                        title="Product Video"
                                        className="w-full h-full"
                                        frameBorder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowFullScreen
                                    />
                                </div>
                            ) : (
                                <div className="aspect-video md:aspect-[16/3] bg-gray-100 rounded-3xl flex flex-col items-center justify-center gap-3 cursor-pointer group">
                                    <div className="w-14 h-14 rounded-full bg-white border border-gray-200 flex items-center justify-center group-hover:scale-105 transition">
                                        <Play className="size-6 text-gray-800 ml-1" />
                                    </div>
                                    <p className="text-sm text-gray-500">
                                        Now Video Available
                                    </p>
                                    <p className="text-xs text-gray-400">
                                        Next time it will be available
                                    </p>
                                </div>
                            )}
                        </>
                    )}
                </motion.div>
            </AnimatePresence>
        </div>
    );
};
