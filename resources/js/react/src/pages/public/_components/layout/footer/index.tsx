import {
    Mail,
    ChevronRight,
    Headphones,
    Facebook,
    Twitter,
    Instagram,
    Youtube,
    MapPin,
} from "lucide-react";
import { LayoutContainer } from "../base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { FooterLogo } from "@/components/common/logo";
import { Newsletter } from "./newsletter";
import { useConfig } from "@/hooks/useConfig";
import { Link } from "react-router-dom";

const linkData = {
    help: [
        { label: "FAQ", href: "/faqs" },
        { label: "Blogs", href: "/blogs" },
        { label: "About Us", href: "/about-us" },
        { label: "Contact Us", href: "/contact-us" },
    ],
    legal: [
        { label: "Privacy Policy", href: "/pages/privacy_policy" },
        { label: "Terms of Service", href: "/pages/terms_and_conditions" },
        { label: "Cookie Policy", href: "/pages/cookie_policy" },
        { label: "Return Policy", href: "/pages/return_policy" },
    ],
};

export function Footer() {
    const config = useConfig();
    return (
        <footer className="bg-gray-800 text-white relative mt-24">
            <LayoutContainer>
                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-7 gap-8 mb-4 pt-8 md:pt-12">
                    <div className="col-span-2 md:col-span-3 lg:col-span-2">
                        <FooterLogo />
                        <p className="text-sm my-5 leading-relaxed">
                            {(config?.about_us_description as string) ||
                                "Nittoz is a leading e-commerce platform that provides a wide range of products and services to customers around the world."}
                        </p>
                    </div>

                    {/* Link Columns */}
                    <LinkColumn title="Help" links={linkData.help} />
                    <LinkColumn title="Legal" links={linkData.legal} />
                    <Newsletter />
                    <div>
                        <h2 className="text-xs font-semibold text-white uppercase tracking-[0.15em] mb-5 relative inline-block">
                            Contact
                            <span className="absolute -bottom-1 left-0 w-8 h-[2px] bg-primary rounded-full transition-all duration-500 group-hover:w-full" />
                        </h2>
                        <div className="space-y-2 mb-2">
                            <p className="text-sm flex items-center gap-3 hover:text-primary transition-colors duration-500 group">
                                <Mail className="size-4" />

                                {(config?.email as string) ||
                                    "info@company.com"}
                            </p>
                            <p className="text-sm flex items-center gap-3 hover:text-primary transition-colors duration-500 group">
                                <Headphones className="size-4" />

                                {(config?.phone as string) || "+880123456789"}
                            </p>
                            <p className="text-sm flex items-center gap-3 hover:text-primary transition-colors duration-500 group">
                                <MapPin className="size-4" />

                                {(config?.address as string) ||
                                    "Dhaka, Bangladesh"}
                            </p>
                        </div>
                        <div className="flex gap-2">
                            {[
                                {
                                    icon: Facebook,
                                    label: "Facebook",
                                    color: "hover:text-blue-500",
                                    link:
                                        (config?.facebook_link as string) ||
                                        "https://www.facebook.com/",
                                },
                                {
                                    icon: Twitter,
                                    label: "Twitter",
                                    color: "hover:text-sky-400",
                                    link:
                                        (config?.twitter_link as string) ||
                                        "https://twitter.com/",
                                },
                                {
                                    icon: Instagram,
                                    label: "Instagram",
                                    color: "hover:text-pink-500",
                                    link:
                                        (config?.instagram_link as string) ||
                                        "https://www.instagram.com/",
                                },
                                {
                                    icon: Youtube,
                                    label: "YouTube",
                                    color: "hover:text-red-500",
                                    link:
                                        (config?.youtube_link as string) ||
                                        "https://www.youtube.com/",
                                },
                            ].map(({ icon: Icon, label, color, link }, idx) => (
                                <Link
                                    key={color}
                                    to={link}
                                    aria-label={label}
                                    className={`size-9 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center ${color} transition-all duration-500 hover:scale-110 hover:-translate-y-0.5`}
                                    style={{
                                        transitionDelay: `${idx * 50}ms`,
                                    }}
                                >
                                    <Icon className="size-4" />
                                </Link>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Bottom Bar */}
                <div className="mb-4 flex justify-center items-center">
                    <OptimizedImage
                        src={
                            (config?.payment_method_images as string) ||
                            "https://enterprise.droploo.com/uploads/all/YdpGVlKYYie2IvjFc3wGHX6eSsvlmpvYxIgWkS3k.png"
                        }
                        className="h-fit w-fit"
                    />
                </div>
                <div className="border-t border-gray-200 pt-6 pb-8 flex justify-center items-center flex-wrap gap-4">
                    <p className="text-sm text-white">
                        &copy; {new Date().getFullYear()}{" "}
                        {(config?.frontend_copyright_text as string) ||
                            "Team™. All Rights Reserved"}
                    </p>
                </div>
            </LayoutContainer>
        </footer>
    );
}

const LinkColumn = ({
    title,
    links,
}: {
    title: string;
    links: { label: string; href: string }[];
}) => (
    <div>
        <h2 className="text-xs font-semibold text-white uppercase tracking-[0.15em] mb-5 relative inline-block">
            {title}
            <span className="absolute -bottom-1 left-0 w-8 h-[2px] bg-primary rounded-full transition-all duration-500 group-hover:w-full" />
        </h2>
        <ul className="space-y-2.5">
            {links.map((item, idx) => (
                <li key={item.label}>
                    <Link
                        to={item.href}
                        className="text-sm hover:text-primary transition-all duration-500 flex items-center gap-1.5 group"
                        style={{ transitionDelay: `${idx * 30}ms` }}
                    >
                        <ChevronRight className="size-3 text-primary/0 -ml-4 group-hover:text-primary/70 group-hover:ml-0 transition-all duration-500" />
                        <span className="relative">
                            {item.label}
                            <span className="absolute left-0 -bottom-0.5 w-0 h-[1px] bg-primary/50 group-hover:w-full transition-all duration-500" />
                        </span>
                    </Link>
                </li>
            ))}
        </ul>
    </div>
);
