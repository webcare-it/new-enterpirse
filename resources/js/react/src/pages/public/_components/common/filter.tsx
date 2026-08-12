import { Checkbox } from "@/components/ui/checkbox";
import { Slider } from "@/components/ui/slider";
import { ArrowUpDown, Star, Building2 } from "lucide-react";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import type { IBrand } from "@/type";
import { usePrice } from "@/hooks/usePrice";
import { useConfig } from "@/hooks/useConfig";

export interface ISubCategory {
    id: number;
    name: string;
    slug: string;
    image: string;
}

export type FiltersState = {
    minPrice: number;
    maxPrice: number;
    rating: number;
    sort: "select" | "newest" | "oldest" | "price-low" | "price-high";
    brands: string[];
    subCategories?: string[];
};

function FilterSection({
    icon: Icon,
    label,
    children,
}: {
    icon?: React.ComponentType<{ className?: string }>;
    label: string;
    children: React.ReactNode;
}) {
    return (
        <div>
            <div className="flex items-center gap-2 mb-2">
                {Icon ? (
                    <Icon className="size-4 text-gray-700" />
                ) : (
                    <svg
                        width="18"
                        height="18"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        className="text-gray-700"
                    >
                        <text
                            x="12"
                            y="12"
                            fontFamily="Arial Black, sans-serif"
                            fontSize="18"
                            fontWeight="900"
                            textAnchor="middle"
                            dominantBaseline="middle"
                            fill="currentColor"
                        >
                            ৳
                        </text>
                    </svg>
                )}

                <span className="text-sm font-medium uppercase tracking-relaxed text-gray-700">
                    {label}
                </span>
            </div>
            {children}
        </div>
    );
}

function RatingStars({ rating }: { rating: number }) {
    const filled = Math.floor(rating);
    return (
        <div className="flex text-amber-500 text-lg tracking-wider">
            {"★".repeat(filled)}
            {"☆".repeat(5 - filled)}
        </div>
    );
}

interface Props {
    subCategories?: ISubCategory[];
    filters: FiltersState;
    updateFilter: (key: string, value: unknown) => void;
    activeCount: number;
    clearAllFilters: () => void;
}

