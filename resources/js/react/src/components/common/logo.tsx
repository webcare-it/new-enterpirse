import { useConfig } from "@/hooks/useConfig";
import { Link } from "react-router-dom";

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
