<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Section;
use App\Models\Brand;

class ProductsController extends Controller
{
    public function products(){
        // Session::put('page','categories');
        // $products = Product::get()->toArray();
        $products = Product::with(['section'=>function($query){
            $query->select('id','name');
        },'category'=>function($query){
            $query->select('id','category_name');
        }])->get()->toArray();
        // dd($products);
        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Product::where('id',$data['product_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'product_id'=>$data['product_id']]);
        }
    }

    public function deleteProduct($id){
        Product::where('id',$id)->delete();
        $message = "Product has been deleted";
        return redirect()->back()->with('success_message',$message);
    }

    public function addEditProduct(Request $request, $id=null){
        if($id==""){
            $title = "Add product";
        }else{
            $title = "Edit product";
        }

        // get sections with categories and sub categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);

        $brands = Brand::where('status',1)->get()->toArray();

        return view('admin.products.add-edit-product')->with(compact('title','categories','brands'));
    }
}
