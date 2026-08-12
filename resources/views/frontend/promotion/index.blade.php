<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>খাঁটি দেশি ঘি – পুষ্টি ও স্বাদের পরিপূর্ণতা!</title>
    <!-- Tailwind via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Noto Sans Bengali for a native feel -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Bengali', sans-serif;
            background: #fcfaf7;
            color: #1e1b1a;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #fdf8f0 0%, #f5ede1 100%);
        }
        .product-card {
            background: white;
            border-radius: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.05);
            border: 1px solid #f0e8dd;
            transition: all 0.2s ease;
        }
        .product-card:hover {
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background: #c49a6c;
            color: white;
            font-weight: 700;
            padding: 0.9rem 2.5rem;
            border-radius: 60px;
            transition: all 0.2s ease;
            border: 1px solid #b58b5e;
            box-shadow: 0 4px 0 #8f6a44;
            letter-spacing: 0.03em;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 0 #8f6a44;
            background: #d1a87e;
        }
        .btn-primary:active {
            transform: translateY(4px);
            box-shadow: 0 2px 0 #8f6a44;
        }
        .badge-gold {
            background: #f2e3d0;
            color: #7a5d3c;
            border-radius: 60px;
            padding: 0.2rem 1.2rem;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            border: 1px solid #dcc29e;
        }
        .input-stitch {
            background: white;
            border: 1px solid #e2d6c8;
            border-radius: 60px;
            padding: 0.9rem 1.5rem;
            width: 100%;
            color: #1e1b1a;
            transition: 0.2s;
        }
        .input-stitch:focus {
            outline: none;
            border-color: #c49a6c;
            box-shadow: 0 0 0 4px #c49a6c22;
        }
        .input-stitch::placeholder {
            color: #a09488;
            font-weight: 300;
        }
        .faq-question {
            border-bottom: 1px solid #ede5db;
            padding: 1rem 0;
            cursor: pointer;
        }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
            color: #5a4d42;
            font-weight: 300;
        }
        .faq-answer.open {
            max-height: 300px;
        }
        .sticky-cta {
            background: #fcfaf7ee;
            backdrop-filter: blur(8px);
            border-top: 1px solid #e2d6c8;
        }
        .benefit-icon {
            background: #f5ede1;
            border-radius: 100%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }
        .review-card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.5rem;
            border: 1px solid #f0e8dd;
        }
        @media (max-width: 640px) {
            .hero-gradient {
                background: linear-gradient(135deg, #fdf8f0 0%, #f5ede1 100%);
            }
        }
    </style>
</head>
<body>

    <!-- MAIN LANDING PAGE -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-5 sm:py-8">

        <!-- HEADER: Brand + Tagline -->
        <div class="flex flex-wrap justify-between items-center gap-3 mb-6">
            <div class="flex items-center gap-3">
                <span class="text-2xl font-bold tracking-tight text-[#7a5d3c]">🍯 খাঁটি ঘি</span>
                <span class="badge-gold">১০০% বিশুদ্ধ</span>
            </div>
            <div class="flex items-center gap-2 text-sm uppercase tracking-wider text-[#7a5d3c] bg-white px-4 py-1.5 rounded-full border border-[#e2d6c8] shadow-sm">
                <span>📞</span>
                <span>০১৭XX-XXXXXX</span>
            </div>
        </div>

        <!-- HERO SECTION -->
        <div class="hero-gradient rounded-3xl p-6 sm:p-10 mb-10 border border-[#f0e8dd]">
            <div class="grid md:grid-cols-2 gap-8 items-center">
                <!-- Left: Text -->
                <div class="space-y-5">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight text-[#3d2c1b]">
                        খাঁটি দেশি ঘি – <br>পুষ্টি ও স্বাদের পরিপূর্ণতা!
                    </h1>
                    <p class="text-lg text-[#5a4d42] font-light leading-relaxed">
                        খাঁটি ঘি তৈরি হয় দুধের মাখন থেকে, যেখানে কোনো কৃত্রিম উপাদান বা রাসায়নিক ব্যবহার করা হয় না।
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <span class="bg-white/70 backdrop-blur-sm px-5 py-2 rounded-full border border-[#dcc29e] text-sm font-medium text-[#7a5d3c]">
                            ⭐ প্রতি কেজি মাত্র ১৫০০ টাকা
                        </span>
                        <span class="bg-[#7a5d3c] text-white text-xs px-4 py-1.5 rounded-full font-medium">
                            সীমিত অফার
                        </span>
                    </div>
                    <a href="#order" class="btn-primary inline-block text-center">
                        আজই অর্ডার করুন
                    </a>
                </div>
                <!-- Right: Image -->
                <div class="flex justify-center">
                    <div class="relative w-64 h-64 sm:w-80 sm:h-80 md:w-96 md:h-96">
                        <img src="https://t4.ftcdn.net/jpg/13/15/19/43/360_F_1315194377_Ro8c5tjMXYc3oEXUJ6zgyeIENDkdQyJX.jpg" 
                             alt="Pure Ghee in a Bowl" 
                             class="w-full h-full object-cover rounded-3xl shadow-2xl border-4 border-white/60" />
                        <div class="absolute -bottom-3 -right-3 bg-white rounded-full px-4 py-2 shadow-lg border border-[#e2d6c8] text-sm font-medium">
                            🧈 ১০০% বিশুদ্ধ
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BENEFITS / WHY THIS GHEE -->
        <div class="my-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-[#3d2c1b] mb-3">আপনার পরিবারের জন্য নিরাপদ ও খাঁটি ঘি</h2>
            <p class="text-center text-[#5a4d42] max-w-2xl mx-auto mb-8 text-sm sm:text-base">
                আমাদের খাঁটি ঘি গরুর দুধ থেকে তৈরি, যা কোনো কেমিক্যাল বা প্রিজারভেটিভ ছাড়াই প্রস্তুত। শিশুসহ পরিবারের সব সদস্যের জন্য নিরাপদ ও পুষ্টিকর।
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">💪</div>
                    <p class="text-xs font-medium">হজম শক্তি বাড়ায়</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">✨</div>
                    <p class="text-xs font-medium">ত্বক ও চুল সুন্দর রাখে</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">🧠</div>
                    <p class="text-xs font-medium">শিশুর বুদ্ধি ও হাড় গঠনে সহায়তা</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">🛡️</div>
                    <p class="text-xs font-medium">রোগ প্রতিরোধ ক্ষমতা বৃদ্ধি করে</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">⚡</div>
                    <p class="text-xs font-medium">শক্তি ও কর্মক্ষমতা বাড়ায়</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">🧘</div>
                    <p class="text-xs font-medium">স্মৃতিশক্তি ও ফোকাস উন্নত করে</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">🌿</div>
                    <p class="text-xs font-medium">আয়ু ও প্রাণশক্তি বৃদ্ধি করে</p>
                </div>
                <div class="product-card p-4 text-center">
                    <div class="benefit-icon mx-auto mb-2">❤️</div>
                    <p class="text-xs font-medium">জয়েন্টের ব্যথা উপশমে সাহায্য</p>
                </div>
            </div>
        </div>

        <!-- WHY BUY FROM US (Features) -->
        <div class="my-12 bg-[#f5ede1] rounded-3xl p-6 sm:p-10 border border-[#e2d6c8]">
            <h3 class="text-xl sm:text-2xl font-bold text-center text-[#3d2c1b] mb-6">কেন আমাদের থেকে খাঁটি ঘি কিনবেন?</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">✅</div>
                    <p class="font-semibold text-sm">বিশুদ্ধতা নিশ্চিত</p>
                    <p class="text-xs text-[#5a4d42]">খাঁটি দুধ থেকে, কোনো রাসায়নিক ছাড়াই।</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">🐄</div>
                    <p class="font-semibold text-sm">দেশি গাভীর দুধে তৈরি</p>
                    <p class="text-xs text-[#5a4d42]">সুগন্ধি ও পুষ্টিকর ঘি</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">🏺</div>
                    <p class="font-semibold text-sm">প্রাকৃতিক প্রস্তুত প্রক্রিয়া</p>
                    <p class="text-xs text-[#5a4d42]">ঐতিহ্যবাহী ও প্রাকৃতিক উপায়ে</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">🔬</div>
                    <p class="font-semibold text-sm">ল্যাব টেস্টেড মান</p>
                    <p class="text-xs text-[#5a4d42]">১০০% মানসম্মত ও নিরাপদ</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">👃</div>
                    <p class="font-semibold text-sm">দারুণ স্বাদ ও ঘ্রাণ</p>
                    <p class="text-xs text-[#5a4d42]">অনন্য স্বাদ ও মনমাতানো ঘ্রাণ</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">🚫</div>
                    <p class="font-semibold text-sm">ভেজালমুক্ত নিশ্চয়তা</p>
                    <p class="text-xs text-[#5a4d42]">পাম অয়েল বা মিশ্র তেল নেই</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">📦</div>
                    <p class="font-semibold text-sm">হাইজেনিক প্যাকেজিং</p>
                    <p class="text-xs text-[#5a4d42]">আধুনিক ও পরিষ্কার পরিবেশে</p>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-[#f0e8dd] text-center">
                    <div class="text-3xl mb-1">🚚</div>
                    <p class="font-semibold text-sm">হোম ডেলিভারি</p>
                    <p class="text-xs text-[#5a4d42]">সরাসরি আমাদের কাছ থেকে</p>
                </div>
            </div>
        </div>

        <!-- CUSTOMER REVIEWS -->
        <div class="my-12">
            <h3 class="text-xl sm:text-2xl font-bold text-center text-[#3d2c1b] mb-6">কাস্টমার রিভিউ</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="review-card">
                    <div class="flex items-center gap-2 text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm mt-2 font-medium">"অসাধারণ খাঁটি ঘি! আমি অনেকদিন ধরে খুঁজছিলাম, অবশেষে পেয়েছি। গন্ধ ও স্বাদ একদম পারফেক্ট।"</p>
                    <p class="text-xs text-[#5a4d42] mt-2">— মোঃ রফিকুল ইসলাম</p>
                </div>
                <div class="review-card">
                    <div class="flex items-center gap-2 text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm mt-2 font-medium">"পরিবারের সবাই খুব পছন্দ করেছে। বিশেষ করে বাচ্চারা খেতে খুব ভালোবাসে। ডেলিভারিও ছিল দ্রুত।"</p>
                    <p class="text-xs text-[#5a4d42] mt-2">— নাসরিন আক্তার</p>
                </div>
                <div class="review-card">
                    <div class="flex items-center gap-2 text-yellow-500 text-sm">⭐⭐⭐⭐⭐</div>
                    <p class="text-sm mt-2 font-medium">"আমি নিয়মিত এই ঘি ব্যবহার করি। স্বাস্থ্যের জন্য অনেক উপকারী এবং স্বাদেও অতুলনীয়।"</p>
                    <p class="text-xs text-[#5a4d42] mt-2">— ডা. আশরাফুল হক</p>
                </div>
            </div>
        </div>

        <!-- ORDER FORM + DELIVERY & FAQ -->
        <div id="order" class="grid md:grid-cols-5 gap-8 my-12 scroll-mt-20">
            <!-- Left: Order Form (colspan 3) -->
            <div class="md:col-span-3 space-y-6">
                <div class="flex items-center gap-2 border-b border-[#e2d6c8] pb-3">
                    <span class="text-2xl">📋</span>
                    <span class="uppercase tracking-widest text-sm font-bold text-[#3d2c1b]">অর্ডার করতে ফর্মটি পূরণ করুন</span>
                </div>
                <form id="orderForm" class="space-y-4 bg-white p-6 rounded-3xl shadow-sm border border-[#f0e8dd]">
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-[#5a4d42] mb-1 font-medium">আপনার নাম</label>
                        <input type="text" class="input-stitch" placeholder="আপনার পুরো নাম লিখুন" required />
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-[#5a4d42] mb-1 font-medium">মোবাইল নম্বর</label>
                        <input type="tel" class="input-stitch" placeholder="০১৭XX-XXXXXX" required />
                    </div>
                    <div>
                        <label class="block text-xs uppercase tracking-wider text-[#5a4d42] mb-1 font-medium">সম্পূর্ণ ঠিকানা</label>
                        <input type="text" class="input-stitch" placeholder="বাড়ি, রাস্তা, থানা, শহর" required />
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-[#5a4d42] mb-1 font-medium">পরিমাণ (কেজি)</label>
                            <select class="input-stitch bg-white">
                                <option value="1">১ কেজি</option>
                                <option value="2" selected>২ কেজি</option>
                                <option value="3">৩ কেজি</option>
                                <option value="5">৫ কেজি</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider text-[#5a4d42] mb-1 font-medium">ডেলিভারি এলাকা</label>
                            <select class="input-stitch bg-white">
                                <option>ঢাকা সিটি</option>
                                <option selected>ঢাকার বাইরে</option>
                            </select>
                        </div>
                    </div>
                    <!-- Delivery Charge (demo) -->
                    <div class="flex items-center gap-4 bg-[#f5ede1] p-3 rounded-full border border-[#e2d6c8]">
                        <span class="text-sm font-medium">🚚 ডেলিভারি চার্জ</span>
                        <span class="ml-auto font-mono text-[#c49a6c] font-bold text-sm" id="deliveryFee">৬০ টাকা</span>
                        <span class="text-[10px] uppercase text-[#7a5d3c]">(ঢাকার বাইরে)</span>
                    </div>
                    <button type="submit" class="btn-primary w-full text-center text-base flex items-center justify-center gap-3">
                        <span>অর্ডার কনফর্ম করুন</span>
                        <span>→</span>
                    </button>
                    <p class="text-[10px] text-center text-[#a09488] tracking-wider">✅ পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন</p>
                </form>
                <!-- Trust Badges -->
                <div class="flex flex-wrap items-center gap-3 justify-center">
                    <span class="text-xs font-medium bg-[#f5ede1] px-4 py-2 rounded-full border border-[#e2d6c8]">📦 হোম ডেলিভারি</span>
                    <span class="text-xs font-medium bg-[#f5ede1] px-4 py-2 rounded-full border border-[#e2d6c8]">🔄 ৭-দিন রিটার্ন সুবিধা</span>
                    <span class="text-xs font-medium bg-[#f5ede1] px-4 py-2 rounded-full border border-[#e2d6c8]">⭐ ৪.৯/৫ রেটিং</span>
                </div>
            </div>

            <!-- Right: FAQ + Contact (colspan 2) -->
            <div class="md:col-span-2 space-y-6">
                <!-- FAQ -->
                <div class="bg-white p-6 rounded-3xl border border-[#f0e8dd] shadow-sm">
                    <h3 class="text-sm font-bold uppercase tracking-widest flex items-center gap-2 text-[#3d2c1b]">
                        <span>❓</span> জিজ্ঞাসা
                    </h3>
                    <div class="mt-3 space-y-1 text-sm">
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ঘি কীভাবে তৈরি হয়?</span>
                                <span class="text-[#c49a6c] font-bold">+</span>
                            </div>
                            <div class="faq-answer text-xs leading-relaxed pt-1">
                                খাঁটি গরুর দুধের মাখন থেকে প্রাকৃতিক পদ্ধতিতে তৈরি। কোনো প্রিজারভেটিভ বা কৃত্রিম উপাদান ব্যবহার করা হয় না।
                            </div>
                        </div>
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">ডেলিভারি সময় কত?</span>
                                <span class="text-[#c49a6c] font-bold">+</span>
                            </div>
                            <div class="faq-answer text-xs leading-relaxed pt-1">
                                ঢাকার মধ্যে ১-২ দিন, ঢাকার বাইরে ২-৪ দিন। ক্যাশ অন ডেলিভারি সুবিধা উপলব্ধ।
                            </div>
                        </div>
                        <div class="faq-question" onclick="toggleFaq(this)">
                            <div class="flex justify-between items-center">
                                <span class="font-medium">কিভাবে সংরক্ষণ করব?</span>
                                <span class="text-[#c49a6c] font-bold">+</span>
                            </div>
                            <div class="faq-answer text-xs leading-relaxed pt-1">
                                ঘরোয়া তাপমাত্রায় সংরক্ষণ করুন। সরাসরি সূর্যের আলো থেকে দূরে রাখুন। দীর্ঘমেয়াদী সংরক্ষণে ফ্রিজে রাখতে পারেন।
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Contact / Office -->
                <div class="bg-[#f5ede1] p-5 rounded-3xl border border-[#e2d6c8]">
                    <h4 class="text-sm font-bold uppercase tracking-widest text-[#3d2c1b] flex items-center gap-2">
                        <span>📞</span> প্রয়োজনে যোগাযোগ করুন
                    </h4>
                    <div class="mt-3 space-y-2 text-sm">
                        <p><span class="font-medium">ফোন:</span> ০১৭XX-XXXXXX</p>
                        <p><span class="font-medium">অফিস সময়:</span> শনি – বৃহস্পতি: সকাল ৯টা – রাত ১১টা</p>
                        <p><span class="font-medium">অফিস:</span> শপ-২, গ্রাউন্ড ফ্লোর, হাউজ-২২, ব্লক-সি, রোড-৩, মিরপুর -১৩, ঢাকা</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STICKY CTA (floating bar) -->
        <div class="sticky-cta fixed bottom-0 left-0 w-full px-4 py-3 flex items-center justify-between md:justify-around z-50 border-t shadow-lg">
            <div class="flex items-center gap-3">
                <span class="font-bold text-[#7a5d3c] text-base">🍯 খাঁটি ঘি</span>
                <span class="hidden sm:inline text-sm text-[#5a4d42]">প্রতি কেজি ১৫০০ টাকা</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-[#c49a6c] font-bold">১৫০০ টাকা/কেজি</span>
                <a href="#order" class="bg-[#c49a6c] text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg hover:bg-[#d1a87e] transition">অর্ডার করুন</a>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="mt-28 pt-6 border-t border-[#e2d6c8] text-[10px] uppercase tracking-widest text-[#a09488] flex flex-wrap justify-between gap-2">
            <span>© ২০২৬ খাঁটি ঘি স্টোর</span>
            <span>ওয়েবসাইট ডিজাইন করেছেন</span>
        </div>
    </div>

    <!-- JavaScript for FAQ & Form -->
    <script>
        // FAQ Toggle
        function toggleFaq(el) {
            const answer = el.querySelector('.faq-answer');
            const icon = el.querySelector('.text-\\[\\#c49a6c\\]');
            if (answer) {
                answer.classList.toggle('open');
                if (icon) {
                    icon.textContent = answer.classList.contains('open') ? '−' : '+';
                }
            }
        }

        // Form Submission Handler
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('orderForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('✅ আপনার অর্ডার কনফর্ম হয়েছে! আমাদের টিম শীঘ্রই যোগাযোগ করবে।');
                });
            }
        });
    </script>
</body>
</html>