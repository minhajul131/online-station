<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\VendorsBusinessDetail;
use Image;

class AdminController extends Controller
{
    public function dashboard(){
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            //check current password is correct or not
            if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
                //validate new and confirm password is same
                if($data['confirm_password']==$data['new_password']){
                    Admin::where('id',Auth::guard('admin')->user()->id)->update(['password'=>bcrypt($data['new_password'])]);

                    return redirect()->back()->with('success_message','Password Updated');
                }else{
                    return redirect()->back()->with('error_message','New Password and Confirm password not matched');
                }
            }else{
                return redirect()->back()->with('error_message','Current password is incorrect');
            }
        }
        $adminDetails = Admin::where('email',Auth::guard('admin')->user()->email)->first()->toArray();
        return view('admin.settings.update-admin-password')->with(compact('adminDetails'));
    }

    public function checkAdminPassword(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        if(Hash::check($data['current_password'],Auth::guard('admin')->user()->password)){
            return "true";
        }else{
            return "false";
        }
    }

    public function updateAdminDetails(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u',
                'admin_mobile' => 'required|numeric',
            ];

            $customMessages = [
                'admin_name.required' =>'Name is required',
                'admin_name.regex' =>'Valid name is required',
                'admin_mobile.required' =>'Mobile number is required',
                'admin_mobile.numeric' =>'Valid mobile number is required',
            ];

            $this->validate($request,$rules,$customMessages);

            //upload image
            if($request->hasFile('admin_image')){
                $image_tmp = $request->file('admin_image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $imageName = rand(111,99999).'.'.$extension;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    Image::make($image_tmp)->save($imagePath);
                }
            }else if(!empty($data['current_admin_image'])){
                $imageName = $data['current_admin_image'];
            }else{
                $imageName = "";
            }

            //update info
            Admin::where('id',Auth::guard('admin')->user()->id)->update(['name'=>$data['admin_name'],'mobile'=>$data['admin_mobile'],'image'=>$imageName]);

            return redirect()->back()->with('success_message','Information Updated');
        }
        return view('admin.settings.update-admin-details');
    }

    public function updateVendorDetails($slug, Request $request){
        if($slug=="personal"){
            if($request->isMethod('post')){
                $data = $request->all();

                $rules = [
                    'vendor_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'vendor_mobile' => 'required|numeric',
                ];
    
                $customMessages = [
                    'vendor_name.required' =>'Name is required',
                    'vendor_name.regex' =>'Valid name is required',
                    'vendor_mobile.required' =>'Mobile number is required',
                    'vendor_mobile.numeric' =>'Valid mobile number is required',
                ];
    
                $this->validate($request,$rules,$customMessages);
    
                //upload image
                if($request->hasFile('vendor_image')){
                    $image_tmp = $request->file('vendor_image');
                    if($image_tmp->isValid()){
                        $extension = $image_tmp->getClientOriginalExtension();
                        $imageName = rand(111,99999).'.'.$extension;
                        $imagePath = 'admin/images/photos/'.$imageName;
                        Image::make($image_tmp)->save($imagePath);
                    }
                }else if(!empty($data['current_vendor_image'])){
                    $imageName = $data['current_vendor_image'];
                }else{
                    $imageName = "";
                }
    
                //update info in admin table
                Admin::where('id',Auth::guard('admin')->user()->id)->update(['name'=>$data['vendor_name'],'mobile'=>$data['vendor_mobile'],'image'=>$imageName]);
                //update info in vendor table
                Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->update(['name'=>$data['vendor_name'],'mobile'=>$data['vendor_mobile'],'address'=>$data['vendor_address'],'city'=>$data['vendor_city'],'division'=>$data['vendor_division'],'country'=>$data['vendor_country'],'postcode'=>$data['vendor_postcode']]);
                return redirect()->back()->with('success_message','Vendor information Updated');
            }
            $vendorDetails = Vendor::where('id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
        }else if($slug=="business"){
            if($request->isMethod('post')){
                $data = $request->all();

                $rules = [
                    'shop_name' => 'required',
                    'shop_mobile' => 'required|numeric',
                    'address_proof' => 'required',
                ];
    
                $customMessages = [
                    'shop_name.required' =>'Name is required',
                    'shop_name.regex' =>'Valid name is required',
                    'shop_mobile.required' =>'Mobile number is required',
                    'shop_mobile.numeric' =>'Valid mobile number is required',
                    'address_proof.required' => 'Address Proof is reqired',
                ];
    
                $this->validate($request,$rules,$customMessages);
    
                //upload image
                if($request->hasFile('address_proof_image')){
                    $image_tmp = $request->file('address_proof_image');
                    if($image_tmp->isValid()){
                        $extension = $image_tmp->getClientOriginalExtension();
                        $imageName = rand(111,99999).'.'.$extension;
                        $imagePath = 'admin/images/proofs/'.$imageName;
                        Image::make($image_tmp)->save($imagePath);
                    }
                }else if(!empty($data['current_address_proof'])){
                    $imageName = $data['current_address_proof'];
                }else{
                    $imageName = "";
                }
    
                //update info in vendor table
                VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update(['shop_name'=>$data['shop_name'],'shop_mobile'=>$data['shop_mobile'],'shop_address'=>$data['shop_address'],'shop_city'=>$data['shop_city'],'shop_state'=>$data['shop_state'],'shop_country'=>$data['shop_country'],'shop_pincode'=>$data['shop_pincode'],'shop_website'=>$data['shop_website'],'shop_email'=>$data['shop_email'],'address_proof'=>$data['address_proof'],'address_proof_image'=>$imageName,'business_license_number'=>$data['business_license_number'],'gst_number'=>$data['gst_number'],'pan_number'=>$data['pan_number']]);
                return redirect()->back()->with('success_message','Vendor information Updated');
            }
            $vendorDetails = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
        }else if($slug=="bank"){
            
        }
        return view('admin.settings.update-vendor-details')->with(compact('slug','vendorDetails'));
    }

    public function login(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],'status'=>1])){
                return redirect('admin/dashboard');
            }else{
                return redirect()->back()->with('error_message','Invalid Email or Password');
            }
        }
        return view('admin.login');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}
