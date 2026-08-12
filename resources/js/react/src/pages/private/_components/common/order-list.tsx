import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { Badge } from "@/components/ui/badge";
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from "@/components/ui/table";
import { usePrice } from "@/hooks/usePrice";
import { formatDate, statusBadgeVariant } from "@/pages/public/_utils/utils";
import type { IOrderListItem } from "../../_utils/types";
import { Button } from "@/components/ui/button";
import { Eye } from "lucide-react";

interface Props {
    orders: IOrderListItem[];
    children?: React.ReactNode;
}

export const OrderList = ({ orders, children }: Props) => {
    const { getHumanReadable } = usePrice();

    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Order</TableHead>
                    <TableHead>Date</TableHead>
                    <TableHead>Status</TableHead>
                    <TableHead>Payment</TableHead>
                    <TableHead>Total</TableHead>
                    <TableHead className="text-right">View</TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {orders?.map((order, i) => (
                    <motion.tr
                        key={order.id}
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: i * 0.05 }}
                        className="hover:bg-muted/50 border-b transition-colors"
                    >
                        <TableCell>
                            <Link
                                to={`/dashboard/orders/${order?.code}`}
                                className="font-medium text-primary hover:underline"
                            >
                                #{order?.code}
                            </Link>
                        </TableCell>
                        <TableCell className="text-muted-foreground">
                            {formatDate(order?.created_at)}
                        </TableCell>
                        <TableCell>
                            <Badge
                                variant={
                                    statusBadgeVariant[order?.delivery_status]
                                }
                                className="capitalize"
                            >
                                {order?.delivery_status.replace(/_/g, " ")}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            <Badge
                                variant={
                                    statusBadgeVariant[order?.payment_status]
                                }
                                className="capitalize"
                            >
                                {order?.payment_status}
                            </Badge>
                        </TableCell>
                        <TableCell>
                            {getHumanReadable(Number(order?.grand_total))}
                        </TableCell>
                        <TableCell className="text-right font-semibold text-foreground">
                            <Link to={`/dashboard/orders/${order?.code}`}>
                                <Button size="icon" variant="outline">
                                    <Eye />
                                </Button>
                            </Link>
                        </TableCell>
                    </motion.tr>
                ))}
            </TableBody>
            {children}
        </Table>
    );
};
