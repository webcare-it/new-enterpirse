import { render, screen } from "@testing-library/react";
import userEvent from "@testing-library/user-event";
import { Button } from "../button";

describe("Button", () => {
    it("renders children", () => {
        render(<Button>Click me</Button>);
        expect(screen.getByRole("button", { name: /click me/i })).toBeInTheDocument();
    });

    it("applies default variant classes", () => {
        render(<Button>Default</Button>);
        const btn = screen.getByRole("button");
        expect(btn).toHaveClass("bg-primary");
    });

    it("applies variant classes", () => {
        render(<Button variant="destructive">Destructive</Button>);
        const btn = screen.getByRole("button");
        expect(btn).toHaveClass("bg-destructive");
    });

    it("applies size classes", () => {
        render(<Button size="lg">Large</Button>);
        const btn = screen.getByRole("button");
        expect(btn).toHaveClass("h-10");
    });

    it("renders as child when asChild is true", () => {
        render(
            <Button asChild>
                <a href="/test">Link</a>
            </Button>,
        );
        const link = screen.getByRole("link", { name: /link/i });
        expect(link).toBeInTheDocument();
        expect(link).toHaveAttribute("href", "/test");
    });

    it("forwards ref", () => {
        const ref = { current: null };
        render(<Button ref={ref}>Ref</Button>);
        expect(ref.current).toBeInstanceOf(HTMLButtonElement);
    });

    it("is disabled when disabled prop is set", () => {
        render(<Button disabled>Disabled</Button>);
        const btn = screen.getByRole("button");
        expect(btn).toBeDisabled();
    });

    it("fires onClick when clicked", async () => {
        const user = userEvent.setup();
        const onClick = vi.fn();
        render(<Button onClick={onClick}>Click</Button>);
        await user.click(screen.getByRole("button"));
        expect(onClick).toHaveBeenCalledTimes(1);
    });
});
