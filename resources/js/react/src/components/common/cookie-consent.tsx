import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Cookie } from "lucide-react";
import { useCookie } from "@/hooks/useCookie";

export const CookieConsent = () => {
    const { showCookieBanner, acceptCookies, declineCookies } = useCookie();

    if (!showCookieBanner) return null;

    return (
        <div className="fixed bottom-4 left-4 z-50 w-full max-w-xs sm:max-w-sm animate-in slide-in-from-bottom-2 duration-300">
            <Card className="bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm border shadow-lg max-h-[80vh] p-3 md:p-4 overflow-y-auto">
                <CardHeader className="px-0">
                    <div className="flex items-center gap-2">
                        <Cookie className="h-5 w-5 text-amber-600" />
                        <CardTitle className="text-base md:text-lg text-foreground font-semibold">
                            🍪 Amazing Cookies!
                        </CardTitle>
                    </div>
                    <CardDescription className="text-muted-foreground text-xs md:text-sm">
                        {
                            "We use cookies to enhance your experience and provide amazing features!"
                        }
                    </CardDescription>
                </CardHeader>

                <CardContent className="px-0">
                    <div className="space-y-3">
                        <p className="text-sm text-muted-foreground">
                            We use cookies to personalize content, analyze
                            traffic, and improve your shopping experience.
                        </p>
                        <div className="flex items-center justify-between gap-2">
                            <Button
                                onClick={declineCookies}
                                variant="outline"
                                className="flex-1"
                                size="sm"
                            >
                                Decline
                            </Button>
                            <Button
                                onClick={acceptCookies}
                                className="flex-1 bg-primary hover:bg-primary/90"
                                size="sm"
                            >
                                Accept
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    );
};
