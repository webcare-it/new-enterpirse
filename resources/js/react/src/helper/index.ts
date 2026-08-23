import {
    BACKEND_URL,
    DEFAULT_MAX_AGE,
    TEMP_USER_ID,
    TOKEN,
    USER_ID,
} from "../constant";

export const getUUID = () => {
    return crypto.randomUUID();
};

export const getBaseUrl = () => BACKEND_URL;

export const getAuthUserId = () => {
    if (typeof window !== "undefined") {
        const userId = getCookie(USER_ID);
        if (userId) return userId;
        return null;
    }
    return null;
};

export const getTempUserId = () => {
    if (typeof window !== "undefined") {
        const userId = getCookie(TEMP_USER_ID);
        if (userId) return userId;
        return null;
    }
    return null;
};

export const getCookie = (name: string): string | undefined => {
    if (typeof document === "undefined") return undefined;

    const value = `; ${document.cookie}`;
    const parts = value?.split(`; ${name}=`);
    if (parts?.length === 2) {
        const cookieValue = parts?.pop()?.split(";")?.shift();
        return cookieValue;
    }
    return undefined;
};

export const setCookie = (
    name: string,
    value: string,
    maxAge: number = DEFAULT_MAX_AGE,
): void => {
    if (typeof document === "undefined") return;

    document.cookie = `${name}=${value}; path=/; max-age=${maxAge}`;
};

export const removeCookie = (name: string): void => {
    if (typeof document === "undefined") return;

    document.cookie = `${name}=; path=/; max-age=0`;
};

export const setLocalStorage = (key: string, value: string) => {
    if (typeof window !== "undefined") {
        localStorage.setItem(key, value);
    }
};

export const getLocalStorage = (key: string) => {
    if (typeof window !== "undefined") {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : null;
    }
};

export const removeLocalStorage = (key: string) => {
    if (typeof window !== "undefined") {
        localStorage.removeItem(key);
    }
};

export const renderVariation = (variation?: Record<string, string>): string => {
    if (!variation) return "";

    return Object.entries(variation)
        ?.filter(
            ([key, value]) =>
                key !== "sku" &&
                key !== "color" &&
                value != null &&
                value !== null &&
                value !== undefined &&
                String(value)?.trim() !== "",
        )
        ?.map(([, value]) => value!)
        ?.join(" • ");
};

export const convertJsonToObject = (data: string) => {
    try {
        return JSON.parse(data);
    } catch {
        return {};
    }
};

export const isValidEmail = (email: string): boolean => {
    const EMAIL_REGEX = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && EMAIL_REGEX.test(email)) return true;
    return false;
};

export const getYoutubeVideoId = (url: string): string | null => {
    try {
        return new URL(url).searchParams.get("v");
    } catch {
        return null;
    }
};

export const slugify = (text: string): string => {
    if (!text || text === null || text === undefined) {
        return "";
    }

    const isBangla = (text: string): boolean => {
        const banglaRegex = /[\u0980-\u09FF]/;
        return banglaRegex.test(text);
    };

    const cleanText = text.replace(/['".,!?;:()[\]{}]/g, "").trim();
    const extraClean = cleanText.replace(/\//g, "");

    const words = extraClean.split(/\s+/).filter((word) => word.length > 0);

    if (isBangla(cleanText)) return words.join("-");

    return words.map((word) => word.toLowerCase()).join("-");
};

export const renderStars = (rating: number) => {
    const full = Math.floor(rating);
    const hasHalf = rating % 1 >= 0.5;
    return (
        "★".repeat(full) +
        (hasHalf ? "½" : "") +
        "☆".repeat(5 - full - (hasHalf ? 1 : 0))
    );
};

export const getSplitName = (name: string) => {
    const words = name.split(" ");

    return {
        firstName: words?.[0],
        lastName: words?.slice(1)?.join(" "),
    };
};

export async function sha256(value: string): Promise<string> {
    const data = new TextEncoder().encode(value);

    const hashBuffer = await crypto.subtle.digest("SHA-256", data);

    return Array.from(new Uint8Array(hashBuffer))
        .map((byte) => byte.toString(16).padStart(2, "0"))
        .join("");
}

export const slugifyToTitle = (slug: string): string => {
    const isBangla = (text: string): boolean => {
        const banglaRegex = /[\u0980-\u09FF]/;
        return banglaRegex.test(text);
    };

    const words = slug.split("-").filter((word) => word.length > 0);

    if (words.length === 0) return "";

    if (isBangla(slug)) {
        return words
            .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
            .join(" ");
    }

    return words
        .map(
            (word) =>
                word.charAt(0).toUpperCase() + word.slice(1).toLowerCase(),
        )
        .join(" ");
};

export const htmlToPlainText = (htmlString: string): string => {
    const parser = new DOMParser();
    const doc = parser.parseFromString(htmlString, "text/html");
    return doc.body.textContent || doc.body.innerText || "";
};

export const truncateText = (text: string, maxLength: number = 30): string => {
    if (!text || text === null || text === undefined) {
        return "";
    }

    if (text?.length <= maxLength) {
        return text;
    }

    return text.substring(0, maxLength) + "...";
};

export const keyToValue = (key: string): string => {
    if (!key || key === null || key === undefined) {
        return "";
    }
    return key
        .replace(/_/g, " ")
        .replace(/\b\w/g, (char) => char.toUpperCase())
        .trim();
};

export const isAuthenticated = (): boolean => {
    if (typeof window !== "undefined") {
        const token = getCookie(TOKEN);
        return !!token;
    }
    return false;
};

const extractYouTubeVideoId = (url: string): string | null => {
    if (!url) return null;

    url = url.trim();

    const patterns = [
        /(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/,
        /(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/,
        /(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
        /(?:m\.youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/,
        /(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/,
        /^([a-zA-Z0-9_-]{11})$/,
    ];

    for (const pattern of patterns) {
        const match = url.match(pattern);
        if (match && match[1]) {
            return match[1];
        }
    }

    return null;
};

export const getYouTubeEmbedUrl = (url: string): string | null => {
    const videoId = extractYouTubeVideoId(url);
    if (!videoId) return null;

    return `https://www.youtube.com/embed/${videoId}`;
};

export const removeCurrencySymbol = (price: string): number => {
    if (!price || price === null || price === undefined) {
        return 0;
    }
    const cleanedPrice = price.replace(/[^\d.-]/g, "");
    return parseFloat(cleanedPrice) || 0;
};

export const isPathActive = (
    currentPath: string,
    menuPath: string,
): boolean => {
    if (currentPath === menuPath) return true;

    if (
        menuPath.startsWith("/categories/") &&
        currentPath.startsWith(menuPath + "/")
    ) {
        return true;
    }

    if (
        menuPath.startsWith("/dashboard/") &&
        currentPath.startsWith(menuPath + "/")
    ) {
        return true;
    }

    return false;
};
