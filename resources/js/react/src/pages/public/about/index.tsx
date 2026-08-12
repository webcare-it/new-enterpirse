import { motion } from "framer-motion";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { useGetAbout } from "@/api/page";
import { Loading } from "../_components/common/loading";
import { RenderHtml } from "@/components/html";
import { OptimizedImage } from "@/components/common/optimized-image";

interface IAboutItem {
    id: number;
    title: string;
    sub_title: string;
    description: string;
    image: string;
    image_position: "left" | "right";
}

interface ICounter {
    number: string;
    title: string;
}

export const AboutPage = () => {
    const { data, isLoading } = useGetAbout();

    const items = (data?.data?.items as IAboutItem[]) || [];
    const counters = (data?.data?.counters as ICounter[]) || [];

    return (
        <>
            <SeoWrapper
                title="About Us"
                description="Learn more about our story and mission"
            />
            <BaseLayout>
                <BreadcrumbBackground
                    title="About Us"
                    breadcrumb={[{ title: "About Us" }]}
                />

                {isLoading ? (
                    <div className="flex items-center h-screen justify-center">
                        <Loading />
                    </div>
                ) : (
                    <section className="py-16 md:py-24">
                        <LayoutContainer>
                            <div className="space-y-20 md:space-y-28">
                                {items?.map((item) => (
                                    <motion.div
                                        key={item?.id}
                                        className="grid md:grid-cols-2 gap-12"
                                        initial={{ opacity: 0, y: 40 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{ once: true, amount: 0.3 }}
                                        transition={{ duration: 0.7 }}
                                    >
                                        <div
                                            className={`space-y-6 ${
                                                item?.image_position === "left"
                                                    ? "md:order-2"
                                                    : "md:order-1"
                                            }`}
                                        >
                                            <span className="text-primary font-semibold text-sm tracking-widest uppercase">
                                                {item?.sub_title}
                                            </span>
                                            <h2 className="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 leading-tight">
                                                {item?.title}
                                            </h2>
                                            <div className="w-16 h-1 bg-primary rounded-full" />
                                            <RenderHtml
                                                html={item?.description}
                                            />
                                        </div>
                                        <div
                                            className={`relative ${
                                                item.image_position === "left"
                                                    ? "md:order-1"
                                                    : "md:order-2"
                                            }`}
                                        >
                                            <div className="aspect-[4/3] rounded-4xl overflow-hidden bg-gradient-to-br from-primary/10 to-primary/5">
                                                <OptimizedImage
                                                    src={item?.image || ""}
                                                    alt="Our team at work"
                                                    className="w-full h-full object-cover mix-blend-multiply"
                                                />
                                            </div>
                                        </div>
                                    </motion.div>
                                ))}

                                <motion.div
                                    className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6"
                                    initial={{ opacity: 0, y: 30 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 0.6 }}
                                >
                                    {counters?.map((stat, i) => (
                                        <motion.div
                                            key={stat?.number}
                                            className="p-6 md:p-8 rounded-2xl bg-white border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] text-center"
                                            initial={{ opacity: 0, y: 20 }}
                                            whileInView={{ opacity: 1, y: 0 }}
                                            viewport={{
                                                once: true,
                                                amount: 0.3,
                                            }}
                                            transition={{
                                                duration: 0.4,
                                                delay: i * 0.1,
                                            }}
                                        >
                                            <div className="text-3xl md:text-4xl font-bold text-primary mb-2">
                                                {stat?.number}
                                            </div>
                                            <div className="text-sm text-gray-500">
                                                {stat?.title}
                                            </div>
                                        </motion.div>
                                    ))}
                                </motion.div>
                            </div>
                        </LayoutContainer>
                    </section>
                )}
            </BaseLayout>
        </>
    );
};
