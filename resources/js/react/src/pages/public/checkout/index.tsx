import { useEffect, useRef, useState } from "react";
import { useNavigate } from "react-router-dom";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { OrderSummary } from "./order-summary";
import { Button } from "@/components/ui/button";
import { OrderFrom } from "./from";
import { Shipping } from "./shipping";
import { Payments } from "./payment";
import type { IOrderFrom } from "@/type";
import {
    useCheckoutMutation,
    useVerifyOrderOtpMutation,
    useResendOrderOtpMutation,
} from "@/api/checkout";
import { useCart } from "@/hooks/useCart";
import { TrustedBadge } from "./trusted-badge";
import { Lock } from "lucide-react";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import { removeLocalStorage, renderVariation } from "@/helper";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { EmptyCart } from "../_components/common/empty-cart";
import { useGtmTracker, type IPurchaseTracker } from "@/hooks/useGtmTracker";
import { GTM_PURCHASE_TRACKED } from "@/constant";
import { OrderOtpVerification } from "./order-otp-modal";
import toast from "react-hot-toast";
import { revalidateQueryFn } from "@/lib/tanstack";

interface ApiError {
    response?: {
        data?: {
            message?: string;
        };
    };
}

interface IOrderProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    quantity: number;
    variation?: Record<string, string>;
}

