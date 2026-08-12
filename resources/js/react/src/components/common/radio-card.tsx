import React from "react";
import { RadioGroupItem } from "@/components/ui/radio-group";
import { Label } from "@/components/ui/label";

interface RadioCardProps {
    value: string;
    id: string;
    label: string;
    isSelected: boolean;
    icon?: React.ReactNode;
}

export const RadioCard: React.FC<RadioCardProps> = ({
    value,
    id,
    label,
    isSelected,
    icon,
}) => {
    return (
        <div
            className={`border rounded-lg flex items-center space-x-3 cursor-pointer transition-all ${
                isSelected
                    ? "border-primary bg-primary/5"
                    : "border-gray-200 hover:border-gray-300"
            }`}
        >
            <RadioGroupItem value={value} id={id} className="peer sr-only" />
            <Label
                htmlFor={id}
                className="text-sm md:text-base font-medium flex items-center gap-1.5 w-full cursor-pointer p-2.5 "
            >
                <div
                    className={`flex items-center justify-center w-5 h-5 rounded-full border ${
                        isSelected
                            ? "border-primary bg-primary"
                            : "border-gray-300"
                    }`}
                >
                    {isSelected && (
                        <div className="w-2 h-2 bg-white rounded-full"></div>
                    )}
                </div>
                {icon && (
                    <span className={`${isSelected ? "text-primary" : ""}`}>
                        {icon}
                    </span>
                )}
                <span className={`${isSelected ? "text-primary" : ""}`}>
                    {label}
                </span>
            </Label>
        </div>
    );
};
