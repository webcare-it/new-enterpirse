import { useEffect, useState } from "react";
import { motion } from "framer-motion";
import { Loader2, Save, ShieldCheck, UserCog } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { DashboardLayout } from "../_components/layout";
import { PageHeader } from "../_components/common/page-header";
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { PasswordInput } from "@/components/common/password-input";
import { toast } from "react-hot-toast";
import {
    useChangePasswordMutation,
    useGetUser,
    useProfileUpdateMutation,
} from "@/api/auth";
import type { IUserProfile } from "../_utils/types";
import { ProfilePicture } from "../_components/layout/profile";

export const ProfilePage = () => {
    const { data } = useGetUser();
    const user = (data?.data?.user as IUserProfile) || {};
    return (
        <>
            <SeoWrapper title="Manage Profile" />
            <DashboardLayout>
                <PageHeader
                    title="Manage Profile"
                    description="Update your personal information and account settings."
                    items={[{ title: "Profile" }]}
                />

                <Tabs defaultValue="info" className="w-full">
                    <TabsList className="grid grid-cols-2 gap-3 mb-6 h-auto bg-transparent p-0">
                        <TabsTrigger
                            value="info"
                            className="flex flex-col items-start gap-1 p-4 rounded-2xl border bg-card h-auto data-[state=active]:border-primary data-[state=active]:bg-primary/5 data-[state=active]:shadow-sm text-left cursor-pointer"
                        >
                            <span className="flex items-center gap-2 font-semibold text-foreground">
                                <UserCog className="size-5 text-primary" />
                                Profile Info
                            </span>
                            <span className="text-xs font-normal text-muted-foreground">
                                Update your personal & contact details
                            </span>
                        </TabsTrigger>
                        <TabsTrigger
                            value="password"
                            className="flex flex-col items-start gap-1 p-4 rounded-2xl border bg-card h-auto data-[state=active]:border-primary data-[state=active]:bg-primary/5 data-[state=active]:shadow-sm text-left cursor-pointer"
                        >
                            <span className="flex items-center gap-2 font-semibold text-foreground">
                                <ShieldCheck className="size-5 text-primary" />
                                Change Password
                            </span>
                            <span className="text-xs font-normal text-muted-foreground">
                                Keep your account secure
                            </span>
                        </TabsTrigger>
                    </TabsList>

                    <TabsContent value="info">
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.3, ease: "easeOut" }}
                        >
                            <ProfileInfoTab user={user} />
                        </motion.div>
                    </TabsContent>
                    <TabsContent value="password">
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.3, ease: "easeOut" }}
                        >
                            <PasswordTab />
                        </motion.div>
                    </TabsContent>
                </Tabs>
            </DashboardLayout>
        </>
    );
};

