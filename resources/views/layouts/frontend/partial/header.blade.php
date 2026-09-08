<div class="header-top d-lg-block d-none">
    <div class="container">
        <div class="row g-2 align-items-center">
            <div class="col-lg-7">
                <div class="header-support">
                    <ul>
                        <li><i class="fal fa-envelope-open-text text-primary me-1"></i> Email:
                            {{ @$setting->primary_email }}
                        </li>
                        <li><i class="fal fa-phone-alt text-primary me-1"></i> Hotline: {{ @$setting->primary_mobile }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="topbar-links">
                        <ul>
                            <li><a href="{{ Route('frontend.contact') }}">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="social-header">
                        <ul>
                            @if (@$setting->facebook_page)
                                <li class="facebook">
                                    <a title="Facebook" href="{{ @$setting->facebook_page }}" target="_blank">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                </li>
                            @endif
                            @if (@$setting->youtube)
                                <li class="youtube">
                                    <a title="Youtube" href="{{ @$setting->youtube }}" target="_blank">
                                        <i class="fab fa-youtube"></i>
                                    </a>
                                </li>
                            @endif
                            @if (@$setting->twitter)
                                <li class="twitter">
                                    <a title="Twitter" href="{{ @$setting->twitter }}" target="_blank">
                                        <i class="fab fa-twitter"></i>
                                    </a>

                                </li>
                            @endif
                            @if (@$setting->instagram)
                                <li class="instagram">
                                    <a title="Instagram" href="{{ @$setting->instagram }}" target="_blank">
                                        <i class="fab fa-instagram"></i>
                                    </a>
                                </li>
                            @endif
                            @if (@$setting->linkedin)
                                <li class="linkedin">
                                    <a title="Linkedin" href="{{ @$setting->linkedin }}" target="_blank">
                                        <i class="fab fa-linkedin-in"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-middle">
    <div class="container">
        <div class="middle-content">
            <div class="d-lg-none mobile-menu__container">
                <button type="button" class="btn menu-toggle-btn menu-toggler">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 640 640">
                        <g id="icomoon-ignore">
                        </g>
                        <path fill="currentColor"
                            d="M0 96h640v64h-640v-64zM0 224h640v64h-640v-64zM0 352h640v64h-640v-64zM0 480h640v64h-640v-64z">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="logo-container">
                <a class="logo" href="{{ Route('frontend.home') }}" title="Sales Tracker">
                    <img src="{{ asset(file_exists(@$setting->logo) ? @$setting->logo : 'frontend/assets/images/logo/logo.png') }}"
                        alt="Sales Tracker" height="60">
                </a>
            </div>
            <div class="search-container">
                <div class="block-search">
                    <div class="block-content position-relative">
                        <form class="form minisearch" id="search_mini_form" action="{{ Route('frontend.search') }}"
                            method="GET">
                            <div class="input-group">
                                <input id="search" type="text" name="search"
                                    placeholder="Search entire store here..." class="input-text form-control"
                                    maxlength="128" autocomplete="off" value="{{ request('search') }}">
                                <button type="submit" title="Search" class="btn btn-primary" aria-label="Search">
                                    <span>Search</span>
                                </button>
                            </div>
                        </form>
                        <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                            style="min-height: 200px">
                            <div class="search-preloader absolute-top-center">
                                <div class="dot-loader">
                                    <div></div>
                                    <div></div>
                                    <div></div>
                                </div>
                            </div>
                            <div class="search-nothing d-none p-3 text-center fs-16">
                            </div>
                            <div id="search-content" class="text-left">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="action-container">
                <div class="action-content">
                    <div class="customer-action d-lg-flex d-none">
                        <div class="action-link">
                            <div class="action__icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="24"
                                    viewBox="0 0 768 768">
                                    <g id="icomoon-ignore">
                                    </g>
                                    <path fill="currentColor"
                                        d="M544 672v-64c0-44.16-17.952-84.224-46.848-113.152s-68.992-46.848-113.152-46.848h-224c-44.16 0-84.224 17.952-113.152 46.848s-46.848 68.992-46.848 113.152v64c0 17.664 14.336 32 32 32s32-14.336 32-32v-64c0-26.528 10.72-50.464 28.128-67.872s41.344-28.128 67.872-28.128h224c26.528 0 50.464 10.72 67.872 28.128s28.128 41.344 28.128 67.872v64c0 17.664 14.336 32 32 32s32-14.336 32-32zM432 224c0-44.16-17.952-84.224-46.848-113.152s-68.992-46.848-113.152-46.848-84.224 17.952-113.152 46.848-46.848 68.992-46.848 113.152 17.952 84.224 46.848 113.152 68.992 46.848 113.152 46.848 84.224-17.952 113.152-46.848 46.848-68.992 46.848-113.152zM368 224c0 26.528-10.72 50.464-28.128 67.872s-41.344 28.128-67.872 28.128-50.464-10.72-67.872-28.128-28.128-41.344-28.128-67.872 10.72-50.464 28.128-67.872 41.344-28.128 67.872-28.128 50.464 10.72 67.872 28.128 28.128 41.344 28.128 67.872zM521.376 374.624l64 64c12.512 12.512 32.768 12.512 45.248 0l128-128c12.512-12.512 12.512-32.768 0-45.248s-32.768-12.512-45.248 0l-105.376 105.376-41.376-41.376c-12.512-12.512-32.768-12.512-45.248 0s-12.512 32.768 0 45.248z">
                                    </path>
                                </svg>
                            </div>
                            <div class="action__text">
                                @auth
                                    <div class="head-title">Hello {{ Auth::user()->name }}!</div>
                                    <ul class="header-links">
                                        <li class="link authorization-link">
                                            <a href="{{ Route('customer.orders') }}">Account</a>
                                        </li>
                                    </ul>
                                @else
                                    <div class="head-title">Hello Guest!</div>
                                    <ul class="header-links">
                                        <li class="link authorization-link">
                                            <a href="{{ Route('customer.login') }}">Login </a>
                                        </li>
                                        <li><a href="{{ Route('customer.register') }}">Register</a></li>
                                    </ul>
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="minicart-wrapper">
                        <a class="cart-toggler action-link" href="{{ Route('customer.cart') }}">
                            <div class="action__icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20"
                                    viewBox="0 0 512 448">
                                    <g id="icomoon-ignore">
                                    </g>
                                    <path fill="currentColor"
                                        d="M479.946 192.007c17.745 0 31.993 14.247 31.993 31.993s-14.247 31.993-31.993 31.993h-3.749l-28.743 165.46c-2.749 15.246-15.996 26.494-31.493 26.494h-319.923c-15.497 0-28.743-11.247-31.493-26.494l-28.743-165.46h-3.749c-17.745 0-31.993-14.247-31.993-31.993s14.247-31.993 31.993-31.993h447.892zM121.282 391.959c8.748-0.75 15.497-8.498 14.746-17.246l-7.998-103.975c-0.75-8.748-8.498-15.497-17.246-14.746s-15.497 8.498-14.746 17.246l7.998 103.975c0.75 8.248 7.748 14.746 15.996 14.746h1.249zM224.008 375.964v-103.975c0-8.748-7.248-15.996-15.996-15.996s-15.996 7.248-15.996 15.996v103.975c0 8.748 7.248 15.996 15.996 15.996s15.996-7.248 15.996-15.996zM319.985 375.964v-103.975c0-8.748-7.248-15.996-15.996-15.996s-15.996 7.248-15.996 15.996v103.975c0 8.748 7.248 15.996 15.996 15.996s15.996-7.248 15.996-15.996zM407.964 377.213l7.998-103.975c0.75-8.748-5.999-16.496-14.746-17.246s-16.496 5.999-17.246 14.746l-7.998 103.975c-0.75 8.748 5.999 16.496 14.746 17.246h1.25c8.248 0 15.246-6.498 15.996-14.746zM119.033 73.036l-23.244 102.976h-32.992l25.244-110.224c6.498-29.242 32.242-49.738 62.235-49.738h41.74c0-8.748 7.248-15.996 15.996-15.996h95.977c8.748 0 15.996 7.248 15.996 15.996h41.74c29.993 0 55.737 20.495 62.235 49.738l25.244 110.224h-32.992l-23.244-102.976c-3.499-14.746-16.246-24.994-31.243-24.994h-41.74c0 8.748-7.248 15.996-15.996 15.996h-95.977c-8.748 0-15.996-7.248-15.996-15.996h-41.74c-14.997 0-27.744 10.248-31.243 24.994z">
                                    </path>
                                </svg>
                            </div>
                            <div class="action__text">
                                <span class="counter qty empty">
                                    <span
                                        class="counter-number cart_count">{{ is_array($cart) ? count($cart) : 0 }}</span>
                                </span>
                                <span class="text d-lg-inline-block d-none">My Cart</span>
                            </div>
                        </a>
                        <div class="st-minicart" id="st-minicart">
                            <div class="st-minicart-inner">
                                <div class="block-minicart">
                                    <div class="minicart-header">
                                        <div>
                                            <strong class="text text-dark">
                                                <span>Your cart</span>
                                            </strong>
                                            <span class="items-total ms-2">
                                                <span
                                                    class="count cart_count">{{ is_array($cart) ? count($cart) : 0 }}</span>
                                                <span>Items</span>
                                            </span>
                                        </div>
                                        <a class="action closecart text-primary d-lg-none d-inline-flex cart-toggler"
                                            href="javascript:void(0)" title="close cart">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                                viewBox="0 0 1024 1024">
                                                <g id="icomoon-ignore">
                                                </g>
                                                <path fill="currentColor"
                                                    d="M655.539 512.042l243.942 243.942c15.847 15.847 15.847 41.578 0 57.425l-86.077 86.077c-15.847 15.847-41.536 15.847-57.425 0l-243.979-243.942-243.979 243.942c-15.847 15.847-41.536 15.847-57.383 0l-86.114-86.077c-15.847-15.847-15.847-41.536 0-57.425l243.979-243.942-243.979-243.979c-15.847-15.847-15.847-41.578 0-57.425l86.156-86.077c15.847-15.847 41.536-15.847 57.383 0l243.942 243.979 243.979-243.979c15.847-15.847 41.536-15.847 57.425 0l86.077 86.114c15.847 15.847 15.847 41.536 0 57.425l-243.942 243.942z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a class="action viewcart text-primary d-lg-block d-none"
                                            href="{{ Route('customer.cart') }}" title="View and edit cart">
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                                viewBox="0 0 448 448">
                                                <g id="icomoon-ignore">
                                                </g>
                                                <path fill="currentColor"
                                                    d="M352 232v80c0 39.75-32.25 72-72 72h-208c-39.75 0-72-32.25-72-72v-208c0-39.75 32.25-72 72-72h176c4.5 0 8 3.5 8 8v16c0 4.5-3.5 8-8 8h-176c-22 0-40 18-40 40v208c0 22 18 40 40 40h208c22 0 40-18 40-40v-80c0-4.5 3.5-8 8-8h16c4.5 0 8 3.5 8 8zM448 16v128c0 8.75-7.25 16-16 16-4.25 0-8.25-1.75-11.25-4.75l-44-44-163 163c-1.5 1.5-3.75 2.5-5.75 2.5s-4.25-1-5.75-2.5l-28.5-28.5c-1.5-1.5-2.5-3.75-2.5-5.75s1-4.25 2.5-5.75l163-163-44-44c-3-3-4.75-7-4.75-11.25 0-8.75 7.25-16 16-16h128c8.75 0 16 7.25 16 16z">
                                                </path>
                                            </svg>
                                        </a>
                                    </div>

                                    <div class="minicart-content mCustomScrollbar">
                                        <div class="minicart-items-wrapper">
                                            <ul class="minicart-items">
                                                @php
                                                    $cart_total_price = 0;
                                                @endphp
                                                @if (is_array($cart))
                                                    @foreach ($cart as $key => $item)
                                                        @php
                                                            $cart_total_price +=
                                                                ($item['price'] - $item['discount_tk']) * $item['qty'];
                                                        @endphp
                                                        <li class="minicart-item"
                                                            id="cart-item-{{ $item['product_id'] . '-v-' . $item['variant_id'] }}">
                                                            <div class="minicart-item-inner">
                                                                <div class="minicart-item-left">
                                                                    <a class="minicart-item-photo"
                                                                        href="{{ Route('frontend.single-product', $item['slug']) }}"
                                                                        title="{{ $item['name'] }}">
                                                                        <div class="ratio ratio-1x1"
                                                                            style="width: 75px;">
                                                                            <img class="fit-cover"
                                                                                src="{{ asset(file_exists($item['thumbnail']) ? $item['thumbnail'] : @$setting->placeholder) }}"
                                                                                alt="{{ $item['name'] }}">
                                                                        </div>
                                                                    </a>
                                                                    <div class="cart-actions">
                                                                        <a href="javascript:void(0)"
                                                                            class="action delete" title="Remove item"
                                                                            data-id="{{ $item['product_id'] }}"
                                                                            data-variant="{{ $item['variant_id'] }}">
                                                                            <i class="far fa-times"></i>
                                                                        </a>
                                                                        <a class="action edit"
                                                                            href="{{ Route('frontend.single-product', $item['slug']) }}"
                                                                            title="Edit item">
                                                                            <i class="fas fa-pencil-alt"
                                                                                style="font-size: 80%;"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="minicart-item-details">
                                                                    <div class="product-name">
                                                                        <a
                                                                            href="{{ Route('frontend.single-product', $item['slug']) }}">{{ $item['name'] }}</a>
                                                                    </div>
                                                                    <div>
                                                                        @foreach ($item['choose_options'] as $key => $option)
                                                                            {{ $key }} : {{ $option }},
                                                                            &nbsp;
                                                                        @endforeach
                                                                    </div>
                                                                    @if ($item['discount_tk'] > 0)
                                                                        <div class="price-box"><span
                                                                                class="special-price">
                                                                                <span class="price">TK
                                                                                    {{ number_format($item['price'] - $item['discount_tk'], 2, '.') }}</span>
                                                                            </span>
                                                                            <span class="old-price">
                                                                                <span class="price">TK
                                                                                    {{ number_format($item['price'], 2, '.') }}</span>
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div class="price-box"><span
                                                                                class="special-price">
                                                                                <span class="price">TK
                                                                                    {{ number_format($item['price'], 2, '.') }}</span>
                                                                            </span></div>
                                                                    @endif
                                                                </div>
                                                                <div class="minicart-item-actions">
                                                                    <div class="details-qty qty">
                                                                        <label class="label"
                                                                            for="cart-item-{{ $item['product_id'] . $item['variant_id'] }}-qty">Qty</label>
                                                                        <div>
                                                                            <div class="cart-quantity">
                                                                                <div class="wg-quantity">
                                                                                    <span
                                                                                        class="btn-quantity minus-btn"
                                                                                        data-id="{{ $item['product_id'] }}"
                                                                                        data-variant="{{ $item['variant_id'] }}">
                                                                                        <svg class="d-inline-block"
                                                                                            width="9"
                                                                                            height="1"
                                                                                            viewBox="0 0 9 1"
                                                                                            fill="currentColor">
                                                                                            <path
                                                                                                d="M9 1H5.14286H3.85714H0V1.50201e-05H3.85714L5.14286 0L9 1.50201e-05V1Z">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </span>
                                                                                    <input type="text"
                                                                                        class="item-qty cart-item-qty"
                                                                                        name="number"
                                                                                        id="header-{{ $key }}"
                                                                                        value="{{ $item['qty'] }}">
                                                                                    <span class="btn-quantity plus-btn"
                                                                                        data-id="{{ $item['product_id'] }}"
                                                                                        data-variant="{{ $item['variant_id'] }}">
                                                                                        <svg class="d-inline-block"
                                                                                            width="9"
                                                                                            height="9"
                                                                                            viewBox="0 0 9 9"
                                                                                            fill="currentColor">
                                                                                            <path
                                                                                                d="M9 5.14286H5.14286V9H3.85714V5.14286H0V3.85714H3.85714V0H5.14286V3.85714H9V5.14286Z">
                                                                                            </path>
                                                                                        </svg>
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="minicart-total">
                                        <div class="subtotal">
                                            <span class="label">
                                                <span>Cart Subtotal</span>
                                            </span>
                                            <span class="price">Tk. <span
                                                    class="total_cart_price">{{ number_format($cart_total_price, 2) }}</span></span>
                                        </div>
                                        <div class="actions mt-2">
                                            <a class="action btn btn-sm rounded-pill btn-primary px-3"
                                                href="{{ Route('customer.cart') }}" title="Go to Cart">
                                                <span class="me-1">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                        height="16" viewBox="0 0 448 448">
                                                        <g id="icomoon-ignore">
                                                        </g>
                                                        <path fill="currentColor"
                                                            d="M352 232v80c0 39.75-32.25 72-72 72h-208c-39.75 0-72-32.25-72-72v-208c0-39.75 32.25-72 72-72h176c4.5 0 8 3.5 8 8v16c0 4.5-3.5 8-8 8h-176c-22 0-40 18-40 40v208c0 22 18 40 40 40h208c22 0 40-18 40-40v-80c0-4.5 3.5-8 8-8h16c4.5 0 8 3.5 8 8zM448 16v128c0 8.75-7.25 16-16 16-4.25 0-8.25-1.75-11.25-4.75l-44-44-163 163c-1.5 1.5-3.75 2.5-5.75 2.5s-4.25-1-5.75-2.5l-28.5-28.5c-1.5-1.5-2.5-3.75-2.5-5.75s1-4.25 2.5-5.75l163-163-44-44c-3-3-4.75-7-4.75-11.25 0-8.75 7.25-16 16-16h128c8.75 0 16 7.25 16 16z">
                                                        </path>
                                                    </svg>
                                                </span>
                                                <span>Go to Cart</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container">
        <div class="header-bottom-wrapper">
            <div class="vertical-menu">
                <div class="menu-title">
                    <span class="text-uppercase">All Categories</span>
                    <span>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 640 640">
                            <g id="icomoon-ignore">
                            </g>
                            <path fill="currentColor"
                                d="M0 96h640v64h-640v-64zM0 224h640v64h-640v-64zM0 352h640v64h-640v-64zM0 480h640v64h-640v-64z">
                            </path>
                        </svg>
                    </span>
                </div>
                <div class="menu-container">
                    <div class="st-menu">
                        <ul class="groupmenu">
                            @if ($menus->where('position', 'sidebar')->first())
                                @foreach ($menus->where('position', 'sidebar')->first()->rootItems as $key => $item)
                                    @if ($item->category)
                                        <li class="st-menu__item {{ count($item->children) > 0 ? 'has-sub' : '' }}"
                                            style="display: {{ $key > 9 ? 'none' : '' }};">
                                            <a class="menu-link"
                                                href="{{ Route('frontend.products', @$item->category->slug) }}">
                                                <span class="st-menu__title">{{ @$item->category->name }}</span>
                                            </a>
                                            @if (count($item->children) > 0)
                                                @php
                                                    $megamenu = false;
                                                @endphp
                                                @foreach ($item->children as $child)
                                                    @if (count($child->children) > 0)
                                                        @php
                                                            $megamenu = true;
                                                            break;
                                                        @endphp
                                                    @endif
                                                @endforeach
                                                <span class="dropdown-toggle d-lg-none"></span>
                                                @if ($megamenu == true)
                                                    <div class="st-megamenu">
                                                        <div class="st_megamenu_content mCustomScrollbar">
                                                            <div class="row g-3">
                                                                @foreach ($item->children as $child)
                                                                    <div class="col-md-4">
                                                                        @if (@$child->category)
                                                                            <h3 class="feature-title">
                                                                                <a
                                                                                    href="{{ Route('frontend.products', @$child->category->slug) }}">{{ @$child->category->name }}</a>
                                                                            </h3>
                                                                            <ul>
                                                                                @foreach ($child->children as $child)
                                                                                    @if (@$child->category)
                                                                                        <li><a
                                                                                                href="{{ Route('frontend.products', @$child->category->slug) }}">{{ @$child->category->name }}</a>
                                                                                        </li>
                                                                                    @endif
                                                                                @endforeach
                                                                            </ul>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="st-submenu">
                                                        <div class="st-submenu__content">
                                                            <ul class="st-submenu__list">
                                                                @foreach ($item->children as $child)
                                                                    @if (@$child->category)
                                                                        <li><a class="st-submenu__link"
                                                                                href="{{ Route('frontend.products', @$child->category->slug) }}">{{ @$child->category->name }}</a>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                        </li>
                                        @if ($key == 9)
                                            <div class="more-w order-last"><span class="more-view">More
                                                    Categories</span></div>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="horizontal-menu d-lg-block d-none">
                <ul class="groupmenu">
                    @if ($menus->where('position', 'header')->first())
                        @foreach ($menus->where('position', 'header')->first()->rootItems as $item)
                            <li class="horizontal-menu__item {{ count($item->children) > 0 ? 'has-sub' : '' }}">
                                <a class="menu-link"
                                    href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span
                                        class="text-uppercase">{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                @if (count($item->children) > 0)
                                    <ul class="groupmenu-drop" style="display: none;">
                                        @foreach ($item->children as $item)
                                            <li
                                                class="horizontal-menu__item {{ count($item->children) > 0 ? 'has-sub' : '' }}">
                                                <a class="menu-link"
                                                    href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                @if (count($item->children) > 0)
                                                    <ul class="groupmenu-drop" style="display: none;">
                                                        @foreach ($item->children as $item)
                                                            <li class="horizontal-menu__item">
                                                                <a class="menu-link"
                                                                    href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                            </li>
                                                            <li
                                                                class="horizontal-menu__item {{ count($item->children) > 0 ? 'has-sub' : '' }}">
                                                                <a class="menu-link"
                                                                    href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                                @if (count($item->children) > 0)
                                                                    <ul class="groupmenu-drop" style="display: none;">
                                                                        @foreach ($item->children as $item)
                                                                            <li class="horizontal-menu__item">
                                                                                <a class="menu-link"
                                                                                    href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End Header -->
