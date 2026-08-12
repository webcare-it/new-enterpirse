import "@testing-library/jest-dom/vitest";

class MockResizeObserver {
    observe() {}
    unobserve() {}
    disconnect() {}
}
window.ResizeObserver = MockResizeObserver as unknown as typeof ResizeObserver;

class MockIntersectionObserver {
    readonly root: Element | Document | null = null;
    readonly rootMargin: string = "";
    readonly thresholds: ReadonlyArray<number> = [];
    constructor() {}
    observe() {}
    unobserve() {}
    disconnect() {}
    takeRecords(): IntersectionObserverEntry[] { return []; }
}
window.IntersectionObserver = MockIntersectionObserver as unknown as typeof IntersectionObserver;

Object.defineProperty(window, "scrollY", { value: 0, writable: true });

vi.mock("canvas-confetti", () => ({
    default: vi.fn(),
}));

vi.mock("react-hot-toast", () => ({
    toast: { error: vi.fn(), success: vi.fn() },
}));

vi.mock("@/api/coupon", () => ({
    useCouponApply: () => ({ mutate: vi.fn(), isPending: false }),
    useCouponRemove: () => ({ mutate: vi.fn(), isPending: false }),
}));