const ProfileInfoTab = ({ user }: { user: IUserProfile }) => {
    const { mutate: updateProfile, isPending } = useProfileUpdateMutation();
    const [form, setForm] = useState<IUserProfile | null>(user || null);

    useEffect(() => {
        if (user) setForm(user);
    }, [user]);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { name, value } = e.target;
        setForm((prev) => ({ ...(prev as IUserProfile), [name]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (!form?.name?.trim() || !form?.email?.trim()) {
            toast.error("Name and email are required");
            return;
        }
        updateProfile({
            name: form.name,
            email: form.email,
            phone: form.phone || undefined,
            address: form.address || undefined,
            city: form.city || undefined,
            state: form.state || undefined,
            country: form.country || undefined,
            postal_code: form.postal_code || undefined,
        });
    };

    if (!form) return null;

    return (
        <div className="space-y-6">
            <Card className="rounded-2xl">
                <CardContent className="p-6">
                    <div className="flex items-center gap-5">
                        <ProfilePicture user={user} />
                        <form onSubmit={handleSubmit} className="space-y-6">
                            <h3 className="font-semibold text-foreground">
                                {form?.name}
                            </h3>
                            <p className="text-sm text-muted-foreground">
                                {form?.email}
                            </p>
                            <p className="text-xs text-muted-foreground mt-1">
                                Click the camera icon to change your photo
                            </p>
                        </form>
                    </div>
                </CardContent>
            </Card>

            <Card className="rounded-2xl">
                <CardContent className="p-6">
                    <form onSubmit={handleSubmit}>
                        <h3 className="font-semibold text-foreground mb-1">
                            Personal Information
                        </h3>
                        <p className="text-sm text-muted-foreground mb-5">
                            Update your account details
                        </p>

                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <Field
                                label="Full Name"
                                name="name"
                                value={form?.name}
                                onChange={handleChange}
                                required
                            />
                            <Field
                                label="Email"
                                name="email"
                                type="email"
                                value={form?.email}
                                onChange={handleChange}
                            />
                            <div className="space-y-2">
                                <Label>Phone</Label>
                                <Input
                                    defaultValue={form?.phone}
                                    className="h-11"
                                    readOnly={true}
                                    disabled
                                />
                            </div>
                            <Field
                                label="Address"
                                name="address"
                                value={form?.address || ""}
                                onChange={handleChange}
                            />
                            <Field
                                label="City"
                                name="city"
                                value={form?.city || ""}
                                onChange={handleChange}
                            />
                            <Field
                                label="State / Province"
                                name="state"
                                value={form?.state || ""}
                                onChange={handleChange}
                            />
                            <Field
                                label="Country"
                                name="country"
                                value={form?.country || ""}
                                onChange={handleChange}
                            />
                            <Field
                                label="Postal Code"
                                name="postal_code"
                                value={form?.postal_code || ""}
                                onChange={handleChange}
                            />
                        </div>

                        <Separator className="my-6" />

                        <div className="flex justify-end">
                            <Button
                                type="submit"
                                size="lg"
                                disabled={isPending}
                                className="gap-2"
                            >
                                {isPending ? (
                                    <Loader2 className="size-4 animate-spin" />
                                ) : (
                                    <Save className="size-4" />
                                )}
                                {isPending ? "Saving..." : "Save Changes"}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    );
};

const PasswordTab = () => {
    const { mutate: changePassword, isPending } = useChangePasswordMutation();
    const [form, setForm] = useState({
        current_password: "",
        new_password: "",
        new_password_confirmation: "",
    });

    const handleChange = (key: string, value: string) => {
        setForm((prev) => ({ ...prev, [key]: value }));
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (!form.current_password || !form.new_password) {
            toast.error("Please fill in all password fields");
            return;
        }
        if (form.new_password.length < 6) {
            toast.error("New password must be at least 6 characters");
            return;
        }
        if (form.new_password !== form.new_password_confirmation) {
            toast.error("New passwords do not match");
            return;
        }

        changePassword(form, {
            onSuccess: () => {
                setForm({
                    current_password: "",
                    new_password: "",
                    new_password_confirmation: "",
                });
            },
        });
    };

    return (
        <Card className="rounded-3xl">
            <CardContent className="p-6">
                <div className="flex items-center gap-3 mb-5">
                    <div className="flex items-center justify-center size-11 rounded-xl bg-primary/10">
                        <ShieldCheck className="size-5 text-primary" />
                    </div>
                    <div>
                        <h3 className="font-semibold text-foreground">
                            Change Password
                        </h3>
                        <p className="text-sm text-muted-foreground">
                            Use a strong password with 8+ characters.
                        </p>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-5">
                    <div className="grid grid-cols-1 gap-5">
                        <PasswordInput
                            id="current_password"
                            name="current_password"
                            label="Current Password"
                            placeholder="Enter your current password"
                            value={form.current_password}
                            onChange={(v) =>
                                handleChange("current_password", v)
                            }
                            required
                        />
                        <div className="hidden md:block" />
                        <PasswordInput
                            id="new_password"
                            name="new_password"
                            label="New Password"
                            placeholder="Enter your new password"
                            value={form.new_password}
                            onChange={(v) => handleChange("new_password", v)}
                            required
                        />
                        <PasswordInput
                            id="new_password_confirmation"
                            name="new_password_confirmation"
                            label="Confirm New Password"
                            placeholder="Re-enter your new password"
                            value={form.new_password_confirmation}
                            onChange={(v) =>
                                handleChange("new_password_confirmation", v)
                            }
                            required
                        />
                    </div>

                    <Separator />

                    <div className="flex justify-end">
                        <Button
                            type="submit"
                            size="lg"
                            disabled={isPending}
                            className="gap-2"
                        >
                            {isPending ? (
                                <Loader2 className="size-4 animate-spin" />
                            ) : (
                                <ShieldCheck className="size-4" />
                            )}
                            {isPending ? "Updating..." : "Update Password"}
                        </Button>
                    </div>
                </form>
            </CardContent>
        </Card>
    );
};

const Field = ({
    label,
    name,
    value,
    onChange,
    type = "text",
    required = false,
}: {
    label: string;
    name: string;
    value: string;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
    type?: string;
    required?: boolean;
}) => {
    return (
        <div className="space-y-2">
            <Label htmlFor={name}>
                {label}
                {required && <span className="text-red-500 ml-0.5">*</span>}
            </Label>
            <Input
                id={name}
                name={name}
                type={type}
                value={value}
                onChange={onChange}
                className="h-11"
                required={required}
            />
        </div>
    );
};
