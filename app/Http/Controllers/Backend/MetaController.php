<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use File;

class MetaController extends BaseController
{
  public function add()
  {
    var_dump($_POST['url']);
    echo "</br>";
    $ctrl = $this->getFindMeta($_POST['url']);
    $mctrl = 0;
    foreach ($ctrl as $m) {
      if (empty($ctrl)) {
        $mctrl = 0;
        echo "0";
      }else {
        $mctrl = 1;
        echo "1";
        $cat = "villa";
        $data = [
          'meta_product' => $_POST['url'],
          'product_cat' => $cat,
          'meta_title' => $_POST['metatitle'],
          'meta_title_ru' => $_POST['metatitle_ru'],
          'meta_keywords' => $_POST['metakeywords'],
          'meta_keywords_ru' => $_POST['metakeywords_ru'],
          'meta_description' => $_POST['metadescription'],
          'meta_description_ru' => $_POST['metadescription_ru']
        ];
        $affected =  DB::table('meta_data')->where('meta_product', $_POST['url'])->update($data);
        return back();
      }
    }

    $cat = "villa";
    $data = [
      'meta_product' => $_POST['url'],
      'product_cat' => $cat,
      'meta_title' => $_POST['metatitle'],
      'meta_title_ru' => $_POST['metatitle_ru'],
      'meta_keywords' => $_POST['metakeywords'],
      'meta_keywords_ru' => $_POST['metakeywords_ru'],
      'meta_description' => $_POST['metadescription'],
      'meta_description_ru' => $_POST['metadescription_ru']
    ];

    $affected =  DB::table('meta_data')->insert($data);
    return back();
  }

  public function getFindMeta($meta_data)
  {
    $findThemTr = \DB::table('meta_data')
            ->where('meta_product', $meta_data)
            ->get();
    return $findThemTr;
  }
}
