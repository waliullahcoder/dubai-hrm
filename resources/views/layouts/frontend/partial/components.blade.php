<div class="mobile-menu">
    <div class="mobile-menu__header">
        <h5 class="h5 mb-0">Menu</h5>
        <a class="action closemenu text-primary menu-toggler" href="javascript:void(0)" title="close menu">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="16" viewBox="0 0 1024 1024">
                <g id="icomoon-ignore">
                </g>
                <path fill="currentColor"
                    d="M655.539 512.042l243.942 243.942c15.847 15.847 15.847 41.578 0 57.425l-86.077 86.077c-15.847 15.847-41.536 15.847-57.425 0l-243.979-243.942-243.979 243.942c-15.847 15.847-41.536 15.847-57.383 0l-86.114-86.077c-15.847-15.847-15.847-41.536 0-57.425l243.979-243.942-243.979-243.979c-15.847-15.847-15.847-41.578 0-57.425l86.156-86.077c15.847-15.847 41.536-15.847 57.383 0l243.942 243.979 243.979-243.979c15.847-15.847 41.536-15.847 57.425 0l86.077 86.114c15.847 15.847 15.847 41.536 0 57.425l-243.942 243.942z">
                </path>
            </svg>
        </a>
    </div>
    <div class="mobile-menu__body">
        <ul class="groupmenu">
            @if ($menus->where('position', 'header')->first())
                @foreach ($menus->where('position', 'header')->first()->rootItems as $item)
                    <li class="mobile-menu__item level1">
                        <a class="menu-link"
                            href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                        @if (count($item->children) > 0)
                            <span class="dropdown-toggle"></span>
                        @endif
                        @if (count($item->children) > 0)
                            <ul class="mobile-groupmenu__drop" style="display: none;">
                                @foreach ($item->children as $item)
                                    <li class="mobile-menu__item level2">
                                        <a class="menu-link"
                                            href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                        <span class="dropdown-toggle"></span>
                                        @if (count($item->children) > 0)
                                            <ul class="mobile-groupmenu__drop" style="display: none;">
                                                @foreach ($item->children as $item)
                                                    <li class="mobile-menu__item level3">
                                                        <a class="menu-link"
                                                            href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                    </li>
                                                    <li class="mobile-menu__item level3">
                                                        <a class="menu-link"
                                                            href="{{ $item->category ? Route('frontend.products', $item->category->slug) : Route('frontend.page', $item->page->slug) }}"><span>{{ @$item->category->name }}{{ @$item->page->name }}</span></a>
                                                        <span class="dropdown-toggle"></span>
                                                        @if (count($item->children) > 0)
                                                            <ul class="mobile-groupmenu__drop" style="display: none;">
                                                                @foreach ($item->children as $item)
                                                                    <li class="mobile-menu__item level4">
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
<!-- End Mobile Menu -->

