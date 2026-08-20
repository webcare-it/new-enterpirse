import { motion } from "framer-motion";

export const ProgressBar = ({
    stock,
    sold,
    unit,
}: {
    stock: number;
    sold: number;
    unit: string;
}) => {
    const total = sold + stock;

    const percentage = total > 0 ? (sold / total) * 100 : 0;

    if (stock === 0) {
        return (
            <div className="flex items-center gap-2 my-4 md:my-5 text-red-600">
                <span className="size-4 bg-red-600 rounded-full" />
                Out of stock
            </div>
        );
    }

    return (
        <div className="space-y-3">
            {/* Progress */}
            <div className="relative h-2 overflow-hidden rounded-full bg-gray-100">
                <motion.div
                    initial={{ width: 0 }}
                    animate={{ width: `${percentage}%` }}
                    transition={{
                        duration: 1.2,
                        ease: "easeOut",
                    }}
                    className="relative h-full rounded-full bg-primary"
                >
                    {/* Moving Shine */}
                    <motion.div
                        animate={{
                            x: ["-120%", "250%"],
                        }}
                        transition={{
                            duration: 2,
                            repeat: Infinity,
                            ease: "linear",
                        }}
                        className="absolute inset-y-0 w-12 bg-white/40 blur-sm"
                    />
                </motion.div>
            </div>

            {/* Minimal Text */}
            <p className="text-sm text-gray-500">
                <span className="font-semibold text-gray-900">{sold}</span> sold
                • <span className="font-semibold text-gray-900">{stock}</span>{" "}
                {unit} left
            </p>
        </div>
    );
};
