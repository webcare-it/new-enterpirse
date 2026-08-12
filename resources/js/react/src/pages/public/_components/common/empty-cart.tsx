import { Button } from "@/components/ui/button";
import { motion } from "framer-motion";
import { ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

export const EmptyCart = ({ onClose }: { onClose?: () => void }) => {
    return (
        <div className="text-center px-4">
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                className="text-center py-20"
            >
                <div className="text-6xl md:text-8xl mb-4">🛍️</div>
                <h2 className="text-3xl font-bold text-gray-900 uppercase mb-2">
                    Your cart is empty
                </h2>
                <p className="text-muted-foreground mb-6">
                    Looks like you haven't added any items to your cart yet.
                </p>
                <Link to="/products">
                    <Button size="xl" onClick={onClose ? onClose : undefined}>
                        Continue Shopping <ArrowRight />
                    </Button>
                </Link>
            </motion.div>
        </div>
    );
};
