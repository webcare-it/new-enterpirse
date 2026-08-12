import { useConfig } from "@/hooks/useConfig";
import { Link } from "react-router-dom";
import { useSidebar } from "../ui/sidebar";

export const HeaderLogo = () => {
    const config = useConfig();
    const logo = config?.header_logo as string;

    return (
        <Link to="/" className="flex items-center shrink-0">
            <div className="h-12 md:h-14 w-32 max-w-sm relative flex items-center justify-start overflow-hidden">
                <img
                    src={logo as string}
                    alt="Logo"
                    className="object-contain absolute"
                />
            </div>
        </Link>
    );
};
export const FooterLogo = () => {
    const config = useConfig();
    const logo = config?.footer_logo as string;

    return (
        <div className="h-12 md:h-14 w-32 relative flex items-center justify-start overflow-hidden">
            <img
                src={logo as string}
                alt="Logo"
                className="object-contain absolute"
            />
        </div>
    );
};

export const DashboardLogo = () => {
    const config = useConfig();
    const { open } = useSidebar();
    const logo = config?.header_logo as string;
    const shortLogo = config?.user_db_logo as string;

    return (
        <Link to="/" className="flex items-center shrink-0">
            {open ? (
                <div className="h-14 w-32 relative flex items-center justify-center">
                    <img
                        src={logo}
                        alt="Logo"
                        className="object-contain absolute h-full w-full"
                    />
                </div>
            ) : (
                <div className="size-14 pr-2 relative flex items-center justify-center overflow-hidden">
                    <img
                        src={shortLogo}
                        alt="Logo"
                        className="object-contain absolute size-[50px]"
                        onError={(e) => {
                            e.currentTarget.src = "/assets/img/droploo.png";
                        }}
                    />
                </div>
            )}
        </Link>
    );
};
