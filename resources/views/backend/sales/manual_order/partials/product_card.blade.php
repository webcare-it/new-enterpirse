<div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-4">
    @php
        $hasVariant = $product->variants && $product->variants->count() > 0;
        $firstVariant = $hasVariant ? $product->variants->first() : null;
        $stock = $hasVariant ? $firstVariant->quantity ?? 0 : $product->inventory?->stock ?? 0;
        $price = $hasVariant ? $firstVariant->price ?? 0 : $product->price?->regular_price ?? 0;
        $discount = $product->price?->discount ?? 0;
        $hasDiscount = $discount > 0;
        $originalPrice = $hasDiscount ? $price + ($price * $discount) / 100 : $price;
    @endphp

    <div class="product-card" data-product-id="{{ $product->id }}">
        <!-- Stock Progress Ring -->
        <div class="stock-ring">
            <svg viewBox="0 0 36 36" class="ring-svg">
                <circle cx="18" cy="18" r="16" fill="none" class="ring-bg" />
                <circle cx="18" cy="18" r="16" fill="none"
                    class="ring-progress {{ $stock > 0 ? 'in-stock-ring' : 'out-stock-ring' }}"
                    stroke-dasharray="{{ $stock > 0 ? min(($stock / 100) * 100, 100) : 0 }} 100" />
            </svg>
            <span class="stock-count">{{ $stock > 0 ? $stock : 0 }}</span>
        </div>

        <div class="product-image-wrapper">
            @if ($hasDiscount)
                <span class="discount-badge">-{{ $discount }}%</span>
            @endif
            <img src="{{ uploaded_asset($product->thumbnail) }}" alt="{{ $product->name }}" class="product-image"
                loading="lazy">
        </div>

        <div class="product-info">
            <!-- Brand/Category -->
            <span class="product-category">{{ $product->category?->name ?? 'Uncategorized' }}</span>

            <h6 class="product-name">{{ Str::limit($product->name, 30) }}</h6>

            @if ($hasVariant)
                <select class="variant-select" id="variant_select_{{ $product->id }}"
                    onchange="updateVariantInfo('{{ $product->id }}')">
                    @foreach ($product->variants as $variant)
                        @php
                            $attr = json_decode($variant->attribute_value, true);
                            $displayValue = is_array($attr)
                                ? implode(' / ', array_values($attr))
                                : $variant->attribute_value;
                        @endphp

                        <option value="{{ $variant->id }}" data-stock="{{ $variant->quantity ?? 0 }}"
                            data-price="{{ $variant->price ?? 0 }}" data-discount="{{ $discount }}"
                            {{ $loop->first ? 'selected' : '' }}>
                            {{ $displayValue }} - {{ number_format($variant->price ?? 0) }}
                            {{ get_setting('currency') }}
                        </option>
                    @endforeach
                </select>
            @endif

            <!-- Price -->
            <div class="price-section" id="price_section_{{ $product->id }}">
                @if ($hasDiscount)
                    <span class="original-price" id="original_price_{{ $product->id }}">
                        {{ number_format($originalPrice) }} {{ get_setting('currency') }}
                    </span>
                @endif
                <span class="product-price" id="product_price_{{ $product->id }}">
                    {{ number_format($price) }} <span> {{ get_setting('currency') }}</span>
                </span>
            </div>

            <div class="product-actions">
                <!-- Quantity Control -->
                <div class="qty-control">
                    <button class="qty-btn qty-minus" onclick="updateQty('{{ $product->id }}', -1)"
                        {{ $stock <= 0 ? 'disabled' : '' }}>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5"
                            fill="none">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                    <input type="number" class="qty-input" id="qty_{{ $product->id }}" value="1" min="1"
                        max="{{ $stock > 0 ? $stock : 999 }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                    <button class="qty-btn qty-plus" onclick="updateQty('{{ $product->id }}', 1)"
                        {{ $stock <= 0 ? 'disabled' : '' }}>
                        <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5"
                            fill="none">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                    </button>
                </div>

                @if ($hasVariant)
                    <button class="btn-add add-to-cart-variant" data-product-id="{{ $product->id }}"
                        data-variant-id="{{ $firstVariant->id ?? '' }}" {{ $stock <= 0 ? 'disabled' : '' }}>
                        <span class="btn-tooltip">Add to Cart</span>
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                            fill="none">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="btn-loader"></span>
                    </button>
                @else
                    <button class="btn-add add-to-cart-simple" data-product-id="{{ $product->id }}"
                        data-price="{{ $price }}" data-stock="{{ $stock }}"
                        {{ $stock <= 0 ? 'disabled' : '' }}>
                        <span class="btn-tooltip">Add to Cart</span>
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2"
                            fill="none">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <span class="btn-loader"></span>
                    </button>
                @endif
            </div>

            <!-- Stock Status Bar -->
            <div class="stock-status" id="stock_status_{{ $product->id }}">
                <div class="stock-bar-bg">
                    <div class="stock-bar-fill"
                        style="width: {{ $stock > 0 ? min(($stock / 100) * 100, 100) : 0 }}%"></div>
                </div>
                <span class="stock-text {{ $stock > 0 ? 'text-success' : 'text-danger' }}">
                    {{ $stock > 0 ? $stock . ' units available' : 'Out of Stock' }}
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    /* ===== BASE CARD ===== */
    .product-card {
        position: relative;
        height: 100%;
        background: #ffffff;
        border-radius: 28px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.04);
        border: 1px solid rgba(226, 232, 240, 0.4);
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(20px);
    }

    .product-card:hover {
        transform: translateY(-12px) scale(1.01);
        box-shadow: 0 32px 80px rgba(79, 70, 229, 0.10);
        border-color: rgba(79, 70, 229, 0.2);
    }

    /* ===== STOCK RING ===== */
    .stock-ring {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 48px;
        height: 48px;
        z-index: 5;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stock-ring .ring-svg {
        width: 48px;
        height: 48px;
        transform: rotate(-90deg);
        position: absolute;
    }

    .ring-bg {
        stroke: #e2e8f0;
        stroke-width: 3;
    }

    .ring-progress {
        stroke-width: 3;
        stroke-linecap: round;
        transition: stroke-dasharray 0.8s ease;
    }

    .in-stock-ring {
        stroke: #10b981;
    }

    .out-stock-ring {
        stroke: #ef4444;
    }

    .stock-count {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        z-index: 1;
    }

    /* ===== IMAGE WRAPPER ===== */
    .product-image-wrapper {
        position: relative;
        padding: 24px 24px 0;
        background: linear-gradient(145deg, #fafbff 0%, #f0f3ff 100%);
        overflow: hidden;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: contain;
        display: block;
        transition: transform 0.6s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .product-card:hover .product-image {
        transform: scale(1.06) rotate(-2deg);
    }

    /* ===== DISCOUNT BADGE ===== */
    .discount-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 4px 12px;
        border-radius: 100px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        z-index: 2;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    /* ===== PRODUCT INFO ===== */
    .product-info {
        padding: 18px 20px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .product-category {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #818cf8;
        background: #eef2ff;
        padding: 2px 12px;
        border-radius: 100px;
        display: inline-block;
        align-self: flex-start;
    }

    .product-name {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 42px;
    }

    /* ===== PRICE ===== */
    .price-section {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        min-height: 32px;
    }

    .original-price {
        font-size: 13px;
        color: #94a3b8;
        text-decoration: line-through;
        font-weight: 600;
    }

    .product-price {
        font-size: 22px;
        font-weight: 800;
        color: #4f46e5;
        letter-spacing: -0.5px;
    }

    .product-price span {
        font-size: 12px;
        font-weight: 600;
        color: #818cf8;
        margin-left: 2px;
    }

    /* ===== VARIANT SELECT ===== */
    .variant-select {
        width: 100%;
        height: 38px;
        padding: 0 14px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        font-size: 12px;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s;
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%234f46e5' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
    }

    .variant-select:focus {
        outline: none;
        border-color: #4f46e5;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    .variant-select option {
        padding: 8px;
        font-weight: 500;
    }

    /* ===== ACTIONS ===== */
    .product-actions {
        display: grid;
        grid-template-columns: 1fr 50px;
        gap: 10px;
        margin-top: 4px;
    }

    /* ===== QUANTITY CONTROL ===== */
    .qty-control {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border-radius: 14px;
        border: 2px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.2s;
        min-width: 0;
    }

    .qty-control:focus-within {
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    .qty-btn {
        width: 34px;
        height: 38px;
        border: none;
        background: transparent;
        color: #475569;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
        padding: 0;
    }

    .qty-btn:hover:not(:disabled) {
        background: rgba(79, 70, 229, 0.08);
        color: #4f46e5;
    }

    .qty-btn:active:not(:disabled) {
        transform: scale(0.9);
    }

    .qty-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    .qty-input {
        width: 100%;
        height: 38px;
        border: none;
        background: transparent;
        text-align: center;
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        padding: 0 4px;
        min-width: 30px;
        -moz-appearance: textfield;
    }

    .qty-input::-webkit-inner-spin-button,
    .qty-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .qty-input:focus {
        outline: none;
    }

    .qty-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* ===== ADD TO CART BUTTON ===== */
    .btn-add {
        position: relative;
        height: 42px;
        border: none;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #4f46e5, #4338ca);
        color: #ffffff;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 20px rgba(79, 70, 229, 0.25);
        cursor: pointer;
        overflow: hidden;
        min-width: 50px;
    }

    .btn-add::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 30%, rgba(255, 255, 255, 0.15) 100%);
        pointer-events: none;
    }

    .btn-add:hover:not(:disabled) {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 35px rgba(79, 70, 229, 0.35);
    }

    .btn-add:active:not(:disabled) {
        transform: scale(0.95);
    }

    .btn-add:disabled {
        background: #cbd5e1;
        box-shadow: none;
        cursor: not-allowed;
        transform: none;
    }

    .btn-add svg {
        stroke: currentColor;
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease;
    }

    .btn-add:hover:not(:disabled) svg {
        transform: translateY(-2px);
    }

    /* Tooltip */
    .btn-tooltip {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%) scale(0.8);
        background: #0f172a;
        color: #fff;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 600;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .btn-add:hover .btn-tooltip {
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }

    /* Loading State */
    .btn-add.loading svg {
        opacity: 0;
    }

    .btn-add.loading .btn-loader {
        display: block;
    }

    .btn-loader {
        display: none;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top: 2px solid #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        position: relative;
        z-index: 1;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Success State */
    .btn-add.success {
        background: linear-gradient(135deg, #10b981, #059669);
        box-shadow: 0 4px 20px rgba(16, 185, 129, 0.3);
    }

    /* ===== STOCK STATUS BAR ===== */
    .stock-status {
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .stock-bar-bg {
        flex: 1;
        height: 4px;
        background: #e2e8f0;
        border-radius: 100px;
        overflow: hidden;
        min-width: 40px;
    }

    .stock-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981, #34d399);
        border-radius: 100px;
        transition: width 0.8s ease;
    }

    .stock-text {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .text-success {
        color: #059669;
    }

    .text-danger {
        color: #ef4444;
    }

    /* ===== TOAST ===== */
    .toast-notification {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 16px 24px;
        border-radius: 16px;
        background: #0f172a;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        transform: translateY(100px) scale(0.9);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        z-index: 9999;
        max-width: 400px;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .toast-notification.toast-show {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    .toast-notification.toast-success {
        background: linear-gradient(135deg, #065f46, #047857);
        border-color: rgba(16, 185, 129, 0.3);
    }

    .toast-notification.toast-error {
        background: linear-gradient(135deg, #991b1b, #dc2626);
        border-color: rgba(239, 68, 68, 0.3);
    }

    .toast-content {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toast-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 576px) {
        .product-image-wrapper {
            min-height: 150px;
            padding: 16px 16px 0;
        }

        .product-image {
            height: 150px;
        }

        .product-price {
            font-size: 19px;
        }

        .product-name {
            font-size: 14px;
            min-height: 38px;
        }

        .qty-btn {
            width: 30px;
            height: 34px;
        }

        .qty-input {
            height: 34px;
            font-size: 13px;
        }

        .btn-add {
            height: 38px;
        }

        .stock-ring {
            width: 38px;
            height: 38px;
            top: 12px;
            right: 12px;
        }

        .stock-ring .ring-svg {
            width: 38px;
            height: 38px;
        }

        .stock-count {
            font-size: 10px;
        }

        .product-actions {
            grid-template-columns: 1fr 44px;
            gap: 8px;
        }

        .toast-notification {
            bottom: 20px;
            right: 20px;
            left: 20px;
            max-width: none;
            padding: 14px 18px;
            font-size: 13px;
        }
    }

    @media (max-width: 400px) {
        .product-info {
            padding: 14px 14px 16px;
        }

        .product-price {
            font-size: 17px;
        }

        .variant-select {
            font-size: 11px;
            height: 34px;
        }
    }
</style>

<script>
    // ===== UPDATE VARIANT INFORMATION =====
    function updateVariantInfo(productId) {
        const select = document.getElementById('variant_select_' + productId);
        if (!select) return;

        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;

        // Get data from selected option
        const variantId = selectedOption.value;
        const stock = parseInt(selectedOption.dataset.stock) || 0;
        const price = parseFloat(selectedOption.dataset.price) || 0;
        const discount = parseFloat(selectedOption.dataset.discount) || 0;

        // Calculate original price if discount exists
        const originalPrice = discount > 0 ? price + (price * discount / 100) : price;

        // Update price section
        const priceElement = document.getElementById('product_price_' + productId);
        const originalPriceElement = document.getElementById('original_price_' + productId);

        if (priceElement) {
            priceElement.innerHTML = numberFormat(price) + ' <span>{{ get_setting('currency') }}</span>';
        }

        if (originalPriceElement) {
            if (discount > 0) {
                originalPriceElement.style.display = 'inline';
                originalPriceElement.textContent = numberFormat(originalPrice) + {{ get_setting('currency') }};
            } else {
                originalPriceElement.style.display = 'none';
            }
        }

        // Update stock ring
        const card = select.closest('.product-card');
        if (!card) return;

        const ringProgress = card.querySelector('.ring-progress');
        const stockCount = card.querySelector('.stock-count');
        const stockBarFill = card.querySelector('.stock-bar-fill');
        const stockText = card.querySelector('.stock-text');
        const qtyInput = document.getElementById('qty_' + productId);
        const minusBtn = card.querySelector('.qty-minus');
        const plusBtn = card.querySelector('.qty-plus');
        const addBtn = card.querySelector('.btn-add');

        // Calculate percentage for stock indicators
        const percentage = stock > 0 ? Math.min((stock / 100) * 100, 100) : 0;

        // Update stock ring
        if (ringProgress) {
            ringProgress.setAttribute('stroke-dasharray', percentage + ' 100');
            ringProgress.className = 'ring-progress ' + (stock > 0 ? 'in-stock-ring' : 'out-stock-ring');
        }

        if (stockCount) {
            stockCount.textContent = stock > 0 ? stock : 0;
        }

        // Update stock bar
        if (stockBarFill) {
            stockBarFill.style.width = percentage + '%';
        }

        // Update stock text
        if (stockText) {
            stockText.textContent = stock > 0 ? stock + ' units available' : 'Out of Stock';
            stockText.className = 'stock-text ' + (stock > 0 ? 'text-success' : 'text-danger');
        }

        // Update quantity input max
        if (qtyInput) {
            qtyInput.max = stock > 0 ? stock : 999;
            qtyInput.disabled = stock <= 0;
            if (parseInt(qtyInput.value) > stock && stock > 0) {
                qtyInput.value = stock;
            } else if (stock <= 0) {
                qtyInput.value = 0;
            }
        }

        // Update buttons
        if (minusBtn) minusBtn.disabled = stock <= 0;
        if (plusBtn) plusBtn.disabled = stock <= 0;
        if (addBtn) {
            addBtn.disabled = stock <= 0;
            // Update variant ID for add to cart
            if (addBtn.classList.contains('add-to-cart-variant')) {
                addBtn.dataset.variantId = variantId;
            }
        }

        // Update card data-stock attribute
        card.dataset.stock = stock;
    }

    // ===== NUMBER FORMAT =====
    function numberFormat(number) {
        return new Intl.NumberFormat('en-US').format(Math.round(number));
    }

    // ===== UPDATE QUANTITY =====
    function updateQty(productId, change) {
        const input = document.getElementById('qty_' + productId);
        if (!input || input.disabled) return;

        let currentValue = parseInt(input.value) || 1;
        let newValue = currentValue + change;
        let max = parseInt(input.max) || 999;

        if (newValue < 1) newValue = 1;
        if (newValue > max) newValue = max;

        input.value = newValue;
        input.dispatchEvent(new Event('input'));
    }

    // ===== ADD TO CART WITH VARIANT =====
    document.addEventListener('DOMContentLoaded', function() {
        // Add to cart with variant
        document.querySelectorAll('.add-to-cart-variant').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.disabled) return;

                const productId = this.dataset.productId;
                const variantId = this.dataset.variantId;
                const qtyInput = document.getElementById('qty_' + productId);
                const quantity = parseInt(qtyInput.value) || 1;

                // Get variant price from select
                const select = document.getElementById('variant_select_' + productId);
                const selectedOption = select?.options[select.selectedIndex];
                const stock = selectedOption ? parseInt(selectedOption.dataset.stock) : 0;

                // Validate stock
                if (quantity > stock) {
                    showToast('Not enough stock available!', 'error');
                    return;
                }

                // Add loading animation
                this.classList.add('loading');

                // AJAX call to add to cart
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('variant_id', variantId);
                formData.append('quantity', quantity);

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    this.classList.remove('loading');
                    showToast('CSRF token not found', 'error');
                    return;
                }

                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.remove('loading');
                            this.classList.add('success');
                            showToast('Added to cart successfully!', 'success');

                            // Update cart count if function exists
                            if (typeof updateCartCount === 'function') {
                                updateCartCount(data.cart_count);
                            }

                            setTimeout(() => {
                                this.classList.remove('success');
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to add to cart');
                        }
                    })
                    .catch(error => {
                        this.classList.remove('loading');
                        showToast(error.message, 'error');
                        console.error('Error:', error);
                    });
            });
        });

        // Add to cart simple (no variant)
        document.querySelectorAll('.add-to-cart-simple').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.disabled) return;

                const productId = this.dataset.productId;
                const qtyInput = document.getElementById('qty_' + productId);
                const quantity = parseInt(qtyInput.value) || 1;
                const stock = parseInt(this.dataset.stock) || 0;

                // Validate stock
                if (quantity > stock) {
                    showToast('Not enough stock available!', 'error');
                    return;
                }

                this.classList.add('loading');

                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('quantity', quantity);

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    this.classList.remove('loading');
                    showToast('CSRF token not found', 'error');
                    return;
                }

                fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.remove('loading');
                            this.classList.add('success');
                            showToast('Added to cart successfully!', 'success');

                            if (typeof updateCartCount === 'function') {
                                updateCartCount(data.cart_count);
                            }

                            setTimeout(() => {
                                this.classList.remove('success');
                            }, 1000);
                        } else {
                            throw new Error(data.message || 'Failed to add to cart');
                        }
                    })
                    .catch(error => {
                        this.classList.remove('loading');
                        showToast(error.message, 'error');
                        console.error('Error:', error);
                    });
            });
        });
    });

    // ===== TOAST NOTIFICATION =====
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }

        const toast = document.createElement('div');
        toast.className = 'toast-notification toast-' + type;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${type === 'success' ? '✓' : '✕'}</span>
                <span class="toast-message">${message}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => {
            toast.classList.add('toast-show');
        }, 10);

        // Auto hide
        setTimeout(() => {
            toast.classList.remove('toast-show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // ===== UPDATE CART COUNT =====
    function updateCartCount(count) {
        const cartCounter = document.querySelector('.cart-counter');
        if (cartCounter) {
            cartCounter.textContent = count;
            cartCounter.classList.add('cart-bump');
            setTimeout(() => {
                cartCounter.classList.remove('cart-bump');
            }, 300);
        }
    }
</script>
