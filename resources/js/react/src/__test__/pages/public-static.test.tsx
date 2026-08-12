import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import type { Mock } from "vitest";
import { renderWithProviders } from "../test-utils";

vi.mock("@/api/page", () => ({
    useGetAbout: vi.fn(),
    useGetFaqs: vi.fn(),
    useGetPage: vi.fn(),
}));
vi.mock("@/api/contact", () => ({ useContactStoreMutation: vi.fn() }));

const { useGetAbout, useGetFaqs, useGetPage } = await import("@/api/page");
const { useContactStoreMutation } = await import("@/api/contact");

const mockAboutData = {
    data: {
        data: {
            items: [
                {
                    id: 1,
                    title: "Our Story",
                    sub_title: "Who We Are",
                    description: "<p>We are great</p>",
                    image: "/about.jpg",
                    image_position: "right",
                },
            ],
            counters: [{ number: "100+", title: "Happy Clients" }],
        },
    },
    isLoading: false,
    error: null,
};

describe("AboutPage", () => {
    it("renders loading state", async () => {
        (useGetAbout as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { AboutPage } = await import("@/pages/public/about");
        renderWithProviders(<AboutPage />);
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders about content", async () => {
        (useGetAbout as Mock).mockReturnValue(mockAboutData);
        const { AboutPage } = await import("@/pages/public/about");
        renderWithProviders(<AboutPage />);
        expect(await screen.findByText("Our Story")).toBeInTheDocument();
        expect(await screen.findByText("Who We Are")).toBeInTheDocument();
        expect(await screen.findByText("100+")).toBeInTheDocument();
        expect(await screen.findByText("Happy Clients")).toBeInTheDocument();
    });
});

describe("FaqsPage", () => {
    it("renders loading state", async () => {
        (useGetFaqs as Mock).mockReturnValue({
            data: null,
            isLoading: true,
            error: null,
        });
        const { FaqsPage } = await import("@/pages/public/faqs");
        renderWithProviders(<FaqsPage />);
        expect(screen.getByRole("status")).toBeInTheDocument();
    });

    it("renders faq list", async () => {
        (useGetFaqs as Mock).mockReturnValue({
            data: {
                data: { faqs: [{ id: 1, question: "Q1?", answer: "A1." }] },
            },
            isLoading: false,
            error: null,
        });
        const { FaqsPage } = await import("@/pages/public/faqs");
        renderWithProviders(<FaqsPage />);
        expect(await screen.findByText("Q1?")).toBeInTheDocument();
    });

    it("renders empty faqs state", async () => {
        (useGetFaqs as Mock).mockReturnValue({
            data: { data: { faqs: [] } },
            isLoading: false,
            error: null,
        });
        const { FaqsPage } = await import("@/pages/public/faqs");
        renderWithProviders(<FaqsPage />);
        expect(
            await screen.findByText(/no questions found/i),
        ).toBeInTheDocument();
    });

    it("renders contact support link", async () => {
        (useGetFaqs as Mock).mockReturnValue({
            data: { data: { faqs: [] } },
            isLoading: false,
            error: null,
        });
        const { FaqsPage } = await import("@/pages/public/faqs");
        renderWithProviders(<FaqsPage />);
        expect(
            screen.getByRole("link", { name: /contact our support team/i }),
        ).toHaveAttribute("href", "/contact-us");
    });
});

describe("PolicyPage", () => {
    it("renders policy content", async () => {
        (useGetPage as Mock).mockReturnValue({
            data: {
                data: { title: "Privacy Policy", content: "<p>Our policy</p>" },
            },
            isLoading: false,
            error: null,
        });
        const { PolicyPage } = await import("@/pages/public/policy");
        renderWithProviders(<PolicyPage />, {
            initialEntries: ["/pages/privacy-policy"],
        });
        const headings = await screen.findAllByText("Privacy Policy");
        expect(headings.length).toBeGreaterThanOrEqual(1);
    });
});

describe("ContactPage", () => {
    it("renders contact form", async () => {
        const mockMutate = vi.fn();
        (useContactStoreMutation as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
        });

        const { ContactPage } = await import("@/pages/public/contact");
        renderWithProviders(<ContactPage />);

        expect(await screen.findByText("Get In Touch")).toBeInTheDocument();
        expect(screen.getByText("Contact Information")).toBeInTheDocument();
        expect(screen.getByLabelText("Full Name")).toBeInTheDocument();
        expect(screen.getByLabelText("Email Address")).toBeInTheDocument();
        expect(screen.getByLabelText("Subject")).toBeInTheDocument();
        expect(screen.getByLabelText("Message")).toBeInTheDocument();
        expect(
            screen.getByRole("button", { name: /send message/i }),
        ).toBeInTheDocument();
    });

    it("shows contact info from config", async () => {
        (useContactStoreMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { ContactPage } = await import("@/pages/public/contact");
        renderWithProviders(<ContactPage />, {
            config: {
                header_logo: "/logo.png",
                footer_logo: "/footer-logo.png",
                contact_phone: "+880-1700-123456",
                contact_email: "test@shop.com",
                contact_address: "Dhaka, Bangladesh",
                currency_setting: {
                    currency: "BDT",
                    currency_symbol: "৳",
                    is_currency_symbol: true,
                    currency_position: "before",
                    is_decimal: true,
                    decimal_digits: 2,
                },
            },
        });

        expect(await screen.findByText("+880-1700-123456")).toBeInTheDocument();
        expect(screen.getByText("test@shop.com")).toBeInTheDocument();
        expect(
            screen.getAllByText("Dhaka, Bangladesh").length,
        ).toBeGreaterThanOrEqual(1);
    });

    it("submits the contact form", async () => {
        const user = userEvent.setup();
        const mockMutate = vi.fn();
        (useContactStoreMutation as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
        });

        const { ContactPage } = await import("@/pages/public/contact");
        renderWithProviders(<ContactPage />);

        await user.type(screen.getByLabelText("Full Name"), "John Doe");
        await user.type(
            screen.getByLabelText("Email Address"),
            "john@test.com",
        );
        await user.type(screen.getByLabelText("Subject"), "Question");
        await user.type(screen.getByLabelText("Message"), "Hello!");

        await user.click(screen.getByRole("button", { name: /send message/i }));
        expect(mockMutate).toHaveBeenCalledWith(expect.any(FormData));
    });
});
