import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import { useState } from "react";
import type { IOrderFrom } from "@/type";
import { renderWithProviders } from "../test-utils";

vi.mock("@/api/checkout", () => ({
    useCheckoutMutation: vi.fn(),
    useIncompleteOrderMutation: vi.fn(() => ({
        mutate: vi.fn(),
        isPending: false,
    })),
}));

const defaultForm: IOrderFrom = {
    name: "",
    email: "",
    phone: "",
    address: "",
    notes: "",
    payment: "",
    shipping: "",
};

describe("OrderFrom", () => {
    it("renders all form fields", async () => {
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        renderWithProviders(<OrderFrom form={defaultForm} setForm={vi.fn()} />);
        expect(screen.getByLabelText(/full name/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/email/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/phone/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/address/i)).toBeInTheDocument();
        expect(screen.getByLabelText(/notes/i)).toBeInTheDocument();
    });

    it("shows validation error on blur for empty required field", async () => {
        const user = userEvent.setup();
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        renderWithProviders(<OrderFrom form={defaultForm} setForm={vi.fn()} />);

        const nameInput = screen.getByLabelText(/full name/i);
        await user.click(nameInput);
        await user.tab();

        expect(await screen.findByText("Name is required")).toBeInTheDocument();
    });

    it("shows email validation error for invalid email", async () => {
        const user = userEvent.setup();
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        const Wrapper = () => {
            const [form, setForm] = useState(defaultForm);
            return <OrderFrom form={form} setForm={setForm} />;
        };
        renderWithProviders(<Wrapper />);

        const emailInput = screen.getByLabelText(/email/i);
        await user.type(emailInput, "invalid");
        await user.tab();

        expect(await screen.findByText(/valid email/i)).toBeInTheDocument();
    });

    it("shows phone validation error for short phone", async () => {
        const user = userEvent.setup();
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        const Wrapper = () => {
            const [form, setForm] = useState(defaultForm);
            return <OrderFrom form={form} setForm={setForm} />;
        };
        renderWithProviders(<Wrapper />);

        const phoneInput = screen.getByLabelText(/phone/i);
        await user.type(phoneInput, "123");
        await user.tab();

        expect(await screen.findByText(/11 digits/i)).toBeInTheDocument();
    });

    it("shows address validation error on blur", async () => {
        const user = userEvent.setup();
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        const Wrapper = () => {
            const [form, setForm] = useState(defaultForm);
            return <OrderFrom form={form} setForm={setForm} />;
        };
        renderWithProviders(<Wrapper />);

        const addrInput = screen.getByLabelText(/address/i);
        await user.click(addrInput);
        await user.tab();

        expect(
            await screen.findByText("Address is required"),
        ).toBeInTheDocument();
    });

    it("calls setForm when typing", async () => {
        const user = userEvent.setup();
        const setForm = vi.fn();
        const { OrderFrom } = await import("@/pages/public/checkout/from");
        renderWithProviders(<OrderFrom form={defaultForm} setForm={setForm} />);

        await user.type(screen.getByLabelText(/full name/i), "J");
        expect(setForm).toHaveBeenCalled();
    });
});
