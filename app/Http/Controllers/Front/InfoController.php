<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class InfoController extends Controller
{
    public function contact(Request $request){

        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $email = 'admin@admin.com';
            $messageData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'subject' => $data['subject'],
                'messages' => $data['message'],
            ];

            Mail::send('emails.contact',$messageData,function($message)use($email){
                $message->to($email)->subject("Message from user to Online Station");
            });

            $message = "Thanks for your message. We will get back to you soon.";
            return redirect()->back()->with('success_message',$message);

        }
        return view('front.pages.contact');
    }
}
