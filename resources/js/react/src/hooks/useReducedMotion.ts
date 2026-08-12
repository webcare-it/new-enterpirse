import { useMediaQuery } from "./useMediaQuery";

export const isFacebookWebView = () => {
    if (typeof navigator === "undefined") return false;
    const ua = navigator.userAgent;
    return /FBAN|FBAV|FB_IAB|Instagram|Messenger|LinkedInApp/.test(ua);
};

export const useReducedMotion = (): boolean => {
    const prefersReduced = useMediaQuery("(prefers-reduced-motion: reduce)");
    return prefersReduced || isFacebookWebView();
};
