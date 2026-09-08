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
            background-color: #F8FCFF;
        }

        .order__card {
            background-color: var(--bs-white);
            border: 1px solid #DEE0E3;
            border-radius: 12px;
            padding: 30px;
            max-width: 800px;
            margin: 30px auto 0;
        }

        .order__details {
            background-color: #F8FCFF;
            padding: 15px;
        }
    </style>
    <!-- End Css Links -->
    @include('layouts.admin.partial.alert')
</head>

<body>
    <div class="py-md-5 py-4">
        <div class="container">
            <div class="success__wrapper">
                <div class="text-center">
                    <img class="mb-3" src="{{ asset('frontend/check.png') }}" alt="">
                    <h1 class="fw-bold check_success">ধন্যবাদ<br>আপনার অর্ডারটি সম্পন্ন হয়েছে</h1>
                </div>
                <div class="order__card">
                    <h3 class="h4 text-center mb-3">আমাদের কল সেন্টার থেকে খুব দ্রুতই আপনার সাথে যোগাযোগ করে অর্ডারটি
                        কনফার্ম করা
                        হবে। ধন্যবাদ</h3>
                    <div class="order__details">
                        <h5>Order details</h5>
                        <table class="table order__table">
                            <thead>
                                <tr>
                                    <th class="product-name">Product</th>
                                    <th class="product-total">Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($order->products as $item)
                                    <tr class="order_item">
                                        <td class="product-name"> {{ @$item->product->name }} <strong
                                                class="product-quantity">×&nbsp;{{ number_format($item->quantity) }}</strong>
                                        </td>
                                        <td class="product-total">
                                            <span
                                                class="amount"><bdi>{{ $item->subtotal }}<span>৳&nbsp;</span></bdi></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th scope="row">Subtotal:</th>
                                    <td>
                                        <span class="amount">{{ $order->sub_total }}
                                            <span>৳&nbsp;</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Shipping:</th>
                                    <td>
                                        <span class="amount">{{ $order->shipping_charge }}
                                            <span>৳&nbsp;</span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Payment method:</th>
                                    <td>ক্যাশ অন ডেলিভারি</td>
                                </tr>
                                <tr>
                                    <th scope="row">Total:</th>
                                    <td><span class="amount">{{ $order->due }}<span>৳&nbsp;</span></span></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
