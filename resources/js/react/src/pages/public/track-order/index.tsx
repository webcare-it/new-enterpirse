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
                                className="flex flex-col items-center justify-center min-h-[40vh] px-4"
                            >
                                <div className="w-full max-w-md text-center space-y-6">
                                    <div className="space-y-2">
                                        <h1 className="text-2xl md:text-4xl font-bold text-foreground">
                                            Track Your Order
                                        </h1>
                                        <p className="text-sm text-muted-foreground">
                                            Enter your order ID to track its
                                            status and details.
                                        </p>
                                    </div>

                                    <form
                                        onSubmit={handleSubmit}
                                        className="flex gap-2"
                                    >
                                        <Input
                                            placeholder="Enter your Order ID"
                                            name="order"
                                            id="order"
                                            type="text"
                                            className="h-11 rounded-xl px-4"
                                            value={orderId}
                                            onChange={(e) =>
                                                setOrderId(e.target.value)
                                            }
                                        />
                                        <button
                                            type="submit"
                                            disabled={isPending}
                                            className="h-11 px-5 rounded-2xl bg-primary text-primary-foreground hover:bg-primary/90 transition flex items-center justify-center font-medium text-sm cursor-pointer shrink-0"
                                        >
                                            <Search className="mr-2 size-4" />{" "}
                                            {isPending
                                                ? "Loading..."
                                                : "Search"}
                                        </button>
                                    </form>

                                    {error && (
                                        <motion.p
                                            initial={{ opacity: 0 }}
                                            animate={{ opacity: 1 }}
                                            className="text-sm text-destructive"
                                        >
                                            {error?.message ||
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