<div class="sticky-footer d-lg-none">
    <ul class="row g-0">
        <li class="col">
            <a class="sticky-footer__link" href="{{ Route('frontend.home') }}">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 640 640">
                    <g id="icomoon-ignore">
                    </g>
                    <path fill="currentColor"
                        d="M614.749 353.989h-56.829v203.931c0 15.125-6.594 33.989-33.989 33.989h-135.954v-203.931h-135.954v203.931h-135.954c-27.394 0-33.989-18.864-33.989-33.989v-203.931h-56.829c-20.326 0-15.974-11.012-2.039-25.424l272.724-272.996c6.627-6.866 15.329-10.264 24.064-10.604 8.735 0.34 17.436 3.705 24.064 10.604l272.691 272.962c13.969 14.445 18.32 25.457-2.006 25.457z">
                    </path>
                </svg>
            </a>
        </li>
        <li class="col">
            <a class="sticky-footer__link search-toggler" href="javascript:void(0);">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 768 768">
                    <g id="icomoon-ignore">
                    </g>
                    <path fill="currentColor"
                        d="M513.312 507.392c-1.088 0.832-2.144 1.76-3.168 2.784s-1.92 2.048-2.784 3.168c-40.256 38.816-95.008 62.656-155.36 62.656-61.856 0-117.824-25.024-158.4-65.6s-65.6-96.544-65.6-158.4 25.024-117.824 65.6-158.4 96.544-65.6 158.4-65.6 117.824 25.024 158.4 65.6 65.6 96.544 65.6 158.4c0 60.352-23.84 115.104-62.688 155.392zM694.624 649.376l-117.6-117.6c39.392-49.28 62.976-111.776 62.976-179.776 0-79.52-32.256-151.552-84.352-203.648s-124.128-84.352-203.648-84.352-151.552 32.256-203.648 84.352-84.352 124.128-84.352 203.648 32.256 151.552 84.352 203.648 124.128 84.352 203.648 84.352c68 0 130.496-23.584 179.776-62.976l117.6 117.6c12.512 12.512 32.768 12.512 45.248 0s12.512-32.768 0-45.248z">
                    </path>
                </svg>

            </a>
        </li>
        <li class="col">
            <a class="sticky-footer__link cart-toggler" href="javascript:void(0);">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 0 512 448">
                    <g id="icomoon-ignore">
                    </g>
                    <path fill="currentColor"
                        d="M479.946 192.007c17.745 0 31.993 14.247 31.993 31.993s-14.247 31.993-31.993 31.993h-3.749l-28.743 165.46c-2.749 15.246-15.996 26.494-31.493 26.494h-319.923c-15.497 0-28.743-11.247-31.493-26.494l-28.743-165.46h-3.749c-17.745 0-31.993-14.247-31.993-31.993s14.247-31.993 31.993-31.993h447.892zM121.282 391.959c8.748-0.75 15.497-8.498 14.746-17.246l-7.998-103.975c-0.75-8.748-8.498-15.497-17.246-14.746s-15.497 8.498-14.746 17.246l7.998 103.975c0.75 8.248 7.748 14.746 15.996 14.746h1.249zM224.008 375.964v-103.975c0-8.748-7.248-15.996-15.996-15.996s-15.996 7.248-15.996 15.996v103.975c0 8.748 7.248 15.996 15.996 15.996s15.996-7.248 15.996-15.996zM319.985 375.964v-103.975c0-8.748-7.248-15.996-15.996-15.996s-15.996 7.248-15.996 15.996v103.975c0 8.748 7.248 15.996 15.996 15.996s15.996-7.248 15.996-15.996zM407.964 377.213l7.998-103.975c0.75-8.748-5.999-16.496-14.746-17.246s-16.496 5.999-17.246 14.746l-7.998 103.975c-0.75 8.748 5.999 16.496 14.746 17.246h1.25c8.248 0 15.246-6.498 15.996-14.746zM119.033 73.036l-23.244 102.976h-32.992l25.244-110.224c6.498-29.242 32.242-49.738 62.235-49.738h41.74c0-8.748 7.248-15.996 15.996-15.996h95.977c8.748 0 15.996 7.248 15.996 15.996h41.74c29.993 0 55.737 20.495 62.235 49.738l25.244 110.224h-32.992l-23.244-102.976c-3.499-14.746-16.246-24.994-31.243-24.994h-41.74c0 8.748-7.248 15.996-15.996 15.996h-95.977c-8.748 0-15.996-7.248-15.996-15.996h-41.74c-14.997 0-27.744 10.248-31.243 24.994z">
                    </path>
                </svg>
                <span class="counter qty empty">
                    <span class="counter-number">0</span>
                </span>
            </a>
        </li>
        <li class="col">
            <a class="sticky-footer__link" href="{{ Route('customer.profile') }}">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 768 768">
                    <g id="icomoon-ignore">
                    </g>
                    <path fill="currentColor"
                        d="M544 672v-64c0-44.16-17.952-84.224-46.848-113.152s-68.992-46.848-113.152-46.848h-224c-44.16 0-84.224 17.952-113.152 46.848s-46.848 68.992-46.848 113.152v64c0 17.664 14.336 32 32 32s32-14.336 32-32v-64c0-26.528 10.72-50.464 28.128-67.872s41.344-28.128 67.872-28.128h224c26.528 0 50.464 10.72 67.872 28.128s28.128 41.344 28.128 67.872v64c0 17.664 14.336 32 32 32s32-14.336 32-32zM432 224c0-44.16-17.952-84.224-46.848-113.152s-68.992-46.848-113.152-46.848-84.224 17.952-113.152 46.848-46.848 68.992-46.848 113.152 17.952 84.224 46.848 113.152 68.992 46.848 113.152 46.848 84.224-17.952 113.152-46.848 46.848-68.992 46.848-113.152zM368 224c0 26.528-10.72 50.464-28.128 67.872s-41.344 28.128-67.872 28.128-50.464-10.72-67.872-28.128-28.128-41.344-28.128-67.872 10.72-50.464 28.128-67.872 41.344-28.128 67.872-28.128 50.464 10.72 67.872 28.128 28.128 41.344 28.128 67.872zM521.376 374.624l64 64c12.512 12.512 32.768 12.512 45.248 0l128-128c12.512-12.512 12.512-32.768 0-45.248s-32.768-12.512-45.248 0l-105.376 105.376-41.376-41.376c-12.512-12.512-32.768-12.512-45.248 0s-12.512 32.768 0 45.248z">
                    </path>
                </svg>
            </a>
        </li>
    </ul>
</div>
<!-- End Sticky Footer -->

<button type="button" class="btn scrollTop">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" viewBox="0 0 640 640">
        <g id="icomoon-ignore">
        </g>
        <path fill="currentColor"
            d="M384 371.2v179.2l96 57.6v32h-320v-32l96-57.6v-179.2l-256 76.8v-64l256-153.6v-166.4c0-35.346 28.654-64 64-64s64 28.654 64 64v0 166.4l256 153.6v64l-256-76.8z">
        </path>
    </svg>
</button>

<div class="overlay"></div>
<div class="overlay2"></div>
<div class="overlay3"></div>
<div class="overlay4"></div>
<!-- End Overlay -->

<!-- Modal -->
<div class="modal fade" id="view_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fal fa-times"></i></button>
                <div id="modal_content"></div>
            </div>
        </div>
    </div>
</div>
