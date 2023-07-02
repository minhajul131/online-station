@extends('admin.layout.layout')
@section('content')
<?php use App\Models\Product; ?>

<div class="main-panel">
    <div class="content-wrapper">
        @if(Session::has('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success: </strong> {{ Session::get('success_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        @endif
        <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                    <h3 class="font-weight-bold">Order #{{ $orderDetails['id'] }} Details</h3>
                    <h6 class="font-weight-normal mb-0"><a href="{{ url('admin/orders') }}">Back to Orders</a></h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex">
                        <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                            <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                <a class="dropdown-item" href="#">January - March</a>
                                <a class="dropdown-item" href="#">March - June</a>
                                <a class="dropdown-item" href="#">June - August</a>
                                <a class="dropdown-item" href="#">August - November</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Details</h4>
                    <p>--------------------------------------------</p>
                    <div class="form-group">
                        <div class="form-group">                        
                            <label><b>Order Date:  </b></label>
                            <label>{{ date('Y-m-d h:i:s', strtotime($orderDetails['created_at'])); }}</label>
                        </div>
                        <div class="form-group">                        
                            <label><b>Order Status:  </b></label>
                            <label>{{ $orderDetails['order_status'] }}</label>
                        </div>
                        <div class="form-group">                        
                            <label><b>Total Amount:  </b></label>
                            <label>{{ $orderDetails['grand_total'] }}</label>
                        </div>
                        <div class="form-group">
                            <label><b>Order Shipping Charges:  </b></label>
                            <label>৳ {{ $orderDetails['shipping_charges'] }}</label>
                        </div>
                        @if(!empty($orderDetails['coupon_code']))
                            <div class="form-group">
                                <label><b>Coupon Code:  </b></label>
                                <label>{{ $orderDetails['coupon_code'] }}</label>
                            </div>
                            <div class="form-group">
                                <label><b>Coupon Amount:  </b></label>
                                <label>৳ {{ $orderDetails['coupon_amount'] }}</label>
                            </div>
                        @endif
                        <div class="form-group">
                            <label><b>Payment Method:  </b></label>
                            <label>{{ $orderDetails['payment_method'] }}</label>
                        </div>
                        <div class="form-group">
                            <label><b>Payment Gateway:  </b></label>
                            <label>{{ $orderDetails['payment_gateway'] }}</label>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div> 

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Customer Information</h4>
                    <p>--------------------------------------------</p>
                    <div class="form-group">
                        <label><b>Name:  </b></label>
                        <label>{{ $userDetails['name'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Address:  </b></label>
                        <label>{{ $userDetails['address'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>City:  </b></label>
                        <label>{{ $userDetails['city'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>State:  </b></label>
                        <label>{{ $userDetails['state'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Country:  </b></label>
                        <label>{{ $userDetails['country'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Pincode:  </b></label>
                        <label>{{ $userDetails['pincode'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Mobile:  </b></label>
                        <label>{{ $userDetails['mobile'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Email:  </b></label>
                        <label>{{ $userDetails['email'] }}</label>
                    </div>
                </div>
            </div>
        </div>    

        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Delivery Address</h4>
                    <p>--------------------------------------------</p>
                    <div class="form-group">
                        <label><b>Name:  </b></label>
                        <label>{{ $orderDetails['name'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Address:  </b></label>
                        <label>{{ $orderDetails['address'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>City:  </b></label>
                        <label>{{ $orderDetails['city'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>State:  </b></label>
                        <label>{{ $orderDetails['state'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Country:  </b></label>
                        <label>{{ $orderDetails['country'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Pincode:  </b></label>
                        <label>{{ $orderDetails['pincode'] }}</label>
                    </div>
                    <div class="form-group">
                        <label><b>Mobile:  </b></label>
                        <label>{{ $orderDetails['mobile'] }}</label>
                    </div>
                </div>
            </div>
        </div>    
        
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Update Order Status</h4>
                    <p>--------------------------------------------</p>
                    @if(Auth::guard('admin')->user()->trpe!="vendor")
                        <form action="{{ url('admin/update-order-status') }}" method="post">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $orderDetails['id'] }}">
                            <select name="order_status" required>
                                <option value="">Select</option>
                                @foreach($orderStatuses as $status)
                                    <option value="{{ $status['name'] }}" @if(!empty($orderDetails['order_status']) && $orderDetails['order_status'] == $status['name']) selected @endif>{{ $status['name'] }}</option>
                                @endforeach
                            </select>
                            <button type="submit">Update Status</button>
                        </form>
                    @else
                        This is not available for you..
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Ordered Products</h4>
                    <table class="table table-hover table-striped table-responsive">
                        <tr class="table-success"><td colspan="12"><strong><i>Product Details</i></strong></td></tr>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Product Size</th>
                            <th>Product Color</th>
                            <th>Product Quantity</th>
                            <th>Item Status</th>
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
                                <td>
                                <form action="{{ url('admin/update-order-item-status') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="order_item_id" value="{{ $product['id'] }}">
                                    <select name="order_item_status" required>
                                        <option value="">Select</option>
                                        @foreach($orderItemStatuses as $status)
                                            <option value="{{ $status['name'] }}" @if(!empty($product['item_status']) && $product['item_status'] == $status['name']) selected @endif>{{ $status['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit">Update Status</button>
                                </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    
                </div>
            </div>
        </div> 
    </div>
</div>
  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  @include('admin.layout.footer')
  <!-- partial -->

@endsection