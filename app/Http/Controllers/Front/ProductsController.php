<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\DeliveryAddress;
use App\Models\Vendor;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Coupon;
use App\Models\User;
use App\Models\Order;
use App\Models\OrdersProduct;
use Session;
use Auth;
use DB;

class ProductsController extends Controller
{
    public function listing(Request $request){
        if($request->ajax()){
            $data = $request->all();

            $url = $data['url'];
            $_GET['sort'] = $data['sort'];
            $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();

            if($categoryCount>0){
                $categoryDetails = Category::categoryDetails($url);

                $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->where('status',1);

                // sorting 
                if(isset($_GET['sort']) && !empty($_GET['sort'])){
                    if($_GET['sort']=="product_latest"){
                        $categoryProducts->orderby('products.id','Desc');
                    }else if($_GET['sort']=="price_lowest"){
                        $categoryProducts->orderby('products.product_price','Asc');
                    }else if($_GET['sort']=="price_highest"){
                        $categoryProducts->orderby('products.product_price','Desc');
                    }else if($_GET['sort']=="name_a_z"){
                        $categoryProducts->orderby('products.product_name','Asc');
                    }else if($_GET['sort']=="name_z_a"){
                        $categoryProducts->orderby('products.product_name','Desc');
                    }
                }

                $categoryProducts = $categoryProducts->paginate(15);

                return view ('front.products.ajax_products_listing')->with(compact('categoryDetails','categoryProducts','url'));
            }else{
                abort(404);
            }
        }else{
            if(isset($_REQUEST['search']) && !empty($_REQUEST['search'])){
                if($_REQUEST['search']=="new-arrivals"){
                    $search_product = $_REQUEST['search'];
                    $categoryDetails['breadcrumbs'] = "New Arrival Products";
                    $categoryDetails['categoryDetails']['category_name'] = "New Arrival Products";
                    $categoryDetails['categoryDetails']['description'] = "New Arrival Products";
                    $categoryProducts = Product::select('products.id','products.section_id','products.category_id','products.brand_id','products.vendor_id','products.product_name','products.product_code','products.product_color','products.product_price','products.product_discount','products.product_image','products.description')->with('brand')->join('categories','categories.id','=','products.category_id')->where('products.status',1)->orderBy('id','Desc');
                }else if($_REQUEST['search']=="featured"){
                    $search_product = $_REQUEST['search'];
                    $categoryDetails['breadcrumbs'] = "Featured Products";
                    $categoryDetails['categoryDetails']['category_name'] = "Featured Products";
                    $categoryDetails['categoryDetails']['description'] = "Featured Products";
                    $categoryProducts = Product::select('products.id','products.section_id','products.category_id','products.brand_id','products.vendor_id','products.product_name','products.product_code','products.product_color','products.product_price','products.product_discount','products.product_image','products.description')->with('brand')->join('categories','categories.id','=','products.category_id')->where('products.status',1)->where('products.is_featured','Yes');
                }else if($_REQUEST['search']=="discounted"){
                    $search_product = $_REQUEST['search'];
                    $categoryDetails['breadcrumbs'] = "Discounted Products";
                    $categoryDetails['categoryDetails']['category_name'] = "Discounted Products";
                    $categoryDetails['categoryDetails']['description'] = "Discounted Products";
                    $categoryProducts = Product::select('products.id','products.section_id','products.category_id','products.brand_id','products.vendor_id','products.product_name','products.product_code','products.product_color','products.product_price','products.product_discount','products.product_image','products.description')->with('brand')->join('categories','categories.id','=','products.category_id')->where('products.status',1)->where('products.product_discount','>',0);
                }else{
                    $search_product = $_REQUEST['search'];
                    $categoryDetails['breadcrumbs'] = $search_product;
                    $categoryDetails['categoryDetails']['category_name'] = $search_product;
                    $categoryDetails['categoryDetails']['description'] = "Search Product for ".$search_product;
                    $categoryProducts = Product::select('products.id','products.section_id','products.category_id','products.brand_id','products.vendor_id','products.product_name','products.product_code','products.product_color','products.product_price','products.product_discount','products.product_image','products.description')->with('brand')->join('categories','categories.id','=','products.category_id')->where(function($query)use($search_product){
                        $query->where('products.product_name','like','%'.$search_product.'%')
                        ->orwhere('products.product_code','like','%'.$search_product.'%')
                        ->orwhere('products.product_color','like','%'.$search_product.'%')
                        ->orwhere('products.description','like','%'.$search_product.'%')
                        ->orwhere('categories.category_name','like','%'.$search_product.'%');
                    })->where('products.status',1);
                }
                
                if(isset($_REQUEST['section_id']) && !empty($_REQUEST['section_id'])){
                    $categoryProducts = $categoryProducts->where('products.section_id',$_REQUEST['section_id']);
                }
                $categoryProducts = $categoryProducts->get();
                return view ('front.products.listing')->with(compact('categoryDetails','categoryProducts'));
            }else{
                $url = Route::getFacadeRoot()->current()->uri();
                $categoryCount = Category::where(['url'=>$url,'status'=>1])->count();

                if($categoryCount>0){
                    $categoryDetails = Category::categoryDetails($url);

                    $categoryProducts = Product::with('brand')->whereIn('category_id',$categoryDetails['catIds'])->where('status',1);

                    // sorting 
                    if(isset($_GET['sort']) && !empty($_GET['sort'])){
                        if($_GET['sort']=="product_latest"){
                            $categoryProducts->orderby('products.id','Desc');
                        }else if($_GET['sort']=="price_lowest"){
                            $categoryProducts->orderby('products.product_price','Asc');
                        }else if($_GET['sort']=="price_highest"){
                            $categoryProducts->orderby('products.product_price','Desc');
                        }else if($_GET['sort']=="name_a_z"){
                            $categoryProducts->orderby('products.product_name','Asc');
                        }else if($_GET['sort']=="name_z_a"){
                            $categoryProducts->orderby('products.product_name','Desc');
                        }
                    }

                    $categoryProducts = $categoryProducts->paginate(15);

                    return view ('front.products.listing')->with(compact('categoryDetails','categoryProducts','url'));
                }else{
                    abort(404);
                }
            }
        }       
    }

