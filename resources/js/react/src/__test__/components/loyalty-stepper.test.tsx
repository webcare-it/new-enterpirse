import { screen } from "@testing-library/react";
import { renderWithProviders } from "../test-utils";

describe("LoyaltyStepper", () => {
    const steps = [
        { member_type: "Regular", point: 0, completed: true },
        { member_type: "Silver", point: 100, completed: false },
        { member_type: "Gold", point: 500, completed: false },
    ];

    it("renders current member type and points", async () => {
        const { LoyaltyStepper } =
            await import("@/pages/private/dashboard/step");
        renderWithProviders(
            <LoyaltyStepper
                steps={steps}
                currentType="Regular"
                earnPoint={50}
            />,
        );
        expect(screen.getByText("Regular Member")).toBeInTheDocument();
        const fifties = screen.getAllByText("50");
        expect(fifties.length).toBeGreaterThanOrEqual(1);
    });

    it("renders all step member types", async () => {
        const { LoyaltyStepper } =
            await import("@/pages/private/dashboard/step");
        renderWithProviders(
            <LoyaltyStepper
                steps={steps}
                currentType="Regular"
                earnPoint={50}
            />,
        );
        const regulars = screen.getAllByText("Regular");
        expect(regulars.length).toBeGreaterThanOrEqual(1);
        const silvers = screen.getAllByText("Silver");
        expect(silvers.length).toBeGreaterThanOrEqual(1);
        expect(screen.getByText("Gold")).toBeInTheDocument();
    });

    it("shows progress percentage", async () => {
        const { LoyaltyStepper } =
            await import("@/pages/private/dashboard/step");
        renderWithProviders(
            <LoyaltyStepper
                steps={steps}
                currentType="Regular"
                earnPoint={50}
            />,
        );
        expect(screen.getByText("10%")).toBeInTheDocument();
    });

    it("shows points to next tier when not maxed", async () => {
        const { LoyaltyStepper } =
            await import("@/pages/private/dashboard/step");
        renderWithProviders(
            <LoyaltyStepper
                steps={steps}
                currentType="Regular"
                earnPoint={50}
            />,
        );
        expect(screen.getByText(/50 pts to Silver/i)).toBeInTheDocument();
    });

    it("shows completed steps with Regular prepended", async () => {
        const { LoyaltyStepper } =
            await import("@/pages/private/dashboard/step");
        const partialSteps = [
            { member_type: "Silver", point: 100, completed: false },
        ];
        renderWithProviders(
            <LoyaltyStepper
                steps={partialSteps}
                currentType="Regular"
                earnPoint={0}
            />,
        );
        const regulars = screen.getAllByText("Regular");
        expect(regulars.length).toBeGreaterThanOrEqual(1);
        const silvers = screen.getAllByText("Silver");
        expect(silvers.length).toBeGreaterThanOrEqual(1);
    });
});
