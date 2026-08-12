import React from "react";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { usePhoneValidation } from "@/hooks/usePhoneValidation";
import { cn } from "@/lib/utils";
import { PhoneIcon } from "lucide-react";

interface Props {
  id: string;
  name: string;
  label: string;
  placeholder: string;
  value: string;
  onChange: (value: string) => void;
  required?: boolean;
  className?: string;
  onValidationChange?: (isValid: boolean) => void;
}

export const PhoneInput = ({
  id,
  name,
  label,
  placeholder,
  value,
  onChange,
  required = false,
  className = "",
  onValidationChange,
}: Props) => {
  const { touched, handlePhoneChange, handleBlur, isValid, error } =
    usePhoneValidation();

  React.useEffect(() => {
    handlePhoneChange(value);
  }, [value, handlePhoneChange]);

  React.useEffect(() => {
    onValidationChange?.(isValid);
  }, [isValid, onValidationChange]);

  return (
    <div className="space-y-2">
      <Label htmlFor={id}>
        {label} {required && <span className="text-red-500">*</span>}
      </Label>
      <div className="relative">
        <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <PhoneIcon className="text-gray-400 size-5" />
        </div>
        <Input
          id={id}
          name={name}
          type="text"
          placeholder={placeholder}
          value={value}
          onChange={(e) => onChange(e.target.value)}
          onBlur={handleBlur}
          className={cn(
            "pl-10 w-full h-11",
            className,
            touched &&
              value !== "" &&
              !isValid &&
              "border-red-500 focus:border-red-500",
            touched && value !== ""
          )}
          required={required}
        />
      </div>
      {touched && value !== "" && error && (
        <div className="text-sm text-red-500 -mt-1">{error}</div>
      )}
      {touched && value !== "" && isValid && (
        <div className="text-sm text-primary/70 -mt-1">
          ✓ Valid phone number
        </div>
      )}
    </div>
  );
};
