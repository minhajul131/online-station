<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrdersProduct;
use Auth;

class OrderController extends Controller
{
    public function orders($id=null){
        if(empty($id)){
            $orders = Order::with('orders_products')->where('user_id',Auth::user()->id)->orderBy('id','Desc')->get()->toArray();
            
            return view('front.orders.orders')->with(compact('orders'));
        }else{
            $orderDetails = Order::with('orders_products')->where('id',$id)->first()->toArray();
            // dd($orderDetails); die;
            return view('front.orders.orderDetails')->with(compact('orderDetails'));
        }
    }
}
