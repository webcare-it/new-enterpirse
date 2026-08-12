import type { ICategoryWithProducts } from "@/type";
import { ProductsSection } from "./product-section";

export const CategorySection = ({
    categories,
    loading,
}: {
    loading: boolean;
    categories: ICategoryWithProducts[];
}) => {
    if (loading) {
        return <div>Loading...</div>;
    }

    if (categories?.length === 0) return null;

    return (
        <>
            {categories?.map((category, index) => (
                <ProductsSection
                    key={category?.id + "_" + index}
                    title={category?.name}
                    products={category?.products || []}
                    href={`/categories/${category?.slug}`}
                    loading={loading}
                />
            ))}
        </>
    );
};
