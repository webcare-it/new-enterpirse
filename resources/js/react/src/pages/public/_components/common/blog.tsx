import { Link } from "react-router-dom";
import type { IBlog } from "@/type";
import { OptimizedImage } from "@/components/common/optimized-image";
import { Skeleton } from "@/components/common/skeleton";

export const BlogCard = ({ blog }: { blog: IBlog }) => {
    return (
        <Link to={`/blogs/${blog?.slug}`} key={blog.id}>
            <article
                key={blog?.id}
                className="rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col"
            >
                <OptimizedImage
                    src={blog?.thumbnail}
                    alt={blog?.title}
                    className="w-full h-60 object-cover"
                />

                <div className="p-6 flex-1 flex flex-col">
                    <h3 className="text-xl font-bold text-gray-900 mb-3 line-clamp-1">
                        {blog?.title}
                    </h3>
                    <p className="text-gray-600 text-sm mb-6 line-clamp-1 flex-1">
                        {blog?.short_description}
                    </p>

                    <div className="flex items-center gap-3 mt-auto">
                        <div className="w-10 h-10 rounded-full bg-gradient-to-br from-primary/20 to-primary flex items-center justify-center text-white font-bold text-sm">
                            {blog?.author?.charAt(0)}
                        </div>
                        <div>
                            <p className="text-sm font-medium text-gray-900">
                                {blog?.author}
                            </p>
                            <p className="text-xs text-gray-500">
                                {blog?.created_at}
                            </p>
                        </div>
                    </div>
                </div>
            </article>
        </Link>
    );
};

export const BlogCardSkeleton = () => {
    return (
        <article className="shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300 flex flex-col hover:bg-primary/5">
            <Skeleton className="w-full h-60" />
            <div className="p-6 flex-1 flex flex-col">
                <Skeleton className="h-6 mb-3" />
                <Skeleton className="h-4 mb-2" />
                <Skeleton className="h-4 mb-6" />
                <div className="flex items-center gap-3 mt-auto">
                    <Skeleton className="w-10 h-10 rounded-full" />
                    <div>
                        <Skeleton className="h-4 w-20 mb-1" />
                        <Skeleton className="h-3 w-16" />
                    </div>
                </div>
            </div>
        </article>
    );
};
