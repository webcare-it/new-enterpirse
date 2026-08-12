import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { BlogCard } from "../_components/common/blog";
import { useEffect } from "react";
import { BlogLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { NoDataFound } from "@/components/common/no-data-found";
import { cn } from "@/lib/utils";
import { LoaderIcon } from "lucide-react";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { useIntersectionObserver } from "@/hooks/useIntersection";
import { BreadcrumbBackground } from "../_components/common/breadcrumb-wrapper";
import { useGetBlogs } from "@/api/blog";
import type { IBlog } from "@/type";

export const BlogsPage = () => {
    const { data, fetchNextPage, hasNextPage, isFetchingNextPage, isLoading } =
        useGetBlogs();

    const { ref, isIntersecting } = useIntersectionObserver({ threshold: 0.1 });

    useEffect(() => {
        if (isIntersecting && hasNextPage && !isFetchingNextPage) {
            fetchNextPage();
        }
    }, [isIntersecting, hasNextPage, isFetchingNextPage, fetchNextPage]);

    const pages = data?.pages || [];
    const blogs =
        pages?.flatMap((page) => (page?.data?.blogs as IBlog[]) || []) || [];

    return (
        <>
            <SeoWrapper title="All Blogs" description="Blogs" />

            <BaseLayout>
                <LayoutContainer>
                    <BreadcrumbBackground
                        title="About Us"
                        breadcrumb={[{ title: "About Us" }]}
                    />

                    {isLoading ? (
                        <div className="flex items-center h-screen justify-center">
                            <LoaderIcon
                                role="status"
                                aria-label="Loading"
                                className={cn("size-4 animate-spin")}
                            />
                        </div>
                    ) : (
                        <BlogLayout className="pb-16 md:pb-20 mt-6">
                            <>
                                {blogs?.length > 0 ? (
                                    blogs?.map((blog, i: number) => (
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
                                    ))
                                ) : (
                                    <div className="col-span-full">
                                        <NoDataFound
                                            title="No Blogs Found"
                                            description="We couldn't find any blogs matching your criteria."
                                        />
                                    </div>
                                )}
                                {hasNextPage && (
                                    <div
                                        ref={ref}
                                        className="col-span-full flex justify-center py-4"
                                    >
                                        {isFetchingNextPage && (
                                            <LoaderIcon
                                                role="status"
                                                aria-label="Loading"
                                                className={cn(
                                                    "size-4 animate-spin",
                                                )}
                                            />
                                        )}
                                    </div>
                                )}
                            </>
                        </BlogLayout>
                    )}
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};
