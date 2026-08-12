import type { IProduct, IHeroSlider, ICampaign, IWishlistItem } from "@/type";

export const mockProduct: IProduct = {
    id: 1,
    name: "Test Product",
    slug: "test-product",
    price: 100,
    original: 150,
    discount: 33,
    rating: 4.5,
    discount_type: "percent",
    image: "/test.jpg",
    sold: 50,
    has_variants: false,
    in_stock: true,
    category: "Test Category",
    brand: "Test Brand",
};

export const mockProductOutOfStock: IProduct = {
    ...mockProduct,
    id: 2,
    in_stock: false,
    name: "Out of Stock Product",
};

export const mockSlider: IHeroSlider = {
    id: 1,
    title: "Hero Slide 1",
    url: "/slide1.jpg",
    type: "image",
    button: "Shop Now",
    link: "/products",
};

export const mockCampaign: ICampaign = {
    id: 1,
    name: "Summer Sale",
    slug: "summer-sale",
    description: "Big summer discounts",
    start_date: "2025-06-01",
    end_date: "2025-08-31",
    discount_amount: "20",
    discount_type: "percent",
    status: "active",
    image: "/campaign.jpg",
};

export const mockWishlistItem: IWishlistItem = {
    id: 1,
    product: mockProduct,
};

export const mockCartItems = [
    {
        id: 1,
        product: {
            id: 1,
            name: "Cart Product",
            slug: "cart-product",
            price: 100,
            image: "/cart.jpg",
            quantity: 2,
        },
    },
];

export const mockHomeData = {
    data: {
        data: {
            sliders: [mockSlider],
            todays_deal: [mockProduct],
            best_selling: [mockProduct],
            new_arrivals: [mockProduct],
            featured: [mockProduct],
            categories: [
                {
                    id: 1,
                    name: "Category 1",
                    slug: "category-1",
                    image: "/cat.jpg",
                    products: [mockProduct],
                },
            ],
            brands: [
                { id: 1, name: "Brand 1", logo: "/brand.png", slug: "brand-1" },
            ],
            campaigns: [mockCampaign],
        },
    },
    isLoading: false,
    error: null,
};

export const mockProductPageData = {
    pages: [
        {
            data: {
                products: [mockProduct],
                pagination: {
                    current_page: 1,
                    total_pages: 1,
                    has_more: false,
                },
            },
        },
    ],
    pageParams: [1],
};

export const mockProductDetailData = {
    data: {
        data: {
            product: {
                ...mockProduct,
                short_description: "Short desc",
                thumbnail: "/thumb.jpg",
                images: [{ id: 1, src: "/img1.jpg", alt: "Image 1" }],
                attributes: [],
                variations: [],
                stock: 10,
                description: "<p>Full description</p>",
            },
            related_products: [mockProduct],
        },
    },
    isLoading: false,
    error: null,
};
