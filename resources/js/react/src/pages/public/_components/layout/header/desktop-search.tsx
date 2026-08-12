import { ChevronDown, Loader2, Search, X } from "lucide-react";
import { useEffect, useRef, useState } from "react";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import type { ICategory } from "@/type";
import { useNavigate, useSearchParams } from "react-router-dom";
import { useConfig } from "@/hooks/useConfig";
import { useGetSearchSuggestions } from "@/api/product";

const getCategoryName = (categories: ICategory[], slug: string) => {
    const cat = categories.find((c) => c.slug === slug);
    return cat ? cat.name : "All";
};

const HighlightMatch = ({ text, query }: { text: string; query: string }) => {
    const q = query.trim();
    if (!q) return <span>{text}</span>;

    const lowerText = text.toLowerCase();
    const lowerQuery = q.toLowerCase();
    const idx = lowerText.indexOf(lowerQuery);

    if (idx === -1) return <span>{text}</span>;

    return (
        <span>
            <span>{text.slice(0, idx)}</span>
            <span className="font-semibold text-primary">
                {text.slice(idx, idx + q.length)}
            </span>
            <span>{text.slice(idx + q.length)}</span>
        </span>
    );
};

export const DesktopSearch = () => {
    const config = useConfig();
    const navigate = useNavigate();
    const [searchParams] = useSearchParams();
    const categories = (config?.categories as ICategory[]) || [];
    const [selected, setSelected] = useState({
        category: getCategoryName(categories, searchParams.get("c") ?? ""),
        slug: searchParams.get("c") ?? "",
        search: searchParams.get("q") ?? "",
    });

    const [debouncedSearch, setDebouncedSearch] = useState(selected.search);
    const [open, setOpen] = useState(false);
    const [activeIndex, setActiveIndex] = useState(-1);
    const wrapperRef = useRef<HTMLDivElement>(null);
    const listRef = useRef<HTMLUListElement>(null);

    const { data, isLoading, isFetching } =
        useGetSearchSuggestions(debouncedSearch);
    const suggestions: string[] = data?.data ?? [];
    const showDropdown = open && selected.search.trim().length > 0;
    const loading = isLoading || (isFetching && !!debouncedSearch.trim());

    useEffect(() => {
        const timer = setTimeout(
            () => setDebouncedSearch(selected.search),
            500,
        );
        return () => clearTimeout(timer);
    }, [selected.search]);

    useEffect(() => {
        setActiveIndex(-1);
    }, [debouncedSearch]);

    useEffect(() => {
        const onClickOutside = (e: MouseEvent) => {
            if (
                wrapperRef.current &&
                !wrapperRef.current.contains(e.target as Node)
            ) {
                setOpen(false);
                setActiveIndex(-1);
            }
        };
        document.addEventListener("mousedown", onClickOutside);
        return () => document.removeEventListener("mousedown", onClickOutside);
    }, []);

    useEffect(() => {
        if (activeIndex < 0 || !listRef.current) return;
        const el = listRef.current.querySelector<HTMLElement>(
            `[data-index="${activeIndex}"]`,
        );
        el?.scrollIntoView({ block: "nearest" });
    }, [activeIndex]);

    const handleSearch = (searchOverride?: string, slugOverride?: string) => {
        const q = (searchOverride ?? selected.search).trim();
        const c = (slugOverride ?? selected.slug).trim();
        setOpen(false);
        setActiveIndex(-1);
        if (!q && !c) {
            return;
        }
        const params = new URLSearchParams();
        if (q) params.set("q", q);
        if (c) params.set("c", c);
        navigate(`/search?${params.toString()}`);
    };

    const handleSelect = (category: string, slug: string) => {
        setSelected((prev) => ({ category, slug, search: prev.search }));
        handleSearch(undefined, slug);
    };

    const handleReset = () => {
        navigate("/");
    };

    const handleSelectSuggestion = (keyword: string) => {
        setSelected({ ...selected, search: keyword });
        handleSearch(keyword);
    };

    const onKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === "Escape") {
            setOpen(false);
            setActiveIndex(-1);
            return;
        }

        if (!showDropdown || loading || suggestions.length === 0) {
            if (e.key === "Enter") handleSearch();
            return;
        }

        if (e.key === "ArrowDown") {
            e.preventDefault();
            setActiveIndex((i) => (i + 1) % suggestions.length);
            setOpen(true);
        } else if (e.key === "ArrowUp") {
            e.preventDefault();
            setActiveIndex((i) => (i <= 0 ? suggestions.length - 1 : i - 1));
            setOpen(true);
        } else if (e.key === "Enter") {
            if (activeIndex >= 0 && activeIndex < suggestions.length) {
                e.preventDefault();
                handleSelectSuggestion(suggestions[activeIndex]);
            } else {
                handleSearch();
            }
        }
    };

    return (
        <div ref={wrapperRef} className="relative flex-1 max-w-2xl">
            <div className="flex items-center bg-muted/30 rounded-xl overflow-hidden border border-primary focus-within:border-primary/50 focus-within:ring-1 focus-within:ring-primary/20 transition">
                <DropdownMenu>
                    <DropdownMenuTrigger
                        asChild
                        className="bg-primary text-primary-foreground"
                    >
                        <button
                            type="button"
                            aria-label={`Filter by category: ${selected?.category}`}
                            className="flex items-center gap-1.5 pl-4 pr-3 h-11 border-r border-gray-300 cursor-pointer bg-primary text-primary-foreground transition text-sm"
                        >
                            <span className="font-medium">
                                {selected?.category}
                            </span>
                            <ChevronDown className="w-3.5 h-3.5" />
                        </button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent className="w-40" align="start">
                        <DropdownMenuGroup>
                            <DropdownMenuLabel>
                                All Categories
                            </DropdownMenuLabel>
                            {categories?.map((c) => (
                                <DropdownMenuItem
                                    key={c?.name}
                                    className={`${c?.slug === selected?.slug ? "bg-primary/20 text-primary cursor-not-allowed" : ""}`}
                                    disabled={c?.slug === selected?.slug}
                                    onClick={() => {
                                        handleSelect(c?.name, c?.slug);
                                    }}
                                >
                                    {c?.name}
                                </DropdownMenuItem>
                            ))}
                            <DropdownMenuItem
                                onClick={handleReset}
                                className="text-red-600 hover:text-red-500 bg-red-50"
                            >
                                Reset
                            </DropdownMenuItem>
                        </DropdownMenuGroup>
                    </DropdownMenuContent>
                </DropdownMenu>

                <div className="relative flex-1 flex items-center">
                    <input
                        type="text"
                        placeholder="Search products..."
                        value={selected.search}
                        onChange={(e) => {
                            setSelected({
                                ...selected,
                                search: e.target.value,
                            });
                            setOpen(true);
                        }}
                        onFocus={() => setOpen(true)}
                        onKeyDown={onKeyDown}
                        aria-autocomplete="list"
                        aria-expanded={showDropdown}
                        aria-controls="search-suggestions-list"
                        className="flex-1 h-10 px-3 pr-9 bg-transparent text-foreground placeholder-muted-foreground outline-none text-sm"
                    />
                    {selected.search && (
                        <button
                            type="button"
                            onClick={() => {
                                setSelected({ ...selected, search: "" });
                                handleSearch("");
                            }}
                            aria-label="Clear search"
                            className="absolute right-3 top-1/2 -translate-y-1/2 text-red-600 hover:text-red-500 transition cursor-pointer"
                        >
                            <X className="size-5" />
                        </button>
                    )}
                </div>
                <button
                    onClick={() => handleSearch()}
                    aria-label="Search products"
                    className="h-11 px-5 bg-primary border border-primary text-primary-foreground hover:bg-primary/90 transition flex items-center cursor-pointer"
                >
                    <svg
                        className="icon icon-search icon-lg"
                        width="20"
                        height="20"
                        strokeWidth="2"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        role="presentation"
                    >
                        <path
                            strokeLinecap="round"
                            d="m21 21-3.636-3.636m0 0A9 9 0 1 0 4.636 4.636a9 9 0 0 0 12.728 12.728Z"
                        ></path>
                    </svg>
                </button>
            </div>

            {showDropdown && (
                <div className="absolute left-0 right-0 top-full mt-1 z-[60] bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden">
                    {loading ? (
                        <div className="flex items-center justify-center gap-2 px-4 py-3 text-sm text-gray-500">
                            <Loader2 className="size-4 animate-spin" />
                            Loading suggestions...
                        </div>
                    ) : suggestions.length > 0 ? (
                        <ul
                            ref={listRef}
                            id="search-suggestions-list"
                            role="listbox"
                            style={{
                                scrollbarWidth: "none",
                                msOverflowStyle: "none",
                            }}
                            className="max-h-80 overflow-y-auto py-1"
                        >
                            {suggestions.map((keyword, i) => {
                                const isActive = i === activeIndex;
                                return (
                                    <li
                                        key={`${keyword}-${i}`}
                                        role="option"
                                        aria-selected={isActive}
                                        data-index={i}
                                    >
                                        <button
                                            type="button"
                                            onMouseDown={(e) =>
                                                e.preventDefault()
                                            }
                                            onMouseEnter={() =>
                                                setActiveIndex(i)
                                            }
                                            onClick={() =>
                                                handleSelectSuggestion(keyword)
                                            }
                                            className={`w-full flex items-center gap-3 px-4 py-2.5 text-sm text-left transition-colors ${
                                                isActive
                                                    ? "bg-primary/10 text-primary"
                                                    : "text-gray-700 hover:bg-gray-50"
                                            }`}
                                        >
                                            <Search
                                                className={`size-4 shrink-0 ${isActive ? "text-primary" : "text-gray-400"}`}
                                            />
                                            <span className="flex-1 truncate">
                                                <HighlightMatch
                                                    text={keyword}
                                                    query={selected.search}
                                                />
                                            </span>
                                            {isActive && (
                                                <span className="text-[10px] uppercase tracking-wider text-gray-400 shrink-0">
                                                    ↵
                                                </span>
                                            )}
                                        </button>
                                    </li>
                                );
                            })}
                        </ul>
                    ) : (
                        <div className="flex items-center gap-3 px-4 py-4 text-sm text-gray-500">
                            <Search className="size-4 shrink-0 text-gray-400" />
                            <span>
                                No suggestions for "
                                <span className="font-medium text-gray-700">
                                    {selected.search}
                                </span>
                                ". Press Enter to search anyway.
                            </span>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};
