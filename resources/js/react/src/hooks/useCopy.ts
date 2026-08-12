import { useState } from "react";
import toast from "react-hot-toast";

export const useCopy = () => {
  const [isCopied, setIsCopied] = useState<boolean>(false);

  const handleCopy = (text: unknown, message?: string) => {
    if (!text) return;

    navigator.clipboard
      .writeText(text as string)
      .then(() => {
        setIsCopied(true);
        toast.success(message || "Copied to clipboard!");

        setTimeout(() => {
          setIsCopied(false);
        }, 2000);
      })
      .catch(() => {
        toast.error("Failed to copy");
      });
  };

  return { isCopied, handleCopy };
};
