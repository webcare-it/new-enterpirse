import { productLayoutConfig } from "@/data";
import { useConfig } from "./useConfig";

export const useProductLayout = () => {
    const config = useConfig();
    const layout = config?.layout_breakpoints as typeof productLayoutConfig;

    const settings = {
        homePage: {
            mobile:
                layout?.homePage?.mobile || productLayoutConfig.homePage.mobile,
            tablet:
                layout?.homePage?.tablet || productLayoutConfig.homePage.tablet,
            laptop:
                layout?.homePage?.laptop || productLayoutConfig.homePage.laptop,
            desktop:
                layout?.homePage?.desktop ||
                productLayoutConfig.homePage.desktop,
            ultrawide:
                layout?.homePage?.ultrawide ||
                productLayoutConfig.homePage.ultrawide,
        },
        withFilter: {
            mobile:
                layout?.withFilter?.mobile ||
                productLayoutConfig.withFilter.mobile,
            tablet:
                layout?.withFilter?.tablet ||
                productLayoutConfig.withFilter.tablet,
            laptop:
                layout?.withFilter?.laptop ||
                productLayoutConfig.withFilter.laptop,
            desktop:
                layout?.withFilter?.desktop ||
                productLayoutConfig.withFilter.desktop,
            ultrawide:
                layout?.withFilter?.ultrawide ||
                productLayoutConfig.withFilter.ultrawide,
        },
        fullLayout: {
            mobile:
                layout?.fullLayout?.mobile ||
                productLayoutConfig.fullLayout.mobile,
            tablet:
                layout?.fullLayout?.tablet ||
                productLayoutConfig.fullLayout.tablet,
            laptop:
                layout?.fullLayout?.laptop ||
                productLayoutConfig.fullLayout.laptop,
            desktop:
                layout?.fullLayout?.desktop ||
                productLayoutConfig.fullLayout.desktop,
            ultrawide:
                layout?.fullLayout?.ultrawide ||
                productLayoutConfig.fullLayout.ultrawide,
        },
    };
    return {
        homePage: settings.homePage,
        withFilter: settings.withFilter,
        fullLayout: settings.fullLayout,
    };
};
