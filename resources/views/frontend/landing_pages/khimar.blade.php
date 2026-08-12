<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KHIMMER</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css">

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family:
                Inter,
                ui-sans-serif,
                system-ui,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;
        }

        .display-font {
            font-family:
                Georgia,
                "Times New Roman",
                serif;
        }

        .khimmer-dark {
            background: #241719;
        }

        .khimmer-burgundy {
            color: #7f3038;
        }

        .khimmer-bg {
            background:
                radial-gradient(circle at 15% 20%,
                    rgba(148, 67, 73, .15),
                    transparent 28%),
                radial-gradient(circle at 90% 80%,
                    rgba(198, 155, 102, .12),
                    transparent 30%),
                #f3eee6;
        }

        .burgundy-gradient {
            background:
                linear-gradient(135deg,
                    #8f3a42,
                    #60262d);
        }

        .gold-line {
            background:
                linear-gradient(90deg,
                    transparent,
                    #c5a16b,
                    transparent);
        }

        .image-reveal {
            transition:
                transform .7s cubic-bezier(.2, .8, .2, 1);
        }

        .product-card:hover .image-reveal {
            transform: scale(1.055);
        }

        .luxury-shadow {
            box-shadow:
                0 35px 90px rgba(44, 27, 29, .16);
        }

        .soft-shadow {
            box-shadow:
                0 20px 60px rgba(44, 27, 29, .09);
        }
    </style>

</head>


<body class="bg-[#f8f5ef] text-[#241719]">


    {{-- =========================================================
    TOP BAR
========================================================== --}}

    <div class="bg-[#241719] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="min-h-10 flex items-center justify-center text-[11px] sm:text-xs tracking-wide">

                <span class="text-[#d7bd91] mr-2">
                    NEW SEASON
                </span>

                <span class="text-white/70">
                    Contemporary Panjabi Collection — Nationwide Delivery
                </span>

            </div>

        </div>

    </div>



    {{-- =========================================================
    NAVBAR
========================================================== --}}

    <header class="bg-[#f8f5ef]/95 backdrop-blur-md sticky top-0 z-40 border-b border-[#241719]/10">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="h-[76px] flex items-center justify-center">

                <a href="#" class="display-font text-2xl sm:text-3xl tracking-[.18em]">
                    KHIMMER
                </a>


            </div>

        </div>

    </header>



    {{-- =========================================================
    HERO
========================================================== --}}

    <section class="khimmer-bg overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-[.9fr_1.1fr] gap-10 lg:gap-16 items-center min-h-[700px] py-14 lg:py-20">


                {{-- Hero Copy --}}

                <div class="order-2 lg:order-1">

                    <div class="flex items-center gap-3">

                        <span class="w-10 h-px bg-[#8f3a42]"></span>

                        <span class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                            KHIMMER / 2026
                        </span>

                    </div>


                    <h1 class="display-font text-[3.5rem] sm:text-6xl lg:text-[5.5rem] leading-[.95] mt-7">

                        Dress

                        <span class="block italic text-[#8f3a42]">
                            Your Roots.
                        </span>

                    </h1>


                    <p class="text-[#241719]/60 text-base sm:text-lg leading-8 max-w-lg mt-8">

                        A contemporary interpretation of the
                        traditional Panjabi — refined fabrics,
                        understated details and a silhouette
                        made for today's man.

                    </p>


                    <div class="flex flex-col sm:flex-row gap-3 mt-9">

                        <a href="#order"
                            class="burgundy-gradient text-white px-7 py-4 rounded-full text-center font-semibold hover:brightness-110 transition">

                            Explore The Collection

                        </a>

                        <a href="#details"
                            class="border border-[#241719]/20 px-7 py-4 rounded-full text-center font-medium hover:bg-white transition">

                            Discover KHIMMER

                        </a>

                    </div>


                    {{-- Mini Stats --}}

                    <div class="flex items-center gap-7 mt-12">

                        <div>

                            <p class="display-font text-2xl">
                                4.9
                            </p>

                            <p class="text-xs text-[#241719]/45 mt-1">
                                Customer Rating
                            </p>

                        </div>


                        <div class="w-px h-10 bg-[#241719]/15"></div>


                        <div>

                            <p class="display-font text-2xl">
                                6K+
                            </p>

                            <p class="text-xs text-[#241719]/45 mt-1">
                                Orders Delivered
                            </p>

                        </div>


                        <div class="w-px h-10 bg-[#241719]/15"></div>


                        <div>

                            <p class="display-font text-2xl">
                                COD
                            </p>

                            <p class="text-xs text-[#241719]/45 mt-1">
                                Nationwide
                            </p>

                        </div>

                    </div>

                </div>


                {{-- Hero Image --}}

                <div class="order-1 lg:order-2 relative">

                    <div class="absolute -top-10 -right-10 w-48 h-48 border border-[#8f3a42]/20 rounded-full"></div>

                    <div class="absolute -bottom-8 -left-8 w-32 h-32 border border-[#c5a16b]/30 rounded-full"></div>


                    <div class="relative">

                        <div class="absolute left-0 top-8 bg-[#241719] text-white px-5 py-4 z-10">

                            <p class="text-[10px] uppercase tracking-[.25em] text-white/50">
                                Edition 01
                            </p>

                            <p class="display-font text-lg mt-1">
                                The Signature
                            </p>

                        </div>


                        <div class="rounded-[3rem] overflow-hidden luxury-shadow aspect-[4/5]">

                            <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=1200&q=90"
                                alt="KHIMMER Premium Panjabi" class="w-full h-full object-cover">

                        </div>


                        <div class="absolute bottom-6 right-6 bg-[#f8f5ef] px-5 py-4 shadow-xl">

                            <p class="text-[10px] uppercase tracking-[.2em] text-[#241719]/45">
                                Starting From
                            </p>

                            <p class="display-font text-2xl mt-1">
                                ৳1,590
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    INTRO STRIP
========================================================== --}}

    <section class="bg-[#241719] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">

                <div class="py-8 md:px-8 text-center">

                    <p class="text-[#d7bd91] text-xs uppercase tracking-[.25em]">
                        01
                    </p>

                    <p class="display-font text-xl mt-2">
                        Premium Materials
                    </p>

                </div>


                <div class="py-8 md:px-8 text-center">

                    <p class="text-[#d7bd91] text-xs uppercase tracking-[.25em]">
                        02
                    </p>

                    <p class="display-font text-xl mt-2">
                        Tailored Comfort
                    </p>

                </div>


                <div class="py-8 md:px-8 text-center">

                    <p class="text-[#d7bd91] text-xs uppercase tracking-[.25em]">
                        03
                    </p>

                    <p class="display-font text-xl mt-2">
                        Delivered Nationwide
                    </p>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    COLLECTION
