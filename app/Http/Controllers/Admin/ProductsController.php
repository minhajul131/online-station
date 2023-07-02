<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Section;
use App\Models\Brand;
use App\Models\ProductsAttribute;
use App\Models\ProductsImage;
use Auth;
use Image;
use Session;

class ProductsController extends Controller
{
    public function products(){
        Session::put('page','products');
        $adminType = Auth::guard('admin')->user()->trpe;
        $vendor_id = Auth::guard('admin')->user()->vendor_id;
        if($adminType=="vendor"){
            $vendorStatus = Auth::guard('admin')->user()->status;
            if($vendorStatus == 0){
                return redirect("admin/update-vendor-details/personal")->with('error_message','Your account is not approved yet. Provide your valid information');
            }
        }
        $products = Product::with(['section'=>function($query){
            $query->select('id','name');
        },'category'=>function($query){
            $query->select('id','category_name');
        }]);
        if($adminType=="vendor"){
            $products = $products->where('vendor_id',$vendor_id);
        }
        $products = $products->get()->toArray();
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
        Session::put('page','products');
        if($id==""){
            $title = "Add product";
            $product = new Product;
            $message = "Products Added";
        }else{
            $title = "Edit product";
            $product = Product::find($id);
            $message = "Products Updated";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r(Auth::guard('admin')->user()); die;

            $rules = [
                'category_id' => 'required',
                'product_name' => 'required',
                'product_code' => 'required',
                'product_price' => 'required|numeric',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u',
            ];

            $customMessages = [
                'category_id.required' =>'Category is required',
                'product_name.required' =>'Product name is required',
                // 'product_name.regex' =>'Valid Product name is required',
                'product_code.required' =>'Product code is required',
                // 'product_code.regex' =>'Valid Product code is required',
                'product_color.required' =>'Product color is required',
                'product_color.regex' =>'Valid Product color is required',
                'product_price.required' =>'Product price is required',
                'product_price.numeric' =>'Valid Product price is required',
            ];

            $this->validate($request,$rules,$customMessages);

            // upload image by sizing
            if($request->hasFile('product_image')){
                $image_tmp = $request->file('product_image');
                if($image_tmp->isValid()){
                    // Get image extension
                    $extension = $image_tmp->getClientOriginalExtension();
                    // generate new image name
                    $imageName = rand(111,99999).'.'.$extension;
                    $largeImagePath = 'front/images/product_images/large/'.$imageName;
                    $mediumImagePath = 'front/images/product_images/medium/'.$imageName;
                    $smallImagePath = 'front/images/product_images/small/'.$imageName;
                    //upload image
                    Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
                    Image::make($image_tmp)->resize(500,500)->save($mediumImagePath);
                    Image::make($image_tmp)->resize(250,250)->save($smallImagePath);
                    //insert image in product table
                    $product->product_image = $imageName;
                }
            }

            //upload video
            if($request->hasFile('product_video')){
                $video_tmp = $request->file('product_video');
                if($video_tmp->isValid()){
                    //upload video
                    $video_name = $video_tmp->getClientOriginalName();
                    $extension = $video_tmp->getClientOriginalExtension();
                    $videoName = $video_name.'-'.rand().'.'.$extension;
                    $videoPath = 'front/videos/product_videos/';
                    $video_tmp->move($videoPath,$videoName);
                    //insert video in products table
                    $product->product_video = $videoName;
                }
            }

            //save products details in products able
            $categoryDetails = Category::find($data['category_id']);
            $product->section_id = $categoryDetails['section_id'];
            $product->category_id = $data['category_id'];
            $product->brand_id = $data['brand_id'];

            if($id==""){
                $adminType = Auth::guard('admin')->user()->trpe;
                $vendor_id = Auth::guard('admin')->user()->vendor_id;
                $admin_id = Auth::guard('admin')->user()->id;

                $product->admin_type = $adminType;
                $product->admin_id = $admin_id;
                if($adminType=="vendor"){
                    $product->vendor_id = $vendor_id;
                }else{
                    $product->vendor_id = 0;
                }
            }
            
            if(empty($data['product_discount'])){
                $data['product_discount'] = 0;
            }
            if(empty($data['product_weight'])){
                $data['product_weight'] = 0;
            }

            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            $product->product_price = $data['product_price'];
            $product->product_discount = $data['product_discount'];
            $product->product_weight = $data['product_weight'];
            $product->description = $data['description'];
            $product->meta_title = $data['meta_title'];
            $product->meta_description = $data['meta_description'];
            $product->meta_keyword = $data['meta_keyword'];
            if(!empty($data['is_featured'])){
                $product->is_featured = $data['is_featured'];
            }else{
                $product->is_featured = "No";
            }

            $product->status = 1;
            $product->save();
            return redirect('admin/products')->with('success_message',$message);
        }

        // get sections with categories and sub categories
        $categories = Section::with('categories')->get()->toArray();
        // dd($categories);

        $brands = Brand::where('status',1)->get()->toArray();

        return view('admin.products.add-edit-product')->with(compact('title','categories','brands','product'));
    }

