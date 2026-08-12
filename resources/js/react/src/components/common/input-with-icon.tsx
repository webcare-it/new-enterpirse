import React from "react";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";

interface InputWithIconProps {
  id: string;
  name: string;
  label: string;
  placeholder: string;
  value: string;
  onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
  icon: React.ReactNode;
  type?: string;
  required?: boolean;
  className?: string;
}

export const InputWithIcon: React.FC<InputWithIconProps> = ({
  id,
  name,
  label,
  placeholder,
  value,
  onChange,
  icon,
  type = "text",
  required = false,
  className = "",
}) => {
  return (
    <div className="space-y-2">
      <Label htmlFor={id}>
        {label} {required && <span className="text-red-500">*</span>}
      </Label>
      <div className="relative">
        <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          {icon}
        </div>
        <Input
          id={id}
          name={name}
          type={type}
          placeholder={placeholder}
          value={value}
          onChange={onChange}
          className={cn("pl-10 w-full ", className)}
          required={required}
        />
      </div>
    </div>
  );
};
