import { renderWithProviders } from "../test-utils";
import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import { fireEvent } from "@testing-library/react";
import { toast } from "react-hot-toast";
import type { Mock } from "vitest";

vi.mock("react-hot-toast", () => ({
    toast: { error: vi.fn(), success: vi.fn() },
}));

vi.mock("@/helper", () => ({
    isAuthenticated: vi.fn(() => true),
    getAuthUserId: vi.fn(() => "1"),
    getTempUserId: vi.fn(() => null),
    isPathActive: vi.fn(() => false),
    convertJsonToObject: vi.fn(() => null),
}));

vi.mock("@/api/auth", () => ({
    useGetUser: vi.fn(),
    useLoginMutation: vi.fn(),
    useRegisterMutation: vi.fn(),
    useProfileUpdateMutation: vi.fn(),
    useChangePasswordMutation: vi.fn(),
    useLogoutMutation: vi.fn(),
    sessionRemove: vi.fn(),
    sessionStore: vi.fn(),
}));

vi.mock("@/api/order", () => ({
    useGetOrderList: vi.fn(),
    useGetOrderDetails: vi.fn(),
    useDownloadInvoice: vi.fn(() => ({ mutate: vi.fn(), isPending: false })),
}));

const {
    useGetUser,
    useProfileUpdateMutation,
    useChangePasswordMutation,
    useLogoutMutation,
} = await import("@/api/auth");
const { useGetOrderList, useGetOrderDetails } = await import("@/api/order");

const mockUserData = {
    data: {
        data: {
            user: {
                id: 1,
                name: "John Doe",
                email: "john@test.com",
                phone: "01700000000",
                avatar: null,
                address: "Dhaka",
                city: "Dhaka",
                state: "Dhaka",
                country: "Bangladesh",
                postal_code: "1209",
            },
            summary: {
                total_orders: 10,
                pending_orders: 2,
                processing_orders: 1,
                completed_orders: 7,
                total_spent: 5000,
                cart_items: 3,
                wishlist_items: 5,
                success_rate: "70%",
            },
            steps: [],
            recent_orders: [
                {
                    id: 1,
                    code: "ORD-001",
                    date: "2025-01-15",
                    delivery_status: "delivered",
                    payment_status: "paid",
                    grand_total: 230,
                    items_count: 2,
                },
            ],
        },
    },
    isLoading: false,
    error: null,
};

