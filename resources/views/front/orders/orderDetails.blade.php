@extends('front.layout.layout')
@section('content')
<?php use App\Models\Product; ?>

<!-- Page Introduction Wrapper -->
<div class="page-style-a">
    <div class="container">
        <div class="page-intro">
            <h2>Order #{{ $orderDetails['id'] }} Details</h2>
            <ul class="bread-crumb">
                <li class="has-separator">
                    <i class="ion ion-md-home"></i>
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="is-marked">
                    <a href="{{ url('user/orders') }}">Orders</a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Introduction Wrapper /- -->
<!-- Cart-Page -->
<div class="page-cart u-s-p-t-80">
    <div class="container">
        <div class="row">
            <table class="table table-striped table-borderless table-hover">
                <tr class="table-success"><td colspan="2"><strong><i>Order Details</i></strong></td></tr>
                <tr><td>Order Date</td><td>{{ date('Y-m-d h:i:s', strtotime($orderDetails['created_at'])); }}</td></tr>
                <tr><td>Order Status</td><td>{{ $orderDetails['order_status'] }}</td></tr>
                <tr><td>Total Amount</td><td>৳ {{ $orderDetails['grand_total'] }}</td></tr>
                <tr><td>Shipping Charges</td><td>{{ $orderDetails['shipping_charges'] }}</td></tr>
                @if($orderDetails['coupon_code']!="")
                    <tr><td>Coupon Code</td><td>{{ $orderDetails['coupon_code'] }}</td></tr>
                    <tr><td>Coupon Amount</td><td>{{ $orderDetails['coupon_amount'] }}</td></tr>
                @endif
                <tr><td>Payment Method</td><td>{{ $orderDetails['payment_method'] }}</td></tr>
            </table>

            <table class="table table-hover table-striped">
                <tr class="table-success"><td colspan="6"><strong><i>Product Details</i></strong></td></tr>
                <tr>
                    <th>Product Image</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Product Size</th>
                    <th>Product Color</th>
                    <th>Product Quantity</th>
                </tr>
                @foreach($orderDetails['orders_products'] as $product)
                    <tr>
                        @php $getProductImage = Product::getProductImage($product['product_id']) @endphp
                        <td><a href="{{ url('product/'.$product['product_id']) }}"><img style="width:50px" src="{{ asset('front/images/product_images/small/'.$getProductImage) }}" alt=""></a></td>
                        <td>{{ $product['product_code'] }}</td>
                        <td>{{ $product['product_name'] }}</td>
                        <td>{{ $product['product_size'] }}</td>
                        <td>{{ $product['product_color'] }}</td>
                        <td>{{ $product['product_qty'] }}</td>
                    </tr>
                @endforeach
            </table>

            <table class="table table-striped table-borderless table-hover">
                <tr class="table-success"><td colspan="2"><strong><i>Delivery Address</i></strong></td></tr>
                <tr><td>Name</td><td>{{ $orderDetails['name'] }}</td></tr>
                <tr><td>Address</td><td>{{ $orderDetails['address'] }}</td></tr>
                <tr><td>City</td><td>{{ $orderDetails['city'] }}</td></tr>
                <tr><td>State</td><td>{{ $orderDetails['state'] }}</td></tr>
                <tr><td>Country</td><td>{{ $orderDetails['country'] }}</td></tr>
                <tr><td>Pincode</td><td>{{ $orderDetails['pincode'] }}</td></tr>
                <tr><td>Mobile</td><td>{{ $orderDetails['mobile'] }}</td></tr>
            </table>
        </div>
    </div>
</div>
<!-- Cart-Page /- -->

@endsection