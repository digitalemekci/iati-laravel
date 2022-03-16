<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DefaultController extends BaseController
{
  public function index($locall)
  {
    $l = \App::setLocale($locall);
    $local= \App::getLocale();
    return view('backend.default.auth-login');
  }

  public function login()
  {
    var_dump($_POST['username']);
    var_dump($_POST['userpassword']);
    $emp = DB::table('employee')
          ->where('employee_name', $_POST['username'])
          ->where('employee_pass', $_POST['userpassword'])
          ->get();

    echo "</br>".$emp;
    exit;
    return view('backend.default.index');
  }
}
