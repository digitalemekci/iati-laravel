<?php

namespace App\Http\Controllers\Backend\Auth;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
  public function check(){
    echo "AUTH";
    exit;
  }
}
