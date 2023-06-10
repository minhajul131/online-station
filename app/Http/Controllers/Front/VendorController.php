<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;
use DB;
use App\Models\Admin;
use App\Models\Vendor;
use Illuminate\Support\Facades\Mail;

class VendorController extends Controller
{
    public function loginRegister(){
        return view('front.vendors.login_register');
    }

    public function vendorRegister(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();

            //velidate vendor
            $rules = [
                "name" => "required",
                "email" => "required|email|unique:admins|unique:vendors",
                "mobile" => "required|min:11|numeric|unique:admins|unique:vendors",
                "accept" => "required"
            ];
            $customMessages = [
                "name.required" => "Name is required",
                "email.required" => "Email is required",
                "email.unique" => "Email already exists",
                "mobile.required" => "Mobile is required",
                "mobile.unique" => "Mobile already exists",
                "accept.required" => "Accept the Terms and conditions"
            ];
            $validator = Validator::make($data,$rules,$customMessages);
            if($validator->fails()){
                return Redirect::back()->withErrors($validator);
            }

            DB::beginTransaction();

            //vendor account create
            $vendor = new Vendor;
            $vendor->name = $data['name'];
            $vendor->mobile = $data['mobile'];
            $vendor->email = $data['email'];
            $vendor->status = 0;

            //set time zone
            date_default_timezone_set('Asia/Dhaka');
            $vendor->created_at = date("Y-m-d H:i:s");
            $vendor->updated_at = date("Y-m-d H:i:s");

            $vendor->save();

            $vendor_id = DB::getPdo()->lastInsertId();

            //insert in to admin table
            $admin = new Admin;
            $admin->trpe = 'vendor';
            $admin->vendor_id = $vendor_id;
            $admin->name = $data['name'];
            $admin->mobile = $data['mobile'];
            $admin->email = $data['email'];
            $admin->password = bcrypt($data['password']);
            $admin->status = 0;

            //set time zone
            date_default_timezone_set('Asia/Dhaka');
            $admin->created_at = date("Y-m-d H:i:s");
            $admin->updated_at = date("Y-m-d H:i:s");

            $admin->save();

            //send confirmation email
            $email = $data['email'];
            $messageData = [
                'email' => $data['email'],
                'name' => $data['name'],
                'code' => base64_encode($data['email']),
            ];

            Mail::send('emails.vendor_confirmation',$messageData,function($message)use($email){
                $message->to($email)->subject('Confirm your Vendor Account');
            });

            DB::commit();

            //redirect back vendor
            $message = "Welcome!!! Please confirm your email for active account";

            return redirect()->back()->with('success_message',$message);
        }
    }

    public function confirmVendor($email){
        //decode vendor email
        $email = base64_decode($email);

        //check email exists or not
        $vendorCount = Vendor::where('email',$email)->count();
        if($vendorCount>0){
            //check already active or not
            $vendorDetails = Vendor::where('email',$email)->first();
            if($vendorDetails->confirm == "Yes"){
                $message = "Your account is already confirmed";
                return redirect('vendor/login-register')->with('error_message',$message);
            }else{
                //update admin and vendor table confirm column yes
                Admin::where('email',$email)->update(['confirm'=>'Yes']);
                Vendor::where('email',$email)->update(['confirm'=>'Yes']);

                //send register email
                $messageData = [
                    'email' => $email,
                    'name' => $vendorDetails->name,
                    'mobile' => $vendorDetails->mobile,
                ];
    
                Mail::send('emails.vendor_confirmed',$messageData,function($message)use($email){
                    $message->to($email)->subject('Your Account is confirmed');
                });
                //redirect to vendor login/register page
                $message = "Your Vendor Email account is confirmed. You can login and add your personal, business and bank details to active your vendor account to add products";
                return redirect('vendor/login-register')->with('success_message',$message);
            }
        }else{
            abort(404);
        }


    }
}
