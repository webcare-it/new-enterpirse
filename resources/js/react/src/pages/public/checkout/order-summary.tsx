import { useCart } from "@/hooks/useCart";
import { CartItem } from "../_components/common/cart-item";
import { Separator } from "@/components/ui/separator";
import { usePrice } from "@/hooks/usePrice";
import { Coupon } from "../_components/common/coupon";

export function OrderSummary({ children }: { children: React.ReactNode }) {
    const { items, summary } = useCart();
    const { getPriceWithCurrency } = usePrice();

    return (
        <div className="space-y-4">
            <h3 className="font-semibold text-xl uppercase">Order Summary</h3>

            <div
                className="space-y-3 max-h-45 overflow-y-auto pr-1  border-border"
                style={{
                    scrollbarWidth: "none",
                    msOverflowStyle: "none",
                }}
            >
                {items?.map((item) => (
                    <CartItem item={item} key={item.id} />
                ))}
            </div>

            {children}

            <div className="space-y-3 text-sm">
                <LabelValue
                    label="Subtotal"
                    value={getPriceWithCurrency(summary.subtotal || 0)}
                />
                <Separator />

                <LabelValue
                    label="Shipping Cost"
                    value={getPriceWithCurrency(summary.shipping_cost || 0)}
                />
                <LabelValue
                    label="Total Discount"
                    value={getPriceWithCurrency(summary.total_discount || 0)}
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
                <div className="flex justify-between text-base md:text-lg">
                    <span className="font-semibold">Total</span>
                    <span className="font-bold text-lg text-primary">
                        {getPriceWithCurrency(summary.total || 0)}
                    </span>
                </div>
            </div>
        </div>
    );
}

const LabelValue = ({ label, value }: { label: string; value: string }) => {
    return (
        <div className="flex justify-between text-sm md:text-base">
            <span className="text-gray-700">{label}</span>
            <span className="font-medium">{value}</span>
        </div>
    );
};
