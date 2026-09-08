<div class="">
    @if (count($products) > 0)
        <div class="px-2 py-1 text-uppercase text-xs text-end text-muted bg-light">Products</div>
        <ul class="list-group list-group-raw">
            @foreach ($products as $key => $product)
                <li class="list-group-item">
                    <a class="text-reset" href="{{ route('frontend.single-product', $product->slug) }}">
                        <div class="d-flex search-product align-items-center">
                            <div class="me-3">
                                <img class="img-fit rounded" src="{{ asset($product->thumbnail) }}" height="50" width="50">
                            </div>
                            <div class="flex-grow-1">
                                <div class="product-name text-truncate text-sm mb-1">
                                    {{ $product->name }}
                                </div>
                            </div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
