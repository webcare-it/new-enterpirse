<style>
    /* Modern Professional Sidebar */
    .aiz-sidebar {
        background: #ffffff !important;
        border-right: 1px solid #eef2f6;
        box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.01), 8px 0 24px rgba(0, 0, 0, 0.03);
        transition: all 0.2s ease;
    }

    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .aiz-sidebar {
            background: #2d2d2d !important;
            border-right-color: #1f2937;
        }
    }

    /* Logo Area */
    .aiz-side-nav-logo-wrap {
        padding: 24px 24px;
        border-bottom: 1px solid #eef2f6;
        background: transparent;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-logo-wrap {
            border-bottom-color: #1f2937;
        }
    }

    .aiz-side-nav-logo-wrap img {
        max-height: 36px;
        object-fit: contain;
    }

    /* Search Input */
    #menu-search {
        height: 40px;
        border-radius: 8px !important;
        background: #f9fafb !important;
        border: 1px solid #e5e7eb !important;
        color: #111827 !important;
        padding-left: 36px;
        font-size: 13px;
        transition: all 0.15s ease;
    }

    @media (prefers-color-scheme: dark) {
        #menu-search {
            background: #1f2937 !important;
            border-color: #374151 !important;
            color: #f9fafb !important;
        }
    }

    #menu-search:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
        background: #ffffff !important;
    }

    @media (prefers-color-scheme: dark) {
        #menu-search:focus {
            background: #1f2937 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }
    }

    #menu-search::placeholder {
        color: #9ca3af;
        font-size: 12px;
    }

    /* Search Icon */
    .px-20px {
        position: relative;
    }

    .px-20px::before {
        content: '\f002';
        font-family: 'Line Awesome Free';
        font-weight: 900;
        position: absolute;
        left: 32px;
        top: 12px;
        font-size: 13px;
        color: #9ca3af;
        pointer-events: none;
        z-index: 1;
    }

    /* Navigation Items */
    .aiz-side-nav-list {
        padding: 0 12px;
    }

    .aiz-side-nav-item {
        margin-bottom: 2px;
    }

    .aiz-side-nav-link {
        min-height: 40px;
        border-radius: 8px;
        color: #374151 !important;
        font-weight: 500;
        font-size: 13px;
        transition: all 0.15s ease;
        padding: 0 12px;
        border-left: 3px solid transparent;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-link {
            color: #d1d5db !important;
        }
    }

    .aiz-side-nav-link:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-link:hover {
            background: #1f2937 !important;
            color: #ffffff !important;
        }
    }

    /* Active State - ONLY BORDER, NO BACKGROUND CHANGE */
    .aiz-side-nav-link.active,
    .aiz-side-nav-item.mm-active>.aiz-side-nav-link,
    .aiz-side-nav-link[aria-expanded="true"] {
        background: transparent !important;
        border: 5px solid #3b82f6 !important;
        color: #111827 !important;
    }

    @media (prefers-color-scheme: dark) {

        .aiz-side-nav-link.active,
        .aiz-side-nav-item.mm-active>.aiz-side-nav-link,
        .aiz-side-nav-link[aria-expanded="true"] {
            background: transparent !important;
            border: 1px solid #60a5fa !important;
            color: #ffffff !important;
        }
    }

    /* Icons */
    .aiz-side-nav-icon {
        width: 28px;
        height: 28px;
        min-width: 28px;
        border-radius: 6px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 16px !important;
        color: #6b7280 !important;
        transition: all 0.15s ease;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-icon {
            color: #9ca3af !important;
        }
    }

    .aiz-side-nav-link:hover .aiz-side-nav-icon {
        color: #3b82f6 !important;
    }

    .aiz-side-nav-link.active .aiz-side-nav-icon {
        color: #3b82f6 !important;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-link.active .aiz-side-nav-icon {
            color: #60a5fa !important;
        }
    }

    /* Text */
    .aiz-side-nav-text {
        font-size: 13px;
        font-weight: 500;
        letter-spacing: normal;
    }

    /* Submenu - Level 2 */
    .level-2 {
        margin: 4px 0 8px 24px;
        padding-left: 16px !important;
        border-left: 1px solid #e5e7eb;
    }

    @media (prefers-color-scheme: dark) {
        .level-2 {
            border-left-color: #374151;
        }
    }

    .level-2 .aiz-side-nav-link {
        min-height: 36px;
        padding: 0 12px;
        font-size: 12.5px;
        font-weight: 400;
        color: #6b7280 !important;
        border-left: 2px solid transparent !important;
    }

    @media (prefers-color-scheme: dark) {
        .level-2 .aiz-side-nav-link {
            color: #9ca3af !important;
        }
    }

    .level-2 .aiz-side-nav-link:hover {
        background: #f9fafb !important;
        color: #111827 !important;
    }

    @media (prefers-color-scheme: dark) {
        .level-2 .aiz-side-nav-link:hover {
            background: #1f2937 !important;
            color: #ffffff !important;
        }
    }

    /* Active state for submenu - ONLY BORDER */
    .level-2 .aiz-side-nav-link.active {
        background: transparent !important;
        border-left: 2px solid #3b82f6 !important;
        color: #111827 !important;
    }

    @media (prefers-color-scheme: dark) {
        .level-2 .aiz-side-nav-link.active {
            background: transparent !important;
            border-left: 2px solid #60a5fa !important;
            color: #ffffff !important;
        }
    }

    .level-2 .aiz-side-nav-icon {
        width: 24px;
        height: 24px;
        min-width: 24px;
        font-size: 13px !important;
        margin-right: 10px;
    }

    /* Arrow Indicator */
    .aiz-side-nav-arrow {
        opacity: 0.6;
        font-size: 11px;
        transition: transform 0.15s ease;
        margin-left: auto;
    }

    .aiz-side-nav-link[aria-expanded="true"] .aiz-side-nav-arrow {
        transform: rotate(90deg);
    }

    /* Scrollbar - Clean */
    .c-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .c-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .c-scrollbar::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 4px;
    }

    .c-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #9ca3af;
    }

    @media (prefers-color-scheme: dark) {
        .c-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563;
        }

        .c-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    }

    /* Collapsible Animation */
    .level-2 {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
    }

    .aiz-side-nav-item.mm-active .level-2,
    .aiz-side-nav-link[aria-expanded="true"]+.level-2 {
        max-height: 500px;
        transition: max-height 0.3s ease-in;
    }

    /* Divider */
    .aiz-side-nav-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 16px 0;
    }

    @media (prefers-color-scheme: dark) {
        .aiz-side-nav-divider {
            background: #374151;
        }
    }

    /* Badge Support */
    .aiz-side-nav-badge {
        background: #ef4444;
        color: white;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        margin-left: 8px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .aiz-sidebar {
            transform: translateX(-100%);
            transition: transform 0.2s ease;
        }

        .aiz-sidebar.mobile-open {
            transform: translateX(0);
        }
    }
