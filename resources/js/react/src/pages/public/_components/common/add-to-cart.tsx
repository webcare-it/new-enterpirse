import { getAuthUserId, getTempUserId, getUUID, setCookie } from "@/helper";
import { CartIcon } from "./icon";
import { useCart, type ICartAddToCart } from "@/hooks/useCart";
import { Link } from "react-router-dom";
import { TEMP_USER_ID } from "@/constant";
import { LockIcon } from "lucide-react";
import { Spinner } from "@/components/ui/spinner";
import { useGtmTracker, type IItemTracker } from "@/hooks/useGtmTracker";

export const AddToCart = ({
    p,
    type = "CARD",
    trackerData,
}: {
    p: ICartAddToCart;
    trackerData: IItemTracker;
    type?: string;
}) => {
    const { addItem, isAdding } = useCart();
    const { addToCartTracker } = useGtmTracker();

    const handleAddToCart = () => {
        addToCartTracker(trackerData);
        const data: ICartAddToCart = {
            ...p,
        };

        const authUserId = getAuthUserId();

        if (authUserId) {
            data.user_id = authUserId;
        } else {
            let tempId = getTempUserId();

            if (!tempId) {
                const newUuid = getUUID();
                setCookie(TEMP_USER_ID, newUuid);
                tempId = newUuid;
            }

            data.temp_user_id = tempId;
        }

        addItem(data);
    };

    if (type === "CARD")
        return (
            <>
                {/* DESKTOP */}
                <div className="hidden md:block absolute inset-x-0 bottom-0 z-10 md:px-4 md:pb-4 md:opacity-0 md:translate-y-8 transition-all duration-500 ease-out md:group-hover/product:opacity-100 md:group-hover/product:translate-y-0">
                    <button
                        type="button"
                        onClick={handleAddToCart}
                        disabled={isAdding}
                        aria-label={`Add ${p?.product_id ?? "product"} to cart`}
                        className="w-full h-10 backdrop-blur rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-base font-normal bg-primary/90 hover:bg-primary text-primary-foreground"
                    >
                        <CartIcon />
                        {isAdding ? "loading..." : "Add to cart"}
                    </button>
                </div>

                {/*  MOBILE */}
                <div className="flex justify-end md:hidden absolute inset-x-0 bottom-0 z-10 px-2 pb-2 transition-all duration-500 ease-out">
                    <button
                        onClick={handleAddToCart}
                        disabled={isAdding}
                        aria-label={`Add ${p?.product_id ?? "product"} to cart`}
                        className={`size-10 rounded-full transition-all duration-300 cursor-pointer inline-flex items-center justify-center bg-primary/90 hover:bg-primary text-primary-foreground`}
                    >
                        {isAdding ? <Spinner /> : <CartIcon />}
                    </button>
                </div>
            </>
        );

    if (type === "DETAILS") {
        return (
            <button
                onClick={handleAddToCart}
                disabled={isAdding}
                className={`rounded-3xl w-full transition-all duration-300 cursor-pointer py-3 px-4 flex items-center justify-center bg-primary/90 hover:bg-primary-foreground hover:text-primary text-primary-foreground border border-primary gap-2`}
            >
                <CartIcon /> {isAdding ? "loading..." : "Add to cart"}
            </button>
        );
    }
};

export const AddToCartLink = ({ slug }: { slug: string }) => {
    return (
        <>
            {/* DESKTOP */}
            <Link
                to={slug}
                className="hidden md:block absolute inset-x-0 bottom-0 z-10 md:px-4 md:pb-4 md:opacity-0 md:translate-y-8 transition-all duration-500 ease-out md:group-hover/product:opacity-100 md:group-hover/product:translate-y-0"
            >
                <button className="w-full h-10 backdrop-blur rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center gap-2 text-base font-normal bg-primary/90 hover:bg-primary text-primary-foreground">
                    <CartIcon />
                    Add to cart
                </button>
            </Link>

            {/*  MOBILE */}
            <Link
                to={slug}
                aria-label={`View ${slug} details`}
                className="flex justify-end md:hidden absolute inset-x-0 bottom-0 z-10 px-2 pb-2 transition-all duration-500 ease-out"
            >
                <button
                    aria-label={`View ${slug} details`}
                    className={`size-10 rounded-full transition-all duration-300 cursor-pointer inline-flex items-center justify-center bg-primary/90 hover:bg-primary text-primary-foreground`}
                >
                    <CartIcon />
                </button>
            </Link>
        </>
    );
};

export const BuyItNow = ({ p }: { p: ICartAddToCart }) => {
    const { addItem, isAdding } = useCart();
    const handleAddToCart = () => {
        const data: ICartAddToCart = {
            ...p,
        };

        const authUserId = getAuthUserId();

        if (authUserId) {
            data.user_id = authUserId;
        } else {
            let tempId = getTempUserId();

            if (!tempId) {
                const newUuid = getUUID();
                setCookie(TEMP_USER_ID, newUuid);
                tempId = newUuid;
            }

            data.temp_user_id = tempId;
        }

        addItem(data, "CHECKOUT");
    };

    return (
        <button
            onClick={handleAddToCart}
            disabled={isAdding}
            className={`rounded-3xl w-full transition-all duration-300 cursor-pointer py-3 px-4 flex items-center justify-center hover:bg-primary/90 bg-primary-foreground text-primary hover:text-primary-foreground border border-primary gap-2`}
        >
            <LockIcon className="size-4 md:size-5" />{" "}
            {isAdding ? "loading..." : "Buy it now"}
        </button>
    );
};
