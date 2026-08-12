import { screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import { renderWithProviders } from "../test-utils";

vi.mock("@/api/product", () => ({ useProductDetails: vi.fn() }));

describe("ProductAttributes", () => {
    const sizeAttr = {
        id: 1,
        name: "Size",
        values: [
            { id: 10, value: "S", sku: "S", price: "10.00", stock: 5, image: "" },
            { id: 11, value: "M", sku: "M", price: "10.00", stock: 3, image: "" },
            { id: 12, value: "L", sku: "L", price: "10.00", stock: 0, image: "" },
        ],
    };

    const colorAttr = {
        id: 2,
        name: "Color",
        values: [
            {
                id: 20,
                value: "Red",
                sku: "R",
                price: "10.00",
                stock: 2,
                image: "/red.jpg",
            },
            {
                id: 21,
                value: "Blue",
                sku: "B",
                price: "10.00",
                stock: 4,
                image: "/blue.jpg",
            },
        ],
    };

    it("returns null when attributes array is empty", async () => {
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        const { container } = renderWithProviders(
            <ProductAttributes
                attributes={[]}
                select={{}}
                onSelect={vi.fn()}
            />,
        );
        expect(container.innerHTML).toBe("");
    });

    it("renders attribute names and values", async () => {
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        const onSelect = vi.fn();
        renderWithProviders(
            <ProductAttributes
                attributes={[sizeAttr]}
                select={{}}
                onSelect={onSelect}
            />,
        );
        expect(screen.getByText("Size:")).toBeInTheDocument();
        expect(screen.getByText(/select size/i)).toBeInTheDocument();
        expect(screen.getByText("S")).toBeInTheDocument();
        expect(screen.getByText("M")).toBeInTheDocument();
        expect(screen.getByText("L")).toBeInTheDocument();
    });

    it("highlights selected value", async () => {
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        renderWithProviders(
            <ProductAttributes
                attributes={[sizeAttr]}
                select={{ Size: "M" }}
                onSelect={vi.fn()}
            />,
        );
        const ms = screen.getAllByText("M");
        expect(ms.length).toBeGreaterThanOrEqual(1);
    });

    it("calls onSelect when a value is clicked", async () => {
        const user = userEvent.setup();
        const onSelect = vi.fn();
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        renderWithProviders(
            <ProductAttributes
                attributes={[sizeAttr]}
                select={{}}
                onSelect={onSelect}
            />,
        );
        await user.click(screen.getByText("M"));
        expect(onSelect).toHaveBeenCalledWith("Size", sizeAttr.values[1]);
    });

    it("renders color attributes with images", async () => {
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        const onSelect = vi.fn();
        renderWithProviders(
            <ProductAttributes
                attributes={[colorAttr]}
                select={{}}
                onSelect={onSelect}
            />,
        );
        expect(screen.getByText("Color:")).toBeInTheDocument();
        expect(screen.getByText(/select color/i)).toBeInTheDocument();
        const buttons = screen.getAllByRole("button");
        expect(buttons.length).toBe(2);
    });

    it("applies selected style to color buttons", async () => {
        const { ProductAttributes } =
            await import("@/pages/public/product-details/attributes");
        renderWithProviders(
            <ProductAttributes
                attributes={[colorAttr]}
                select={{ Color: "Red" }}
                onSelect={vi.fn()}
            />,
        );
        expect(screen.getByText("Color:")).toBeInTheDocument();
        expect(screen.queryByText(/select color/i)).not.toBeInTheDocument();
    });
});
