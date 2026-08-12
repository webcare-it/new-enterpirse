<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KHIMMER | Modern Khimar Collection</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system,
                BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .display {
            font-family: Georgia, "Times New Roman", serif;
        }

        .natural-bg {
            background:
                radial-gradient(circle at 15% 20%,
                    rgba(159, 177, 146, .20),
                    transparent 28%),
                radial-gradient(circle at 85% 80%,
                    rgba(218, 204, 177, .30),
                    transparent 30%),
                #f7f6f0;
        }

        .sage-gradient {
            background:
                linear-gradient(145deg,
                    #71806a 0%,
                    #87977d 50%,
                    #9eaa91 100%);
        }

        .cream-gradient {
            background:
                linear-gradient(135deg,
                    #f4f1e7,
                    #e7eadf);
        }

        .leaf-shadow {
            box-shadow:
                0 25px 70px rgba(74, 88, 69, .13);
        }

        .card-shadow {
            box-shadow:
                0 12px 40px rgba(60, 70, 55, .08);
        }

        .image-zoom img {
            transition: transform .8s cubic-bezier(.2, .7, .2, 1);
        }

        .image-zoom:hover img {
            transform: scale(1.045);
        }
    </style>

</head>


<body class="bg-[#f7f6f0] text-[#263027]">


    {{-- =========================================================
    TOP NOTICE
========================================================== --}}

    <div class="bg-[#263027] text-[#f7f6f0]">

        <div class="max-w-7xl mx-auto px-4 py-2.5">

            <p class="text-center text-[11px] sm:text-xs tracking-wide">
                Free delivery on orders over ৳2,500 · Cash on delivery available
            </p>

        </div>

    </div>



    {{-- =========================================================
    NAVIGATION
========================================================== --}}

    <header class="bg-[#f7f6f0]/95 backdrop-blur-xl sticky top-0 z-40">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="h-20 flex items-center justify-center">

                {{-- Logo --}}

                <a href="#" class="flex items-center gap-3">

                    <span class="w-9 h-9 rounded-full bg-[#87977d] flex items-center justify-center text-white text-sm">
                        K
                    </span>

                    <span class="display text-xl tracking-[.18em]">
                        KHIMMER
                    </span>

                </a>

            </div>

        </div>

    </header>



    {{-- =========================================================
    HERO
========================================================== --}}

    <section class="natural-bg overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-[.85fr_1.15fr] gap-10 lg:gap-20 items-center py-12 sm:py-16 lg:py-20">


                {{-- LEFT CONTENT --}}

                <div class="order-2 lg:order-1">

                    <div class="flex items-center gap-3">

                        <span class="w-10 h-px bg-[#87977d]"></span>

                        <span class="text-xs uppercase tracking-[.28em] text-[#66725f]">
                            New Season · 2026
                        </span>

                    </div>


                    <h1 class="display text-[3.5rem] sm:text-6xl lg:text-[5.5rem] leading-[.95] tracking-tight mt-7">

                        Made to feel

                        <span class="block text-[#788b70] italic">
                            effortless.
                        </span>

                    </h1>


                    <p class="text-[#687169] leading-8 max-w-lg mt-7 text-base sm:text-lg">

                        Lightweight khimars designed for everyday
                        movement, quiet confidence and a naturally
                        graceful silhouette.

                    </p>


                    <div class="flex flex-wrap gap-3 mt-8">

                        <a href="#collection"
                            class="bg-[#263027] text-white px-7 py-3.5 rounded-full text-sm font-medium hover:bg-[#3b493d] transition">

                            Explore Collection

                        </a>

                        <a href="#story"
                            class="border border-[#cbd0c5] px-7 py-3.5 rounded-full text-sm hover:bg-white transition">

                            Discover Khimmer

                        </a>

                    </div>


                    {{-- Mini Features --}}

                    <div class="flex flex-wrap gap-x-8 gap-y-4 mt-12">

                        <div class="flex items-center gap-2">

                            <span class="w-7 h-7 rounded-full bg-[#e3e8dd] flex items-center justify-center text-xs">
                                ✓
                            </span>

                            <span class="text-xs text-[#687169]">
                                Breathable fabric
                            </span>

                        </div>


                        <div class="flex items-center gap-2">

                            <span class="w-7 h-7 rounded-full bg-[#e3e8dd] flex items-center justify-center text-xs">
                                ✓
                            </span>

                            <span class="text-xs text-[#687169]">
                                Easy everyday wear
                            </span>

                        </div>

                    </div>

                </div>



                {{-- HERO IMAGE --}}

                <div class="order-1 lg:order-2 relative">

                    <div class="absolute -top-10 -right-10 w-48 h-48 bg-[#dfe6d8] rounded-full blur-2xl">
                    </div>

                    <div class="relative">

                        <div class="rounded-[3rem] overflow-hidden aspect-[4/5] leaf-shadow image-zoom">

                            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWRRW1koZrkQd6AU-YIkVIiXqRrX6aoXoMbxcO5aD6aPtkrxJUPsGwctM&s=10"
                                alt="Fresh natural Khimar collection" class="w-full h-full object-cover">

                        </div>


                        {{-- Floating Product Card --}}

                        <div
                            class="absolute left-4 sm:left-8 bottom-4 sm:bottom-8 bg-white/90 backdrop-blur-xl rounded-2xl p-4 sm:p-5 w-[calc(100%-2rem)] sm:w-72 card-shadow">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-[10px] uppercase tracking-[.2em] text-[#899186]">
                                        Featured
                                    </p>

                                    <p class="font-medium mt-1">
                                        Airy Everyday Khimar
                                    </p>

                                </div>

                                <span class="text-lg font-semibold">
                                    ৳1,290
                                </span>

                            </div>

                        </div>


                        {{-- Small Badge --}}

                        <div class="absolute top-5 left-5 bg-[#263027] text-white rounded-full px-4 py-2 text-[11px]">

                            Naturally lightweight

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    SIMPLE STAT STRIP
========================================================== --}}

    <section class="bg-white border-y border-[#e5e6df]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 lg:grid-cols-4">

                <div class="py-7 px-4 text-center border-r border-[#e8e9e3]">

                    <p class="display text-2xl">
                        12+
                    </p>

                    <p class="text-xs text-[#7b847b] mt-1">
                        Khimar styles
                    </p>

                </div>


                <div class="py-7 px-4 text-center lg:border-r border-[#e8e9e3]">

                    <p class="display text-2xl">
                        4.9
                    </p>

                    <p class="text-xs text-[#7b847b] mt-1">
                        Average rating
                    </p>

                </div>


                <div class="py-7 px-4 text-center border-r border-[#e8e9e3]">

                    <p class="display text-2xl">
                        5K+
                    </p>

                    <p class="text-xs text-[#7b847b] mt-1">
                        Happy customers
                    </p>

                </div>


                <div class="py-7 px-4 text-center">

                    <p class="display text-2xl">
                        COD
                    </p>

                    <p class="text-xs text-[#7b847b] mt-1">
                        Across Bangladesh
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    COLLECTION
========================================================== --}}

    <section id="collection" class="py-20 lg:py-28 bg-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                <div>

                    <span class="text-[#788b70] text-xs uppercase tracking-[.25em] font-semibold">
                        The Edit
                    </span>

                    <h2 class="display text-4xl sm:text-5xl mt-4">
                        Everyday, elevated.
                    </h2>

                </div>


                <p class="text-sm text-[#7a837b] max-w-md leading-7">
                    A small collection of thoughtfully selected
                    silhouettes made for comfort, modesty and
                    effortless styling.
                </p>

            </div>



            {{-- COLLECTION GRID --}}

            <div class="grid sm:grid-cols-2 lg:grid-cols-12 gap-5 mt-12">


                {{-- ITEM 1 --}}

                <div class="lg:col-span-5 group">

                    <a href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxCdFikGZrsNoUta1HijWFd2z44HZ3SEWxgksVz5MxbQ5ZM70XM3gsO6vo&s=10"
                        data-fancybox="khimmer-gallery" data-caption="Olive Mist — Soft everyday khimar"
                        class="block rounded-[2rem] overflow-hidden aspect-[4/5] image-zoom cursor-zoom-in">

                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTxCdFikGZrsNoUta1HijWFd2z44HZ3SEWxgksVz5MxbQ5ZM70XM3gsO6vo&s=10"
                            alt="Olive Mist Khimar" class="w-full h-full object-cover">

                    </a>


                    <div class="flex justify-between items-start gap-4 mt-5">

                        <div>

                            <h3 class="font-medium text-lg">
                                Olive Mist
                            </h3>

                            <p class="text-sm text-[#899186] mt-1">
                                Soft everyday khimar
                            </p>

                        </div>

                        <p class="font-semibold">
                            ৳1,290
                        </p>

                    </div>

                </div>



                {{-- ITEM 2 --}}

                <div class="lg:col-span-7 group lg:mt-16">

                    <a href="https://i.etsystatic.com/13372528/r/il/bace2a/7237707367/il_fullxfull.7237707367_rsyt.jpg"
                        data-fancybox="khimmer-gallery" data-caption="Sand Veil — Minimal neutral khimar"
                        class="block rounded-[2rem] overflow-hidden aspect-[16/10] image-zoom">

                        <img src="https://i.etsystatic.com/13372528/r/il/bace2a/7237707367/il_fullxfull.7237707367_rsyt.jpg"
                            alt="Sand Veil Khimar" class="w-full h-full object-cover">

                    </a>


                    <div class="flex justify-between items-start gap-4 mt-5">

                        <div>

                            <h3 class="font-medium text-lg">
                                Sand Veil
                            </h3>

                            <p class="text-sm text-[#899186] mt-1">
                                Minimal neutral silhouette
                            </p>

                        </div>

                        <p class="font-semibold">
                            ৳1,390
                        </p>

                    </div>

                </div>



                {{-- ITEM 3 --}}

                <div class="lg:col-span-7 group">

                    <a href="https://i.pinimg.com/736x/49/03/12/49031263c1ef8f707323ea285cb4cbdd.jpg"
                        data-fancybox="khimmer-gallery" data-caption="Sage Flow — Relaxed premium khimar"
                        class="block rounded-[2rem] overflow-hidden aspect-[16/10] image-zoom">

                        <img src="https://i.pinimg.com/736x/49/03/12/49031263c1ef8f707323ea285cb4cbdd.jpg"
                            alt="Sage Flow Khimar" class="w-full h-full object-cover">

                    </a>


                    <div class="flex justify-between items-start gap-4 mt-5">

                        <div>

                            <h3 class="font-medium text-lg">
                                Sage Flow
                            </h3>

                            <p class="text-sm text-[#899186] mt-1">
                                Relaxed premium drape
                            </p>

                        </div>

                        <p class="font-semibold">
                            ৳1,490
                        </p>

                    </div>

                </div>



                {{-- ITEM 4 --}}

                <div class="lg:col-span-5 group lg:mt-16">

                    <a href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWRRW1koZrkQd6AU-YIkVIiXqRrX6aoXoMbxcO5aD6aPtkrxJUPsGwctM&s=10"
                        data-fancybox="khimmer-gallery" data-caption="Cloud Cream — Lightweight soft khimar"
                        class="block rounded-[2rem] overflow-hidden aspect-[4/5] image-zoom">

                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTWRRW1koZrkQd6AU-YIkVIiXqRrX6aoXoMbxcO5aD6aPtkrxJUPsGwctM&s=10"
                            alt="Cloud Cream Khimar" class="w-full h-full object-cover">

                    </a>


                    <div class="flex justify-between items-start gap-4 mt-5">

                        <div>

                            <h3 class="font-medium text-lg">
                                Cloud Cream
                            </h3>

                            <p class="text-sm text-[#899186] mt-1">
                                Lightweight soft finish
                            </p>

                        </div>

                        <p class="font-semibold">
                            ৳1,350
                        </p>

                    </div>

                </div>

            </div>


        </div>

    </section>



    {{-- =========================================================
    STORY / BRAND
========================================================== --}}

    <section id="story" class="bg-[#e9ede3] py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">


                {{-- Decorative Image --}}

                <div class="relative">

                    <div class="absolute -left-5 -bottom-5 w-32 h-32 rounded-full border border-[#b8c2ae]">
                    </div>

                    <div class="rounded-[2.5rem] overflow-hidden aspect-[5/6] relative z-10">

                        <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=1000&q=88"
                            alt="Khimmer natural lifestyle" class="w-full h-full object-cover">

                    </div>

                </div>



                {{-- Text --}}

                <div>

                    <span class="text-[#71826a] text-xs uppercase tracking-[.25em] font-semibold">
                        Why Khimmer
                    </span>

                    <h2 class="display text-4xl sm:text-5xl lg:text-6xl leading-tight mt-5">

                        Less effort.

                        <span class="block italic text-[#71826a]">
                            More ease.
                        </span>

                    </h2>


                    <p class="text-[#667067] leading-8 mt-7 max-w-xl">

                        We believe modest fashion doesn't need to feel
                        complicated. Khimmer creates uncomplicated pieces
                        that move naturally with you through your day.

                    </p>


                    <div class="space-y-6 mt-9">


                        <div class="flex gap-4">

                            <div
                                class="shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#71826a]">
                                01
                            </div>

                            <div>

                                <h3 class="font-semibold">
                                    Light by design
                                </h3>

                                <p class="text-sm text-[#768078] mt-1 leading-6">
                                    Carefully selected fabrics that feel
                                    airy without sacrificing coverage.
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-4">

                            <div
                                class="shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#71826a]">
                                02
                            </div>

                            <div>

                                <h3 class="font-semibold">
                                    Naturally versatile
                                </h3>

                                <p class="text-sm text-[#768078] mt-1 leading-6">
                                    Neutral shades and relaxed silhouettes
                                    that work across your everyday wardrobe.
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-4">

                            <div
                                class="shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center text-[#71826a]">
                                03
                            </div>

                            <div>

                                <h3 class="font-semibold">
                                    Made for real life
                                </h3>

                                <p class="text-sm text-[#768078] mt-1 leading-6">
                                    Comfortable enough for long days,
                                    polished enough for every occasion.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    PRODUCT HIGHLIGHT
