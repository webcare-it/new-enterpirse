import { MotionConfig } from "framer-motion";
import { useReducedMotion } from "@/hooks/useReducedMotion";

export const ReducedMotionProvider = ({ children }: { children: React.ReactNode }) => {
    const reducedMotion = useReducedMotion();
    return (
        <MotionConfig reducedMotion={reducedMotion ? "always" : "never"}>
            {children}
        </MotionConfig>
    );
};