    public function vendorListing($vendorid){
        //get vendor shop
        $getVendorShop = Vendor::getVendorShop($vendorid);
        // get vendor products
        $vendorProducts = Product::with('brand')->where('vendor_id',$vendorid)->where('status',1);
        $vendorProducts = $vendorProducts->paginate(15);

        return view('front.products.vendor_listing')->with(compact('getVendorShop','vendorProducts'));
    }

    public function detail($id){
        $productDetails = Product::with(['section','category','brand','attributes'=>function($query){
            $query->where('stock','>',0)->where('status',1);
        },'images','vendor'])->find($id)->toArray();
        // dd($productDetails); die;
        $categoryDetails = Category::categoryDetails($productDetails['category']['url']);
        // dd($categoryDetails); die;

        // get similar product
        $similarProducts = Product::with('brand')->where('category_id',$productDetails['category']['id'])->where('id','!=',$id)->limit(4)->inRandomOrder()->get()->toArray();
        // dd($similarProducts); die;
        $totalStock = ProductsAttribute::where('product_id',$id)->sum('stock');
        return view('front.products.detail')->with(compact('productDetails','categoryDetails','totalStock','similarProducts'));
    }

    public function getProductPrice(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'],$data['size']);
            return $getDiscountAttributePrice;
        }
    }

    public function cartAdd(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // check product is stock or not
            $getProductStock = ProductsAttribute::getProductStock($data['product_id'],$data['size']);

            if($getProductStock<$data['quantity']){
                return redirect()->back()->with('error_message','Required quantity is not available');
            }

            // generate session id
            $session_id = Session::get('session_id');
            if(empty($session_id)){
                $session_id = Session::getId();
                Session::put('session_id',$session_id);
            }

            //check product is in cart or not
            if(Auth::check()){
                // user is logged in
                $user_id = Auth::user()->id;
                $countProducts = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],'user_id'=>$user_id])->count();
            }else{
                // user is not logged in
                $user_id = 0;
                $countProducts = Cart::where(['product_id'=>$data['product_id'],'size'=>$data['size'],'session_id'=>$session_id])->count();
            }

            if($countProducts>0){
                return redirect()->back()->with('error_message','Product already in cart');
            }

            // save product in cart table
            $item = new Cart;
            $item->session_id = $session_id;
            $item->user_id = $user_id;
            $item->product_id = $data['product_id'];
            $item->size = $data['size'];
            $item->quantity = $data['quantity'];

            $item->save();

            return redirect()->back()->with('success_message','Product has beed added in cart');
        }
    }

    public function cart(Request $request){

        $getCartItems = Cart::getCartItems();
        // dd($getCartItems);

        return view('front.products.cart')->with(compact('getCartItems'));
    }

    public function cartUpdate(Request $request){
        if($request->ajax()){
            $data = $request->all();

            // get cart details
            $cartDetails = Cart::find($data['cartid']);

            // get available product stock
            $availableStock = ProductsAttribute::select('stock')->where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size']])->first()->toArray();
            // echo "<pre>"; print_r($availableStock); die;

            //check stock is available or not for user
            if($data['qty']>$availableStock['stock']){
                $getCartItems = Cart::getCartItems();

                return response()->json(['status'=>false,'message'=>'Out of Stock','view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))]);
            }

            // check size available or not
            $availableSize = ProductsAttribute::where(['product_id'=>$cartDetails['product_id'],'size'=>$cartDetails['size'],'status'=>1])->count();
            if($availableSize==0){
                $getCartItems = Cart::getCartItems();

                return response()->json(['status'=>false,'message'=>'Size is out of Stock','view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))]);
            }

            //update quantity
            Cart::where('id',$data['cartid'])->update(['quantity'=>$data['qty']]);
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            // destroy session
            Session::forget('couponAmount');
            Session::forget('couponCode');
            return response()->json(['status'=>true,'totalCartItems'=>$totalCartItems,'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems')),'headerview'=>(String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))]);
        }
    }

    public function cartDelete(Request $request){
        if($request->ajax()){
            // destroy session
            Session::forget('couponAmount');
            Session::forget('couponCode');
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            Cart::where('id',$data['cartid'])->delete();
            
            $getCartItems = Cart::getCartItems();
            $totalCartItems = totalCartItems();
            return response()->json(['totalCartItems'=>$totalCartItems,'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems')),'headerview'=>(String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))]);

        }
    }

    public function checkout(Request $request){
        
        $deliveryAddresses = DeliveryAddress::deliveryAddresses();
        $countries = Country::where('status',1)->get()->toArray();
        // dd($deliveryAddresses); die;
        $getCartItems = Cart::getCartItems();
        // dd($getCartItems);

        if(count($getCartItems)==0){
            $message = 'No products in cart. Add product to checkout!';

            return redirect('cart')->with('error_message',$message);
        }
        
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // web security
            foreach ($getCartItems as $item) {
                // prevent disabled product
                $product_status = Product::getProductStatus($item['product_id']);
                if($product_status==0){
                    // Product::deleteCartProduct($item['product_id']);
                    // $message = "One of the product is disabled! Try another..";
                    $message = $item['product']['product_name']." with ".$item['size']." size is not available. Try another..";

                    return redirect('/cart')->with('error_message',$message);
                }

                // prevent sold out product
                $getProductStock = ProductsAttribute::getProductStock($item['product_id'],$item['size']);
                if($getProductStock==0){
                    // Product::deleteCartProduct($item['product_id']);
                    // $message = "One of the product is sold out! Try another..";
                    $message = $item['product']['product_name']." with ".$item['size']." size is not available. Try another..";
                    
                    return redirect('/cart')->with('error_message',$message);
                }

                // prevent disabled attibute product
                $getAttributeStatus = ProductsAttribute::getAttributeStatus($item['product_id'],$item['size']);
                if($getAttributeStatus==0){
                    // Product::deleteCartProduct($item['product_id']);
                    $message = $item['product']['product_name']." with ".$item['size']." size is not available. Try another..";
                    
                    return redirect('/cart')->with('error_message',$message);
                }

                // prevent disabled category product
                $getCategoryStatus = Category::getCategoryStatus($item['product']['category_id']);
                if($getCategoryStatus==0){
                    // Product::deleteCartProduct($item['product_id']);
                    $message = $item['product']['product_name']." with ".$item['size']." size is not available. Try another..";
                    
                    return redirect('/cart')->with('error_message',$message);
                }
            }

            // select delivery address
            if(empty($data['address_id'])){
                $message = "Please select Delivery address";

                return redirect()->back()->with('error_message',$message);
            }

            // paymed method validation
            if(empty($data['payment_geteway'])){
                $message = "Please select Payment method";

                return redirect()->back()->with('error_message',$message);
            }

            // accept validation
            if(empty($data['accept'])){
                $message = "Please agree to the trams and conditions";

                return redirect()->back()->with('error_message',$message);
            }
            // echo "<pre>"; print_r($data); die;

            // get delivary address
            $deliveryAddress = DeliveryAddress::where('id',$data['address_id'])->first()->toArray();
            // dd($deliveryAddress);

            // set payment method as COD otherwise set as prepaid
            if($data['payment_geteway']=="COD"){
                $payment_method = "COD";
                $order_status = "New";
            }else{
                $payment_method = "Prepaid";
                $order_status = "Pending";
            }

            DB::beginTransaction();

            // fetch total price
            $total_price = 0;
            foreach($getCartItems as $item){
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'],$item['size']);
                $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']);
            }

            // calculate shipping
            $shipping_charges = 0;

            // calculate total
            $grand_total = $total_price + $shipping_charges - Session::get('couponAmount');
            

            // insert total in session
            Session::put('grand_total',$grand_total);

            //insert order details
            $order = new Order;
            $order->user_id = Auth::user()->id;
            $order->name = $deliveryAddress['name'];
            $order->address = $deliveryAddress['address'];
            $order->city = $deliveryAddress['city'];
            $order->state = $deliveryAddress['state'];
            $order->country = $deliveryAddress['country'];
            $order->pincode = $deliveryAddress['pincode'];
            $order->mobile = $deliveryAddress['mobile'];
            $order->email = Auth::user()->email;
            $order->shipping_charges = $shipping_charges;
            $order->coupon_code = Session::get('couponCode');
            $order->coupon_amount = Session::get('couponAmount');
            $order->order_status = $order_status;
            $order->payment_method = $payment_method;
            $order->payment_gateway = $data['payment_geteway'];
            $order->grand_total = $grand_total;
            $order->save();
            $order_id = DB::getPdo()->lastInsertId();

            foreach($getCartItems as $item){
                $cartItem = new OrdersProduct;
                $cartItem->order_id = $order_id;
                $cartItem->user_id = Auth::user()->id;
                $getProductDetails = Product::select('product_code','product_name','product_color','admin_id','vendor_id')->where('id',$item['product_id'])->first()->toArray();
                // dd($getProductDetails);
                $cartItem->admin_id = $getProductDetails['admin_id'];
                $cartItem->vendor_id = $getProductDetails['vendor_id'];
                $cartItem->product_id = $item['product_id'];
                $cartItem->product_code = $getProductDetails['product_code'];
                $cartItem->product_name = $getProductDetails['product_name'];
                $cartItem->product_color = $getProductDetails['product_color'];
                $cartItem->product_size = $item['size'];
                $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'],$item['size']);
                $cartItem->product_price = $getDiscountAttributePrice['final_price'];
                $cartItem->product_qty = $item['quantity'];
                $cartItem->item_status = "New";
                $cartItem->save();

                // reduce stock
                $getProductStock = ProductsAttribute::getProductStock($item['product_id'],$item['size']);
                $newStock = $getProductStock - $item['quantity'];
                ProductsAttribute::where(['product_id'=>$item['product_id'],'size'=>$item['size']])->update(['stock'=>$newStock]);
            }

            // insert order id in session
            Session::put('order_id',$order_id);

            DB::commit();

            return redirect('thanks');

        }
        
        return view('front.products.checkout')->with(compact('deliveryAddresses','countries','getCartItems'));
    }

    public function thanks(){
        if(Session::has('order_id')){
            //empty cart
            Cart::where('user_id',Auth::user()->id)->delete();
            Session::forget('couponAmount');
            Session::forget('couponCode');
            return view('front.products.thanks');
        }else{
            return view('cart');
        }
        
    }

    public function applyCoupon(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // destroy session
            Session::forget('couponAmount');
            Session::forget('couponCode');
            
            $getCartItems = Cart::getCartItems();
            // echo "<pre>"; print_r($getCartItems); die;
            $totalCartItems = totalCartItems();
            $couponCount = Coupon::where('coupon_code',$data['code'])->count();
            if($couponCount==0){
                return response()->json(['status'=>false,'totalCartItems'=>$totalCartItems,'message'=>'This coupon is invalid!','view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems')),'headerview'=>(String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))]);
            }else{
                // check other condition
                //get coupon details
                $couponDetails = Coupon::where('coupon_code',$data['code'])->first();

                // coupon active or not
                if($couponDetails->status==0){
                    $message = "This coupon is inactive";
                }

                // coupon is expire or not
                $expiry_date = $couponDetails->expiry_date;
                $current_date = date('Y-m-d');
                if($expiry_date<$current_date){
                    $message = "The Coupon is expired!";
                }

                // coupon is for that category or not
                // get selected categories
                $total_amount = 0;
                $catArr = explode(",",$couponDetails->categories);
                foreach($getCartItems as $key => $item){
                    if(!in_array($item['product']['category_id'],$catArr)){
                        $message = "This coupon is not for one of the selected products!";
                    }
                    $attrPrice = Product::getDiscountAttributePrice($item['product_id'],$item['size']);
                    $total_amount = $total_amount + ($attrPrice['final_price']*$item['quantity']);
                }

                // coupon is for that category or not
                //get users
                if(isset($couponDetails->users)&&!empty($couponDetails->users)){
                    $usersArr = explode(",",$couponDetails->users);

                    if(count($usersArr)){
                        foreach($usersArr as $key => $user){
                            $getUserId = User::select('id')->where('email',$user)->first()->toArray();
                            $usersId[] = $getUserId['id'];
                        }

                        foreach($getCartItems as $item){
                            if(!in_array($item['user_id'],$usersId)){
                                $message = "This coupon is not for you!";
                            }
                        }
                    }
                }

                // check coupon belongs to vendor
                if($couponDetails->vendor_id>0){
                    $productIds = Product::select('id')->where('vendor_id',$couponDetails->vendor_id)->pluck('id')->toArray();
                    // echo "<pre>"; print_r($productIds); die;
                    foreach($getCartItems as $item){
                        if(!in_array($item['product']['id'],$productIds)){
                            $message = "This coupon is not for you!(v)";
                        }
                    }
                }

                // if any error occur
                if(isset($message)){
                    return response()->json(['status'=>false,'totalCartItems'=>$totalCartItems,'message'=>$message,'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems')),'headerview'=>(String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))]);
                }else{
                    // if the coupon is correct
                    // if amount type fix or parcentage
                    if($couponDetails->amount_type=="Fixed"){
                        $couponAmount = $couponDetails->amount;
                    }else{
                        $couponAmount = $total_amount * ($couponDetails->amount/100);
                    }

                    $grand_total = $total_amount - $couponAmount;

                    // add coupon code & amount in session
                    Session::put('couponAmount',$couponAmount);
                    Session::put('couponCode',$data['code']);

                    $message = "Coupon Code successfully applied. You are availing discount!";
                    
                    return response()->json(['status'=>true,'totalCartItems'=>$totalCartItems,'couponAmount'=>$couponAmount,'grand_total'=>$grand_total,'message'=>$message,'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems')),'headerview'=>(String)View::make('front.layout.header_cart_items')->with(compact('getCartItems'))]);
                }
            }
        }
    }
}
