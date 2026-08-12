import { renderWithProviders } from "../test-utils";
import { screen } from "@testing-library/react";
import {
    mockProduct,
    mockSlider,
    mockCampaign,
    mockProductDetailData,
} from "../mocks/data";
import type { Mock } from "vitest";

vi.mock("@/api/home", () => ({ useGetHome: vi.fn() }));
vi.mock("@/api/product", () => ({
    useGetProducts: vi.fn(),
    useGetCollections: vi.fn(),
    useProductDetails: vi.fn(),
    useGetCategoryProducts: vi.fn(),
    useCampaignProducts: vi.fn(),
    useSearchProducts: vi.fn(),
    useGetSearchProducts: vi.fn(),
}));

const { useGetHome } = await import("@/api/home");
const {
    useGetProducts,
    useGetCollections,
    useProductDetails,
    useGetCategoryProducts,
    useCampaignProducts,
    useSearchProducts,
} = await import("@/api/product");

const { useGetSearchProducts } = await import("@/api/product");

beforeEach(() => {
    (useGetSearchProducts as Mock).mockReturnValue({
        data: null,
        isLoading: false,
    });
});

const mockInfiniteData = (products = [mockProduct]) => ({
    data: {
        pages: [
            {
                data: {
                    products,
                    pagination: {
                        current_page: 1,
                        total_pages: 1,
                        has_more: false,
                    },
                },
            },
        ],
        pageParams: [1],
    },
    fetchNextPage: vi.fn(),
    hasNextPage: false,
    isFetchingNextPage: false,
    isLoading: false,
    error: null,
});

describe("HomePage", () => {
    it("renders loading state", async () => {
        (useGetHome as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { HomePage } = await import("@/pages/public/home");
        renderWithProviders(<HomePage />);
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders home sections with data", async () => {
        (useGetHome as Mock).mockReturnValue({
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
                            name: "Test Cat",
                            slug: "test-cat",
                            image: "/cat.jpg",
                            products: [mockProduct],
                        },
                    ],
                    brands: [
                        {
                            id: 1,
                            name: "Brand 1",
                            logo: "/brand.png",
                            slug: "brand-1",
                        },
                    ],
                    campaigns: [mockCampaign],
                },
            },
            isLoading: false,
            error: null,
        });
        const { HomePage } = await import("@/pages/public/home");
        renderWithProviders(<HomePage />);
        const products = await screen.findAllByText("Test Product");
        expect(products.length).toBeGreaterThanOrEqual(1);
    });
});

describe("ProductsPage", () => {
    it("renders loading state", async () => {
        (useGetProducts as Mock).mockReturnValue(mockInfiniteData([]));
        const { useGetProducts: ugp } = await import("@/api/product");
        (ugp as Mock).mockReturnValue({
            ...mockInfiniteData([]),
            isLoading: true,
            data: undefined,
        });
        const { ProductsPage } = await import("@/pages/public/products");
        renderWithProviders(<ProductsPage />, {
            initialEntries: ["/products"],
        });
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders products when loaded", async () => {
        (useGetProducts as Mock).mockReturnValue(
            mockInfiniteData([mockProduct]),
        );
        const { ProductsPage } = await import("@/pages/public/products");
        renderWithProviders(<ProductsPage />, {
            initialEntries: ["/products"],
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });

    it("renders empty state when no products match", async () => {
        (useGetProducts as Mock).mockReturnValue(mockInfiniteData([]));
        const { ProductsPage } = await import("@/pages/public/products");
        renderWithProviders(<ProductsPage />, {
            initialEntries: ["/products"],
        });
        expect(
            await screen.findByText(/no products match/i),
        ).toBeInTheDocument();
    });
});

describe("ProductDetailPage", () => {
    it("renders loading state", async () => {
        (useProductDetails as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { ProductDetailPage } =
            await import("@/pages/public/product-details");
        renderWithProviders(<ProductDetailPage />, {
            initialEntries: ["/products/test-product"],
        });
        expect(await screen.findByText("Products")).toBeInTheDocument();
    });

    it("renders product details when loaded", async () => {
        (useProductDetails as Mock).mockReturnValue(mockProductDetailData);
        const { ProductDetailPage } =
            await import("@/pages/public/product-details");
        renderWithProviders(<ProductDetailPage />, {
            initialEntries: ["/products/test-product"],
        });
        const products = await screen.findAllByText("Test Product");
        expect(products.length).toBeGreaterThanOrEqual(1);
        expect(
            await screen.findByText(/you may also like/i),
        ).toBeInTheDocument();
    });
});

describe("CollectionsPage", () => {
    it("renders loading state", async () => {
        (useGetCollections as Mock).mockReturnValue({
            ...mockInfiniteData([]),
            isLoading: true,
            data: undefined,
        });
        const { CollectionsPage } = await import("@/pages/public/collections");
        renderWithProviders(<CollectionsPage />, {
            initialEntries: ["/collections/new-arrivals"],
        });
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders collection products", async () => {
        (useGetCollections as Mock).mockReturnValue(
            mockInfiniteData([mockProduct]),
        );
        const { CollectionsPage } = await import("@/pages/public/collections");
        renderWithProviders(<CollectionsPage />, {
            initialEntries: ["/collections/new-arrivals"],
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });

    it("renders empty state", async () => {
        (useGetCollections as Mock).mockReturnValue(mockInfiniteData([]));
        const { CollectionsPage } = await import("@/pages/public/collections");
        renderWithProviders(<CollectionsPage />, {
            initialEntries: ["/collections/new-arrivals"],
        });
        expect(
            await screen.findByText(/no collections found/i),
        ).toBeInTheDocument();
    });
});

describe("CampaignPage", () => {
    it("renders loading state", async () => {
        (useCampaignProducts as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { CampaignPage } = await import("@/pages/public/campaign");
        renderWithProviders(<CampaignPage />, {
            initialEntries: ["/campaigns/summer-sale"],
        });
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders campaign products", async () => {
        (useCampaignProducts as Mock).mockReturnValue({
            data: { data: { products: [mockProduct], campaign: mockCampaign } },
            isLoading: false,
            error: null,
        });
        const { CampaignPage } = await import("@/pages/public/campaign");
        renderWithProviders(<CampaignPage />, {
            initialEntries: ["/campaigns/summer-sale"],
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });
});

describe("CategoryProductsPage", () => {
    it("renders products for category", async () => {
        (useGetCategoryProducts as Mock).mockReturnValue(
            mockInfiniteData([mockProduct]),
        );
        const { CategoryProductsPage } =
            await import("@/pages/public/categories");
        renderWithProviders(<CategoryProductsPage />, {
            initialEntries: ["/categories/test-cat"],
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });
});

describe("ProductsSearchPage", () => {
    it("renders search results", async () => {
        (useSearchProducts as Mock).mockReturnValue(
            mockInfiniteData([mockProduct]),
        );
        const { ProductsSearchPage } = await import("@/pages/public/search");
        renderWithProviders(<ProductsSearchPage />, {
            initialEntries: ["/search?q=test"],
        });
        expect(await screen.findByText("Test Product")).toBeInTheDocument();
    });

    it("renders empty search state", async () => {
        (useSearchProducts as Mock).mockReturnValue(mockInfiniteData([]));
        const { ProductsSearchPage } = await import("@/pages/public/search");
        renderWithProviders(<ProductsSearchPage />, {
            initialEntries: ["/search?q=nonexistent"],
        });
        expect(
            await screen.findByText(/no products found/i),
        ).toBeInTheDocument();
    });
});
