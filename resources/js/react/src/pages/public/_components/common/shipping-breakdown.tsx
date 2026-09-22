import { useCart } from "@/hooks/useCart";
import { useConfig } from "@/hooks/useConfig";
import { usePrice } from "@/hooks/usePrice";
import { Truck } from "lucide-react";

interface IShippingArea {
    id: number;
    name: string;
}

export const ShippingBreakdown = () => {
    const { items, summary } = useCart();
    const config = useConfig();
    const { getPriceWithCurrency } = usePrice();

    const areas = (config?.shippings as IShippingArea[]) || [];
    const area = areas.find(
        (a) => String(a.id) === String(summary?.shipping_id),
    );

    const productLines = (items || []).filter(
        (i) => i.shipping_source === "product" && Number(i.shipping_cost) > 0,
    );
    const zeroLines = (items || []).filter(
        (i) =>
            i.shipping_source === "product_free" ||
            i.shipping_source === "pickup",
    );
    const areaItems = (items || []).filter(
        (i) => i.shipping_source === "area" || i.shipping_source === "none",
    );
    const areaTotal = areaItems.reduce(
        (sum, i) => sum + Number(i.shipping_cost || 0),
        0,
    );

    const showBreakdown =
        productLines.length + zeroLines.length > 0 &&
        (productLines.length + zeroLines.length > 1 || areaItems.length > 0);

    return (
        <div className="space-y-2">
            <div className="flex justify-between text-sm md:text-base">
                <span className="text-gray-700">Shipping Cost</span>
                <span className="font-medium">
                    {getPriceWithCurrency(summary?.shipping_cost || 0)}
                </span>
            </div>

            {showBreakdown && (
                <ul className="ml-1 space-y-1.5 border-l-2 border-primary pl-3 text-xs md:text-sm text-gray-500">
                    {areaItems.length > 0 && (
                        <li className="flex items-start justify-between gap-3 border-b ">
                            <span className="flex min-w-0 items-center gap-1.5 text-gray-700">
                                <Truck className="size-3.5 shrink-0" />
                                <span className="truncate">
                                    {area
                                        ? `${area.name} delivery`
                                        : "Delivery area"}{" "}
                                    <span>
                                        ({areaItems.length}{" "}
                                        {areaItems.length > 1
                                            ? "items"
                                            : "item"}
                                        )
                                    </span>
                                </span>
                            </span>
                            <span className="shrink-0 font-medium text-gray-700">
                                {area ? (
                                    getPriceWithCurrency(areaTotal)
                                ) : (
                                    <span className="text-amber-600">
                                        Select area
                                    </span>
                                )}
                            </span>
                        </li>
                    )}

                    {productLines.map((i) => (
                        <li
                            key={i.id}
                            className="flex items-start justify-between gap-3"
                        >
                            <span className="min-w-0">
                                <span className="block truncate text-gray-700">
                                    {i.product?.name}
                                </span>
                                <span className="text-[11px] text-gray-600">
                                    Product's own delivery charge
                                </span>
                            </span>
                            <span className="shrink-0 font-medium text-gray-700">
                                {getPriceWithCurrency(
                                    Number(i.shipping_cost || 0),
                                )}
                            </span>
                        </li>
                    ))}

                    {zeroLines?.map((i) => (
                        <li
                            key={i.id}
                            className="flex items-start justify-between gap-3"
                        >
                            <span className="min-w-0">
                                <span className="block truncate">
                                    {i.product?.name}
                                </span>
                                <span className="text-[11px] text-gray-400">
                                    {i.shipping_source === "pickup"
                                        ? "Local pickup"
                                        : "Free delivery"}
                                </span>
                            </span>
                            <span className="shrink-0 font-medium text-green-600">
                                Free
                            </span>
                        </li>
                    ))}
                </ul>
            )}
        </div>
    );
};
