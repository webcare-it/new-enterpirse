import { Link } from "react-router-dom";
import type { IOrderListItem } from "../_utils/types";
import { OrderList } from "../_components/common/order-list";

interface Props {
    orders: IOrderListItem[];
}

export const RecentOrders = ({ orders }: Props) => {
    return (
        <div className="overflow-x-auto rounded-2xl border bg-card">
            <div className="flex items-center justify-between px-5 py-4 border-b">
                <h2 className="font-semibold text-foreground">Recent Orders</h2>
                <Link
                    to="/dashboard/orders"
                    className="text-sm font-medium text-primary hover:underline"
                >
                    View All
                </Link>
            </div>
            <OrderList orders={orders} />
        </div>
    );
};
