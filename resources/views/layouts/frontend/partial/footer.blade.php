<footer class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row g-4">
                {{-- 
                    @foreach ($menus->where('position', 'footer') as $menu)
                        <div class="col-lg-3 col-6">
                            <h5 class="h5 footer-title">{{ $menu->name }}</h5>
                            <ul class="footer-link">
                                @foreach ($menu->items as $item)
                                    <li>
                                        <a
                                            href="{{ $item->page ? Route('frontend.page', @$item->page->slug) : Route('frontend.products', @$item->category->slug) }}">{{ @$item->page->name . @$item->category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                --}}
                <div class="col-lg-4 col-sm-6">
                    <h5 class="h5 footer-title">Head Office</h5>
                    <ul class="footer-list">
                        <li>{!! @$setting->address !!}</li>
                    </ul>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <div class="newsletter-block">
                        <h5 class="h5 footer-title">Subscribe us</h5>
                        <form class="subscribe" action="{{ Route('frontend.subscribe.store') }}" method="post">
                            @csrf
                            <div class="newsletter">
                                <div class="control">
                                    <input name="email" type="email" id="newsletter" class="form-control"
                                        placeholder="Enter your email address">
                                </div>
                                <button class="subscribe-btn btn" title="Subscribe" type="submit">
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20"
                                        viewBox="0 0 768 768">
                                        <g id="icomoon-ignore">
                                        </g>
                                        <path fill="currentColor"
                                            d="M577.123 336.842v-143.72l190.877 190.877-190.877 190.877v-143.72h-577.123v-94.316h577.123z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-6">
                    <p class="h5 footer-title">We Accept</p>
                    <div id="footer-content-5">
                        <div class="instagram-wrapper">
                            <div class="photo-items items">
                                <img src="{{ asset('frontend/assets/images/icons/payment-logos.png') }}" alt=""
                                    height="160">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-address">
                <p class="mb-0">Copyright © {{ date('Y') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
<!-- End Footer -->
