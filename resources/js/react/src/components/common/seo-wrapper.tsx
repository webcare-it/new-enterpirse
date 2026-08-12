import { useEffect, useState } from "react";
import { useConfig, type ConfigData } from "@/hooks/useConfig";
import { Helmet } from "react-helmet-async";

export const SeoWrapper = ({
    title,
    description,
    tags,
    image,
}: {
    title?: string;
    description?: string;
    tags?: string;
    image?: string;
}) => {
    const c = useConfig();
    const [config, setConfig] = useState<null | ConfigData>(null);

    useEffect(() => {
        if (c) {
            setConfig(c);
        }
    }, [c]);

    const [currentUrl, setCurrentUrl] = useState("");
    useEffect(() => {
        if (typeof window !== "undefined") {
            setCurrentUrl(window.location.href);
        }
    }, []);

    const favicon = (config?.site_icon as string) || "/favicon.ico";
    const siteName = (config?.website_name as string) || "Nittoz";
    const fallbackTitle = (config?.meta_title as string) || "Nittoz";
    const fallbackDescription = (config?.meta_description as string) || "";
    const metaImg = image || (config?.meta_image as string);
    const defaultKeywords =
        (config?.meta_keywords as string) ||
        "dropshipping, ecommerce, online store, suppliers, products";
    const siteMotto =
        (config?.site_motto as string) || "Best Dropshipping Platform";

    // Compute the final strings to present
    const finalTitle = `${title || fallbackTitle} | ${siteMotto}`;
    const finalDescription = description || fallbackDescription;
    const finalKeywords =
        tags && tags.length > 0
            ? tags
                  ?.split(",")
                  .map((tag) => tag.trim())
                  ?.join(", ")
            : defaultKeywords;

    return (
        <Helmet>
            {/* Structural Basics */}
            <title>{finalTitle}</title>
            {favicon && <link rel="icon" href={favicon} />}
            <meta name="description" content={finalDescription} />
            <meta name="keywords" content={finalKeywords} />

            {/* Open Graph (Facebook / WhatsApp / LinkedIn) */}
            <meta property="og:type" content="website" />
            <meta property="og:site_name" content={siteName} />
            <meta property="og:title" content={title || fallbackTitle} />
            <meta property="og:description" content={finalDescription} />
            <meta property="og:locale" content="en_US" />
            {currentUrl && <meta property="og:url" content={currentUrl} />}
            {metaImg && <meta property="og:image" content={metaImg} />}
            {metaImg && <meta property="og:image:width" content="1200" />}
            {metaImg && <meta property="og:image:height" content="630" />}
            {metaImg && <meta property="og:image:alt" content={title || fallbackTitle} />}

            {/* Twitter Meta Tags */}
            <meta name="twitter:card" content="summary_large_image" />
            <meta name="twitter:title" content={title || fallbackTitle} />
            <meta name="twitter:description" content={finalDescription} />
            {metaImg && <meta name="twitter:image" content={metaImg} />}
        </Helmet>
    );
};
