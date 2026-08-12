import type { IBrand, ICategory } from "@/type";

export interface IProductImage {
    id: number;
    src: string;
    alt: string;
}

export interface IProductDetails {
    id: number;
    name: string;
    slug: string;
    description: string;
    short_description: string;
    thumbnail: string;
    yt_video_id: string;
    images: string[];
    category: ICategory;
    brand: IBrand;
    price: IPrice;
    inventory: IInventory;
    variants: IVariant[];
    has_variants: boolean;
    review: IReview;
    created_at: string;
    updated_at: string;
}

export interface IPrice {
    regular: string;
    sale: string;
    discount: string;
    discount_type: string;
    discount_percentage: number;
    current: string;
}

export interface IInventory {
    unit: string;
    sku: string;
    stock: number;
    stock_status: "in_stock" | "out_of_stock";
    total_sold: number;
}

export interface IAttributeOption {
    value: string;
    image: string | null;
}

export interface IAttribute {
    name: string;
    values: IAttributeOption[];
}

export interface IVariant {
    id: number;
    sku: string;
    price: string;
    stock: number;
    image: string | null;
    attribute_value: Record<string, string>;
    attribute_name: string;
}

export interface IReview {
    rating: number;
    reviews_count: number;
    items: IReviewItem[];
}

export interface IReviewItem {
    id: number;
    rating: number;
    reviews_count: number;
    comment: string;
    user: IUser;
    created_at: string;
}

export interface IUser {
    id: number;
    name: string;
    avatar: string;
}
