<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use File;

class MetaController extends BaseController
{
  
  public function getFindMeta($meta_data)
  {
    $findThemTr = \DB::table('meta_data')
            ->where('meta_product', $meta_data)
            ->get();
    return $findThemTr;
  }
}