export const Filters = ({
    subCategories,
    filters,
    updateFilter,
    activeCount,
    clearAllFilters,
}: Props) => {
    const config = useConfig();
    const brands = (config?.brands as IBrand[]) || [];

    const { getPriceWithCurrency } = usePrice();
    return (
        <>
            <div className="flex items-center justify-between pb-2 border-b">
                <h2 className="text-xl font-semibold">Filters</h2>
                {activeCount > 0 && (
                    <button
                        onClick={clearAllFilters}
                        className="text-xs text-gray-600 hover:text-red-600 transition-colors underline underline-offset-2 cursor-pointer"
                    >
                        Clear all
                    </button>
                )}
            </div>

            {/* Sort */}
            <FilterSection icon={ArrowUpDown} label="Sort By">
                <Select
                    value={filters?.sort}
                    onValueChange={(value: string) =>
                        updateFilter("sort", value)
                    }
                >
                    <SelectTrigger className="w-full">
                        <SelectValue placeholder="Select" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Sort Options</SelectLabel>
                            <SelectItem value="select">Select</SelectItem>
                            <SelectItem value="newest">Newest</SelectItem>
                            <SelectItem value="oldest">Oldest</SelectItem>
                            <SelectItem value="price-low">
                                Price: Low to High
                            </SelectItem>
                            <SelectItem value="price-high">
                                Price: High to Low
                            </SelectItem>
                        </SelectGroup>
                    </SelectContent>
                </Select>
            </FilterSection>

            <div className="border-t" />

            {/* Price Range */}
            <FilterSection label="Price Range">
                <div className="px-1 pt-1">
                    <Slider
                        min={0}
                        max={50000}
                        step={100}
                        value={[filters?.minPrice, filters?.maxPrice]}
                        onValueChange={([min, max]) => {
                            updateFilter("minPrice", min);
                            updateFilter("maxPrice", max);
                        }}
                    />
                </div>
                <div className="flex items-center justify-between mt-3 gap-4">
                    <div className="flex-1 bg-muted rounded-md px-3 py-2 text-sm font-medium">
                        {getPriceWithCurrency(filters?.minPrice)}
                    </div>
                    <span className="text-muted-foreground text-xs">—</span>
                    <div className="flex-1 bg-muted rounded-md px-3 py-2 text-sm font-medium text-right">
                        {getPriceWithCurrency(filters?.maxPrice)}
                    </div>
                </div>
            </FilterSection>

            <div className="border-t" />

            {/* Ratings */}
            <FilterSection icon={Star} label="Ratings">
                <div>
                    {[4.5, 4, 3.5, 3].map((r) => {
                        const isActive = filters.rating === r;
                        return (
                            <div
                                key={r}
                                className="flex items-center gap-3 px-3 py-1 rounded-md cursor-pointer transition-all"
                                onClick={() =>
                                    updateFilter("rating", isActive ? 0 : r)
                                }
                            >
                                <Checkbox
                                    checked={isActive}
                                    className="size-5"
                                />
                                <RatingStars rating={r} />
                                <span className="text-sm text-gray-800">
                                    & up
                                </span>
                            </div>
                        );
                    })}
                </div>
            </FilterSection>

            {subCategories && subCategories.length > 0 && (
                <>
                    <div className="border-t" />

                    {/* Sub Categories */}
                    <FilterSection icon={Building2} label="Sub Categories">
                        <div
                            className="overflow-y-auto max-h-56"
                            style={{
                                scrollbarWidth: "none",
                                msOverflowStyle: "none",
                            }}
                        >
                            <div className="space-y-1 pr-1">
                                {subCategories?.map((sc) => {
                                    const isSelected =
                                        filters?.subCategories?.includes(
                                            sc?.slug,
                                        );
                                    return (
                                        <div
                                            key={sc?.id}
                                            className="flex items-center gap-3 px-3 py-2.5 rounded-md cursor-pointer transition-all"
                                            onClick={() => {
                                                if (isSelected) {
                                                    updateFilter(
                                                        "subCategories",
                                                        filters?.subCategories?.filter(
                                                            (s) =>
                                                                s !== sc?.slug,
                                                        ),
                                                    );
                                                } else {
                                                    updateFilter(
                                                        "subCategories",
                                                        [
                                                            ...(filters?.subCategories ||
                                                                []),
                                                            sc?.slug,
                                                        ],
                                                    );
                                                }
                                            }}
                                        >
                                            <Checkbox
                                                className="size-5"
                                                checked={isSelected}
                                            />
                                            <span className="text-sm">
                                                {sc?.name}
                                            </span>
                                        </div>
                                    );
                                })}
                            </div>
                        </div>
                    </FilterSection>
                </>
            )}

            <div className="border-t" />

            {/* Brands */}
            <FilterSection icon={Building2} label="Brands">
                <div
                    className="overflow-y-auto max-h-56"
                    style={{
                        scrollbarWidth: "none",
                        msOverflowStyle: "none",
                    }}
                >
                    <div className="space-y-1 pr-1">
                        {brands?.map((b) => {
                            const isSelected = filters.brands.includes(b.name);
                            return (
                                <div
                                    key={b.id}
                                    className="flex items-center gap-3 px-3 py-2.5 rounded-md cursor-pointer transition-all"
                                    onClick={() => {
                                        if (isSelected) {
                                            updateFilter(
                                                "brands",
                                                filters.brands.filter(
                                                    (s) => s !== b.name,
                                                ),
                                            );
                                        } else {
                                            updateFilter("brands", [
                                                ...filters.brands,
                                                b.name,
                                            ]);
                                        }
                                    }}
                                >
                                    <Checkbox
                                        className="size-5"
                                        checked={isSelected}
                                    />
                                    <span className="text-sm">{b.name}</span>
                                </div>
                            );
                        })}
                    </div>
                </div>
            </FilterSection>

            <div className="border-t" />

            <button
                onClick={clearAllFilters}
                className={`hidden py-2 px-4 cursor-pointer text-sm w-full rounded-full text-center text-primary-foreground md:flex items-center bg-primary border border-primary hover:bg-primary/90 justify-center gap-1`}
            >
                See Result
            </button>
        </>
    );
};
