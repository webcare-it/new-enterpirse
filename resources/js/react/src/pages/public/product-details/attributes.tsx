import { OptimizedImage } from "@/components/common/optimized-image";
import { TooltipWrapper } from "@/components/common/tooltip-wrapper";
import type { IAttribute, IAttributeOption } from "./type";

interface Props {
    attributes: IAttribute[];
    select: Record<string, string | undefined>;
    onSelect: (attrName: string, option: IAttributeOption) => void;
}

export const ProductAttributes = ({ attributes, select, onSelect }: Props) => {
    if (!attributes?.length) return null;

    return (
        <>
            {attributes?.map((attr, id) => {
                const isColor = attr?.name?.toLowerCase() === "color";

                return (
                    <div key={id} className="mb-5">
                        <p className="font-medium mb-3 capitalize">
                            {attr?.name}:{" "}
                            <span className="text-primary font-semibold">
                                {select[attr?.name] || `Select ${attr.name}`}
                            </span>
                        </p>

                        <div
                            className={`flex flex-wrap ${
                                isColor ? "gap-3 md:gap-4" : "gap-2 md:gap-3"
                            }`}
                        >
                            {attr?.values?.map((item) => {
                                if (!item || !item?.value) return null;

                                const isSelected =
                                    select[attr?.name] === item?.value;
                                const key = `${attr?.name}-${item?.value}`;

                                if (isColor) {
                                    return (
                                        <TooltipWrapper
                                            key={key}
                                            text={item?.value}
                                        >
                                            {item?.image ? (
                                                <button
                                                    onClick={() =>
                                                        onSelect(
                                                            attr?.name,
                                                            item,
                                                        )
                                                    }
                                                    className={`size-16 rounded-lg overflow-hidden transition-all cursor-pointer border-2 ${
                                                        isSelected
                                                            ? "border-primary scale-105"
                                                            : "border-white"
                                                    }`}
                                                >
                                                    <OptimizedImage
                                                        src={item?.image || ""}
                                                        alt={item?.value}
                                                        className="w-full h-full object-cover"
                                                    />
                                                </button>
                                            ) : (
                                                <button
                                                    onClick={() =>
                                                        onSelect(
                                                            attr?.name,
                                                            item,
                                                        )
                                                    }
                                                    className={`h-10 px-4 rounded-lg border text-sm md:text-base font-medium transition-all cursor-pointer ${
                                                        isSelected
                                                            ? "border-primary bg-primary/10 text-primary"
                                                            : "border-gray-200 bg-white text-gray-700 hover:border-gray-300"
                                                    }`}
                                                >
                                                    {item?.value}
                                                </button>
                                            )}
                                        </TooltipWrapper>
                                    );
                                }

                                return (
                                    <TooltipWrapper
                                        key={key}
                                        text={item?.value}
                                    >
                                        <button
                                            onClick={() =>
                                                onSelect(attr?.name, item)
                                            }
                                            className={`h-10 px-4 rounded-lg border text-sm md:text-base font-medium transition-all cursor-pointer ${
                                                isSelected
                                                    ? "border-primary bg-primary/10 text-primary"
                                                    : "border-gray-200 bg-white text-gray-700 hover:border-gray-300"
                                            }`}
                                        >
                                            {item?.value}
                                        </button>
                                    </TooltipWrapper>
                                );
                            })}
                        </div>
                    </div>
                );
            })}
        </>
    );
};
