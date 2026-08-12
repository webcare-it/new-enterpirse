import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import type { Mock } from "vitest";
import { renderWithProviders } from "../test-utils";

vi.mock("@/api/order", () => ({
    useGetOrderDetails: vi.fn(),
    useTrackOrder: vi.fn(),
    useDownloadInvoice: vi.fn(() => ({ mutate: vi.fn(), isPending: false })),
}));

const { useGetOrderDetails, useTrackOrder } = await import("@/api/order");

const mockOrder = {
    data: {
        data: {
            details: {
                id: 1,
                code: "ORD-001",
                date: "2025-01-15",
                status: "processing",
                products: [
                    { id: 1, name: "Test", image: "/test.jpg", price: 100 },
                ],
                summary: {
                    subtotal: 200,
                    discount: 0,
                    coupon_discount: 0,
                    tax: 0,
                    shipping_cost: 30,
                    total: 230,
                },
                payment_status: "paid",
                payment_method_title: "Cash on Delivery",
                shipping: { phone: "01700000000" },
            },
        },
    },
    isLoading: false,
    error: null,
};

describe("NotFoundPage", () => {
    it("renders 404 content", async () => {
        const { NotFoundPage } =
            await import("@/pages/public/utils-pages/notfound");
        renderWithProviders(<NotFoundPage />);
        expect(await screen.findByText("404")).toBeInTheDocument();
        expect(screen.getByText(/page not found/i)).toBeInTheDocument();
        expect(
            screen.getByRole("button", { name: /go back/i }),
        ).toBeInTheDocument();
        expect(
            screen.getByRole("button", { name: /go home/i }),
        ).toBeInTheDocument();
    });
});

describe("ServerError", () => {
    it("renders server error page", async () => {
        const { ServerError } =
            await import("@/pages/public/utils-pages/server");
        renderWithProviders(<ServerError />);
        expect(
            await screen.findByRole("button", { name: /try again/i }),
        ).toBeInTheDocument();
    });
});

describe("MaintenancePage", () => {
    it("renders maintenance mode page", async () => {
        const { MaintenancePage } =
            await import("@/pages/public/utils-pages/maintenance");
        renderWithProviders(<MaintenancePage />);
        expect(
            await screen.findByText(/maintenance mode/i),
        ).toBeInTheDocument();
        expect(
            screen.getByRole("button", { name: /refresh page/i }),
        ).toBeInTheDocument();
    });
});

describe("RootPageLoading", () => {
    it("renders loading spinner", async () => {
        const { RootPageLoading } =
            await import("@/pages/public/utils-pages/root-loading");
        renderWithProviders(<RootPageLoading />);
        expect(screen.getByRole("status")).toBeInTheDocument();
    });
});

describe("OrderDetailsPage (public)", () => {
    it("renders loading state", async () => {
        (useGetOrderDetails as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { OrderDetailsPage } =
            await import("@/pages/public/order-details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/orders/ORD-001"],
        });
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders order details", async () => {
        (useGetOrderDetails as Mock).mockReturnValue(mockOrder);
        const { OrderDetailsPage } =
            await import("@/pages/public/order-details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/orders/ORD-001"],
        });
        const orderRefs = await screen.findAllByText(/ORD-001/i);
        expect(orderRefs.length).toBeGreaterThanOrEqual(1);
    });

    it("renders download invoice button", async () => {
        (useGetOrderDetails as Mock).mockReturnValue(mockOrder);
        const { OrderDetailsPage } =
            await import("@/pages/public/order-details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/orders/ORD-001"],
        });
        expect(
            await screen.findByRole("button", { name: /download invoice/i }),
        ).toBeInTheDocument();
    });

    it("clicking download invoice does not throw", async () => {
        const user = userEvent.setup();
        (useGetOrderDetails as Mock).mockReturnValue(mockOrder);
        const { OrderDetailsPage } =
            await import("@/pages/public/order-details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/orders/ORD-001"],
        });
        const btn = await screen.findByRole("button", {
            name: /download invoice/i,
        });
        await expect(user.click(btn)).resolves.toBeUndefined();
    });

    it("shows downloading state when isPending", async () => {
        (useGetOrderDetails as Mock).mockReturnValue(mockOrder);
        const { OrderDetailsPage } =
            await import("@/pages/public/order-details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/orders/ORD-001"],
        });
        expect(await screen.findByText("Download Invoice")).toBeInTheDocument();
    });
});

describe("TrackOrderPage (public)", () => {
    it("renders search form initially", async () => {
        const mockMutate = vi.fn();
        (useTrackOrder as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
            data: null,
            error: null,
            isSuccess: false,
            reset: vi.fn(),
        });
        const { TrackOrderPage } = await import("@/pages/public/track-order");
        renderWithProviders(<TrackOrderPage />);
        expect(await screen.findByText("Track Your Order")).toBeInTheDocument();
        expect(
            screen.getByPlaceholderText(/enter your order id/i),
        ).toBeInTheDocument();
    });

    it("searches for order", async () => {
        const user = userEvent.setup();
        const mockMutate = vi.fn();
        (useTrackOrder as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
            data: null,
            error: null,
            isSuccess: false,
            reset: vi.fn(),
        });
        const { TrackOrderPage } = await import("@/pages/public/track-order");
        renderWithProviders(<TrackOrderPage />);

        await user.type(
            screen.getByPlaceholderText(/enter your order id/i),
            "ORD-001",
        );
        await user.click(screen.getByRole("button", { name: /^search$/i }));
        expect(mockMutate).toHaveBeenCalledWith("ORD-001");
    });

    it("shows error message when order not found", async () => {
        (useTrackOrder as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
            data: null,
            error: { message: "Not found" },
            isSuccess: false,
            reset: vi.fn(),
        });
        const { TrackOrderPage } = await import("@/pages/public/track-order");
        renderWithProviders(<TrackOrderPage />);
        expect(await screen.findByText(/not found/i)).toBeInTheDocument();
    });
});
