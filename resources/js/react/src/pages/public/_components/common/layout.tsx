import { productLayoutConfig } from "@/data";
import { useProductLayout } from "@/hooks/useProductLayout";
import { useEffect, useState } from "react";

type ColumnConfig =
    (typeof productLayoutConfig)[keyof typeof productLayoutConfig];

const breakpoints = [
    { min: 1536, key: "ultrawide" },
    { min: 1280, key: "desktop" },
    { min: 1024, key: "laptop" },
    { min: 768, key: "tablet" },
    { min: 0, key: "mobile" },
] as const;

const useGridColumns = (config: ColumnConfig) => {
    const [columns, setColumns] = useState(config.mobile);

    useEffect(() => {
        const update = () => {
            const width = window.innerWidth;
            for (const { min, key } of breakpoints) {
                if (width >= min) {
                    setColumns(config[key]);
                    break;
                }
            }
        };
        update();
        window.addEventListener("resize", update);
        return () => window.removeEventListener("resize", update);
    }, [config]);

    return columns;
};

export const BlogLayout = ({
    children,
    className,
}: {
    children: React.ReactNode;
    className?: string;
}) => {
    return (
        <section
            className={`grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 ${className || ""}`}
        >
            {children}
        </section>
    );
};

export function ProductLayout({ children }: { children: React.ReactNode }) {
    const { fullLayout } = useProductLayout();
    const columns = useGridColumns(fullLayout);
    return (
        <section
            className="grid gap-2 md:gap-4"
            style={{
                gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))`,
            }}
        >
            {children}
        </section>
    );
}

export function ProductPageLayout({ children }: { children: React.ReactNode }) {
    const { withFilter } = useProductLayout();
    const columns = useGridColumns(withFilter);
    return (
        <section
            className="grid gap-2 md:gap-4"
            style={{
                gridTemplateColumns: `repeat(${columns}, minmax(0, 1fr))`,
            }}
        >
            {children}
        </section>
    );
}
