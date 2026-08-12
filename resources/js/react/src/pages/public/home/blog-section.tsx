import { LayoutContainer } from "../_components/layout/base-layout";
import type { IBlog } from "@/type";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { BlogCard } from "../_components/common/blog";
import { BlogLayout } from "../_components/common/layout";
import { SectionTitleWithLink } from "./product-section";

export const BlogSection = ({ blogs }: { blogs: IBlog[] }) => {
    if (blogs?.length === 0) return null;

    return (
        <LayoutContainer className="py-4">
            <SectionTitleWithLink title="Latest Blogs" href="/blogs" />
            <BlogLayout className="pb-16 md:pb-20 mt-6">
                {blogs?.map((blog, i: number) => (
                    <AnimationWrapper
                        key={blog?.id}
                        initial={{ opacity: 0, y: 40 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        viewport={{
                            once: true,
                            amount: 0.2,
                        }}
                        transition={{
                            duration: 0.6,
                            delay: i * 0.04,
                        }}
                    >
                        <BlogCard blog={blog} />
                    </AnimationWrapper>
                ))}
            </BlogLayout>
        </LayoutContainer>
    );
};
