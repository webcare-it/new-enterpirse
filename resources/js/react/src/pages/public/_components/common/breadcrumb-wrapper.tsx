import { useConfig } from "@/hooks/useConfig";
import { ChevronRight, Home } from "lucide-react";
import { Link } from "react-router-dom";

interface IBreadcrumbItem {
    title: string;
    path?: string;
    items?: IBreadcrumbItem[];
}

interface BreadcrumbProps {
    title: string;
    breadcrumb: IBreadcrumbItem[];
    bgImg?: string;
}

export const BreadcrumbBackground = ({
    title,
    breadcrumb = [],
    bgImg,
}: BreadcrumbProps) => {
    const config = useConfig();
    const bg = config?.breadcrumb_image as string;
    const image =
        bgImg ||
        bg ||
        `https://img.magnific.com/free-photo/top-view-black-friday-sales-assortment-with-copy-space_23-2148665597.jpg`;

    const allBreadcrumbs = [{ title: "Home", path: "/" }, ...breadcrumb];

    const renderBreadcrumb = () => {
        const pathList: Array<{ title: string; path: string }> = [];

        allBreadcrumbs?.forEach((item) => {
            pathList.push({ title: item.title, path: item.path || "#" });

            if (item.items && item.items.length > 0) {
                item.items.forEach((subItem) => {
                    pathList.push({
                        title: subItem.title,
                        path: subItem.path || "#",
                    });
                });
            }
        });

        return (
            <nav
                className="flex items-center flex-wrap text-sm text-white/90"
                aria-label="Breadcrumb"
            >
                {pathList.map((item, index) => {
                    const isLast = index === pathList.length - 1;

                    return (
                        <div
                            className="flex items-center flex-wrap"
                            key={index}
                        >
                            {isLast || item.path === "#" ? (
                                <span className="font-medium flex items-center text-white/80 capitalize">
                                    {item.title}
                                </span>
                            ) : (
                                <Link
                                    to={item.path}
                                    className="hover:text-white transition-colors duration-200 font-medium flex items-center capitalize"
                                >
                                    {item.path === "/" && (
                                        <Home className="size-4 mr-1 text-white" />
                                    )}
                                    {item.title}
                                </Link>
                            )}

                            {!isLast && (
                                <div
                                    className="flex items-center mx-1"
                                    aria-hidden="true"
                                >
                                    <ChevronRight className="size-4 text-white" />
                                    <ChevronRight className="size-4 text-white -ml-2.5" />
                                </div>
                            )}
                        </div>
                    );
                })}
            </nav>
        );
    };

    return (
        <section
            className="relative w-full h-[180px] md:h-[220px] rounded-2xl flex items-center bg-cover bg-center bg-no-repeat overflow-hidden"
            style={{
                backgroundImage: `linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.55)), url(${image})`,
            }}
        >
            <div
                className="absolute inset-0 bg-cover bg-center bg-no-repeat"
                style={{ backgroundImage: `url(${image})` }}
            />

            <div className="relative mx-auto px-6 w-full">
                <h1 className="text-4xl uppercase md:text-7xl font-bold text-white tracking-tighter mb-4 text-center">
                    {title}
                </h1>
                <div className="mb-6 flex justify-center">
                    {renderBreadcrumb()}
                </div>
            </div>
            <div className="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/30 to-transparent" />
        </section>
    );
};
