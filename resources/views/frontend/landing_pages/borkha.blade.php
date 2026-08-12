<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Modest Collection | Borkha & Khimar</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .hero-gradient {
            background:
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, .15), transparent 30%),
                linear-gradient(135deg, #24352d 0%, #31493d 50%, #18251f 100%);
        }

        .soft-shadow {
            box-shadow: 0 20px 60px rgba(20, 35, 28, .12);
        }
    </style>
</head>

<body class="bg-[#faf9f6] text-[#26332c]">

    {{-- =========================================================
        TOP ANNOUNCEMENT
    ========================================================== --}}
    <div class="bg-[#1d2b24] text-white text-center text-sm py-2.5 px-4">
        ✨ Free Delivery on orders above ৳1500 | Cash on Delivery Available
    </div>


    {{-- =========================================================
        NAVBAR
    ========================================================== --}}
    <header class="bg-white border-b border-black/5 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="h-20 flex items-center justify-center">

                <a href="#" class="text-2xl sm:text-3xl font-serif font-semibold tracking-wide">
                    T-Shirt
                </a>

            </div>
        </div>
    </header>


    {{-- =========================================================
        HERO
    ========================================================== --}}
    <section class="hero-gradient text-white overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 items-center min-h-[650px] py-16 lg:py-20 gap-12">

                {{-- Hero Content --}}
                <div>

                    <span
                        class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm mb-7">
                        <span class="w-2 h-2 rounded-full bg-[#d7c3a3]"></span>
                        New Modest Collection 2026
                    </span>

                    <h1 class="font-serif text-5xl sm:text-6xl lg:text-7xl leading-[1.05] font-medium">
                        Elegance
                        <span class="block text-[#d7c3a3]">
                            in Every Layer.
                        </span>
                    </h1>

                    <p class="mt-7 text-white/75 text-lg leading-8 max-w-xl">
                        Discover our carefully crafted collection of
                        premium Borkha, Khimar, Hijab and Abaya —
                        designed for comfort, modesty and timeless elegance.
                    </p>

                    <div class="mt-9 flex flex-col sm:flex-row gap-4">

                        <a href="#collection"
                            class="inline-flex justify-center items-center bg-white text-[#24352d] px-7 py-3.5 rounded-full font-semibold hover:bg-[#eeeae1] transition">
                            Explore Collection
                            <span class="ml-2">→</span>
                        </a>

                        <a href="#why-us"
                            class="inline-flex justify-center items-center border border-white/30 px-7 py-3.5 rounded-full font-semibold hover:bg-white/10 transition">
                            Why Choose Us
                        </a>

                    </div>

                    <div class="mt-12 flex items-center gap-8 text-sm">

                        <div>
                            <p class="text-2xl font-semibold">10K+</p>
                            <p class="text-white/60 mt-1">Happy Customers</p>
                        </div>

                        <div class="h-10 w-px bg-white/20"></div>

                        <div>
                            <p class="text-2xl font-semibold">4.9/5</p>
                            <p class="text-white/60 mt-1">Customer Rating</p>
                        </div>

                    </div>

                </div>


                {{-- Hero Image --}}
                <div class="relative">

                    <div class="absolute -inset-5 bg-[#d7c3a3]/10 rounded-[3rem] blur-2xl"></div>

                    <div class="relative rounded-[2.5rem] overflow-hidden aspect-[4/5] soft-shadow">

                        <img src="https://img.drz.lazcdn.com/static/bd/p/5eae66fac0f11b41cd58553e7d8ce817.jpg_720x720q80.jpg"
                            alt="Modest fashion" class="w-full h-full object-cover">

                        <div class="absolute bottom-5 left-5 right-5">
                            <div
                                class="bg-white/90 backdrop-blur-md text-[#26332c] rounded-2xl p-4 flex items-center justify-between">

                                <div>
                                    <p class="text-xs text-gray-500">
                                        Featured Collection
                                    </p>

                                    <p class="font-semibold mt-1">
                                        Premium Everyday Khimar
                                    </p>
                                </div>

                                <span class="text-lg font-semibold">
                                    ৳1,290
                                </span>

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

            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-black/5">

                <div class="py-7 text-center">
                    <p class="text-xl">🚚</p>
                    <p class="font-semibold mt-2 text-sm">Fast Delivery</p>
                    <p class="text-xs text-gray-500 mt-1">Across Bangladesh</p>
                </div>

                <div class="py-7 text-center">
                    <p class="text-xl">✓</p>
                    <p class="font-semibold mt-2 text-sm">Premium Fabric</p>
                    <p class="text-xs text-gray-500 mt-1">Comfortable & Soft</p>
                </div>

                <div class="py-7 text-center">
                    <p class="text-xl">↺</p>
                    <p class="font-semibold mt-2 text-sm">Easy Exchange</p>
                    <p class="text-xs text-gray-500 mt-1">Simple Process</p>
                </div>

                <div class="py-7 text-center">
                    <p class="text-xl">♡</p>
                    <p class="font-semibold mt-2 text-sm">Made With Care</p>
                    <p class="text-xs text-gray-500 mt-1">Quality First</p>
                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        COLLECTION
    ========================================================== --}}
    <section id="collection" class="py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto">

                <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                    Our Collection
                </span>

                <h2 class="font-serif text-4xl sm:text-5xl mt-4">
                    Modesty Meets Elegance
                </h2>

                <p class="text-gray-500 mt-5 leading-7">
                    Choose from our thoughtfully designed pieces for
                    everyday wear, prayer, work and special occasions.
                </p>

            </div>


            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-14">

                {{-- Category 1 --}}
                <a href="#" class="group">

                    <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-100">

                        <img src="https://images.unsplash.com/photo-1591369822096-ffd140ec948f?auto=format&fit=crop&w=700&q=80"
                            alt="Borkha"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </div>

                    <div class="mt-4 flex justify-between items-center">

                        <div>
                            <h3 class="font-semibold text-lg">
                                Borkha
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Elegant everyday wear
                            </p>
                        </div>

                        <span class="text-xl group-hover:translate-x-1 transition">
                            →
                        </span>

                    </div>

                </a>


                {{-- Category 2 --}}
                <a href="#" class="group">

                    <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-100">

                        <img src="https://img.drz.lazcdn.com/static/bd/p/5eae66fac0f11b41cd58553e7d8ce817.jpg_720x720q80.jpg"
                            alt="Khimar"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </div>

                    <div class="mt-4 flex justify-between items-center">

                        <div>
                            <h3 class="font-semibold text-lg">
                                Khimar
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Lightweight & comfortable
                            </p>
                        </div>

                        <span class="text-xl group-hover:translate-x-1 transition">
                            →
                        </span>

                    </div>

                </a>


                {{-- Category 3 --}}
                <a href="#" class="group">

                    <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-100">

                        <img src="https://images.unsplash.com/photo-1601924994987-69e26d50dc26?auto=format&fit=crop&w=700&q=80"
                            alt="Hijab"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </div>

                    <div class="mt-4 flex justify-between items-center">

                        <div>
                            <h3 class="font-semibold text-lg">
                                Hijab
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Soft & breathable
                            </p>
                        </div>

                        <span class="text-xl group-hover:translate-x-1 transition">
                            →
                        </span>

                    </div>

                </a>


                {{-- Category 4 --}}
                <a href="#" class="group">

                    <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-100">

                        <img src="https://images.unsplash.com/photo-1594223274512-ad4803739b7c?auto=format&fit=crop&w=700&q=80"
                            alt="Abaya"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-700">

                    </div>

                    <div class="mt-4 flex justify-between items-center">

                        <div>
                            <h3 class="font-semibold text-lg">
                                Abaya
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Timeless & graceful
                            </p>
                        </div>

                        <span class="text-xl group-hover:translate-x-1 transition">
                            →
                        </span>

                    </div>

                </a>

            </div>

        </div>

    </section>


    {{-- =========================================================
        FEATURED PRODUCT
    ========================================================== --}}
    <section class="bg-[#eeeae1] py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

                <div class="rounded-[2.5rem] overflow-hidden">

                    <img src="https://img.drz.lazcdn.com/static/bd/p/5eae66fac0f11b41cd58553e7d8ce817.jpg_720x720q80.jpg"
                        alt="Premium Khimar" class="w-full aspect-square object-cover">

                </div>


                <div>

                    <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                        Best Seller
                    </span>

                    <h2 class="font-serif text-4xl sm:text-5xl mt-4 leading-tight">
                        Premium Everyday
                        <span class="text-[#71877a]">
                            Khimar
                        </span>
                    </h2>

                    <p class="text-gray-600 leading-7 mt-6">
                        Designed for women who want effortless modesty
                        without compromising comfort. Our premium khimar
                        uses soft, breathable fabric that feels comfortable
                        throughout the day.
                    </p>


                    <div class="mt-7 space-y-3">

                        <div class="flex items-center gap-3">
                            <span
                                class="w-6 h-6 rounded-full bg-[#26382e] text-white flex items-center justify-center text-xs">
                                ✓
                            </span>

                            <span class="text-sm">
                                Premium breathable fabric
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                class="w-6 h-6 rounded-full bg-[#26382e] text-white flex items-center justify-center text-xs">
                                ✓
                            </span>

                            <span class="text-sm">
                                Comfortable all-day fit
                            </span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span
                                class="w-6 h-6 rounded-full bg-[#26382e] text-white flex items-center justify-center text-xs">
                                ✓
                            </span>

                            <span class="text-sm">
                                Multiple colors available
                            </span>
                        </div>

                    </div>


                    <div class="flex items-center gap-5 mt-9">

                        <div>
                            <span class="text-3xl font-semibold">
                                ৳1,290
                            </span>

                            <span class="ml-2 text-gray-400 line-through">
                                ৳1,590
                            </span>
                        </div>

                        <span class="bg-[#26382e] text-white text-xs px-3 py-1.5 rounded-full">
                            19% OFF
                        </span>

                    </div>


                    <a href="#order"
                        class="mt-8 inline-flex bg-[#26382e] text-white px-8 py-4 rounded-full font-semibold hover:bg-[#17231d] transition">
                        Order Now
                        <span class="ml-3">→</span>
                    </a>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        ORDER FORM
    ========================================================== --}}
    <section id="order" class="py-20 lg:py-28 bg-[#faf9f6]">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto mb-12">

                <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                    Place Your Order
                </span>

                <h2 class="font-serif text-4xl sm:text-5xl mt-4">
                    Order With Confidence
                </h2>

                <p class="text-gray-500 mt-5 leading-7">
                    Fill in your details below and we'll deliver your
                    premium khimar straight to your door.
                </p>

            </div>


            <form action="#" method="POST" class="grid lg:grid-cols-5 gap-6 lg:gap-8 items-start">

                {{-- Order Summary (sticky on desktop) --}}
                <div class="lg:col-span-2 lg:sticky lg:top-28">

                    <div class="rounded-3xl overflow-hidden bg-white border border-black/5 soft-shadow">

                        <img src="https://img.drz.lazcdn.com/static/bd/p/5eae66fac0f11b41cd58553e7d8ce817.jpg_720x720q80.jpg"
                            alt="Premium Khimar" class="w-full h-56 object-cover">

                        <div class="p-6">

                            <span class="text-[#71877a] text-xs font-semibold uppercase tracking-[.2em]">
                                Best Seller
                            </span>

                            <h3 class="font-serif text-2xl mt-2">
                                Premium Everyday Khimar
                            </h3>

                            <div class="flex items-center gap-2 mt-3 text-[#c39b5c]">
                                ★★★★★
                                <span class="text-gray-400 text-xs ml-1">(4.9 · 1,284 reviews)</span>
                            </div>

                            <div class="flex items-end gap-3 mt-5">
                                <span class="text-3xl font-semibold">৳1,290</span>
                                <span class="text-gray-400 line-through mb-1">৳1,590</span>
                                <span class="bg-[#26382e] text-white text-xs px-3 py-1.5 rounded-full mb-1">
                                    19% OFF
                                </span>
                            </div>

                            <ul class="mt-5 space-y-2.5 text-sm text-gray-600">

                                <li class="flex items-center gap-3">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#26382e] text-white flex items-center justify-center text-[10px]">✓</span>
                                    Premium breathable fabric
                                </li>

                                <li class="flex items-center gap-3">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#26382e] text-white flex items-center justify-center text-[10px]">✓</span>
                                    Matching hijab included
                                </li>

                                <li class="flex items-center gap-3">
                                    <span
                                        class="w-5 h-5 rounded-full bg-[#26382e] text-white flex items-center justify-center text-[10px]">✓</span>
                                    Free delivery over ৳1500
                                </li>

                            </ul>

                            {{-- Live total --}}
                            <div class="border-t border-black/5 mt-6 pt-5 space-y-2 text-sm">

                                <div class="flex justify-between text-gray-500">
                                    <span>Subtotal (<span id="qty-label">1</span> × ৳1,290)</span>
                                    <span id="sum-subtotal">৳1,290</span>
                                </div>

                                <div class="flex justify-between text-gray-500">
                                    <span>Shipping</span>
                                    <span id="sum-shipping">৳60</span>
                                </div>

                                <div
                                    class="flex justify-between font-semibold text-lg text-[#26332c] pt-2 border-t border-black/5">
                                    <span>Total</span>
                                    <span id="sum-total">৳1,350</span>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Order Fields --}}
                <div class="lg:col-span-3">

                    <div class="bg-white rounded-3xl border border-black/5 soft-shadow p-6 sm:p-8 lg:p-10 space-y-7">

                        {{-- Quantity & Size --}}
                        <div class="grid sm:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold mb-2.5">Quantity</label>
                                <div
                                    class="inline-flex items-center border border-black/10 rounded-full overflow-hidden">
                                    <button type="button" onclick="updateQty(-1)"
                                        class="w-11 h-11 text-lg text-gray-500 hover:bg-gray-50 transition">−</button>
                                    <input id="qty" type="text" value="1" readonly
                                        class="w-12 text-center font-semibold bg-transparent focus:outline-none">
                                    <button type="button" onclick="updateQty(1)"
                                        class="w-11 h-11 text-lg text-gray-500 hover:bg-gray-50 transition">+</button>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2.5">Size</label>
                                <select
                                    class="w-full rounded-xl border border-black/10 bg-white px-4 py-3 text-sm focus:border-[#26382e] focus:ring-2 focus:ring-[#26382e]/20 focus:outline-none transition">
                                    <option>S — up to 5'2"</option>
                                    <option selected>M — 5'2" to 5'5"</option>
                                    <option>L — 5'5" to 5'8"</option>
                                    <option>XL — 5'8"+</option>
                                </select>
                            </div>

                        </div>


                        {{-- Colour --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2.5">Colour</label>
                            <div class="flex flex-wrap gap-3" id="color-picker">
                                <button type="button" data-name="Forest Green" style="background:#26382e"
                                    class="color-btn w-10 h-10 rounded-full border-2 border-[#26382e] ring-2 ring-[#26382e]/30"
                                    aria-label="Forest Green"></button>
                                <button type="button" data-name="Charcoal" style="background:#2b2b2b"
                                    class="color-btn w-10 h-10 rounded-full border-2 border-transparent hover:ring-2 hover:ring-black/10 transition"
                                    aria-label="Charcoal"></button>
                                <button type="button" data-name="Sand Beige" style="background:#d7c3a3"
                                    class="color-btn w-10 h-10 rounded-full border-2 border-transparent hover:ring-2 hover:ring-black/10 transition"
                                    aria-label="Sand Beige"></button>
                                <button type="button" data-name="Dusty Rose" style="background:#c98b86"
                                    class="color-btn w-10 h-10 rounded-full border-2 border-transparent hover:ring-2 hover:ring-black/10 transition"
                                    aria-label="Dusty Rose"></button>
                                <button type="button" data-name="Ivory" style="background:#f3efe6"
                                    class="color-btn w-10 h-10 rounded-full border-2 border-transparent hover:ring-2 hover:ring-black/10 transition"
                                    aria-label="Ivory"></button>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Selected: <span id="color-label"
                                    class="font-medium text-gray-600">Forest Green</span></p>
                        </div>


                        {{-- Contact info --}}
                        <div class="grid sm:grid-cols-2 gap-5">

                            <div>
                                <label class="block text-sm font-semibold mb-2.5">Full Name <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="name" required placeholder="e.g. Ayesha Rahman"
                                    class="w-full rounded-xl border border-black/10 bg-[#faf9f6] px-4 py-3 text-sm focus:border-[#26382e] focus:ring-2 focus:ring-[#26382e]/20 focus:outline-none transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2.5">Phone Number <span
                                        class="text-red-500">*</span></label>
                                <input type="tel" name="phone" required placeholder="01XXX XXXXXX"
                                    class="w-full rounded-xl border border-black/10 bg-[#faf9f6] px-4 py-3 text-sm focus:border-[#26382e] focus:ring-2 focus:ring-[#26382e]/20 focus:outline-none transition">
                            </div>

                        </div>


                        {{-- Address --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2.5">Delivery Address <span
                                    class="text-red-500">*</span></label>
                            <textarea name="address" rows="3" required placeholder="House, Road, Area, Thana, City"
                                class="w-full rounded-xl border border-black/10 bg-[#faf9f6] px-4 py-3 text-sm focus:border-[#26382e] focus:ring-2 focus:ring-[#26382e]/20 focus:outline-none transition"></textarea>
                        </div>


                        {{-- Note --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2.5">Order Note <span
                                    class="text-gray-400 font-normal">(optional)</span></label>
                            <textarea name="note" rows="2" placeholder="Any special instruction for delivery..."
                                class="w-full rounded-xl border border-black/10 bg-[#faf9f6] px-4 py-3 text-sm focus:border-[#26382e] focus:ring-2 focus:ring-[#26382e]/20 focus:outline-none transition"></textarea>
                        </div>


                        {{-- Shipping Method --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2.5">Shipping Method</label>
                            <div class="grid sm:grid-cols-3 gap-3" id="shipping-picker">

                                <label class="ship-option cursor-pointer">
                                    <input type="radio" name="shipping" value="standard" data-cost="60" checked
                                        class="peer sr-only">
                                    <div
                                        class="rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <p class="font-semibold text-sm">Standard</p>
                                        <p class="text-xs text-gray-500 mt-1">3–5 days · ৳60</p>
                                    </div>
                                </label>

                                <label class="ship-option cursor-pointer">
                                    <input type="radio" name="shipping" value="express" data-cost="120"
                                        class="peer sr-only">
                                    <div
                                        class="rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <p class="font-semibold text-sm">Express</p>
                                        <p class="text-xs text-gray-500 mt-1">1–2 days · ৳120</p>
                                    </div>
                                </label>

                                <label class="ship-option cursor-pointer">
                                    <input type="radio" name="shipping" value="pickup" data-cost="0"
                                        class="peer sr-only">
                                    <div
                                        class="rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <p class="font-semibold text-sm">Pickup</p>
                                        <p class="text-xs text-gray-500 mt-1">Free · Dhaka</p>
                                    </div>
                                </label>

                            </div>
                        </div>


                        {{-- Payment Method --}}
                        <div>
                            <label class="block text-sm font-semibold mb-2.5">Payment Method</label>
                            <div class="space-y-3" id="payment-picker">

                                <label class="pay-option cursor-pointer block">
                                    <input type="radio" name="payment" value="cod" checked
                                        class="peer sr-only">
                                    <div
                                        class="flex items-center gap-4 rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <span
                                            class="w-11 h-11 rounded-xl bg-[#26382e] text-white flex items-center justify-center text-lg">💵</span>
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm">Cash on Delivery</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Pay when your package arrives</p>
                                        </div>
                                        <span
                                            class="w-5 h-5 rounded-full border-2 border-black/15 peer-checked:border-[#26382e] peer-checked:bg-[#26382e] flex items-center justify-center text-white text-[10px]">✓</span>
                                    </div>
                                </label>

                                <label class="pay-option cursor-pointer block">
                                    <input type="radio" name="payment" value="bkash" class="peer sr-only">
                                    <div
                                        class="flex items-center gap-4 rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <span
                                            class="w-11 h-11 rounded-xl bg-[#E2136E] text-white flex items-center justify-center font-bold text-sm">bK</span>
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm">bKash</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Send to 01XXXXXXXXX</p>
                                        </div>
                                        <span
                                            class="w-5 h-5 rounded-full border-2 border-black/15 peer-checked:border-[#26382e] peer-checked:bg-[#26382e] flex items-center justify-center text-white text-[10px]">✓</span>
                                    </div>
                                </label>

                                <label class="pay-option cursor-pointer block">
                                    <input type="radio" name="payment" value="card" class="peer sr-only">
                                    <div
                                        class="flex items-center gap-4 rounded-2xl border-2 border-black/10 peer-checked:border-[#26382e] peer-checked:bg-[#26382e]/5 p-4 transition hover:border-[#26382e]/40">
                                        <span
                                            class="w-11 h-11 rounded-xl bg-[#1a1a4f] text-white flex items-center justify-center text-lg">💳</span>
                                        <div class="flex-1">
                                            <p class="font-semibold text-sm">Visa / Mastercard</p>
                                            <p class="text-xs text-gray-500 mt-0.5">Secure card payment</p>
                                        </div>
                                        <span
                                            class="w-5 h-5 rounded-full border-2 border-black/15 peer-checked:border-[#26382e] peer-checked:bg-[#26382e] flex items-center justify-center text-white text-[10px]">✓</span>
                                    </div>
                                </label>

                            </div>
                        </div>


                        {{-- Submit --}}
                        <div class="pt-2">

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center bg-[#26382e] text-white px-8 py-4 rounded-full font-semibold hover:bg-[#17231d] transition shadow-lg shadow-[#26382e]/20 hover:shadow-xl hover:shadow-[#26382e]/30 hover:-translate-y-0.5">
                                Place Order — <span id="btn-total">৳1,350</span>
                                <span class="ml-3">→</span>
                            </button>

                            <p class="text-center text-xs text-gray-400 mt-4">
                                🔒 Your information is encrypted and secure. By placing your order you agree to our
                                <a href="#" class="text-[#71877a] underline">Terms</a> &
                                <a href="#" class="text-[#71877a] underline">Privacy Policy</a>.
                            </p>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </section>


    {{-- =========================================================
        WHY US
    ========================================================== --}}
    <section id="why-us" class="py-20 lg:py-28 bg-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto">

                <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                    Why Choose Us
                </span>

                <h2 class="font-serif text-4xl sm:text-5xl mt-4">
                    Made For Your Everyday
                </h2>

            </div>


            <div class="grid md:grid-cols-3 gap-6 mt-14">

                <div class="bg-[#faf9f6] rounded-3xl p-8">

                    <div
                        class="w-12 h-12 rounded-2xl bg-[#26382e] text-white flex items-center justify-center text-xl">
                        ✦
                    </div>

                    <h3 class="text-xl font-semibold mt-6">
                        Premium Quality
                    </h3>

                    <p class="text-gray-500 leading-7 mt-3">
                        We carefully select fabrics that feel soft,
                        breathable and comfortable against your skin.
                    </p>

                </div>


                <div class="bg-[#faf9f6] rounded-3xl p-8">

                    <div
                        class="w-12 h-12 rounded-2xl bg-[#26382e] text-white flex items-center justify-center text-xl">
                        ♡
                    </div>

                    <h3 class="text-xl font-semibold mt-6">
                        Designed With Care
                    </h3>

                    <p class="text-gray-500 leading-7 mt-3">
                        Every piece is thoughtfully designed around
                        modesty, comfort and everyday practicality.
                    </p>

                </div>


                <div class="bg-[#faf9f6] rounded-3xl p-8">

                    <div
                        class="w-12 h-12 rounded-2xl bg-[#26382e] text-white flex items-center justify-center text-xl">
                        ✓
                    </div>

                    <h3 class="text-xl font-semibold mt-6">
                        Customer First
                    </h3>

                    <p class="text-gray-500 leading-7 mt-3">
                        From ordering to delivery, we're committed to
                        making your shopping experience simple and reliable.
                    </p>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        SIZE GUIDE
    ========================================================== --}}
    <section id="size-guide" class="py-20 bg-[#f2f0ea]">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center">

                <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                    Perfect Fit
                </span>

                <h2 class="font-serif text-4xl mt-4">
                    Find Your Perfect Size
                </h2>

                <p class="text-gray-500 mt-4">
                    Not sure which size to choose? Here's our simple guide.
                </p>

            </div>


            <div class="mt-10 bg-white rounded-3xl overflow-hidden border border-black/5">

                <div class="grid grid-cols-3 bg-[#26382e] text-white text-sm font-semibold">

                    <div class="p-4">
                        Size
                    </div>

                    <div class="p-4">
                        Length
                    </div>

                    <div class="p-4">
                        Recommended Height
                    </div>

                </div>


                <div class="divide-y divide-gray-100 text-sm">

                    <div class="grid grid-cols-3">
                        <div class="p-4 font-semibold">S</div>
                        <div class="p-4 text-gray-500">52"</div>
                        <div class="p-4 text-gray-500">Up to 5'2"</div>
                    </div>

                    <div class="grid grid-cols-3">
                        <div class="p-4 font-semibold">M</div>
                        <div class="p-4 text-gray-500">54"</div>
                        <div class="p-4 text-gray-500">5'2" – 5'5"</div>
                    </div>

                    <div class="grid grid-cols-3">
                        <div class="p-4 font-semibold">L</div>
                        <div class="p-4 text-gray-500">56"</div>
                        <div class="p-4 text-gray-500">5'5" – 5'8"</div>
                    </div>

                    <div class="grid grid-cols-3">
                        <div class="p-4 font-semibold">XL</div>
                        <div class="p-4 text-gray-500">58"</div>
                        <div class="p-4 text-gray-500">5'8"+</div>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        REVIEWS
    ========================================================== --}}
    <section id="reviews" class="py-20 lg:py-28">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center">

                <span class="text-[#71877a] text-sm font-semibold uppercase tracking-[.2em]">
                    Customer Love
                </span>

                <h2 class="font-serif text-4xl sm:text-5xl mt-4">
                    Loved By Women Like You
                </h2>

            </div>


            <div class="grid md:grid-cols-3 gap-6 mt-14">

                <div class="bg-white border border-black/5 rounded-3xl p-7 soft-shadow">

                    <div class="text-[#c39b5c]">
                        ★★★★★
                    </div>

                    <p class="text-gray-600 leading-7 mt-5">
                        "The fabric is honestly amazing. Very comfortable
                        and the fitting is exactly what I wanted."
                    </p>

                    <div class="mt-6">
                        <p class="font-semibold">Nusrat Jahan</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Verified Customer
                        </p>
                    </div>

                </div>


                <div class="bg-white border border-black/5 rounded-3xl p-7 soft-shadow">

                    <div class="text-[#c39b5c]">
                        ★★★★★
                    </div>

                    <p class="text-gray-600 leading-7 mt-5">
                        "I ordered the khimar for everyday use and
                        absolutely loved it. Will definitely order again."
                    </p>

                    <div class="mt-6">
                        <p class="font-semibold">Sumaiya Ahmed</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Verified Customer
                        </p>
                    </div>

                </div>


                <div class="bg-white border border-black/5 rounded-3xl p-7 soft-shadow">

                    <div class="text-[#c39b5c]">
                        ★★★★★
                    </div>

                    <p class="text-gray-600 leading-7 mt-5">
                        "Beautiful packaging, fast delivery and the
                        quality exceeded my expectations."
                    </p>

                    <div class="mt-6">
                        <p class="font-semibold">Ayesha Rahman</p>
                        <p class="text-xs text-gray-400 mt-1">
                            Verified Customer
                        </p>
                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- =========================================================
        FINAL CTA
    ========================================================== --}}
    <section class="bg-[#26382e] text-white">

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center py-20 lg:py-28">

            <span class="text-[#d7c3a3] text-sm font-semibold uppercase tracking-[.2em]">
                Your Modest Wardrobe
            </span>

            <h2 class="font-serif text-4xl sm:text-5xl lg:text-6xl mt-5 leading-tight">
                Dress With Grace.
                <span class="block text-[#d7c3a3]">
                    Feel Beautiful.
                </span>
            </h2>

            <p class="text-white/65 max-w-xl mx-auto mt-6 leading-7">
                Explore our collection of Borkha, Khimar, Hijab and
                Abaya designed for your everyday life.
            </p>

            <a href="#collection"
                class="inline-flex mt-9 bg-white text-[#26382e] px-8 py-4 rounded-full font-semibold hover:bg-[#eeeae1] transition">
                Shop The Collection
                <span class="ml-3">→</span>
            </a>

        </div>

    </section>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}
    <footer class="bg-[#17231d] text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="flex flex-col md:flex-row justify-between gap-8">

                <div>

                    <h3 class="font-serif text-2xl">
                        NOOR
                    </h3>

                    <p class="text-white/50 text-sm mt-3 max-w-sm">
                        Modest fashion designed with comfort,
                        quality and elegance in mind.
                    </p>

                </div>


                <div class="flex gap-8 text-sm text-white/60">

                    <a href="#" class="hover:text-white transition">
                        Facebook
                    </a>

                    <a href="#" class="hover:text-white transition">
                        Instagram
                    </a>

                    <a href="#" class="hover:text-white transition">
                        Contact
                    </a>

                </div>

            </div>


            <div class="border-t border-white/10 mt-10 pt-6 text-center text-xs text-white/40">
                © {{ date('Y') }} NOOR. All rights reserved.
            </div>

        </div>

    </footer>


    {{-- =========================================================
        FLOATING WHATSAPP
    ========================================================== --}}
    <a href="https://wa.me/8801000000000" target="_blank"
        class="fixed right-5 bottom-5 z-50 w-14 h-14 rounded-full bg-[#25D366] text-white flex items-center justify-center text-2xl shadow-xl hover:scale-110 transition"
        aria-label="Contact on WhatsApp">
        ☎
    </a>


    {{-- =========================================================
        ORDER FORM SCRIPT
    ========================================================== --}}
    <script>
        const UNIT = 1290;
        const fmt = n => '৳' + n.toLocaleString('en-US');

        function recalc() {
            const qty = Math.max(1, parseInt(document.getElementById('qty').value) || 1);
            const ship = parseInt(document.querySelector('input[name="shipping"]:checked')?.dataset.cost || 0);
            const subtotal = qty * UNIT;
            const total = subtotal + ship;

            document.getElementById('qty-label').textContent = qty;
            document.getElementById('sum-subtotal').textContent = fmt(subtotal);
            document.getElementById('sum-shipping').textContent = ship === 0 ? 'Free' : fmt(ship);
            document.getElementById('sum-total').textContent = fmt(total);
            document.getElementById('btn-total').textContent = fmt(total);
        }

        function updateQty(delta) {
            const el = document.getElementById('qty');
            const next = Math.max(1, (parseInt(el.value) || 1) + delta);
            el.value = next;
            recalc();
        }

        // Colour picker
        document.querySelectorAll('#color-picker .color-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('#color-picker .color-btn').forEach(b => {
                    b.classList.remove('border-[#26382e]', 'ring-2', 'ring-[#26382e]/30');
                    b.classList.add('border-transparent');
                });
                btn.classList.remove('border-transparent');
                btn.classList.add('border-[#26382e]', 'ring-2', 'ring-[#26382e]/30');
                document.getElementById('color-label').textContent = btn.dataset.name;
            });
        });

        // Shipping change
        document.querySelectorAll('input[name="shipping"]').forEach(input => {
            input.addEventListener('change', recalc);
        });

        recalc();
    </script>

</body>

</html>
