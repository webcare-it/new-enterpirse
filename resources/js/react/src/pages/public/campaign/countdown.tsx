import type { ICampaign } from "@/type";
import { useCallback, useEffect, useState } from "react";

interface Props {
    campaign: ICampaign;
}

interface TimeLeft {
    days: number;
    hours: number;
    minutes: number;
    seconds: number;
}

export function CampaignCountdown({ campaign }: Props) {
    const calculateTimeLeft = useCallback((): TimeLeft => {
        const startDate = campaign?.start_date || "";
        const endDate = campaign?.end_date || "";
        const now = new Date().getTime();
        const start = new Date(startDate).getTime();
        const end = new Date(endDate).getTime();

        if (now < start) {
            return {
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
            };
        }

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
    }, [campaign]);

    const [timeLeft, setTimeLeft] = useState<TimeLeft>(calculateTimeLeft());

    useEffect(() => {
        const interval = setInterval(() => {
            setTimeLeft(calculateTimeLeft());
        }, 1000);

        return () => clearInterval(interval);
    }, [calculateTimeLeft]);

    const Item = ({ value, label }: { value: number; label: string }) => (
        <div className="flex flex-col items-center rounded-lg md:rounded-xl bg-white border-red-600 shadow-md border px-3 md:px-4 py-2 md:py-3 mb-4">
            <span className="text-xl md:text-4xl font-bold text-red-600">
                {String(value)?.padStart(2, "0")}
            </span>
            <span className="mt-1 text-xs md:text-sm text-gray-800 uppercase">
                {label}
            </span>
        </div>
    );

    return (
        <div className="grid grid-cols-4 gap-3 md:gap-4">
            <Item value={timeLeft.days} label="Days" />
            <Item value={timeLeft.hours} label="Hours" />
            <Item value={timeLeft.minutes} label="Minutes" />
            <Item value={timeLeft.seconds} label="Seconds" />
        </div>
    );
}
