import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import { type IOrderDetails } from "../_utils/utils";
import { useGetOrderDetails } from "@/api/order";
import { OrderDetails } from "../_components/common/order-details";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export function OrderDetailsPage() {
    const { data, isLoading } = useGetOrderDetails();
    const order = (data?.data?.details as IOrderDetails) || {};

    return (
        <>
            <SeoWrapper
                title={`Details of Order #${order?.code || ""}`}
                description="View your order details"
            />
            <BaseLayout>
                <LayoutContainer className="pb-10">
                    <BreadcrumbWrapper
                        className="my-4"
                        items={[
                            { title: "Orders", path: `/orders/${order?.code}` },
                            {
                                title: `#${order?.code || ""}`,
                            },
                        ]}
                    />

                    {isLoading ? (
                        <div className="h-[600px] flex items-center justify-center">
                            <Loading />
                        </div>
                    ) : (
                        <OrderDetails order={order} />
                    )}
                </LayoutContainer>
            </BaseLayout>
        </>
    );
}
