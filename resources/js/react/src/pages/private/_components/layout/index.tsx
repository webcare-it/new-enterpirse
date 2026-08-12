import {
    BaseLayout,
    LayoutContainer,
} from "@/pages/public/_components/layout/base-layout";
import { ProfileCard } from "./profile";
import { ProtectRoute } from "./protect";

export const DashboardLayout = ({
    children,
}: {
    children: React.ReactNode;
}) => {
    return (
        <ProtectRoute>
            <BaseLayout>
                <LayoutContainer>
                    <section className="mb-10 md:mb-20 mt-10 grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
                        <div className="hidden md:block col-span-1">
                            <div className="sticky top-32">
                                <ProfileCard />
                            </div>
                        </div>
                        <div className="md:col-span-3">{children}</div>
                    </section>
                </LayoutContainer>
            </BaseLayout>
        </ProtectRoute>
    );
};
