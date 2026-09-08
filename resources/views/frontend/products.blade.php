@extends('layouts.frontend.app')

@section('content')
    <div class="pb-md-5 pb-4">
        <div class="container">
            <div class="breadcrumbs py-3">
                <ul class="items">
                    <li class="item home">
                        <a href="{{ Route('frontend.home') }}" title="Go to Home Page">Home</a>
                    </li>
                    <li class="item">{{ @$category->name }}</li>
                </ul>
            </div>
            <form action="" id="search-form" method="GET">
                <div class="sidebar-main__wrapper">
                    <div class="main-area ps-lg-4">
                        <div class="toolbar toolbar-products">
                            <div class="filter-mobile-btn d-lg-none">
                                <a id="btn-filter" class="btn btn-sm btn-primary" href="javascript:void(0);">Filter</a>
                            </div>
                            <div class="modes d-lg-block d-none">
                                <button type="button" class="btn btn-sm btn-filter">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16"
                                        viewBox="0 0 1024 1024">
                                        <g id="icomoon-ignore">
                                        </g>
                                        <path fill="currentColor"
                                            d="M0 448h448v-448h-448v448zM576 0v448h448v-448h-448zM576 1024h448v-448h-448v448zM0 1024h448v-448h-448v448z">
                                        </path>
                                    </svg>
                                </button>
                                <button type="button" class="btn btn-sm btn-filter">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="18"
                                        viewBox="0 0 448 448">
                                        <g id="icomoon-ignore">
                                        </g>
                                        <path fill="currentColor"
                                            d="M64 328v48c0 4.25-3.75 8-8 8h-48c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h48c4.25 0 8 3.75 8 8zM64 232v48c0 4.25-3.75 8-8 8h-48c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h48c4.25 0 8 3.75 8 8zM64 136v48c0 4.25-3.75 8-8 8h-48c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h48c4.25 0 8 3.75 8 8zM448 328v48c0 4.25-3.75 8-8 8h-336c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h336c4.25 0 8 3.75 8 8zM64 40v48c0 4.25-3.75 8-8 8h-48c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h48c4.25 0 8 3.75 8 8zM448 232v48c0 4.25-3.75 8-8 8h-336c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h336c4.25 0 8 3.75 8 8zM448 136v48c0 4.25-3.75 8-8 8h-336c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h336c4.25 0 8 3.75 8 8zM448 40v48c0 4.25-3.75 8-8 8h-336c-4.25 0-8-3.75-8-8v-48c0-4.25 3.75-8 8-8h336c4.25 0 8 3.75 8 8z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <p class="toolbar-amount d-lg-block d-none"> Items <span class="toolbar-number">1</span>-<span
                                    class="toolbar-number">96</span> of <span class="toolbar-number">341</span></p>
                            <div class="toolbar-sorter">
                                <label class="sorter-label" for="sorter">Sort By</label>
                                <select id="sorter" class="sorter-options form-select">
                                    <option value="position" selected="selected"> Position</option>
                                    <option value="name"> Product Name</option>
                                    <option value="price"> Price</option>
                                </select>
                            </div>
                        </div>
                        <div class="border-start border-top">
                            <div class="row g-0">
                                @foreach ($products as $product)
                                    <div class="col-xl-3 col-md-4 col-6">
                                        <div class="border-end border-bottom">
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
                                                        <a href="{{ Auth::check() ? 'javascript:void(0)' : Route('customer.login') }}"
                                                            class="action {{ Auth::check() ? 'add-to-wishlist' : '' }}"
                                                            title="Add to Wishlist" data-id="{{ $product->id }}">
                                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg"
                                                                height="16" viewBox="0 0 1024 1024">
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
                                                            <div class="d-flex justify-content-between gap-2">
                                                                <div class="flex-grow-1">
                                                                    <span class="special-price">
                                                                        <span class="price">TK
                                                                            {{ number_format($product->price->online_price - $product->price->discount_tk) }}</span>
                                                                    </span>
                                                                    @if (@$product->price->discount_tk > 0)
                                                                        <span class="old-price">
                                                                            <span class="price">TK
                                                                                {{ number_format($product->price->online_price) }}</span>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="flex-shrink-0">
                                                                    Per {{ @$product->attribute->name }}
                                                                </div>
                                                            </div>
                                                        @else
                                                            @php
                                                                $sku = $product->sku->first();
                                                            @endphp
                                                            <span class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format($sku->price - $sku->discount_tk) }}</span>
                                                            </span>
                                                            @if ($sku->discount_tk > 0)
                                                                <span class="old-price">
                                                                    <span class="price">TK
                                                                        {{ number_format($sku->price) }}</span>
                                                                </span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="product-view-wrap">
                                                        <div class="actions-primary">
                                                            <button type="button"
                                                                class="btn action {{ count(@$product->sku) > 0 ? 'toModal' : 'toCart' }} btn-primary"
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
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="pt-2">
                            {!! $products->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                    <div class="sidebar-area order-lg-first">
                        <div class="block filter">
                            <div class="block-title filter-title">Filter Products By</div>
                            <div class="filter-content">
                                <div class="filter-options">
                                    <div class="filter-options-item">
                                        <div class="filter-options-title">Category</div>
                                        <div class="filter-options-content">
                                            <ul class="items">
                                                @php
                                                    $categories = \App\Models\Category::root()
                                                        ->with(['children'])
                                                        ->where('status', 1)
                                                        ->orderBy('name', 'asc')
                                                        ->get();
                                                @endphp
                                                @foreach ($categories as $category)
                                                    <li class="item">
                                                        <a href="{{ Route('frontend.products', $category->slug) }}">{{ $category->name }}<span
                                                                class="count">{{ count($category->products) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="filter-options-item">
                                        <div class="filter-options-title">FILTER BY PRICE</div>
                                        <div class="filter-options-content">
                                            <div data-min="0" data-max="10000" class="mb-3 priceFilter"
                                                data-setmin="{{ request('min_price') ?? 0 }}"
                                                data-setmax="{{ request('max_price') ?? 10000 }}"></div>
                                            <div id="price" class="d-flex justify-content-between"></div>
                                        </div>
                                        <input type="hidden" id="min_price" name="min_price"
                                            value="{{ request('min_price') ?? 0 }}">
                                        <input type="hidden" id="max_price" name="max_price"
                                            value="{{ request('max_price') ?? 10000 }}">
                                    </div>
                                    @php
                                        $attributes = \App\Models\Attribute::with('values')
                                            ->whereHas('values')
                                            ->where('status', 1)
                                            ->get();
                                    @endphp
                                    @foreach ($attributes as $attribute)
                                        <div class="filter-options-item">
                                            <div class="filter-options-title">{{ $attribute->name }}</div>
                                            <div class="filter-options-content">
                                                <ul class="checkbox__list">
                                                    @foreach ($attribute->values as $value)
                                                        <li class="checkbox__item">
                                                            <label class="facet-label"
                                                                for="{{ 'facet_input_' . $value->id }}">
                                                                <span class="custom-checkbox">
                                                                    <input id="{{ 'facet_input_' . $value->id }}"
                                                                        name="attribute[]" type="checkbox"
                                                                        onchange="filter();" value="{{ $value->value }}"
                                                                        {{ is_array(request('attribute')) && in_array($value->value, request('attribute')) ? 'checked' : '' }}>
                                                                    <span class="ps-shown-by-js">
                                                                        <i class="fas fa-check"></i>
                                                                    </span>
                                                                </span>
                                                                <span class="js-search-link">{{ $value->value }}</span>
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="filter-overlay"></div>
                </div>
            </form>
        </div>
    </div>
    <!-- End Category Products -->
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            var minVal = $(".priceFilter").data("min");
            var maxVal = $(".priceFilter").data("max");
            var setMin = $(".priceFilter").data("setmin");
            var setMax = $(".priceFilter").data("setmax");
            $(".priceFilter").slider({
                range: true,
                min: minVal,
                max: maxVal,
                values: [setMin, setMax],
                slide: function(event, ui) {
                    $("#price").html(
                        `<span class='fw-500 text-dark'>৳ ${ui.values[0]}</span><span class='fw-500 text-dark'>৳ ${ui.values[1]}</span>`
                    );
                    $('#min_price').val(ui.values[0]);
                    $('#max_price').val(ui.values[1]);
                    filter();
                }
            });
            $("#price").html("<span class='fw-500 text-dark'>৳ " + $(".priceFilter").slider("values", 0) +
                "</span>" + "<span class='fw-500 text-dark'>৳ " + $(".priceFilter").slider("values", 1) +
                "</span>");

            $(document).on('click', '#btn-filter', function(e) {
                e.preventDefault();
                $('.sidebar-area').addClass('show');
                $('.filter-overlay').addClass('show');
            });
        });

        function filter() {
            $('#search-form').submit();
        }
    </script>
@endpush
