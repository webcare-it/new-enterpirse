@extends('backend.layouts.app')

@section('content')
    <style>
        /* ─────────── SCOPED STYLES (only for this page) ─────────── */
        .oc-wrap {
            --oc-bg: #f6f7fb;
            --oc-card: #ffffff;
            --oc-border: #e6e8ef;
            --oc-primary: #4f46e5;
            --oc-primary-soft: #eef2ff;
            --oc-user-bubble: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            --oc-bot-bubble: #ffffff;
            --oc-text: #1f2937;
            --oc-muted: #6b7280;
            --oc-shadow: 0 10px 30px rgba(15, 23, 42, .06);
        }

        .oc-wrap {
            padding: 22px;
        }

        .oc-card {
            position: relative;
            overflow: hidden;
            background: var(--oc-card);
            border: 1px solid var(--oc-border);
            border-radius: 16px;
            box-shadow: var(--oc-shadow);
        }

        /* Header */
        .oc-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 22px;
            border-bottom: 1px solid var(--oc-border);
            background: linear-gradient(180deg, #ffffff 0%, #fbfbfe 100%);
        }

        .oc-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--oc-text);
            font-size: 16px;
            margin: 0;
        }

        .oc-title .oc-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--oc-primary-soft);
            color: var(--oc-primary);
            display: grid;
            place-items: center;
            font-size: 20px;
        }

        .oc-title small {
            display: block;
            font-weight: 400;
            color: var(--oc-muted);
            font-size: 12px;
        }

        .oc-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .oc-btn {
            border: 1px solid var(--oc-border);
            background: #fff;
            color: var(--oc-text);
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .15s ease;
            cursor: pointer;
        }

        .oc-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(15, 23, 42, .08);
        }

        .oc-btn.oc-btn-primary {
            background: var(--oc-primary);
            border-color: var(--oc-primary);
            color: #fff;
        }

        .oc-btn.oc-btn-primary:hover {
            background: #4338ca;
        }

        .oc-btn.oc-btn-danger {
            color: #dc2626;
            border-color: #fecaca;
            background: #fef2f2;
        }

        .oc-btn.oc-btn-danger:hover {
            background: #fee2e2;
        }

        /* Chat body */
        .oc-body {
            height: 480px;
            overflow-y: auto;
            padding: 22px;
            background:
                radial-gradient(1200px 400px at 50% -10%, #eef2ff 0%, transparent 60%),
                var(--oc-bg);
        }

        .oc-body::-webkit-scrollbar {
            width: 8px;
        }

        .oc-body::-webkit-scrollbar-thumb {
            background: #d5d8e2;
            border-radius: 10px;
        }

        .oc-body::-webkit-scrollbar-track {
            background: transparent;
        }

        /* Message rows */
        .oc-msg {
            display: flex;
            margin-bottom: 14px;
        }

        .oc-msg.user {
            justify-content: flex-end;
        }

        .oc-msg.bot {
            justify-content: flex-start;
        }

        .oc-bubble {
            max-width: 78%;
            padding: 11px 15px;
            border-radius: 16px;
            font-size: 13.5px;
            line-height: 1.55;
            color: var(--oc-text);
            word-wrap: break-word;
            animation: oc-pop .2s ease;
        }

        @keyframes oc-pop {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .oc-msg.user .oc-bubble {
            background: var(--oc-user-bubble);
            color: #fff;
            border-bottom-right-radius: 4px;
            box-shadow: 0 6px 14px rgba(79, 70, 229, .25);
        }

        .oc-msg.bot .oc-bubble {
            background: var(--oc-bot-bubble);
            border: 1px solid var(--oc-border);
            border-bottom-left-radius: 4px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .04);
        }

        /* Typing dots */
        .oc-typing {
            display: inline-flex;
            gap: 4px;
            align-items: center;
            height: 14px;
        }

        .oc-typing span {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #c7cbe0;
            animation: oc-blink 1.2s infinite ease-in-out;
        }

        .oc-typing span:nth-child(2) {
            animation-delay: .15s;
        }

        .oc-typing span:nth-child(3) {
            animation-delay: .3s;
        }

        @keyframes oc-blink {

            0%,
            80%,
            100% {
                opacity: .35;
                transform: translateY(0);
            }

            40% {
                opacity: 1;
                transform: translateY(-3px);
            }
        }

        /* Welcome card */
        .oc-welcome {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff;
            border: 1px dashed var(--oc-border);
            border-radius: 14px;
            padding: 14px 16px;
            max-width: 92%;
            margin: 0 auto;
            color: var(--oc-text);
            font-size: 13px;
        }

        .oc-welcome .oc-emoji {
            font-size: 22px;
        }

        .oc-welcome kbd {
            background: #eef1f8;
            color: #4f46e5;
            padding: 1px 6px;
            border-radius: 6px;
            font-size: 11.5px;
            border: 1px solid #dfe3ef;
        }

        /* ─────────── SUGGESTIONS (clickable chips) ─────────── */
        .oc-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 12px 22px;
            background: #fff;
            border-top: 1px dashed var(--oc-border);
        }

        .oc-suggestions:empty {
            display: none;
            padding: 0;
            border: none;
        }

        .oc-chip {
            border: 1px solid #d6d9ff;
            background: #f6f7ff;
            color: var(--oc-primary);
            padding: 7px 14px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all .12s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .oc-chip:hover {
            background: var(--oc-primary);
            color: #fff;
            border-color: var(--oc-primary);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(79, 70, 229, .25);
        }

        .oc-chip.oc-chip-danger {
            border-color: #fecaca;
            background: #fef2f2;
            color: #dc2626;
        }

        .oc-chip.oc-chip-danger:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
            box-shadow: 0 6px 14px rgba(220, 38, 38, .25);
        }

        .oc-chip.oc-chip-success {
            border-color: #bbf7d0;
            background: #f0fdf4;
            color: #16a34a;
        }

        .oc-chip.oc-chip-success:hover {
            background: #16a34a;
            color: #fff;
            border-color: #16a34a;
            box-shadow: 0 6px 14px rgba(22, 163, 74, .25);
        }

        /* ─────────── STRUCTURED INFO FORM ─────────── */
        .oc-info-form {
            padding: 14px 22px 18px;
            background: #fff;
            border-top: 1px dashed var(--oc-border);
            display: none;
        }

        .oc-info-form.active {
            display: block;
        }

        .oc-info-form .oc-info-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--oc-muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .oc-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .oc-info-grid .oc-field-full {
            grid-column: 1 / -1;
        }

        .oc-field label {
            display: block;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--oc-muted);
            margin-bottom: 4px;
        }

        .oc-field input,
        .oc-field textarea {
            width: 100%;
            border: 1px solid var(--oc-border);
            border-radius: 10px;
            padding: 9px 12px;
            font-size: 13px;
            outline: none;
            background: #fafbff;
            transition: all .15s ease;
            color: var(--oc-text);
            font-family: inherit;
            resize: vertical;
        }

        .oc-field input:focus,
        .oc-field textarea:focus {
            border-color: #c7c9ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
        }

        .oc-info-submit {
            margin-top: 12px;
            width: 100%;
            border: none;
            background: var(--oc-primary);
            color: #fff;
            font-weight: 700;
            font-size: 13.5px;
            padding: 11px;
            border-radius: 10px;
            cursor: pointer;
            transition: all .15s ease;
        }

        .oc-info-submit:hover {
            background: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px rgba(79, 70, 229, .25);
        }

        .oc-info-cancel {
            margin-top: 8px;
            width: 100%;
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #dc2626;
            font-weight: 600;
            font-size: 12.5px;
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
        }

        .oc-info-cancel:hover {
            background: #fee2e2;
        }

        /* Footer input */
        .oc-footer {
            padding: 14px 18px;
            border-top: 1px solid var(--oc-border);
            background: #fff;
        }

        .oc-form {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #f4f5fb;
            border: 1px solid var(--oc-border);
            border-radius: 999px;
            padding: 6px 6px 6px 16px;
            transition: all .15s ease;
        }

        .oc-form:focus-within {
            border-color: #c7c9ff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
            background: #fff;
        }

        .oc-form input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            color: var(--oc-text);
        }

        .oc-form input::placeholder {
            color: #9aa1b3;
        }

        .oc-send {
            border: none;
            background: var(--oc-primary);
            color: #fff;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: all .15s ease;
        }

        .oc-send:hover {
            background: #4338ca;
            transform: scale(1.05);
        }

        .oc-send:disabled {
            background: #c7c9ff;
            cursor: not-allowed;
        }

        /* ─────────── DRAWER ─────────── */
        .oc-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, .35);
            opacity: 0;
            display: none;
            z-index: 1040;
            transition: opacity .2s ease;
            backdrop-filter: blur(2px);
        }

        .oc-drawer {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 400px;
            max-width: 92%;
            background: #fff;
            z-index: 1041;
            box-shadow: -8px 0 24px rgba(15, 23, 42, .12);
            transform: translateX(100%);
            transition: transform .25s cubic-bezier(.22, .61, .36, 1);
            display: flex;
            flex-direction: column;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .oc-drawer-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
            border-bottom: 1px solid var(--oc-border);
        }

        .oc-drawer-head strong {
            font-size: 14px;
            color: var(--oc-text);
        }

        .oc-drawer-search {
            padding: 12px 16px;
            border-bottom: 1px solid var(--oc-border);
        }

        .oc-drawer-search input {
            width: 100%;
            border: 1px solid var(--oc-border);
            border-radius: 10px;
            padding: 9px 12px;
            outline: none;
            font-size: 13px;
            background: #f8f9fd;
        }

        .oc-drawer-search input:focus {
            border-color: #c7c9ff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, .10);
        }

        .oc-drawer-body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #fcfcfe;
        }

        .oc-drawer-body::-webkit-scrollbar {
            width: 8px;
        }

        .oc-drawer-body::-webkit-scrollbar-thumb {
            background: #d5d8e2;
            border-radius: 10px;
        }

        .oc-group-title {
            font-size: 11px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--oc-muted);
            margin: 12px 0 8px;
            font-weight: 700;
        }

        .oc-sample {
            display: block;
            width: 100%;
            text-align: left;
            background: #fff;
            border: 1px solid var(--oc-border);
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 13px;
            color: var(--oc-text);
            margin-bottom: 6px;
            transition: all .12s ease;
            cursor: pointer;
        }

        .oc-sample:hover {
            border-color: #c7c9ff;
            background: var(--oc-primary-soft);
            color: var(--oc-primary);
            transform: translateX(2px);
        }
    </style>

    <div class="oc-wrap">
        <div class="oc-card">

            {{-- ─────────── HEADER ─────────── --}}
            <div class="oc-header">
                <h3 class="oc-title">
                    <span class="oc-avatar">🤖</span>
                    <span>
                        Order Assistant
                    </span>
                </h3>
                <div class="oc-actions">
                    <button class="oc-btn oc-btn-danger" id="clearChatBtn" type="button">
                        🗑 Clear
                    </button>
                    <button class="oc-btn oc-btn-primary" id="openDrawerBtn" type="button">
                        💡 Samples
                    </button>
                </div>
            </div>

            {{-- ─────────── CHAT BODY ─────────── --}}
            <div class="oc-body" id="chatBox" style="overflow-anchor: none;">
                @if ($messages->isEmpty())
                    <div class="oc-welcome">
                        <span class="oc-emoji">👋</span>
                        <div>
                            <strong>Hi Admin!</strong> Ask me anything about orders — try
                            <kbd>today orders</kbd>, <kbd>last 30 days revenue</kbd>,
                            <kbd>top products this month</kbd>.
                            <br>
                            <small class="text-muted">Or paste a product link to place an order. Click <strong>💡
                                    Samples</strong> for 100+ ready questions.</small>
                        </div>
                    </div>
                @else
                    @foreach ($messages as $msg)
                        <div class="oc-msg user">
                            <div class="oc-bubble">{{ $msg->question }}</div>
                        </div>
                        <div class="oc-msg bot">
                            <div class="oc-bubble">{!! $msg->answer !!}</div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- ─────────── SUGGESTIONS CHIPS ─────────── --}}
            <div class="oc-suggestions" id="suggestionsBox"></div>

            {{-- ─────────── STRUCTURED INFO FORM ─────────── --}}
            <div class="oc-info-form" id="infoForm">
                <div class="oc-info-title">📋 Customer Information</div>
                <div class="oc-info-grid">
                    <div class="oc-field">
                        <label>👤 Name</label>
                        <input type="text" id="infoName" placeholder="Customer name" autocomplete="off">
                    </div>
                    <div class="oc-field">
                        <label>📞 Phone</label>
                        <input type="text" id="infoPhone" placeholder="01XXXXXXXXX" autocomplete="off">
                    </div>
                    <div class="oc-field oc-field-full">
                        <label>📍 Address</label>
                        <textarea id="infoAddress" rows="2" placeholder="Full delivery address"></textarea>
                    </div>
                </div>
                <button type="button" class="oc-info-submit" id="infoSubmit">
                    ✅ Place Order
                </button>
                <button type="button" class="oc-info-cancel" id="infoCancel">
                    ✕ Cancel Order
                </button>
            </div>

            {{-- ─────────── FOOTER ─────────── --}}
            <div class="oc-footer" id="footerInput">
                <form id="chatForm" class="oc-form" autocomplete="off">
                    @csrf
                    <input type="text" id="chatInput" name="message"
                        placeholder="Type your question… or paste a product link" required>
                    <button type="submit" class="oc-send" id="sendBtn" title="Send">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                    </button>
                </form>
            </div>

            {{-- ─────────── DRAWER ─────────── --}}
            <div class="oc-backdrop" id="drawerBackdrop"></div>

            <div class="oc-drawer" id="samplesDrawer">
                <div class="oc-drawer-head">
                    <strong>💡 Sample Questions</strong>
                    <button class="oc-btn" id="closeDrawerBtn" type="button">✕</button>
                </div>
                <div class="oc-drawer-search">
                    <input type="text" id="sampleSearch" placeholder="Search samples… e.g. revenue, top, pending">
                </div>
                <div class="oc-drawer-body" id="samplesList"></div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            var askUrl = "{{ route('admin.order-chat.ask') }}";
            var clearUrl = "{{ route('admin.order-chat.clear') }}";
            var csrfToken = '{{ csrf_token() }}';

            var $box = $('#chatBox');
            var $form = $('#chatForm');
            var $input = $('#chatInput');
            var $send = $('#sendBtn');
            var $suggestionsBox = $('#suggestionsBox');
            var $infoForm = $('#infoForm');
            var $footerInput = $('#footerInput');

            // ───────── SCROLL HELPERS ─────────
            function pinToBottom() {
                if ($box.length && $box[0]) {
                    $box[0].scrollTop = $box[0].scrollHeight;
                }
            }

            // ───────── SAMPLES ─────────
            var SAMPLE_GROUPS = [{
                    title: '⏱ Time Ranges',
                    items: [
                        'today orders', 'yesterday orders', 'this week orders', 'last week orders',
                        'this month orders', 'last month orders', 'this year orders', 'last year orders',
                        'last 7 days orders', 'last 30 days orders', 'last 90 days orders',
                        'last 120 days orders', 'last 365 days orders', '15 day orders', '45 day orders'
                    ]
                },
                {
                    title: '🔢 Count',
                    items: [
                        'how many orders today', 'how many orders last 30 days',
                        'count of orders this month', 'number of orders yesterday', 'total orders this week'
                    ]
                },
                {
                    title: '🚚 Delivery Status',
                    items: [
                        'today pending orders', 'last 30 days pending orders',
                        'this month delivered orders',
                        'today shipped orders', 'last week cancelled orders', 'pending orders last 7 days',
                        'how many pending orders today', 'how many delivered this month',
                        'shipped orders last 30 days', 'cancelled orders today'
                    ]
                },
                {
                    title: '💳 Payment Status',
                    items: [
                        'today paid orders', 'today unpaid orders', 'last 30 days paid orders',
                        'unpaid orders this month', 'refunded orders this month',
                        'how many unpaid orders last 7 days', 'paid orders this week',
                        'unpaid orders last month'
                    ]
                },
                {
                    title: '💰 Revenue & Sales',
                    items: [
                        'today revenue', 'yesterday revenue', 'this week revenue', 'last week revenue',
                        'this month revenue', 'last month revenue', 'this year revenue',
                        'last 30 days revenue', 'last 7 days revenue', 'last 90 days revenue',
                        'today sales', 'this month sales', 'last 30 days sales',
                        'paid revenue today', 'paid revenue this month', 'paid revenue last 30 days',
                        'revenue of delivered orders this month', 'revenue of pending orders',
                        'revenue of cancelled orders last 30 days', 'earnings this month', 'earnings today'
                    ]
                },
                {
                    title: '📊 Averages',
                    items: [
                        'average order value', 'average order value today', 'avg order value this month',
                        'average order value last 30 days', 'avg revenue last 7 days'
                    ]
                },
                {
                    title: '🏆 Top Products',
                    items: [
                        'top products today', 'top products this month', 'top products last 30 days',
                        'top 10 products this month', 'best selling products last 7 days',
                        'best selling items today', 'top selling products last month',
                        'top items this week', 'best products this year'
                    ]
                },
                {
                    title: '👑 Top Customers',
                    items: [
                        'top customers today', 'top customers this month', 'top customers last 30 days',
                        'top 10 customers this year', 'best buyers last week',
                        'top clients this month', 'best customers last 7 days'
                    ]
                },
                {
                    title: '🏭 Vendors',
                    items: [
                        'vendor list', 'vendor list this month', 'vendor list last 30 days',
                        'top vendor order', 'top vendor order today', 'top vendor order last 30 days',
                        'top vendor sale', 'top vendor sale this month', 'top vendor sale last 30 days',
                        'top vendor profit', 'top vendor profit today', 'top vendor profit this month',
                        'top vendor profit last 30 days', 'best vendor profit last 7 days',
                        'top vendor margin this year'
                    ]
                },
                {
                    title: '📂 Categories & Brands',
                    items: [
                        'top categories', 'top categories today', 'top categories this month',
                        'top categories last 30 days', 'top brands', 'top brands this month',
                        'top brands last 30 days', 'best brands this year'
                    ]
                },
                {
                    title: '📊 Dashboard & Stats',
                    items: [
                        'dashboard', 'full report', 'everything', 'customer stats', 'total customers',
                        'customer count', 'product stats', 'product count', 'stock stats', 'inventory',
                        'low stock count', 'business metrics', 'average order value', 'ltv',
                        'conversion rate'
                    ]
                },
                {
                    title: '💳 Payment & Courier',
                    items: [
                        'payment methods', 'payment type', 'cod orders', 'courier performance',
                        'pathao orders', 'steadfast orders', 'redx orders'
                    ]
                },
                {
                    title: '🎟 Coupons & Search',
                    items: [
                        'coupon usage', 'discount total', 'promo usage', 'search analytics',
                        'top keywords', 'top searched keywords'
                    ]
                },
                {
                    title: '⭐ Reviews & Wishlist',
                    items: [
                        'reviews', 'average rating', 'satisfaction', 'positive reviews',
                        'most wishlisted', 'top wishlisted products'
                    ]
                },
                {
                    title: '⏰ Insights',
                    items: [
                        'peak hours', 'busy hours', 'rush hour', 'growth', 'monthly trend',
                        'growth this month', 'compare this month vs last month'
                    ]
                },
                {
                    title: '📅 Summaries & Reports',
                    items: [
                        'summary', 'today summary', 'yesterday summary', 'this week summary',
                        'last week summary', 'this month summary', 'last month summary',
                        'this year summary', 'last 30 days summary', 'last 7 days summary',
                        'report', 'this month report', 'last 30 days report', 'overview today',
                        'breakdown this month', 'today breakdown', 'last 30 days breakdown',
                        'monthly overview'
                    ]
                }
            ];

            function renderSamples(filter) {
                filter = (filter || '').toLowerCase().trim();
                var html = '';
                SAMPLE_GROUPS.forEach(function(group) {
                    var items = group.items.filter(function(q) {
                        return !filter || q.toLowerCase().indexOf(filter) !== -1;
                    });
                    if (!items.length) return;
                    html += '<div class="oc-group-title">' + group.title + '</div>';
                    items.forEach(function(q) {
                        html += '<button type="button" class="oc-sample sample-btn" data-q="' +
                            q.replace(/"/g, '&quot;') + '">' + q + '</button>';
                    });
                });
                if (!html) html = '<div class="text-muted text-center py-4">No matching samples.</div>';
                $('#samplesList').html(html);
            }
            renderSamples('');
            $('#sampleSearch').on('input', function() {
                renderSamples($(this).val());
            });

            // ───────── DRAWER ─────────
            function openDrawer() {
                $('#drawerBackdrop').css('display', 'block');
                requestAnimationFrame(function() {
                    $('#drawerBackdrop').css('opacity', 1);
                });
                $('#samplesDrawer').css('transform', 'translateX(0)');
            }

            function closeDrawer() {
                $('#drawerBackdrop').css('opacity', 0);
                $('#samplesDrawer').css('transform', 'translateX(100%)');
                setTimeout(function() {
                    $('#drawerBackdrop').css('display', 'none');
                }, 200);
            }
            $('#openDrawerBtn').on('click', openDrawer);
            $('#closeDrawerBtn').on('click', closeDrawer);
            $('#drawerBackdrop').on('click', closeDrawer);
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') closeDrawer();
            });

            // ───────── SAMPLE CLICK ─────────
            $(document).on('click', '.sample-btn', function() {
                var q = $(this).data('q');
                closeDrawer();
                $input.val(q);
                $form.trigger('submit');
            });

            // ───────── CHAT RENDER ─────────
            function appendBubble(html, isUser) {
                var wrapper = $('<div class="oc-msg ' + (isUser ? 'user' : 'bot') + '"></div>');
                var bubble = $('<div class="oc-bubble"></div>').html(html);
                wrapper.append(bubble);
                $box.append(wrapper);
                pinToBottom();
            }

            function showTyping() {
                var $t = $(
                    '<div class="oc-msg bot" id="typingBubble">' +
                    '<div class="oc-bubble">' +
                    '<span class="oc-typing"><span></span><span></span><span></span></span>' +
                    '</div>' +
                    '</div>'
                );
                $box.append($t);
                pinToBottom();
            }

            // ───────── SUGGESTIONS ─────────
            function renderSuggestions(suggestions) {
                $suggestionsBox.empty();
                if (!suggestions || !suggestions.length) return;

                suggestions.forEach(function(s) {
                    var cls = 'oc-chip';
                    var labelLower = (s.label || '').toLowerCase();
                    var valueLower = (s.value || '').toLowerCase();

                    if (valueLower === 'cancel' || valueLower === 'exit' || labelLower.indexOf('cancel') !==
                        -1) {
                        cls += ' oc-chip-danger';
                    } else if (valueLower === 'no' || valueLower === 'done' ||
                        labelLower.indexOf('done') !== -1 ||
                        labelLower.indexOf('checkout') !== -1 ||
                        labelLower.indexOf('place order') !== -1) {
                        cls += ' oc-chip-success';
                    }

                    var $chip = $('<button type="button" class="' + cls + '"></button>').text(s.label || s
                        .value);
                    $chip.on('click', function() {
                        $suggestionsBox.empty();
                        sendMessage(s.value, s.label);
                    });
                    $suggestionsBox.append($chip);
                });
            }

            // ───────── INFO FORM ─────────
            function showInfoForm() {
                $infoForm.addClass('active');
                $footerInput.hide();
            }

            function hideInfoForm() {
                $infoForm.removeClass('active');
                $footerInput.show();
                $('#infoName').val('');
                $('#infoPhone').val('');
                $('#infoAddress').val('');
            }

            // ───────── SEND MESSAGE ─────────
            function sendMessage(message, displayLabel) {
                appendBubble(escapeHtml(displayLabel || message), true);
                $suggestionsBox.empty();
                $send.prop('disabled', true);
                showTyping();

                $.ajax({
                    url: askUrl,
                    type: 'POST',
                    data: {
                        _token: csrfToken,
                        message: message
                    },
                    dataType: 'json'
                }).done(function(data) {
                    $('#typingBubble').remove();
                    appendBubble(data.answer || 'No answer.', false);
                    renderSuggestions(data.suggestions);

                    if (data.input_fields && data.input_fields.length) {
                        showInfoForm();
                    } else {
                        hideInfoForm();
                    }

                    // Update input placeholder based on state
                    var placeholders = {
                        awaiting_variant: 'Variant number likhun…',
                        awaiting_qty: 'Quantity likhun…',
                        awaiting_more_items: 'Product link paste korun, ba Done click korun…',
                        awaiting_shipping_area: 'Area number likhun…',
                        awaiting_info: 'Niche form puron korun…',
                        idle: 'Type your question… or paste a product link'
                    };
                    $input.attr('placeholder', placeholders[data.state] || 'Type your question…');
                }).fail(function(xhr) {
                    $('#typingBubble').remove();
                    var msg = (xhr.responseJSON && xhr.responseJSON.answer) ?
                        xhr.responseJSON.answer :
                        '⚠️ Error. Please try again.';
                    appendBubble(msg, false);
                    console.error('Chat error:', xhr.status, xhr.responseText);
                }).always(function() {
                    $send.prop('disabled', false);
                    if (!$infoForm.hasClass('active')) {
                        $input.focus();
                    }
                });
            }

            function escapeHtml(str) {
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            }

            // ───────── FORM SUBMIT ─────────
            $form.on('submit', function(e) {
                e.preventDefault();
                var message = $.trim($input.val());
                if (!message) return;
                $input.val('');
                sendMessage(message);
            });

            // ───────── INFO FORM SUBMIT ─────────
            $('#infoSubmit').on('click', function() {
                var name = $.trim($('#infoName').val());
                var phone = $.trim($('#infoPhone').val());
                var address = $.trim($('#infoAddress').val());

                if (!name || !phone || !address) {
                    alert('Sob field puron korun.');
                    return;
                }

                var payload = JSON.stringify({
                    name: name,
                    phone: phone,
                    address: address
                });
                var display = '📋 ' + name + ' | ' + phone + ' | ' + address;

                sendMessage(payload, display);
            });

            // ───────── INFO FORM CANCEL ─────────
            $('#infoCancel').on('click', function() {
                hideInfoForm();
                sendMessage('cancel', '❌ Cancel');
            });

            // ───────── CLEAR ─────────
            $('#clearChatBtn').on('click', function() {
                if (!confirm('Clear your chat history?')) return;
                $.post(clearUrl, {
                    _token: csrfToken
                }).done(function() {
                    $box.html(
                        '<div class="oc-welcome">' +
                        '<span class="oc-emoji">👋</span>' +
                        '<div><strong>Chat cleared.</strong> Ask me anything!</div>' +
                        '</div>'
                    );
                    $suggestionsBox.empty();
                    hideInfoForm();
                });
            });

            // ───────── AUTOSCROLL ON LOAD ─────────
            pinToBottom();
            $(window).on('load', pinToBottom);
        });
    </script>
@endsection
