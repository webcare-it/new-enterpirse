import React, { useState, useEffect } from "react";
import { CookieContext, type CookieContextType } from "@/hooks/useCookie";
import { toast } from "react-hot-toast";

interface CookieProviderProps {
  children: React.ReactNode;
}

export const CookieProvider: React.FC<CookieProviderProps> = ({ children }) => {
  const [showCookieBanner, setShowCookieBanner] = useState(false);
  const [cookiePreferences, setCookiePreferences] = useState({
    necessary: true,
    analytics: false,
    marketing: false,
  });

  useEffect(() => {
    const cookieConsent = localStorage.getItem("cookie-consent");
    if (!cookieConsent) {
      const timer = setTimeout(() => {
        setShowCookieBanner(true);
      }, 1000);
      return () => clearTimeout(timer);
    } else {
      const savedPreferences = JSON.parse(cookieConsent);
      setCookiePreferences(savedPreferences);
    }
  }, []);

  const acceptCookies = () => {
    const preferences = {
      necessary: true,
      analytics: true,
      marketing: true,
    };
    setCookiePreferences(preferences);
    localStorage.setItem("cookie-consent", JSON.stringify(preferences));
    toast.success("Cookies accepted");
    setShowCookieBanner(false);
  };

  const declineCookies = () => {
    const preferences = {
      necessary: true,
      analytics: false,
      marketing: false,
    };
    setCookiePreferences(preferences);
    localStorage.setItem("cookie-consent", JSON.stringify(preferences));
    toast.success("Cookies declined");
    setShowCookieBanner(false);
  };

  const updateCookiePreferences = (
    newPreferences: Partial<CookieContextType["cookiePreferences"]>
  ) => {
    const updatedPreferences = { ...cookiePreferences, ...newPreferences };
    setCookiePreferences(updatedPreferences);
    localStorage.setItem("cookie-consent", JSON.stringify(updatedPreferences));
  };

  const value: CookieContextType = {
    showCookieBanner,
    acceptCookies,
    declineCookies,
    cookiePreferences,
    updateCookiePreferences,
  };

  return (
    <CookieContext.Provider value={value}>{children}</CookieContext.Provider>
  );
};
