import { motion } from "framer-motion";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { Label } from "@/components/ui/label";
import { Mail, Phone, MapPin, User, MessageCircle, Send } from "lucide-react";
import { useConfig } from "@/hooks/useConfig";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { useContactStoreMutation } from "@/api/contact";

export const ContactPage = () => {
    const config = useConfig();

    const phone = config?.contact_phone as string;
    const email = config?.contact_email as string;
    const address = config?.contact_address as string;
    const desc =
        (config?.contact_desc as string) ||
        ` Have questions or want to learn more about
                                    our services? We'd love to hear from you.
                                    Reach out to us and we'll respond as soon as
                                    possible.`;
    const businessOpeningHour =
        (config?.b_opening_hour as string) ||
        `Saturday - Thursday: 10:00 AM - 8:00 PM`;
    const businessClosingHour =
        (config?.b_closing_hour as string) || `Friday: Closed`;

    const { mutate, isPending } = useContactStoreMutation();

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        const formData = new FormData(e.target as HTMLFormElement);

        mutate(formData);
        (e.currentTarget as HTMLFormElement).reset();
    };

    return (
        <>
            <SeoWrapper title="Contact Us" description="Get in touch with us" />
            <BaseLayout>
                <section className="pb-16 mt-6 md:pb-24">
                    <LayoutContainer>
                        <div className="max-w-6xl mx-auto">
                            <motion.div
                                className="text-center mb-16"
                                initial={{ opacity: 0, y: 30 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true, amount: 0.3 }}
                                transition={{ duration: 0.7 }}
                            >
                                <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                                    Get In Touch
                                </h2>
                                <p className="text-gray-500 max-w-2xl mx-auto">
                                    {desc}
                                </p>
                            </motion.div>

                            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                                <motion.div
                                    className="lg:col-span-1"
                                    initial={{ opacity: 0, x: -30 }}
                                    whileInView={{ opacity: 1, x: 0 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 0.6, delay: 0.1 }}
                                >
                                    <div className="p-6 rounded-2xl bg-white border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] h-full">
                                        <h3 className="text-xl font-semibold text-gray-900 mb-6">
                                            Contact Information
                                        </h3>

                                        <div className="space-y-6">
                                            <div className="flex items-start space-x-4">
                                                <div className="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center">
                                                    <Phone className="w-5 h-5 text-primary" />
                                                </div>
                                                <div>
                                                    <h4 className="font-medium text-gray-900">
                                                        Phone
                                                    </h4>
                                                    <p className="text-gray-500 mt-1 text-sm">
                                                        {phone}
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="flex items-start space-x-4">
                                                <div className="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center">
                                                    <Mail className="w-5 h-5 text-primary" />
                                                </div>
                                                <div>
                                                    <h4 className="font-medium text-gray-900">
                                                        Email
                                                    </h4>
                                                    <p className="text-gray-500 mt-1 text-sm">
                                                        {email}
                                                    </p>
                                                </div>
                                            </div>

                                            <div className="flex items-start space-x-4">
                                                <div className="flex-shrink-0 w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center">
                                                    <MapPin className="w-5 h-5 text-primary" />
                                                </div>
                                                <div>
                                                    <h4 className="font-medium text-gray-900">
                                                        Address
                                                    </h4>
                                                    <p className="text-gray-500 mt-1 text-sm">
                                                        {address}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div className="my-6 h-px bg-gray-100" />

                                        <div className="space-y-4">
                                            <h4 className="font-medium text-gray-900">
                                                Business Hours
                                            </h4>
                                            <div className="text-sm text-gray-500 space-y-1">
                                                <p>{businessOpeningHour}</p>
                                                <p>{businessClosingHour}</p>
                                            </div>
                                        </div>
                                    </div>
                                </motion.div>

                                <motion.div
                                    className="lg:col-span-2"
                                    initial={{ opacity: 0, x: 30 }}
                                    whileInView={{ opacity: 1, x: 0 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 0.6, delay: 0.2 }}
                                >
                                    <div className="p-6 rounded-2xl bg-white border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)]">
                                        <h3 className="text-xl font-semibold text-gray-900 mb-6">
                                            Send us a message
                                        </h3>

                                        <form
                                            onSubmit={handleSubmit}
                                            className="space-y-6"
                                        >
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div className="space-y-2">
                                                    <Label
                                                        htmlFor="name"
                                                        className="text-sm font-medium text-gray-700"
                                                    >
                                                        Full Name
                                                    </Label>
                                                    <div className="relative">
                                                        <Input
                                                            id="name"
                                                            name="full_name"
                                                            placeholder="Enter your name"
                                                            required
                                                            className="pl-10 h-11"
                                                        />
                                                        <User className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                                    </div>
                                                </div>

                                                <div className="space-y-2">
                                                    <Label
                                                        htmlFor="email"
                                                        className="text-sm font-medium text-gray-700"
                                                    >
                                                        Email Address
                                                    </Label>
                                                    <div className="relative">
                                                        <Input
                                                            id="email"
                                                            type="email"
                                                            name="email"
                                                            placeholder="Enter your email"
                                                            required
                                                            className="pl-10 h-11"
                                                        />
                                                        <Mail className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div className="space-y-2">
                                                <Label
                                                    htmlFor="subject"
                                                    className="text-sm font-medium text-gray-700"
                                                >
                                                    Subject
                                                </Label>
                                                <div className="relative">
                                                    <Input
                                                        id="subject"
                                                        name="subject"
                                                        placeholder="What is this regarding?"
                                                        required
                                                        className="pl-10 h-11"
                                                    />
                                                    <MessageCircle className="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" />
                                                </div>
                                            </div>

                                            <div className="space-y-2">
                                                <Label
                                                    htmlFor="message"
                                                    className="text-sm font-medium text-gray-700"
                                                >
                                                    Message
                                                </Label>
                                                <Textarea
                                                    id="message"
                                                    name="message"
                                                    placeholder="How can we help you?"
                                                    required
                                                    rows={5}
                                                    className="min-h-[120px]"
                                                />
                                            </div>

                                            <div className="flex justify-end">
                                                <Button
                                                    type="submit"
                                                    size="xl"
                                                    disabled={isPending}
                                                    className="w-full md:w-auto"
                                                >
                                                    <Send className="w-4 h-4 mr-2" />
                                                    Send Message
                                                </Button>
                                            </div>
                                        </form>
                                    </div>
                                </motion.div>
                            </div>
                        </div>
                    </LayoutContainer>
                </section>
            </BaseLayout>
        </>
    );
};
