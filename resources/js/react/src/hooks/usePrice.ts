import { useConfig } from "./useConfig";

interface ICurrencyConfig {
    currency: string;
    currency_symbol: string;
    is_currency_symbol: boolean;
    currency_position: string;
    is_decimal: boolean;
    decimal_digits: number;
}

export const usePrice = () => {
    const data = useConfig();
    const c = (data?.currency_setting as ICurrencyConfig) || {};

    const config: ICurrencyConfig = {
        currency: c?.currency || "BDT",
        currency_symbol: c?.currency_symbol || "৳",
        is_currency_symbol: c?.is_currency_symbol || true, // true | false
        currency_position: c?.currency_position || "before", // "before" | "after"
        is_decimal: c?.is_decimal || false, // true | false
        decimal_digits: c?.decimal_digits || 2,
    };

    const formatPrice = (price = 0) => {
        const n = Number(price);
        return config?.is_decimal
            ? n?.toFixed(config?.decimal_digits)
            : Math.round(n).toString();
    };

    const getPriceNumber = (price: number) => {
        return Number(formatPrice(price));
    };

    const getCurrencyFormate = (price: string) => {
        const currency = config?.is_currency_symbol
            ? config?.currency_symbol
            : config?.currency;

        return config?.currency_position === "before"
            ? `${currency}${price}`
            : `${price} ${currency}`;
    };

    const getPriceWithCurrency = (price: number) => {
        const formattedPrice = formatPrice(price);

        return getCurrencyFormate(formattedPrice);
    };

    const getHumanReadable = (price: number) => {
        const x = formatPrice(price);
        const a = getPriceNumber(Number(x))?.toLocaleString("en-US");
        return getCurrencyFormate(a);
    };

    const getCurrencySymbol = (): string => {
        return config?.currency_symbol;
    };
    const getCurrencyExtension = (): string => {
        return config?.currency;
    };

    return {
        getCurrencySymbol,
        getCurrencyExtension,
        getPriceNumber,
        getCurrencyFormate,
        getHumanReadable,
        getPriceWithCurrency,
    };
};
