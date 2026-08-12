import { motion } from "framer-motion";
import { Loader2 } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { DashboardLayout } from "../_components/layout";
import { PageHeader } from "../_components/common/page-header";
import { OrderList } from "../_components/common/order-list";
import { NoDataFound } from "@/components/common/no-data-found";
import { Skeleton } from "@/components/common/skeleton";
import { Button } from "@/components/ui/button";
import { useGetOrderList } from "@/api/order";
import type { IOrderListItem } from "../_utils/types";

export const OrdersPage = () => {
    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
        useGetOrderList();

    const pages = data?.pages || [];
    const orders =
        pages?.flatMap(
            (page) => (page?.data?.orders as IOrderListItem[]) || [],
        ) || [];

    return (
        <>
            <SeoWrapper title="Purchase History" />
            <DashboardLayout>
                <PageHeader
                    title="Purchase History"
                    description="View and track all of your past orders."
                    items={[{ title: "Orders" }]}
                />

                {isLoading ? (
                    <div className="rounded-2xl border bg-card overflow-hidden">
                        <div className="p-4 space-y-4">
                            {Array.from({ length: 6 }).map((_, i) => (
                                <Skeleton
                                    key={i}
                                    className="rounded-xl"
                                    height="3rem"
                                />
                            ))}
                        </div>
                    </div>
                ) : orders.length > 0 ? (
                    <motion.div
                        initial={{ opacity: 0, y: 12 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.3 }}
                        className="overflow-x-auto rounded-2xl border bg-card"
                    >
                        <OrderList orders={orders} />

                        {hasNextPage && (
                            <div className="flex justify-center py-6 border-t">
                                <Button
                                    variant="outline"
                                    onClick={() => fetchNextPage()}
                                    disabled={isFetchingNextPage}
                                    className="gap-2"
                                >
                                    {isFetchingNextPage ? (
                                        <Loader2 className="size-4 animate-spin" />
                                    ) : null}
                                    {isFetchingNextPage
                                        ? "Loading..."
                                        : "Load More"}
                                </Button>
                            </div>
                        )}
                    </motion.div>
                ) : (
                    <NoDataFound
                        title="No Orders Yet"
                        description="You haven't placed any orders yet. Start shopping to see your orders here."
                    />
                )}
            </DashboardLayout>
        </>
    );
};
