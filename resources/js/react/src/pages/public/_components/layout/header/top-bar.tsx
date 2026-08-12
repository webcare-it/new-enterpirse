import { MapPin } from "lucide-react";
import { LayoutContainer } from "../base-layout";
import { Link } from "react-router-dom";

export const TopBar = () => {
    return (
        <div className="bg-primary text-primary-foreground">
            <LayoutContainer>
                <div className="flex justify-center md:justify-between items-center h-9 text-xs">
                    <span className="hidden md:flex items-center gap-1">
                        <MapPin className="size-4 mr-1 inline" />
                        <span className="font-medium">Dhaka 1230</span>
                    </span>
                    <span> Get offers on your first order</span>
                    <div className="hidden md:flex gap-6 items-center">
                        <a href="#">Sell on Store</a>
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
