import { cn } from "@/lib/utils";

interface ButtonGroupProps {
    children?: React.ReactNode;
    className?: string;
}

export const ButtonGroup = ({ children, className }: ButtonGroupProps) => {
    return (
        <div className={cn("flex items-center", className)}>
            {children}
        </div>
    );
};
