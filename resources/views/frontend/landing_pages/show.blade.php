<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $landingPage->meta_title ?? $landingPage->title }}</title>
    <meta name="description" content="{{ $landingPage->meta_description ?? ($landingPage->short_description ?? '') }}" />
    @if ($landingPage->meta_image)
        <meta property="og:image" content="{{ uploaded_asset($landingPage->meta_image) }}" />
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@400;600;700&family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #7F1D1D;
            --ink-soft: #991B1B;
            --canvas: #FFFDF5;
            --moss: #DC2626;
            --clay: #EAB308;
            --gold: #FACC15;
            --cream: #FFFBEB;
            --red: #DC2626;
            --red-dark: #991B1B;
            --yellow: #FACC15;
            --yellow-dark: #CA8A04;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Hind Siliguri', sans-serif;
            background: var(--canvas);
            color: var(--ink);
        }

        .display {
            font-family: 'Noto Serif Bengali', serif;
        }

        .num {
            font-family: 'Inter', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #e6e6dd;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--moss);
            border-radius: 20px;
        }

        .capsule {
            border-radius: 999px;
        }

        .card {
            background: #fff;
            border: 1px solid rgba(20, 46, 34, 0.07);
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px -20px rgba(20, 46, 34, 0.25);
        }

        .btn-primary {
            background: var(--ink);
            color: #fff;
            transition: transform .15s ease, background .15s ease;
        }

        .btn-primary:hover {
            background: var(--ink-soft);
            transform: translateY(-1px);
        }

        .btn-clay {
            background: var(--clay);
            color: #fff;
            box-shadow: 0 10px 24px -10px rgba(181, 82, 46, 0.55);
            transition: transform .15s ease, box-shadow .15s ease;
        }

        .btn-clay:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 30px -10px rgba(181, 82, 46, 0.6);
        }

        .btn-ghost {
            background: transparent;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.35);
            transition: all .2s ease;
        }

        .btn-ghost:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fff;
        }

        input:focus,
        textarea:focus {
            outline: none;
            border-color: var(--moss);
            box-shadow: 0 0 0 3px rgba(63, 107, 79, 0.15);
        }

        .blob {
            background: radial-gradient(circle at 30% 30%, #4d7d5d, var(--moss) 55%, transparent 78%);
        }

        @media (prefers-reduced-motion: no-preference) {
            .fade-up {
                animation: fadeUp .6s ease both;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .video-frame {
            position: relative;
            width: 100%;
            aspect-ratio: 4/3;
            border-radius: 1rem;
            overflow: hidden;
            background: #000;
        }

        .video-frame iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }

        .video-frame::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.25), transparent 40%);
        }

        @media (max-width:640px) {
            .video-frame {
                aspect-ratio: 16/9;
            }
        }

        .faq-question {
            cursor: pointer;
            user-select: none;
        }

        .faq-answer {
            display: none;
        }

        .faq-item.active .faq-answer {
            display: block;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        .faq-icon {
            transition: transform 0.3s ease;
        }

        .order-btn-wrapper {
            position: relative;
            display: inline-block;
            padding: 22px 28px;
            z-index: 5;
        }

        .order-btn-animate {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-width: 290px;
            min-height: 82px;
            padding: 13px 38px;
            text-decoration: none;
            color: #fff;
            background: linear-gradient(180deg, #ff5a3d 0%, #f0442d 45%, #d92f1f 100%);
            border: 3px solid #ffb52e;
            border-radius: 999px;
            box-shadow: 0 8px 0 #b82719, 0 15px 30px rgba(194, 45, 29, 0.35), inset 0 2px 2px rgba(255, 255, 255, 0.35), inset 0 -5px 10px rgba(120, 20, 10, 0.15);
            font-weight: 800;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }

        .order-btn-animate::before {
            content: "";
            position: absolute;
            top: -40%;
            left: -80%;
            width: 45%;
            height: 180%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.38), transparent);
            transform: rotate(20deg);
            animation: buttonShine 2.8s ease-in-out infinite;
            pointer-events: none;
        }

        .order-btn-text {
            position: relative;
            z-index: 2;
            font-size: 20px;
            line-height: 1.2;
            white-space: nowrap;
            text-shadow: 0 2px 2px rgba(100, 20, 10, 0.25);
        }

        .order-btn-price {
            position: relative;
            z-index: 2;
            font-size: 23px;
            font-weight: 900;
            color: #fff4b0;
            white-space: nowrap;
            text-shadow: 0 2px 3px rgba(100, 20, 10, 0.35);
        }

        .order-btn-wrapper::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 118%;
            height: 128%;
            transform: translate(-50%, -50%) rotate(-3deg);
            border: 5px solid #ed211c;
            border-radius: 48% 52% 46% 54% / 54% 45% 55% 46%;
            pointer-events: none;
            z-index: 1;
            filter: drop-shadow(1px 1px 0 #ed211c) drop-shadow(-1px -1px 0 #ed211c);
            animation: redHandCircle 1.8s ease-in-out infinite;
        }

        .order-btn-wrapper::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 123%;
            height: 135%;
            transform: translate(-50%, -50%) rotate(2deg);
            border: 3px solid rgba(237, 33, 28, 0.85);
            border-radius: 52% 48% 55% 45% / 46% 55% 45% 54%;
            pointer-events: none;
            z-index: 1;
            animation: redHandCircle2 1.8s ease-in-out infinite;
        }

        @keyframes redHandCircle {

            0%,
            100% {
                transform: translate(-50%, -50%) rotate(-3deg) scale(1);
            }

            50% {
                transform: translate(-50%, -50%) rotate(-5deg) scale(1.035);
            }
        }

        @keyframes redHandCircle2 {

            0%,
            100% {
                transform: translate(-50%, -50%) rotate(2deg) scale(1);
            }

            50% {
                transform: translate(-50%, -50%) rotate(4deg) scale(1.045);
            }
        }

        @keyframes buttonShine {
            0% {
                left: -80%;
            }

            45% {
                left: 130%;
            }

            100% {
                left: 130%;
            }
        }

        .order-btn-animate:hover {
            transform: translateY(-5px) scale(1.04);
            box-shadow: 0 10px 0 #b82719, 0 20px 40px rgba(194, 45, 29, 0.45), inset 0 2px 3px rgba(255, 255, 255, 0.4);
            filter: brightness(1.08);
        }

        @media (max-width:640px) {
            .order-btn-wrapper {
                padding: 18px 20px;
                width: 100%;
            }

            .order-btn-animate {
                min-width: 0;
                width: 100%;
                min-height: 72px;
                padding: 12px 20px;
                flex-direction: column;
                gap: 2px;
            }

            .order-btn-text {
                font-size: 18px;
            }

            .order-btn-price {
                font-size: 21px;
            }

            .order-btn-wrapper::before {
                width: 112%;
                height: 125%;
            }

            .order-btn-wrapper::after {
                width: 117%;
                height: 132%;
            }
        }

        .cart-qty-btn {
            user-select: none;
            cursor: pointer;
            transition: background 0.15s;
        }

        .cart-qty-btn:hover {
            background: #f0f0f0;
        }

        .cart-qty-input {
            -moz-appearance: textfield;
            width: 45px;
            text-align: center;
            border: 0;
            background: transparent;
            font-weight: bold;
            font-size: 1rem;
        }

        .cart-qty-input::-webkit-outer-spin-button,
        .cart-qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .empty-cart-msg {
            text-align: center;
            color: #999;
            padding: 20px 0;
        }

        .add-to-cart-btn {
            background: var(--clay);
            color: #fff;
            padding: 5px 15px;
            border-radius: 999px;
            font-weight: 600;
            transition: background 0.2s;
            cursor: pointer;
            border: none;
        }

        .add-to-cart-btn:hover {
            background: var(--yellow-dark);
        }

        .remove-item-btn {
            color: #dc2626;
            cursor: pointer;
            font-weight: 700;
            font-size: 1.2rem;
            background: none;
            border: none;
            padding: 0 5px;
        }

        .remove-item-btn:hover {
            color: #991b1b;
        }

        /* Toast */
        #toast {
            position: fixed;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            background: #1e293b;
            color: #fff;
            padding: 12px 28px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.4s ease, transform 0.3s ease;
            z-index: 9999;
            pointer-events: none;
            white-space: nowrap;
        }

        #toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        #toast.success {
            background: #0b6e4f;
        }

        #toast.error {
            background: #b91c1c;
        }

        /* Deadline Countdown */
        #deadline-section {
            background: linear-gradient(135deg, #2a7cff, #4565b0);
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 1.5rem;
            font-weight: 600;
            margin: 1rem auto;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            flex-wrap: wrap;
            justify-content: center;
        }

        #deadline-section .label {
            font-size: 1rem;
            opacity: 0.8;
        }

        #deadline-section .time {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        #deadline-section .time .unit {
            background: rgba(255, 255, 255, 0.15);
            padding: 0.2rem 0.6rem;
            border-radius: 0.5rem;
            min-width: 3rem;
            text-align: center;
        }

        #deadline-section .time .colon {
            opacity: 0.5;
        }

        #deadline-section .expired {
            color: #f87171;
            font-size: 1.2rem;
        }

        @media (max-width:640px) {
            #deadline-section {
                flex-direction: column;
                gap: 0.5rem;
                padding: 1rem;
                border-radius: 1.5rem;
            }

            #deadline-section .time {
                font-size: 1.1rem;
            }
        }
    </style>
