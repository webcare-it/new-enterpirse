import { useState } from "react";
import { cn } from "@/lib/utils";
import { ButtonGroup } from "@/components/ui/button-group";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { useCouponApply, useCouponRemove } from "@/api/coupon";
import { useCart } from "@/hooks/useCart";

export const Coupon = () => {
    const { items, summary } = useCart();
    const [coupon, setCoupon] = useState("");
    const { mutate: applyMutate, isPending } = useCouponApply();
    const { mutate, isPending: isRemovePending } = useCouponRemove();

    const handleApplyCoupon = () => {
        applyMutate({ coupon_code: coupon });
    };

    const handleRemoveCoupon = () => {
        mutate({ coupon_code: coupon });
        setCoupon("");
    };

    const loading = isPending || isRemovePending;

    return (
        <div className="space-y-2 pb-2">
            <ButtonGroup className="w-full h-10">
                <Input
                    placeholder={"Enter Coupon code"}
                    className={cn(
                        "h-10 md:h-11 bg-accent rounded-r-none",
                        loading ||
                            (summary?.coupon_code !== "" &&
                                "cursor-not-allowed"),
                    )}
                    type="text"
                    readOnly={!!summary?.coupon_code}
                    disabled={items?.length === 0 || loading}
                    value={coupon}
                    onChange={(e) => setCoupon(e.target.value)}
                />
                <Button
                    disabled={loading || coupon === ""}
                    aria-label="Apply Coupon"
                    variant={
                        summary?.coupon_code?.trim() ? "destructive" : "default"
                    }
                    className="h-10 md:h-11 rounded-none rounded-r-lg"
                    onClick={
                        summary?.coupon_code
                            ? handleRemoveCoupon
                            : handleApplyCoupon
                    }
                >
                    {loading ? (
                        <>{"loading..."}</>
                    ) : summary?.coupon_code?.trim() ? (
                        "Remove"
                    ) : (
                        "Apply"
                    )}
                </Button>
            </ButtonGroup>
            {summary?.coupon_code?.trim() && (
                <div className="text-sm text-green-600 font-medium flex items-center gap-1">
                    Coupon code applied:{" "}
                    <span className="font-bold">
                        [{summary.coupon_code?.trim()}]
                    </span>
                </div>
            )}
        </div>
    );
};
