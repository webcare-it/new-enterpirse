import { LayoutContainer } from "../_components/layout/base-layout";
import type { IBlog } from "@/type";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { BlogCard } from "../_components/common/blog";
import { BlogLayout } from "../_components/common/layout";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { ArrowRight } from "lucide-react";

export const BlogSection = ({ blogs }: { blogs: IBlog[] }) => {
    if (blogs?.length === 0) return null;

    return (
        <LayoutContainer className="py-4">
            <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground mt-1 transition-all duration-500 ease-out hover:tracking-wide hover:text-primary text-center">
                Latest Blogs
            </h2>
            <BlogLayout className="my-6">
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

            <div className="flex justify-center mt-4">
                <Link to="/blogs">
                    <Button>
                        View all
                        <ArrowRight className="w-4 h-4 transition-transform duration-300 group-hover/view-all:translate-x-1" />{" "}
                    </Button>
                </Link>
            </div>
        </LayoutContainer>
    );
};