========================================================== --}}

    <section id="collection" class="py-20 lg:py-28 bg-[#f8f5ef]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">

                <div>

                    <p class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                        The Edit
                    </p>

                    <h2 class="display-font text-4xl sm:text-5xl lg:text-6xl mt-4">
                        Three ways to wear it.
                    </h2>

                </div>


                <p class="max-w-md text-sm leading-7 text-[#241719]/55">

                    Carefully selected silhouettes for Eid,
                    celebrations, Friday gatherings and
                    everyday occasions.

                </p>

            </div>



            {{-- Product Grid --}}

            <div class="grid md:grid-cols-12 gap-6 mt-14">


                {{-- Product 01 --}}

                <div class="product-card md:col-span-7 group">

                    <a href="https://saralifestyle.com/_next/image?url=https%3A%2F%2Fprod.saralifestyle.com%2FImages%2FProducts%2FVariationWisetImage%2F3314ddcdc4204f65a3987fa039290f8c.jpeg&w=1080&q=75"
                        data-fancybox="khimmer-gallery" data-caption="The Signature — Minimal, confident and versatile."
                        class="block aspect-[5/4] rounded-[2rem] overflow-hidden bg-[#e8e1d7] cursor-zoom-in">

                        <img src="https://saralifestyle.com/_next/image?url=https%3A%2F%2Fprod.saralifestyle.com%2FImages%2FProducts%2FVariationWisetImage%2F3314ddcdc4204f65a3987fa039290f8c.jpeg&w=1080&q=75"
                            alt="KHIMMER Signature Panjabi" class="image-reveal w-full h-full object-cover">

                    </a>


                    <div class="flex justify-between gap-5 mt-5">

                        <div>

                            <span class="text-[10px] uppercase tracking-[.25em] text-[#8f3a42]">
                                01 / Signature
                            </span>

                            <h3 class="display-font text-2xl mt-2">
                                The Signature
                            </h3>

                            <p class="text-sm text-[#241719]/50 mt-1">
                                Clean lines for every occasion
                            </p>

                        </div>


                        <div class="text-right">

                            <p class="font-semibold">
                                ৳1,590
                            </p>

                            <a href="#order" class="inline-block text-xs mt-2 underline underline-offset-4">
                                Order

                            </a>

                        </div>

                    </div>

                </div>



                {{-- Product 02 --}}

                <div class="product-card md:col-span-5 group md:mt-20">

                    <a href="https://www.shoppersbd.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/p/sp2187a.jpg"
                        data-fancybox="khimmer-gallery"
                        data-caption="The Festive — A richer silhouette for special occasions."
                        class="block aspect-[4/5] rounded-[2rem] overflow-hidden bg-[#e8e1d7] cursor-zoom-in">

                        <img src="https://www.shoppersbd.com/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/s/p/sp2187a.jpg"
                            alt="KHIMMER Festive Panjabi" class="image-reveal w-full h-full object-cover">

                    </a>


                    <div class="mt-5">

                        <span class="text-[10px] uppercase tracking-[.25em] text-[#8f3a42]">
                            02 / Festive
                        </span>

                        <div class="flex justify-between gap-4">

                            <div>

                                <h3 class="display-font text-2xl mt-2">
                                    The Festive
                                </h3>

                                <p class="text-sm text-[#241719]/50 mt-1">
                                    Made for memorable evenings
                                </p>

                            </div>

                            <div class="pt-3">

                                <p class="font-semibold">
                                    ৳1,790
                                </p>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Product 03 --}}

                <div class="product-card md:col-span-5 group">

                    <a href="https://www.siwaklifestyle.com/media/2026/06/SJQ3023-3.webp"
                        data-fancybox="khimmer-gallery"
                        data-caption="The Reserve — Refined details with a luxurious finish."
                        class="block aspect-[4/5] rounded-[2rem] overflow-hidden bg-[#e8e1d7] cursor-zoom-in">

                        <img src="https://www.siwaklifestyle.com/media/2026/06/SJQ3023-3.webp"
                            alt="KHIMMER Reserve Panjabi" class="image-reveal w-full h-full object-cover">

                    </a>


                    <div class="mt-5">

                        <span class="text-[10px] uppercase tracking-[.25em] text-[#8f3a42]">
                            03 / Reserve
                        </span>

                        <div class="flex justify-between gap-4">

                            <div>

                                <h3 class="display-font text-2xl mt-2">
                                    The Reserve
                                </h3>

                                <p class="text-sm text-[#241719]/50 mt-1">
                                    Quiet luxury, elevated
                                </p>

                            </div>

                            <div class="pt-3">

                                <p class="font-semibold">
                                    ৳1,990
                                </p>

                            </div>

                        </div>

                    </div>

                </div>



                {{-- Editorial Block --}}

                <div
                    class="md:col-span-7 bg-[#e8e0d5] rounded-[2rem] p-8 sm:p-12 flex flex-col justify-between min-h-[430px]">

                    <div>

                        <p class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                            Why KHIMMER
                        </p>

                        <h3 class="display-font text-4xl sm:text-5xl mt-5 max-w-xl leading-tight">

                            Tradition doesn't need to look traditional.

                        </h3>

                    </div>


                    <div class="flex flex-col sm:flex-row gap-6 sm:items-end justify-between mt-10">

                        <p class="text-sm leading-7 text-[#241719]/55 max-w-sm">

                            We believe classic Bengali clothing can
                            evolve without losing the character that
                            makes it special.

                        </p>

                        <span class="display-font text-5xl text-[#8f3a42]/20">
                            KH.
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    PRODUCT DETAILS
========================================================== --}}

    <section id="details" class="bg-[#241719] text-white py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-14 lg:gap-20 items-center">


                <div class="relative">

                    <div class="absolute -inset-4 border border-white/10 rounded-[2.5rem]"></div>

                    <div class="relative rounded-[2rem] overflow-hidden aspect-square">

                        <img src="https://images.unsplash.com/photo-1603252110481-7ba873bf42ab?auto=format&fit=crop&w=1200&q=90"
                            alt="KHIMMER premium fabric" class="w-full h-full object-cover">

                    </div>

                </div>


                <div>

                    <p class="text-[#d7bd91] text-xs uppercase tracking-[.3em] font-bold">
                        The Details
                    </p>

                    <h2 class="display-font text-4xl sm:text-5xl lg:text-6xl mt-5 leading-tight">

                        Designed around
                        <span class="italic text-[#d7bd91]">
                            comfort.
                        </span>

                    </h2>


                    <p class="text-white/55 leading-8 mt-7 max-w-xl">

                        Soft against the skin, breathable through the
                        day and structured enough to keep its shape.
                        Every detail is designed to make the Panjabi
                        feel effortless.

                    </p>


                    <div class="grid grid-cols-2 gap-x-8 gap-y-8 mt-10">


                        <div class="border-t border-white/10 pt-5">

                            <p class="text-xs text-white/35 uppercase tracking-wider">
                                Fabric
                            </p>

                            <p class="mt-2 font-medium">
                                Premium Cotton
                            </p>

                        </div>


                        <div class="border-t border-white/10 pt-5">

                            <p class="text-xs text-white/35 uppercase tracking-wider">
                                Construction
                            </p>

                            <p class="mt-2 font-medium">
                                Fine Stitching
                            </p>

                        </div>


                        <div class="border-t border-white/10 pt-5">

                            <p class="text-xs text-white/35 uppercase tracking-wider">
                                Fit
                            </p>

                            <p class="mt-2 font-medium">
                                Modern Regular
                            </p>

                        </div>


                        <div class="border-t border-white/10 pt-5">

                            <p class="text-xs text-white/35 uppercase tracking-wider">
                                Finish
                            </p>

                            <p class="mt-2 font-medium">
                                Soft Washed
                            </p>

                        </div>

                    </div>


                    <a href="#order"
                        class="inline-flex mt-10 bg-[#d7bd91] text-[#241719] px-7 py-4 rounded-full font-semibold hover:bg-[#e4d1af] transition">

                        Get Yours

                        <span class="ml-3">
                            →
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </section>



    {{-- =========================================================
    SIZE GUIDE
========================================================== --}}

    <section id="size-guide" class="py-20 lg:py-24 bg-[#f3eee6]">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="text-center">

                <p class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                    Fit Guide
                </p>

                <h2 class="display-font text-4xl sm:text-5xl mt-4">
                    Find your size.
                </h2>

                <p class="text-sm text-[#241719]/50 mt-4">
                    Measure around the fullest part of your chest.
                </p>

            </div>


            <div class="mt-12 overflow-hidden rounded-[2rem] border border-[#241719]/10 bg-white">

                <div class="grid grid-cols-3 bg-[#241719] text-white">

                    <div class="p-4 sm:p-5 text-xs uppercase tracking-wider">
                        Size
                    </div>

                    <div class="p-4 sm:p-5 text-xs uppercase tracking-wider">
                        Chest
                    </div>

                    <div class="p-4 sm:p-5 text-xs uppercase tracking-wider">
                        Length
                    </div>

                </div>


                @foreach ([['M', '40"', '40"'], ['L', '42"', '41"'], ['XL', '44"', '42"'], ['XXL', '46"', '43"'], ['3XL', '48"', '44"']] as $row)
                    <div class="grid grid-cols-3 border-t border-gray-100">

                        <div class="p-4 sm:p-5 font-semibold">
                            {{ $row[0] }}
                        </div>

                        <div class="p-4 sm:p-5 text-gray-500">
                            {{ $row[1] }}
                        </div>

                        <div class="p-4 sm:p-5 text-gray-500">
                            {{ $row[2] }}
                        </div>

                    </div>
                @endforeach

            </div>

        </div>

    </section>



    {{-- =========================================================
    ORDER SECTION
========================================================== --}}

    <section id="order" class="py-20 lg:py-28 bg-[#fbfaf7]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="max-w-2xl">

                <p class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                    Private Order
                </p>

                <h2 class="display-font text-4xl sm:text-5xl lg:text-6xl mt-4">
                    Make it yours.
                </h2>

                <p class="text-[#241719]/55 leading-7 mt-5">
                    Choose your preferred color, size and delivery option.
                    We'll take care of the rest.
                </p>

            </div>


            <form action="#" method="POST" class="mt-12">

                @csrf

                <div class="grid lg:grid-cols-[1fr_400px] gap-8">


                    {{-- LEFT --}}

                    <div class="space-y-6">


                        {{-- Product --}}

                        <div class="bg-white border border-[#241719]/8 rounded-[2rem] p-5 sm:p-8">

                            <div class="flex items-center justify-between">

                                <div>

                                    <p class="text-xs uppercase tracking-wider text-[#8f3a42]">
                                        Product
                                    </p>

                                    <h3 class="display-font text-2xl mt-2">
                                        The Signature
                                    </h3>

                                </div>

                                <p class="font-semibold">
                                    ৳1,590
                                </p>

                            </div>


                            {{-- Color --}}

                            <div class="mt-8">

                                <label class="text-sm font-semibold">
                                    Choose Color
                                </label>

                                <div class="flex flex-wrap gap-3 mt-4">


                                    @foreach (['Black', 'Off White', 'Maroon', 'Navy'] as $index => $color)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="color" value="{{ $color }}"
                                                class="peer sr-only" @checked($index === 0)>

                                            <span
                                                class="block border border-[#241719]/15 rounded-full px-5 py-2.5 text-sm
                                            peer-checked:bg-[#241719]
                                            peer-checked:text-white
                                            peer-checked:border-[#241719]
                                            transition">

                                                {{ $color }}

                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>


                            {{-- Size --}}

                            <div class="mt-8">

                                <div class="flex justify-between items-center">

                                    <label class="text-sm font-semibold">
                                        Select Size
                                    </label>

                                    <a href="#size-guide" class="text-xs text-[#8f3a42] underline underline-offset-4">
                                        View Size Guide
                                    </a>

                                </div>


                                <div class="grid grid-cols-5 gap-2 sm:gap-3 mt-4">

                                    @foreach (['M', 'L', 'XL', 'XXL', '3XL'] as $size)
                                        <label class="cursor-pointer">

                                            <input type="radio" name="size" value="{{ $size }}"
                                                class="peer sr-only" @checked($size === 'L')>

                                            <span
                                                class="flex justify-center py-3 rounded-xl border border-[#241719]/15 text-sm
                                            peer-checked:bg-[#8f3a42]
                                            peer-checked:text-white
                                            peer-checked:border-[#8f3a42]
                                            transition">

                                                {{ $size }}

                                            </span>

                                        </label>
                                    @endforeach

                                </div>

                            </div>


                            {{-- Quantity --}}

                            <div class="mt-8">

                                <label class="text-sm font-semibold">
                                    Quantity
                                </label>

                                <div
                                    class="flex items-center w-fit border border-[#241719]/15 rounded-xl overflow-hidden mt-3">

                                    <button type="button" onclick="changeQuantity(-1)"
                                        class="w-12 h-12 hover:bg-[#f5f1eb] text-lg">

                                        −

                                    </button>

                                    <input id="quantity" name="quantity" value="1" readonly
                                        class="w-12 h-12 text-center border-x border-[#241719]/15 outline-none">

                                    <button type="button" onclick="changeQuantity(1)"
                                        class="w-12 h-12 hover:bg-[#f5f1eb] text-lg">

                                        +

                                    </button>

                                </div>

                            </div>

                        </div>



                        {{-- Customer --}}

                        <div class="bg-white border border-[#241719]/8 rounded-[2rem] p-5 sm:p-8">

                            <p class="text-xs uppercase tracking-wider text-[#8f3a42]">
                                Delivery Information
                            </p>

                            <h3 class="display-font text-2xl mt-2">
                                Where should we send it?
                            </h3>


                            <div class="space-y-5 mt-7">


                                <div>

                                    <label class="text-sm font-medium">
                                        Full Name
                                    </label>

                                    <input type="text" name="name" required placeholder="Your full name"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#241719]/15 outline-none focus:border-[#8f3a42]">

                                </div>


                                <div>

                                    <label class="text-sm font-medium">
                                        Phone Number
                                    </label>

                                    <input type="text" name="phone" required placeholder="01XXXXXXXXX"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#241719]/15 outline-none focus:border-[#8f3a42]">

                                </div>


                                <div>

                                    <label class="text-sm font-medium">
                                        Delivery Address
                                    </label>

                                    <textarea name="address" rows="3" required placeholder="House, road, area, district..."
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#241719]/15 outline-none focus:border-[#8f3a42] resize-none"></textarea>

                                </div>


                                <div>

                                    <label class="text-sm font-medium">
                                        Order Note
                                    </label>

                                    <textarea name="note" rows="2" placeholder="Anything we should know?"
                                        class="w-full mt-2 px-4 py-3.5 rounded-xl border border-[#241719]/15 outline-none focus:border-[#8f3a42] resize-none"></textarea>

                                </div>


                            </div>

                        </div>



                        {{-- Shipping --}}

                        <div class="bg-white border border-[#241719]/8 rounded-[2rem] p-5 sm:p-8">

                            <p class="text-xs uppercase tracking-wider text-[#8f3a42]">
                                Delivery
                            </p>

                            <h3 class="display-font text-2xl mt-2">
                                Choose your location
                            </h3>


                            <div class="grid sm:grid-cols-2 gap-4 mt-7">


                                <label class="cursor-pointer">

                                    <input type="radio" name="shipping" value="inside_dhaka" data-charge="80"
                                        class="peer sr-only" checked onchange="updateOrderSummary()">

                                    <div
                                        class="border border-[#241719]/15 rounded-2xl p-5
                                    peer-checked:border-[#8f3a42]
                                    peer-checked:bg-[#fbf4f4]">

                                        <div class="flex justify-between">

                                            <div>

                                                <p class="font-semibold">
                                                    Inside Dhaka
                                                </p>

                                                <p class="text-xs text-gray-500 mt-1">
                                                    1–2 working days
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
                                        class="border border-[#241719]/15 rounded-2xl p-5
                                    peer-checked:border-[#8f3a42]
                                    peer-checked:bg-[#fbf4f4]">

                                        <div class="flex justify-between">

                                            <div>

                                                <p class="font-semibold">
                                                    Outside Dhaka
                                                </p>

                                                <p class="text-xs text-gray-500 mt-1">
                                                    2–4 working days
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



                        {{-- Payment --}}

                        <div class="bg-white border border-[#241719]/8 rounded-[2rem] p-5 sm:p-8">

                            <p class="text-xs uppercase tracking-wider text-[#8f3a42]">
                                Payment
                            </p>

                            <h3 class="display-font text-2xl mt-2">
                                How would you like to pay?
                            </h3>


                            <div class="grid sm:grid-cols-3 gap-3 mt-7">


                                @foreach ([['cod', 'Cash on Delivery', 'Pay when delivered'], ['bkash', 'bKash', 'Mobile payment'], ['nagad', 'Nagad', 'Mobile payment']] as $index => $payment)
                                    <label class="cursor-pointer">

                                        <input type="radio" name="payment_method" value="{{ $payment[0] }}"
                                            class="peer sr-only" @checked($index === 0)>

                                        <div
                                            class="border border-[#241719]/15 rounded-2xl p-5
                                        peer-checked:border-[#8f3a42]
                                        peer-checked:bg-[#fbf4f4]">

                                            <div class="text-xl">
                                                {{ $payment[0] === 'cod' ? '৳' : '•' }}
                                            </div>

                                            <p class="font-semibold text-sm mt-3">
                                                {{ $payment[1] }}
                                            </p>

                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $payment[2] }}
                                            </p>

                                        </div>

                                    </label>
                                @endforeach

                            </div>

                        </div>


                    </div>



                    {{-- RIGHT SUMMARY --}}

                    <div>

                        <div class="bg-[#241719] text-white rounded-[2rem] p-5 sm:p-8 lg:sticky lg:top-24">


                            <p class="text-[#d7bd91] text-xs uppercase tracking-[.25em]">
                                Your Selection
                            </p>

                            <h3 class="display-font text-3xl mt-3">
                                Order Summary
                            </h3>


                            <div class="flex gap-4 mt-8 pb-7 border-b border-white/10">

                                <div class="w-20 h-24 rounded-xl overflow-hidden shrink-0">

                                    <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?auto=format&fit=crop&w=300&q=80"
                                        alt="KHIMMER Panjabi" class="w-full h-full object-cover">

                                </div>


                                <div class="flex-1">

                                    <p class="font-semibold">
                                        The Signature
                                    </p>

                                    <p class="text-sm text-white/45 mt-2">
                                        Color:
                                        <span id="summaryColor">
                                            Black
                                        </span>
                                    </p>

                                    <p class="text-sm text-white/45">
                                        Size:
                                        <span id="summarySize">
                                            L
                                        </span>
                                    </p>

                                    <p class="text-sm text-white/45">
                                        Quantity:
                                        <span id="summaryQuantity">
                                            1
                                        </span>
                                    </p>

                                </div>

                                <p class="font-semibold">
                                    ৳<span id="productTotal">1590</span>
                                </p>

                            </div>


                            <div class="space-y-4 py-7 border-b border-white/10">

                                <div class="flex justify-between text-sm">

                                    <span class="text-white/45">
                                        Subtotal
                                    </span>

                                    <span>
                                        ৳<span id="subtotal">1590</span>
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


                            <div class="flex items-end justify-between py-7">

                                <div>

                                    <p class="text-xs text-white/40">
                                        Total
                                    </p>

                                    <p class="display-font text-4xl mt-1">
                                        ৳<span id="grandTotal">1670</span>
                                    </p>

                                </div>


                                <span class="text-xs text-[#d7bd91]">
                                    COD Available
                                </span>

                            </div>


                            <button type="submit"
                                class="w-full bg-[#d7bd91] text-[#241719] py-4 rounded-xl font-bold hover:bg-[#e4d1af] transition">

                                Confirm My Order

                                <span class="ml-2">
                                    →
                                </span>

                            </button>


                            <p class="text-center text-xs text-white/30 mt-5">
                                Your information is kept private and secure.
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

    <section id="reviews" class="py-20 lg:py-28 bg-[#e9e1d6]">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-7">

                <div>

                    <p class="text-xs uppercase tracking-[.3em] text-[#8f3a42] font-bold">
                        From Our Customers
                    </p>

                    <h2 class="display-font text-4xl sm:text-5xl lg:text-6xl mt-4">
                        Worn. Loved. Reordered.
                    </h2>

                </div>


                <div class="flex items-center gap-4">

                    <span class="display-font text-4xl">
                        4.9
                    </span>

                    <div>

                        <div class="text-[#8f3a42] tracking-widest">
                            ★★★★★
                        </div>

                        <p class="text-xs text-[#241719]/45 mt-1">
                            1,200+ verified reviews
                        </p>

                    </div>

                </div>

            </div>



            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 mt-14">


                @foreach ([['AH', 'Arif Hasan', 'Dhaka', 'The fabric feels premium and the fitting is exactly what I wanted. It looks much better in person.'], ['FR', 'Fahim Rahman', 'Chattogram', 'Ordered it for Eid. Delivery was quick and the Panjabi looked very elegant. Definitely buying again.'], ['SA', 'Sakib Ahmed', 'Sylhet', 'Really comfortable throughout the day. The stitching and finishing are excellent.'], ['MR', 'Mahmud Rahman', 'Narayanganj', 'The color was exactly like the picture. Quality is impressive for the price.'], ['TN', 'Tanvir Noor', 'Gazipur', 'Bought this for my brother. He loved the fit and the packaging was also very nice.'], ['IH', 'Imran Hossain', 'Rajshahi', 'This is my second order. Great fabric, clean design and very fast delivery.']] as $review)
                    <article class="bg-[#f8f5ef] rounded-[2rem] p-7 soft-shadow">

                        <div class="flex justify-between items-center">

                            <div class="text-[#8f3a42] tracking-widest text-sm">
                                ★★★★★
                            </div>

                            <span
                                class="text-[9px] uppercase tracking-wider text-green-700 bg-green-50 px-2.5 py-1 rounded-full">
                                Verified
                            </span>

                        </div>


                        <p class="text-[#241719]/65 leading-7 mt-6">
                            "{{ $review[3] }}"
                        </p>


                        <div class="flex items-center gap-3 mt-7">

                            <div
                                class="w-10 h-10 rounded-full bg-[#241719] text-white flex items-center justify-center text-xs font-semibold">
                                {{ $review[0] }}
                            </div>

                            <div>

                                <p class="font-semibold text-sm">
                                    {{ $review[1] }}
                                </p>

                                <p class="text-xs text-[#241719]/40 mt-0.5">
                                    {{ $review[2] }}
                                </p>

                            </div>

                        </div>

                    </article>
                @endforeach

            </div>

        </div>

    </section>



    {{-- =========================================================
    FINAL CTA
========================================================== --}}

    <section class="bg-[#8f3a42] text-white">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28 text-center">

            <p class="text-[#ead6b5] text-xs uppercase tracking-[.3em] font-bold">
                KHIMMER / 2026
            </p>


            <h2 class="display-font text-4xl sm:text-5xl lg:text-6xl mt-6 leading-tight">

                Wear something
                <span class="italic">
                    meaningful.
                </span>

            </h2>


            <p class="text-white/65 max-w-xl mx-auto mt-6 leading-7">

                Traditional at heart. Contemporary in spirit.
                Find your next Panjabi from the KHIMMER collection.

            </p>


            <a href="#order"
                class="inline-flex bg-[#f3e4c8] text-[#241719] px-8 py-4 rounded-full font-bold mt-9 hover:bg-white transition">

                Shop KHIMMER

                <span class="ml-3">
                    →
                </span>

            </a>

        </div>

    </section>



    {{-- =========================================================
    FOOTER
========================================================== --}}

    <footer class="bg-[#241719] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">


            <div class="flex flex-col md:flex-row justify-between gap-8">

                <div>

                    <h3 class="display-font text-2xl tracking-[.18em]">
                        KHIMMER
                    </h3>

                    <p class="text-white/35 text-sm max-w-sm mt-3 leading-6">
                        Contemporary clothing inspired by
                        Bengali heritage.
                    </p>

                </div>


                <div class="flex gap-6 text-sm text-white/45">

                    <a href="#" class="hover:text-white transition">
                        Facebook
                    </a>

                    <a href="#" class="hover:text-white transition">
                        Instagram
                    </a>

                    <a href="#collection" class="hover:text-white transition">
                        Collection
                    </a>

                    <a href="#order" class="hover:text-white transition">
                        Order
                    </a>

                </div>

            </div>


            <div class="border-t border-white/10 mt-10 pt-6 flex flex-col sm:flex-row justify-between gap-3">

                <p class="text-xs text-white/25">
                    © {{ date('Y') }} KHIMMER. All rights reserved.
                </p>

                <p class="text-xs text-white/25">
                    Made for the modern Bengali man.
                </p>

            </div>

        </div>

    </footer>



    {{-- =========================================================
    JAVASCRIPT
========================================================== --}}

    <script>
        const PRODUCT_PRICE = 1590;


        function changeQuantity(change) {

            const input = document.getElementById('quantity');

            let quantity = parseInt(input.value) || 1;

            quantity += change;

            quantity = Math.max(1, Math.min(10, quantity));

            input.value = quantity;

            updateOrderSummary();

        }



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


            document.getElementById('productTotal')
                .textContent = subtotal;


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
    </script>



    {{-- =========================================================
    FANCYBOX
========================================================== --}}

    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>

    <script>
        Fancybox.bind(
            '[data-fancybox="khimmer-gallery"]', {
                animated: true,

                showClass: 'f-fadeIn',

                hideClass: 'f-fadeOut',

                Images: {
                    zoom: true,
                },

                Carousel: {

                    Toolbar: {

                        display: {

                            left: [
                                "infobar"
                            ],

                            middle: [
                                "prev",
                                "next"
                            ],

                            right: [
                                "slideshow",
                                "fullscreen",
                                "close"
                            ]

                        }

                    }

                }

            }
        );
    </script>


</body>

</html>
