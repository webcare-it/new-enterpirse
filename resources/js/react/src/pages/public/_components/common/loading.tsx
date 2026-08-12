import { LoaderIcon } from "lucide-react";
import { cn } from "@/lib/utils";

export const Loading = () => (
    <LoaderIcon
        role="status"
        aria-label="Loading"
        className={cn("size-4 animate-spin")}
    />
);
