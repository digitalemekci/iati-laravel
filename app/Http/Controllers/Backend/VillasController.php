<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class VillasController extends BaseController
{

  public function list(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillas();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
        if (isset($_GET['search'])) {
          $villas  = $this->searchview($_GET['search']);
        }
      if (isset($_GET['submitfilter'])) {
        $arrayroom = array();
          for ($i=1; $i < 100 ; $i++) {
            if (isset($_GET['room'.$i])) {
              array_push($arrayroom, $i);
            }
          }
          $arraydist = array();
          for ($d=1; $d < 100 ; $d++) {
            if (isset($_GET['district'.$d])) {
              array_push($arraydist, $d);
            }
          }
        $prc = explode(";", $_GET['pricerange']);
        $villas  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }

      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'products' => $villas,
      ];
      return view('backend.default.villas-list', $data);
    }
  }

  public function listdesc(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillasDesc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();

        if (isset($_GET['search'])) {
          $villas  = $this->searchview($_GET['search']);
        }
      if (isset($_GET['submitfilter'])) {
        $arrayroom = array();
          for ($i=1; $i < 100 ; $i++) {
            if (isset($_GET['room'.$i])) {
              array_push($arrayroom, $i);
            }
          }
          $arraydist = array();
          for ($d=1; $d < 15 ; $d++) {
            if (isset($_GET['district'.$d])) {
              echo $_GET['district'.$d]."</br>";
              array_push($arraydist, $d);
            }
          }
        $prc = explode(";", $_GET['pricerange']);
        $villas  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }

      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'products' => $villas,
      ];
      return view('backend.default.villas-list', $data);
    }
  }

  public function listasc(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillasAsc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();

        if (isset($_GET['search'])) {
          $villas  = $this->searchview($_GET['search']);
        }
      if (isset($_GET['submitfilter'])) {
        $arrayroom = array();
          for ($i=1; $i < 13 ; $i++) {
            if (isset($_GET['room'.$i])) {
              array_push($arrayroom, $i);
            }
          }
          $arraydist = array();
          for ($d=1; $d < 13 ; $d++) {
            if (isset($_GET['district'.$d])) {
              array_push($arraydist, $d);
            }
          }
        $prc = explode(";", $_GET['pricerange']);
        $villas  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }

      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'products' => $villas,
      ];
      return view('backend.default.villas-list', $data);
    }
  }

  public function select(Request $request, $locall, $name)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getFindVillas($name);
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
      $district = $this->getDistrict();
      $supplier = $this->getSupplier();
      $pool = $this->getPool();
      $meta = new MetaController();
      $metadata = $meta->getFindMeta($name);
      $villasprice = $this->getFindVillasPer($name);

      $vpr = 0;
      foreach ($villasprice as $v) {
        if (empty($villasprice)) {
          $vpr = 0;
        }else {
          $vpr = 1;
        }
      }
      $mctrl = 0;
      foreach ($metadata as $m) {
        if (empty($metadata)) {
          $mctrl = 0;
        }else {
          $mctrl = 1;
        }
      }
      $data = [
            'local' => $local,
            'crrn' => $curall,
            'district' => $district,
            'supplier' => $supplier,
            'products' => $villas,
            'vpr' => $vpr,
            'pool' => $pool,
            'mctrl' => $mctrl,
            'vlpr' => $villasprice,
            'meta' => $metadata,
      ];
      return view('backend.default.villas-product-detail', $data);
    }
  }

  public function update()
  {

    $checkbox1 = $_POST['pooltp'];
    $chk="";
    foreach($checkbox1 as $chk1)
    { $chk.= $chk1.","; }
    $affected = DB::table('villas')
              ->where('villa_id', $_POST['id'])
              ->update(['villa_name_en' => $_POST['villaname'],
                        'villa_district_id' => $_POST['city'],
                        'villa_address' => $_POST['adress'],
                        'max_guest' => $_POST['maxgue'],
                        'rooms' => $_POST['room'],
                        'bath' => $_POST['bath'],
                        'villa_status' => $_POST['status'],
                        'min_rent_day' => $_POST['min_rent_day'],
                        'price' => $_POST['sellingprice'],
                        'extra_guest_price' => $_POST['extra_guest_price'],
                        'buying_price' => $_POST['buyingprice'],
                        'currency' => $_POST['currency'],
                        'gps' => $_POST['productlocation'],
                        'pool_type' => $chk,
                        'description_en' => $_POST['productdesc'],
                        'for_childrens_en' => $_POST['productforchild'],
                        'neerby_en' => $_POST['productnear'],
                        'distance_city' => $_POST['distance_city'],
                        'distance_sea' => $_POST['distance_sea'],
                        'distance_market' => $_POST['distance_market'],
                        'distance_airport' => $_POST['distance_airport'],
                        'supplier_id' => $_POST['supplier']]);
    return back();
  }

  public function updateru()
  {

    $checkbox1 = $_POST['pooltp'];
    $chk="";
    foreach($checkbox1 as $chk1)
    {
       $chk.= $chk1.",";
    }

    $affected = DB::table('villas')
              ->where('villa_id', $_POST['id'])
              ->update(['villa_name_ru' => $_POST['villaname'],
                        'villa_district_id' => $_POST['city'],
                        'villa_address' => $_POST['adress'],
                        'max_guest' => $_POST['maxgue'],
                        'rooms' => $_POST['room'],
                        'villa_status' => $_POST['status'],
                        'price' => $_POST['sellingprice'],
                        'buying_price' => $_POST['buyingprice'],
                        'currency' => $_POST['currency'],
                        'gps' => $_POST['productlocation'],
                        'pool_type' => $chk,
                        'description_ru' => $_POST['productdesc'],
                        'for_childrens_ru' => $_POST['productforchild'],
                        'neerby_ru' => $_POST['productnear'],
                        'supplier_id' => $_POST['supplier']]);

    return back();
  }

  public function add(Request $request)
  {
    if ($request->session()->has('emp')) {

      $checkbox1 = $_POST['pooltp'];
      $chk="";
      foreach($checkbox1 as $chk1)
      {
         $chk.= $chk1.",";
      }

      $villaname = $_POST['villaname'];
      $url = mb_convert_case($_POST['villaname'], MB_CASE_LOWER, "UTF-8");
      $url = str_replace(' ', '_', $url);
      $url = str_replace('+', '_', $url);
      echo $url;

      $data = [
        'villa_name_en' => $_POST['villaname'],
        'villa_name_ru' => $_POST['villaname'],
        'villa_url' => $url,
        'villa_district_id' => $_POST['city'],
        'villa_address' => $_POST['adress'],
        'max_guest' => $_POST['maxgue'],
        'rooms' => $_POST['room'],
        'villa_status' => $_POST['status'],
        'price' => $_POST['sellingprice'],
        'buying_price' => $_POST['buyingprice'],
        'currency' => $_POST['currency'],
        'gps' => $_POST['productlocation'],
        'pool_type' => $chk,
        'description_en' => $_POST['productdesc'],
        'for_childrens_en' => $_POST['productforchild'],
        'neerby_en' => $_POST['productnear'],
        'description_ru' => $_POST['productdescru'],
        'for_childrens_ru' => $_POST['productforchildru'],
        'neerby_ru' => $_POST['productnearru'],
        'supplier_id' => $_POST['supplier']
      ];

      $mypath="./img/villas/".$url;

      if(!is_dir($mypath)){
        File::makeDirectory($mypath,0777,TRUE);
      }else {
        echo "Klasör İsmi Hatalı/Var";
        exit;
      }

      $affected =  DB::table('villas')
                  ->insert(
                            $data
                          );


      // return back();
      return redirect('/en/administrator/villas-detail/'.$url);
    }
  }


  public function delete(Request $request, $name)
  {
    if ($request->session()->has('emp')) {
      // $mypath="./img/villas/".$name."/1.jpg";
      $a = public_path().'/img/villas/'.$name."/1.jpg";
      echo $a;
      echo "</br>";

      if (File::deleteDirectory('img/villas/'.$name)) {
          echo "Delete ".$name;
      }

      $deletevilla = \DB::table('villas')
              ->where('villa_url', $name)
              ->delete();

      $deletevillaprice = \DB::table('villas_price')
            ->where('villa_url', $name)
            ->delete();

      return redirect('/en/administrator/villas/');
    }
  }

  public function addview(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillas();
      $currencys = new CurrencyController();
      $currency = $currencys->getCurrency();
      $district = $this->getDistrict();
      $supplier = $this->getSupplier();
      $pool = $this->getPool();
      $data = [
        'local' => $local,
        'currency_iso' => $currency,
        'district' => $district,
        'supplier' => $supplier,
        'pool' => $pool,
        'products' => $villas,
      ];
      return view('backend.default.villa-add', $data);
    }
  }

  public function searchview($srcname)
  {
    $searchvilla = \DB::table('villas')
            ->where('villa_name_en', 'LIKE', "%{$srcname}%")
            ->orderByDesc('villas.villa_id')
            ->simplePaginate(150);
    return $searchvilla;
  }

  public function updaterate($tl_rate, $eur_rate, $gbp_rate, $rub_rate)
  {
    echo "string";
    $affectedtr = DB::table('currency')
              ->where('iso_code', 'TRY')
              ->update(['conversion_rate' => $tl_rate]);

    $affectedgbp = DB::table('currency')
              ->where('iso_code', 'EUR')
              ->update(['conversion_rate' => $eur_rate]);

    $affectedeur = DB::table('currency')
              ->where('iso_code', 'GBP')
              ->update(['conversion_rate' => $gbp_rate]);

    $affectedrub = DB::table('currency')
              ->where('iso_code', 'RUB')
              ->update(['conversion_rate' => $rub_rate]);

    return back();
  }
  public function filterview($pool, $sprc, $bprc, $distar, $roomar)
  {
    $query = DB::table('villas')
          ->orderByDesc('villas.currency_price');
            if ($roomar) {
                $query->where('rooms', '=', $roomar);
            }
            if ($distar) {
                $query->where('villa_district_id', '=', $distar);
            }
            $query->where('pool_type', $pool)
            ->where('currency_price', '<', $bprc)
            ->where('currency_price', '>', $sprc);
            $result= $query->simplePaginate(100);
            return $result;
  }

  public function imageupl(Request $request)
  {
        $image = $request->file('file');
        $imageName = $image->getClientOriginalName();
        $image->move(public_path('img/villas/'.$_POST['name']), $imageName);
       return response()->json([
           'status' => 1,
           'filename' => $imageName
       ]);

    return back();
  }

  public function getVillas()
  {
    $findThemTr = \DB::table('villas')
            ->orderByDesc('villas.villa_id')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getVillasDesc()
  {
    $findThemTr = \DB::table('villas')
            ->orderByDesc('villas.currency_price')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getVillasAsc()
  {
    $findThemTr = \DB::table('villas')
            ->orderBy('villas.currency_price', 'asc')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getactiveVillas()
  {
    $findThemTr = \DB::table('villas')
            ->where('villa_status', '1')
            ->get();
    return $findThemTr;
  }

  public function getallVillas()
  {
    $findThemTr = \DB::table('villas')
            ->get();
    return $findThemTr;
  }

  public function getFindVillas($name)
  {
    $findThemTr = \DB::table('villas')
            ->where('villa_url', $name)
            ->get();
    return $findThemTr;
  }

  public function getFindVillasPer($name)
  {
    $findThemTr = \DB::table('villas_price')
            ->where('villa_url', $name)
            ->get();
    return $findThemTr;
  }

  public function getDistrict()
  {
    $findThemTr = \DB::table('destination_district')
            ->where('status',1)
            ->orderBy('district_name', 'asc')
            ->get();
    return $findThemTr;
  }

  public function getSupplier()
  {
    $exp = \DB::table('supplier')
          ->where('active',1)
          ->pluck('department', 'supplier_name');
          $dil = explode(",", $exp);

    $findThemTr = \DB::table('supplier')
            ->where('department','villa')
            ->where('active',1)
            ->orderBy('supplier_name', 'asc')
            ->get();
    return $findThemTr;
  }

  public function getFindCurrency($iso)
  {
    $findThemTr = \DB::table('currency')
            ->where('iso_code', $name)
            ->where('active',1)
            ->get();
    return $findThemTr;
  }

  public function perprice()
  {
    $period1  = $_POST['pstart1']."/".$_POST['pend1']."/".$_POST['pprice1'];
    $period2  = $_POST['pstart2']."/".$_POST['pend2']."/".$_POST['pprice2'];
    $period3  = $_POST['pstart3']."/".$_POST['pend3']."/".$_POST['pprice3'];
    $period4  = $_POST['pstart4']."/".$_POST['pend4']."/".$_POST['pprice4'];
    $period5  = $_POST['pstart5']."/".$_POST['pend5']."/".$_POST['pprice5'];
    $period6  = $_POST['pstart6']."/".$_POST['pend6']."/".$_POST['pprice6'];
    $period7  = $_POST['pstart7']."/".$_POST['pend7']."/".$_POST['pprice7'];
    $period8  = $_POST['pstart8']."/".$_POST['pend8']."/".$_POST['pprice8'];
    $period9  = $_POST['pstart9']."/".$_POST['pend9']."/".$_POST['pprice9'];
    $period10 = $_POST['pstart10']."/".$_POST['pend10']."/".$_POST['pprice10'];
    $period11 = $_POST['pstart11']."/".$_POST['pend11']."/".$_POST['pprice11'];
    $period12 = $_POST['pstart12']."/".$_POST['pend12']."/".$_POST['pprice12'];
    $period13 = $_POST['pstart13']."/".$_POST['pend13']."/".$_POST['pprice13'];
    $period14 = $_POST['pstart14']."/".$_POST['pend14']."/".$_POST['pprice14'];
    $period15 = $_POST['pstart15']."/".$_POST['pend15']."/".$_POST['pprice15'];
    $period16 = $_POST['pstart16']."/".$_POST['pend16']."/".$_POST['pprice16'];
    $period17 = $_POST['pstart17']."/".$_POST['pend17']."/".$_POST['pprice17'];
    $villadat = $period1."|".$period2."|".$period3."|".$period4."|".$period5."|".$period6."|".$period7."|".$period8."|".$period9."|".$period10."|".$period11."|".$period12."|".$period13."|".$period14."|".$period15."|".$period16."|".$period17;
    $vln = $_POST['namevil'];
    $ctrl = $this->getFindPrice($_POST['namevil']);
    $mctrl = 0;
    foreach ($ctrl as $m) {
      if (empty($ctrl)) {
        $mctrl = 0;
      }else {
        $mctrl = 1;
        $data = [
          'villa_data' => $villadat,
          'villa_url' => $vln,
          'update_date' => date("Ymd")
        ];
        $affected =  DB::table('villas_price')->where('villa_url', $_POST['namevil'])->update($data);
        return back();
      }
    }

   $affected =  DB::table('villas_price')
                   ->insert([
                     'villa_data' => $villadat,
                     'villa_url' => $vln,
                     'update_date' => date("Ymd")
                      ]);
   return back();
  }

  public function getFindPrice($url)
  {
    $findThemTr = \DB::table('villas_price')
            ->where('villa_url', $url)
            ->value('villa_data');
    return $findThemTr;
  }

  public function getPool()
  {
    $findThemTr = \DB::table('pool_type')
            ->where('pool_type_status',1)
            ->get();
    return $findThemTr;
  }

  public function xmlexport()
  {
    $pool = $this->getPool();
    $villas = $this->getactiveVillas();
    $district = $this->getDistrict();
    $data = [];
    foreach ($villas as $vname) {
      $vurl= $vname->villa_url;
      $vprc = $this->getFindPrice($vurl);
      $text = $vurl.":".$vprc;
      $yeni = array_push($data, $text);
    }

    return response()->view('export', [
            'villa' => $villas,
            'vprice' => $data,
            'district' => $district,
            'pool' => $pool
        ])->header('Content-Type', 'text/xml');
  }
}
