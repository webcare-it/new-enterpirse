import {
    ShoppingCart,
    PackageCheck,
    Store,
    Navigation2,
    MapPin,
} from "lucide-react";
import type { ComponentType } from "react";

export type OrderStatus =
    | "pending"
    | "confirmed"
    | "picked_up"
    | "on_the_way"
    | "delivered"
    | "cancelled";

export interface StepDef {
    id: OrderStatus;
    label: string;
    icon: ComponentType<{ className?: string }>;
}

export interface IOderDetailsProduct {
    id: number;
    name: string;
    slug: string;
    price: number;
    quantity: number;
    image: string;
    variation: string;
}

export interface IShippingAddress {
    name: string;
    email: string;
    phone: string;
    address: string;
    notes: string;
}

export interface IOrderSummary {
    subtotal: number;
    discount: number;
    tax: number;
    shipping_cost: number;
    coupon_discount: number;
    total: number;
}

export interface IOrderDetails {
    id: number;
    code: string;
    date: string;
    status: OrderStatus;
    payment_status: string;
    payment_method: string;
    payment_method_title: string;
    shipping: IShippingAddress;
    summary: IOrderSummary;
    products: IOderDetailsProduct[];
}

export const orderSteps: StepDef[] = [
    { id: "pending", label: "Order Placed", icon: ShoppingCart },
    { id: "confirmed", label: "Confirmed", icon: PackageCheck },
    { id: "picked_up", label: "Picked Up", icon: Store },
    { id: "on_the_way", label: "On the Way", icon: Navigation2 },
    { id: "delivered", label: "Delivered", icon: MapPin },
];

export const formatDate = (date: string) =>
    new Date(date).toLocaleString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "2-digit",
        hour12: true,
    });

export const statusBadgeVariant: Record<
    string,
    "default" | "secondary" | "destructive" | "outline"
> = {
    paid: "default",
    unpaid: "destructive",
    pending: "secondary",
    confirmed: "default",
    picked_up: "default",
    on_the_way: "default",
    delivered: "default",
    cancelled: "destructive",
};
