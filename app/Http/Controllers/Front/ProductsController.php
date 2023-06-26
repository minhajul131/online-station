<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\Vendor;
use App\Models\Cart;
use Session;
use Auth;

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

            return response()->json(['status'=>true,'view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))]);
        }
    }

    public function cartDelete(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            Cart::where('id',$data['cartid'])->delete();
            
            $getCartItems = Cart::getCartItems();

            return response()->json(['view'=>(String)View::make('front.products.cart_items')->with(compact('getCartItems'))]);

        }
    }
}
