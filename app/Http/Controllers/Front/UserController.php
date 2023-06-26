<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Illuminate\Support\Facades\Mail;
use Session;
use App\Models\Cart;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function loginRegister(){
        return view('front.users.login_register');
    }

    public function userRegister(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:100',
                'mobile' => 'required|numeric|digits:11',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'accept' => 'required',
            ],[
                'accept.required' => 'Accept Terms & Conditions'
            ]);

            if($validator->passes()){
                // register user
                $user = new User;
                $user->name = $data['name'];
                $user->mobile = $data['mobile'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->status = 0;
                $user->save();
                
                // Active user with confirming by mail account
                $email = $data['email'];
                $messageData = ['name'=>$data['name'],'email'=>$data['email'],'code'=>base64_encode($data['email'])];
                Mail::send('emails.confirmation',$messageData,function($message)use($email){
                    $message->to($email)->subject('Confirm your Online Station account');
                });
                
                // redirect back user with message
                $redirectTo = url('user/login-register');
                return response()->json(['type'=>'success','url'=>$redirectTo,'message'=>'Please confirm your account']);

                /* simple registration of user without confirming by mail account
                // send register mail
                $email = $data['email'];
                $messageData = ['name'=>$data['name'],'mobile'=>$data['mobile'],'email'=>$data['email']];
                Mail::send('emails.register',$messageData,function($message)use($email){
                    $message->to($email)->subject('Welcome to Online Station');
                });

                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
                    // update cart with user id
                    if(!empty(Session::get('session_id'))){
                        $user_id = Auth::user()->id;
                        $session_id = Session::get('session_id');
                        Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                    }

                    $redirectTo = url('cart');
                    return response()->json(['type'=>'success','url'=>$redirectTo]);
                }*/
            }else{
                return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
    }

    public function userLogin(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $validator = Validator::make($request->all(),[
                'email' => 'required|email|exists:users',
                'password' => 'required|min:6',
            ]);

            if($validator->passes()){
                if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){

                    if(Auth::user()->status==0){
                        Auth::logout();
                        return response()->json(['type'=>'inactive','message'=>'Your account is not activated. Please confirm to login']);
                    }

                    // update cart with user id
                    if(!empty(Session::get('session_id'))){
                        $user_id = Auth::user()->id;
                        $session_id = Session::get('session_id');
                        Cart::where('session_id',$session_id)->update(['user_id'=>$user_id]);
                    }

                    $redirectTo = url('cart');
                    return response()->json(['type'=>'success','url'=>$redirectTo]);
                }else{
                    return response()->json(['type'=>'incorrect','message'=>'Incorrect Email or Password']);
                }
            }else{
                return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }
    }

    public function confirmAccount($code){
        $email= base64_decode($code);
        $userCount = User::where('email',$email)->count();
        if($userCount>0){
            $userDetails = User::where('email',$email)->first();
            if($userDetails->status==1){
                return redirect('user/login-register')->with('error_message','Your account is already activated. Login now');
            }else{
                User::where('email',$email)->update(['status'=>1]);

                // send register welcome mail
                $messageData = ['name'=>$userDetails->name,'mobile'=>$userDetails->mobile,'email'=>$email];
                Mail::send('emails.register',$messageData,function($message)use($email){
                    $message->to($email)->subject('Welcome to Online Station');
                });

                return redirect('user/login-register')->with('success_message','Your account is activated. Login now');
            }
        }else{
            abort(404);
        }
    }

    public function forgotPassword(Request $request){
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            
            // validation for email
            $validator = Validator::make($request->all(),[
                'email' => 'required|email|exists:users',
            ],[
                'email.exists'=>'Email does not exists',
            ]);

            if($validator->passes()){
                //generate new password
                $new_password = Str::random(16);
                //update new pass to table
                User::where('email',$data['email'])->update(['password'=>bcrypt($new_password)]);
                //get user detail
                $userDetails = User::where('email',$data['email'])->first()->toArray();
                //sent mail to user
                $email = $data['email'];
                $messageData = ['name'=>$userDetails['name'],'email'=>$email,'password'=>$new_password];
                Mail::send('emails.user_forgot_password',$messageData,function($message)use($email){
                    $message->to($email)->subject('Youe Password is reset');
                });

                return response()->json(['type'=>'success','message'=>'Check your email. Reset password have sent to your email']);
            }else{
                return response()->json(['type'=>'error','errors'=>$validator->messages()]);
            }
        }else{
            return view('front.users.forgot_password');
        }
    }

    public function userLogout(){
        Auth::logout();

        return redirect('/');
    }
}
