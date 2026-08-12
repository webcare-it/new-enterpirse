import { motion } from "framer-motion";
import {
    ShoppingBag,
    Clock,
    Loader2,
    CheckCircle2,
    Wallet,
    ShoppingCart,
    Heart,
    TrendingUp,
} from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { DashboardLayout } from "../_components/layout";
import { PageHeader } from "../_components/common/page-header";
import { StatCard } from "../_components/common/stat-card";
import { RecentOrders } from "./recent-orders";
import { Skeleton } from "@/components/common/skeleton";
import { usePrice } from "@/hooks/usePrice";
import { useGetUser } from "@/api/auth";
import type { IGetUserResponse, IOrderListItem } from "../_utils/types";
import { LoyaltyStepper } from "./step";

export const DashboardPage = () => {
    const { getPriceWithCurrency } = usePrice();
    const { data, isLoading } = useGetUser();

    const res = data as IGetUserResponse;
    const summary = res?.data?.summary || {};
    const steps = res?.data?.steps || [];
    const currentMemberType = res?.data?.current_member_type || "Regular";
    const earnPoint = summary?.earn_point ?? 0;
    const recentOrders = (res?.data?.recent_orders as IOrderListItem[]) || [];

    const hasSteps = Array.isArray(steps) && steps?.length > 0;

    const cards = summary
        ? [
              {
                  icon: ShoppingBag,
                  label: "Total Orders",
                  value: summary.total_orders,
                  accent: "text-primary",
                  bg: "bg-primary/10",
              },
              {
                  icon: Clock,
                  label: "Pending",
                  value: summary.pending_orders,
                  accent: "text-amber-500",
                  bg: "bg-amber-500/10",
              },
              {
                  icon: Loader2,
                  label: "In Progress",
                  value: summary.in_progress,
                  accent: "text-blue-500",
                  bg: "bg-blue-500/10",
              },
              {
                  icon: CheckCircle2,
                  label: "Completed",
                  value: summary.completed_orders,
                  accent: "text-green-500",
                  bg: "bg-green-500/10",
              },
              {
                  icon: Wallet,
                  label: "Total Spent",
                  value: getPriceWithCurrency(summary.total_amount_spent),
                  accent: "text-primary",
                  bg: "bg-primary/10",
              },
              {
                  icon: ShoppingCart,
                  label: "Cart Items",
                  value: summary.cart_items,
                  accent: "text-purple-500",
                  bg: "bg-purple-500/10",
              },
              {
                  icon: Heart,
                  label: "Wishlist",
                  value: summary.wishlist_items,
                  accent: "text-rose-500",
                  bg: "bg-rose-500/10",
              },
              {
                  icon: TrendingUp,
                  label: "Success Rate",
                  value: `${summary.success_rate}%`,
                  accent: "text-emerald-500",
                  bg: "bg-emerald-500/10",
              },
          ]
        : [];

    return (
        <>
            <SeoWrapper title="Dashboard" />
            <DashboardLayout>
                <PageHeader
                    title="Dashboard"
                    description="Welcome back! Here's an overview of your account activity."
                />

                {isLoading ? (
                    <Skeleton className="rounded-3xl mb-6" height="14rem" />
                ) : hasSteps ? (
                    <motion.div
                        initial={{ opacity: 0, y: 12 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.1, duration: 0.4 }}
                    >
                        <LoyaltyStepper
                            steps={steps}
                            currentType={currentMemberType}
                            earnPoint={earnPoint}
                        />
                    </motion.div>
                ) : null}

                {/* Stat Cards */}
                {isLoading ? (
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 my-6">
                        {Array.from({ length: 8 }).map((_, i) => (
                            <Skeleton
                                key={i}
                                className="rounded-2xl"
                                height="5rem"
                            />
                        ))}
                    </div>
                ) : (
                    <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                        {cards.map((card, i) => (
                            <StatCard
                                key={card.label}
                                icon={card.icon}
                                label={card.label}
                                value={card.value}
                                index={i}
                                accent={card.accent}
                                iconBg={card.bg}
                            />
                        ))}
                    </div>
                )}

                {/* Recent Orders */}
                {isLoading ? (
                    <Skeleton className="rounded-2xl" height="20rem" />
                ) : (
                    recentOrders?.length > 0 && (
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.2, duration: 0.3 }}
                        >
                            <RecentOrders orders={recentOrders} />
                        </motion.div>
                    )
                )}
            </DashboardLayout>
        </>
    );
};
