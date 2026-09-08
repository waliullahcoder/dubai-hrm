@extends('layouts.frontend.app')
@section('content')
    <div class="bg-white">
        <div class="container">
            <div class="breadcrumbs py-3">
                <ul class="items">
                    <li class="item home">
                        <a href="{{ Route('frontend.home') }}" title="Go to Home Page">Home</a>
                    </li>
                    <li class="item">{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="py-4">
        <div class="container">
            <div class="product-info__wrapper">
                <div class="product-gallery">
                    <div class="product-gallery__wrapper">
                        <div class="product-zoom mb-3">
                            <div class="zoom-wrapper">
                                <a class="popup-image" data-fancybox=""
                                    href="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}">
                                    <img id="zoomImg"
                                        src="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}"
                                        alt="Image">
                                </a>
                            </div>
                        </div>
                        <div class="product-navs">
                            <div class="product-photos swiper" id="image-gallery">
                                <button class="btn btn-sm btn-primary button-prev">
                                    <i class="fas fa-chevron-left"></i></button>
                                <ul class="swiper-wrapper">
                                    <li class="swiper-slide">
                                        <a class="active nav__image"
                                            data-image="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}"
                                            href="#">
                                            <img src="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}"
                                                alt="Image"></a>
                                    </li>
                                    @if (!is_null(@$product->more_images))
                                        @php
                                            $images = explode('|', @$product->more_images);
                                        @endphp
                                        @foreach ($images as $image)
                                            <li class="swiper-slide">
                                                <a class="nav__image"
                                                    data-image="{{ asset(file_exists($image) ? $image : @$setting->placeholder) }}"
                                                    href="#">
                                                    <img src="{{ asset(file_exists($image) ? $image : @$setting->placeholder) }}"
                                                        alt="Image"></a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <button class="btn btn-sm btn-primary button-next">
                                    <i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="product-info">
                    <div class="info__wrapper">
                        <h1 class="detail-product-name">{{ $product->name }}</h1>
                        {{-- 
                        <div class="product-availability">
                            <label class="control-label">Availability</label>:
                            <span id="product-availability" class="product-available">
                                @php
                                    if ($product->product_type == 'Consumer') {
                                        $stock = App\HelperClass::stock($product->id, 'Consumer');
                                    } else {
                                        $first_variant = $product->sku->first();
                                        $stock = App\HelperClass::stock($first_variant->id, 'Fashion');
                                    }
                                @endphp
                                <span class="in-stock {{ $stock > 0 ? 'd-inline-block' : 'd-none' }}">
                                    <i class="far fa-check-square text-sm"></i>
                                    In stock
                                </span>
                                <span class="out-of-stock text-danger {{ $stock > 0 ? 'd-none' : 'd-inline-block' }}">Stock
                                    Out</span>
                            </span>
                        </div>
                        --}}
                        <div class="price-box">
                            @if ($product->product_type == 'Consumer')
                                <span class="special-price">
                                    <span class="price text-xl">TK
                                        {{ number_format($product->price->online_price - $product->price->discount_tk, 2, '.') }}</span>
                                </span>
                                @if (@$product->price->discount_tk > 0)
                                    <span class="old-price">
                                        <span class="price text-lg">TK
                                            {{ number_format($product->price->online_price, 2, '.') }}</span>
                                    </span>
                                @endif
                            @else
                                @php
                                    $sku = $product->sku->first();
                                @endphp
                                <span class="special-price">
                                    <span class="price text-xl">TK
                                        {{ number_format($sku->price - $sku->discount_tk, 2, '.') }}</span>
                                </span>
                                @if ($sku->discount_tk > 0)
                                    <span class="old-price">
                                        <span class="price text-lg">TK
                                            {{ number_format($sku->price, 2, '.') }}</span>
                                    </span>
                                @endif
                            @endif
                        </div>
                        @if ($product->short_description)
                            <div class="short_description mb-3">
                                {!! $product->short_description !!}
                            </div>
                        @endif
                        <form id="option-choice-form">
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            @if ($product->product_type == 'Fashion')
                                <input type="hidden" id="variant_id" name="variant_id" value="{{ @$first_variant->id }}">
                            @endif
                            <div class="product-variants in_border">
                                @if ($product->choice_options != null)
                                    @foreach (json_decode($product->choice_options) as $key => $choice)
                                        <div class="product-variants-item">
                                            <span class="text-uppercase fw-500 text-xs">
                                                {{ \App\Models\Attribute::find($choice->attribute_id)->name }}:
                                            </span>
                                            <select id="attribute_id_{{ $choice->attribute_id }}" class="form-select"
                                                name="attribute_id_{{ $choice->attribute_id }}">
                                                @foreach ($choice->values as $key => $value)
                                                    <option value="{{ $value }}" {{ $key == 0 ? 'selected' : '' }}
                                                        title="{{ $value }}">
                                                        {{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="product-add-to-cart in_border">
                                <span class="text-uppercase fw-500 text-xs">QTY: </span>
                                <div class="product-quantity">
                                    <div class="qty">
                                        <div class="input-group border">
                                            <button class="btn qty-minus btn-sm d-inline-flex align-items-center ps-3"
                                                type="button"><i class="fal fa-minus"></i></button>
                                            <input type="number" name="quantity" id="quantity_wanted"
                                                class="form-control quantity_wanted text-center border-0" placeholder="1"
                                                value="1" min="1"
                                                style="max-width: 60px;">
                                            <button class="btn qty-plus btn-sm d-inline-flex align-items-center pe-3"
                                                type="button">
                                                <i class="fal fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                {{-- 
                                <div class="d-inline-flex text-muted text-xs text-nowrap">
                                    (<span id="available-quantity"> {{ $stock }} &nbsp; </span> available)
                                </div>
                                --}}
                            </div>

                            <div class="product-add-to-cart in_border">
                                <div class="add">
                                    <button class="btn btn-primary add-cart px-3" id="add_cart_btn" type="button"
                                        data-id="{{ $product->id }}" data-variant="{{ @$sku->id }}">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20"
                                            viewBox="0 0 768 768">
                                            <g id="icomoon-ignore">
                                            </g>
                                            <path fill="currentColor"
                                                d="M702.197 142.154c-6.941-7.959-16.934-12.537-27.473-12.537h-502.19l-7.087-42.301c-2.906-17.516-18.061-30.38-35.832-30.38h-81.767c-20.096 0-36.341 16.244-36.341 36.341s16.244 36.341 36.341 36.341h50.985l67.593 405.705c0.29 1.635 1.127 2.981 1.635 4.507 0.582 1.925 1.053 3.743 1.963 5.488 1.163 2.399 2.725 4.433 4.361 6.505 1.126 1.418 2.143 2.834 3.452 4.070 2.108 1.963 4.543 3.343 7.014 4.725 1.381 0.763 2.58 1.781 4.070 2.362 4.215 1.709 8.65 2.726 13.337 2.726 0.038 0 399.782 0 399.782 0 20.096 0 36.341-16.244 36.341-36.341s-16.244-36.341-36.341-36.341h-368.966l-6.033-36.341h411.338c18.098 0 33.433-13.3 35.977-31.18l36.341-254.383c1.49-10.465-1.635-21.042-8.503-28.963zM632.824 202.298l-10.356 72.68h-129.445v-72.68h139.801zM456.68 202.298v72.68h-109.021v-72.68h109.021zM456.68 311.32v72.68h-109.021v-72.68h109.021zM311.32 202.298v72.68h-109.022c-1.925 0-3.671 0.544-5.378 1.090l-12.283-73.771h126.683zM202.807 311.32h108.513v72.68h-96.411l-12.102-72.68zM493.021 384v-72.68h124.212l-10.356 72.68h-113.855z">
                                            </path>
                                            <path fill="currentColor"
                                                d="M311.32 656.553c0 30.105-24.405 54.51-54.51 54.51s-54.51-24.405-54.51-54.51c0-30.105 24.405-54.51 54.51-54.51s54.51 24.405 54.51 54.51z">
                                            </path>
                                            <path fill="currentColor"
                                                d="M638.383 656.553c0 30.105-24.405 54.51-54.51 54.51s-54.51-24.405-54.51-54.51c0-30.105 24.405-54.51 54.51-54.51s54.51 24.405 54.51 54.51z">
                                            </path>
                                        </svg>
                                        <span class="ms-1">Add to cart</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="product-share in_border">
                            <div class="product-share__list">
                                {!! $shareComponent !!}
                            </div>
                        </div>
                        <div class="product_comments_block_extra in_border">
                            <div class="comments_note">
                                <span>Review: </span>
                                <div class="star_content clearfix">
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                    <div class="star"></div>
                                </div>
                            </div>
                            <div class="comments_advices">
                                <a href="#" class="review_tab me-3"><i class="fa fa-comments"></i>Read
                                    reviews (0)</a>
                            </div>
                        </div>
                        <div class="end">
                            @if ($product->product_type == 'Fashion')
                                <div class="sku">
                                    <label class="text-dark me-1">Sku</label>:
                                    <span id="product_sku">{{ $sku->sku }}</span>
                                </div>
                            @endif
                            @if (@$product->category)
                                <div class="pro-cate">
                                    <label class="text-dark me-1">Category:</label>
                                    <span><a href="{{ Route('frontend.products', @$product->category->slug) }}"
                                            title="Computer">PC
                                            Gaming</a></span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4 custom-tab">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-description-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-description" type="button" role="tab"
                            aria-controls="nav-description" aria-selected="true">Description</button>
                        <button class="nav-link" id="nav-informations-tab" data-bs-toggle="tab"
                            data-bs-target="#nav-informations" type="button" role="tab"
                            aria-controls="nav-informations" aria-selected="false">Informations</button>
                        <button class="nav-link" id="nav-reviews-tab" data-bs-toggle="tab" data-bs-target="#nav-reviews"
                            type="button" role="tab" aria-controls="nav-reviews" aria-selected="false">Reviews
                            <span class="text-xs">(0)</span></button>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-description" role="tabpanel"
                        aria-labelledby="nav-description-tab">
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                    </div>
                    <div class="tab-pane fade" id="nav-informations" role="tabpanel"
                        aria-labelledby="nav-informations-tab">
                        {!! $product->additional_info !!}
                    </div>
                    <div class="tab-pane fade" id="nav-reviews" role="tabpanel" aria-labelledby="nav-reviews-tab">
                        ...</div>
                </div>
            </div>
            <div class="mt-4">
                <div class="block-card">
                    <div class="block-card__header">
                        <div class="block-title mb-0">
                            <h2 class="b-title h4">RELATED PRODUCTS</h2>
                        </div>
                    </div>
                    <div class="block-card__body">
                        <div class="carousel owl-carousel related-carousel" data-margin="8" data-items="6"
                            data-xl-items="5" data-lg-items="4" data-md-items="3" data-xs-items="2" data-arrows="true">
                            @foreach ($related_products as $product)
                                <div>
                                    <div class="product-card">
                                        <div class="product-card__thumbnail">
                                            @php
                                                $discount = 0;
                                                if (
                                                    $product->product_type == 'Consumer' &&
                                                    $product->price->discount_tk > 0
                                                ) {
                                                    $price = $product->price->online_price;
                                                    $discount_tk = $product->price->discount_tk;
                                                    $discount = ceil(($discount_tk / $price) * 100);
                                                } elseif ($product->product_type == 'Fashion') {
                                                    $sku = $product->sku->first();
                                                    $price = $sku->price;
                                                    $discount_tk = $sku->discount_tk;
                                                    $discount = ceil(($discount_tk / $price) * 100);
                                                }
                                            @endphp
                                            @if ($discount > 0)
                                                <span class="discount">-{{ $discount }}%</span>
                                            @endif
                                            <div class="actions-secondary">
                                                <a href="javascript:void(0)" class="action add-to-wishlist"
                                                    title="Add to Wishlist" data-id="{{ $product->id }}">
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                                        viewBox="0 0 1024 1024">
                                                        <g id="icomoon-ignore">
                                                        </g>
                                                        <path fill="currentColor"
                                                            d="M934.176 168.48c-116.128-115.072-301.824-117.472-422.112-9.216-120.32-108.256-305.952-105.856-422.144 9.216-119.712 118.528-119.712 310.688 0 429.28 34.208 33.888 353.696 350.112 353.696 350.112 37.856 37.504 99.072 37.504 136.896 0 0 0 349.824-346.304 353.696-350.112 119.744-118.592 119.744-310.752-0.032-429.28zM888.576 552.576l-353.696 350.112c-12.576 12.512-33.088 12.512-45.6 0l-353.696-350.112c-94.4-93.44-94.4-245.472 0-338.912 91.008-90.080 237.312-93.248 333.088-7.104l43.392 39.040 43.36-39.040c95.808-86.144 242.112-83.008 333.12 7.104 94.4 93.408 94.4 245.44 0.032 338.912zM296.096 240.032c8.864 0 16 7.168 16 16s-7.168 16-16 16h-0.032c-57.408 0-103.968 46.56-103.968 103.968v0.032c0 8.832-7.168 16-16 16s-16-7.168-16-16v0c0-75.072 60.832-135.904 135.872-135.968 0.064 0 0.064-0.032 0.128-0.032z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a class="action quickview-handler" title="Quick View"
                                                    href="javascript:void(0)" data-id="{{ $product->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                            <a href="{{ Route('frontend.single-product', $product->slug) }}">
                                                <div class="ratio ratio-1x1">
                                                    <img class="fit-cover"
                                                        src="{{ file_exists($product->thumbnail) ? asset($product->thumbnail) : asset(@$setting->placeholder) }}"
                                                        alt="{{ $product->name }}">
                                                </div>
                                            </a>
                                        </div>
                                        <div class="product-card__details">
                                            <a class="product-item-link"
                                                href="{{ Route('frontend.single-product', $product->slug) }}">{{ $product->name }}
                                            </a>
                                            <div class="price-box">
                                                @if ($product->product_type == 'Consumer')
                                                    <span class="special-price">
                                                        <span class="price">TK
                                                            {{ number_format($product->price->online_price - $product->price->discount_tk, 2, '.') }}</span>
                                                    </span>
                                                    @if (@$product->price->discount_tk > 0)
                                                        <span class="old-price">
                                                            <span class="price">TK
                                                                {{ number_format($product->price->online_price, 2, '.') }}</span>
                                                        </span>
                                                    @endif
                                                @else
                                                    @php
                                                        $sku = $product->sku->first();
                                                    @endphp
                                                    <span class="special-price">
                                                        <span class="price">TK
                                                            {{ number_format($sku->price - $sku->discount_tk, 2, '.') }}</span>
                                                    </span>
                                                    @if ($sku->discount_tk > 0)
                                                        <span class="old-price">
                                                            <span class="price">TK
                                                                {{ number_format($sku->price, 2, '.') }}</span>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="product-view-wrap">
                                                <div class="actions-primary">
                                                    <button type="button" class="btn action tocart btn-primary"
                                                        title="Add to Cart" data-id="{{ $product->id }}">
                                                        <span>
                                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                height="16" viewBox="0 0 512 448">
                                                                <g id="icomoon-ignore">
                                                                </g>
                                                                <path fill="currentColor"
                                                                    d="M431.932 198.865c13.942 0 25.135 11.193 25.135 25.135s-11.193 25.135-25.135 25.135h-2.946l-22.582 129.996c-2.16 11.978-12.568 20.815-24.742 20.815h-251.352c-12.175 0-22.582-8.836-24.742-20.815l-22.582-129.996h-2.946c-13.942 0-25.135-11.193-25.135-25.135s11.193-25.135 25.135-25.135h351.892zM150.144 355.96c6.872-0.59 12.175-6.676 11.586-13.551l-6.284-81.689c-0.59-6.872-6.676-12.175-13.551-11.586s-12.175 6.676-11.586 13.551l6.284 81.689c0.59 6.48 6.088 11.586 12.568 11.586h0.982zM230.851 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM306.257 343.392v-81.689c0-6.872-5.694-12.568-12.568-12.568s-12.568 5.694-12.568 12.568v81.689c0 6.872 5.694 12.568 12.568 12.568s12.568-5.694 12.568-12.568zM375.378 344.374l6.284-81.689c0.59-6.872-4.713-12.96-11.586-13.551s-12.96 4.713-13.551 11.586l-6.284 81.689c-0.59 6.872 4.713 12.96 11.586 13.551h0.982c6.48 0 11.978-5.106 12.568-11.586zM148.376 105.393l-18.262 80.904h-25.921l19.833-86.599c5.106-22.975 25.332-39.078 48.896-39.078h32.794c0-6.872 5.694-12.568 12.568-12.568h75.405c6.872 0 12.568 5.694 12.568 12.568h32.794c23.564 0 43.79 16.102 48.896 39.078l19.833 86.599h-25.921l-18.262-80.904c-2.749-11.586-12.764-19.636-24.546-19.636h-32.794c0 6.872-5.694 12.568-12.568 12.568h-75.405c-6.872 0-12.568-5.694-12.568-12.568h-32.794c-11.782 0-21.797 8.051-24.546 19.636z">
                                                                </path>
                                                            </svg>
                                                        </span>
                                                        <span>Add to Cart</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Single Products -->
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var swiper = new Swiper("#image-gallery", {
                effect: 'slide',
                pagination: false,
                slidesPerView: 'auto',
                spaceBetween: 5,
                autoHeight: true,
                navigation: {
                    nextEl: ".button-next",
                    prevEl: ".button-prev",
                },
                // Responsive breakpoints
                breakpoints: {
                    0: {
                        direction: "horizontal",
                        slideToClickedSlide: true,
                        centeredSlides: true,
                        centerInsufficientSlides: true,
                        centeredSlidesBounds: true,
                    },
                    576: {
                        direction: "horizontal",
                    }
                }
            });

            function zoom() {
                if ($(window).width() > 576) {
                    $('#zoomImg').ezPlus({
                        responsive: true,
                        scrollZoom: false,
                        imageCrossfade: true,
                        easing: true,
                        borderSize: 0,
                        zoomLens: false,
                        zoomType: 'inner',
                        gallery: 'image-gallery',
                        cursor: 'pointer',
                        galleryActiveClass: 'active',
                    });
                }
            }
            zoom();

            $(window).resize(function() {
                zoom();
            });
        });
    </script>
@endpush
