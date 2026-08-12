<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RAZIN | Premium Panjabi</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css" />

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .serif {
            font-family: Georgia, "Times New Roman", serif;
        }

        .hero-bg {
            background:
                radial-gradient(circle at 80% 20%,
                    rgba(210, 174, 104, .16),
                    transparent 30%),
                linear-gradient(135deg,
                    #171b18 0%,
                    #252d27 50%,
                    #111411 100%);
        }

        .gold-gradient {
            background: linear-gradient(135deg,
                    #d7b76a,
                    #a87b31);
        }

        .soft-shadow {
            box-shadow: 0 25px 70px rgba(0, 0, 0, .12);
        }

        .order-shadow {
            box-shadow:
                0 25px 80px rgba(20, 25, 21, .12);
        }
    </style>
</head>

<body class="bg-[#f8f6f1] text-[#202620] m-0 p-0">


    {{-- =========================================================
        ANNOUNCEMENT
    ========================================================== --}}
    <div class="bg-[#151a16] text-white text-center text-xs sm:text-sm py-2.5 px-4">
        ✦ ঈদ স্পেশাল কালেকশন | সারাদেশে ক্যাশ অন ডেলিভারি ✦
    </div>


    {{-- =========================================================
        NAVBAR
    ========================================================== --}}
    <header class="bg-white/95 backdrop-blur border-b border-black/5">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="h-20 flex items-center justify-center">

                <a href="#" class="serif text-2xl sm:text-3xl tracking-[.12em] font-semibold">
                    RAZIN
                </a>

            </div>

        </div>

    </header>


    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="hero-bg text-white overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[680px] py-16 lg:py-20">

                {{-- Content --}}

                <div>

                    <div
                        class="inline-flex items-center gap-2 border border-white/15 bg-white/5 rounded-full px-4 py-2 text-xs sm:text-sm">

                        <span class="w-2 h-2 rounded-full bg-[#d2ad61]"></span>

                        Premium Panjabi Collection 2026

                    </div>


                    <h1 class="serif text-5xl sm:text-6xl lg:text-7xl leading-[1.04] mt-7">

                        Tradition

                        <span class="block text-[#d2ad61]">
                            With Character.
                        </span>

                    </h1>


                    <p class="text-white/65 text-base sm:text-lg leading-8 max-w-xl mt-7">

                        Crafted for the modern man who appreciates
                        timeless Bengali tradition, premium fabric and
                        effortless elegance.

                    </p>


                    <div class="flex flex-col sm:flex-row gap-4 mt-9">

                        <a href="#order"
                            class="inline-flex justify-center items-center bg-[#d2ad61] text-[#171b18] px-7 py-4 rounded-full font-semibold hover:bg-[#e3c77f] transition">
                            Order Your Panjabi
                            <span class="ml-2">→</span>
                        </a>

                        <a href="#details"
                            class="inline-flex justify-center items-center border border-white/20 px-7 py-4 rounded-full hover:bg-white/10 transition">
                            Explore Details
                        </a>

                    </div>


                    <div class="flex gap-8 mt-12">

                        <div>

                            <p class="text-2xl font-semibold">
                                5K+
                            </p>

                            <p class="text-xs text-white/45 mt-1">
                                Happy Customers
                            </p>

                        </div>


                        <div class="w-px bg-white/15"></div>


                        <div>

                            <p class="text-2xl font-semibold">
                                4.9/5
                            </p>

                            <p class="text-xs text-white/45 mt-1">
                                Customer Rating
                            </p>

                        </div>


                        <div class="w-px bg-white/15"></div>


                        <div>

                            <p class="text-2xl font-semibold">
                                COD
                            </p>

                            <p class="text-xs text-white/45 mt-1">
                                Available
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Hero Product --}}

                <div class="relative">

                    <div class="absolute -inset-8 bg-[#c9a65b]/10 blur-3xl rounded-full"></div>

                    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/5] soft-shadow">

                        <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1000&q=85"
                            alt="Premium Panjabi" class="w-full h-full object-cover">

                        <div class="absolute bottom-5 left-5 right-5">

                            <div class="bg-black/65 backdrop-blur-xl rounded-2xl p-5">

                                <div class="flex items-center justify-between">

                                    <div>

                                        <p class="text-xs text-white/50">
                                            Featured
                                        </p>

                                        <p class="font-semibold mt-1">
                                            Classic Premium Panjabi
                                        </p>

                                    </div>

                                    <p class="text-xl font-semibold">
                                        ৳1,490
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        TRUST BAR
    ========================================================== --}}
    <section class="bg-white border-b border-black/5">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 md:grid-cols-4">

                <div class="text-center py-7">

                    <div class="text-xl">
                        ✦
                    </div>

                    <p class="font-semibold text-sm mt-2">
                        Premium Fabric
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Soft & Comfortable
                    </p>

                </div>


                <div class="text-center py-7">

                    <div class="text-xl">
                        ✓
                    </div>

                    <p class="font-semibold text-sm mt-2">
                        Perfect Fit
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Multiple Sizes
                    </p>

                </div>


                <div class="text-center py-7">

                    <div class="text-xl">
                        🚚
                    </div>

                    <p class="font-semibold text-sm mt-2">
                        Fast Delivery
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        All Over Bangladesh
                    </p>

                </div>


                <div class="text-center py-7">

                    <div class="text-xl">
                        ৳
                    </div>

                    <p class="font-semibold text-sm mt-2">
                        Cash On Delivery
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Available Nationwide
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        COLLECTION
    ========================================================== --}}
    <section id="collection" class="py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}

            <div class="text-center max-w-2xl mx-auto">

                <span class="text-[#a17b39] text-xs font-bold uppercase tracking-[.25em]">
                    The Collection
                </span>

                <h2 class="serif text-4xl sm:text-5xl mt-4">
                    Crafted For Every Occasion
                </h2>

                <p class="text-gray-500 mt-5 leading-7">
                    From Eid prayers to family gatherings,
                    find the perfect Panjabi for every moment.
                </p>

            </div>


            {{-- =================================================
            COLLECTION GRID
        ================================================== --}}

            <div class="grid md:grid-cols-3 gap-6 mt-14">


                {{-- =================================================
                PRODUCT 1
            ================================================== --}}

                <div class="group">

                    {{-- Image --}}

                    <a href="https://saralifestyle.com/_next/image?url=https%3A%2F%2Fprod.saralifestyle.com%2FImages%2FProducts%2FVariationWisetImage%2F3314ddcdc4204f65a3987fa039290f8c.jpeg&w=1080&q=75"
                        data-fancybox="panjabi-gallery" data-caption="Classic Panjabi — Everyday elegance"
                        class="block aspect-[4/5] rounded-3xl overflow-hidden cursor-zoom-in">

                        <img src="https://saralifestyle.com/_next/image?url=https%3A%2F%2Fprod.saralifestyle.com%2FImages%2FProducts%2FVariationWisetImage%2F3314ddcdc4204f65a3987fa039290f8c.jpeg&w=1080&q=75"
                            alt="Classic Panjabi"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </a>


                    {{-- Product Info --}}

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="font-semibold text-lg mt-5">
                                Classic Panjabi
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Everyday elegance
                            </p>

                        </div>


                        {{-- Order Button --}}

                        <a href="#order"
                            class="inline-flex justify-center items-center bg-[#d2ad61] text-[#171b18] size-12 rounded-full font-bold hover:bg-[#e3c77f] transition"
                            aria-label="Order Classic Panjabi">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="currentColor">

                                <path
                                    d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.2761905 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z" />

                            </svg>

                        </a>

                    </div>

                </div>


                {{-- =================================================
                PRODUCT 2
            ================================================== --}}

                <div class="group">

                    {{-- Image --}}

                    <a href="https://www.shoppersbd.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/p/sp2187a.jpg"
                        data-fancybox="panjabi-gallery" data-caption="Eid Collection — Made for special moments"
                        class="block aspect-[4/5] rounded-3xl overflow-hidden cursor-zoom-in">

                        <img src="https://www.shoppersbd.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/p/sp2187a.jpg"
                            alt="Eid Panjabi"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </a>


                    {{-- Product Info --}}

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="font-semibold text-lg mt-5">
                                Eid Collection
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Made for special moments
                            </p>

                        </div>


                        <a href="#order"
                            class="inline-flex justify-center items-center bg-[#d2ad61] text-[#171b18] size-12 rounded-full font-bold hover:bg-[#e3c77f] transition"
                            aria-label="Order Eid Collection">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="currentColor">

                                <path
                                    d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.2761905 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z" />

                            </svg>

                        </a>

                    </div>

                </div>


                {{-- =================================================
                PRODUCT 3
            ================================================== --}}

                <div class="group">

                    {{-- Image --}}

                    <a href="https://www.siwaklifestyle.com/media/2026/06/SJQ3023-3.webp"
                        data-fancybox="panjabi-gallery" data-caption="Premium Collection — Refined & luxurious"
                        class="block aspect-[4/5] rounded-3xl overflow-hidden cursor-zoom-in">

                        <img src="https://www.siwaklifestyle.com/media/2026/06/SJQ3023-3-320x480.webp"
                            alt="Premium Panjabi"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </a>


                    {{-- Product Info --}}

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="font-semibold text-lg mt-5">
                                Premium Collection
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Refined & luxurious
                            </p>

                        </div>


                        <a href="#order"
                            class="inline-flex justify-center items-center bg-[#d2ad61] text-[#171b18] size-12 rounded-full font-bold hover:bg-[#e3c77f] transition"
                            aria-label="Order Premium Collection">

                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 24 24" fill="currentColor">

                                <path
                                    d="M 12 1 C 8.6761905 1 6 3.6761905 6 7 L 6 8 C 4.9069372 8 4 8.9069372 4 10 L 4 20 C 4 21.093063 4.9069372 22 6 22 L 18 22 C 19.093063 22 20 21.093063 20 20 L 20 10 C 20 8.9069372 19.093063 8 18 8 L 18 7 C 18 3.6761905 15.32381 1 12 1 z M 12 3 C 14.2761905 3 16 4.7238095 16 7 L 16 8 L 8 8 L 8 7 C 8 4.7238095 9.7238095 3 12 3 z M 6 10 L 18 10 L 18 20 L 6 20 L 6 10 z M 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 L 12 13 C 10.9 13 10 13.9 10 15 C 10 16.1 10.9 17 12 17 C 13.1 17 14 16.1 14 15 C 14 13.9 13.1 13 12 13 z" />

                            </svg>

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        PRODUCT DETAILS
    ========================================================== --}}
    <section id="details" class="bg-[#eeeae0] py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-14 items-center">

                <div class="rounded-[2.5rem] overflow-hidden">

                    <img src="https://images.unsplash.com/photo-1603252110481-7ba873bf42ab?auto=format&fit=crop&w=1000&q=85"
                        alt="Premium Panjabi" class="w-full aspect-square object-cover">

                </div>


                <div>

                    <span class="text-[#a17b39] text-xs font-bold uppercase tracking-[.25em]">
                        Product Details
                    </span>

                    <h2 class="serif text-4xl sm:text-5xl mt-4">
                        Premium Classic Panjabi
                    </h2>


                    <div class="flex items-center gap-4 mt-6">

                        <span class="text-3xl font-bold">
                            ৳1,490
                        </span>

                        <span class="text-gray-400 line-through">
                            ৳1,790
                        </span>

                        <span class="bg-[#202820] text-white text-xs px-3 py-1.5 rounded-full">
                            SAVE ৳300
                        </span>

                    </div>


                    <p class="text-gray-600 leading-8 mt-6">
                        A timeless Panjabi crafted from premium,
                        breathable fabric. Designed with a clean silhouette,
                        refined details and a comfortable fit.
                    </p>


                    <div class="grid sm:grid-cols-2 gap-4 mt-8">

                        <div class="bg-white rounded-2xl p-5">

                            <p class="text-xs text-gray-400">
                                Fabric
                            </p>

                            <p class="font-semibold mt-2">
                                Premium Cotton
                            </p>

                        </div>


                        <div class="bg-white rounded-2xl p-5">

                            <p class="text-xs text-gray-400">
                                Fit
                            </p>

                            <p class="font-semibold mt-2">
                                Regular Comfort Fit
                            </p>

                        </div>


                        <div class="bg-white rounded-2xl p-5">

                            <p class="text-xs text-gray-400">
                                Sleeve
                            </p>

                            <p class="font-semibold mt-2">
                                Full Sleeve
                            </p>

                        </div>


                        <div class="bg-white rounded-2xl p-5">

                            <p class="text-xs text-gray-400">
                                Occasion
                            </p>

                            <p class="font-semibold mt-2">
                                Eid / Casual / Formal
                            </p>

                        </div>

                    </div>

                    <a href="#order"
                        class="inline-flex justify-center items-center mt-4 md:mt-8 bg-[#d2ad61] text-[#171b18] px-7 py-4 rounded-full font-semibold hover:bg-[#e3c77f] transition">
                        Order Now
                        <span class="ml-2">→</span>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        SIZE GUIDE
    ========================================================== --}}
    <section id="size-guide" class="py-20 bg-white">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center">

                <span class="text-[#a17b39] text-xs font-bold uppercase tracking-[.25em]">
                    Size Guide
                </span>

                <h2 class="serif text-4xl mt-4">
                    Find Your Perfect Fit
                </h2>

            </div>


            <div class="mt-10 rounded-3xl overflow-hidden border border-black/5">

                <div class="grid grid-cols-3 bg-[#202820] text-white text-sm font-semibold">

                    <div class="p-4">
                        Size
                    </div>

                    <div class="p-4">
                        Chest
                    </div>

                    <div class="p-4">
                        Length
                    </div>

                </div>


                <div class="divide-y divide-gray-100">

                    <div class="grid grid-cols-3 text-sm">

                        <div class="p-4 font-semibold">M</div>
                        <div class="p-4 text-gray-500">40"</div>
                        <div class="p-4 text-gray-500">40"</div>

                    </div>


                    <div class="grid grid-cols-3 text-sm">

                        <div class="p-4 font-semibold">L</div>
                        <div class="p-4 text-gray-500">42"</div>
                        <div class="p-4 text-gray-500">41"</div>

                    </div>


                    <div class="grid grid-cols-3 text-sm">

                        <div class="p-4 font-semibold">XL</div>
                        <div class="p-4 text-gray-500">44"</div>
                        <div class="p-4 text-gray-500">42"</div>

                    </div>


                    <div class="grid grid-cols-3 text-sm">

                        <div class="p-4 font-semibold">XXL</div>
                        <div class="p-4 text-gray-500">46"</div>
                        <div class="p-4 text-gray-500">43"</div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
        ORDER SECTION
    ========================================================== --}}
    <section id="order" class="py-20 lg:py-28 bg-[#faf9f6]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto">

                <span class="text-[#a17b39] text-xs font-bold uppercase tracking-[.25em]">
                    Place Your Order
                </span>

                <h2 class="serif text-4xl sm:text-5xl mt-4">
                    Get Your Panjabi Today
                </h2>

                <p class="text-gray-500 mt-4">
                    Fill in your information below and choose
                    your preferred delivery and payment method.
                </p>

            </div>


            <form action="#" method="POST" class="mt-14">

                @csrf

                <div class="grid lg:grid-cols-[1fr_380px] gap-8">


                    {{-- =================================================
                        LEFT: FORM
                    ================================================== --}}

                    <div class="space-y-6">


                        {{-- Product Selection --}}

                        <div class="bg-white rounded-3xl border border-black/5 p-4 sm:p-8">

                            <div class="flex items-center justify-between">


                                <h3 class="font-semibold text-xl mt-1">
                                    Select Your Panjabi
                                </h3>

                                <span class="text-[#a17b39]">
                                    Product
                                </span>

                            </div>


                            {{-- Color --}}

                            <div class="mt-7">

                                <label class="text-sm font-semibold">
                                    Color
                                </label>

                                <div class="flex flex-wrap gap-3 mt-3">

                                    <label class="cursor-pointer">

                                        <input type="radio" name="color" value="Black" class="peer sr-only"
                                            checked>

                                        <span
                                            class="block px-5 py-2.5 rounded-full border border-gray-200 text-sm peer-checked:bg-[#202820] peer-checked:text-white peer-checked:border-[#202820]">
                                            Black
                                        </span>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="color" value="White" class="peer sr-only">

                                        <span
                                            class="block px-5 py-2.5 rounded-full border border-gray-200 text-sm peer-checked:bg-[#202820] peer-checked:text-white peer-checked:border-[#202820]">
                                            White
                                        </span>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="color" value="Navy" class="peer sr-only">

                                        <span
                                            class="block px-5 py-2.5 rounded-full border border-gray-200 text-sm peer-checked:bg-[#202820] peer-checked:text-white peer-checked:border-[#202820]">
                                            Navy
                                        </span>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="color" value="Olive" class="peer sr-only">

                                        <span
                                            class="block px-5 py-2.5 rounded-full border border-gray-200 text-sm peer-checked:bg-[#202820] peer-checked:text-white peer-checked:border-[#202820]">
                                            Olive
                                        </span>

                                    </label>

                                </div>

                            </div>


                            {{-- Size --}}

                            <div class="mt-7">

                                <div class="flex justify-between">

                                    <label class="text-sm font-semibold">
                                        Size
                                    </label>

                                    <a href="#size-guide" class="text-xs text-[#a17b39] underline">
                                        Size Guide
                                    </a>

                                </div>


                                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 mt-3">

                                    @foreach (['M', 'L', 'XL', 'XXL', '3XL', '4XL'] as $size)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="size" value="{{ $size }}"
                                                class="peer sr-only" @checked($size === 'L')>

                                            <span
                                                class="flex justify-center py-3 rounded-xl border border-gray-200 text-sm font-medium peer-checked:bg-[#202820] peer-checked:text-white peer-checked:border-[#202820]">
                                                {{ $size }}
                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>


                            {{-- Quantity --}}

                            <div class="mt-7">

                                <label class="text-sm font-semibold">
                                    Quantity
                                </label>

                                <div
                                    class="flex items-center border border-gray-200 rounded-xl w-fit mt-3 overflow-hidden">

                                    <button type="button" onclick="changeQuantity(-1)"
                                        class="w-12 h-12 hover:bg-gray-50">
                                        −
                                    </button>

                                    <input id="quantity" name="quantity" value="1" min="1"
                                        max="10" readonly
                                        class="w-12 h-12 text-center border-x border-gray-200 outline-none">

                                    <button type="button" onclick="changeQuantity(1)"
                                        class="w-12 h-12 hover:bg-gray-50">
                                        +
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- Customer Information --}}

                        <div class="bg-white rounded-3xl border border-black/5 p-4 sm:p-8">

                            <div>
                                <h3 class="font-semibold text-xl mt-1">
                                    Customer Information
                                </h3>

                            </div>


                            <div class="grid grid-cols-1 gap-5 mt-7">

                                <div class="">

                                    <label class="text-sm font-medium">
                                        Full Name
                                    </label>

                                    <input type="text" name="name" required placeholder="Enter your full name"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-gray-200 outline-none focus:border-[#a17b39] transition">

                                </div>
                                <div class="">

                                    <label class="text-sm font-medium">
                                        Phone Number
                                    </label>

                                    <input type="text" name="phone" required
                                        placeholder="Enter your phone number"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-gray-200 outline-none focus:border-[#a17b39] transition">

                                </div>


                                <div class="">

                                    <label class="text-sm font-medium">
                                        Full Address
                                    </label>

                                    <textarea name="address" rows="3" required placeholder="House, road, area, landmark..."
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-gray-200 outline-none focus:border-[#a17b39] transition resize-none"></textarea>

                                </div>
                                <div class="">

                                    <label class="text-sm font-medium">
                                        Note
                                    </label>

                                    <textarea name="note" rows="3" placeholder="Any additional notes or instructions..."
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-gray-200 outline-none focus:border-[#a17b39] transition resize-none"></textarea>

                                </div>

                                <div>
                                    <h3 class="font-semibold text-xl mt-1">
                                        Shipping Method
                                    </h3>

                                </div>


                                <div class="grid sm:grid-cols-2 gap-4">


                                    <label class="cursor-pointer">

                                        <input type="radio" name="shipping" value="inside_dhaka" data-charge="80"
                                            class="peer sr-only" checked onchange="updateOrderSummary()">

                                        <div
                                            class="border border-gray-200 rounded-2xl p-5 peer-checked:border-[#202820] peer-checked:bg-[#f4f5f2]">

                                            <div class="flex justify-between">

                                                <div>

                                                    <p class="font-semibold">
                                                        Inside Dhaka
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Delivery within 1–2 days
                                                    </p>

                                                </div>

                                                <span class="font-semibold">
                                                    ৳80
                                                </span>

                                            </div>

                                        </div>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="shipping" value="outside_dhaka"
                                            data-charge="130" class="peer sr-only" onchange="updateOrderSummary()">

                                        <div
                                            class="border border-gray-200 rounded-2xl p-5 peer-checked:border-[#202820] peer-checked:bg-[#f4f5f2]">

                                            <div class="flex justify-between">

                                                <div>

                                                    <p class="font-semibold">
                                                        Outside Dhaka
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Delivery within 2–4 days
                                                    </p>

                                                </div>

                                                <span class="font-semibold">
                                                    ৳130
                                                </span>

                                            </div>

                                        </div>

                                    </label>

                                </div>

                                <div>
                                    <h3 class="font-semibold text-xl mt-1">
                                        Payment Method
                                    </h3>

                                </div>


                                <div class="grid sm:grid-cols-3 gap-4">


                                    <label class="cursor-pointer">

                                        <input type="radio" name="payment_method" value="cod"
                                            class="peer sr-only" checked>

                                        <div
                                            class="border border-gray-200 rounded-2xl p-2 text-center peer-checked:border-[#202820] peer-checked:bg-[#f4f5f2]">

                                            <div class="text-2xl">
                                                💵
                                            </div>

                                            <p class="font-semibold text-sm">
                                                Cash on Delivery
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                Pay on delivery
                                            </p>

                                        </div>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="payment_method" value="bkash"
                                            class="peer sr-only">

                                        <div
                                            class="border border-gray-200 rounded-2xl p-2 text-center peer-checked:border-[#202820] peer-checked:bg-[#f4f5f2]">

                                            <div class="text-2xl">
                                                💵
                                            </div>

                                            <p class="font-semibold text-sm mt-3">
                                                bKash
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                Mobile payment
                                            </p>

                                        </div>

                                    </label>


                                    <label class="cursor-pointer">

                                        <input type="radio" name="payment_method" value="nagad"
                                            class="peer sr-only">

                                        <div
                                            class="border border-gray-200 rounded-2xl p-2 text-center peer-checked:border-[#202820] peer-checked:bg-[#f4f5f2]">

                                            <div class="text-2xl">
                                                💵
                                            </div>

                                            <p class="font-semibold text-sm mt-3">
                                                Nagad
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                Mobile payment
                                            </p>

                                        </div>

                                    </label>

                                </div>

                            </div>

                        </div>



                    </div>


                    {{-- =================================================
                        RIGHT: ORDER SUMMARY
                    ================================================== --}}

                    <div>

                        <div class="bg-[#202820] text-white rounded-3xl p-4 sm:p-8 lg:sticky lg:top-28">

                            <p class="text-xs uppercase tracking-[.2em] text-white/40">
                                Your Order
                            </p>

                            <h3 class="serif text-3xl mt-2">
                                Order Summary
                            </h3>


                            <div class="flex gap-4 mt-7 pb-6 border-b border-white/10">

                                <div class="size-20 rounded-xl overflow-hidden shrink-0">

                                    <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=300&q=80"
                                        alt="Panjabi" class="w-full h-full object-cover">

                                </div>


                                <div class="flex-1">

                                    <p class="font-semibold">
                                        Premium Classic Panjabi
                                    </p>

                                    <p class="text-sm text-white/50 mt-1">
                                        Color: <span id="summaryColor">Black</span>
                                    </p>

                                    <p class="text-sm text-white/50">
                                        Size: <span id="summarySize">L</span>
                                    </p>

                                    <p class="text-sm text-white/50">
                                        Qty: <span id="summaryQuantity">1</span>
                                    </p>

                                </div>

                                <p class="font-semibold">
                                    ৳<span id="productTotal">1490</span>
                                </p>

                            </div>


                            <div class="space-y-4 py-6 border-b border-white/10">

                                <div class="flex justify-between text-sm">

                                    <span class="text-white/50">
                                        Subtotal
                                    </span>

                                    <span>
                                        ৳<span id="subtotal">1490</span>
                                    </span>

                                </div>


                                <div class="flex justify-between text-sm">

                                    <span class="text-white/50">
                                        Shipping
                                    </span>

                                    <span>
                                        ৳<span id="shippingCharge">80</span>
                                    </span>

                                </div>

                            </div>


                            <div class="flex justify-between items-end py-6">

                                <div>

                                    <p class="text-xs text-white/40">
                                        Total Amount
                                    </p>

                                    <p class="text-3xl font-semibold mt-1">
                                        ৳<span id="grandTotal">1570</span>
                                    </p>

                                </div>

                                <span class="text-xs text-[#d2ad61]">
                                    COD Available
                                </span>

                            </div>


                            <button type="submit"
                                class="w-full gold-gradient text-[#171b18] py-4 rounded-2xl font-bold hover:brightness-110 transition">
                                Confirm Order
                                <span class="ml-2">→</span>
                            </button>


                            <p class="text-center text-xs text-white/75 mt-4">
                                🔒 Your information is secure and private.
                            </p>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
        REVIEWS
    ========================================================== --}}
    <section id="reviews" class="py-20 lg:py-28 bg-[#f5f3ed] overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Section Header --}}
            <div class="text-center max-w-2xl mx-auto">

                <span class="text-[#a17b39] text-xs font-bold uppercase tracking-[.25em]">
                    Customer Reviews
                </span>

                <h2 class="serif text-4xl sm:text-5xl mt-4">
                    Men Love The Fit
                </h2>

                <p class="text-gray-500 leading-7 mt-5">
                    Thousands of customers have already experienced
                    the comfort, quality and elegance of our Panjabi.
                </p>

            </div>


            {{-- Rating Summary --}}
            <div class="flex flex-col sm:flex-row justify-center items-center gap-3 mt-8">

                <div class="flex items-center gap-2">

                    <span class="text-2xl font-bold">
                        4.9
                    </span>

                    <span class="text-[#b68b3f] text-lg tracking-wide">
                        ★★★★★
                    </span>

                </div>

                <div class="hidden sm:block w-px h-5 bg-gray-300"></div>

                <span class="text-sm text-gray-500">
                    Based on 1,200+ verified reviews
                </span>

            </div>


            {{-- =================================================
            SLIDER
        ================================================== --}}

            <div class="relative mt-14">


                {{-- Previous Button --}}
                <button type="button" id="reviewPrev" aria-label="Previous reviews"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 hidden lg:flex w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-xl hover:bg-[#202820] hover:text-white transition z-10">
                    ←
                </button>


                {{-- Slider Viewport --}}
                <div class="overflow-hidden">

                    <div id="reviewSlider" class="flex transition-transform duration-500 ease-out">


                        {{-- =================================================
                        REVIEW 1
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "The Panjabi looks even better in person.
                                    Fabric quality is excellent and the fitting
                                    is perfect."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        AH
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Arif Hasan
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Dhaka
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 2
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "Ordered for Eid and received it quickly.
                                    The design is simple but very premium.
                                    Definitely worth the price."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        FR
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Fahim Rahman
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Chattogram
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 3
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "Really comfortable fabric. I've already
                                    ordered another color. The fitting is
                                    exactly as described."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        SA
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Sakib Ahmed
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Sylhet
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 4
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "Very happy with the purchase. The fabric
                                    feels premium and the color looks exactly
                                    like the pictures."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        MR
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Mahmud Rahman
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Narayanganj
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 5
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "Bought this as a gift for my brother.
                                    He loved the fitting and the quality.
                                    Packaging was also excellent."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        TN
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Tanvir Noor
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Gazipur
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 6
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "This is my second order from them.
                                    Great quality, comfortable fabric and
                                    delivery was very fast."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        NR
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Nayeem Rahman
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Cumilla
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 7
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "The stitching and finishing are excellent.
                                    It feels like a much more expensive Panjabi."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        IH
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Imran Hossain
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Rajshahi
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- =================================================
                        REVIEW 8
                    ================================================== --}}

                        <div class="review-slide shrink-0 w-full md:w-1/2 lg:w-1/3 px-3">

                            <div
                                class="bg-white rounded-3xl p-7 h-full border border-black/5 hover:shadow-xl transition">

                                <div class="flex items-center justify-between">

                                    <div class="text-[#b68b3f] tracking-wide">
                                        ★★★★★
                                    </div>

                                    <span
                                        class="text-[10px] uppercase tracking-wider bg-green-50 text-green-700 px-2.5 py-1 rounded-full">
                                        Verified
                                    </span>

                                </div>

                                <p class="text-gray-600 leading-7 mt-5">
                                    "Beautiful design and very comfortable
                                    to wear. Got many compliments at the
                                    family event."
                                </p>

                                <div class="flex items-center gap-3 mt-7">

                                    <div
                                        class="w-10 h-10 rounded-full bg-[#202820] text-white flex items-center justify-center font-semibold">
                                        AS
                                    </div>

                                    <div>
                                        <p class="font-semibold text-sm">
                                            Abdullah Sami
                                        </p>

                                        <p class="text-xs text-gray-400 mt-0.5">
                                            Khulna
                                        </p>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Next Button --}}

                <button type="button" id="reviewNext" aria-label="Next reviews"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 hidden lg:flex w-12 h-12 bg-white rounded-full shadow-lg items-center justify-center text-xl hover:bg-[#202820] hover:text-white transition z-10">
                    →
                </button>

            </div>


            {{-- Mobile Navigation --}}

            <div class="flex lg:hidden justify-center gap-3 mt-8">

                <button type="button" id="reviewPrevMobile"
                    class="w-11 h-11 rounded-full bg-white shadow flex items-center justify-center hover:bg-[#202820] hover:text-white transition"
                    aria-label="Previous reviews">
                    ←
                </button>

                <button type="button" id="reviewNextMobile"
                    class="w-11 h-11 rounded-full bg-white shadow flex items-center justify-center hover:bg-[#202820] hover:text-white transition"
                    aria-label="Next reviews">
                    →
                </button>

            </div>


            {{-- Dots --}}

            <div id="reviewDots" class="flex justify-center gap-2 mt-7"></div>

        </div>

    </section>


    {{-- =========================================================
        FINAL CTA
    ========================================================== --}}
    <section class="bg-[#202820] text-white">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">

            <span class="text-[#d2ad61] text-xs font-bold uppercase tracking-[.25em]">
                Premium Panjabi
            </span>

            <h2 class="serif text-4xl sm:text-5xl lg:text-6xl mt-5 leading-tight">

                Your Style.

                <span class="block text-[#d2ad61]">
                    Your Tradition.
                </span>

            </h2>

            <p class="text-white/55 max-w-xl mx-auto mt-6 leading-7">
                Make your next occasion memorable with a Panjabi
                designed to look good and feel even better.
            </p>

            <a href="#order"
                class="inline-flex bg-[#d2ad61] text-[#171b18] px-8 py-4 rounded-full font-bold mt-9 hover:bg-[#e3c77f] transition">
                Order Now
                <span class="ml-3">→</span>
            </a>

        </div>

    </section>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="bg-[#131713] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="flex flex-col md:flex-row justify-between gap-8">

                <div>

                    <h3 class="serif text-2xl tracking-widest">
                        RAZIN
                    </h3>

                    <p class="text-white/40 text-sm max-w-sm mt-3">
                        Premium traditional clothing for the
                        modern Bengali man.
                    </p>

                </div>


                <div class="flex gap-7 text-sm text-white/50">

                    <a href="#" class="hover:text-white transition">
                        Facebook
                    </a>

                    <a href="#" class="hover:text-white transition">
                        Instagram
                    </a>

                    <a href="#order" class="hover:text-white transition">
                        Order
                    </a>

                </div>

            </div>


            <div class="border-t border-white/10 mt-10 pt-6 text-center">

                <p class="text-xs text-white/30">
                    © {{ date('Y') }} RAZIN. All rights reserved.
                </p>

            </div>

        </div>

    </footer>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>
        const PRODUCT_PRICE = 1490;

        function changeQuantity(change) {

            const input = document.getElementById('quantity');

            let quantity = parseInt(input.value);

            quantity += change;

            if (quantity < 1) {
                quantity = 1;
            }

            if (quantity > 10) {
                quantity = 10;
            }

            input.value = quantity;

            updateOrderSummary();
        }


        function updateOrderSummary() {

            const quantity =
                parseInt(
                    document.getElementById('quantity').value
                );

            const shipping =
                document.querySelector(
                    'input[name="shipping"]:checked'
                );

            const shippingCharge =
                parseInt(
                    shipping?.dataset.charge || 0
                );


            const subtotal =
                PRODUCT_PRICE * quantity;

            const total =
                subtotal + shippingCharge;


            document.getElementById('summaryQuantity')
                .textContent = quantity;

            document.getElementById('subtotal')
                .textContent = subtotal;

            document.getElementById('productTotal')
                .textContent = subtotal;

            document.getElementById('shippingCharge')
                .textContent = shippingCharge;

            document.getElementById('grandTotal')
                .textContent = total;


            const selectedColor =
                document.querySelector(
                    'input[name="color"]:checked'
                );

            if (selectedColor) {

                document.getElementById('summaryColor')
                    .textContent = selectedColor.value;

            }


            const selectedSize =
                document.querySelector(
                    'input[name="size"]:checked'
                );

            if (selectedSize) {

                document.getElementById('summarySize')
                    .textContent = selectedSize.value;

            }

        }


        document
            .querySelectorAll('input[name="color"]')
            .forEach(input => {

                input.addEventListener(
                    'change',
                    updateOrderSummary
                );

            });


        document
            .querySelectorAll('input[name="size"]')
            .forEach(input => {

                input.addEventListener(
                    'change',
                    updateOrderSummary
                );

            });


        updateOrderSummary();


        document.addEventListener('DOMContentLoaded', function() {

            const slider =
                document.getElementById('reviewSlider');

            const slides =
                document.querySelectorAll('.review-slide');

            const dotsContainer =
                document.getElementById('reviewDots');


            const nextButtons = [
                document.getElementById('reviewNext'),
                document.getElementById('reviewNextMobile')
            ];

            const prevButtons = [
                document.getElementById('reviewPrev'),
                document.getElementById('reviewPrevMobile')
            ];


            let currentIndex = 0;

            let autoPlay;


            /*
            |--------------------------------------------------------------------------
            | Get slides visible at current screen size
            |--------------------------------------------------------------------------
            */

            function getSlidesPerView() {

                if (window.innerWidth >= 1024) {
                    return 3;
                }

                if (window.innerWidth >= 768) {
                    return 2;
                }

                return 1;
            }


            /*
            |--------------------------------------------------------------------------
            | Get maximum slide index
            |--------------------------------------------------------------------------
            */

            function getMaxIndex() {

                return Math.max(
                    0,
                    slides.length - getSlidesPerView()
                );

            }


            /*
            |--------------------------------------------------------------------------
            | Create Dots
            |--------------------------------------------------------------------------
            */

            function createDots() {

                dotsContainer.innerHTML = '';

                const totalDots =
                    getMaxIndex() + 1;


                for (let i = 0; i < totalDots; i++) {

                    const dot =
                        document.createElement('button');

                    dot.type = 'button';

                    dot.className =
                        'w-2 h-2 rounded-full bg-gray-300 transition-all duration-300';


                    dot.addEventListener(
                        'click',
                        function() {

                            currentIndex = i;

                            updateSlider();

                            restartAutoPlay();

                        }
                    );


                    dotsContainer.appendChild(dot);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Update Dots
            |--------------------------------------------------------------------------
            */

            function updateDots() {

                const dots =
                    dotsContainer.querySelectorAll('button');


                dots.forEach((dot, index) => {

                    if (index === currentIndex) {

                        dot.classList.remove(
                            'bg-gray-300',
                            'w-2'
                        );

                        dot.classList.add(
                            'bg-[#202820]',
                            'w-6'
                        );

                    } else {

                        dot.classList.remove(
                            'bg-[#202820]',
                            'w-6'
                        );

                        dot.classList.add(
                            'bg-gray-300',
                            'w-2'
                        );

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Update Slider
            |--------------------------------------------------------------------------
            */

            function updateSlider() {

                const slidesPerView =
                    getSlidesPerView();

                const percentage =
                    (currentIndex * 100) /
                    slidesPerView;


                slider.style.transform =
                    `translateX(-${percentage}%)`;


                updateDots();

            }


            /*
            |--------------------------------------------------------------------------
            | Next
            |--------------------------------------------------------------------------
            */

            function nextSlide() {

                const maxIndex =
                    getMaxIndex();


                if (currentIndex >= maxIndex) {

                    currentIndex = 0;

                } else {

                    currentIndex++;

                }


                updateSlider();

            }


            /*
            |--------------------------------------------------------------------------
            | Previous
            |--------------------------------------------------------------------------
            */

            function previousSlide() {

                const maxIndex =
                    getMaxIndex();


                if (currentIndex <= 0) {

                    currentIndex = maxIndex;

                } else {

                    currentIndex--;

                }


                updateSlider();

            }


            /*
            |--------------------------------------------------------------------------
            | Buttons
            |--------------------------------------------------------------------------
            */

            nextButtons.forEach(button => {

                if (button) {

                    button.addEventListener(
                        'click',
                        function() {

                            nextSlide();

                            restartAutoPlay();

                        }
                    );

                }

            });


            prevButtons.forEach(button => {

                if (button) {

                    button.addEventListener(
                        'click',
                        function() {

                            previousSlide();

                            restartAutoPlay();

                        }
                    );

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Auto Play
            |--------------------------------------------------------------------------
            */

            function startAutoPlay() {

                autoPlay =
                    setInterval(
                        nextSlide,
                        4000
                    );

            }


            function stopAutoPlay() {

                clearInterval(autoPlay);

            }


            function restartAutoPlay() {

                stopAutoPlay();

                startAutoPlay();

            }


            /*
            |--------------------------------------------------------------------------
            | Pause on Hover
            |--------------------------------------------------------------------------
            */

            slider.addEventListener(
                'mouseenter',
                stopAutoPlay
            );


            slider.addEventListener(
                'mouseleave',
                startAutoPlay
            );


            /*
            |--------------------------------------------------------------------------
            | Responsive Resize
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'resize',
                function() {

                    const maxIndex =
                        getMaxIndex();


                    if (currentIndex > maxIndex) {

                        currentIndex = maxIndex;

                    }


                    createDots();

                    updateSlider();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Touch Swipe
            |--------------------------------------------------------------------------
            */

            let touchStartX = 0;

            let touchEndX = 0;


            slider.addEventListener(
                'touchstart',
                function(event) {

                    touchStartX =
                        event.changedTouches[0].screenX;

                    stopAutoPlay();

                }, {
                    passive: true
                }
            );


            slider.addEventListener(
                'touchend',
                function(event) {

                    touchEndX =
                        event.changedTouches[0].screenX;


                    const difference =
                        touchStartX - touchEndX;


                    if (Math.abs(difference) > 50) {

                        if (difference > 0) {

                            nextSlide();

                        } else {

                            previousSlide();

                        }

                    }


                    startAutoPlay();

                }, {
                    passive: true
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Initialize
            |--------------------------------------------------------------------------
            */

            createDots();

            updateSlider();

            startAutoPlay();

        });
    </script>

    {{-- Fancybox --}}
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind('[data-fancybox="panjabi-gallery"]', {
            zoomEffect: true,

            Carousel: {
                Toolbar: {
                    display: {
                        left: [],
                        middle: ["prev", "infobar", "next"],
                        right: ["slideshow", "fullscreen", "close"],
                    },
                },
            },
        });
    </script>

</body>

</html>
