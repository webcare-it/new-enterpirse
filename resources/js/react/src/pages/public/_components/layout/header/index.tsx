import { DesktopHeader } from "./desktop";
import { MobileHeader } from "./mobile";
import { MobileBottom } from "./mobile-bottom";
import { useScrollHide } from "@/hooks/useScrollHide";

export const Header = () => {
    const isHidden = useScrollHide(80);

    return (
        <>
            <header
                className={`bg-background sticky top-0 z-50 transition-transform duration-300 md:translate-y-0 ${
                    isHidden ? "-translate-y-full" : "translate-y-0"
                }`}
            >
                <DesktopHeader />
                <MobileHeader />
            </header>

            <MobileBottom />
        </>
    );
};
