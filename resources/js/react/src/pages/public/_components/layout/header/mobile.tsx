import { useCart } from "@/hooks/useCart";
import { useState } from "react";
import { SearchDrawerMobile } from "./mobile-search";
import { CartIcon, CustomSearchIcon } from "../../common/icon";
import { UserComponent } from "./user";
import { HeaderLogo } from "@/components/common/logo";

export const MobileHeader = () => {
    const { setDrawerOpen, items } = useCart();
    const [isOpenSearchDrawer, setOpenSearchDrawer] = useState(false);

    return (
        <>
            <div className="md:hidden">
                <div className="border-b border-border bg-white px-4 py-1.5">
                    <div className="flex items-center justify-between gap-3">
                        <HeaderLogo />

                        <div className="flex items-center gap-3">
                            <button
                                aria-label="Search products"
                                className="relative text-gray-900 cursor-pointer hover:text-primary transition p-1.5"
                                onClick={() => setOpenSearchDrawer(true)}
                            >
                                <CustomSearchIcon />
                            </button>
                            <button
                                title="Cart"
                                aria-label={`Open cart${items?.length > 0 ? `, ${items.length} items` : ""}`}
                                onClick={() => setDrawerOpen(true)}
                                className="relative text-gray-900 hover:text-primary transition p-1.5 cursor-pointer"
                            >
                                <CartIcon />
                                {items?.length > 0 && (
                                    <span className="absolute -top-1 -right-0.5 bg-text-primary text-xs font-medium">
                                        {items.length}
                                    </span>
                                )}
                            </button>
                            <UserComponent type="header" />
                        </div>
                    </div>
                </div>
            </div>
            <SearchDrawerMobile
                drawerOpen={isOpenSearchDrawer}
                setDrawerOpen={setOpenSearchDrawer}
            />
        </>
    );
};
