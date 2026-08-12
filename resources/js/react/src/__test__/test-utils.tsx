import type { ReactElement } from "react";
import { MemoryRouter, type MemoryRouterProps } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { render, type RenderOptions } from "@testing-library/react";
import { HelmetProvider } from "react-helmet-async";
import { CartContext, type CartContextType } from "@/hooks/useCart";
import { WishlistContext, type IWishlistContext } from "@/hooks/useWishlist";
import { ConfigContext, type ConfigData } from "@/hooks/useConfig";

const defaultConfig: ConfigData = {
    header_logo: "/logo.png",
    footer_logo: "/footer-logo.png",
    contact_phone: "+880-1700-000000",
    contact_email: "info@example.com",
    contact_address: "Dhaka, Bangladesh",
    currency_setting: {
        currency: "BDT",
        currency_symbol: "৳",
        is_currency_symbol: true,
        currency_position: "before",
        is_decimal: true,
        decimal_digits: 2,
    },
    base_color: "#000000",
    base_hov_color: "#333333",
};

const defaultCart: CartContextType = {
    items: [],
    summary: {
        subtotal: 0,
        total_discount: 0,
        total_tax: 0,
        shipping_cost: 0,
        total_item: 0,
        total: 0,
        coupon_discount: 0,
        coupon_code: "",
        shipping_id: 0,
    },
    addItem: vi.fn(),
    removeItem: vi.fn(),
    updateQuantity: vi.fn(),
    setDrawerOpen: vi.fn(),
    drawerOpen: false,
    isAdding: false,
    isRemoving: false,
    isUpdating: false,
};

const defaultWishlist: IWishlistContext = {
    items: [],
    toggleItem: vi.fn(),
    isPending: false,
};

let queryClient: QueryClient;

const createTestQueryClient = () =>
    new QueryClient({
        defaultOptions: {
            queries: { retry: false, gcTime: 0 },
            mutations: { retry: false },
        },
    });

interface RenderWithProvidersOptions extends Omit<RenderOptions, "wrapper"> {
    initialEntries?: MemoryRouterProps["initialEntries"];
    config?: ConfigData;
    cart?: Partial<CartContextType>;
    wishlist?: Partial<IWishlistContext>;
}

export const renderWithProviders = (
    ui: ReactElement,
    {
        initialEntries = ["/"],
        config = defaultConfig,
        cart,
        wishlist,
        ...renderOptions
    }: RenderWithProvidersOptions = {},
) => {
    queryClient = createTestQueryClient();

    const Wrapper = ({ children }: { children: React.ReactNode }) => (
        <HelmetProvider>
            <QueryClientProvider client={queryClient}>
                <MemoryRouter initialEntries={initialEntries}>
                    <ConfigContext.Provider value={config}>
                        <CartContext.Provider
                            value={{ ...defaultCart, ...cart }}
                        >
                            <WishlistContext.Provider
                                value={{ ...defaultWishlist, ...wishlist }}
                            >
                                {children}
                            </WishlistContext.Provider>
                        </CartContext.Provider>
                    </ConfigContext.Provider>
                </MemoryRouter>
            </QueryClientProvider>
        </HelmetProvider>
    );

    return {
        ...render(ui, { wrapper: Wrapper, ...renderOptions }),
        queryClient,
    };
};

beforeAll(() => {
    window.scrollTo = vi.fn();
    window.dataLayer = [];
    Object.defineProperty(window, "dataLayer", {
        value: [],
        writable: true,
    });
    Element.prototype.scrollIntoView = vi.fn();
});

afterEach(() => {
    if (queryClient) {
        queryClient.clear();
    }
});
