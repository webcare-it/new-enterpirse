import { useEffect, useState } from "react";
import { AnimatePresence, motion } from "framer-motion";

export const CampaignTimer = ({ endDate }: { endDate: string }) => {
    const [timeLeft, setTimeLeft] = useState({
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
    });

    useEffect(() => {
        const calculateTimeLeft = () => {
            const now = new Date().getTime();
            const end = new Date(endDate).getTime();
            const difference = end - now;

            if (difference <= 0) {
                return {
                    days: 0,
                    hours: 0,
                    minutes: 0,
                    seconds: 0,
                };
            }

            return {
                days: Math.floor(difference / (1000 * 60 * 60 * 24)),
                hours: Math.floor((difference / (1000 * 60 * 60)) % 24),
                minutes: Math.floor((difference / (1000 * 60)) % 60),
                seconds: Math.floor((difference / 1000) % 60),
            };
        };

        setTimeLeft(calculateTimeLeft());

        const timer = setInterval(() => {
            setTimeLeft(calculateTimeLeft());
        }, 1000);

        return () => clearInterval(timer);
    }, [endDate]);

    const formatNumber = (num: number) => String(num).padStart(2, "0");

    return (
        <div className="w-full -mt-10 md:-mt-12 px-2 sm:px-4 pb-4">
            <div className="w-full flex items-start justify-center gap-1 sm:gap-2 md:gap-3">
                <TimeBlock value={formatNumber(timeLeft.days)} label="DAYS" />

                <TimeSeparator />

                <TimeBlock value={formatNumber(timeLeft.hours)} label="HOURS" />

                <TimeSeparator />

                <TimeBlock
                    value={formatNumber(timeLeft.minutes)}
                    label="MINUTES"
                />

                <TimeSeparator />

                <TimeBlock
                    value={formatNumber(timeLeft.seconds)}
                    label="SECONDS"
                />
            </div>
        </div>
    );
};

const TimeSeparator = () => {
    return (
        <span
            className="
                text-primary
                font-bold
                text-lg
                sm:text-xl
                md:text-2xl
                leading-none
                mt-2
                sm:mt-3
                md:mt-4
                shrink-0
            "
        >
            :
        </span>
    );
};

const TimeBlock = ({ value, label }: { value: string; label: string }) => {
    return (
        <div className="flex flex-col items-center gap-1 sm:gap-1.5 md:gap-2 min-w-0">
            <div className="flex gap-0.5 sm:gap-1">
                {value.split("").map((digit, index) => (
                    <DigitCard key={`${label}-${index}`} digit={digit} />
                ))}
            </div>

            <span
                className="
                    text-gray-700
                    font-semibold
                    uppercase
                    opacity-95
                    whitespace-nowrap
                    text-[7px]
                    sm:text-[9px]
                    md:text-[13px]
                    tracking-[0.5px]
                    sm:tracking-[1px]
                    md:tracking-[1.5px]
                "
            >
                {label}
            </span>
        </div>
    );
};

const DigitCard = ({ digit }: { digit: string }) => {
    return (
        <div
            className="
                relative
                w-[clamp(28px,8vw,52px)]
                h-[clamp(38px,11vw,64px)]
            "
            style={{
                perspective: "500px",
            }}
        >
            <AnimatePresence mode="sync" initial={false}>
                <motion.div
                    key={digit}
                    initial={{
                        rotateX: -90,
                    }}
                    animate={{
                        rotateX: 0,
                    }}
                    exit={{
                        rotateX: 90,
                    }}
                    transition={{
                        duration: 0.35,
                        ease: [0.4, 0, 0.2, 1],
                    }}
                    className="
                        absolute
                        inset-0
                        bg-primary/90
                        text-primary-foreground
                        font-bold
                        flex
                        items-center
                        justify-center
                        rounded-md
                        sm:rounded-lg
                        origin-center
                        overflow-hidden
                        text-[22px]
                        sm:text-[30px]
                        md:text-[42px]
                    "
                    style={{
                        transformStyle: "preserve-3d",
                        backfaceVisibility: "hidden",
                    }}
                >
                    {digit}
                </motion.div>
            </AnimatePresence>
        </div>
    );
};
