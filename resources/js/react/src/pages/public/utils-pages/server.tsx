import { SeoWrapper } from "@/components/common/seo-wrapper";
import { Button } from "@/components/ui/button";
import { RefreshCw } from "lucide-react";
import { motion } from "framer-motion";
import { getBaseUrl } from "@/helper";

export const ServerError = () => {
    const handleRefresh = () => {
        window.location.reload();
    };
    const img_url = `${getBaseUrl()}/assets/img/500-client.gif`;

    return (
        <>
            <SeoWrapper title="500" description="Server Error" />

            <div
                className="w-full h-screen bg-center bg-no-repeat bg-contain"
                style={{
                    backgroundImage: `url(${img_url})`,
                }}
            >
                <div className="flex h-screen justify-center items-center">
                    <Button
                        onClick={handleRefresh}
                        className="fixed bottom-10 translate-x-1/2"
                        size="lg"
                    >
                        <motion.div
                            animate={{ rotate: 360 }}
                            transition={{
                                duration: 2,
                                repeat: Infinity,
                                ease: "linear",
                            }}
                            className="mr-2"
                        >
                            <RefreshCw className="h-5 w-5" />
                        </motion.div>
                        Try Again
                    </Button>
                </div>
            </div>
        </>
    );
};