const mockOrderListData = {
    data: {
        pages: [
            {
                data: {
                    orders: [
                        {
                            id: 1,
                            code: "ORD-001",
                            date: "2025-01-15",
                            delivery_status: "delivered",
                            payment_status: "paid",
                            grand_total: 230,
                            items_count: 2,
                            status: "completed",
                        },
                    ],
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
};

describe("DashboardPage", () => {
    beforeEach(() => {
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
    });

    it("renders dashboard with user data", async () => {
        const { DashboardPage } = await import("@/pages/private/dashboard");
        renderWithProviders(<DashboardPage />);
        expect(await screen.findByText("Total Orders")).toBeInTheDocument();
        expect(screen.getByText("10")).toBeInTheDocument();
    });

    it("renders recent orders section", async () => {
        const { DashboardPage } = await import("@/pages/private/dashboard");
        renderWithProviders(<DashboardPage />);
        expect(await screen.findByText(/ORD-001/i)).toBeInTheDocument();
    });

    it("renders with skeleton while loading", async () => {
        (useGetUser as Mock).mockReturnValue({
            ...mockUserData,
            isLoading: true,
            data: null,
        });
        const { DashboardPage } = await import("@/pages/private/dashboard");
        renderWithProviders(<DashboardPage />);
        expect(await screen.findByText(/welcome back/i)).toBeInTheDocument();
    });
});

describe("ProfilePage", () => {
    beforeEach(() => {
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useProfileUpdateMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        (useChangePasswordMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
    });

    it("renders profile tabs", async () => {
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        const manageProfiles = await screen.findAllByText(/manage profile/i);
        expect(manageProfiles.length).toBeGreaterThanOrEqual(1);
        expect(screen.getByText(/profile info/i)).toBeInTheDocument();
        expect(screen.getByText(/change password/i)).toBeInTheDocument();
    });

    it("renders user info in profile tab", async () => {
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        expect(await screen.findByDisplayValue("John Doe")).toBeInTheDocument();
        expect(screen.getByDisplayValue("john@test.com")).toBeInTheDocument();
        expect(screen.getByDisplayValue("01700000000")).toBeInTheDocument();
    });

    it("switches to password tab", async () => {
        const user = userEvent.setup();
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        await user.click(screen.getByText(/change password/i));
        const newPassLabels = screen.getAllByText(/new password/i);
        expect(newPassLabels.length).toBeGreaterThanOrEqual(1);
        expect(screen.getByText(/current password/i)).toBeInTheDocument();
        expect(screen.getByText(/confirm new password/i)).toBeInTheDocument();
    });

    it("submits profile form", async () => {
        const user = userEvent.setup();
        const mockUpdateProfile = vi.fn();
        (useProfileUpdateMutation as Mock).mockReturnValue({
            mutate: mockUpdateProfile,
            isPending: false,
        });
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        await user.click(screen.getByRole("button", { name: /save changes/i }));
        expect(mockUpdateProfile).toHaveBeenCalled();
    });

    it("shows validation toast when submitting empty form", async () => {
        (useGetUser as Mock).mockReturnValue({
            ...mockUserData,
            data: {
                data: {
                    user: { id: 1, name: "", email: "" },
                    summary: {},
                    steps: [],
                    recent_orders: [],
                },
            },
        });
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        const form = screen
            .getByRole("button", { name: /save changes/i })
            .closest("form")!;
        fireEvent.submit(form);
        expect(toast.error).toHaveBeenCalledWith("Name and email are required");
    });

    it("shows password validation on empty submission", async () => {
        const user = userEvent.setup();
        const { ProfilePage } = await import("@/pages/private/profile");
        renderWithProviders(<ProfilePage />);
        await user.click(screen.getByText(/change password/i));
        const form = screen
            .getByRole("button", { name: /update password/i })
            .closest("form")!;
        fireEvent.submit(form);
        expect(toast.error).toHaveBeenCalledWith(
            "Please fill in all password fields",
        );
    });
});

describe("OrdersPage (private)", () => {
    it("renders order list", async () => {
        (useGetOrderList as Mock).mockReturnValue(mockOrderListData);
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { OrdersPage } = await import("@/pages/private/orders");
        renderWithProviders(<OrdersPage />);
        const purchaseHeadings =
            await screen.findAllByText(/purchase history/i);
        expect(purchaseHeadings.length).toBeGreaterThanOrEqual(1);
        expect(await screen.findByText(/ORD-001/i)).toBeInTheDocument();
    });

    it("renders empty orders state", async () => {
        (useGetOrderList as Mock).mockReturnValue({
            ...mockOrderListData,
            data: {
                pages: [
                    {
                        data: {
                            orders: [],
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
        });
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { OrdersPage } = await import("@/pages/private/orders");
        renderWithProviders(<OrdersPage />);
        expect(await screen.findByText(/no orders yet/i)).toBeInTheDocument();
    });
});

describe("OrderDetailsPage (private)", () => {
    it("renders order details", async () => {
        (useGetOrderDetails as Mock).mockReturnValue({
            data: {
                data: {
                    details: {
                        id: 1,
                        code: "ORD-001",
                        date: "2025-01-15",
                        status: "processing",
                        products: [
                            {
                                id: 1,
                                name: "Test",
                                image: "/test.jpg",
                                price: 100,
                            },
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
        });
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { OrderDetailsPage } =
            await import("@/pages/private/orders/details");
        renderWithProviders(<OrderDetailsPage />, {
            initialEntries: ["/dashboard/orders/ORD-001"],
        });
        expect(await screen.findByText(/ORD-001/i)).toBeInTheDocument();
    });
});

describe("TrackOrderPage (private)", () => {
    it("renders search form", async () => {
        (useGetUser as Mock).mockReturnValue(mockUserData);
        (useLogoutMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { TrackOrderPage } = await import("@/pages/private/track-order");
        renderWithProviders(<TrackOrderPage />);
        expect(
            await screen.findByText(/track your order/i),
        ).toBeInTheDocument();
    });
});
