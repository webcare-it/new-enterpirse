import { lazy, Suspense, useEffect } from "react";
import { Routes, Route, useLocation } from "react-router";
import { RootPageLoading } from "./public/utils-pages/root-loading";

const HomePage = lazy(() =>
    import("./public/home").then((m) => ({ default: m.HomePage })),
);
const Products = lazy(() =>
    import("./public/products").then((m) => ({ default: m.ProductsPage })),
);
const Collections = lazy(() =>
    import("./public/collections").then((m) => ({
        default: m.CollectionsPage,
    })),
);
const CampaignPage = lazy(() =>
    import("./public/campaign").then((m) => ({
        default: m.CampaignPage,
    })),
);
const Search = lazy(() =>
    import("./public/search").then((m) => ({ default: m.ProductsSearchPage })),
);
const CategoryProducts = lazy(() =>
    import("./public/categories").then((m) => ({
        default: m.CategoryProductsPage,
    })),
);
const Checkout = lazy(() =>
    import("./public/checkout").then((m) => ({
        default: m.CheckoutPage,
    })),
);
const Success = lazy(() =>
    import("./public/success").then((m) => ({
        default: m.SuccessPage,
    })),
);
const OrderDetails = lazy(() =>
    import("./public/order-details").then((m) => ({
        default: m.OrderDetailsPage,
    })),
);
const TrackOrder = lazy(() =>
    import("./public/track-order").then((m) => ({
        default: m.TrackOrderPage,
    })),
);
const ProductDetail = lazy(() =>
    import("./public/product-details").then((m) => ({
        default: m.ProductDetailPage,
    })),
);
const MyCartPage = lazy(() =>
    import("./public/my-cart").then((m) => ({
        default: m.MyCartPage,
    })),
);
const MyWishlistPage = lazy(() =>
    import("./public/my-wishlist").then((m) => ({
        default: m.MyWishlistPage,
    })),
);
const ContactPage = lazy(() =>
    import("./public/contact").then((m) => ({
        default: m.ContactPage,
    })),
);
const AboutPage = lazy(() =>
    import("./public/about").then((m) => ({
        default: m.AboutPage,
    })),
);

const PolicyPage = lazy(() =>
    import("./public/policy").then((m) => ({
        default: m.PolicyPage,
    })),
);
const FaqsPage = lazy(() =>
    import("./public/faqs").then((m) => ({
        default: m.FaqsPage,
    })),
);

const SignInPage = lazy(() =>
    import("./public/auth/signin").then((m) => ({
        default: m.SignInPage,
    })),
);
const SignUpPage = lazy(() =>
    import("./public/auth/signup").then((m) => ({
        default: m.SignUpPage,
    })),
);
const RedirectPage = lazy(() =>
    import("./public/auth/redirect").then((m) => ({
        default: m.RedirectPage,
    })),
);

const ServerError = lazy(() =>
    import("./public/utils-pages/server").then((m) => ({
        default: m.ServerError,
    })),
);
const MaintenancePage = lazy(() =>
    import("./public/utils-pages/maintenance").then((m) => ({
        default: m.MaintenancePage,
    })),
);
const NotFoundPage = lazy(() =>
    import("./public/utils-pages/notfound").then((m) => ({
        default: m.NotFoundPage,
    })),
);

// Private
const Dashboard = lazy(() =>
    import("./private/dashboard").then((m) => ({
        default: m.DashboardPage,
    })),
);
const ProfilePage = lazy(() =>
    import("./private/profile").then((m) => ({
        default: m.ProfilePage,
    })),
);
const OrdersPage = lazy(() =>
    import("./private/orders").then((m) => ({
        default: m.OrdersPage,
    })),
);
const DashboardOrderDetails = lazy(() =>
    import("./private/orders/details").then((m) => ({
        default: m.OrderDetailsPage,
    })),
);
const DashboardTrackOrder = lazy(() =>
    import("./private/track-order").then((m) => ({
        default: m.TrackOrderPage,
    })),
);

export const AppRoutes = () => {
    return (
        <Suspense fallback={<RootPageLoading />}>
            <ScrollToTop>
                <Routes>
                    {/* Public Routes  */}
                    <Route path="/" element={<HomePage />} />
                    <Route path="/products" element={<Products />} />
                    <Route path="/my-cart" element={<MyCartPage />} />
                    <Route path="/checkout" element={<Checkout />} />
                    <Route path="/track-order" element={<TrackOrder />} />
                    <Route path="/checkout/success" element={<Success />} />
                    <Route path="/orders/:id" element={<OrderDetails />} />
                    <Route path="/my-wishlist" element={<MyWishlistPage />} />
                    <Route path="/products/:slug" element={<ProductDetail />} />
                    <Route
                        path="/collections/:slug"
                        element={<Collections />}
                    />
                    <Route path="/campaigns/:slug" element={<CampaignPage />} />
                    <Route path="/search" element={<Search />} />
                    <Route path="/contact-us" element={<ContactPage />} />
                    <Route path="/about-us" element={<AboutPage />} />
                    <Route path="/pages/:slug" element={<PolicyPage />} />
                    <Route path="/faqs" element={<FaqsPage />} />
                    <Route
                        path="/categories/:slug"
                        element={<CategoryProducts />}
                    />

                    {/*  Auth Routes */}
                    <Route path="/signin" element={<SignInPage />} />
                    <Route path="/signup" element={<SignUpPage />} />
                    <Route path="/redirect" element={<RedirectPage />} />

                    {/* Private Routes */}
                    <Route path="/dashboard" element={<Dashboard />} />
                    <Route
                        path="/dashboard/profile"
                        element={<ProfilePage />}
                    />
                    <Route path="/dashboard/orders" element={<OrdersPage />} />
                    <Route
                        path="/dashboard/orders/:id"
                        element={<DashboardOrderDetails />}
                    />
                    <Route
                        path="/dashboard/track-order"
                        element={<DashboardTrackOrder />}
                    />

                    {/** Other Routes */}
                    <Route path="/500" element={<ServerError />} />
                    <Route path="/maintenance" element={<MaintenancePage />} />
                    <Route path="*" element={<NotFoundPage />} />
                </Routes>
            </ScrollToTop>
        </Suspense>
    );
};

const ScrollToTop = ({ children }: { children: React.ReactNode }) => {
    const location = useLocation();
    useEffect(() => {
        window.scrollTo({ top: 0, left: 0, behavior: "smooth" });
    }, [location]);

    return <>{children}</>;
};
