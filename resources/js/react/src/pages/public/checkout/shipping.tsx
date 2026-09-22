import { useShippingMutation } from "@/api/shipping";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { useCart } from "@/hooks/useCart";
import { useConfig } from "@/hooks/useConfig";
import { usePrice } from "@/hooks/usePrice";
import { revalidateQueryFn } from "@/lib/tanstack";
import type { IOrderFrom } from "@/type";
import { useEffect } from "react";
import { Truck } from "lucide-react";
import toast from "react-hot-toast";

interface IShippingArea {
    id: number;
    name: string;
    amount: number;
}

interface Props {
    form: IOrderFrom;
    setForm: React.Dispatch<React.SetStateAction<IOrderFrom>>;
}

export const Shipping = ({ form, setForm }: Props) => {
    const config = useConfig();
    const { summary, items } = useCart();
    const ownShippingItems = (items || []).filter(
        (i) => i.shipping_source === "product" && Number(i.shipping_cost) > 0,
    );
    const { getPriceWithCurrency } = usePrice();
    const selectedId = summary?.shipping_id || "";
    const { mutate, isPending } = useShippingMutation();
    const shippings = (config?.shippings as IShippingArea[]) || [];
    const areaNotNeeded =
        items?.length > 0 && summary?.needs_shipping_area === false;

    useEffect(() => {
        if (!selectedId) return;
        setForm((prev) => ({ ...prev, shipping: String(selectedId) }));
    }, [selectedId, setForm]);

    const handleShipping = (id: string) => {
        mutate(
            {
                shipping_area: id,
            },
            {
                onSuccess: () => {
                    revalidateQueryFn("get_cart");
                    toast.success("Shipping updated successfully");
                    setForm((prev) => ({ ...prev, shipping: id }));
                },
            },
        );
    };

    return (
        <>
            <h2 className="font-semibold text-lg flex items-center gap-2 mt-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 122.88 67.31"
                    className="w-14 h-8 fill-primary text-primary"
                    fill="none"
                >
                    <path
                        fill="currentColor"
                        fillRule="evenodd"
                        clipRule="evenodd"
                        d="M92.71 16.53l-7.92-.05V5.77c0-1.59-.65-3.03-1.7-4.08C82.04.65 80.6 0 79.01 0H26.35c-1.59 0-3.03.65-4.08 1.7-1.04 1.04-1.7 2.49-1.7 4.08 0 .87.7 1.56 1.56 1.56.87 0 1.57-.7 1.57-1.56 0-.73.3-1.39.78-1.87.48-.48 1.14-.78 1.87-.78h52.66c.72 0 1.39.3 1.87.78.48.48.78 1.14.78 1.87v49h-8.83c-.87 0-1.57.7-1.57 1.57s.7 1.57 1.57 1.57h10.39c.87 0 1.57-.7 1.57-1.57v-1.78h6.32c.72-16.29 24.11-18.54 26.49 0h5.14c1.32-15.88-6.52-22.06-18.45-23.45-.86-3.73-2.49-7.19-4.34-10.61-1.11-2.04-1.62-1.88-6.14-1.95zM29.55 30.91h1.93c1.25 0 2.08.06 2.51.17.43.11.74.3.94.56.2.26.31.55.32.87.02.32-.03.95-.14 1.89l-.41 3.5c-.11.89-.22 1.49-.34 1.79-.12.3-.29.54-.52.71-.23.17-.5.29-.82.36-.31.07-.78.1-1.4.1h-3.26l1.19-9.94zm1.61 8.22c.18 0 .34-.02.45-.06.36-.04.68-.09.73-.17.1-.15.19-.56.27-1.22l.46-3.87c.05-.45.07-.74.06-.87-.01-.13-.07-.22-.16-.28-.06-.04-.48-.06-.88-.08h-.6l-.78 6.56c.12.01.3.01.45-.01zm5.8-8.22h4.31l-.24 1.98h-1.72l-.23 1.9h1.61l-.23 1.88h-1.61l-.26 2.19h1.9l-.24 1.98h-4.48l1.19-9.94zm8.26 0l-.95 7.95h1.56l-.24 1.98h-4.15l1.19-9.94h2.59zm5.24 0l-1.19 9.94h-2.59l1.19-9.94h2.59zm7.63 0l-2.5 9.94h-3.91l-.31-9.94h2.73c-.02 2.74-.07 5.06-.16 6.95.37-1.92.71-3.62 1.04-5.1l.39-1.85h2.72zm.9 0h4.31l-.24 1.98h-1.72l-.23 1.9h1.61l-.22 1.88h-1.61l-.26 2.19h1.9l-.24 1.98H57.8l1.19-9.94zm5.68 0h1.83c1.22 0 2.04.05 2.46.14.42.09.74.33.97.72.23.39.29 1 .19 1.85-.09.77-.25 1.29-.48 1.56-.22.27-.62.42-1.19.48.5.13.82.3.97.51.15.21.24.41.26.59.02.18-.02.67-.11 1.47l-.31 2.62h-2.4l.4-3.31c.06-.53.06-.86-.01-.99-.07-.13-.28-.19-.63-.19l-.54 4.48h-2.59l1.19-9.94zm2.12 3.89c.15 0 .28-.01.38-.03.31-.02.62-.05.68-.1.24-.16.46-1.71.23-1.94-.06-.07-.48-.11-.84-.13h-.43l-.26 2.21c.06.01.14.01.24-.01zm10.61-3.89l-2.64 6.34-.43 3.6h-2.39l.43-3.6-1.06-6.34h2.37c.13 1.94.19 3.25.16 3.92.28-1.06.67-2.37 1.19-3.92h2.37zm-70.94-6.18c-.97 0-1.76-.7-1.76-1.56s.79-1.57 1.76-1.57h17.08c.97 0 1.76.7 1.76 1.57 0 .87-.79 1.56-1.76 1.56H6.46zm-4.84 8.47c-2.16 0-2.16-3.15 0-3.15h19.57c1.3 0 2.34.71 2.34 1.57 0 .87-1.05 1.57-2.34 1.57H1.62zm38.97 21.57c.87 0 1.57.7 1.57 1.56 0 .87-.7 1.57-1.57 1.57H26.35c-1.58 0-3.02-.69-4.08-1.78-1.04-1.09-1.7-2.57-1.7-4.15v-11.5c0-.87.7-1.57 1.56-1.57.87 0 1.57.7 1.57 1.57v11.5c0 .75.31 1.47.81 1.99.48.5 1.12.81 1.82.81h14.24zm15.91-8.67c-5.85 0-10.6 4.75-10.6 10.6s4.75 10.6 10.6 10.6 10.6-4.75 10.6-10.6c0-5.85-4.74-10.6-10.6-10.6zm0 6.53c-2.25 0-4.08 1.82-4.08 4.08 0 2.25 1.82 4.08 4.08 4.08 2.25 0 4.08-1.82 4.08-4.08 0-2.26-1.82-4.08-4.08-4.08zm47.82-8.09c-5.85 0-10.6 4.75-10.6 10.6 0 5.85 4.75 10.6 10.6 10.6 5.85 0 10.6-4.75 10.6-10.6 0-5.85-4.74-10.6-10.6-10.6zm-4.07 10.61c0 2.25 1.82 4.08 4.08 4.08 2.25 0 4.08-1.82 4.08-4.08s-1.82-4.08-4.08-4.08c-2.26 0-4.08 1.83-4.08 4.08zm-6.78-34.09l-4.78-.09v10.16h10.12c-1.25-3.63-3.09-6.95-5.34-10.07z"
                    />
                    <path
                        fill="currentColor"
                        fillRule="evenodd"
                        clipRule="evenodd"
                        d="M11.03 16.25c-.97 0-1.76-.7-1.76-1.56 0-.87.79-1.57 1.76-1.57h14.83c.97 0 1.76.7 1.76 1.57 0 .87-.79 1.56-1.76 1.56H11.03zm22.59-6.4h7.44l-.4 3.38h-3.04l-.39 3.23h2.69l-.38 3.21h-2.69L36 26.76h-4.41l2.03-16.91zm16.24 0l.49 16.92h-4.49l.12-3.04h-1.59l-.6 3.04h-4.55l4.26-16.92h6.36zm-3.64 10.88c.02-1.92.08-4.29.19-7.13-.82 3.25-1.38 5.62-1.68 7.13h1.49zm16.16-5.75H58.3l.15-1.25c.07-.58.06-.96-.02-1.12-.08-.16-.25-.24-.49-.24-.26 0-.48.11-.64.32-.16.21-.27.54-.32.98-.07.56-.04.99.08 1.27.12.28.49.63 1.12 1.03 1.81 1.17 2.93 2.12 3.34 2.87.42.75.53 1.95.33 3.6-.14 1.21-.39 2.09-.74 2.67-.35.57-.95 1.05-1.8 1.43-.85.39-1.81.58-2.89.58-1.18 0-2.15-.22-2.93-.67-.78-.45-1.25-1.01-1.43-1.7-.17-.69-.18-1.67-.03-2.94l.13-1.11h4.08L56 22.77c-.08.63-.07 1.04.02 1.22.09.18.28.27.58.27.29 0 .52-.11.69-.35.17-.23.28-.57.34-1.02.12-.99.06-1.65-.17-1.95-.24-.31-.87-.82-1.87-1.54-1-.72-1.66-1.25-1.98-1.58-.31-.33-.56-.78-.72-1.36-.16-.58-.19-1.32-.08-2.21.15-1.29.43-2.24.84-2.84.4-.6.99-1.07 1.77-1.4.78-.34 1.69-.51 2.73-.51 1.14 0 2.09.19 2.85.55.76.37 1.23.83 1.43 1.4.19.56.21 1.51.05 2.85l-.14 1.17zm11.79-5.13l-.4 3.38h-2.59l-1.62 13.54h-4.41l1.62-13.54h-2.61l.4-3.38h9.61z"
                    />
                </svg>
                {areaNotNeeded ? "Delivery" : "Shipping Area"}
            </h2>

            {areaNotNeeded ? (
                <div className="flex items-start gap-3 rounded-xl border border-primary/30 bg-primary/5 p-4">
                    <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 text-primary">
                        <Truck className="size-4" />
                    </div>
                    <div className="min-w-0 flex-1">
                        <div className="flex flex-wrap items-center justify-between gap-2">
                            <p className="text-sm font-semibold text-gray-900">
                                {Number(summary?.shipping_cost) > 0
                                    ? "Delivery charge included"
                                    : "Free delivery"}
                            </p>
                            <span className="text-base font-semibold text-gray-900">
                                {Number(summary?.shipping_cost) > 0
                                    ? getPriceWithCurrency(
                                          Number(summary?.shipping_cost),
                                      )
                                    : "Free"}
                            </span>
                        </div>
                        <p className="mt-1 text-xs md:text-sm text-gray-500">
                            {Number(summary?.shipping_cost) > 0
                                ? `${items.length > 1 ? "Your items have" : "This item has"} a fixed delivery charge, so you don't need to choose a shipping area.`
                                : "No delivery charge for your items, so you don't need to choose a shipping area."}
                        </p>
                    </div>
                </div>
            ) : (
                <>
                    {ownShippingItems?.length > 0 && (
                        <p className="-mt-2 text-xs md:text-sm text-gray-500">
                            {`The area charge is added once per order. ${ownShippingItems.length} ${ownShippingItems.length > 1 ? "items also have their" : "item also has its"} own delivery charge. See the order summary.`}
                        </p>
                    )}

                    <RadioGroup
                        value={String(form.shipping)}
                        disabled={isPending}
                        onValueChange={(v) => handleShipping(v)}
                        className={`grid grid-cols-1 md:grid-cols-2 gap-4  ${isPending ? "pointer-events-none" : ""}`}
                    >
                        {shippings?.map((p) => (
                            <Label
                                key={p.id}
                                htmlFor={`shipping-${p.id}`}
                                className="flex items-center gap-3 rounded-xl border border-border p-4 has-[[data-state=checked]]:border-primary has-[[data-state=checked]]:bg-primary/5 cursor-pointer transition-colors md:col-span-1"
                            >
                                <RadioGroupItem
                                    value={String(p.id)}
                                    id={`shipping-${p.id}`}
                                />

                                <span className="text-sm font-medium">
                                    {p?.name}
                                </span>
                                <span className="ml-auto text-base font-semibold text-gray-900">
                                    {getPriceWithCurrency(p?.amount)}
                                </span>
                            </Label>
                        ))}
                    </RadioGroup>
                    {!form.shipping && summary?.needs_shipping_area && (
                        <p className="text-sm text-amber-600">
                            Select a shipping area to see the final shipping
                            cost.
                        </p>
                    )}
                </>
            )}
        </>
    );
};
