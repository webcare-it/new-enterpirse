import { getAuthUserId, getTempUserId, getUUID, setCookie } from "@/helper";
import { CartIcon, StockOutIcon } from "./icon";
import { useCart, type ICartAddToCart } from "@/hooks/useCart";
import { Link } from "react-router-dom";
import { TEMP_USER_ID } from "@/constant";
import { LockIcon } from "lucide-react";
import { Spinner } from "@/components/ui/spinner";
import { useGtmTracker, type IItemTracker } from "@/hooks/useGtmTracker";

export const AddToCart = ({
    p,
    isInStock,
    trackerData,
    type = "CARD",
}: {
    type?: string;
    isInStock?: boolean;
    p: ICartAddToCart;
    trackerData: IItemTracker;
}) => {
    const { addItem, addingProductId } = useCart();
    const { addToCartTracker } = useGtmTracker();
    const isAdding = addingProductId === p.product_id;

    const handleAddToCart = (type?: string) => {
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
        if (type) {
            addItem(data, "CHECKOUT");
        } else {
            addItem(data);
        }
    };

    if (type === "CARD")
        return (
            <div
                key={p?.product_id}
                className="flex justify-between items-center gap-1 md:gap-2 mt-1"
            >
                <button
                    type="button"
                    onClick={() => handleAddToCart("CHECKOUT")}
                    disabled={isAdding || !isInStock}
                    aria-label={`Add ${p?.product_id ?? "product"} to cart`}
                    className={`flex-1 text-xs sm:text-sm md:text-base rounded-3xl w-full transition-all duration-300 cursor-pointer h-10 md:h-12 flex items-center justify-center hover:bg-primary/90 bg-primary-foreground text-primary hover:text-primary-foreground border border-primary gap-1 md:gap-2 ${!isInStock ? "cursor-not-allowed" : "cursor-pointer"}`}
                >
                    <LockIcon className="size-3 sm:size-4" />
                    {isAdding ? "loading..." : " Order now"}
                </button>

                <button
                    onClick={() => handleAddToCart()}
                    disabled={isAdding || !isInStock}
                    aria-label={`Add ${p?.product_id ?? "product"} to cart`}
                    className={`size-10 md:size-12 rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center border border-primary bg-primary/80 text-primary-foreground hover:bg-primary ${!isInStock ? "cursor-not-allowed" : "cursor-pointer"}`}
                >
                    {isAdding ? <Spinner /> : <CartIcon />}
                </button>
            </div>
        );

    if (type === "DETAILS") {
        return (
            <button
                type="button"
                key={p?.product_id}
                onClick={() => handleAddToCart()}
                disabled={isAdding || !isInStock}
                className={`rounded-3xl w-full transition-all duration-300 py-3 px-4 flex items-center justify-center border gap-2 ${!isInStock ? "cursor-not-allowed text-red-600 border-red-600 bg-red-100" : "cursor-pointer bg-primary/90 hover:bg-primary-foreground hover:text-primary text-primary-foreground  border-primary"}`}
            >
                {!isInStock ? <StockOutIcon /> : <CartIcon />}
                {isAdding
                    ? "loading..."
                    : !isInStock
                      ? "Out of stock"
                      : "Add to cart"}
            </button>
        );
    }
};

export const AddToCartLink = ({ slug }: { slug: string }) => {
    return (
        <Link
            to={slug}
            className="flex justify-between items-center gap-2 mt-1"
        >
            <button className="flex-1 rounded-3xl w-full transition-all duration-300 cursor-pointer h-10 md:h-12 flex items-center justify-center hover:bg-primary/90 bg-primary-foreground text-primary hover:text-primary-foreground border text-sm md:text-base border-primary gap-1 md:gap-2">
                <LockIcon className="size-4 md:size-5" />
                Order now
            </button>

            <button
                className={`size-10 md:size-12 rounded-full transition-all duration-300 cursor-pointer flex items-center justify-center border border-primary bg-primary/90 text-primary-foreground hover:bg-primary-foreground hover:text-primary`}
            >
                <CartIcon />
            </button>
        </Link>
    );
};

export const BuyItNow = ({
    p,
    isInStock,
}: {
    p: ICartAddToCart;
    isInStock: boolean;
}) => {
    const { addItem, addingProductId } = useCart();
    const isAdding = addingProductId === p.product_id;
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
            disabled={isAdding || !isInStock}
            className={`rounded-3xl w-full transition-all duration-300 py-3 px-4 flex items-center justify-center border gap-2 ${!isInStock ? "cursor-not-allowed border-red-600 bg-red-100 text-red-600" : "cursor-pointer hover:bg-primary/90 bg-primary-foreground text-primary hover:text-primary-foreground  border-primary"}`}
        >
            {!isInStock ? (
                <StockOutIcon />
            ) : (
                <LockIcon className="size-4 md:size-5" />
            )}
            {isAdding
                ? "loading..."
                : !isInStock
                  ? "Out of stock"
                  : "Order now"}
        </button>
    );
};