</head>

<body class="pb-24 md:pb-0">

    @php
        // Decode JSON
        $features = is_array($landingPage->features)
            ? $landingPage->features
            : json_decode($landingPage->features, true) ?? [];
        $testimonials = is_array($landingPage->testimonials)
            ? $landingPage->testimonials
            : json_decode($landingPage->testimonials, true) ?? [];
        $faq = is_array($landingPage->faq) ? $landingPage->faq : json_decode($landingPage->faq, true) ?? [];

        // Product list
        $products = $landingPage->products->map(function ($product) {
            $regularPrice = $product->pivot->regular_price ?? null;
            $discountPrice = $product->pivot->discount_price ?? null;
            $price = $discountPrice && $discountPrice > 0 ? $discountPrice : $regularPrice;
            return [
                'id' => $product->id,
                'name' => $product->name,
                'thumbnail' => $product->thumbnail,
                'price' => $price,
                'regular_price' => $regularPrice,
                'image' => uploaded_asset($product->thumbnail),
            ];
        });

        $firstProduct = $products->first();
        $price = $firstProduct ? $firstProduct['price'] : 0;
        $productName = $firstProduct ? $firstProduct['name'] : '';

        $videoId = null;
        if ($landingPage->video_link) {
            parse_str(parse_url($landingPage->video_link, PHP_URL_QUERY), $query);
            $videoId = $query['v'] ?? null;
            if (!$videoId) {
                $path = parse_url($landingPage->video_link, PHP_URL_PATH);
                if ($path) {
                    $videoId = ltrim($path, '/');
                }
            }
        }

        // Deadline
        $deadline = $landingPage->deadline ?? null;
        $deadlineString = $deadline ? (string) $deadline : null;
        $deadlinePassed = $deadline ? now()->greaterThan($deadline) : false;
    @endphp

    <!-- Toast container -->
    <div id="toast"></div>

    <!-- ========================= HERO ========================= -->
    <header class="relative overflow-hidden bg-[var(--ink)] text-white">
        <div class="blob -top-24 -left-24"></div>
        <div class="blob top-1/3 -right-32 opacity-30"></div>
        <div class="relative max-w-6xl mx-auto px-5 pt-8 pb-16 md:pt-14 md:pb-24">

            <div class="flex flex-col items-center gap-4 mb-10 md:mb-16">
                @if ($headerLogoUrl)
                    <img src="{{ $headerLogoUrl }}" alt="Logo" class="h-16 md:h-20 w-auto" />
                @endif
                <div
                    class="inline-block bg-[#2d6a4f] px-6 py-3 rounded-xl text-[#f9c74f] text-3xl md:text-5xl font-semibold">
                    {{ $landingPage->title }}
                </div>
                <div class="inline-block bg-purple-600 px-4 py-4 rounded-lg text-1xl md:text-2xl font-semibold">
                    {{ $landingPage->sub_title }}
                </div>
                <style>
                    .order-btn-wrapper {
                        display: flex;
                        justify-content: center;
                        margin-top: 1rem;
                    }

                    .order-btn-animate {
                        display: inline-flex;
                        align-items: center;
                        gap: 0.5rem;
                        background: linear-gradient(135deg, #f97316, #dc2626);
                        padding: 0.9rem 2rem;
                        border-radius: 50px;
                        text-decoration: none;
                        color: #fff;
                        font-weight: 700;
                        font-size: 1.2rem;
                        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4);
                        transition: transform 0.2s ease, box-shadow 0.2s ease;
                        cursor: pointer;
                    }

                    .order-btn-animate:hover {
                        transform: scale(1.04);
                        box-shadow: 0 12px 28px rgba(220, 38, 38, 0.5);
                    }

                    .hand-icon {
                        display: inline-block;
                        font-size: 2rem;
                        line-height: 1;
                        animation: bounce-hand 1.4s ease-in-out infinite;
                        transform-origin: center;
                        position: relative;
                        top: -0.1rem;
                    }

                    @keyframes bounce-hand {

                        0%,
                        100% {
                            transform: translateY(0) rotate(0deg);
                        }

                        30% {
                            transform: translateY(-10px) rotate(-8deg);
                        }

                        50% {
                            transform: translateY(0) rotate(0deg);
                        }

                        70% {
                            transform: translateY(-6px) rotate(5deg);
                        }

                        85% {
                            transform: translateY(0) rotate(0deg);
                        }
                    }

                    @media (max-width: 480px) {
                        .order-btn-animate {
                            padding: 0.7rem 1.2rem;
                            font-size: 1rem;
                            gap: 0.3rem;
                            flex-wrap: wrap;
                            justify-content: center;
                        }

                        .hand-icon {
                            font-size: 1.6rem;
                        }
                    }
                </style>

                <!-- ===== DEADLINE ===== -->
                @if ($deadlineString)
                    <div id="deadline-section" class="fade-up">
                        <span class="label">⏳ অফার শেষ হতে বাকি</span>
                        <div class="time" id="countdown-timer">
                            @if ($deadlinePassed)
                                <span class="expired">⛔ অফার শেষ</span>
                            @else
                                <span class="unit" id="days">00</span><span class="colon">:</span>
                                <span class="unit" id="hours">00</span><span class="colon">:</span>
                                <span class="unit" id="minutes">00</span><span class="colon">:</span>
                                <span class="unit" id="seconds">00</span>
                            @endif
                        </div>
                    </div>

                @endif
            </div>

            <div class="grid lg:grid-cols-1 gap-12 items-center">
                <div class="fade-up">
                    @if ($videoId)
                        <div class="video-frame">
                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" title="YouTube video player"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                    @elseif($landingPage->banner_image)
                        <img src="{{ uploaded_asset($landingPage->banner_image) }}" alt="{{ $landingPage->title }}"
                            class="rounded-2xl w-full" />
                    @else
                        <div
                            class="rounded-2xl bg-white/10 aspect-video flex items-center justify-center text-white/30">
                            নো মিডিয়া
                        </div>
                    @endif
                </div>
            </div>

            <div class="fade-up">
                <div class="order-btn-wrapper mt-2">
                    <a href="#order" class="order-btn-animate">
                        <span class="hand-icon"> 👇</span>
                        <span class="order-btn-text">অর্ডার করতে চাই —</span>
                        <span class="order-btn-price">{{ number_format($price) }} ৳</span>
                    </a>
                </div>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-8 text-xs text-white/50">
                    <span class="flex items-center gap-1.5"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>১০০% প্রাকৃতিক</span>
                    <span class="flex items-center gap-1.5"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5" />
                        </svg>সরকারি অনুমোদিত</span>
                    <span class="flex items-center gap-1.5"><svg width="14" height="14" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M10 1l2.6 5.9L19 8l-4.8 4.2L15.5 19 10 15.6 4.5 19l1.3-6.8L1 8l6.4-1.1z" />
                        </svg>{{ $landingPage->sold_count ? number_format($landingPage->sold_count) : '৪.৮' }}
                        @if ($landingPage->sold_count)
                            + রিভিউ
                        @else
                            (২,৩০০+ রিভিউ)
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- ========================= TRUST STRIP ========================= -->
    <section class="bg-white border-b border-black/5">
        <div
            class="max-w-6xl mx-auto px-5 py-4 flex flex-wrap items-center justify-center gap-x-10 gap-y-2 text-xs text-[var(--ink)]/50 font-medium">
            <span>রিফান্ড গ্যারান্টি</span>
            <span class="hidden sm:inline text-[var(--ink)]/20">•</span>
            <span>ফ্রি হোম ডেলিভারি</span>
            <span class="hidden sm:inline text-[var(--ink)]/20">•</span>
            <span>ল্যাব টেস্টেড</span>
            <span class="hidden sm:inline text-[var(--ink)]/20">•</span>
            <span>{{ number_format($landingPage->sold_count) }} কাস্টমার</span>
        </div>
    </section>

    <!-- ========================= FEATURES ========================= -->
    <section class="py-16 md:py-24 bg-white border-b border-black/5" id="features">
        <div class="max-w-6xl mx-auto px-5">
            <div class="mb-12 text-center">
                <span
                    class="num text-sm md:text-base tracking-[0.2em] uppercase text-[var(--clay)] font-semibold">সূচক</span>
                <h2 class="display text-3xl md:text-5xl font-bold mt-3">প্রোডাক্টের বৈশিষ্ট্য</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-7">
                @foreach ($features as $feature)
                    <div class="border border-black/5 rounded-2xl p-7 md:p-8 hover:shadow-lg transition-shadow">
                        <p class="font-bold text-xl md:text-2xl mb-3">{{ $feature['title'] }}</p>
                        <p class="text-base md:text-lg text-[var(--ink)]/60 leading-relaxed">
                            {{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="order-btn-wrapper mt-2">
                <a href="#order" class="order-btn-animate">
                    <span class="hand-icon"> 👇</span>
                    <span class="order-btn-text">অর্ডার করতে চাই —</span>
                    <span class="order-btn-price">{{ number_format($price) }} ৳</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ========================= REVIEWS ========================= -->
    @if (count($testimonials) > 0)
        <section class="py-16 md:py-24" id="reviews">
            <div class="max-w-6xl mx-auto px-5">
                <div class="mb-12 text-center">
                    <span
                        class="num text-sm md:text-base tracking-[0.2em] uppercase text-[var(--clay)] font-semibold">রিভিউ</span>
                    <h2 class="display text-3xl md:text-5xl font-bold mt-3">কাস্টমাররা যা বলছেন</h2>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-7">
                    @foreach ($testimonials as $testimonial)
                        <div class="card rounded-2xl p-7 md:p-8">
                            <div class="flex items-center gap-1.5 text-[var(--gold)] mb-5">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= (int) ($testimonial['rating'] ?? 0))
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                            <path
                                                d="M10 1l2.6 5.9L19 8l-4.8 4.2L15.5 19 10 15.6 4.5 19l1.3-6.8L1 8l6.4-1.1z" />
                                        </svg>
                                    @else
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            stroke="currentColor" stroke-width="1.2">
                                            <path
                                                d="M10 1l2.6 5.9L19 8l-4.8 4.2L15.5 19 10 15.6 4.5 19l1.3-6.8L1 8l6.4-1.1z" />
                                        </svg>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-base md:text-lg text-[var(--ink)]/75 leading-[1.8] mb-7">
                                {{ $testimonial['comment'] ?? '' }}</p>
                            <div class="flex items-center gap-4">
                                <div>
                                    <p class="text-base md:text-lg font-bold">{{ $testimonial['name'] ?? '' }}</p>
                                    <p class="text-sm md:text-base text-[var(--ink)]/45 mt-0.5">
                                        {{ $testimonial['position'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="order-btn-wrapper mt-2">
                    <a href="#order" class="order-btn-animate">
                        <span class="hand-icon"> 👇</span>
                        <span class="order-btn-text">অর্ডার করতে চাই —</span>
                        <span class="order-btn-price">{{ number_format($price) }} ৳</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- ========================= FAQ ========================= -->
    @if (count($faq) > 0)
        <section class="py-16 md:py-24 bg-white border-y border-black/5" id="faq">
            <div class="max-w-5xl mx-auto px-5">
                <div class="mb-12 text-center">
                    <span
                        class="num text-sm md:text-base tracking-[0.2em] uppercase text-[var(--clay)] font-semibold">প্রশ্ন
                        ও উত্তর</span>
                    <h2 class="display text-3xl md:text-5xl font-bold mt-3">সচরাচর জিজ্ঞাসিত প্রশ্ন</h2>
                </div>
                <div class="space-y-4">
                    @foreach ($faq as $item)
                        <div class="faq-item border border-black/5 rounded-2xl overflow-hidden bg-white shadow-sm">
                            <div class="faq-question flex items-center justify-between gap-5 p-5 md:p-6 hover:bg-[var(--canvas)]/50 transition cursor-pointer"
                                onclick="toggleFaq(this)">
                                <span
                                    class="font-bold text-lg md:text-xl leading-relaxed text-[var(--ink)]">{{ $item['question'] ?? '' }}</span>
                                <span
                                    class="faq-icon flex-shrink-0 text-[var(--ink)]/50 w-9 h-9 md:w-10 md:h-10 rounded-full bg-[var(--canvas)] flex items-center justify-center">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6" />
                                    </svg>
                                </span>
                            </div>
                            <div
                                class="faq-answer px-5 md:px-6 pb-6 text-base md:text-lg text-[var(--ink)]/70 leading-[1.8]">
                                {{ $item['answer'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="order-btn-wrapper mt-2">
                    <a href="#order" class="order-btn-animate">
                        <span class="hand-icon"> 👇</span>
                        <span class="order-btn-text">অর্ডার করতে চাই —</span>
                        <span class="order-btn-price">{{ number_format($price) }} ৳</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- ========================= PRICING ========================= -->
    <section class="max-w-2xl mx-auto px-5 py-10 text-center">
        <div class="border-2 border-[var(--ink)]/10 rounded-3xl p-8 md:p-10 relative">
            @if ($firstProduct && $firstProduct['regular_price'] && $firstProduct['regular_price'] > $firstProduct['price'])
                <span class="offer-badge-wrapper">
                    <span class="offer-badge-inner">সীমিত সময়ের অফার</span>
                </span>
            @endif
            <div class="order-btn-wrapper">
                @if ($firstProduct && $firstProduct['regular_price'])
                    <p class="mt-5 text-sm text-[var(--ink)]/40 line-through">নিয়মিত মূল্য
                        {{ number_format($firstProduct['regular_price']) }} ৳</p>
                @endif
                <p class="display text-4xl md:text-5xl font-semibold mt-1">{{ number_format($price) }} ৳</p>
                <p class="text-xs text-[var(--ink)]/50 mt-2">সারাদেশে হোম ডেলিভারি ফ্রি</p>
            </div>
        </div>
    </section>

    <!-- ========================= NEW SECTION: আপনার পছন্দের প্রোডাক্ট ========================= -->
    <section class="py-12 md:py-16 bg-white border-y border-black/5" id="product-list">
        <div class="max-w-5xl mx-auto px-5">
            <div class="mb-8 text-center">
                <span
                    class="num text-sm md:text-base tracking-[0.2em] uppercase text-[var(--clay)] font-semibold">প্রোডাক্ট</span>
                <h2 class="display text-3xl md:text-5xl font-bold mt-3">আপনার পছন্দের প্রোডাক্ট</h2>
                <p class="text-base md:text-lg text-[var(--ink)]/50 mt-3">আপনার পছন্দের প্রোডাক্ট যোগ করে অর্ডার করুন
                </p>
            </div>

            <div class="flex flex-col gap-4">
                @foreach ($products as $product)
                    <div
                        class="border border-black/10 rounded-2xl p-4 bg-[var(--canvas)]/30 hover:shadow-lg transition-shadow flex flex-col sm:flex-row items-center gap-4">
                        <!-- Image -->
                        <div class="w-24 h-24 flex-shrink-0 rounded-xl bg-white overflow-hidden border border-black/5">
                            @if ($product['thumbnail'])
                                <img src="{{ uploaded_asset($product['thumbnail']) }}" alt="{{ $product['name'] }}"
                                    class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-xs text-gray-400">
                                    <img src="{{ uploaded_asset($product['thumbnail']) }}"
                                        alt="{{ $landingPage->title }}" class="rounded-2xl w-full" />
                                </div>
                            @endif
                        </div>

                        <!-- Details -->
                        <div
                            class="flex-1 flex flex-col sm:flex-row sm:items-center justify-between w-full text-center sm:text-left">
                            <div>
                                <h3 class="font-bold text-lg mb-1">{{ $product['name'] }}</h3>
                                <p class="text-sm text-[var(--ink)]/60">{{ number_format($product['price']) }} ৳</p>
                            </div>
                            <button type="button"
                                class="add-to-cart-btn mt-2 sm:mt-0 px-6 py-2 bg-[var(--clay)] text-white rounded-full hover:opacity-80 transition"
                                data-id="{{ $product['id'] }}" data-name="{{ $product['name'] }}"
                                data-price="{{ $product['price'] }}"
                                data-image="{{ uploaded_asset($product['thumbnail']) }}">
                                ➕ অর্ডারে যোগ করুন
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ========================= CONTACT / SUPPORT ========================= -->
    <section class="py-8 md:py-12 bg-gradient-to-r from-red-600 to-red-700 text-white">
        <div class="max-w-4xl mx-auto px-5 text-center">
            <span class="num text-[11px] tracking-[0.2em] uppercase text-yellow-300 font-semibold">সহায়তা</span>
            <h1 class="display text-xl md:text-2xl font-semibold mt-2 mb-1" style="font-size: 50px">অর্ডার করতে নিচের
                ফর্মটি পূরণ করুন</h1>
            <p class="text-sm text-yellow-100/90 mb-4">যেকোনো সময় রিটার্ন বা পরিবর্তন করতে পারবেন ১০-১৫ দিনের মধ্যে!
                প্রয়োজনে ফোন করুন</p>
            <p class="text-xs text-yellow-200/70 mt-4">সকাল ৯টা – রাত ১০টা, সপ্তাহের ৭ দিন</p>
        </div>
    </section>

    <!-- ========================= ORDER FORM ========================= -->
    <section id="order" class="max-w-5xl mx-auto px-5 py-16 md:py-20">
        <div class="mb-12 text-center">
            <span class="num text-sm md:text-base tracking-[0.2em] uppercase text-[var(--clay)] font-semibold">অর্ডার
                করুন</span>
            <h2 class="display text-3xl md:text-5xl font-bold mt-3">ক্যাশ অন ডেলিভারিতে অর্ডার সম্পন্ন করুন</h2>
            <p class="text-base md:text-lg text-[var(--ink)]/50 mt-3">ফর্ম পূরণ করুন — আমাদের টিম ২৪ ঘন্টার মধ্যে
                যোগাযোগ করবে।</p>
        </div>

        <form
            class="bg-white rounded-3xl border border-black/5 shadow-sm p-6 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8"
            method="POST" action="{{ route('landing.product.order') }}">
            @csrf

            <input type="hidden" name="landing_page_id" value="{{ $landingPage->id }}">

            <!-- বাম দিক: বিলিং ডিটেইলস -->
            <div>
                <h3 class="text-base md:text-lg font-bold text-[var(--ink)] mb-5">বিলিং ডিটেইলস</h3>
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="আপনার নাম" required
                        class="w-full border border-black/10 rounded-xl px-5 py-4 text-base md:text-lg bg-[var(--canvas)]/50" />
                    <input type="tel" name="phone" placeholder="মোবাইল নাম্বার" required
                        class="w-full border border-black/10 rounded-xl px-5 py-4 text-base md:text-lg bg-[var(--canvas)]/50" />
                    <textarea name="address" placeholder="সম্পূর্ণ ঠিকানা লিখুন" rows="4" required
                        class="w-full border border-black/10 rounded-xl px-5 py-4 text-base md:text-lg bg-[var(--canvas)]/50"></textarea>

                    <div>
                        <label class="block text-base md:text-lg font-semibold text-[var(--ink)] mb-3">ডেলিভারি
                            এলাকা</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="delivery_area" value="inside_dhaka" data-charge="60"
                                    class="peer hidden" checked>
                                <div
                                    class="border border-black/10 rounded-xl px-5 py-4 peer-checked:border-[var(--clay)] peer-checked:bg-yellow-50 transition">
                                    <p class="font-bold text-base md:text-lg">ঢাকার ভিতরে</p>
                                    <p class="text-sm md:text-base text-[var(--ink)]/50 mt-1">ডেলিভারি চার্জ: ৬০ ৳</p>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="delivery_area" value="outside_dhaka" data-charge="120"
                                    class="peer hidden">
                                <div
                                    class="border border-black/10 rounded-xl px-5 py-4 peer-checked:border-[var(--clay)] peer-checked:bg-yellow-50 transition">
                                    <p class="font-bold text-base md:text-lg">ঢাকার বাইরে</p>
                                    <p class="text-sm md:text-base text-[var(--ink)]/50 mt-1">ডেলিভারি চার্জ: ১২০ ৳</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ডান দিক: অর্ডার সামারি (কার্ট) -->
            <div>
                <h3 class="text-base md:text-lg font-bold text-[var(--ink)] mb-3">অর্ডার সামারি</h3>
                <div id="cartContainer"
                    class="border border-black/10 rounded-2xl p-5 md:p-6 bg-[var(--canvas)]/50 min-h-[120px]">
                    <div id="cartItems" class="space-y-4">
                        <!-- JS দিয়ে রেন্ডার হবে -->
                    </div>

                    <div id="cartTotals" class="mt-5 border-t border-black/10 pt-4 space-y-3" style="display:none;">
                        <div class="flex justify-between text-base">
                            <span class="text-[var(--ink)]/60">পণ্যের মূল্য</span>
                            <span class="num font-semibold" id="cartSubtotal">০ ৳</span>
                        </div>
                        <div class="flex justify-between text-base">
                            <span class="text-[var(--ink)]/60">ডেলিভারি চার্জ</span>
                            <span class="num font-bold text-[var(--moss)]" id="cartDeliveryCharge">৬০ ৳</span>
                        </div>
                        <div class="flex justify-between text-lg md:text-2xl font-bold border-t border-black/10 pt-3">
                            <span>সর্বমোট</span>
                            <span class="num text-[var(--clay)]" id="cartTotal">০ ৳</span>
                        </div>
                    </div>
                </div>

                <!-- Hidden fields for cart data -->
                <div id="hiddenFieldsContainer"></div>

                <button type="submit" class="btn-clay capsule w-full mt-5 py-4 text-base md:text-lg font-bold">ক্যাশ
                    অন ডেলিভারিতে অর্ডার করুন</button>
                <p class="text-center text-sm md:text-base text-[var(--ink)]/40 mt-4">নিরাপদ লেনদেন · ২৪ ঘন্টার মধ্যে
                    কল</p>
            </div>
        </form>
    </section>

    <!-- ========================= FOOTER ========================= -->
    <footer class="footer">
        <div class="container">
            <div class="top-section">
                <div class="brand-info">
                    <div class="logo">
                        @if ($headerLogoUrl)
                            <img src="{{ $headerLogoUrl }}" alt="Logo" class="h-16 md:h-20 w-auto" />
                        @endif
                    </div>
                    <p>Nittoz is a leading e-commerce platform that provides a wide range of products and services to
                        customers around the world.</p>
                    <div class="contact-list">
                        <div><i class="fas fa-envelope"></i> info@nittoz.com</div>
                        <div><i class="fas fa-phone-alt"></i> +880123456789</div>
                        <div><i class="fas fa-map-marker-alt"></i> Dhaka, Bangladesh</div>
                    </div>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="links-grid">
                    <div class="col">
                        <h4>Shop</h4>
                        <ul>
                            <li><a href="#">Vendor</a></li>
                            <li><a href="#">Affiliate</a></li>
                            <li><a href="#">Referral</a></li>
                            <li><a href="#">Reseller</a></li>
                            <li><a href="#">Dropshipping</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h4>Explore</h4>
                        <ul>
                            <li><a href="#">Home</a></li>
                            <li><a href="#">Today's Deal</a></li>
                            <li><a href="#">Flash Sale</a></li>
                            <li><a href="#">Best Sellers</a></li>
                            <li><a href="#">New Arrivals</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h4>Help</h4>
                        <ul>
                            <li><a href="#">Contact Us</a></li>
                            <li><a href="#">Track Order</a></li>
                            <li><a href="#">Returns</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Shipping Info</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h4>Company</h4>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Blogs</a></li>
                            <li><a href="#">Press</a></li>
                            <li><a href="#">Affiliates</a></li>
                        </ul>
                    </div>
                    <div class="col">
                        <h4>Legal</h4>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                            <li><a href="#">Disclaimer</a></li>
                            <li><a href="#">Return Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                © 2026 Nittoz™. All Rights Reserved.
            </div>
        </div>
    </footer>

    <style>
        .footer {
            background-color: #050d16;
            color: #fff;
            padding: 50px 20px 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .top-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 30px;
            margin-bottom: 40px;
        }

        .brand-info {
            flex: 1;
            min-width: 300px;
        }

        .logo {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            border-bottom: 4px solid #4CAF50;
            display: inline-block;
            margin-bottom: 20px;
            padding-bottom: 5px;
        }

        .brand-info p {
            color: #a0a0a0;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .contact-list div {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            color: #ddd;
        }

        .contact-list i {
            color: #f56c6c;
        }

        .social-icons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }

        .social-icons a {
            width: 35px;
            height: 35px;
            background: #1a1a1a;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            text-decoration: none;
            transition: 0.3s;
        }

        .social-icons a:hover {
            background: #555;
        }

        .links-grid {
            flex: 3;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        .col {
            min-width: 140px;
        }

        .col h4 {
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-transform: uppercase;
            border-bottom: 2px solid #f56c6c;
            display: inline-block;
            padding-bottom: 5px;
        }

        .col ul {
            list-style: none;
        }

        .col ul li {
            margin-bottom: 10px;
        }

        .col ul li a {
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .col ul li a:hover {
            color: #fff;
        }

        .copyright {
            text-align: center;
            margin-top: 30px;
            color: #888;
            font-size: 13px;
            border-top: 1px solid #222;
            padding-top: 20px;
        }

        @media (max-width: 992px) {
            .top-section {
                flex-direction: column;
            }

            .links-grid {
                gap: 20px;
            }
        }

        @media (max-width: 600px) {
            .links-grid {
                flex-direction: column;
            }
        }
    </style>

    <!-- ========================= MOBILE CTA ========================= -->
    <div
        class="fixed bottom-0 left-0 right-0 md:hidden bg-white border-t border-black/10 px-4 py-3 flex items-center gap-3 z-50">
        <div>
            @if ($firstProduct && $firstProduct['regular_price'])
                <p class="text-[10px] text-[var(--ink)]/40 line-through">
                    {{ number_format($firstProduct['regular_price']) }} ৳</p>
            @endif
            <p class="num font-bold text-sm">{{ number_format($price) }} ৳</p>
        </div>
        <a href="#order" class="btn-clay capsule flex-1 text-center py-3 font-semibold text-sm">অর্ডার করুন</a>
    </div>

    <!-- ========================= JAVASCRIPT (শুধু ফ্রন্টএন্ড) ========================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ---------- Deadline Countdown ----------
            const deadlineStr = @json($deadlineString);
            const deadlinePassed = @json($deadlinePassed);
            if (deadlineStr && !deadlinePassed) {
                const target = new Date(deadlineStr).getTime();
                const timerEl = document.getElementById('countdown-timer');
                if (timerEl) {
                    function updateCountdown() {
                        const now = new Date().getTime();
                        const diff = target - now;
                        if (diff <= 0) {
                            timerEl.innerHTML = '<span class="expired">⛔ অফার শেষ</span>';
                            return;
                        }
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                        document.getElementById('days').textContent = String(days).padStart(2, '0');
                        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
                        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
                        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
                    }
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                }
            }

            // ---------- Toast ----------
            function showToast(message, type = 'success') {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.className = '';
                toast.classList.add(type, 'show');
                clearTimeout(toast._timer);
                toast._timer = setTimeout(() => {
                    toast.classList.remove('show');
                }, 2500);
            }

            // ---------- Cart (localStorage) ----------
            function getCart() {
                try {
                    return JSON.parse(localStorage.getItem('cart')) || {};
                } catch {
                    return {};
                }
            }

            function saveCart(cart) {
                localStorage.setItem('cart', JSON.stringify(cart));
            }

            function renderCart() {
                const cart = getCart();
                const cartItemsEl = document.getElementById('cartItems');
                const cartTotals = document.getElementById('cartTotals');
                const cartSubtotal = document.getElementById('cartSubtotal');
                const cartDeliveryCharge = document.getElementById('cartDeliveryCharge');
                const cartTotal = document.getElementById('cartTotal');
                const hiddenContainer = document.getElementById('hiddenFieldsContainer');

                const productIds = Object.keys(cart);

                if (productIds.length === 0) {
                    cartItemsEl.innerHTML = `<div class="empty-cart-msg">📦 এখনো কোনো প্রোডাক্ট যোগ করা হয়নি</div>`;
                    cartTotals.style.display = 'none';
                    if (hiddenContainer) hiddenContainer.innerHTML = '';
                    return;
                }

                let html = '';
                let subtotal = 0;

                productIds.forEach(id => {
                    const item = cart[id];
                    const price = item.price;
                    const qty = item.quantity;
                    const totalPrice = price * qty;
                    subtotal += totalPrice;

                    html += `
                        <div class="cart-item flex items-center gap-4 border-b border-black/5 pb-4 last:border-0" data-id="${id}">
                            <div class="w-14 h-14 rounded-lg bg-white overflow-hidden flex-shrink-0 border border-black/5">
                                <img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-base">${item.name}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <button type="button" class="cart-qty-btn qty-minus w-7 h-7 rounded-full border border-black/10 flex items-center justify-center text-lg font-bold hover:bg-black/5" data-id="${id}">−</button>
                                    <input type="number" class="cart-qty-input" value="${qty}" min="1" max="10" readonly />
                                    <button type="button" class="cart-qty-btn qty-plus w-7 h-7 rounded-full border border-black/10 flex items-center justify-center text-lg font-bold hover:bg-black/5" data-id="${id}">+</button>
                                </div>
                            </div>
                            <div>
                                <span class="num font-bold text-base">${formatPrice(totalPrice)} ৳</span>
                                <button type="button" class="remove-item-btn" data-id="${id}">✖</button>
                            </div>
                        </div>
                    `;
                });

                cartItemsEl.innerHTML = html;
                cartTotals.style.display = 'block';

                const deliveryCharge = getDeliveryCharge();
                const total = subtotal + deliveryCharge;

                cartSubtotal.textContent = formatPrice(subtotal) + ' ৳';
                cartDeliveryCharge.textContent = formatPrice(deliveryCharge) + ' ৳';
                cartTotal.textContent = formatPrice(total) + ' ৳';

                updateHiddenFields(cart, subtotal, deliveryCharge);
                attachCartEvents();
            }

            function formatPrice(num) {
                return num.toLocaleString('en-US');
            }

            function getDeliveryCharge() {
                const selected = document.querySelector('input[name="delivery_area"]:checked');
                if (!selected) return 60;
                return parseFloat(selected.getAttribute('data-charge')) || 60;
            }

            function updateHiddenFields(cart, subtotal, deliveryCharge) {
                const container = document.getElementById('hiddenFieldsContainer');
                if (!container) return;
                container.innerHTML = '';

                const productIds = Object.keys(cart);
                productIds.forEach(id => {
                    const item = cart[id];
                    const inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'product_ids[]';
                    inputId.value = id;
                    container.appendChild(inputId);

                    const inputQty = document.createElement('input');
                    inputQty.type = 'hidden';
                    inputQty.name = 'quantities[]';
                    inputQty.value = item.quantity;
                    container.appendChild(inputQty);

                    const inputPrice = document.createElement('input');
                    inputPrice.type = 'hidden';
                    inputPrice.name = 'prices[]';
                    inputPrice.value = item.price;
                    container.appendChild(inputPrice);
                });

                let totalInput = document.querySelector('input[name="total_price"]');
                if (!totalInput) {
                    totalInput = document.createElement('input');
                    totalInput.type = 'hidden';
                    totalInput.name = 'total_price';
                    container.appendChild(totalInput);
                }
                totalInput.value = subtotal + deliveryCharge;

                let dcInput = document.querySelector('input[name="delivery_charge"]');
                if (!dcInput) {
                    dcInput = document.createElement('input');
                    dcInput.type = 'hidden';
                    dcInput.name = 'delivery_charge';
                    container.appendChild(dcInput);
                }
                dcInput.value = deliveryCharge;
            }

            function addToCart(productId, name, price, image) {
                let cart = getCart();
                if (cart[productId]) {
                    cart[productId].quantity += 1;
                    showToast(`"${name}" এর পরিমাণ বাড়ানো হয়েছে`, 'success');
                } else {
                    cart[productId] = {
                        id: productId,
                        name: name,
                        price: price,
                        image: image,
                        quantity: 1
                    };
                    showToast(`"${name}" কার্টে যোগ করা হয়েছে`, 'success');
                }
                saveCart(cart);
                renderCart();
            }

            function removeFromCart(productId) {
                let cart = getCart();
                if (cart[productId]) {
                    const name = cart[productId].name;
                    delete cart[productId];
                    saveCart(cart);
                    showToast(`"${name}" সরানো হয়েছে`, 'error');
                    renderCart();
                }
            }

            function changeQuantity(productId, delta) {
                let cart = getCart();
                if (!cart[productId]) return;
                const newQty = cart[productId].quantity + delta;
                if (newQty < 1) {
                    removeFromCart(productId);
                } else if (newQty <= 10) {
                    cart[productId].quantity = newQty;
                    saveCart(cart);
                    renderCart();
                    showToast(`পরিমাণ পরিবর্তন করা হয়েছে`, 'success');
                }
            }

            function attachCartEvents() {
                document.querySelectorAll('.cart-item .qty-minus').forEach(btn => {
                    btn.removeEventListener('click', handleMinus);
                    btn.addEventListener('click', handleMinus);
                });
                document.querySelectorAll('.cart-item .qty-plus').forEach(btn => {
                    btn.removeEventListener('click', handlePlus);
                    btn.addEventListener('click', handlePlus);
                });
                document.querySelectorAll('.cart-item .remove-item-btn').forEach(btn => {
                    btn.removeEventListener('click', handleRemove);
                    btn.addEventListener('click', handleRemove);
                });
            }

            function handleMinus(e) {
                const id = e.currentTarget.dataset.id;
                changeQuantity(id, -1);
            }

            function handlePlus(e) {
                const id = e.currentTarget.dataset.id;
                changeQuantity(id, 1);
            }

            function handleRemove(e) {
                const id = e.currentTarget.dataset.id;
                removeFromCart(id);
            }

            // ---------- "Add to Cart" buttons ----------
            document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;
                    const price = parseFloat(this.dataset.price);
                    const image = this.dataset.image || 'https://via.placeholder.com/80';
                    addToCart(id, name, price, image);
                });
            });

            // ---------- Delivery charge change ----------
            document.querySelectorAll('input[name="delivery_area"]').forEach(opt => {
                opt.addEventListener('change', function() {
                    const cart = getCart();
                    if (Object.keys(cart).length > 0) renderCart();
                });
            });

            // ---------- Smooth scroll ----------
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (!href || href === '#') return;
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // ---------- FAQ toggle ----------
            window.toggleFaq = function(el) {
                const parent = el.closest('.faq-item');
                const answer = parent.querySelector('.faq-answer');
                const isActive = parent.classList.contains('active');
                if (isActive) {
                    parent.classList.remove('active');
                    answer.style.display = 'none';
                } else {
                    parent.classList.add('active');
                    answer.style.display = 'block';
                }
            };

            // Open first FAQ by default
            const firstFaq = document.querySelector('.faq-item');
            if (firstFaq) {
                firstFaq.classList.add('active');
                firstFaq.querySelector('.faq-answer').style.display = 'block';
            }

            // ---------- Initial render ----------
            renderCart();
        });
    </script>

</body>

</html>