</style>

<div class="aiz-sidebar-wrap">
    <div class="aiz-sidebar left c-scrollbar">
        <div class="aiz-side-nav-logo-wrap">
            <a href="{{ route('admin.dashboard') }}" class="d-block text-left">
                @if (get_setting('system_logo_white') != null)
                    <img class="mw-100" src="{{ uploaded_asset(get_setting('system_logo_white')) }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @else
                    <img class="mw-100" src="{{ static_asset('assets/img/logo.png') }}" class="brand-icon"
                        alt="{{ get_setting('site_name') }}">
                @endif
            </a>
        </div>

        <div class="aiz-side-nav-wrap">
            <div class="px-20px mb-3">
                <input class="form-control bg-soft-secondary border-0 form-control-sm text-white" type="text"
                    placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>

            <ul class="aiz-side-nav-list" id="search-menu"></ul>

            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">
                <li class="aiz-side-nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link">
                        <i class="las la-home aiz-side-nav-icon"></i>
                        <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                    </a>
                </li>

                @if (Auth::check() &&
                        Auth::user() &&
                        (Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions))))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.create']) }}">
                            <i class="las la-folder-open aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('File Manager') }}</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('products.*', 'categories.*', 'brands.*', 'attributes.*', 'colors.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['products.*', 'categories.*', 'brands.*', 'attributes.*', 'colors.*']) }}">
                            <i class="las la-box aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('products.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('products.create') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.create']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Add New Product') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('products.import.form') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['products.import.form']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Import Products') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('brand.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['brand.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Brands') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('dropshipping-category.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['dropshipping-category.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('subcategory.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['subcategory.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Subcategories') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('colors.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['colors.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Colors') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('attributes.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['attributes.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Attributes') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('orders.*', 'manual_orders.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['orders.*', 'manual_orders.*']) }}">
                            <i class="las la-shopping-cart aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Orders') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Orders') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.all.delivered.orders') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.all.delivered.orders']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Delivered') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.all.shipped.orders') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.all.shipped.orders']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Shipped') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.all.canceled.orders') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.all.canceled.orders']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Cancelled') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('incomplete-orders.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['incomplete-orders.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Incomplete Orders') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.all.manual.orders') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.all.manual.orders']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Manual Orders') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('manual_orders.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['manual_orders.index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Create Order') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('orders.import.form') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['orders.import.form']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Import Orders') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::check() &&
                        Auth::user() &&
                        (Auth::user()->user_type == 'admin' || in_array('22', json_decode(Auth::user()->staff->role->permissions))))
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('fraud_checker') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['fraud_checker']) }}">
                            <i class="las la-user-shield aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Fraud Checker') }}</span>
                        </a>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('customers.*', 'wishlists.*', 'carts.*', 'reviews.*', 'searches.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['customers.*', 'wishlists.*', 'carts.*', 'reviews.*', 'searches.*']) }}">
                            <i class="las la-users aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('customers.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['customers.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Customers') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reviews.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reviews.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Reviews') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('wishlists.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['wishlists.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Wishlists') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('carts.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['carts.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Carts') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('searches.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['searches.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Search History') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('blog.*', 'slider.*', 'faq.*', 'about.*', 'contact-message.*', 'newsletter.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['blog.*', 'slider.*', 'faq.*', 'about.*', 'contact-message.*', 'newsletter.*']) }}">
                            <i class="las la-file-alt aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Content') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('blog.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['blog.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Blog Posts') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('slider.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['slider.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Sliders') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('faq.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['faq.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('FAQs') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('about.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['about.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('About Us') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('contact-message.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['contact-message.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Contact Messages') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('newsletter.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['newsletter.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Newsletter Subscribers') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('campaigns.*', 'landingpages.*', 'coupon.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['campaigns.*', 'landingpages.*', 'coupon.*']) }}">
                            <i class="las la-bullhorn aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Marketing') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('campaigns.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['campaigns.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Campaigns') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('landingpages.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['landingpages.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Landing Pages') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('coupon.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['coupon.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Coupons') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('paymentsystem.*', 'shipping_costs.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['paymentsystem.*', 'shipping_costs.*']) }}">
                            <i class="las la-credit-card aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Payments & Shipping') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('paymentsystem.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['paymentsystem.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Payment Methods') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('shipping_costs.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['shipping_costs.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Shipping Options') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item {{ request()->routeIs('reports.*') ? 'mm-active' : '' }}">
                        <a href="#" class="aiz-side-nav-link {{ areActiveRoutes(['reports.*']) }}">
                            <i class="las la-chart-bar aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.sales') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.sales']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Sales Report') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.product-stocks') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.product-stocks']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Product Stocks') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.products') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.products']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Product Reports') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.orders') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.orders']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Order Reports') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.customers') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.customers']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Customer Reports') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.product-wishlist') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.product-wishlist']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Wishlist Reports') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('reports.user-searches') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['reports.user-searches']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Search Reports') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('13', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item {{ request()->routeIs('website.*') ? 'mm-active' : '' }}">
                        <a href="#" class="aiz-side-nav-link {{ areActiveRoutes(['website.*']) }}">
                            <i class="las la-desktop aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Website') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.header') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.header']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Header') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.footer', ['lang' => App::getLocale()]) }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.footer']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Footer') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.appearance') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.appearance']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Appearance') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('website.pages') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create', 'custom-pages.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Custom Pages') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('14', json_decode(Auth::user()->staff->role->permissions)))
                    <li
                        class="aiz-side-nav-item {{ request()->routeIs('general_setting.*', 'activation.*', 'social_login.*', 'google_analytics.*') ? 'mm-active' : '' }}">
                        <a href="#"
                            class="aiz-side-nav-link {{ areActiveRoutes(['general_setting.*', 'activation.*', 'social_login.*', 'google_analytics.*']) }}">
                            <i class="las la-cog aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Settings') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('general_setting.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['general_setting.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('General Settings') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('activation.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['activation.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Feature Activation') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('credentials.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['credentials.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Credentials') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('social_login.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['social_login.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Social Login') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('google_analytics.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['google_analytics.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Google Analytics') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if (Auth::user()->user_type == 'admin' || in_array('20', json_decode(Auth::user()->staff->role->permissions)))
                    <li class="aiz-side-nav-item {{ request()->routeIs('staffs.*', 'roles.*') ? 'mm-active' : '' }}">
                        <a href="#" class="aiz-side-nav-link {{ areActiveRoutes(['staffs.*', 'roles.*']) }}">
                            <i class="las la-user-tie aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Staff Management') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('staffs.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['staffs.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('All Staff') }}</span>
                                </a>
                            </li>
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('roles.index') }}"
                                    class="aiz-side-nav-link {{ areActiveRoutes(['roles.*']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Roles & Permissions') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="aiz-side-nav-item">
                        <a href="{{ route('admin.visitor_log') }}"
                            class="aiz-side-nav-link {{ areActiveRoutes(['admin.visitor_log']) }}">
                            <i class="las la-eye aiz-side-nav-icon"></i>
                            <span class="aiz-side-nav-text">{{ translate('Visitor Logs') }}</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="aiz-sidebar-overlay"></div>
</div>
