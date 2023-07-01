<?php
use App\Models\Section;
$sections = Section::sections();
$totalCartItems = totalCartItems();
?>
<!-- Header -->
<header>
    <!-- Top-Header -->
    <div class="full-layer-outer-header">
        <div class="container clearfix">
            <nav>
                <ul class="primary-nav g-nav">
                    <li>
                        <a href="tel:+111222333">
                            <i class="fas fa-phone u-c-brand u-s-m-r-9"></i>
                            Telephone:+111-222-333</a>
                    </li>
                    <li>
                        <a href="mailto:info@sitemakers.in">
                            <i class="fas fa-envelope u-c-brand u-s-m-r-9"></i>
                            E-mail: info@sitemakers.in
                        </a>
                    </li>
                </ul>
            </nav>
            <nav>
                <ul class="secondary-nav g-nav">
                    <li>
                        <a>@if(Auth::check()) My Account @else Login/Register @endif
                            <i class="fas fa-chevron-down u-s-m-l-9"></i>
                        </a>
                        <ul class="g-dropdown" style="width:200px">
                            <li>
                                <a href="cart.html">
                                    <i class="fas fa-cog u-s-m-r-9"></i>
                                    My Cart</a>
                            </li>
                            <li>
                                <a href="wishlist.html">
                                    <i class="far fa-heart u-s-m-r-9"></i>
                                    My Wishlist</a>
                            </li>
                            <!-- <li>
                                <a href="checkout.html">
                                    <i class="far fa-check-circle u-s-m-r-9"></i>
                                    Checkout</a>
                            </li> -->
                            @if(Auth::check())
                                <li>
                                    <a href="{{ url('/user/account') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        My Account</a>
                                </li>
                                <li>
                                    <a href="{{ url('/user/orders') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        My Orders</a>
                                </li>
                                <li>
                                    <a href="{{ url('/user/logout') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Logout</a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ url('/user/login-register') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Customer Login</a>
                                </li>
                                <li>
                                    <a href="{{ url('/vendor/login-register') }}">
                                        <i class="fas fa-sign-in-alt u-s-m-r-9"></i>
                                        Vendor Login</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    <li>
                        <a>USD
                            <i class="fas fa-chevron-down u-s-m-l-9"></i>
                        </a>
                        <ul class="g-dropdown" style="width:90px">
                            <li>
                                <a href="#" class="u-c-brand">($) USD</a>
                            </li>
                            <li>
                                <a href="#">(£) GBP</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a>ENG
                            <i class="fas fa-chevron-down u-s-m-l-9"></i>
                        </a>
                        <ul class="g-dropdown" style="width:70px">
                            <li>
                                <a href="#" class="u-c-brand">ENG</a>
                            </li>
                            <li>
                                <a href="#">ARB</a>
                            </li>
                        </ul>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Top-Header /- -->
    <!-- Mid-Header -->
    <div class="full-layer-mid-header">
        <div class="container">
            <div class="row clearfix align-items-center">
                <div class="col-lg-3 col-md-9 col-sm-6">
                    <div class="brand-logo text-lg-center">
                        <a href="{{ url('/') }}">
                            <h5>Online Station</h5>
                            <!-- <img src="{{ asset('front/images/main-logo/stack-developers-logo.png') }}" alt="Stack Developers" class="app-brand-logo"> -->
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 u-d-none-lg">
                    <form class="form-searchbox">
                        <label class="sr-only" for="search-landscape">Search</label>
                        <input id="search-landscape" type="text" class="text-field" placeholder="Search everything">
                        <div class="select-box-position">
                            <div class="select-box-wrapper select-hide">
                                <label class="sr-only" for="select-category">Choose category for search</label>
                                <select class="select-box" id="select-category">
                                    <option selected="selected" value="">
                                        All
                                    </option>
                                    @foreach($sections as $section)
                                    <option value="">{{ $section['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button id="btn-search" type="submit" class="button button-primary fas fa-search"></button>
                    </form>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <nav>
                        <ul class="mid-nav g-nav">
                            <li class="u-d-none-lg">
                                <a href="index.html">
                                    <i class="ion ion-md-home u-c-brand"></i>
                                </a>
                            </li>
                            <li class="u-d-none-lg">
                                <a href="wishlist.html">
                                    <i class="far fa-heart"></i>
                                </a>
                            </li>
                            <li>
                                <a id="mini-cart-trigger">
                                    <i class="ion ion-md-basket"></i>
                                    <span class="item-counter totalCartItems">{{ $totalCartItems }}</span>
                                    <!-- <span class="item-price">$220.00</span> -->
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Mid-Header /- -->
    <!-- Responsive-Buttons -->
    <div class="fixed-responsive-container">
        <div class="fixed-responsive-wrapper">
            <button type="button" class="button fas fa-search" id="responsive-search"></button>
        </div>
        <div class="fixed-responsive-wrapper">
            <a href="wishlist.html">
                <i class="far fa-heart"></i>
                <span class="fixed-item-counter">4</span>
            </a>
        </div>
    </div>
    <!-- Responsive-Buttons /- -->
    <!-- Mini Cart -->
    <div id="appendHeaderCartItems">
        @include('front.layout.header_cart_items')
    </div>
    <!-- Mini Cart /- -->
    <!-- Bottom-Header -->
    <div class="full-layer-bottom-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="v-menu v-close">
                        <span class="v-title">
                            <i class="ion ion-md-menu"></i>
                            All Categories
                            <i class="fas fa-angle-down"></i>
                        </span>
                        <nav>
                            <div class="v-wrapper">
                                <ul class="v-list animated fadeIn">
                                    @foreach($sections as $section)
                                        @if(count($section['categories'])>0)
                                            <li class="js-backdrop">
                                                <a href="javascript:;">
                                                    <i class="ion-ios-add-circle"></i>
                                                    {{ $section['name'] }}
                                                    <i class="ion ion-ios-arrow-forward"></i>
                                                </a>
                                                <button class="v-button ion ion-md-add"></button>
                                                <div class="v-drop-right" style="width: 700px;">
                                                    <div class="row">
                                                        @foreach($section['categories'] as $category)
                                                        <div class="col-lg-4">
                                                            <ul class="v-level-2">
                                                                <li>
                                                                    <a href="{{ url($category['url']) }}">{{ $category['category_name'] }}</a>
                                                                    <ul>
                                                                        @foreach($category['subcategories'] as $subcategory)
                                                                        <li>
                                                                            <a href="{{ url($subcategory['url']) }}">{{ $subcategory['category_name'] }}</a>
                                                                        </li>
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                    <li>
                                        <a class="v-more">
                                            <i class="ion ion-md-add"></i>
                                            <span>View More</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-9">
                    <ul class="bottom-nav g-nav u-d-none-lg">
                        <li>
                            <a href="listing-without-filters.html">New Arrivals
                                <span class="superscript-label-new">NEW</span>
                            </a>
                        </li>
                        <li>
                            <a href="listing-without-filters.html">Best Seller
                                <span class="superscript-label-hot">HOT</span>
                            </a>
                        </li>
                        <li>
                            <a href="listing-without-filters.html">Featured
                            </a>
                        </li>
                        <li>
                            <a href="listing-without-filters.html">Discounted
                                <span class="superscript-label-discount">-30%</span>
                            </a>
                        </li>
                        <li class="mega-position">
                            <a>More
                                <i class="fas fa-chevron-down u-s-m-l-9"></i>
                            </a>
                            <div class="mega-menu mega-3-colm">
                                <ul>
                                    <li class="menu-title">COMPANY</li>
                                    <li>
                                        <a href="about.html" class="u-c-brand">About Us</a>
                                    </li>
                                    <li>
                                        <a href="contact.html">Contact Us</a>
                                    </li>
                                    <li>
                                        <a href="faq.html">FAQ</a>
                                    </li>
                                </ul>
                                <ul>
                                    <li class="menu-title">COLLECTION</li>
                                    <li>
                                        <a href="cart.html">Men Clothing</a>
                                    </li>
                                    <li>
                                        <a href="checkout.html">Women Clothing</a>
                                    </li>
                                    <li>
                                        <a href="account.html">Kids Clothing</a>
                                    </li>
                                </ul>
                                <ul>
                                    <li class="menu-title">ACCOUNT</li>
                                    <li>
                                        <a href="shop-v1-root-category.html">My Account</a>
                                    </li>
                                    <li>
                                        <a href="shop-v1-root-category.html">My Profile</a>
                                    </li>
                                    <li>
                                        <a href="listing.html">My Orders</a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Bottom-Header /- -->
</header>
<!-- Header /- -->