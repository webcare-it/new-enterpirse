import { motion } from "framer-motion";
import type { ComponentType } from "react";
import { Card, CardContent } from "@/components/ui/card";
import { cn } from "@/lib/utils";

interface Props {
    icon: ComponentType<{ className?: string }>;
    label: string;
    value: string | number;
    index?: number;
    accent?: string;
    iconBg?: string;
}

export const StatCard = ({
    icon: Icon,
    label,
    value,
    index = 0,
    accent = "text-primary",
    iconBg = "bg-primary/10",
}: Props) => {
    return (
        <motion.div
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: index * 0.05, duration: 0.3 }}
            whileHover={{ y: -2 }}
        >
            <Card className="rounded-2xl transition-shadow hover:shadow-md">
                <CardContent className="flex items-center gap-4 p-5">
                    <div
                        className={cn(
                            "flex items-center justify-center size-12 rounded-xl shrink-0",
                            iconBg,
                        )}
                    >
                        <Icon className={cn("size-6", accent)} />
                    </div>
                    <div className="min-w-0">
                        <p className="text-sm text-muted-foreground truncate">
                            {label}
                        </p>
                        <p className="text-xl font-bold text-foreground truncate">
                            {value}
                        </p>
                    </div>
                </CardContent>
            </Card>
        </motion.div>
    );
};
