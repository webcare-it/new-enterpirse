<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Turbo Mini Fan | Nittoz</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --green: #0a8b45;
            --green-dark: #08733a;
            --green-light: #effcf4;
            --ink: #071426;
            --muted: #6d7886;
            --line: #e8edf0;
            --soft: #f7f9fa;
            --orange: #ff9f00;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            color: var(--ink);
            background: #fff;
            font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container-xl {
            max-width: 1180px;
        }

        .announcement {
            min-height: 28px;
            background: var(--green);
            color: #fff;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            letter-spacing: .1px;
        }

        .brand-bar {
            height: 62px;
            border-bottom: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand {
            font-size: 29px;
            line-height: 1;
            font-weight: 900;
            color: #1d3c28;
            letter-spacing: -2px;
        }

        .brand span {
            color: var(--green);
        }

        .hero {
            background: linear-gradient(180deg, #f0fff6 0%, #fff 100%);
            padding: 58px 0 54px;
        }

        .hero-copy h1 {
            font-size: clamp(42px, 5vw, 72px);
            line-height: .96;
            letter-spacing: -4px;
            font-weight: 900;
            margin: 0 0 24px;
        }

        .hero-copy h1 .green {
            color: var(--green);
        }

        .hero-copy p {
            color: var(--muted);
            max-width: 460px;
        }

        .stats {
            display: flex;
            gap: 28px;
            margin: 24px 0 30px;
        }

        .stat strong {
            display: block;
            font-size: 20px;
            font-weight: 900;
        }

        .stat small {
            color: #8a949d;
            font-size: 9px;
        }

        .btn-green {
            background: var(--green);
            border: 1px solid var(--green);
            color: #fff;
            border-radius: 999px;
            padding: 11px 21px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-green:hover {
            background: var(--green-dark);
            color: #fff;
        }

        .btn-soft {
            border: 1px solid #dfe5e8;
            background: #fff;
            color: #25303a;
            border-radius: 999px;
            padding: 11px 21px;
            font-size: 12px;
            font-weight: 600;
        }

        .hero-video {
            min-height: 330px;
            border-radius: 26px;
            overflow: hidden;
            position: relative;
            background:
                radial-gradient(circle at 75% 45%, #303934 0 12%, transparent 13%),
                radial-gradient(circle at 77% 44%, #111 0 19%, transparent 20%),
                linear-gradient(135deg, #101610, #63b74e 48%, #101510);
            box-shadow: 0 24px 50px rgba(20, 42, 30, .18);
        }

        .hero-video::before {
            content: "BEAST\A MODE";
            white-space: pre;
            position: absolute;
            left: 24px;
            top: 42px;
            color: #fff;
            font-weight: 900;
            font-style: italic;
            font-size: clamp(34px, 4vw, 62px);
            line-height: .8;
            text-shadow: 2px 3px 0 #111;
        }

        .hero-video::after {
            content: "LIVE TURBO FAN  •  BUDGET COOLER";
            position: absolute;
            left: 26px;
            bottom: 26px;
            color: #d5ffb9;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .play {
            position: absolute;
            z-index: 2;
            inset: 50% auto auto 50%;
            transform: translate(-50%, -50%);
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .95);
            display: grid;
            place-items: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .2);
        }

        .play::after {
            content: "";
            margin-left: 5px;
            border-top: 12px solid transparent;
            border-bottom: 12px solid transparent;
            border-left: 18px solid var(--green);
        }

        .feature-strip {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            background: #fbfcfc;
        }

        .feature {
            padding: 17px 10px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .feature-icon {
            width: 30px;
            height: 30px;
            border: 1px solid #d5eee0;
            border-radius: 9px;
            display: grid;
            place-items: center;
            color: var(--green);
            font-size: 13px;
        }

        .feature strong {
            font-size: 10px;
            display: block;
        }

        .feature small {
            color: #98a0a8;
            font-size: 8px;
        }

        .section {
            padding: 74px 0;
        }

        .section-soft {
            background: #f8fafb;
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 24px;
            background: radial-gradient(circle at center, #b9bdbb 0 7%, #161919 8% 10%, #313533 11% 14%, #080909 15% 35%, #1e2321 36% 54%, #090b0b 55% 100%);
            position: relative;
            overflow: hidden;
        }

        .product-image::before {
            content: "";
            position: absolute;
            inset: 10%;
            border-radius: 50%;
            background: repeating-conic-gradient(from 0deg, rgba(255, 255, 255, .12) 0deg 2deg, transparent 2deg 9deg);
            opacity: .85;
        }

        .product-image::after {
            content: "";
            position: absolute;
            width: 20%;
            height: 38%;
            left: 40%;
            bottom: -7%;
            background: linear-gradient(90deg, #eee, #aaa, #fff, #888);
            border-radius: 50% 50% 15% 15%;
            box-shadow: 0 10px 20px rgba(0, 0, 0, .45);
        }

        .eyebrow {
            color: var(--green);
            font-size: 9px;
            letter-spacing: 2px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .section-title {
            font-size: clamp(30px, 4vw, 48px);
            font-weight: 900;
            line-height: 1.02;
            letter-spacing: -2px;
        }

        .section-copy {
            color: #7c8791;
            font-size: 12px;
            line-height: 1.8;
        }

        .feature-card {
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 13px;
            background: #fff;
            height: 100%;
        }

        .feature-card strong {
            display: block;
            font-size: 10px;
        }

        .feature-card small {
            color: #9ba3aa;
            font-size: 8px;
        }

        .gallery {
            display: grid;
            grid-template-columns: 52px 1fr;
            gap: 12px;
        }

        .thumbs {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .thumb {
            width: 52px;
            height: 52px;
            border-radius: 9px;
            border: 1px solid #dce3e6;
            background: radial-gradient(circle, #eee 0 10%, #555 11% 20%, #171919 21% 55%, #eee 56% 58%, #222 59%);
        }

        .thumb.active {
            border: 2px solid var(--green);
        }

        .details h2 {
            font-size: 25px;
            font-weight: 900;
            margin: 8px 0;
        }

        .rating {
            color: var(--orange);
            font-size: 12px;
        }

        .price {
            font-size: 30px;
            font-weight: 900;
            margin: 12px 0;
        }

        .old-price {
            font-size: 12px;
            color: #adb4b9;
            text-decoration: line-through;
            margin-left: 8px;
        }

        .bullets {
            padding-left: 16px;
            color: #6e7981;
            font-size: 10px;
            line-height: 1.9;
        }

        .order-box {
            background: #f8fafb;
            border: 1px solid #e5ebee;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 12px 30px rgba(22, 39, 51, .04);
        }

        .order-box h3 {
            font-size: 18px;
            font-weight: 900;
        }

        .form-label {
            font-size: 9px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border-color: #e2e8eb;
            border-radius: 8px;
            font-size: 10px;
            min-height: 38px;
        }

        .choice {
            background: #fff;
            border: 1px solid #e2e8eb;
            border-radius: 10px;
            padding: 10px;
            font-size: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .choice input {
            accent-color: var(--green);
        }

        .color-dot {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: inline-block;
            background: #0b8b45;
            border: 2px solid #fff;
            box-shadow: 0 0 0 1px #cfd8d4;
        }

        .mode {
            border: 1px solid #e2e8eb;
            background: #fff;
            border-radius: 6px;
            padding: 6px 9px;
            font-size: 9px;
        }

        .mode.active {
            background: var(--green);
            border-color: var(--green);
            color: #fff;
        }

        .testimonials {
            background: #f7f9fa;
        }

        .big-rating {
            font-size: 38px;
            font-weight: 900;
            line-height: 1;
        }

        .review-card {
            background: #fff;
            border: 1px solid #e6ebee;
            border-radius: 15px;
            padding: 18px;
            height: 100%;
        }

        .review-stars {
            color: #ffad00;
            letter-spacing: 2px;
            font-size: 10px;
        }

        .review-card p {
            color: #74808a;
            font-size: 10px;
            line-height: 1.7;
        }

        .avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: var(--green);
            color: #fff;
            display: grid;
            place-items: center;
            font-size: 9px;
            font-weight: 800;
        }

        .power {
            background: #effff4;
        }

        .power-list {
            display: grid;
            gap: 10px;
        }

        .power-item {
            background: #fff;
            border: 1px solid #e1eee6;
            border-radius: 13px;
            padding: 12px 15px;
        }

        .power-item strong {
            display: block;
            font-size: 10px;
        }

        .power-item small {
            color: #87928e;
            font-size: 8px;
        }

        .ticker {
            background: var(--green);
            color: #fff;
            overflow: hidden;
            white-space: nowrap;
            font-size: 9px;
            padding: 8px 0;
        }

        .newsletter {
            padding: 42px 0;
        }

        .newsletter .form-control {
            min-height: 42px;
            border-radius: 10px 0 0 10px;
        }

        .newsletter .btn {
            border-radius: 0 10px 10px 0;
        }

        footer {
            border-top: 1px solid var(--line);
            padding: 25px 0;
            color: #a2aab0;
            font-size: 9px;
            text-align: center;
        }

        @media (max-width: 991.98px) {
            .hero {
                padding-top: 40px;
            }

            .hero-video {
                min-height: 280px;
                margin-top: 25px;
            }

            .section {
                padding: 55px 0;
            }
        }

        @media (max-width: 767.98px) {
            .hero-copy h1 {
                letter-spacing: -2px;
            }

            .stats {
                gap: 18px;
            }

            .feature {
                justify-content: flex-start;
            }

            .gallery {
                grid-template-columns: 42px 1fr;
            }

            .thumb {
                width: 42px;
                height: 42px;
            }

            .order-box {
                margin-top: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="announcement">🔥 Introducing the Live Turbo Mini — Save 20% this week only. Shop Now →</div>

    <header class="brand-bar">
        <a href="#" class="brand">Nitto<span>z</span></a>
    </header>

    <main>
        <!-- HERO -->
        <section class="hero">
            <div class="container-xl">
                <div class="row align-items-center g-5">
                    <div class="col-lg-5 hero-copy">
                        <h1>Feel the <span class="green">TURBO</span> difference.</h1>
                        <div class="stats">
                            <div class="stat"><strong>2,800</strong><small>RPM speed</small></div>
                            <div class="stat"><strong>&lt;25</strong><small>dB silent</small></div>
                            <div class="stat"><strong>28W</strong><small>max power</small></div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="#order" class="btn btn-green">Order Now — $89</a>
                            <a href="#product" class="btn btn-soft">See Features →</a>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="hero-video">
                            <div class="play"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="feature-strip">
            <div class="container-xl">
                <div class="row g-0">
                    <div class="col-md-3 col-6">
                        <div class="feature">
                            <div class="feature-icon">⚡</div>
                            <div><strong>Brushless DC Motor</strong><small>Rated for 10,000+ hours</small></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature">
                            <div class="feature-icon">▥</div>
                            <div><strong>5 Speed Modes</strong><small>Silent to Full Turbo</small></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature">
                            <div class="feature-icon">◉</div>
                            <div><strong>App & Voice Control</strong><small>iOS · Android · Alexa · Google</small></div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="feature">
                            <div class="feature-icon">✓</div>
                            <div><strong>3-Year Warranty</strong><small>Free replacement in year one</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ENGINEERING -->
        <section class="section" id="product">
            <div class="container-xl">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="product-image"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="eyebrow mb-3">Engineering excellence features</div>
                        <h2 class="section-title mb-3">Designed for people who demand more from their environment.</h2>
                        <p class="section-copy mb-4">The Live Turbo Mini isn't just a desk fan — it's a precision
                            climate device built with aerospace-grade blade geometry, a sealed brushless motor, and an
                            intelligent chip that auto-adjusts RPM to maintain target temperature.</p>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="feature-card"><strong>🌬 AeroFoil Blades</strong><small>7 blade design for
                                        max airflow</small></div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card"><strong>🔇 Ultra-Quiet</strong><small>&lt; 25 dB on Silent
                                        mode</small></div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card"><strong>⚙ Smart Control</strong><small>5 modes + app
                                        control</small></div>
                            </div>
                            <div class="col-6">
                                <div class="feature-card"><strong>⚡ Energy Efficient</strong><small>28W max
                                        power</small></div>
                            </div>
                        </div>
                        <a href="#order" class="d-inline-block mt-4 text-success fw-bold" style="font-size:11px;">Shop
                            Live Turbo Mini →</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRODUCT + ORDER -->
        <section class="section section-soft" id="order">
            <div class="container-xl">
                <div class="row g-5">
                    <div class="col-lg-6">
                        <div class="gallery">
                            <div class="thumbs">
                                <div class="thumb active"></div>
                                <div class="thumb"></div>
                                <div class="thumb"></div>
                                <div class="thumb"></div>
                            </div>
                            <div>
                                <div class="product-image mb-4"></div>
                                <div class="eyebrow">Nittoz — Tech Collection 2026</div>
                                <div class="details">
                                    <h2>Live Turbo Mini Fan</h2>
                                    <p class="text-muted" style="font-size:9px;">Pro Edition · Brushless DC · Wi-Fi
                                        Smart</p>
                                    <p class="section-copy">The Live Turbo Mini delivers 2,800 RPM of near-silent
                                        airflow using a brushless DC motor built for desks, nightstands, and everywhere
                                        in between. Five smart speeds. One compact powerhouse.</p>
                                    <div class="rating">★★★★★ <span class="text-muted">4.9 (2,412 reviews)</span> <span
                                            class="text-success fw-bold">✓ In Stock</span></div>
                                    <div class="price">$89.00 <span class="old-price">$119.00</span> <span
                                            class="badge text-bg-warning" style="font-size:8px;">Save 25%</span></div>
                                    <ul class="bullets">
                                        <li>2,800 RPM brushless DC motor — rated 10,000+ hours</li>
                                        <li>5 speed modes: Silent · 25 dB to full Turbo</li>
                                        <li>360° oscillation · 8-hour sleep timer</li>
                                        <li>Free shipping · 30-day returns · 3-year warranty</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="order-box">
                            <h3>Place Your Order</h3>
                            <p class="text-muted" style="font-size:9px;">Fill in your details and we'll deliver directly
                                to your door.</p>

                            <div class="mb-3"><label class="form-label">COLOUR — FOREST GREEN</label>
                                <div class="d-flex align-items-center gap-2"><span class="color-dot"></span><small
                                        class="text-muted">Forest Green</small></div>
                            </div>
                            <div class="mb-3"><label class="form-label">SPEED MODE</label>
                                <div class="d-flex flex-wrap gap-1"><button type="button"
                                        class="mode">Silent</button><button type="button"
                                        class="mode">Low</button><button type="button"
                                        class="mode active">Medium</button><button type="button"
                                        class="mode">High</button><button type="button"
                                        class="mode">Turbo</button></div>
                            </div>
                            <div class="mb-3"><label class="form-label">QUANTITY</label>
                                <div class="d-flex align-items-center gap-2"><button
                                        class="btn btn-light btn-sm">−</button><span>1</span><button
                                        class="btn btn-light btn-sm">+</button><strong
                                        class="ms-2">$89.00</strong><small class="text-muted">total</small></div>
                            </div>
                            <hr>

                            <div class="row g-2">
                                <div class="col-12"><label class="form-label">FULL NAME *</label><input
                                        class="form-control" placeholder="e.g. Ahmed Raza"></div>
                                <div class="col-md-6"><label class="form-label">EMAIL *</label><input
                                        class="form-control" placeholder="you@email.com"></div>
                                <div class="col-md-6"><label class="form-label">PHONE *</label><input
                                        class="form-control" placeholder="+880 1XX XXX XXXX"></div>
                                <div class="col-12"><label class="form-label">DELIVERY ADDRESS *</label>
                                    <textarea class="form-control" rows="2" placeholder="House, Road, Area, City"></textarea>
                                </div>
                            </div>

                            <label class="form-label mt-3">PAYMENT METHOD *</label>
                            <label class="choice"><input type="radio" name="payment" checked>
                                <span>💵</span><span><strong>Cash on Delivery</strong><small
                                        class="d-block text-muted">Pay when your package arrives</small></span></label>
                            <label class="choice"><input type="radio" name="payment">
                                <span>💳</span><span><strong>Visa / Mastercard</strong><small
                                        class="d-block text-muted">Secure card payment</small></span></label>
                            <label class="choice"><input type="radio" name="payment">
                                <span>▣</span><span><strong>bKash</strong><small class="d-block text-muted">Send to
                                        01XXXXXXXXX</small></span></label>

                            <div class="d-flex justify-content-between border-top pt-3 mt-3" style="font-size:10px;">
                                <span>Total</span><strong>$89.00</strong></div>
                            <button class="btn btn-green w-100 mt-3">Place Order — $89.00</button>
                            <div class="text-center text-muted mt-2" style="font-size:8px;">🔒 Your information is
                                encrypted and secure.</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- TESTIMONIALS -->
        <section class="section testimonials">
            <div class="container-xl">
                <div class="row align-items-end mb-4">
                    <div class="col">
                        <div class="eyebrow">Testimonials</div>
                        <h2 class="section-title" style="font-size:32px;">What customers say</h2>
                    </div>
                    <div class="col-auto text-end">
                        <div class="big-rating">4.9</div>
                        <div class="rating">★★★★★</div><small class="text-muted" style="font-size:8px;">2,412
                            verified reviews</small>
                    </div>
                </div>
                <div class="row g-3">
                    @foreach ([['M', 'Rahim A.', 'New York, USA', 'Turbo mode is genuinely powerful for how small this thing is. Silent mode is completely inaudible — perfect for sleeping with it on.'], ['R', 'Rohim A.', 'Dubai, UAE', 'Beautiful build. Build quality is excellent — feels premium, not plasticky. App setup took under a minute. Highly recommended!'], ['J', 'Jonas K.', 'Berlin, Germany', 'Finally a fan with this precise range. Live mini is uniform, noise level, and app experience. Nothing else comes close compact!'], ['P', 'Priya S.', 'Singapore', 'Excellent fan, turbo mode is surprisingly strong. App is powerful. Power could easily be 30cm longer — hence the half star.']] as $review)
                        <div class="col-md-6">
                            <div class="review-card">
                                <div class="review-stars">★★★★★</div>
                                <p class="mt-3">“{{ $review[3] }}”</p>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="avatar">{{ $review[0] }}</div>
                                    <div><strong style="font-size:9px;">{{ $review[1] }}</strong><small
                                            class="d-block text-muted"
                                            style="font-size:8px;">{{ $review[2] }}</small></div><span
                                        class="ms-auto text-success" style="font-size:8px;">✓ Verified</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- POWER -->
        <section class="section power">
            <div class="container-xl">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6">
                        <div class="eyebrow">Nittoz · Live Turbo Mini</div>
                        <h2 class="section-title mt-2">Power in a <span class="text-success">small package.</span>
                        </h2>
                        <p class="section-copy">Engineered for people who want real airflow without noise, bulk, or
                            wasted energy. Live Turbo Mini delivers on every front — from whisper-quiet nights to
                            full-power workdays.</p>
                        <div class="d-flex gap-2 mt-4"><a href="#order" class="btn btn-green">Shop Now —
                                $89</a><button class="btn btn-soft">Download App</button></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="power-list">
                            <div class="power-item"><strong>⚡ 2,800 RPM Turbo Speed</strong><small>AeroFoil 7-blade
                                    system pushes 30% more air than flat-blade designs at the same RPM.</small></div>
                            <div class="power-item"><strong>🔇 Silent Mode &lt; 25 dB</strong><small>Quieter than a
                                    library at its lowest setting. Perfect companion for sleeping or deep focus.</small>
                            </div>
                            <div class="power-item"><strong>📱 Full Smart Control</strong><small>Wi-Fi app, Alexa, and
                                    Google Home support. Schedule, timer, and remote speed control.</small></div>
                            <div class="power-item"><strong>✓ 3-Year Warranty</strong><small>We stand behind every fan.
                                    Free replacement in year one, no questions asked.</small></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="ticker">🚚 3-year manufacturer warranty &nbsp;&nbsp; • &nbsp;&nbsp; 📱 Wi-Fi, Alexa & Google Home
            ready &nbsp;&nbsp; • &nbsp;&nbsp; ⚡ 72-hour sale — 20% off today only &nbsp;&nbsp; • &nbsp;&nbsp; 🌬
            Brushless DC motor — 40% more efficient</div>

        <!-- NEWSLETTER -->
        <section class="newsletter">
            <div class="container-xl">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="eyebrow">Newsletter</div>
                        <h3 class="fw-bold mb-1" style="font-size:18px;">Get updates on new products & exclusive deals
                        </h3>
                        <p class="text-muted mb-0" style="font-size:9px;">By subscribing you agree to our <span
                                class="text-success">Privacy Policy.</span></p>
                    </div>
                    <div class="col-lg-5">
                        <form class="input-group"><input type="email" class="form-control"
                                placeholder="your@email.com"><button class="btn btn-green">Subscribe</button></form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="mb-3">◉ &nbsp; ◉ &nbsp; ◉ &nbsp; ◉</div>
        © {{ date('Y') }} Nittoz. All Rights Reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
