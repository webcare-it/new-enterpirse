import { motion } from "framer-motion";
import { cn } from "@/lib/utils";
import type { ILoyaltyStep } from "../_utils/types";

const TIER_EMOJI: Record<string, string> = {
    Regular: "🥉",
    Star: "⭐",
    Gold: "👑",
    Diamond: "💎",
    Platinum: "💎",
    Silver: "🥈",
};

export const LoyaltyStepper = ({
    steps,
    currentType,
    earnPoint,
}: {
    steps: ILoyaltyStep[];
    currentType: string;
    earnPoint: number;
}) => {
    const maxPoint = steps[steps.length - 1]?.point ?? 1;
    const overallProgress = Math.min(
        Math.round((earnPoint / maxPoint) * 100),
        100,
    );

    const nextStepIndex = steps.findIndex((s) => !s.completed);
    const targetStep = nextStepIndex !== -1 ? steps[nextStepIndex] : null;
    const pointsToNext = targetStep
        ? Math.max(targetStep.point - earnPoint, 0)
        : 0;

    return (
        <div className="w-full rounded-3xl border bg-white dark:bg-zinc-950 shadow-sm overflow-hidden">
            <div className="p-6 md:p-8">
                {/* Header */}
                <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-10">
                    <div className="flex items-center gap-4">
                        <div className="relative">
                            <div className="size-16 md:size-18 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/25">
                                {TIER_EMOJI[currentType] ?? "🏆"}
                            </div>
                        </div>

                        <div>
                            <div className="flex items-center gap-2.5">
                                <h3 className="text-xl md:text-2xl font-bold text-zinc-900 dark:text-white">
                                    {currentType} Member
                                </h3>
                                <span className="text-[11px] font-semibold px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-950 dark:text-emerald-400 dark:border-emerald-900">
                                    LOYALTY
                                </span>
                            </div>
                            <p className="mt-1 flex items-baseline gap-1.5">
                                <span className="text-2xl md:text-3xl font-bold text-emerald-600 tabular-nums">
                                    {earnPoint.toLocaleString()}
                                </span>
                                <span className="text-sm text-zinc-500">
                                    points
                                </span>
                            </p>
                        </div>
                    </div>

                    {/* Progress Card */}
                    <div className="w-full lg:w-72">
                        <div className="flex items-center justify-between text-sm mb-2">
                            <span className="text-zinc-500 font-medium">
                                Overall Progress
                            </span>
                            <span className="font-semibold text-zinc-800 dark:text-zinc-200">
                                {overallProgress}%
                            </span>
                        </div>
                        <div className="h-2.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden">
                            <motion.div
                                initial={{ width: 0 }}
                                animate={{ width: `${overallProgress}%` }}
                                transition={{ duration: 1.1, ease: "easeOut" }}
                                className="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400"
                            />
                        </div>
                        <div className="flex justify-between mt-1.5 text-xs text-zinc-500">
                            <span>
                                {earnPoint.toLocaleString()} /{" "}
                                {maxPoint.toLocaleString()} pts
                            </span>
                            {pointsToNext > 0 && (
                                <span className="text-emerald-600 font-medium">
                                    {pointsToNext.toLocaleString()} pts left
                                </span>
                            )}
                        </div>
                    </div>
                </div>

                {/* Stepper */}
                <div className="relative">
                    {/* Progress Line */}
                    <div className="absolute top-7 left-[8%] right-[8%] h-[3px] bg-zinc-100 dark:bg-zinc-800 rounded-full z-0">
                        <motion.div
                            initial={{ width: 0 }}
                            animate={{ width: `${overallProgress}%` }}
                            transition={{ duration: 1.2, ease: "easeOut" }}
                            className="h-full rounded-full bg-gradient-to-r from-emerald-500 to-emerald-400"
                        />
                    </div>

                    <div className="relative flex justify-between z-10">
                        {steps.map((step, i) => {
                            const emoji = TIER_EMOJI[step.member_type] ?? "🏆";
                            const isCompleted =
                                step.completed || earnPoint >= step.point;
                            const isCurrent = step.member_type === currentType;
                            const isNext = i === nextStepIndex;

                            return (
                                <div
                                    key={step.member_type}
                                    className="flex flex-col items-center flex-1"
                                >
                                    <motion.div
                                        initial={{ scale: 0.7, opacity: 0 }}
                                        animate={{ scale: 1, opacity: 1 }}
                                        transition={{
                                            delay: i * 0.08,
                                            type: "spring",
                                            stiffness: 300,
                                        }}
                                        className={cn(
                                            "relative size-14 rounded-2xl flex items-center justify-center text-2xl transition-all duration-300",
                                            isCompleted
                                                ? "bg-gradient-to-br from-emerald-500 to-emerald-600 text-white shadow-md shadow-emerald-500/30"
                                                : isCurrent
                                                  ? "bg-white dark:bg-zinc-900 border-2 border-emerald-500 ring-4 ring-emerald-500/15"
                                                  : "bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 text-zinc-400",
                                        )}
                                    >
                                        {emoji}

                                        {(isCurrent || isNext) &&
                                            !isCompleted && (
                                                <motion.div
                                                    animate={{
                                                        opacity: [
                                                            0.4, 0.8, 0.4,
                                                        ],
                                                    }}
                                                    transition={{
                                                        duration: 2,
                                                        repeat: Infinity,
                                                    }}
                                                    className="absolute inset-0 rounded-2xl ring-2 ring-emerald-400/50"
                                                />
                                            )}
                                    </motion.div>

                                    <div className="mt-3 text-center">
                                        <p
                                            className={cn(
                                                "font-semibold text-sm",
                                                isCompleted || isCurrent
                                                    ? "text-emerald-700 dark:text-emerald-400"
                                                    : "text-zinc-500",
                                            )}
                                        >
                                            {step.member_type}
                                        </p>
                                        <p className="text-[11px] text-zinc-400 mt-0.5">
                                            {step.point.toLocaleString()} pts
                                        </p>
                                    </div>
                                </div>
                            );
                        })}
                    </div>
                </div>

                {/* Bottom Text */}
                {targetStep && pointsToNext > 0 && (
                    <motion.p
                        initial={{ opacity: 0, y: 6 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="text-center text-sm text-zinc-500 mt-8"
                    >
                        Earn{" "}
                        <span className="font-semibold text-emerald-600">
                            {pointsToNext.toLocaleString()}
                        </span>{" "}
                        more points to unlock{" "}
                        <span className="font-semibold text-zinc-800 dark:text-zinc-200">
                            {targetStep.member_type}
                        </span>
                    </motion.p>
                )}
            </div>
        </div>
    );
};
