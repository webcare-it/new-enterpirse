import { useNewsletterMutation } from "@/api/newsletter";
import { Input } from "@/components/ui/input";
import { isValidEmail } from "@/helper";
import { useConfig } from "@/hooks/useConfig";
import toast from "react-hot-toast";

export const Newsletter = () => {
    const config = useConfig();
    const sub_title =
        (config?.n_sub_title as string) || "Get the latest news and updates";

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
        <div className="col-span-2 flex flex-col gap-4">
            <div className="space-y-1">
                <h2 className="text-2xl md:text-3xl font-semibold tracking-tight uppercase leading-tight">
                    Subscribe to <br className="hidden sm:inline lg:hidden" />{" "}
                    our Newsletter
                </h2>
                <p className="text-sm md:text-base text-primary-foreground font-medium">
                    {sub_title}
                </p>
            </div>

            <form className="w-full lg:max-w-md" onSubmit={handleNewsletter}>
                <div className="flex h-11 w-full overflow-hidden rounded-xl border bg-background focus-within:ring-1 focus-within:ring-ring">
                    <div className="relative flex min-w-0 flex-1 items-center">
                        <Input
                            placeholder="Enter your email"
                            type="email"
                            name="email"
                            id="email"
                            required
                            className="h-full w-full rounded-none border-0 px-4 pr-10 shadow-none focus-visible:ring-0 text-gray-900"
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={isPending}
                        className="flex h-full shrink-0 items-center justify-center gap-2 border-l bg-primary px-5 text-sm font-medium text-primary-foreground transition hover:bg-primary/90 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {isPending ? "Loading..." : "Submit"}
                    </button>
                </div>
            </form>
        </div>
    );
};
