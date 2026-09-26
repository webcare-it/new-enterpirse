import { SeoWrapper } from "@/components/common/seo-wrapper";
import { Button } from "@/components/ui/button";
import { RefreshCw, ServerCrash } from "lucide-react";
import { motion } from "framer-motion";
import { getBaseUrl } from "@/helper";
import { useState } from "react";

export const ServerErrorPage = () => {
    const [imageError, setImageError] = useState(false);

    const handleRefresh = () => {
        window.location.reload();
    };

    const imgUrl = `${getBaseUrl()}/assets/img/500-client.gif`;

    return (
        <>
            <SeoWrapper title="500" description="Server Error" />

            <div className="flex min-h-screen w-full flex-col items-center justify-center bg-background px-6 text-center">
                {!imageError ? (
                    <img
                        src={imgUrl}
                        alt="500 Server Error"
                        className="mb-6 max-h-[400px] w-auto max-w-full object-contain"
                        onError={() => setImageError(true)}
                    />
                ) : (
                    <div className="mb-6 flex h-32 w-32 items-center justify-center rounded-full bg-muted">
                        <ServerCrash className="h-16 w-16 text-muted-foreground" />
                    </div>
                )}

                <h1 className="text-4xl font-bold tracking-tight">500</h1>

                <h2 className="mt-2 text-xl font-semibold">
                    Something went wrong
                </h2>

                <p className="mt-2 max-w-md text-sm text-muted-foreground">
                    We’re having trouble processing your request. Please try
                    again in a moment.
                </p>

                <Button onClick={handleRefresh} size="lg" className="mt-8">
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
        </>
    );
};
