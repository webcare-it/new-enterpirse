import { motion } from "framer-motion";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/components/ui/accordion";
import { HelpCircle } from "lucide-react";
import { useGetFaqs } from "@/api/page";
import { Loading } from "../_components/common/loading";

interface IFaq {
    id: number;
    question: string;
    answer: string;
}

export const FaqsPage = () => {
    const { data, isLoading } = useGetFaqs();
    const faqs = (data?.data?.faqs as IFaq[]) || [];

    return (
        <>
            <SeoWrapper title="FAQs" description="Frequently asked questions" />
            <BaseLayout>
                <BreadcrumbBackground
                    title="FAQs"
                    breadcrumb={[{ title: "FAQs" }]}
                />

                {isLoading ? (
                    <div className="flex items-center h-screen justify-center">
                        <Loading />
                    </div>
                ) : (
                    <section className="pt-4 md:pt-6 pb-16 md:pb-24">
                        <LayoutContainer>
                            <div className=" space-y-6">
                                <motion.div
                                    className="bg-white border border-gray-100 shadow-[0_2px_12px_rgba(0,0,0,0.04)] rounded-3xl overflow-hidden max-w-7xl mx-auto"
                                    initial={{ opacity: 0, y: 30 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 0.6, delay: 0.2 }}
                                >
                                    {faqs?.length > 0 ? (
                                        <Accordion
                                            type="single"
                                            collapsible
                                            defaultValue={`faq-${faqs?.at(0)?.id}`}
                                            className="divide-y divide-gray-100"
                                        >
                                            {faqs.map((faq) => (
                                                <AccordionItem
                                                    key={faq.id}
                                                    value={`faq-${faq.id}`}
                                                    className="border-none"
                                                >
                                                    <AccordionTrigger className="px-6 py-5 text-base font-medium text-gray-900 hover:no-underline hover:bg-gray-50/50 cursor-pointer transition-colors">
                                                        {faq.question}
                                                    </AccordionTrigger>
                                                    <AccordionContent className="px-6 pb-5 text-gray-500 leading-relaxed">
                                                        {faq.answer}
                                                    </AccordionContent>
                                                </AccordionItem>
                                            ))}
                                        </Accordion>
                                    ) : (
                                        <div className="py-16 text-center">
                                            <HelpCircle className="w-12 h-12 text-gray-300 mx-auto mb-4" />
                                            <p className="text-gray-500 font-medium">
                                                No questions found
                                            </p>
                                            <p className="text-gray-400 text-sm mt-1">
                                                Try adjusting your search or
                                                filter
                                            </p>
                                        </div>
                                    )}
                                </motion.div>

                                <motion.div
                                    className="text-center pt-4"
                                    initial={{ opacity: 0, y: 20 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    viewport={{ once: true, amount: 0.3 }}
                                    transition={{ duration: 0.5 }}
                                >
                                    <p className="text-gray-500">
                                        Still have questions?{" "}
                                        <a
                                            href="/contact-us"
                                            className="text-primary font-medium hover:underline"
                                        >
                                            Contact our support team
                                        </a>
                                    </p>
                                </motion.div>
                            </div>
                        </LayoutContainer>
                    </section>
                )}
            </BaseLayout>
        </>
    );
};
