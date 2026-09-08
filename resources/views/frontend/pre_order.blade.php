<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ !is_null($setting) ? $setting->title : '' }}</title>
    <link rel="shortcut icon"
        href="{{ !is_null($setting) && file_exists($setting->favicon) ? asset($setting->favicon) : asset('frontend/assets/images/logo/favicon.png') }}"
        type="image/x-icon">

    <link rel='stylesheet' id='google-fonts-1-css'
        href='https://fonts.googleapis.com/css?display=swap&family=Roboto%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CRoboto+Slab%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CNoto+Sans+Bengali%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CHind+Siliguri%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CAnek+Bangla%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic%7CInter%3A100%2C100italic%2C200%2C200italic%2C300%2C300italic%2C400%2C400italic%2C500%2C500italic%2C600%2C600italic%2C700%2C700italic%2C800%2C800italic%2C900%2C900italic&#038;ver=6.6.2'
        media='all' />
    @include('layouts.frontend.partial.styles')
    <style>
        body {
            font-family: "Hind Siliguri", Sans-serif;
            background-color: var(--bs-white);
        }

        .heading_title {
            color: #FFFFFF;
            font-size: 1.5rem;
            font-weight: 600;
            background-color: #00684d;
            padding: 20px 10px 20px 10px;
            border-radius: 7px 7px 7px 7px;
            text-align: center;
            margin-bottom: 20px;
        }

        .iframe__wrapper {
            max-width: 800px;
            margin-inline: auto;
            border: 2px solid #e72d41;
        }

        .feature__list {}

        .feature__list li {
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            padding-block: 6px;
            font-weight: 500;
            align-items: baseline;
            display: flex;
            gap: 8px;
        }

        .feature__list li i {
            color: #00684d;
        }

        .feature__list li:last-child {
            border-bottom: none;
        }

        .cart__wrapper {
            border: 1px solid rgb(223, 223, 223);
        }

        .checkout__cart {
            background-color: rgb(232, 243, 246);
            border-left: 1px solid rgb(223, 223, 223);
            height: 100%;
        }

        .wg-quantity {
            background-color: var(--bs-white);
        }

        .checkout__wrapper {
            border: 1px solid #DEE0E3;
            box-shadow: 0px 64px 80px -40px rgba(0, 0, 0, 0.12);
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 40px;
        }

        .btn {
            font-weight: 600;
        }

        @media (max-width: 575px) {
            .checkout__wrapper {
                padding: 0;
                border: none;
            }

            .heading_title {
                font-size: 1rem;
                padding: 12px 10px;
                border-radius: 4px;
            }

            .h2,
            h2 {
                font-size: calc(1.325rem + .1vw);
            }
        }
    </style>
    <!-- End Css Links -->
    @include('layouts.admin.partial.alert')
</head>

