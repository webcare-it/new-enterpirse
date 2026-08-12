import { motion } from "framer-motion";
import { Button } from "@/components/ui/button";
import { ArrowLeft, Home } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const NotFoundPage = () => {
    const handleGoBack = () => {
        window.history.back();
    };

    const handleGoHome = () => {
        window.location.href = "/";
    };

    return (
        <>
            <SeoWrapper
                title="404 - Page Not Found"
                description="The page you are looking for does not exist"
            />

            <div className="min-h-screen w-full bg-gray-200 flex items-center justify-center px-4">
                <div className="text-center max-w-md w-full space-y-8">
                    {/* Big 404 */}
                    <motion.h1
                        className="text-8xl md:text-9xl font-bold text-gray-800"
                        initial={{ opacity: 0, scale: 0.8 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.5 }}
                    >
                        404
                    </motion.h1>

                    {/* Message */}
                    <motion.div
                        className="space-y-3"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5, delay: 0.15 }}
                    >
                        <h2 className="text-2xl md:text-3xl font-semibold text-gray-800">
                            Page Not Found
                        </h2>
                        <p className="text-gray-800/80">
                            Oops! The page you're looking for doesn't exist or
                            has been moved.
                        </p>
                    </motion.div>

                    {/* Buttons */}
                    <motion.div
                        className="flex flex-col sm:flex-row items-center justify-center gap-4"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5, delay: 0.3 }}
                    >
                        <Button
                            onClick={handleGoBack}
                            size="lg"
                            className="w-full sm:w-auto"
                        >
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Go Back
                        </Button>

                        <Button
                            onClick={handleGoHome}
                            variant="outline"
                            size="lg"
                            className="w-full sm:w-auto"
                        >
                            <Home className="mr-2 h-4 w-4" />
                            Go Home
                        </Button>
                    </motion.div>
                </div>
            </div>
        </>
    );
};
