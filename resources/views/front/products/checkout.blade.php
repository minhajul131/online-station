@extends('front.layout.layout')
@section('content')
<?php use App\Models\Product; ?>

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
    <div class="container">
        <div class="page-intro">
            <h2>Checkout</h2>
            <ul class="bread-crumb">
                <li class="has-separator">
                    <i class="ion ion-md-home"></i>
                    <a href="{{url('/')}}">Home</a>
                </li>
                <li class="is-marked">
                    <a href="{{url('/checkout')}}">Checkout</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Checkout-Page -->
<div class="page-checkout u-s-p-t-80">
    <div class="container">
        @if(Session::has('error_message'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error: </strong> {{ Session::get('error_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        
        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success: </strong> {{ Session::get('success_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif

        @if($errors->any())
            <div class= "alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="row">
                    <!-- Billing-&-Shipping-Details -->
                    <div class="col-lg-6" id="deliveryAddresses">
                        @include('front.products.delivery_addresses')
                    </div>
                    <!-- Billing-&-Shipping-Details /- -->
                    <!-- Checkout -->
                    <div class="col-lg-6">
                        <form action="{{ url('/checkout') }}" method="post" name="checkoutForm" id="checkoutForm">
                            @csrf
                            @if($deliveryAddresses>0)
                                <h4 class="section-h4">Delivery Details</h4>
                                @foreach($deliveryAddresses as $address)
                                    <div style="float:left; margin-right:5px;" class="control-group"><input type="radio" id="address{{ $address['id'] }}" name="address_id" value="{{ $address['id'] }}"></div>
                                    <div><label class="control-label">{{ $address['name'] }}, {{ $address['address'] }}, {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }}, {{ $address['pincode'] }}, {{ $address['mobile'] }}</label>
                                        <a style="float: right; margin-left: 10px;" href="javascript:;" data-addressid="{{ $address['id'] }}" class="removeAddress button-outline-secondary">R</a>&nbsp;&nbsp;&nbsp;
                                        <a style="float: right;" href="javascript:;" data-addressid="{{ $address['id'] }}" class="editAddress button-outline-secondary">E</a>&nbsp;&nbsp;&nbsp;
                                    </div>
                                @endforeach
                            @endif
                            <br>
                            <h4 class="section-h4">Your Order</h4>
                            <div class="order-table">
                                <table class="u-s-m-b-13">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $total_price = 0 @endphp
                                    @foreach($getCartItems as $item)
                                        <?php $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'],$item['size']); ?>
                                            <tr>
                                                <td>
                                                <a href="{{ url('product/'.$item['product_id']) }}">
                                                    <img style="width: 30px" src="{{ asset('front/images/product_images/small/'.$item['product']['product_image']) }}" alt="Product">
                                                    <h6 class="order-h6">{{ $item['product']['product_name'] }} - {{ $item['size'] }}</h6></a>
                                                    <span class="order-span-quantity">x {{ $item['quantity'] }}</span>
                                                </td>
                                                <td>
                                                    <h6 class="order-h6">৳ {{ $getDiscountAttributePrice['final_price'] * $item['quantity'] }}</h6>
                                                </td>
                                            </tr>
                                        @php $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']) @endphp
                                    @endforeach
                                        <tr>
                                            <td>
                                                <h3 class="order-h3">Subtotal</h3>
                                            </td>
                                            <td>
                                                <h3 class="order-h3">৳ {{ $total_price }}</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h3 class="order-h3">Shipping</h3>
                                            </td>
                                            <td>
                                                <h3 class="order-h3">৳ 0.00</h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h3 class="order-h3">Coupon Discount</h3>
                                            </td>
                                            <td>
                                                <h3 class="order-h3">
                                                    @if(Session::has('couponAmount'))
                                                        ৳ {{ Session::get('couponAmount') }}
                                                    @else
                                                        ৳  0
                                                    @endif
                                                </h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h3 class="order-h3">Total</h3>
                                            </td>
                                            <td>
                                                <h3 class="order-h3">৳ {{ $total_price -  Session::get('couponAmount') }}</h3>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="u-s-m-b-13">
                                    <input type="radio" class="radio-box" name="payment_geteway" id="cash-on-delivery" value="COD">
                                    <label class="label-text" for="cash-on-delivery">Cash on Delivery</label>
                                </div>
                                <div class="u-s-m-b-13">
                                    <input type="radio" class="radio-box" name="payment_geteway" id="bkash" value="Bkash">
                                    <label class="label-text" for="bkash">Bkash</label>
                                </div>
                                <div class="u-s-m-b-13">
                                    <input type="radio" class="radio-box" name="payment_geteway" id="nogod" value="Nogod">
                                    <label class="label-text" for="nogod">Nogod</label>
                                </div>
                                <div class="u-s-m-b-13">
                                    <input type="checkbox" class="check-box" id="accept" name="accept" value="Yes" title="Accept terms & conditions">
                                    <label class="label-text no-color" for="accept">I’ve read and accept the
                                        <a href="terms-and-conditions.html" class="u-c-brand">terms & conditions</a>
                                    </label>
                                </div>
                                <button type="submit" class="button button-outline-secondary">Place Order</button>
                            </div>
                        </form>
                    </div>
                    <!-- Checkout /- -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Checkout-Page /- -->

@endsection