<body>
    <div class="py-4">
        <div class="container">
            @php
                $count = 1;
            @endphp
            @foreach ($data->sections as $item)
                <div class="pb-lg-5 pb-4">
                    @if (!is_null($item->title))
                        <h2 class="heading_title">{{ $item->title }}</h2>
                    @endif
                    @if ($item->type == 'video')
                        <div class="iframe__wrapper mt-4">
                            <div class="ratio ratio-16x9">
                                <iframe width="560" height="315"
                                    src="https://www.youtube.com/embed/{{ $item->video_link }}"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>
                    @endif
                    @if ($item->type == 'list')
                        <ul class="feature__list">
                            @foreach ($list as $single)
                                <li><i class="fas fa-check-circle"></i> {{ $single }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @if ($item->type == 'image_list')
                        <div class="row g-lg-5 g-4">
                            <div class="col-md-7 {{ $count % 2 == 0 ? 'order-last' : '' }}">
                                @php
                                    $list = explode('|', $item->list);
                                    $count++;
                                @endphp
                                <ul class="feature__list">
                                    @foreach ($list as $single)
                                        <li><i class="fas fa-check-circle"></i> {{ $single }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-5">
                                @if (file_exists($item->image))
                                    <img src="{{ asset($item->image) }}" alt="">
                                @endif
                            </div>
                        </div>
                    @endif
                    @if ($item->description)
                        <div>
                            {!! $item->description !!}
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="checkout__wrapper">
                <h1 class="h2 fw-bold text-center mb-3 text-primary">কোন প্রশ্ন বা সাহায্য লাগলে কল করুনঃ
                    {{ @$setting->primary_mobile }}
                </h1>
                <h2 class="h2 fw-bold text-center mb-4 pb-md-2 text-dark">অর্ডার করতে নিচের ফর্মটি সম্পূর্ণ পূরন করুন
                </h2>
                <form action="{{ Route('frontend.pre-order.store', $data->slug) }}" method="POST">
                    @csrf
                    <div class="cart__wrapper">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="p-4 checkout__cart">
                                    <div class="table-responsive">
                                        <table class="table align-middle">
                                            <thead class="text-xs text-uppercase">
                                                <tr>
                                                    <th>Image</th>
                                                    <th>Product</th>
                                                    <th>price</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="{{ file_exists(@$data->product->thumbnail) ? asset(@$data->product->thumbnail) : asset(@$setting->placeholder) }}"
                                                            alt="{{ $data->product->name }}" height="50">
                                                    </td>
                                                    <td>{{ $data->product->name }}</td>
                                                    <td>
                                                        <div class="price-box">
                                                            <div class="special-price">
                                                                <span class="price">TK
                                                                    {{ number_format(@$data->product->price->online_price) }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="price"
                                                            value="{{ @$data->product->price->online_price }}">
                                                        <div class="cart-quantity justify-content-center">
                                                            <div class="wg-quantity">
                                                                <span class="btn-quantity minus-btn">
                                                                    <svg class="d-inline-block" width="9"
                                                                        height="1" viewBox="0 0 9 1"
                                                                        fill="currentColor">
                                                                        <path
                                                                            d="M9 1H5.14286H3.85714H0V1.50201e-05H3.85714L5.14286 0L9 1.50201e-05V1Z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                                <input type="text" class="item-qty cart-item-qty"
                                                                    name="qty" value="1"
                                                                    data-price="{{ @$data->product->price->online_price }}">
                                                                <span class="btn-quantity plus-btn">
                                                                    <svg class="d-inline-block" width="9"
                                                                        height="9" viewBox="0 0 9 9"
                                                                        fill="currentColor">
                                                                        <path
                                                                            d="M9 5.14286H5.14286V9H3.85714V5.14286H0V3.85714H3.85714V0H5.14286V3.85714H9V5.14286Z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">TK
                                                        <span
                                                            id="subtotal">{{ number_format(@$data->product->price->online_price) }}</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 order-md-first">
                                <div class="p-4">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="আপনার নাম..." required>
                                        </div>
                                        <div class="col-12">
                                            <input type="number" name="phone" id="phone" class="form-control"
                                                placeholder="ফোন নাম্বার..." required>
                                        </div>
                                        <div class="col-12">
                                            <input type="text" name="shipping_address" id="shipping_address"
                                                class="form-control" placeholder="সম্পূর্ণ ঠিকানা..." required>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary">অর্ডার কনফার্ম
                                                    করুন</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('layouts.frontend.partial.scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.plus-btn', function(e) {
                let qty = +$(this).closest('.cart-quantity').find('.item-qty').val();
                let price = +$(this).closest('.cart-quantity').find('.item-qty').data('price');
                qty++;
                $(this).closest('.cart-quantity').find('.item-qty').val(qty);
                $('#subtotal').text(qty * price);
            });

            $(document).on('click', '.minus-btn', function(e) {
                let qty = +$(this).closest('.cart-quantity').find('.item-qty').val();
                let price = +$(this).closest('.cart-quantity').find('.item-qty').data('price');
                qty--;
                if (qty == 0) {
                    return;
                }
                $(this).closest('.cart-quantity').find('.item-qty').val(qty);
                $('#subtotal').text(qty * price);
            });
        });
    </script>
</body>

</html>
