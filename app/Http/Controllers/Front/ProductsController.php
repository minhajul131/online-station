<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\Vendor;

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
}
