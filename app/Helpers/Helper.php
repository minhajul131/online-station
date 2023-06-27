<?php
use App\Models\Cart;

function totalCartItems(){
    if(Auth::check()){
        $user_id = Auth::user()->id;
        $totalCartItems = Cart::where('user_id',$user_id)->sum('quantity');
    }else{
        $session_id = Session::get('session_id');
        $totalCartItems = Cart::where('session_id',$session_id)->sum('quantity');
    }
    return $totalCartItems;
}

function getCartItems(){
    if(Auth::check()){
        // user is logged in and get user id
        $getCartItems = Cart::with(['product'=>function($query){
            $query->select('id','product_name','product_code','product_color','product_image');
        }])->orderby('id','Desc')->where('user_id',Auth::user()->id)->get()->toArray();
    }else{
        // user is not logged in and get session id
        $getCartItems = Cart::with(['product'=>function($query){
            $query->select('id','product_name','product_code','product_color','product_image');
        }])->orderby('id','Desc')->where('session_id',Session::get('session_id'))->get()->toArray();
    }
    return $getCartItems;
}