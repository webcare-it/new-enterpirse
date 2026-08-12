import { useSearchParams } from "react-router-dom";
import { Loading } from "../_components/common/loading";
import { setCookie } from "@/helper";
import { TOKEN, USER_ID } from "@/constant";
import { useEffect } from "react";
import { sessionRemove } from "@/api/auth";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const RedirectPage = () => {
    sessionRemove();
    const [searchParams] = useSearchParams();
    const token = searchParams.get("token");
    const user_id = searchParams.get("user_id");

    useEffect(() => {
        if (token && user_id) {
            setCookie(TOKEN, token);
            setCookie(USER_ID, user_id);
            window.location.href = "/";
        }
    }, [token, user_id]);

    return (
        <>
            <SeoWrapper
                title="Redirecting"
                description="Authentication redirect"
            />
            <div className="flex justify-center items-center h-screen">
                <h1 className="text-2xl font-bold">Redirecting...</h1>
                <Loading />
            </div>
        </>
    );
};
