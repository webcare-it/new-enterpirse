import { motion } from "framer-motion";
import { X } from "lucide-react";
import type { ComponentType } from "react";
import { orderSteps } from "../../_utils/utils";

function Icon3D({
    icon: Icon,
    active,
    className,
}: {
    icon: ComponentType<{ className?: string }>;
    active?: boolean;
    className?: string;
}) {
    return (
        <div className="relative" style={{ perspective: "600px" }}>
            <motion.div
                className="relative transition-all duration-500"
                style={{ transformStyle: "preserve-3d" }}
                whileHover={{ rotateY: 15, rotateX: -8, scale: 1.1 }}
                animate={
                    active
                        ? {
                              rotateY: [0, 5, 0],
                              rotateX: [0, -3, 0],
                          }
                        : {}
                }
                transition={
                    active
                        ? { duration: 3, repeat: Infinity, ease: "easeInOut" }
                        : {}
                }
            >
                <div
                    className="absolute inset-0 rounded-full opacity-30"
                    style={{
                        background:
                            "linear-gradient(135deg, rgba(255,255,255,0.4) 0%, transparent 50%)",
                        transform: "translateZ(6px)",
                    }}
                />
                <Icon className={`relative ${className ?? ""}`} />
                <div
                    className="absolute inset-0 rounded-full"
                    style={{
                        boxShadow: "inset 0 -4px 6px rgba(0,0,0,0.15)",
                        transform: "translateZ(-2px)",
                    }}
                />
                {active && (
                    <div
                        className="absolute inset-0 rounded-full"
                        style={{
                            background:
                                "radial-gradient(circle at 30% 30%, rgba(255,255,255,0.2), transparent 70%)",
                            transform: "translateZ(4px)",
                        }}
                    />
                )}
            </motion.div>
        </div>
    );
}

export function CancelledState() {
    return (
        <motion.div
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.5, ease: "easeOut" }}
            className="flex items-center justify-center py-6"
        >
            <div className="flex flex-col items-center gap-3">
                <motion.div
                    initial={{ scale: 0 }}
                    animate={{ scale: 1 }}
                    transition={{
                        type: "spring",
                        stiffness: 200,
                        damping: 15,
                        delay: 0.3,
                    }}
                    className="relative"
                >
                    <motion.div
                        className="w-20 h-20 rounded-full bg-destructive text-white flex items-center justify-center shadow-lg"
                        style={{ perspective: "600px" }}
                        animate={{
                            boxShadow: [
                                "0 0 0 0 rgba(239,68,68,0.7)",
                                "0 0 0 10px rgba(239,68,68,0)",
                                "0 0 0 0 rgba(239,68,68,0)",
                            ],
                        }}
                        transition={{
                            duration: 2,
                            repeat: Infinity,
                            ease: "easeOut",
                        }}
                    >
                        <motion.div
                            initial={{ rotate: -180, opacity: 0 }}
                            animate={{ rotate: 0, opacity: 1 }}
                            transition={{
                                duration: 0.6,
                                delay: 0.4,
                                type: "spring",
                                stiffness: 200,
                            }}
                            style={{ transformStyle: "preserve-3d" }}
                            whileHover={{ rotateY: 15 }}
                        >
                            <X className="w-10 h-10 text-white" />
                        </motion.div>
                    </motion.div>
                </motion.div>
                <motion.p
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.5, delay: 0.5 }}
                    className="text-xl font-bold text-destructive"
                >
                    Order Cancelled
                </motion.p>
                <motion.p
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.5, delay: 0.6 }}
                    className="text-sm text-muted-foreground text-center max-w-sm"
                >
                    This order has been cancelled and cannot be processed
                    further.
                </motion.p>
            </div>
        </motion.div>
    );
}

