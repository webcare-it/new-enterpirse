import { createContext, useContext } from "react";

export type ConfigData = Record<string, unknown> | null;

export const ConfigContext = createContext<ConfigData>(null);

export const useConfig = () => {
    const context = useContext(ConfigContext);
    if (context === null) {
        throw new Error("Server is not responding!");
    }

    return context;
};
