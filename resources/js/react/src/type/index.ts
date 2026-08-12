export interface IConfig {
    data?: Record<string, unknown> | null;
}

export interface IProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    original: number;
    discount: number;
    discount_type: string; //flat or percent
    rating: number;
    image: string;
    sold: number;
    has_variants: boolean;
    in_stock: boolean;
    category: string;
    brand: string;
    price_range?: {
        min: number;
        max: number;
    };
}

export interface ICategory {
    id: number;
    name: string;
    slug: string;
    image: string;
}

export interface IHeroSlider {
    id: number;
    title: string;
    url: string;
    type: "image" | "video";
    button: string;
    link: string;
}

export interface ICategoryWithProducts extends ICategory {
    products: IProduct[];
}

export interface IBrand {
    id: number;
    name: string;
    logo: string;
    slug: string;
}

export interface ICartItem {
    id: number;
    product: {
        id: number;
        name: string;
        slug: string;
        price: number;
        image: string;
        quantity: number;
        variation?: Record<string, string>;
    };
}
export interface IWishlistItem {
    id: number;
    product: IProduct;
}

export interface IFilters {
    minPrice: number;
    maxPrice: number;
    rating: number;
    sort: "select" | "newest" | "oldest" | "price-low" | "price-high";
    brands: string[];
    subCategories?: string[];
}

export interface IOrderFrom {
    name: string;
    email: string;
    phone: string;
    address: string;
    notes: string;
    payment: string;
    shipping: string;
}

export interface IPayment {
    id: number;
    title: string;
    type: string;
    image: string;
    default: boolean;
}

export interface IPage {
    id: number;
    type: string;
    title: string;
    slug: string;
    content: string;
    meta_title: string;
    meta_description: string;
    keywords: string;
    meta_image: string | null;
    created_at: string;
    updated_at: string;
}

export interface ICampaign {
    id: number;
    name: string;
    slug: string;
    description: string;
    start_date: string;
    end_date: string;
    discount_amount: string;
    discount_type: string;
    status: string;
    image: string;
}
