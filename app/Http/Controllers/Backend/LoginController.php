<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
  public function index(Request $req){
    $data = $req->input();
    $req->session()->put('user', $data['email']);
    $req->session()->put('pass', $data['password']);
    $cchk = $this->check($data);
    $req->session()->put('emp', $cchk);
    if ($cchk == null) {
     return redirect('en/administrator/login');
   }else {
     return redirect('en/administrator/');
   }
  }

  public function check($data){
    $eml = $data['email'];
    $psw = sha1(md5($data['password']));
    $feedemp = $this->checkDb($eml, $psw);
    $ctrl_emp = null;
    foreach ($feedemp as $key) {
      echo $key->employee_name;
      if (isset($key->employee_name)) {
        $ctrl_emp = 1;
      }
    }
    return $ctrl_emp;
  }

  public function checkDb($eml, $psw){
    $findThemTr = \DB::table('employee')
            ->where('employee_mail', $eml)
            ->where('employee_pass', $psw)
            ->get();
    return $findThemTr;
  }

}
