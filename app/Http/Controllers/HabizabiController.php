<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use App\Mail\ContactConfirmation;
use Illuminate\Support\Facades\Mail;

class HabizabiController extends Controller
{
    public function contact() {
      return view('contact');
    }
    
    public function submit(ContactRequest $request)
    {
        $data = $request->validated();
        
        $data['message'] = strip_tags($data['message']);
        
        $supportAddress = config('mail.from.address', 'support@smartagro.com');

        // If you use Laravel queues, use ->queue(...). Otherwise use ->send(...)
        Mail::to($supportAddress)->queue(new ContactMessage($data));
        Mail::to($data['email'])->queue(new ContactConfirmation($data));
        return redirect()->back()->with('success', 'Your message has been sent. We will get back to you soon.');
    }
    
    public function dashboard() {
      return view('dashboard');
    }
}