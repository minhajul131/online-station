<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use Session;

class BrandController extends Controller
{
    public function brands(){
        Session::put('page','brands');
        $brands = Brand::get()->toArray();
        // dd($brands);
        return view('admin.brands.brands')->with(compact('brands'));
    }

    public function updateBrandStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Brand::where('id',$data['brand_id'])->update(['status'=>$status]);
            return response()->json(['status'=>$status, 'brand_id'=>$data['brand_id']]);
        }
    }

    public function deleteBrand($id){
        Brand::where('id',$id)->delete();
        $message = "Brand has been deleted successfully";
        return redirect()->back()->with('success_message',$message);
    }

    public function addEditBrand(Request $request, $id=null){
        Session::put('page','brands');
        if($id==""){
            $title = "Add Brand";
            $brand = new Brand;
            $message = "Brand updated";
        }else{
            $title = "Edit Brand";
            $brand = Brand::find($id);
            $message = "Brand added";
        }
        if($request->isMethod('post')){
            $data =$request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                'brand_name' => 'required|regex:/^[\pL\s\-]+$/u',
            ];

            $customMessages = [
                'brand_name.required' =>'Name is required',
                'brand_name.regex' =>'Valid name is required',
            ];

            $this->validate($request,$rules,$customMessages);

            $brand->name = $data['brand_name'];
            $brand->status = 1;
            $brand->save();

            return redirect('admin/brands')->with('success_message',$message);
        }
        return view('admin.brands.add-edit-brand')->with(compact('title','brand'));
    }
}
