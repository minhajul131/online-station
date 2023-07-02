<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Auth;
use App\Models\Admin;
use App\Models\Vendor;
use App\Models\VendorsBusinessDetail;
use App\Models\VendorsBankDetail;
use App\Models\Country;
use Image;
use Session;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function dashboard(){
        Session::put('page','dashboard');
        return view('admin.dashboard');
    }

    public function updateAdminPassword(Request $request){
        Session::put('page','update-admin-password');
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
        Session::put('page','update-admin-details');
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
            Session::put('page','update-vendor-parsonal');
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
            Session::put('page','update-vendor-business');
            if($request->isMethod('post')){
                $data = $request->all();

                $rules = [
                    'shop_name' => 'required',
                    'shop_mobile' => 'required|numeric',
                    'address_proof' => 'required',
                ];
    
                $customMessages = [
                    'shop_name.required' =>'Name is required',
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
                $vendorCount = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
                if($vendorCount>0){
                    VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update(['shop_name'=>$data['shop_name'],'shop_mobile'=>$data['shop_mobile'],'shop_address'=>$data['shop_address'],'shop_city'=>$data['shop_city'],'shop_state'=>$data['shop_state'],'shop_country'=>$data['shop_country'],'shop_pincode'=>$data['shop_pincode'],'shop_website'=>$data['shop_website'],'shop_email'=>$data['shop_email'],'address_proof'=>$data['address_proof'],'address_proof_image'=>$imageName,'business_license_number'=>$data['business_license_number'],'gst_number'=>$data['gst_number'],'pan_number'=>$data['pan_number']]);
                }else{
                    VendorsBusinessDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,'shop_name'=>$data['shop_name'],'shop_mobile'=>$data['shop_mobile'],'shop_address'=>$data['shop_address'],'shop_city'=>$data['shop_city'],'shop_state'=>$data['shop_state'],'shop_country'=>$data['shop_country'],'shop_pincode'=>$data['shop_pincode'],'shop_website'=>$data['shop_website'],'shop_email'=>$data['shop_email'],'address_proof'=>$data['address_proof'],'address_proof_image'=>$imageName,'business_license_number'=>$data['business_license_number'],'gst_number'=>$data['gst_number'],'pan_number'=>$data['pan_number']]);
                }
                return redirect()->back()->with('success_message','Vendor information Updated');
            }
            $vendorCount = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
                $vendorDetails = VendorsBusinessDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
            }else{
                $vendorDetails = array();
            }
        }else if($slug=="bank"){
            Session::put('page','update-vendor-bank');
            if($request->isMethod('post')){
                $data = $request->all();

                $rules = [
                    'account_holder_name' => 'required|regex:/^[\pL\s\-]+$/u',
                    'bank_name' => 'required',
                    'account_number' => 'required',
                ];
    
                $customMessages = [
                    'account_holder_name.required' =>'Name is required',
                    'account_holder_name.regex' =>'Valid name is required',
                    'bank_name.required' =>'Mobile number is required',
                    'account_number.required' => 'Address Proof is reqired',
                ];
    
                $this->validate($request,$rules,$customMessages);
    
                //update info in vendor table
                $vendorCount = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
                if($vendorCount>0){
                    VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->update(['account_holder_name'=>$data['account_holder_name'],'bank_name'=>$data['bank_name'],'account_number'=>$data['account_number'],'bank_code'=>$data['bank_code']]);
                }else{
                    VendorsBankDetail::insert(['vendor_id'=>Auth::guard('admin')->user()->vendor_id,'account_holder_name'=>$data['account_holder_name'],'bank_name'=>$data['bank_name'],'account_number'=>$data['account_number'],'bank_code'=>$data['bank_code']]);
                }
                    return redirect()->back()->with('success_message','Bank information Updated');
            }
            $vendorCount = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->count();
            if($vendorCount>0){
                $vendorDetails = VendorsBankDetail::where('vendor_id',Auth::guard('admin')->user()->vendor_id)->first()->toArray();
            }else{
                $vendorDetails = array();
            }
        }
        $countries = Country::where('status',1)->get()->toArray();
        return view('admin.settings.update-vendor-details')->with(compact('slug','vendorDetails','countries'));
    }

    public function login(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password'],'status'=>1])){
            //     return redirect('admin/dashboard');
            // }else{
            //     return redirect()->back()->with('error_message','Invalid Email or Password');
            // }

            if(Auth::guard('admin')->attempt(['email'=>$data['email'],'password'=>$data['password']])){

                if(Auth::guard('admin')->user()->trpe=="vendor" && Auth::guard('admin')->user()->confirm=="No"){
                    return redirect()->back()->with('error_message','Please confirm your email to active account');
                }else if(Auth::guard('admin')->user()->trpe!="vendor" && Auth::guard('admin')->user()->status=="0"){
                    return redirect()->back()->with('error_message','Your Admin account is not activated');
                }else{
                    return redirect('admin/dashboard');
                }
            }else{
                return redirect()->back()->with('error_message','Invalid Email or Password');
            }
        }
        return view('admin.login');
    }

    public function admins($trpe = null){
        $admins = Admin::query();
        if(!empty($trpe)){
            $admins = $admins->where('trpe', $trpe);
            $title = ucfirst($trpe)."s";
            Session::put('page','view_'.strtolower($title));
        }else{
            $title = "All Admins/Subadmins/Vendors";
            Session::put('page','view_all');
        }
        $admins = $admins->get()->toArray();
        // dd($admins);
        return view('admin.admins.admins')->with(compact('admins','title'));
    }

    public function viewVendorDetails($id){
        $vendorDetails = Admin::with('vendorPersonal','vendorBusiness','vendorBank')->where('id',$id)->first();        
        $vendorDetails = json_decode(json_encode($vendorDetails),true);   
        // dd($vendorDetails);
        return view('admin.admins.view-vendor-details')->with(compact('vendorDetails'));
    }

    public function updateAdminStatus(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>";print_r($data); die;
            if($data['status']=="Active"){
                $status = 0;
            }else{
                $status = 1;
            }
            Admin::where('id',$data['admin_id'])->update(['status'=>$status]);
            $adminDetails = Admin::where('id',$data['admin_id'])->first()->toArray();
            Vendor::where('id',$adminDetails['vendor_id'])->update(['status'=>$status]);
            if($adminDetails['trpe']=="vendor" && $status==1){                
                //send Approval email
                $email = $adminDetails['email'];
                $messageData = [
                    'email' => $adminDetails['email'],
                    'name' => $adminDetails['name'],
                    'mobile' => $adminDetails['mobile']
                ];

                Mail::send('emails.vendor_approved',$messageData,function($message)use($email){
                    $message->to($email)->subject('Your Account is approved');
                });
            }
            return response()->json(['status'=>$status, 'admin_id'=>$data['admin_id']]);
        }
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect('admin/login');
    }
}
