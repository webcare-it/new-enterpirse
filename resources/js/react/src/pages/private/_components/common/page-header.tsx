import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";

interface BreadcrumbItem {
    title: string;
    path?: string;
}

interface Props {
    title: string;
    description?: string;
    items?: BreadcrumbItem[];
    action?: React.ReactNode;
}

export const PageHeader = ({
    title,
    description,
    items = [],
    action,
}: Props) => {
    return (
        <div className="mb-6">
            <BreadcrumbWrapper
                type="dashboard"
                className="mb-3"
                items={items}
            />
            <div className="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <h1 className="text-2xl font-bold text-foreground">
                        {title}
                    </h1>
                    {description && (
                        <p className="text-sm text-muted-foreground mt-1">
                            {description}
                        </p>
                    )}
                </div>
                {action && <div>{action}</div>}
            </div>
        </div>
    );
};
