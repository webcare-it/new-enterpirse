import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Calendar, User } from "lucide-react";
import type { IBlog } from "@/type";
import { BlogLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { BlogCard, BlogCardSkeleton } from "../_components/common/blog";
import { Skeleton } from "@/components/common/skeleton";
import { RenderHtml } from "@/components/html";
import { SeoWrapper } from "@/components/common/seo-wrapper";
import { useBlogDetails } from "@/api/blog";

interface IBlogDetails {
    id: number;
    title: string;
    slug: string;
    description: string;
    user: {
        name: string;
    };
    main_image: string;
    created_at: string;
    long_description: string;
    tags: string[];
    meta: {
        title: string;
        description: string;
        image: string;
    };
}

export const BlogDetailsPage = () => {
    const { data, isLoading } = useBlogDetails();
    const blogInfo = (data?.data?.blog as IBlogDetails) || {};
    const relatedBlogs = (data?.data?.related_blogs as IBlog[]) || [];

    return (
        <>
            <SeoWrapper
                title={blogInfo?.meta?.title || blogInfo?.title}
                description={
                    blogInfo?.meta?.description || blogInfo?.description
                }
                tags={blogInfo?.tags?.join(", ")}
            />
            <BaseLayout>
                <LayoutContainer className=" pt-10">
                    {isLoading ? (
                        <BlogDetailsSkeleton />
                    ) : (
                        <BlogDetails blog={blogInfo} />
                    )}

                    <BlogLayout className="pb-16 md:pb-20">
                        {relatedBlogs?.map((blog, i: number) => (
                            <AnimationWrapper
                                key={blog?.id}
                                initial={{
                                    opacity: 0,
                                    y: 40,
                                }}
                                whileInView={{
                                    opacity: 1,
                                    y: 0,
                                }}
                                viewport={{
                                    once: true,
                                    amount: 0.2,
                                }}
                                transition={{
                                    duration: 0.6,
                                    delay: i * 0.05,
                                }}
                            >
                                <BlogCard blog={blog} />
                            </AnimationWrapper>
                        ))}
                    </BlogLayout>
                </LayoutContainer>
            </BaseLayout>
        </>
    );
};

const BlogDetails = ({ blog }: { blog: IBlogDetails }) => {
    return (
        <section className="pb-16 md:pb-24">
            <div className="aspect-[16/10] md:aspect-[16/5] relative overflow-hidden rounded shadow">
                <OptimizedImage
                    src={blog?.main_image}
                    alt={"Blog Image"}
                    className="absolute w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
            </div>
            <div className="mx-auto py-8 max-w-5xl">
                <article className="bg-white rounded-0 shadow-none sm:rounded sm:shadow-lg -mt-0 sm:-mt-20 relative z-10">
                    <div className="p-2 md:p-8">
                        <h1 className="text-2xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 leading-tight">
                            {blog?.title}
                        </h1>
                        <p className="text-lg text-muted-foreground mb-6 leading-relaxed">
                            {blog?.description}
                        </p>
                        <div className="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-muted-foreground border-b pb-4 sm:pb-6">
                            <div className="flex items-center gap-2">
                                <User className="w-4 h-4" />
                                <span className="font-medium text-gray-700">
                                    {blog?.user?.name}
                                </span>
                            </div>
                            <div className="flex items-center gap-2">
                                <Calendar className="w-4 h-4" />
                                <span>{blog?.created_at}</span>
                            </div>
                        </div>
                    </div>
                    <div className="p-2 md:p-8">
                        <RenderHtml html={blog?.long_description || ""} />
                    </div>
                </article>
            </div>
        </section>
    );
};

const BlogDetailsSkeleton = () => (
    <>
        <section className="pb-10 md:pb-16">
            <div className="aspect-[16/10] md:aspect-[16/5] relative overflow-hidden">
                <Skeleton className="absolute w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
            </div>
            <div className="mx-auto py-8 max-w-5xl">
                <article className="bg-white shadow-none sm:shadow-lg -mt-0 sm:-mt-20 relative z-10">
                    <div className="p-2 md:p-8">
                        <Skeleton className="h-6 mb-3" />
                        <Skeleton className="h-4 mb-2" />
                        <Skeleton className="h-4 mb-6" />
                        <div className="flex flex-col sm:flex-row sm:items-center gap-4 text-sm text-muted-foreground border-b pb-4 sm:pb-6">
                            <Skeleton className="w-10 h-10 rounded-full" />
                            <div className="flex items-center gap-2">
                                <Skeleton className="h-4 w-20 mb-1" />
                                <Skeleton className="h-3 w-16" />
                            </div>
                        </div>
                    </div>
                    <div className="p-2 md:p-8">
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                        <Skeleton className="h-4 mb-3" />
                    </div>
                </article>
            </div>
        </section>
        <BlogLayout className="pb-16 md:pb-20">
            {[1, 2, 3, 4, 5, 6, 7, 8, 9].map((i) => (
                <BlogCardSkeleton key={i} />
            ))}
        </BlogLayout>
    </>
);