export function OrderSteps({ currentStepIndex }: { currentStepIndex: number }) {
    return (
        <>
            <div className="hidden md:block relative">
                <div className="absolute top-12 left-[10%] right-[10%] h-0.5 bg-muted z-0" />
                <motion.div
                    className="absolute top-12 left-[10%] h-0.5 bg-primary z-10"
                    initial={{ width: 0 }}
                    animate={{
                        width:
                            currentStepIndex >= 0
                                ? `${(currentStepIndex / (orderSteps.length - 1)) * 80}%`
                                : "0%",
                    }}
                    transition={{ duration: 0.8, ease: "easeInOut" }}
                />
                <div className="relative z-20 grid grid-cols-5 gap-0">
                    {orderSteps.map((step, index) => {
                        const isActive = index <= currentStepIndex;
                        const isCurrent = index === currentStepIndex;

                        return (
                            <motion.div
                                key={step.id}
                                initial={{ opacity: 0, scale: 0.8 }}
                                animate={{ opacity: 1, scale: 1 }}
                                transition={{
                                    duration: 0.5,
                                    delay: index * 0.1,
                                }}
                                className="flex flex-col items-center"
                            >
                                <div
                                    className={`relative w-20 h-20 rounded-full flex items-center justify-center mb-3 transition-all duration-300 ${
                                        isActive
                                            ? "bg-primary text-primary-foreground shadow-lg scale-105"
                                            : "bg-muted text-muted-foreground"
                                    }`}
                                    style={
                                        isActive ? { perspective: "600px" } : {}
                                    }
                                >
                                    <Icon3D
                                        icon={step.icon}
                                        active={isActive}
                                        className={`w-8 h-8 ${isActive ? "text-white" : ""}`}
                                    />
                                    {isCurrent && (
                                        <motion.div
                                            className="absolute inset-0 rounded-full border-2 border-primary"
                                            animate={{
                                                scale: [1, 1.2, 1],
                                                opacity: [1, 0.5, 1],
                                            }}
                                            transition={{
                                                duration: 2,
                                                repeat: Infinity,
                                                ease: "easeInOut",
                                            }}
                                        />
                                    )}
                                </div>
                                <p
                                    className={`text-sm font-medium text-center ${
                                        isActive
                                            ? "text-foreground"
                                            : "text-muted-foreground"
                                    }`}
                                >
                                    {step.label}
                                </p>
                            </motion.div>
                        );
                    })}
                </div>
            </div>

            <div className="md:hidden relative pl-10">
                <div className="absolute left-10 top-8 bottom-8 w-0.5 bg-muted z-0" />
                <motion.div
                    className="absolute left-10 top-8 w-0.5 bg-primary z-10"
                    initial={{ height: 0 }}
                    animate={{
                        height:
                            currentStepIndex >= 0
                                ? `calc(${(currentStepIndex / (orderSteps.length - 1)) * 100}% - 2rem)`
                                : "0%",
                    }}
                    transition={{ duration: 0.8, ease: "easeInOut" }}
                />
                <div className="relative z-20 space-y-6">
                    {orderSteps.map((step, index) => {
                        const isActive = index <= currentStepIndex;
                        const isCurrent = index === currentStepIndex;

                        return (
                            <motion.div
                                key={step.id}
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{
                                    duration: 0.5,
                                    delay: index * 0.1,
                                }}
                                className="relative flex items-center gap-4 -ml-8"
                            >
                                <div
                                    className={`relative z-20 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 ${
                                        isActive
                                            ? "bg-primary text-primary-foreground shadow-lg"
                                            : "bg-muted text-muted-foreground"
                                    }`}
                                    style={
                                        isActive ? { perspective: "600px" } : {}
                                    }
                                >
                                    <Icon3D
                                        icon={step.icon}
                                        active={isActive}
                                        className={`w-6 h-6 ${isActive ? "text-white" : ""}`}
                                    />
                                    {isCurrent && (
                                        <motion.div
                                            className="absolute inset-0 rounded-full border-2 border-primary"
                                            animate={{
                                                scale: [1, 1.2, 1],
                                                opacity: [1, 0.5, 1],
                                            }}
                                            transition={{
                                                duration: 2,
                                                repeat: Infinity,
                                                ease: "easeInOut",
                                            }}
                                        />
                                    )}
                                </div>
                                <div className="flex-1">
                                    <p
                                        className={`text-sm font-semibold ${
                                            isActive
                                                ? "text-foreground"
                                                : "text-muted-foreground"
                                        }`}
                                    >
                                        {step.label}
                                    </p>
                                    {isCurrent && (
                                        <motion.p
                                            initial={{ opacity: 0 }}
                                            animate={{ opacity: 1 }}
                                            className="text-xs text-muted-foreground"
                                        >
                                            Current status
                                        </motion.p>
                                    )}
                                </div>
                            </motion.div>
                        );
                    })}
                </div>
            </div>
        </>
    );
}
