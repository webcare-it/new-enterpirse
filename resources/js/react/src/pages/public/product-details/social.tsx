import { useState, useMemo } from "react";
import { Copy, Check, Share2 } from "lucide-react";

interface SocialShareProps {
    title: string;
    image?: string;
}

export function SocialShare({ title, image }: SocialShareProps) {
    const [copied, setCopied] = useState(false);

    const url = typeof window !== "undefined" ? window.location.href : "";

    const socials = useMemo(() => {
        const encodedUrl = encodeURIComponent(url);
        const encodedTitle = encodeURIComponent(title);
        const encodedImage = encodeURIComponent(image || "");

        return [
            {
                name: "Facebook",
                href: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
                hoverClass:
                    "hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2]",
                icon: (
                    <svg
                        viewBox="0 0 24 24"
                        className="size-4"
                        fill="currentColor"
                    >
                        <path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.88 3.77-3.88 1.09 0 2.23.2 2.23.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.77l-.44 2.89h-2.33v6.99A10 10 0 0 0 22 12z" />
                    </svg>
                ),
            },
            {
                name: "X",
                href: `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`,
                hoverClass:
                    "hover:bg-black hover:text-white hover:border-black dark:hover:border-neutral-800",
                icon: (
                    <svg
                        viewBox="0 0 24 24"
                        className="size-4"
                        fill="currentColor"
                    >
                        <path d="M18.9 2H22l-6.77 7.73L23 22h-6.07l-4.76-6.22L6.72 22H3.6l7.24-8.27L1 2h6.22l4.3 5.67L18.9 2z" />
                    </svg>
                ),
            },
            {
                name: "WhatsApp",
                href: `https://wa.me/?text=${encodedTitle}%20${encodedUrl}`,
                hoverClass:
                    "hover:bg-[#25D366] hover:text-white hover:border-[#25D366]",
                icon: (
                    <svg
                        viewBox="0 0 24 24"
                        className="size-4"
                        fill="currentColor"
                    >
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.746.953 3.71 1.455 5.704 1.456h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                ),
            },
            {
                name: "Pinterest",
                href: `https://pinterest.com/pin/create/button/?url=${encodedUrl}&media=${encodedImage}&description=${encodedTitle}`,
                hoverClass:
                    "hover:bg-[#E60023] hover:text-white hover:border-[#E60023]",
                icon: (
                    <svg
                        viewBox="0 0 24 24"
                        className="size-4"
                        fill="currentColor"
                    >
                        <path d="M12 2a10 10 0 0 0-3.6 19.3c0-.8 0-2 .2-2.8l1.3-5.5s-.3-.6-.3-1.5c0-1.4.8-2.5 1.9-2.5.9 0 1.3.7 1.3 1.5 0 .9-.6 2.3-.9 3.6-.3 1 .5 1.9 1.5 1.9 1.8 0 3.2-1.9 3.2-4.7 0-2.5-1.8-4.2-4.3-4.2-2.9 0-4.6 2.2-4.6 4.5 0 .9.3 1.8.8 2.3.1.1.1.2.1.4l-.3 1.1c0 .2-.2.3-.4.2-1.4-.6-2.2-2.3-2.2-4.2 0-3.4 2.5-6.5 7.2-6.5 3.8 0 6.7 2.7 6.7 6.3 0 3.8-2.4 6.8-5.7 6.8-1.1 0-2.2-.6-2.6-1.3l-.7 2.7c-.2.9-.8 2-1.2 2.6.9.3 1.8.5 2.8.5A10 10 0 1 0 12 2z" />
                    </svg>
                ),
            },
        ];
    }, [url, title, image]);

    const handleCopy = async () => {
        try {
            await navigator.clipboard.writeText(url);
            setCopied(true);
            setTimeout(() => setCopied(false), 2000);
        } catch (err) {
            console.error("Failed to copy link: ", err);
        }
    };

    return (
        <div className="flex items-center justify-between gap-3 border-t border-gray-100 pt-4 dark:border-neutral-800">
            {/* Left side label */}
            <div className="flex items-center gap-2 text-sm font-medium text-gray-600 dark:text-neutral-400">
                <Share2 className="size-4 stroke-[2.5]" />
                <span>Share</span>
            </div>

            {/* Interactive button layout */}
            <div className="flex flex-wrap items-center gap-2">
                {socials.map((social) => (
                    <a
                        key={social.name}
                        href={social.href}
                        target="_blank"
                        rel="noopener noreferrer"
                        title={`Share on ${social.name}`}
                        className={`flex size-9 items-center justify-center rounded-full border border-gray-200 text-gray-500 bg-white transition-all duration-200 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 ${social.hoverClass} hover:scale-105 active:scale-95`}
                    >
                        {social.icon}
                        <span className="sr-only">Share on {social.name}</span>
                    </a>
                ))}

                {/* Copy Link Button with State Feedback */}
                <button
                    title={copied ? "Link copied!" : "Copy link"}
                    onClick={handleCopy}
                    className={`flex h-9 items-center cursor-pointer justify-center gap-1.5 rounded-full border px-3 text-xs font-medium transition-all duration-200 hover:scale-105 active:scale-95 ${
                        copied
                            ? "border-green-500 bg-green-50 text-green-600 dark:bg-green-950/30 dark:text-green-400"
                            : "border-gray-200 bg-white text-gray-600 hover:border-gray-300 hover:bg-gray-50 dark:border-neutral-800 dark:bg-neutral-900 dark:text-neutral-400 dark:hover:bg-neutral-800"
                    }`}
                >
                    {copied ? (
                        <>
                            <Check className="size-3.5 stroke-[2.5]" />
                            <span>Copied!</span>
                        </>
                    ) : (
                        <>
                            <Copy className="size-3.5" />
                            <span>Copy Link</span>
                        </>
                    )}
                </button>
            </div>
        </div>
    );
}
