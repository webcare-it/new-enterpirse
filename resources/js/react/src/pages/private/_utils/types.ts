export interface IUserProfile {
    id: number;
    referred_by: string | null;
    provider_id: string | null;
    user_type: string;
    name: string;
    email: string;
    email_verified_at: string | null;
    verification_code: string | null;
    new_email_verificating_code: string | null;
    device_token: string | null;
    avatar: string | null;
    avatar_original: string | null;
    address: string | null;
    country: string | null;
    state: string | null;
    city: string | null;
    postal_code: string | null;
    phone: string;
    balance: number;
    banned: number;
    referral_code: string | null;
    customer_package_id: string | null;
    remaining_uploads: number;
    created_at: string;
    updated_at: string;
}

export interface IUserSummary {
    total_orders: number;
    pending_orders: number;
    in_progress: number;
    completed_orders: number;
    successful_deliveries: number;
    total_amount_spent: number;
    cart_items: number;
    success_rate: number;
    wishlist_items: number;
    earn_point: number;
}

export interface ILoyaltyStep {
    member_type: string;
    point: number;
    completed: boolean;
}

export interface IOrderListItem {
    id: number;
    code: string;
    grand_total: string;
    payment_status: string;
    delivery_status: string;
    created_at: string;
}

export interface IGetUserResponse {
    success: boolean;
    data: {
        user: IUserProfile;
        summary: IUserSummary;
        steps: ILoyaltyStep[];
        current_member_type: string;
        recent_orders: IOrderListItem[];
    };
}
