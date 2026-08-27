import { useEffect, useState } from "react";
import { Search } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";
import { useSearchParams } from "react-router-dom";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { type IOrderDetails } from "../_utils/utils";
import { useTrackOrder } from "@/api/order";
import { OrderDetails } from "../_components/common/order-details";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import type { AxiosError } from "axios";

export function TrackOrderPage() {
    const [searchParams, setSearchParams] = useSearchParams();
    const [orderId, setOrderId] = useState(searchParams.get("id") || "");
    const { mutate, isPending, data, isSuccess, error, reset } =
        useTrackOrder();

    useEffect(() => {
        const id = searchParams.get("id");

        if (id) {
            setOrderId(id);
            reset();
            mutate(id);
        } else {
            setOrderId("");
            reset();
        }
    }, [mutate, reset, searchParams]);

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!orderId.trim()) return;
        setSearchParams({ id: orderId.trim() });
        reset();
        mutate(orderId.trim());
    };

    const handleBack = () => {
        setOrderId("");
        setSearchParams({});
        reset();
    };

    const axiosError = error as AxiosError<{ message?: string }> | null;

    const order = (data?.data?.details as IOrderDetails) || null;
    const notFound = isSuccess && !data?.data?.details;
    const showForm = !isPending && !order;

    const items = () => {
        return order
            ? [
                  { title: "Orders", path: `/orders/${order?.code}` },
                  { title: `#${order?.code}` },
              ]
            : [{ title: "Track Order" }];
    };

    return (
        <>
            <SeoWrapper
                title="Track Order"
                description="Track your order status"
            />
            <BaseLayout>
                <LayoutContainer className="pb-10">
                    <div className="flex justify-between items-center">
                        <BreadcrumbWrapper className="my-4" items={items()} />
                        {order && (
                            <Button
                                variant="outline"
                                size="sm"
                                onClick={handleBack}
                            >
                                &larr; Back
                            </Button>
                        )}
                    </div>

                    <AnimatePresence mode="wait">
                        {showForm && (
                            <motion.div
                                key="form"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                exit={{ opacity: 0, y: -20 }}
                                transition={{ duration: 0.3 }}
                                className="flex flex-col items-center justify-center min-h-[50vh] px-4"
                            >
                                <div className="w-full max-w-md text-center space-y-6">
                                    <div className="space-y-2">
                                        <h1 className="text-2xl md:text-4xl font-bold text-foreground">
                                            Track Your Order
                                        </h1>
                                        <p className="text-sm text-muted-foreground">
                                            Enter your order ID to check the
                                            latest status and delivery updates.
                                        </p>
                                    </div>

                                    <form
                                        onSubmit={handleSubmit}
                                        className="w-full"
                                    >
                                        <div className="flex h-11 w-full overflow-hidden rounded-xl border bg-background focus-within:ring-1 focus-within:ring-ring">
                                            {/* Input + Clear button */}
                                            <div className="relative flex min-w-0 flex-1 items-center">
                                                <Input
                                                    placeholder="Enter your Order ID"
                                                    name="order"
                                                    id="order"
                                                    type="text"
                                                    value={orderId}
                                                    onChange={(e) =>
                                                        setOrderId(
                                                            e.target.value,
                                                        )
                                                    }
                                                    className="h-full w-full rounded-none border-0 px-4 pr-10 shadow-none focus-visible:ring-0"
                                                />

                                                {/* Cross button */}
                                                <AnimatePresence>
                                                    {orderId && (
                                                        <motion.button
                                                            type="button"
                                                            initial={{
                                                                opacity: 0,
                                                                scale: 0.8,
                                                            }}
                                                            animate={{
                                                                opacity: 1,
                                                                scale: 1,
                                                            }}
                                                            exit={{
                                                                opacity: 0,
                                                                scale: 0.8,
                                                            }}
                                                            transition={{
                                                                duration: 0.15,
                                                            }}
                                                            onClick={() => {
                                                                setOrderId("");
                                                                reset();
                                                                setSearchParams(
                                                                    {},
                                                                );
                                                            }}
                                                            className="absolute right-2 flex size-7 items-center justify-center rounded-full text-red-600 transition hover:bg-muted"
                                                            aria-label="Clear order ID"
                                                        >
                                                            <span className="text-lg leading-none">
                                                                ×
                                                            </span>
                                                        </motion.button>
                                                    )}
                                                </AnimatePresence>
                                            </div>

                                            {/* Search button */}
                                            <button
                                                type="submit"
                                                disabled={
                                                    isPending || !orderId.trim()
                                                }
                                                className="flex h-full shrink-0 items-center justify-center gap-2 border-l bg-primary px-5 text-sm font-medium text-primary-foreground transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                <Search className="size-4" />

                                                <span>
                                                    {isPending
                                                        ? "Loading..."
                                                        : "Search"}
                                                </span>
                                            </button>
                                        </div>
                                    </form>

                                    {axiosError && (
                                        <motion.p
                                            initial={{ opacity: 0 }}
                                            animate={{ opacity: 1 }}
                                            className="text-sm text-destructive"
                                        >
                                            {axiosError.response?.data
                                                ?.message ||
                                                axiosError.message ||
                                                "Order not found. Please check the ID and try again."}
                                        </motion.p>
                                    )}

                                    {notFound && (
                                        <motion.p
                                            initial={{ opacity: 0 }}
                                            animate={{ opacity: 1 }}
                                            className="text-sm text-destructive"
                                        >
                                            Order not found. Please check the ID
                                            and try again.
                                        </motion.p>
                                    )}
                                </div>
                            </motion.div>
                        )}

                        {isPending && (
                            <motion.div
                                key="loading"
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                                transition={{ duration: 0.2 }}
                                className="h-[600px] flex items-center justify-center"
                            >
                                <Loading />
                            </motion.div>
                        )}

                        {order && (
                            <motion.div
                                key="details"
                                initial={{ opacity: 0, y: 30 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.4, ease: "easeOut" }}
                            >
                                <OrderDetails order={order} />
                            </motion.div>
                        )}
                    </AnimatePresence>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
}
