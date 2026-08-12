import { motion } from "framer-motion";
import * as React from "react";
import { cn } from "@/lib/utils";

interface TypewriterTextProps {
  words: string[];
  className?: string;
  typingSpeed?: number;
  deletingSpeed?: number;
  pauseDuration?: number;
  cursorClassName?: string;
  joinWords?: boolean; // New prop to join all words into one sentence
}

const TypewriterText = React.forwardRef<HTMLSpanElement, TypewriterTextProps>(
  (
    {
      words,
      className,
      typingSpeed = 100,
      deletingSpeed = 50,
      pauseDuration = 1500,
      cursorClassName,
      joinWords = false, // Default to false for backward compatibility
    },
    ref
  ) => {
    const [currentWordIndex, setCurrentWordIndex] = React.useState(0);
    const [currentText, setCurrentText] = React.useState("");
    const [isDeleting, setIsDeleting] = React.useState(false);

    // Join all words into one sentence if joinWords is true
    const fullText = joinWords
      ? words.join(" ")
      : words[currentWordIndex] || "";

    React.useEffect(() => {
      const timeout = setTimeout(
        () => {
          if (!isDeleting) {
            if (currentText.length < fullText.length) {
              setCurrentText(fullText.slice(0, currentText.length + 1));
            } else {
              setTimeout(() => setIsDeleting(true), pauseDuration);
            }
          } else {
            if (currentText.length > 0) {
              setCurrentText(currentText.slice(0, -1));
            } else {
              setIsDeleting(false);
              if (joinWords) {
                setCurrentText("");
              } else {
                setCurrentWordIndex((prev) => (prev + 1) % words.length);
              }
            }
          }
        },
        isDeleting ? deletingSpeed : typingSpeed
      );

      return () => clearTimeout(timeout);
    }, [
      currentText,
      isDeleting,
      currentWordIndex,
      words,
      typingSpeed,
      deletingSpeed,
      pauseDuration,
      fullText,
      joinWords,
    ]);

    return (
      <span ref={ref} className={cn("inline-block", className)}>
        {currentText}
        <motion.span
          animate={{ opacity: [1, 0] }}
          transition={{
            duration: 0.5,
            repeat: Infinity,
            repeatType: "reverse",
          }}
          className={cn(
            "ml-0.5 inline-block h-[1em] w-[4px] bg-current align-middle text-primary",
            cursorClassName
          )}
        />
      </span>
    );
  }
);
TypewriterText.displayName = "TypewriterText";

export { TypewriterText };