========================================================== --}}

    <section class="py-20 lg:py-28 bg-[#f7f6f0]">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="sage-gradient rounded-[2.5rem] overflow-hidden text-white">

                <div class="grid md:grid-cols-2 items-center">


                    <div class="p-8 sm:p-12 lg:p-16">

                        <span class="text-white/60 text-xs uppercase tracking-[.25em]">
                            Khimmer Essential
                        </span>

                        <h2 class="display text-4xl sm:text-5xl mt-5 leading-tight">
                            The Everyday
                            <span class="italic">
                                Flow
                            </span>
                        </h2>

                        <p class="text-white/70 leading-7 mt-6 max-w-md">
                            Our signature khimar with an easy drape,
                            soft touch and clean finish.
                        </p>


                        <div class="flex items-center gap-4 mt-7">

                            <span class="text-3xl font-semibold">
                                ৳1,290
                            </span>

                            <span class="text-white/40 line-through">
                                ৳1,590
                            </span>

                        </div>


                        <a href="#order"
                            class="inline-flex bg-white text-[#354236] px-7 py-3.5 rounded-full mt-8 text-sm font-semibold hover:bg-[#f0f2eb] transition">

                            Get The Essential

                            <span class="ml-2">
                                →
                            </span>

                        </a>

                    </div>


                    <div class="h-full min-h-[400px]">

                        <img src="https://images.unsplash.com/photo-1525507119028-ed4c629a60a3?auto=format&fit=crop&w=1000&q=88"
                            alt="Khimmer Essential" class="w-full h-full object-cover">

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    ORDER
========================================================== --}}

    <section id="order" class="py-20 lg:py-28 bg-white">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="max-w-xl">

                <span class="text-[#788b70] text-xs uppercase tracking-[.25em] font-semibold">
                    Your Khimar
                </span>

                <h2 class="display text-4xl sm:text-5xl mt-4">
                    Choose your everyday favourite.
                </h2>

                <p class="text-[#7a837b] mt-5 leading-7">
                    Select your preferences and we'll prepare your order
                    for delivery anywhere in Bangladesh.
                </p>

            </div>



            <form action="#" method="POST" class="mt-12">

                @csrf

                <div class="grid lg:grid-cols-[1fr_360px] gap-8">


                    {{-- FORM --}}

                    <div class="space-y-5">


                        {{-- PRODUCT --}}

                        <div class="border border-[#e2e5dc] rounded-[2rem] p-6 sm:p-8">

                            <div class="flex justify-between items-start">

                                <div>

                                    <p class="text-xs uppercase tracking-[.2em] text-[#899186]">
                                        Product
                                    </p>

                                    <h3 class="text-xl font-semibold mt-2">
                                        The Everyday Flow
                                    </h3>

                                </div>

                                <span class="text-lg font-semibold">
                                    ৳1,290
                                </span>

                            </div>


                            {{-- COLOR --}}

                            <div class="mt-8">

                                <label class="text-sm font-semibold">
                                    Shade
                                </label>

                                <div class="flex flex-wrap gap-2 mt-3">

                                    @foreach (['Sage', 'Sand', 'Cream', 'Mocha'] as $color)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="color" value="{{ $color }}"
                                                class="peer sr-only" @checked($color === 'Sage')>

                                            <span
                                                class="inline-flex px-5 py-2.5 rounded-full border border-[#dfe2da] text-sm peer-checked:bg-[#788b70] peer-checked:text-white peer-checked:border-[#788b70] transition">

                                                {{ $color }}

                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>


                            {{-- SIZE --}}

                            <div class="mt-7">

                                <div class="flex items-center justify-between">

                                    <label class="text-sm font-semibold">
                                        Length
                                    </label>

                                    <span class="text-xs text-[#899186]">
                                        One size fits most
                                    </span>

                                </div>


                                <div class="grid grid-cols-3 gap-2 mt-3">

                                    @foreach (['Regular', 'Long', 'Extra Long'] as $size)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="size" value="{{ $size }}"
                                                class="peer sr-only" @checked($size === 'Regular')>

                                            <span
                                                class="flex justify-center text-center px-3 py-3 rounded-xl border border-[#dfe2da] text-sm peer-checked:bg-[#263027] peer-checked:text-white peer-checked:border-[#263027] transition">

                                                {{ $size }}

                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>


                            {{-- QUANTITY --}}

                            <div class="mt-7">

                                <label class="text-sm font-semibold">
                                    Quantity
                                </label>

                                <div
                                    class="flex items-center w-fit mt-3 border border-[#dfe2da] rounded-xl overflow-hidden">

                                    <button type="button" onclick="changeQuantity(-1)"
                                        class="w-12 h-11 hover:bg-[#f3f5ef]">
                                        −
                                    </button>

                                    <input id="quantity" name="quantity" value="1" readonly
                                        class="w-12 h-11 text-center border-x border-[#dfe2da] outline-none">

                                    <button type="button" onclick="changeQuantity(1)"
                                        class="w-12 h-11 hover:bg-[#f3f5ef]">
                                        +
                                    </button>

                                </div>

                            </div>

                        </div>



                        {{-- CUSTOMER --}}

                        <div class="border border-[#e2e5dc] rounded-[2rem] p-6 sm:p-8">

                            <h3 class="text-xl font-semibold">
                                Delivery details
                            </h3>


                            <div class="grid sm:grid-cols-2 gap-5 mt-7">


                                <div class="sm:col-span-2">

                                    <label class="text-sm font-medium">
                                        Name
                                    </label>

                                    <input type="text" name="name" required placeholder="Your full name"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#dfe2da] outline-none focus:border-[#788b70]">

                                </div>


                                <div>

                                    <label class="text-sm font-medium">
                                        Phone
                                    </label>

                                    <input type="tel" name="phone" required placeholder="01XXXXXXXXX"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#dfe2da] outline-none focus:border-[#788b70]">

                                </div>


                                <div>

                                    <label class="text-sm font-medium">
                                        Area
                                    </label>

                                    <input type="text" name="area" required placeholder="Dhaka / Chattogram..."
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#dfe2da] outline-none focus:border-[#788b70]">

                                </div>


                                <div class="sm:col-span-2">

                                    <label class="text-sm font-medium">
                                        Delivery address
                                    </label>

                                    <textarea name="address" rows="3" required placeholder="House, road, area, landmark..."
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#dfe2da] outline-none focus:border-[#788b70] resize-none"></textarea>

                                </div>


                                <div class="sm:col-span-2">

                                    <label class="text-sm font-medium">
                                        Order note
                                    </label>

                                    <textarea name="note" rows="2" placeholder="Optional"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#dfe2da] outline-none focus:border-[#788b70] resize-none"></textarea>

                                </div>

                            </div>

                        </div>



                        {{-- SHIPPING --}}

                        <div class="border border-[#e2e5dc] rounded-[2rem] p-6 sm:p-8">

                            <h3 class="text-xl font-semibold">
                                Delivery method
                            </h3>


                            <div class="grid sm:grid-cols-2 gap-3 mt-6">


                                <label class="cursor-pointer">

                                    <input type="radio" name="shipping" value="inside_dhaka" data-charge="80"
                                        class="peer sr-only" checked onchange="updateOrderSummary()">

                                    <div
                                        class="p-5 rounded-2xl border border-[#dfe2da] peer-checked:border-[#788b70] peer-checked:bg-[#f2f5ee]">

                                        <div class="flex justify-between">

                                            <div>

                                                <p class="font-semibold">
                                                    Inside Dhaka
                                                </p>

                                                <p class="text-xs text-[#899186] mt-1">
                                                    1–2 business days
                                                </p>

                                            </div>

                                            <span class="font-semibold">
                                                ৳80
                                            </span>

                                        </div>

                                    </div>

                                </label>


                                <label class="cursor-pointer">

                                    <input type="radio" name="shipping" value="outside_dhaka" data-charge="130"
                                        class="peer sr-only" onchange="updateOrderSummary()">

                                    <div
                                        class="p-5 rounded-2xl border border-[#dfe2da] peer-checked:border-[#788b70] peer-checked:bg-[#f2f5ee]">

                                        <div class="flex justify-between">

                                            <div>

                                                <p class="font-semibold">
                                                    Outside Dhaka
                                                </p>

                                                <p class="text-xs text-[#899186] mt-1">
                                                    2–4 business days
                                                </p>

                                            </div>

                                            <span class="font-semibold">
                                                ৳130
                                            </span>

                                        </div>

                                    </div>

                                </label>

                            </div>

                        </div>



                        {{-- PAYMENT --}}

                        <div class="border border-[#e2e5dc] rounded-[2rem] p-6 sm:p-8">

                            <h3 class="text-xl font-semibold">
                                Payment
                            </h3>


                            <div class="grid sm:grid-cols-3 gap-3 mt-6">


                                @foreach ([
        'cod' => 'Cash on Delivery',
        'bkash' => 'bKash',
        'nagad' => 'Nagad',
    ] as $value => $label)
                                    <label class="cursor-pointer">

                                        <input type="radio" name="payment_method" value="{{ $value }}"
                                            class="peer sr-only" @checked($value === 'cod')>

                                        <div
                                            class="p-4 text-center rounded-2xl border border-[#dfe2da] peer-checked:border-[#788b70] peer-checked:bg-[#f2f5ee]">

                                            <div class="text-xl">
                                                @if ($value === 'cod')
                                                    💵
                                                @elseif($value === 'bkash')
                                                    💳
                                                @else
                                                    ◈
                                                @endif
                                            </div>

                                            <p class="text-sm font-semibold mt-2">
                                                {{ $label }}
                                            </p>

                                        </div>

                                    </label>
                                @endforeach

                            </div>

                        </div>

                    </div>



                    {{-- SUMMARY --}}

                    <div>

                        <div class="bg-[#263027] text-white rounded-[2rem] p-6 sm:p-8 lg:sticky lg:top-28">


                            <p class="text-[10px] uppercase tracking-[.25em] text-white/40">
                                Your selection
                            </p>

                            <h3 class="display text-3xl mt-2">
                                Order Summary
                            </h3>


                            <div class="flex gap-4 mt-7 pb-6 border-b border-white/10">

                                <div class="w-20 h-24 rounded-xl overflow-hidden shrink-0">

                                    <img src="https://images.unsplash.com/photo-1594736797933-d0501ba2fe65?auto=format&fit=crop&w=300&q=80"
                                        alt="Khimar" class="w-full h-full object-cover">

                                </div>


                                <div class="flex-1">

                                    <p class="font-medium">
                                        The Everyday Flow
                                    </p>

                                    <p class="text-xs text-white/45 mt-2">
                                        Shade:
                                        <span id="summaryColor">
                                            Sage
                                        </span>
                                    </p>

                                    <p class="text-xs text-white/45 mt-1">
                                        Length:
                                        <span id="summarySize">
                                            Regular
                                        </span>
                                    </p>

                                    <p class="text-xs text-white/45 mt-1">
                                        Quantity:
                                        <span id="summaryQuantity">
                                            1
                                        </span>
                                    </p>

                                </div>

                            </div>


                            <div class="space-y-4 py-6 border-b border-white/10">

                                <div class="flex justify-between text-sm">

                                    <span class="text-white/45">
                                        Subtotal
                                    </span>

                                    <span>
                                        ৳<span id="subtotal">1290</span>
                                    </span>

                                </div>


                                <div class="flex justify-between text-sm">

                                    <span class="text-white/45">
                                        Delivery
                                    </span>

                                    <span>
                                        ৳<span id="shippingCharge">80</span>
                                    </span>

                                </div>

                            </div>


                            <div class="flex justify-between items-end py-6">

                                <div>

                                    <p class="text-xs text-white/40">
                                        Total
                                    </p>

                                    <p class="text-3xl font-semibold mt-1">
                                        ৳<span id="grandTotal">1370</span>
                                    </p>

                                </div>

                                <span class="text-[10px] text-[#b8c9ad]">
                                    COD available
                                </span>

                            </div>


                            <button type="submit"
                                class="w-full bg-[#dce5d4] text-[#263027] py-4 rounded-xl font-semibold hover:bg-white transition">

                                Place My Order

                                <span class="ml-2">
                                    →
                                </span>

                            </button>


                            <p class="text-center text-[11px] text-white/35 mt-4">
                                Your information is kept private.
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

    <section id="reviews" class="py-20 lg:py-28 bg-[#edf0e8]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-7">

                <div>

                    <span class="text-[#788b70] text-xs uppercase tracking-[.25em] font-semibold">
                        From Our Community
                    </span>

                    <h2 class="display text-4xl sm:text-5xl mt-4">
                        Worn. Loved. Reordered.
                    </h2>

                </div>


                <div class="flex items-center gap-3">

                    <span class="text-2xl">
                        ★
                    </span>

                    <div>

                        <p class="font-semibold">
                            4.9 out of 5
                        </p>

                        <p class="text-xs text-[#7b847b]">
                            1,200+ verified reviews
                        </p>

                    </div>

                </div>

            </div>



            <div class="grid md:grid-cols-3 gap-5 mt-12">


                <article class="bg-white rounded-[2rem] p-7">

                    <div class="text-[#788b70] tracking-widest">
                        ★★★★★
                    </div>

                    <p class="text-[#5f6860] leading-7 mt-5">
                        “The fabric is incredibly light. I wore it
                        for almost the whole day and it still felt
                        comfortable.”
                    </p>

                    <div class="flex items-center gap-3 mt-7">

                        <div
                            class="w-10 h-10 rounded-full bg-[#dfe7d9] flex items-center justify-center text-sm font-semibold text-[#596754]">
                            NA
                        </div>

                        <div>

                            <p class="text-sm font-semibold">
                                Nadia A.
                            </p>

                            <p class="text-xs text-[#9aa19b]">
                                Dhaka
                            </p>

                        </div>

                    </div>

                </article>



                <article class="bg-[#263027] text-white rounded-[2rem] p-7">

                    <div class="text-[#c5d4bb] tracking-widest">
                        ★★★★★
                    </div>

                    <p class="text-white/70 leading-7 mt-5">
                        “Finally found a khimar that feels modern
                        without being complicated. The Sage shade
                        is beautiful.”
                    </p>

                    <div class="flex items-center gap-3 mt-7">

                        <div
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-sm font-semibold">
                            SR
                        </div>

                        <div>

                            <p class="text-sm font-semibold">
                                Sara R.
                            </p>

                            <p class="text-xs text-white/40">
                                Chattogram
                            </p>

                        </div>

                    </div>

                </article>



                <article class="bg-white rounded-[2rem] p-7">

                    <div class="text-[#788b70] tracking-widest">
                        ★★★★★
                    </div>

                    <p class="text-[#5f6860] leading-7 mt-5">
                        “The drape is exactly what I wanted.
                        Ordered one first and came back for
                        another color.”
                    </p>

                    <div class="flex items-center gap-3 mt-7">

                        <div
                            class="w-10 h-10 rounded-full bg-[#dfe7d9] flex items-center justify-center text-sm font-semibold text-[#596754]">
                            FH
                        </div>

                        <div>

                            <p class="text-sm font-semibold">
                                Farhana H.
                            </p>

                            <p class="text-xs text-[#9aa19b]">
                                Sylhet
                            </p>

                        </div>

                    </div>

                </article>

            </div>

        </div>

    </section>



    {{-- =========================================================
    FINAL CTA
========================================================== --}}

    <section class="bg-[#dfe6d8]">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">

            <span class="text-[#71826a] text-xs uppercase tracking-[.25em] font-semibold">
                Your everyday layer
            </span>

            <h2 class="display text-4xl sm:text-5xl lg:text-6xl mt-5 leading-tight">

                Simple looks good

                <span class="block italic text-[#71826a]">
                    on you.
                </span>

            </h2>

            <p class="text-[#687169] max-w-lg mx-auto mt-6 leading-7">
                Discover soft, effortless khimars made to
                move naturally with your everyday life.
            </p>

            <a href="#order"
                class="inline-flex items-center bg-[#263027] text-white px-8 py-4 rounded-full font-semibold mt-8 hover:bg-[#3c493e] transition">

                Shop Khimmer

                <span class="ml-3">
                    →
                </span>

            </a>

        </div>

    </section>



    {{-- =========================================================
    FOOTER
========================================================== --}}

    <footer class="bg-[#263027] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">


                <div class="lg:col-span-2">

                    <div class="flex items-center gap-3">

                        <span class="w-9 h-9 rounded-full bg-[#87977d] flex items-center justify-center">
                            K
                        </span>

                        <span class="display text-xl tracking-[.18em]">
                            KHIMMER
                        </span>

                    </div>

                    <p class="text-white/40 text-sm leading-7 max-w-sm mt-5">
                        Modern khimars designed around comfort,
                        simplicity and natural elegance.
                    </p>

                </div>


                <div>

                    <p class="text-xs uppercase tracking-[.2em] text-white/35">
                        Explore
                    </p>

                    <div class="flex flex-col gap-3 mt-5 text-sm text-white/55">

                        <a href="#collection" class="hover:text-white transition">
                            Collection
                        </a>

                        <a href="#story" class="hover:text-white transition">
                            Our Story
                        </a>

                        <a href="#reviews" class="hover:text-white transition">
                            Reviews
                        </a>

                        <a href="#order" class="hover:text-white transition">
                            Order
                        </a>

                    </div>

                </div>


                <div>

                    <p class="text-xs uppercase tracking-[.2em] text-white/35">
                        Contact
                    </p>

                    <div class="flex flex-col gap-3 mt-5 text-sm text-white/55">

                        <span>
                            Dhaka, Bangladesh
                        </span>

                        <a href="#" class="hover:text-white transition">
                            Facebook
                        </a>

                        <a href="#" class="hover:text-white transition">
                            Instagram
                        </a>

                    </div>

                </div>

            </div>


            <div class="border-t border-white/10 mt-10 pt-6">

                <p class="text-xs text-white/25 text-center">
                    © {{ date('Y') }} Khimmer. Crafted for everyday ease.
                </p>

            </div>

        </div>

    </footer>



    {{-- =========================================================
    JAVASCRIPT
========================================================== --}}

    <script>
        const PRODUCT_PRICE = 1290;


        /*
        |--------------------------------------------------------------------------
        | Quantity
        |--------------------------------------------------------------------------
        */

        function changeQuantity(change) {

            const input = document.getElementById('quantity');

            let quantity = parseInt(input.value) || 1;

            quantity += change;

            quantity = Math.max(1, Math.min(10, quantity));

            input.value = quantity;

            updateOrderSummary();

        }



        /*
        |--------------------------------------------------------------------------
        | Order Summary
        |--------------------------------------------------------------------------
        */

        function updateOrderSummary() {

            const quantity =
                parseInt(
                    document.getElementById('quantity').value
                ) || 1;


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



        /*
        |--------------------------------------------------------------------------
        | Color / Size listeners
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Initial Summary
        |--------------------------------------------------------------------------
        */

        updateOrderSummary();
    </script>



    {{-- =========================================================
    FANCYBOX
========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>

    <script>
        Fancybox.bind(
            '[data-fancybox="khimmer-gallery"]', {
                animated: true,

                showClass: "f-fadeIn",

                hideClass: "f-fadeOut",

                Toolbar: {
                    display: {
                        left: [],
                        middle: [
                            "infobar"
                        ],
                        right: [
                            "slideshow",
                            "fullscreen",
                            "close"
                        ]
                    }
                },

                Carousel: {
                    transition: "slide",
                    friction: 0.85
                }

            }
        );
    </script>


</body>

</html>
