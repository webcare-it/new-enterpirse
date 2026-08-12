import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IOrderFrom, IPayment } from "@/type";
import { useEffect } from "react";
import { useConfig } from "@/hooks/useConfig";

const defaultPayment: IPayment[] = [
    {
        id: 0,
        is_default: true,
        title: "Cash On Delivery",
        type: "cod",
        image: "https://png.pngtree.com/png-vector/20210602/ourmid/pngtree-truck-for-the-cash-on-delivery-logo-vector-png-image_3401504.jpg",
    },
];

interface Props {
    form: IOrderFrom;
    setForm: React.Dispatch<React.SetStateAction<IOrderFrom>>;
}

export const Payments = ({ form, setForm }: Props) => {
    const config = useConfig();
    const list = (config?.payments as IPayment[]) || [];
    const payments = list?.length ? list : defaultPayment;

    useEffect(() => {
        const payment = payments.find((p) => p.is_default);
        if (payment)
            setForm((prev) => ({ ...prev, payment: String(payment.type) }));
    }, [setForm, payments]);

    return (
        <div className="space-y-4 border-t pt-3 border-border">
            <h2 className="font-semibold text-lg flex items-center gap-2">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 111.81 122.88"
                    className="w-10 h-8"
                    fill="none"
                >
                    <path
                        d="M55.71 0C76.56 13.21 95.39 19.47 111.56 17.99C114.38 75.09 93.3 108.81 55.93 122.88C19.84 109.71-1.5 77.44.08 17.12C19.06 18.12 37.67 14.01 55.71 0Z"
                        className="fill-green-600"
                    />

                    <path
                        d="M55.73 7.05C74.18 18.75 90.86 24.28 105.16 22.97C107.66 73.51 88.99 103.36 55.92 115.82C23.98 104.16 5.09 75.6 6.49 22.21C23.29 23.09 39.77 19.46 55.73 7.05Z"
                        className="fill-white"
                    />

                    <path
                        d="M56.24 19.54C70.46 28.55 83.3 32.81 94.32 31.81C96.24 70.75 81.87 93.74 56.38 103.34L55.9 103.16L55.42 103.34C29.94 93.74 15.56 70.75 17.48 31.81C28.5 32.82 41.35 28.55 55.56 19.54L55.89 19.79L56.24 19.54Z"
                        className="fill-green-600"
                    />

                    <path
                        d="M35.44 58.28L42.91 58.18L43.47 58.32C44.98 59.19 46.4 60.18 47.73 61.31C48.69 62.12 49.6 63 50.47 63.96C53.15 59.65 56.01 55.68 59.03 52.04C62.34 48.05 64.41 45.86 68.09 42.55L68.82 42.27H76.98L75.33 44.09C70.28 49.7 67.12 54.08 62.98 60.06C58.83 66.06 55.13 72.24 51.83 78.6L50.8 80.58L49.86 78.56C48.12 74.83 46.04 71.41 43.56 68.35C41.08 65.29 38.19 62.57 34.82 60.26L35.44 58.28Z"
                        className="fill-white"
                    />
                </svg>
                Payment Method
            </h2>
            <RadioGroup
                value={form.payment}
                onValueChange={(v) =>
                    setForm((prev) => ({ ...prev, payment: v }))
                }
                className="space-y-3"
            >
                {payments?.map((p) => (
                    <Label
                        key={p.id}
                        htmlFor={`payment-${p.id}`}
                        className="flex items-center gap-3 rounded-xl border border-border p-4 has-[[data-state=checked]]:border-primary has-[[data-state=checked]]:bg-primary/5 cursor-pointer transition-colors"
                    >
                        <RadioGroupItem
                            className="size-4"
                            value={String(p.type)}
                            id={`payment-${p.id}`}
                        />
                        <OptimizedImage
                            src={p?.image || ""}
                            alt={p.title}
                            className="size-8 object-contain"
                        />
                        <span className="text-sm font-medium">{p?.title}</span>
                    </Label>
                ))}
            </RadioGroup>
        </div>
    );
};
