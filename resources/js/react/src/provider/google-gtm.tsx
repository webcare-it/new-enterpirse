import { useCallback, useEffect, useRef } from "react";
import { useLocation, useSearchParams } from "react-router-dom";
import { useConfig } from "@/hooks/useConfig";

export const GoogleGtmTracker = () => {
    return <GtmTracker />;
};

const GtmTracker = () => {
    const config = useConfig();
    const location = useLocation();
    const scriptLoadedRef = useRef(false);
    const noscriptLoadedRef = useRef(false);
    const [searchParams] = useSearchParams();
    const gtmId = config?.gtm_id as string;

    const GTM_ID = useCallback(() => gtmId || "GTM-WFJ36NN9", [gtmId]);

    useEffect(() => {
        const gtmIdValue = GTM_ID();

        if (scriptLoadedRef.current) return;

        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
            "gtm.start": new Date().getTime(),
            event: "gtm.js",
        });

        const loadGtmScript = () => {
            if (scriptLoadedRef.current) return;
            scriptLoadedRef.current = true;
            removeDeferralListeners();

            const f = document.getElementsByTagName("script")[0];
            const j = document.createElement("script");
            j.async = true;
            j.src = `https://www.googletagmanager.com/gtm.js?id=${gtmIdValue}`;
            if (f?.parentNode) {
                f.parentNode.insertBefore(j, f);
            }
        };

        const deferralEvents: (keyof WindowEventMap)[] = [
            "pointerdown",
            "keydown",
            "touchstart",
            "scroll",
        ];
        const removeDeferralListeners = () => {
            deferralEvents.forEach((evt) =>
                window.removeEventListener(evt, loadGtmScript),
            );
        };
        deferralEvents.forEach((evt) =>
            window.addEventListener(evt, loadGtmScript, {
                once: true,
                passive: true,
            }),
        );

        const usesIdleCallback = "requestIdleCallback" in window;
        const idleHandle = usesIdleCallback
            ? window.requestIdleCallback(loadGtmScript, { timeout: 4000 })
            : window.setTimeout(loadGtmScript, 3000);

        return () => {
            removeDeferralListeners();
            if (usesIdleCallback) {
                window.cancelIdleCallback(idleHandle);
            } else {
                window.clearTimeout(idleHandle);
            }

            const existingScript = document.querySelector(
                `script[src*="googletagmanager.com/gtm.js"]`,
            );
            if (existingScript) {
                existingScript.remove();
            }
            scriptLoadedRef.current = false;
        };
    }, [GTM_ID]);

    useEffect(() => {
        const gtmIdValue = GTM_ID();

        if (noscriptLoadedRef.current) return;

        const existingNoscript = document.querySelector(
            `noscript iframe[src*="googletagmanager.com/ns.html"]`,
        );
        if (existingNoscript) {
            noscriptLoadedRef.current = true;
            return;
        }

        const noscript = document.createElement("noscript");
        const iframe = document.createElement("iframe");
        iframe.src = `https://www.googletagmanager.com/ns.html?id=${gtmIdValue}`;
        iframe.height = "0";
        iframe.width = "0";
        iframe.style.display = "none";
        iframe.style.visibility = "hidden";
        noscript.appendChild(iframe);
        document.body.insertBefore(noscript, document.body.firstChild);

        noscriptLoadedRef.current = true;

        return () => {
            const existingNoscript = document.querySelector(
                `noscript iframe[src*="googletagmanager.com/ns.html"]`,
            )?.parentElement;
            if (existingNoscript && existingNoscript.parentNode) {
                existingNoscript.parentNode.removeChild(existingNoscript);
            }
            noscriptLoadedRef.current = false;
        };
    }, [GTM_ID]);

    useEffect(() => {
        if (window.dataLayer) {
            window.dataLayer.push({
                event: "page_view",
                page_path: location.pathname + location.search,
                page_title: document.title,
            });
        }
    }, [location.pathname, location.search, searchParams]);

    return null;
};
