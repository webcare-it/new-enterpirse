import { useNewsletterMutation } from "@/api/newsletter";
import { LayoutContainer } from "../base-layout";
import { isValidEmail } from "@/helper";
import toast from "react-hot-toast";

export const Newsletter = () => {
    const { mutate, isPending } = useNewsletterMutation();

    const handleNewsletter = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        const form = e.currentTarget;
        const email = form.email.value;

        if (!isValidEmail(email)) {
            toast.error("Invalid email address.");
            return;
        }

        mutate(
            { email },
            {
                onSuccess: () => {
                    form.reset();
                },
            },
        );
    };

    return (
        <LayoutContainer>
            <div className="rounded-3xl -mt-24 md:-mt-20 bg-primary border border-primary/40 text-primary-foreground py-6 px-4 md:p-12">
                <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div className="space-y-1">
                        <h2 className="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight uppercase leading-tight">
                            Subscribe to{" "}
                            <br className="hidden sm:inline lg:hidden" /> our
                            Newsletter
                        </h2>
                        <p className="text-sm md:text-base text-primary-foreground font-medium">
                            Get the latest news and updates
                        </p>
                    </div>

                    <form
                        className="w-full lg:max-w-md"
                        onSubmit={handleNewsletter}
                    >
                        <div className="flex items-center w-full bg-white rounded-2xl overflow-hidden border border-gray-300 focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/20 transition p-0.5">
                            <input
                                type="email"
                                name="email"
                                disabled={isPending}
                                id="email"
                                placeholder="Enter your email address"
                                className="flex-1 h-10 md:h-11 px-3 bg-transparent text-gray-950 placeholder-gray-400 outline-none text-sm min-w-0"
                                required
                            />
                            <button
                                type="submit"
                                disabled={isPending}
                                className="h-10 md:h-11 px-5 rounded-xl bg-primary text-primary-foreground hover:bg-primary/90 transition flex items-center justify-center font-medium text-sm cursor-pointer shrink-0"
                            >
                                {isPending ? "Loading..." : "Submit"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </LayoutContainer>
    );
};
