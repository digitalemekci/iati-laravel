<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Mail\SendMail;
use App\Mail\SendRequestMail;
use App\Mail\SendRequestYachtsMail;
use App\Mail\SendRequestThematicMail;
use App\Mail\SendRequestEventMail;
use App\Mail\SendRequestDailyexMail;
use App\Mail\SendRequestCampaign;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function index()
    {
        return view('mailsend');
    }
    public function sendMail(Request $request)
    {
        $data=$request->only(['firstName','lastName', 'email', 'phone', 'country', 'w3review']);
        Mail::to('request@melinotravel.com')
            ->send(new SendMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendMailVilla(Request $request)
    {
        $data=$request->only(['firstName', 'email', 'location', 'pax', 'chech-in', 'chech-out', 'url']);
        Mail::to('request@melinotravel.com')
            ->send(new SendRequestMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendMailYacht(Request $request)
    {
        $data=$request->only(['firstName', 'email', 'location', 'pax', 'chech-in', 'chech-out', 'url']);
        Mail::to('request@melinotravel.com')
            ->send(new SendRequestYachtsMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendMailThematic(Request $request)
    {
        $data=$request->only(['firstName', 'email', 'location', 'phone', 'pax', 'chech-in', 'url']);
        Mail::to('request@melinotravel.com')
            ->send(new SendRequestThematicMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendEvent(Request $request)
    {
        $data=$request->only(['firstName','lastName', 'email', 'phone', 'country', 'w3review', 'url']);
        Mail::to('request@melinotravel.com')
            ->send(new SendRequestEventMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendDailyex(Request $request)
    {
        $data=$request->only(['firstName', 'email', 'phone', 'location', 'w3review', 'url']);
        Mail::to('request@melinotravel.com')
            ->send(new SendRequestDailyexMail($data));
        return back()->with('success','Mail Sent Successfully');
    }

    public function sendMailCampaign(Request $request)
    {
        $data=$request->only(['name', 'phone', 'contact', 'comment']);
        Mail::to('russia@melinotravel.com')
            ->send(new SendRequestCampaign($data));
        return back()->with('success','Mail Sent Successfully');
    }


}
