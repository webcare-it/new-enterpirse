import {
    Home,
    FileText,
    Heart,
    User,
    LogOutIcon,
    ShoppingCart,
    Camera,
    Truck,
} from "lucide-react";
import { cn } from "@/lib/utils";
import { Link, useLocation } from "react-router-dom";
import { useEffect, useRef, useState } from "react";
import { toast } from "react-hot-toast";
import { isPathActive } from "@/helper";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    ModalWrapper,
    type ModalWrapperRef,
} from "@/components/common/modal-wrapper";
import { SignOutModal } from "@/components/common/signout-modal";
import { Spinner } from "@/components/ui/spinner";
import { Skeleton } from "@/components/common/skeleton";
import {
    useGetUser,
    useLogoutMutation,
    useProfileUpdateMutation,
} from "@/api/auth";
import type { IGetUserResponse, IUserProfile } from "../../_utils/types";

interface Props {
    className?: string;
    width?: string;
}

export const ProfileCard = ({ className, width }: Props) => {
    const location = useLocation();
    const { isPending, mutate } = useLogoutMutation();
    const modalRef = useRef<ModalWrapperRef>(null);
    const { data, isLoading } = useGetUser();
    const user = (data as IGetUserResponse)?.data?.user || {};

    const handleSignOut = () => {
        mutate();
    };

    const menuItems = [
        {
            icon: Home,
            label: "Dashboard",
            href: "/dashboard",
            action: null,
        },
        {
            icon: User,
            label: "Manage Profile",
            href: "/dashboard/profile",
            action: null,
        },
        {
            icon: FileText,
            label: "Purchase History",
            href: "/dashboard/orders",
            action: null,
        },
        {
            icon: Truck,
            label: "Track Order",
            href: "/dashboard/track-order",
            action: null,
        },
        {
            icon: Heart,
            label: "My Wishlist",
            href: "/my-wishlist",
            action: null,
        },
        {
            icon: ShoppingCart,
            label: "My Cart",
            href: "/my-cart",
            action: null,
        },
        {
            icon: LogOutIcon,
            label: "Sign Out",
            href: null,
            action: () => modalRef.current?.open(),
        },
    ];

    return (
        <>
            <div
                className={cn("overflow-hidden rounded-3xl border", className)}
                style={{ width }}
            >
                <div className="bg-primary p-6 text-center">
                    <ProfilePicture user={user} type="card" />

                    {isLoading ? (
                        <>
                            <Skeleton
                                className="mx-auto mt-3 bg-primary-foreground/20"
                                width="8rem"
                                height="1.25rem"
                            />
                            <Skeleton
                                className="mx-auto mt-2 bg-primary-foreground/20"
                                width="10rem"
                                height="0.875rem"
                            />
                        </>
                    ) : (
                        <>
                            <h3 className="text-primary-foreground font-bold text-lg mb-1">
                                {user?.name}
                            </h3>

                            <p className="text-primary-foreground text-sm opacity-90">
                                {user?.email}
                            </p>
                        </>
                    )}
                </div>

                <div className="p-4">
                    <div className="space-y-1">
                        {menuItems?.map((item, index) => {
                            const IconComponent = item.icon;

                            if (item?.action && item?.href === null) {
                                return (
                                    <button
                                        key={index}
                                        onClick={item.action}
                                        className="flex hover:bg-destructive/10 cursor-pointer items-center space-x-3 px-3 py-2 rounded-md transition-colors duration-200 group w-full text-left disabled:opacity-50"
                                        disabled={isPending}
                                    >
                                        <IconComponent className="w-5 h-5  text-destructive  transition-colors duration-200" />
                                        <span className="text-destructive transition-colors duration-200 flex items-center gap-2">
                                            {item.label}
                                            {isPending && <Spinner />}
                                        </span>
                                    </button>
                                );
                            }
                            if (item?.href) {
                                return (
                                    <Link
                                        key={index}
                                        to={item.href}
                                        className={`flex items-center space-x-3 px-3 py-2 rounded-md hover:bg-muted transition-colors duration-200 group ${
                                            isPathActive(
                                                location.pathname,
                                                item.href,
                                            )
                                                ? "bg-primary/10"
                                                : ""
                                        }`}
                                    >
                                        <IconComponent
                                            className={`w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors duration-200 ${
                                                isPathActive(
                                                    location.pathname,
                                                    item.href,
                                                )
                                                    ? "text-primary"
                                                    : ""
                                            }`}
                                        />
                                        <span
                                            className={`text-foreground group-hover:text-primary transition-colors duration-200 ${
                                                isPathActive(
                                                    location.pathname,
                                                    item.href,
                                                )
                                                    ? "text-primary"
                                                    : ""
                                            }`}
                                        >
                                            {item.label}
                                        </span>
                                    </Link>
                                );
                            }
                        })}
                    </div>
                </div>
            </div>

            <ModalWrapper ref={modalRef} title="Sign Out" onHide={() => {}}>
                <SignOutModal
                    onSignOut={handleSignOut}
                    isPending={isPending}
                    onHideModal={() => modalRef.current?.close()}
                />
            </ModalWrapper>
        </>
    );
};

interface PProps {
    user: IUserProfile;
    type?: string;
}

export const ProfilePicture = ({ user, type }: PProps) => {
    const { mutate, isPending } = useProfileUpdateMutation();
    const [avatar, setAvatar] = useState(user?.avatar || "");
    const fileInputRef = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (user) {
            setAvatar(user?.avatar || "");
        }
    }, [user]);

    const handleAvatarChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        if (!file.type.startsWith("image/")) {
            toast.error("Please select an image file");
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            toast.error("File size must be less than 5MB");
            return;
        }

        const reader = new FileReader();

        reader.onload = () => {
            const image = reader.result as string;
            setAvatar(image);

            mutate({
                avatar: image,
            });
        };

        reader.readAsDataURL(file);
    };

    if (type) {
        return (
            <div className="flex justify-center items-center">
                <div className="size-16 relative rounded-full group ">
                    <Avatar className="size-16 border-2 border-primary-foreground/30">
                        <AvatarImage src={avatar} alt={user?.name} />
                        <AvatarFallback>
                            <User className="w-8 h-8 text-foreground" />
                        </AvatarFallback>
                    </Avatar>

                    <input
                        ref={fileInputRef}
                        type="file"
                        accept="image/*"
                        onChange={handleAvatarChange}
                        className="hidden"
                    />
                    <button
                        type="button"
                        onClick={() => fileInputRef.current?.click()}
                        className="z-10 absolute bottom-2 p-1 bg-accent rounded-full -right-3 cursor-pointer"
                    >
                        <Camera className="size-5 z-10 text-primary" />
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="relative shrink-0">
            <Avatar className="size-20 border">
                <AvatarImage src={avatar} />
                <AvatarFallback>U</AvatarFallback>
            </Avatar>

            <button
                type="button"
                disabled={isPending}
                onClick={() => fileInputRef.current?.click()}
                className="absolute -bottom-1 -right-1 rounded-full bg-primary p-1.5 text-primary-foreground transition hover:bg-primary/90"
            >
                <Camera className="size-4" />
            </button>

            <input
                ref={fileInputRef}
                type="file"
                accept="image/*"
                className="hidden"
                onChange={handleAvatarChange}
            />
        </div>
    );
};
