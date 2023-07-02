@extends('front.layout.layout')
@section('content')
<?php use App\Models\Product; ?>

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
    <div class="container">
        <div class="page-intro">
            <h2>Cart</h2>
            <ul class="bread-crumb">
                <li class="has-separator">
                    <i class="ion ion-md-home"></i>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="is-marked">
                    <a href="{{ url('cart') }}">Cart</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Cart-Page -->
<div class="page-cart u-s-p-t-80">
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
            <div class="col-lg-12">
                <div id="appendCartItems">
                    @include('front.products.cart_items')
                </div>
                <!-- Coupon -->
                <div class="coupon-continue-checkout u-s-m-b-60">
                    <div class="coupon-area">
                        <h6>Enter your coupon code if you have one.</h6>
                        <div class="coupon-field">
                            <form action="javascript:void(0);" id="ApplyCoupon" method="post" @if(Auth::check()) user="1" @endif>
                                @csrf
                                <label class="sr-only" for="code">Apply Coupon</label>
                                <input id="code" name="code" type="text" class="text-field" placeholder="Coupon Code">
                                <button type="submit" class="button">Apply Coupon</button>
                            </form>
                        </div>
                    </div>
                    <div class="button-area">
                        <a href="{{url('/')}}" class="continue">Continue Shopping</a>
                        <a href="{{url('/checkout')}}" class="checkout">Proceed to Checkout</a>
                    </div>
                </div>
                <!-- Coupon /- -->
            </div>
        </div>
    </div>
</div>
<!-- Cart-Page /- -->

@endsection