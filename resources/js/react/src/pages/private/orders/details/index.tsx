import { useParams, Link } from "react-router-dom";
import { ArrowLeft } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { DashboardLayout } from "../../_components/layout";
import { PageHeader } from "../../_components/common/page-header";
import { Button } from "@/components/ui/button";
import { NoDataFound } from "@/components/common/no-data-found";
import { Skeleton } from "@/components/common/skeleton";
import { OrderDetails } from "@/pages/public/_components/common/order-details";
import type { IOrderDetails } from "@/pages/public/_utils/utils";
import { useGetOrderDetails } from "@/api/order";

export const OrderDetailsPage = () => {
    const { id } = useParams();
    const { data, isLoading } = useGetOrderDetails();
    const order = (data?.data?.details as IOrderDetails) || null;

    return (
        <>
            <SeoWrapper title={`Order #${id || ""}`} />
            <DashboardLayout>
                <PageHeader
                    title={`Order #${id || ""}`}
                    description="View the full details of this order."
                    items={[
                        { title: "Orders", path: "/dashboard/orders" },
                        { title: `#${id || ""}` },
                    ]}
                    action={
                        <Button variant="outline" size="sm" asChild>
                            <Link to="/dashboard/orders">
                                <ArrowLeft className="size-4" />
                                Back to Orders
                            </Link>
                        </Button>
                    }
                />

                {isLoading ? (
                    <div className="space-y-6">
                        <Skeleton className="rounded-3xl" height="10rem" />
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <Skeleton
                                className="rounded-3xl md:col-span-2"
                                height="16rem"
                            />
                            <Skeleton
                                className="rounded-3xl"
                                height="16rem"
                            />
                        </div>
                    </div>
                ) : order ? (
                    <OrderDetails order={order} />
                ) : (
                    <NoDataFound
                        title="Order Not Found"
                        description="We couldn't find an order with that ID. Please check and try again."
                    />
                )}
            </DashboardLayout>
        </>
    );
};
