import { screen } from "@testing-library/react";
import { renderWithProviders } from "../test-utils";

describe("FeatureHighlights", () => {
    it("renders all feature badges", async () => {
        const { FeatureHighlights } =
            await import("@/pages/public/home/badge-section");
        renderWithProviders(<FeatureHighlights />);
        expect(screen.getByText("Competitive Price")).toBeInTheDocument();
        expect(
            screen.getByText("Get The Best Prices Everyday"),
        ).toBeInTheDocument();
        expect(screen.getByText("Authentic Products")).toBeInTheDocument();
        expect(
            screen.getByText("Secured with Brand Warranty"),
        ).toBeInTheDocument();
        expect(screen.getByText("Easy & Secured Payment")).toBeInTheDocument();
        expect(
            screen.getByText("Pre-payment, Cash on Delivery"),
        ).toBeInTheDocument();
        expect(screen.getByText("Fast Delivery")).toBeInTheDocument();
        expect(
            screen.getByText("Rapid Delivery At Your Doorstep"),
        ).toBeInTheDocument();
        expect(screen.getByText("Instant Return")).toBeInTheDocument();
        expect(
            screen.getByText("100% Money Back Guarantee"),
        ).toBeInTheDocument();
    });
});
