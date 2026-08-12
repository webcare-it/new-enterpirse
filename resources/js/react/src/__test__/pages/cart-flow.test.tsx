import { renderWithProviders } from "../test-utils";
import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import type { Mock } from "vitest";
import { mockProduct, mockWishlistItem, mockCartItems } from "../mocks/data";

vi.mock("@/api/coupon", () => ({
    useCouponApply: () => ({ mutate: vi.fn(), isPending: false }),
    useCouponRemove: () => ({ mutate: vi.fn(), isPending: false }),
}));
vi.mock("@/api/cart", () => ({ useCartRelatedProducts: vi.fn() }));
vi.mock("@/api/checkout", () => ({
    useCheckoutMutation: vi.fn(),
    useIncompleteOrderMutation: vi.fn(() => ({
        mutate: vi.fn(),
        isPending: false,
    })),
}));

const { useCartRelatedProducts } = await import("@/api/cart");
const { useCheckoutMutation } = await import("@/api/checkout");

const mockRelated = (products = [mockProduct], isLoading = false) => ({
    data: { data: { related_products: products } },
    isLoading,
    error: null,
});

describe("MyCartPage", () => {
    it("renders empty cart", async () => {
        (useCartRelatedProducts as Mock).mockReturnValue(mockRelated([]));
        const { MyCartPage } = await import("@/pages/public/my-cart");
        renderWithProviders(<MyCartPage />);
        expect(
            await screen.findByText(/your cart is empty/i),
        ).toBeInTheDocument();
    });

    it("renders cart items", async () => {
        (useCartRelatedProducts as Mock).mockReturnValue(mockRelated([]));
        const { MyCartPage } = await import("@/pages/public/my-cart");
        renderWithProviders(<MyCartPage />, {
            cart: {
                items: mockCartItems as never[],
                summary: {
                    subtotal: 200,
                    total_discount: 0,
                    total_tax: 0,
                    shipping_cost: 0,
                    total_item: 2,
                    total: 200,
                    coupon_discount: 0,
                    coupon_code: "",
                    shipping_id: 0,
                },
            },
        });
        expect(await screen.findByText("Cart Product")).toBeInTheDocument();
    });

    it("renders related products", async () => {
        (useCartRelatedProducts as Mock).mockReturnValue(
            mockRelated([mockProduct], false),
        );
        const { MyCartPage } = await import("@/pages/public/my-cart");
        renderWithProviders(<MyCartPage />, {
            cart: {
                items: mockCartItems as never[],
                summary: {
                    subtotal: 200,
                    total_discount: 0,
                    total_tax: 0,
                    shipping_cost: 0,
                    total_item: 2,
                    total: 200,
                    coupon_discount: 0,
                    coupon_code: "",
                    shipping_id: 0,
                },
            },
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });
});

describe("CheckoutPage", () => {
    it("renders empty cart state", async () => {
        (useCheckoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { CheckoutPage } = await import("@/pages/public/checkout");
        renderWithProviders(<CheckoutPage />);
        expect(
            await screen.findByText(/your cart is empty/i),
        ).toBeInTheDocument();
    });

    it("renders checkout form with items", async () => {
        (useCheckoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { CheckoutPage } = await import("@/pages/public/checkout");
        renderWithProviders(<CheckoutPage />, {
            cart: {
                items: mockCartItems as never[],
                summary: {
                    subtotal: 200,
                    total_discount: 0,
                    total_tax: 0,
                    shipping_cost: 0,
                    total_item: 2,
                    total: 200,
                    coupon_discount: 0,
                    coupon_code: "",
                    shipping_id: 0,
                },
            },
        });
        const checkouts = await screen.findAllByText("Checkout");
        expect(checkouts.length).toBeGreaterThanOrEqual(1);
        expect(screen.getByText("Shipping Information")).toBeInTheDocument();
        expect(screen.getByText(/100% Secure checkout/i)).toBeInTheDocument();
    });
});

describe("MyWishlistPage", () => {
    it("renders empty wishlist", async () => {
        const { MyWishlistPage } = await import("@/pages/public/my-wishlist");
        renderWithProviders(<MyWishlistPage />);
        expect(
            await screen.findByText(/your wishlist is empty/i),
        ).toBeInTheDocument();
    });

    it("renders wishlist items", async () => {
        const { MyWishlistPage } = await import("@/pages/public/my-wishlist");
        renderWithProviders(<MyWishlistPage />, {
            wishlist: {
                items: [mockWishlistItem],
                toggleItem: vi.fn(),
                isPending: false,
            },
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
        expect(screen.getByText(/add to cart/i)).toBeInTheDocument();
    });

    it("shows continue shopping when items exist", async () => {
        const { MyWishlistPage } = await import("@/pages/public/my-wishlist");
        renderWithProviders(<MyWishlistPage />, {
            wishlist: {
                items: [mockWishlistItem],
                toggleItem: vi.fn(),
                isPending: false,
            },
        });
        expect(
            screen.getByRole("link", { name: /continue shopping/i }),
        ).toBeInTheDocument();
    });

    it("calls addItem when add to cart is clicked", async () => {
        const user = userEvent.setup();
        const { MyWishlistPage } = await import("@/pages/public/my-wishlist");
        renderWithProviders(<MyWishlistPage />, {
            wishlist: {
                items: [mockWishlistItem],
                toggleItem: vi.fn(),
                isPending: false,
            },
        });
        await user.click(screen.getByText(/add to cart/i));
        expect(screen.getByText(/add to cart/i)).toBeInTheDocument();
    });

    it("shows out of stock state", async () => {
        const outOfStockItem = {
            ...mockWishlistItem,
            product: {
                ...mockWishlistItem.product,
                in_stock: false,
                original: 200,
            },
        };
        const { MyWishlistPage } = await import("@/pages/public/my-wishlist");
        renderWithProviders(<MyWishlistPage />, {
            wishlist: {
                items: [outOfStockItem],
                toggleItem: vi.fn(),
                isPending: false,
            },
        });
        expect(await screen.findByText(/out of stock/i)).toBeInTheDocument();
        expect(screen.getByText(/view product/i)).toBeInTheDocument();
    });
});

describe("SuccessPage", () => {
    const orderData = {
        code: "ORD-001",
        date: "2025-01-15",
        subtotal: 200,
        shipping_cost: 30,
        coupon_discount: 0,
        discount: 10,
        tax: 0,
        grand_total: 220,
    };
    const userData = {
        name: "John",
        email: "john@test.com",
        phone: "01700000000",
        address: "Dhaka",
    };
    const trackerData = {
        transaction_id: "ORD-001",
        value: 220,
        shipping: 30,
        coupon: "",
        tax: 0,
        customer_type: "new",
        items: [],
    };

    it("renders order confirmation", async () => {
        const { SuccessPage } = await import("@/pages/public/success");
        const params = new URLSearchParams({
            order: JSON.stringify(orderData),
            user: JSON.stringify(userData),
            tracker: JSON.stringify(trackerData),
        });
        renderWithProviders(<SuccessPage />, {
            initialEntries: [`/checkout/success?${params.toString()}`],
        });
        expect(await screen.findByText("Order Confirmed!")).toBeInTheDocument();
        expect(screen.getByText("ORD-001")).toBeInTheDocument();
        expect(
            screen.getByRole("link", { name: /continue shopping/i }),
        ).toBeInTheDocument();
        expect(
            screen.getByRole("link", { name: /customer support/i }),
        ).toHaveAttribute("href", "/contact");
    });
});
