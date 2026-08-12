import { motion } from "framer-motion";
import {
    Package,
    Truck,
    CreditCard,
    Phone,
    Mail,
    MapPinIcon,
    ClipboardList,
    CircleCheck,
    Download,
    Loader2,
} from "lucide-react";
import { Badge } from "@/components/ui/badge";
import { Card, CardContent } from "@/components/ui/card";
import { Separator } from "@/components/ui/separator";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { convertJsonToObject, renderVariation } from "@/helper";
import { usePrice } from "@/hooks/usePrice";
import { useDownloadInvoice } from "@/api/order";
import { toast } from "react-hot-toast";
import {
    formatDate,
    orderSteps,
    statusBadgeVariant,
    type IOrderDetails,
} from "../../_utils/utils";
import { CancelledState, OrderSteps } from "./steps";

export function OrderDetails({ order }: { order: IOrderDetails }) {
    const { getPriceWithCurrency } = usePrice();
    const { mutate: downloadInvoice, isPending } = useDownloadInvoice();

    const currentStepIndex = orderSteps?.findIndex(
        (s) => s?.id === order?.status,
    );
    const isCancelled = order?.status === "cancelled";

    const handleDownloadInvoice = () => {
        downloadInvoice(order.id, {
            onSuccess: (response) => {
                const blob = new Blob([response.data], {
                    type: "application/pdf",
                });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement("a");
                link.href = url;
                link.download = `invoice-${order.code}.pdf`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
                toast.success("Invoice downloaded successfully");
            },
            onError: () => {
                toast.error("Failed to download invoice. Please try again.");
            },
        });
    };

    return (
        <div className="space-y-6">
            <Card className="border overflow-hidden rounded-3xl">
                <div className="bg-gradient-to-r from-primary/5 via-primary/10 to-primary/5 px-6 py-4 border-b">
                    <div className="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <h1 className="text-xl font-bold text-foreground flex items-center gap-2">
                                <ClipboardList className="w-5 h-5 text-primary" />
                                Order #{order?.code}
                            </h1>
                            <p className="text-sm text-muted-foreground mt-0.5">
                                Placed on {formatDate(order?.date)}
                            </p>
                        </div>
                        <Badge className="text-sm px-4 py-1.5 rounded-full uppercase">
                            {order?.status}
                        </Badge>
                    </div>
                </div>
                <CardContent className="p-4 md:p-6">
                    {isCancelled ? (
                        <CancelledState />
                    ) : (
                        <OrderSteps currentStepIndex={currentStepIndex} />
                    )}
                </CardContent>
            </Card>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="md:col-span-2 space-y-6">
                    <Card className="border rounded-3xl">
                        <div className="bg-gradient-to-r from-muted/50 to-muted/30 px-6 py-3 border-b">
                            <h2 className="font-semibold text-foreground flex items-center gap-2">
                                <Package className="w-4 h-4 text-primary" />
                                Items ({order.products.length})
                            </h2>
                        </div>
                        <CardContent className="p-0 divide-y">
                            {order?.products?.map((product, i) => {
                                const variation = convertJsonToObject(
                                    product?.variation,
                                );

                                return (
                                    <motion.div
                                        key={product.id}
                                        initial={{
                                            opacity: 0,
                                            y: 10,
                                        }}
                                        animate={{
                                            opacity: 1,
                                            y: 0,
                                        }}
                                        transition={{
                                            delay: i * 0.05,
                                            duration: 0.3,
                                        }}
                                        className="flex gap-4 p-4 md:p-5 hover:bg-muted/30 transition-colors"
                                    >
                                        <div className="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden bg-muted flex-shrink-0 border">
                                            <OptimizedImage
                                                src={product?.image}
                                                alt={product?.name}
                                                className="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <Link
                                                to={`/products/${product?.slug}`}
                                                className="text-sm md:text-base font-semibold text-foreground hover:text-primary truncate block transition-colors"
                                            >
                                                {product?.name}
                                            </Link>
                                            {variation && (
                                                <p className="text-xs text-muted-foreground mt-1">
                                                    {renderVariation(variation)}
                                                </p>
                                            )}
                                            <div className="flex items-center justify-between mt-2">
                                                <span className="text-sm text-muted-foreground">
                                                    Qty: {product?.quantity}
                                                </span>
                                                <span className="text-sm font-bold text-foreground">
                                                    {getPriceWithCurrency(
                                                        product?.price *
                                                            product?.quantity,
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </motion.div>
                                );
                            })}
                        </CardContent>
                    </Card>

                    <Card className="border rounded-3xl">
                        <div className="bg-gradient-to-r from-muted/50 to-muted/30 px-6 py-3 border-b">
                            <h2 className="font-semibold text-foreground flex items-center gap-2">
                                <Truck className="w-4 h-4 text-primary" />
                                Shipping Details
                            </h2>
                        </div>
                        <CardContent className="p-5 space-y-3">
                            <div className="flex items-start gap-3">
                                <MapPinIcon className="w-4 h-4 text-muted-foreground mt-0.5" />
                                <div>
                                    <p className="font-medium text-foreground">
                                        {order?.shipping?.name}
                                    </p>
                                    <p className="text-sm text-muted-foreground">
                                        {order?.shipping?.address}
                                    </p>
                                </div>
                            </div>
                            {order?.shipping?.email && (
                                <div className="flex items-center gap-3 text-sm">
                                    <Mail className="w-4 h-4 text-muted-foreground" />
                                    <span className="text-muted-foreground">
                                        {order?.shipping?.email}
                                    </span>
                                </div>
                            )}

                            <div className="flex items-center gap-3 text-sm">
                                <Phone className="w-4 h-4 text-muted-foreground" />
                                <span className="text-muted-foreground">
                                    {order?.shipping?.phone}
                                </span>
                            </div>
                            {order?.shipping?.notes && (
                                <div className="flex items-start gap-3 text-sm pt-2 border-t">
                                    <ClipboardList className="w-4 h-4 text-muted-foreground mt-0.5" />
                                    <span className="text-muted-foreground italic">
                                        "{order?.shipping?.notes}"
                                    </span>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <div className="space-y-6">
                    <Card className="border rounded-3xl">
                        <div className="bg-gradient-to-r from-muted/50 to-muted/30 px-6 py-3 border-b">
                            <h2 className="font-semibold text-foreground flex items-center gap-2">
                                <CircleCheck className="w-4 h-4 text-primary" />
                                Order Summary
                            </h2>
                        </div>
                        <CardContent className="p-5 space-y-3">
                            <SummaryRow
                                label="Subtotal"
                                value={getPriceWithCurrency(
                                    order?.summary?.subtotal,
                                )}
                            />
                            <SummaryRow
                                label="Discount"
                                value={`-${getPriceWithCurrency(order?.summary?.discount)}`}
                                valueClass="text-green-600"
                            />
                            <SummaryRow
                                label="Coupon Discount"
                                value={`-${getPriceWithCurrency(order?.summary.coupon_discount)}`}
                                valueClass="text-green-600"
                            />
                            <SummaryRow
                                label="Tax"
                                value={getPriceWithCurrency(
                                    order?.summary?.tax,
                                )}
                            />
                            <SummaryRow
                                label="Shipping"
                                value={
                                    order?.summary?.shipping_cost === 0
                                        ? "Free"
                                        : getPriceWithCurrency(
                                              order.summary.shipping_cost,
                                          )
                                }
                            />
                            <Separator />
                            <div className="flex justify-between text-base">
                                <span className="font-bold text-foreground">
                                    Total
                                </span>
                                <span className="font-bold text-lg text-primary">
                                    {getPriceWithCurrency(
                                        order?.summary?.total,
                                    )}
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="border rounded-3xl">
                        <div className="bg-gradient-to-r from-muted/50 to-muted/30 px-6 py-3 border-b">
                            <h2 className="font-semibold text-foreground flex items-center gap-2">
                                <CreditCard className="w-4 h-4 text-primary" />
                                Payment
                            </h2>
                        </div>
                        <CardContent className="p-5 space-y-3">
                            <div className="flex items-center justify-between text-sm">
                                <span className="text-muted-foreground">
                                    Method
                                </span>
                                <span className="font-medium text-foreground">
                                    {order?.payment_method_title}
                                </span>
                            </div>
                            <div className="flex items-center justify-between text-sm">
                                <span className="text-muted-foreground">
                                    Status
                                </span>
                                <Badge
                                    variant={
                                        statusBadgeVariant[
                                            order?.payment_status
                                        ]
                                    }
                                    className="text-xs capitalize"
                                >
                                    {order?.payment_status}
                                </Badge>
                            </div>
                            <div className="flex items-center justify-between text-sm">
                                <span className="text-muted-foreground">
                                    Date
                                </span>
                                <span className="font-medium text-foreground">
                                    {formatDate(order.date)}
                                </span>
                            </div>
                        </CardContent>
                    </Card>

                    <div className="flex flex-col gap-3">
                        <div className="flex flex-col  gap-4">
                            <Button
                                size="lg"
                                className="w-full"
                                onClick={handleDownloadInvoice}
                                disabled={isPending || isCancelled}
                            >
                                {isPending ? (
                                    <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                                ) : (
                                    <Download className="w-4 h-4 mr-2" />
                                )}
                                {isPending
                                    ? "Downloading..."
                                    : "Download Invoice"}
                            </Button>
                            <Button
                                size="lg"
                                className="w-full"
                                variant="outline"
                            >
                                <Phone className="w-4 h-4 mr-2" /> Contact
                                Support
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

function SummaryRow({
    label,
    value,
    valueClass,
}: {
    label: string;
    value: string;
    valueClass?: string;
}) {
    return (
        <div className="flex justify-between text-sm">
            <span className="text-gray-600">{label}</span>
            <span className={`font-medium text-gray-800 ${valueClass ?? ""}`}>
                {value}
            </span>
        </div>
    );
}