    public function deleteProductImage($id){
        //get product image from model
        $productImage = Product::select('product_image')->where('id',$id)->first();
        //get image path
        $small_image_path = 'front/images/product_images/small/';
        $medium_image_path = 'front/images/product_images/medium/';
        $large_image_path = 'front/images/product_images/large/';
        //delete product image
        if(file_exists($small_image_path.$productImage->product_image)){
            unlink($small_image_path.$productImage->product_image);
        }
        if(file_exists($medium_image_path.$productImage->product_image)){
            unlink($medium_image_path.$productImage->product_image);
        }
        if(file_exists($large_image_path.$productImage->product_image)){
            unlink($large_image_path.$productImage->product_image);
        }

        //delete image from table
        Product::where('id',$id)->update(['product_image'=>'']);

        $message = "Product Image has been deleted";
        return redirect()->back()->with('success_message',$message);
    }

    public function deleteProductVideo($id){
        // get product video 
        $productVideo = Product::select('product_video')->where('id',$id)->first();

        //get product video path
        $product_video_path = 'front/videos/product_videos/';

        //delete video from folder
        if(file_exists($product_video_path.$productVideo->product_video)){
            unlink($product_video_path.$productVideo->product_video);
        }

        //delete from table
        Product::where('id',$id)->update(['product_video'=>'']);

        $message = "Product Video has been deleted";
        return redirect()->back()->with('success_message',$message);
    }

    public function addAttributes(Request $request, $id){
        Session::put('page','products');
        $product = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('attributes')->find($id);

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;

            foreach ($data['sku'] as $key => $value) {
                if(!empty($value)){

                    // sku duplicate check
                    $skuCount = ProductsAttribute::where('sku',$value)->count();
                    if($skuCount>0){
                        return redirect()->back()->with('error_message','SKU exists');
                    }
                    //size count
                    // $sizeCount = ProductsAttribute::where(['product_id'=>$id,'size',$data['size'][$key]])->count();
                    // if($sizeCount>0){
                    //     return redirect()->back()->with('error_message','Size exists');
                    // }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $value;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->status = 1;

                    $attribute->save();
                }
            }
            return redirect()->back()->with('success_message','Product attribute added');
        }
        return view('admin.attributes.add-edit-attributes')->with(compact('product'));
    }

    public function updateAttributeStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsAttribute::where('id',$data['attribute_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'attribute_id'=>$data['attribute_id']]);
        }
    }

    public function editAttributes(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            foreach ($data['attributeId'] as $key => $attribute) {
                if(!empty($attribute)){
                    ProductsAttribute::where(['id'=>$data['attributeId'][$key]])->update(['price'=>$data['price'][$key],'stock'=>$data['stock'][$key]]);
                }
            }
            return redirect()->back()->with('success_message','Product attribute updated');
        }
    }

    public function addImages($id, Request $request){
        Session::put('page','products');
        $product = Product::select('id','product_name','product_code','product_color','product_price','product_image')->with('images')->find($id);

        if($request->isMethod('post')){
            $data = $request->all();
            if($request->hasFile('images')){
                $images = $request->file('images');

                foreach ($images as $key => $image) {
                    // generate temp image
                    $image_tmp = Image::make($image);
                    //get image name
                    $image_name = $image->getClientOriginalName();
                    // Get image extension
                    $extension = $image->getClientOriginalExtension();
                    // generate new image name
                    $imageName = $image_tmp.rand(111,99999).'.'.$extension;
                    $largeImagePath = 'front/images/product_images/large/'.$imageName;
                    $mediumImagePath = 'front/images/product_images/medium/'.$imageName;
                    $smallImagePath = 'front/images/product_images/small/'.$imageName;
                    //upload image
                    Image::make($image_tmp)->resize(1000,1000)->save($largeImagePath);
                    Image::make($image_tmp)->resize(500,500)->save($mediumImagePath);
                    Image::make($image_tmp)->resize(250,250)->save($smallImagePath);
                    //insert image in product table
                    $image = new ProductsImage;
                    $image->image = $imageName;
                    $image->product_id = $id;
                    $image->status = 1;
                    
                    $image->save();
                }
            }
            return redirect()->back()->with('success_message','Product Images added');
        }

        return view('admin.images.add-images')->with(compact('product'));
    }

    public function updateImageStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            ProductsImage::where('id',$data['image_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'image_id'=>$data['image_id']]);
        }
    }

    public function deleteImage($id){
        //get product image from model
        $productImage = ProductsImage::select('image')->where('id',$id)->first();
        //get image path
        $small_image_path = 'front/images/product_images/small/';
        $medium_image_path = 'front/images/product_images/medium/';
        $large_image_path = 'front/images/product_images/large/';
        //delete product image
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        //delete image from table
        ProductsImage::where('id',$id)->delete();

        $message = "Product Image has been deleted";
        return redirect()->back()->with('success_message',$message);
    }
}
