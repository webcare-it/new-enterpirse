export const renderStars = (rating: number = 0) => {
    const full = Math.floor(rating);
    const hasHalf = rating % 1 >= 0.5;
    const empty = 5 - full - (hasHalf ? 1 : 0);

    return (
        <>
            {Array.from({ length: full }).map((_, i) => (
                <span key={`full-${i}`} className="text-amber-500">
                    &#9733;
                </span>
            ))}
            {hasHalf && (
                <span
                    key="half"
                    className="relative inline-block text-gray-300"
                >
                    <span className="text-gray-300">&#9733;</span>
                    <span className="absolute inset-0 overflow-hidden w-1/2">
                        <span className="text-amber-500">&#9733;</span>
                    </span>
                </span>
            )}
            {Array.from({ length: empty }).map((_, i) => (
                <span key={`empty-${i}`} className="text-gray-300">
                    &#9733;
                </span>
            ))}
        </>
    );
};
