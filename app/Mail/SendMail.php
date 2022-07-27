<?php

namespace App\Mail;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
/*********/
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
        return $this->from('smtp@melinotravel.com')
            ->subject('Website Request')
            ->view('template')
            ->with('data', $this->data);
    }

    public function send($data){
      $this->data = $data;
      return $this->from('smtp@melinotravel.com')
          ->subject('Villa Request Form')
          ->view('villas-request')
          ->with('data', $this->data);
    }
}
