import { Link } from "react-router-dom";
import { CartIcon, TrackIcon } from "../../common/icon";
import { useCart } from "@/hooks/useCart";
import { HeaderLogo } from "@/components/common/logo";
import { DesktopSearch } from "./desktop-search";
import { UserComponent } from "./user";
import { useWishlist } from "@/hooks/useWishlist";
import { LayoutContainer } from "../base-layout";

export function DesktopHeader() {
    const { items: wishlist } = useWishlist();
    const { setDrawerOpen, items } = useCart();

    return (
        <div className="hidden md:block bg-white border-b border-border">
            {/* Main Header */}
            <LayoutContainer className="py-1 flex items-center justify-between gap-6">
                <HeaderLogo />

                {/* Search Bar */}
                <DesktopSearch />

                {/* Right Icons */}
                <div className="flex items-center gap-4">
                    <Link
                        to="/track-order"
                        aria-label="Track order"
                        className="relative text-gray-700 cursor-pointer hover:text-primary transition p-1.5"
                    >
                        <TrackIcon className="size-7" />
                    </Link>
                    <Link
                        to="/my-wishlist"
                        aria-label="View wishlist"
                        className="relative text-gray-900 cursor-pointer hover:text-primary transition p-1.5"
                    >
                        <svg
                            className="icon icon-wishlist icon-lg"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            strokeWidth="1.8"
                            width="24"
                            height="24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                            role="presentation"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"
                            />
                        </svg>
                        {wishlist?.length > 0 && (
                            <span className="absolute -top-1 -right-0.5 bg-text-primary text-xs font-medium w-4 h-4 flex items-center justify-center">
                                {wishlist?.length}
                            </span>
                        )}
                    </Link>
                    <button
                        onClick={() => setDrawerOpen(true)}
                        aria-label={`Open cart${items?.length > 0 ? `, ${items.length} items` : ""}`}
                        className="relative text-gray-900 hover:text-primary transition p-1.5 cursor-pointer"
                    >
                        <CartIcon className="size-6" />
                        {items?.length > 0 && (
                            <span className="absolute -top-1 -right-0.5 bg-text-primary text-xs font-medium w-4 h-4 flex items-center justify-center">
                                {items?.length}
                            </span>
                        )}
                    </button>
                    <UserComponent type="header" />
                </div>
            </LayoutContainer>
        </div>
    );
}
