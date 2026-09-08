<div class="product-info__wrapper">
    <div class="product-gallery">
        <div class="product-gallery__wrapper">
            <div class="product-zoom mb-3">
                <div class="zoom-wrapper">
                    <a class="popup-image" data-fancybox=""
                        href="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}">
                        <img id="big_img" src="{{ asset(file_exists($product->thumbnail) ? $product->thumbnail : @$setting->placeholder) }}"
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
                                    onchange="getVariantPrice()" name="attribute_id_{{ $choice->attribute_id }}">
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
                                    value="1" min="1" style="max-width: 60px;">
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
                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 768 768">
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

            <div class="product-share">
                <div class="product-share__list">
                    {!! $shareComponent !!}
                </div>
            </div>
        </div>
    </div>
</div>
