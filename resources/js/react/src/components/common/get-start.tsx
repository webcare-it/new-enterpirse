import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

export const GetStartButton = ({
    children,
    to = "/signup",
}: {
    children?: React.ReactNode;
    to?: string;
}) => {
    return (
        <motion.div
            className="text-center"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.6, delay: 1 }}
        >
            <Link to={to}>
                <motion.button
                    className="px-8 py-3 bg-primary cursor-pointer text-white font-semibold rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2"
                    whileHover={{ scale: 1.05 }}
                    whileTap={{ scale: 0.95 }}
                >
                    {children || "Get Started"}{" "}
                    <motion.div
                        animate={{ x: [2, -2, 2] }}
                        transition={{ duration: 1.5, repeat: Infinity }}
                        className="ml-2"
                    >
                        <ArrowRight className="h-5 w-5" />
                    </motion.div>
                </motion.button>
            </Link>
        </motion.div>
    );
};
