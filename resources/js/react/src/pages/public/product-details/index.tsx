import { useState, useMemo, useCallback } from "react";
import { BaseLayout, LayoutContainer } from "../_components/layout/base-layout";
import { OptimizedImage } from "@/components/common/optimized-image";
import { ProductInfo } from "./info";
import { ProductLayout } from "../_components/common/layout";
import { AnimationWrapper } from "@/components/common/animation-wrapper";
import { ProductCard } from "../_components/common/product";
import { ProductInfoTabs } from "./tabs";
import { ProductImageSliderMobile } from "./mobile-slider";
import { HorizontalGalleryStrip } from "./horizontal-gallery";
import { PhotoSwipeGallery } from "./photo-swipe-gallery";
import { BreadcrumbWrapper } from "@/components/common/breadcrumb-wrapper";
import type { IProductDetails, IProductImage } from "./type";
import { useProductDetails } from "@/api/product";
import type { IProduct } from "@/type";
import { Loading } from "../_components/common/loading";
import { SeoWrapper } from "@/components/common/seo-wrapper";

export const ProductDetailPage = () => {
    const { data, isLoading } = useProductDetails();

    const _product = (data?.data?.product as IProductDetails) || {};
    const products = data?.data?.related_products || [];

    const [isModalOpen, setIsModalOpen] = useState(false);
    const [selectedImageIndex, setSelectedImageIndex] = useState(0);
    const [extraImages, setExtraImages] = useState<IProductImage[]>([]);
    const productImages: IProductImage[] = useMemo(
        () => [
            {
                id: 1,
                src: _product.thumbnail,
                alt: _product.name,
            },
            ...(_product?.images || []).map((src: string, i: number) => ({
                id: i + 2,
                src,
                alt: `${_product.name} - Image ${i + 1}`,
            })),
        ],
        [_product],
    );

    const allImages = useMemo(() => {
        const images = [...extraImages, ...productImages];
        const imgs = new Set(images);
        return Array.from(imgs);
    }, [extraImages, productImages]);
    const currentImage = allImages[selectedImageIndex];

    const openModal = (index: number) => {
        setSelectedImageIndex(index);
        setIsModalOpen(true);
    };

    const closeModal = () => setIsModalOpen(false);

    const addVariantImage = useCallback(
        (src: string, alt: string) => {
            setExtraImages((prev) => {
                const extraIndex = prev.findIndex((img) => img?.src === src);
                if (extraIndex >= 0) {
                    setSelectedImageIndex(extraIndex);
                    return prev;
                }
                const productIndex = productImages.findIndex(
                    (img) => img?.src === src,
                );
                if (productIndex >= 0) {
                    setSelectedImageIndex(prev?.length + productIndex);
                    return prev;
                }
                const newImg: IProductImage = { id: Date.now(), src, alt };
                setSelectedImageIndex(0);
                return [newImg, ...prev];
            });
        },
        [productImages],
    );

    return (
        <>
            <SeoWrapper
                title={_product?.name || "Product Details"}
                description={_product?.short_description || "Product Details"}
                image={_product?.thumbnail}
            />
            <BaseLayout>
                <LayoutContainer>
                    <BreadcrumbWrapper
                        className="my-4"
                        items={[
                            {
                                title: "Products",
                                path: "/products",
                            },
                            { title: _product.name },
                        ]}
                    />

                    {isLoading ? (
                        <div className="flex justify-center items-center h-screen">
                            <Loading />
                        </div>
                    ) : (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-6 mb-6">
                                {/* Left - Sticky Main Image Desktop */}
                                <div className="hidden md:block md:col-span-1 lg:col-span-7 xl:col-span-5 md:sticky md:top-28 lg:sticky lg:top-28 h-fit">
                                    <div
                                        className="relative aspect-square md:aspect-[16/17] xl:aspect-[3/3.7] 2xl:aspect-[3/3] bg-white border rounded-3xl overflow-hidden cursor-zoom-in"
                                        onClick={() =>
                                            openModal(selectedImageIndex)
                                        }
                                    >
                                        <OptimizedImage
                                            src={currentImage.src}
                                            alt={currentImage.alt}
                                            className="absolute w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                                        />
                                    </div>

                                    {/* md and lg: horizontal thumbnail strip below main image */}
                                    <HorizontalGalleryStrip
                                        images={allImages}
                                        onOpenModal={openModal}
                                        selectedImageIndex={selectedImageIndex}
                                    />
                                </div>

                                {/* Middle - Scrollable Gallery (only on xl) */}
                                <div className="hidden xl:block xl:col-span-3">
                                    <div className="space-y-6 overflow-y-auto">
                                        {allImages?.map((img, idx) => (
                                            <button
                                                key={img.id}
                                                onClick={() => openModal(idx)}
                                                className={`w-full aspect-[3/2.5] rounded-3xl overflow-hidden bg-gray-50 border cursor-zoom-in`}
                                            >
                                                <OptimizedImage
                                                    src={img.src}
                                                    alt={img.alt}
                                                    className="w-full h-full object-cover hover:scale-105 duration-300 transition-all"
                                                />
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                {/* Main image Gallery for mobile */}
                                <ProductImageSliderMobile
                                    productImages={allImages}
                                    onOpenModal={openModal}
                                    selectedImageIndex={selectedImageIndex}
                                    setSelectedImageIndex={
                                        setSelectedImageIndex
                                    }
                                />

                                {/* Right - Sticky Product Info */}
                                <ProductInfo
                                    onVariantImage={addVariantImage}
                                    product={_product}
                                />
                            </div>
                            {/* Tabs */}
                            <ProductInfoTabs product={_product} />
                            {/* You may also like */}
                            {products?.length > 0 && (
                                <div className="mb-16: md:mb-20">
                                    <h2 className="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-foreground mt-1 transition-all duration-500 ease-out hover:tracking-wide hover:text-primary capitalize text-center mb-6 md:mb-8">
                                        You may also like
                                    </h2>

                                    <ProductLayout>
                                        {products?.map(
                                            (p: IProduct, i: number) => (
                                                <AnimationWrapper
                                                    key={p.id}
                                                    initial={{
                                                        opacity: 0,
                                                        y: 40,
                                                    }}
                                                    whileInView={{
                                                        opacity: 1,
                                                        y: 0,
                                                    }}
                                                    transition={{
                                                        duration: 0.35,
                                                        delay: i * 0.03,
                                                    }}
                                                >
                                                    <ProductCard
                                                        key={p.id}
                                                        p={p}
                                                    />
                                                </AnimationWrapper>
                                            ),
                                        )}
                                    </ProductLayout>
                                </div>
                            )}
                        </>
                    )}
                </LayoutContainer>
            </BaseLayout>

            <PhotoSwipeGallery
                images={allImages}
                isOpen={isModalOpen}
                index={selectedImageIndex}
                onClose={closeModal}
            />
        </>
    );
};
