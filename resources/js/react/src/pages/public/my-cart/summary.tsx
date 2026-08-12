import { Button } from "@/components/ui/button";
import { Separator } from "@/components/ui/separator";
import { useCart } from "@/hooks/useCart";
import { usePrice } from "@/hooks/usePrice";
import { Lock } from "lucide-react";
import { Link } from "react-router-dom";
import { Coupon } from "../_components/common/coupon";

export const MyCartSummary = () => {
    const { summary, items } = useCart();
    const { getPriceWithCurrency } = usePrice();

    return (
        <div className="lg:col-span-1">
            <div className="bg-card rounded-3xl border shadow-xl p-4 md:p-6 space-y-4 md:sticky md:top-32">
                <h2 className="text-lg font-semibold uppercase">
                    Order Summary
                </h2>
                <div className="space-y-3 text-sm">
                    <LabelValue
                        label="Subtotal"
                        value={getPriceWithCurrency(summary.subtotal || 0)}
                    />
                    <Separator />

                    <div className="flex justify-between text-sm md:text-base">
                        <span className="text-gray-700">Total Item</span>
                        <span className="font-medium size-6 p-1 rounded-full bg-primary text-primary-foreground flex justify-center items-center">
                            {items?.length}
                        </span>
                    </div>

                    <Separator />

                    <LabelValue
                        label="Shipping Cost"
                        value={getPriceWithCurrency(summary.shipping_cost || 0)}
                    />
                    <LabelValue
                        label="Total Discount"
                        value={getPriceWithCurrency(
                            summary.total_discount || 0,
                        )}
                    />

                    <Coupon />

                    {summary?.coupon_code?.trim() &&
                        summary?.coupon_discount > 0 && (
                            <div className="flex justify-between text-sm md:text-base text-primary">
                                <span>Coupon Discount</span>
                                <span className="font-medium">
                                    {getPriceWithCurrency(
                                        summary.coupon_discount || 0,
                                    )}
                                </span>
                            </div>
                        )}

                    <Separator />
                    <div className="flex justify-between text-base">
                        <span className="font-semibold">Total</span>
                        <span className="font-bold text-lg text-primary">
                            {getPriceWithCurrency(summary.total || 0)}
                        </span>
                    </div>
                </div>
                <Link to="/checkout" className="w-full">
                    <Button size="xl" className="w-full text-base gap-2">
                        <Lock className="size-5" /> Proceed to Checkout
                    </Button>
                </Link>
            </div>
        </div>
    );
};

const LabelValue = ({ label, value }: { label: string; value: string }) => {
    return (
        <div className="flex justify-between text-sm md:text-base">
            <span className="text-gray-700">{label}</span>
            <span className="font-medium">{value}</span>
        </div>
    );
};