export const CheckoutPage = () => {
    return (
        <>
            <SeoWrapper title="Checkout" description="Complete your order" />
            <BaseLayout>
                <LayoutContainer className="pb-16 md:pb-20">
                    <Form />
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};

const Form = () => {
    const navigate = useNavigate();
    const firedRef = useRef(false);
    const { summary, items } = useCart();
    const { beginCheckoutTracker } = useGtmTracker();
    const { mutate, isPending } = useCheckoutMutation();
    const { mutate: verifyOtp, isPending: verifyPending } =
        useVerifyOrderOtpMutation();
    const { mutate: resendOtp, isPending: resendPending } =
        useResendOrderOtpMutation();

    const [otpData, setOtpData] = useState<{
        orderCode: string;
    } | null>(null);

    const [form, setForm] = useState<IOrderFrom>({
        name: "",
        email: "",
        phone: "",
        address: "",
        notes: "",
        payment: "",
        shipping: String(summary?.shipping_id || ""),
    });

    useEffect(() => {
        if (firedRef.current) return;
        if (items?.length > 0) {
            firedRef.current = true;
            const trackerData: IPurchaseTracker = {
                transaction_id: "",
                value: summary?.total || 0,
                coupon: summary?.coupon_code || "",
                items: items?.map((p) => ({
                    item_id: p?.product?.id.toString() || "",
                    item_name: p?.product?.name || "",
                    item_price: p?.product?.price || 0,
                    item_quantity: p?.product?.quantity || 0,
                    item_variant: renderVariation(p?.product?.variation) || "",
                })),
            };
            beginCheckoutTracker(trackerData);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [items]);

    const handlePlace = () => {
        removeLocalStorage(GTM_PURCHASE_TRACKED);
        if (!form.name || form.name.trim() === "") {
            toast.error("Full name is required");
            return;
        }
        if (!form.phone || form.phone.trim() === "") {
            toast.error("Phone number is required");
            return;
        }
        if (!form.address || form.address.trim() === "") {
            toast.error("Shipping address is required");
            return;
        }
        if (!form.shipping || form.shipping === "") {
            toast.error("Shipping method is required");
            return;
        }
        if (!form.payment || form.payment === "") {
            toast.error("Payment method is required");
            return;
        }

        mutate(
            {
                shipping_address: form.address,
                payment_type: form.payment,
                notes: form.notes,
                shipping_area: form.shipping,
                name: form.name,
                email: form.email,
                phone: form.phone,
            },
            {
                onSuccess: (data) => {
                    const res = data?.data;
                    if (res?.otp_sent && res?.order_code) {
                        setOtpData({ orderCode: res.order_code });
                    }
                },
            },
        );
    };
    const handleVerifyOtp = (code: string, otp: string) => {
        verifyOtp(
            { order_code: code, otp },
            {
                onSuccess: (res) => {
                    if (res?.success) {
                        revalidateQueryFn("get_cart");
                        toast.success("Order verified successfully.");
                        const data = res?.data;
                        if (data) {
                            const params = new URLSearchParams({
                                order: JSON.stringify({
                                    code: data.code || "",
                                    date: data.date || "",
                                    ...data.summary,
                                }),
                                user: JSON.stringify(data.customer || {}),
                                tracker: JSON.stringify({
                                    transaction_id: data.code || "",
                                    value: data.summary?.grand_total || 0,
                                    shipping: data.summary?.shipping_cost || 0,
                                    coupon: "",
                                    tax: data.summary?.tax || 0,
                                    customer_type: "returning",
                                    items:
                                        data.items?.map(
                                            (
                                                item: IOrderProduct,
                                                i: number,
                                            ) => ({
                                                item_id:
                                                    item?.id?.toString() || "",
                                                item_name: item?.name || "",
                                                item_price: item?.price || 0,
                                                item_quantity:
                                                    item?.quantity || 0,
                                                item_variant:
                                                    renderVariation(
                                                        item?.variation,
                                                    ) || "",
                                                index: i || 0,
                                            }),
                                        ) || [],
                                }),
                            });
                            navigate(`/checkout/success?${params.toString()}`);
                        }
                    }
                },
                onError: (error: unknown) => {
                    const err =
                        typeof error === "object" &&
                        error !== null &&
                        "response" in error
                            ? (error as ApiError)
                            : null;
                    toast.error(err?.response?.data?.message || "Invalid OTP");
                },
            },
        );
    };

    const handleResendOtp = (code: string) => {
        resendOtp(
            { order_code: code },
            {
                onSuccess: () => {
                    toast.success("OTP resent successfully.");
                },
            },
        );
    };

    if (otpData) {
        return (
            <OrderOtpVerification
                orderCode={otpData.orderCode}
                onVerified={() => setOtpData(null)}
                onResend={handleResendOtp}
                verifyPending={verifyPending}
                resendPending={resendPending}
                onVerify={handleVerifyOtp}
            />
        );
    }

    return (
        <>
            <BreadcrumbWrapper
                className="mt-4 hidden md:block"
                items={[
                    {
                        title: "My Cart",
                        path: "/my-cart",
                    },
                    { title: "Checkout", path: "/checkout" },
                ]}
            />
            <h1 className="text-2xl md:text-3xl font-bold md:text-start text-center uppercase my-4">
                Checkout
            </h1>
            {items?.length === 0 ? (
                <EmptyCart />
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-7 gap-6">
                    <div className="md:col-span-4">
                        <div className="md:sticky md:top-28 space-y-6">
                            <div className="border border-border shadow-lg rounded-3xl p-4 md:p-5 space-y-5">
                                <h2 className="font-semibold text-lg uppercase">
                                    Shipping Information
                                </h2>
                                <OrderFrom form={form} setForm={setForm} />
                                <Shipping form={form} setForm={setForm} />
                                <TrustedBadge />
                            </div>
                        </div>
                    </div>

                    <div className="md:col-span-3">
                        <div className="border border-border shadow-lg rounded-3xl p-4 md:p-5 md:sticky md:top-28 space-y-4">
                            <OrderSummary>
                                <Payments form={form} setForm={setForm} />
                            </OrderSummary>
                            <Button
                                className="w-full inline-flex"
                                size="xl"
                                onClick={handlePlace}
                                disabled={isPending}
                            >
                                <Lock className="size-4" />
                                {isPending ? "Processing..." : "Place Order"}
                            </Button>
                            <div className="flex items-center gap-2 text-xs justify-center mt-2">
                                <Lock className="size-4 text-primary" /> 100%
                                Secure checkout
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};
