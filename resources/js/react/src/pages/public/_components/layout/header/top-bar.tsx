import { Phone } from "lucide-react";
import { LayoutContainer } from "../base-layout";
import { Link } from "react-router-dom";
import { useConfig } from "@/hooks/useConfig";

export const TopBar = () => {
    const config = useConfig();
    const topBarOffer = config?.top_bar_offer as string;
    const helplineNumber = config?.helpline_number as string;
    return (
        <div className="bg-primary text-primary-foreground">
            <LayoutContainer>
                <div className="flex justify-between items-center h-9 text-xs">
                    <span className="hidden md:flex items-center gap-1">
                        <Phone className="size-4 mr-1 inline" />
                        <span className="font-medium">{helplineNumber}</span>
                    </span>
                    <span>{topBarOffer}</span>
                    <div className="flex gap-6 items-center">
                        <Link to="/track-order">Track Order</Link>
                        <Link className="hidden md:block" to="/contact-us">
                            Help
                        </Link>
                    </div>
                </div>
            </LayoutContainer>
        </div>
    );
};
