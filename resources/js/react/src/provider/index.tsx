import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/scrollbar";
import "swiper/css/autoplay";
import "swiper/css/effect-fade";
import "nprogress/nprogress.css";
import React from "react";
import { BrowserRouter } from "react-router";
import { QueryClientProvider } from "@tanstack/react-query";
import { queryClient } from "../lib/tanstack";
import { HelmetProvider } from "react-helmet-async";
import { Toaster } from "react-hot-toast";
import { CartProvider } from "./cart";
import { ConfigProvider } from "./config";
// import { LenisProvider } from "./lenis";
import { GoogleGtmTracker } from "./google-gtm";
import { WishListProvider } from "./wishlist";
import { ReducedMotionProvider } from "./reduced-motion";

export const AppProvider = ({ children }: { children: React.ReactNode }) => {
    return (
        <HelmetProvider>
            <QueryClientProvider client={queryClient}>
                <BrowserRouter>
                    <ConfigProvider>
                        {/* <CookieProvider> */}
                        <Toaster />
                        <GoogleGtmTracker />
                        <ReducedMotionProvider>
                            {/* <LenisProvider> */}
                            <CartProvider>
                                <WishListProvider>{children}</WishListProvider>
                            </CartProvider>
                            {/* </LenisProvider> */}
                        </ReducedMotionProvider>
                        {/* <CookieConsent />
                        </CookieProvider> */}
                    </ConfigProvider>
                </BrowserRouter>
            </QueryClientProvider>
        </HelmetProvider>
    );
};
