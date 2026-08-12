import { Link } from "react-router-dom";
import { useLogoutMutation } from "@/api/auth";
import { CustomUserIcon } from "../../common/icon";
import { isAuthenticated } from "@/helper";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import { LayoutDashboard, LogOut, User2 } from "lucide-react";

export function UserComponent({ type }: { type: string }) {
    const { mutate, isPending } = useLogoutMutation();

    const handleLogout = () => mutate();

    if (type === "header")
        return isAuthenticated() ? (
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <button
                        aria-label="Account menu"
                        className="flex items-center gap-2 text-sm text-gray-900 cursor-pointer hover:text-primary transition"
                    >
                        <CustomUserIcon strokeWidth="1.8" />
                    </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent className="w-56" align="end">
                    <DropdownMenuLabel>My Account</DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <Link to="/dashboard/profile">
                        <DropdownMenuItem>
                            <User2 className="mr-2 size-4" />
                            Profile
                        </DropdownMenuItem>
                    </Link>
                    <Link to="/dashboard">
                        <DropdownMenuItem>
                            <LayoutDashboard className="mr-2 size-4" />
                            Dashboard
                        </DropdownMenuItem>
                    </Link>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem
                        onClick={handleLogout}
                        disabled={isPending}
                        className="text-red-600 bg-red-50 hover:text-red-700 transition-colors duration-200 focus:bg-red-50 focus:text-red-700 cursor-pointer"
                    >
                        <LogOut className="size-4 text-red-600" />
                        {isPending ? "loading..." : "Log out"}
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        ) : (
            <Link
                to="/signin"
                className="flex items-center gap-2 text-sm text-gray-900 cursor-pointer hover:text-primary transition"
            >
                <span className="text-sm font-medium hidden md:block">
                    Sign in
                </span>
                <CustomUserIcon strokeWidth="1.8" />
            </Link>
        );

    return null;
}
