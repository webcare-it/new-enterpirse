import { useGetConfig } from "@/api/config";
import { ConfigContext } from "@/hooks/useConfig";
import { updatePrimaryColor, updatePrimaryForeground } from "@/lib/chroma";
import { RootPageLoading } from "@/pages/public/utils-pages/root-loading";
import { ServerErrorPage } from "@/pages/public/utils-pages/server";
import { useEffect } from "react";

export const ConfigProvider = ({ children }: { children: React.ReactNode }) => {
    const { data, isLoading, error } = useGetConfig();

    const config = (data?.data ?? {}) as Record<string, unknown>;

    const primaryColor = (config?.base_color as string) ?? "";
    const secondaryColor = (config?.base_hov_color as string) ?? "";

    useEffect(() => {
        if (primaryColor) {
            updatePrimaryColor(primaryColor);
        }
        if (secondaryColor) {
            updatePrimaryForeground(secondaryColor);
        }
    }, [primaryColor, secondaryColor]);

    return (
        <ConfigContext.Provider value={config}>
            {error ? (
                <ServerErrorPage />
            ) : isLoading ? (
                <RootPageLoading />
            ) : (
                children
            )}
        </ConfigContext.Provider>
    );
};
