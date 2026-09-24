import { Pencil, Star, Loader2, ChevronUp } from "lucide-react";
import { useState, useEffect } from "react";
import { OptimizedImage } from "@/components/common/optimized-image";
import type { IProductDetails, IReviewItem } from "./type";
import { Button } from "@/components/ui/button";
import { Textarea } from "@/components/ui/textarea";
import { isAuthenticated } from "@/helper";
import toast from "react-hot-toast";
import { useReviewStoreMutation } from "@/api/product";
import { renderStars } from "../_components/common/star-ratting";

export const Reviews = ({ product }: { product: IProductDetails }) => {
    const { isPending, mutate } = useReviewStoreMutation();
    const [showForm, setShowForm] = useState(false);
    const [rating, setRating] = useState(0);
    const [hoverRating, setHoverRating] = useState(0);
    const [comment, setComment] = useState("");
    const [message, setMessage] = useState("");

    const handleSubmit = () => {
        if (rating === 0) {
            toast.error("Please select a rating");
            return;
        }

        mutate(
            {
                product_id: product.id,
                rating,
                comment: comment || undefined,
            },
            {
                onSuccess: (res) => {
                    if (res?.status) {
                        setMessage(
                            "Your comment will be reviewed and then published.",
                        );
                        setShowForm(false);
                        setRating(0);
                        setComment("");
                    } else {
                        toast.error(res?.message || "Failed to submit review");
                    }
                },
                onError: (r) => {
                    toast.error(
                        r?.message || "Something went wrong. Please try again.",
                    );
                },
            },
        );
    };

    useEffect(() => {
        if (message) {
            const timer = setTimeout(() => setMessage(""), 4000);
            return () => clearTimeout(timer);
        }
    }, [message]);

    return (
        <div className="space-y-3">
            {message && (
                <div className="w-full border border-green-500 bg-green-50 rounded-xl p-4 text-sm text-green-700">
                    {message}
                </div>
            )}

            <ReviewSummary product={product} />

            {product?.review?.items?.length > 0 ? (
                product?.review?.items?.map((item) => (
                    <ReviewItem key={item?.id} item={item} />
                ))
            ) : (
                <div className="rounded-xl bg-gray-50 py-8 text-center text-sm text-gray-500">
                    No reviews yet.
                </div>
            )}

            <div className="space-y-3">
                <Button
                    className="w-full"
                    variant={showForm ? "outline" : "default"}
                    onClick={() => {
                        if (!isAuthenticated()) {
                            toast.error("Please login to add review");
                            return;
                        }
                        setShowForm(!showForm);
                    }}
                >
                    {showForm ? <ChevronUp /> : <Pencil />}
                    {showForm ? "Close" : "Write a review"}
                </Button>

                <div
                    className={`transition-all duration-300 ease-in-out overflow-hidden ${
                        showForm ? "max-h-80 opacity-100" : "max-h-0 opacity-0"
                    }`}
                >
                    <div className="bg-gray-50 rounded-xl p-4 space-y-4">
                        <div className="flex items-center justify-center gap-1">
                            {[1, 2, 3, 4, 5].map((star) => (
                                <button
                                    key={star}
                                    type="button"
                                    className="transition-colors"
                                    onMouseEnter={() => setHoverRating(star)}
                                    onMouseLeave={() => setHoverRating(0)}
                                    onClick={() => setRating(star)}
                                >
                                    <Star
                                        className={`size-8 cursor-pointer duration-100 ${
                                            star <= (hoverRating || rating)
                                                ? "fill-amber-400 text-amber-400"
                                                : "text-gray-300"
                                        }`}
                                    />
                                </button>
                            ))}
                        </div>

                        <Textarea
                            placeholder="Share your experience with this product..."
                            className="min-h-[100px]"
                            value={comment}
                            onChange={(e) => setComment(e.target.value)}
                        />

                        <Button
                            className="w-full"
                            size="lg"
                            onClick={handleSubmit}
                            disabled={isPending}
                        >
                            {isPending ? (
                                <Loader2 className="w-4 h-4 animate-spin" />
                            ) : (
                                "Submit"
                            )}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export const ReviewSummary = ({ product }: { product: IProductDetails }) => (
    <div className="flex items-center gap-5 bg-gray-50 rounded-xl p-4">
        <div className="text-center">
            <div className="text-4xl font-semibold">
                {product.review?.rating ?? 0}
            </div>

            <div className="text-amber-400 text-lg">
                {renderStars(product.review?.rating ?? 0)}
            </div>

            <div className="text-xs text-gray-400 mt-1">
                {product.review?.reviews_count ?? 0} review
                {(product.review?.reviews_count ?? 0) !== 1 && "s"}
            </div>
        </div>

        <div className="flex-1 space-y-1.5">
            {[5, 4, 3, 2, 1].map((star) => {
                const count =
                    product.review?.items?.filter(
                        (item) => item.rating === star,
                    ).length ?? 0;

                const total = product.review?.reviews_count ?? 0;

                const percentage = total > 0 ? (count / total) * 100 : 0;

                return (
                    <div key={star} className="flex items-center gap-2 text-xs">
                        <span className="w-3 text-gray-400">{star}</span>

                        <span className="text-amber-400">★</span>

                        <div className="flex-1 h-1.5 bg-white rounded-full overflow-hidden">
                            <div
                                className="h-full bg-amber-400 rounded-full"
                                style={{
                                    width: `${percentage}%`,
                                }}
                            />
                        </div>

                        <span className="w-4 text-gray-400">{count}</span>
                    </div>
                );
            })}
        </div>
    </div>
);

export const ReviewItem = ({ item }: { item: IReviewItem }) => (
    <div className="bg-gray-50 rounded-xl p-4 space-y-2">
        <div className="flex items-center justify-between">
            <div className="flex items-center gap-2.5">
                <OptimizedImage
                    src={item?.user?.avatar}
                    alt={item?.user?.name}
                    className="w-8 h-8 rounded-full object-cover"
                />

                <div>
                    <p className="text-sm font-medium">{item?.user?.name}</p>

                    <p className="text-xs text-gray-500">
                        {new Date(item?.created_at).toLocaleDateString()}
                    </p>
                </div>
            </div>

            <span className="text-sm text-amber-400">
                {"★".repeat(item?.rating)}
                {"☆".repeat(5 - item?.rating)}
            </span>
        </div>

        <p className="text-sm leading-relaxed text-gray-700">{item?.comment}</p>
    </div>
);
