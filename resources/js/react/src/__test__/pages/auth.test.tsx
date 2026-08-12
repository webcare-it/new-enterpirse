import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import type { Mock } from "vitest";
import { renderWithProviders } from "../test-utils";

vi.mock("@/api/auth", () => ({
    useLoginMutation: vi.fn(),
    useRegisterMutation: vi.fn(),
    sessionRemove: vi.fn(),
    sessionStore: vi.fn(),
}));

const { useLoginMutation, useRegisterMutation } = await import("@/api/auth");

describe("SignInPage", () => {
    it("renders sign in form", async () => {
        (useLoginMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { SignInPage } = await import("@/pages/public/auth/signin");
        renderWithProviders(<SignInPage />);
        expect(await screen.findByText("Welcome back")).toBeInTheDocument();
        expect(screen.getByLabelText(/phone/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/password/i)).toBeInTheDocument();
        expect(
            screen.getByRole("button", { name: /sign in/i }),
        ).toBeInTheDocument();
    });

    it("renders sign up link", async () => {
        (useLoginMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { SignInPage } = await import("@/pages/public/auth/signin");
        renderWithProviders(<SignInPage />);
        expect(
            screen.getByRole("link", { name: /create one/i }),
        ).toHaveAttribute("href", "/signup");
    });

    it("calls login mutation on submit", async () => {
        const user = userEvent.setup();
        const mockMutate = vi.fn();
        (useLoginMutation as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
        });

        const { SignInPage } = await import("@/pages/public/auth/signin");
        renderWithProviders(<SignInPage />);

        await user.type(screen.getByLabelText(/phone/i), "01700000000");
        await user.type(screen.getByLabelText(/password/i), "password123");
        await user.click(screen.getByRole("button", { name: /sign in/i }));

        expect(mockMutate).toHaveBeenCalledWith(
            expect.objectContaining({
                phone: "01700000000",
                password: "password123",
            }),
        );
    });
});

describe("SignUpPage", () => {
    it("renders sign up form", async () => {
        (useRegisterMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { SignUpPage } = await import("@/pages/public/auth/signup");
        renderWithProviders(<SignUpPage />);
        expect(await screen.findByText(/create account/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/full name/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/phone/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/^password/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/confirm password/i)).toBeInTheDocument();
    });

    it("renders sign in link", async () => {
        (useRegisterMutation as Mock).mockReturnValue({
            mutate: vi.fn(),
            isPending: false,
        });
        const { SignUpPage } = await import("@/pages/public/auth/signup");
        renderWithProviders(<SignUpPage />);
        expect(screen.getByRole("link", { name: /sign in/i })).toHaveAttribute(
            "href",
            "/signin",
        );
    });

    it("calls register mutation on submit", async () => {
        const user = userEvent.setup();
        const mockMutate = vi.fn();
        (useRegisterMutation as Mock).mockReturnValue({
            mutate: mockMutate,
            isPending: false,
        });

        const { SignUpPage } = await import("@/pages/public/auth/signup");
        renderWithProviders(<SignUpPage />);

        await user.type(screen.getByLabelText(/full name/i), "John Doe");
        await user.type(screen.getByLabelText(/phone/i), "01700000000");
        await user.type(screen.getByLabelText(/^password/i), "password123");
        await user.type(
            screen.getByLabelText(/confirm password/i),
            "password123",
        );
        await user.click(screen.getByRole("button", { name: /sign up/i }));

        expect(mockMutate).toHaveBeenCalled();
    });
});

describe("RedirectPage", () => {
    it("renders redirecting state", async () => {
        const { RedirectPage } = await import("@/pages/public/auth/redirect");
        renderWithProviders(<RedirectPage />, {
            initialEntries: ["/redirect?token=abc123&user_id=1"],
        });
        expect(await screen.findByText(/redirecting/i)).toBeInTheDocument();
    });
});
