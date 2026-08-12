import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const RootPageLoading = () => {
    return (
        <>
            <SeoWrapper title="Loading" description="Loading..." />
            <div className="flex h-screen w-full items-center justify-center bg-background">
                <Loading />
            </div>
        </>
    );
};
