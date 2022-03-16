<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ApartmentsController extends BaseController
{

  public function list(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $apart = $this->getApartments();
      $district = $this->getDistrict();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
      if (isset($_GET['search'])) {
        $apart  = $this->searchview($_GET['search']);
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
        $apart  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }
        $data = [
              'local' => $local,
              'district' => $district,
              'crrn' => $curall,
              'products' => $apart,
        ];
        return view('backend.default.apartments-list', $data);
    }
  }

  public function listdesc(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $apart = $this->getApartsDesc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
        if (isset($_GET['search'])) {
          $apart  = $this->searchview($_GET['search']);
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
        $apart  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'products' => $apart,
      ];
      return view('backend.default.apartments-list', $data);
    }
  }

  public function listasc(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $apart = $this->getApartsAsc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
        if (isset($_GET['search'])) {
          $apart  = $this->searchview($_GET['search']);
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
        $apart  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'products' => $apart,
      ];
      return view('backend.default.apartments-list', $data);
    }
  }

  public function select(Request $request, $locall, $name)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $apart = $this->getFindAparts($name);
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
      $district = $this->getDistrict();
      $supplier = $this->getSupplier();
      $meta = new MetaController();
      $metadata = $meta->getFindMeta($name);
      $apartsprice = $this->getFindApartsPer($name);
      $vpr = 0;
      foreach ($apartsprice as $v) {
        if (empty($apartsprice)) {
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
            'products' => $apart,
            'vpr' => $vpr,
            'mctrl' => $mctrl,
            'vlpr' => $apartsprice,
            'meta' => $metadata,
      ];
      return view('backend.default.apartments-product-detail', $data);
    }
  }

  public function update()
  {
    $affected = DB::table('apartment')
              ->where('apartment_id', $_POST['id'])
              ->update(['apartment_name_en' => $_POST['villaname'],
                        'apartment_district_id' => $_POST['city'],
                        'apartment_address' => $_POST['adress'],
                        'max_guest' => $_POST['maxgue'],
                        'rooms' => $_POST['room'],
                        'apartment_status' => $_POST['status'],
                        'price' => $_POST['sellingprice'],
                        'buying_price' => $_POST['buyingprice'],
                        'currency' => $_POST['currency'],
                        'gps' => $_POST['productlocation'],
                        'pool_type' => $_POST['pool'],
                        'description_en' => $_POST['productdesc'],
                        'for_childrens_en' => $_POST['productforchild'],
                        'neerby_en' => $_POST['productnear'],
                        'supplier_id' => $_POST['supplier']]);
    return back();
  }

  public function updateru()
  {
    $affected = DB::table('apartment')
              ->where('apartment_id', $_POST['id'])
              ->update(['apartment_name_ru' => $_POST['villaname'],
                        'apartment_district_id' => $_POST['city'],
                        'apartment_address' => $_POST['adress'],
                        'max_guest' => $_POST['maxgue'],
                        'rooms' => $_POST['room'],
                        'apartment_status' => $_POST['status'],
                        'price' => $_POST['sellingprice'],
                        'buying_price' => $_POST['buyingprice'],
                        'currency' => $_POST['currency'],
                        'gps' => $_POST['productlocation'],
                        'pool_type' => $_POST['pool'],
                        'description_ru' => $_POST['productdesc'],
                        'for_childrens_ru' => $_POST['productforchild'],
                        'neerby_ru' => $_POST['productnear'],
                        'supplier_id' => $_POST['supplier']]);

    return back();
  }

  public function add(Request $request)
  {
    if ($request->session()->has('emp')) {
      $villaname = $_POST['villaname'];
      $url = mb_convert_case($_POST['villaname'], MB_CASE_LOWER, "UTF-8");
      $url = str_replace(' ', '_', $url);
      $url = str_replace('+', '_', $url);
      echo $url;
      $data = [
        'apartment_name_en' => $_POST['villaname'],
        'apartment_name_ru' => $_POST['villaname'],
        'apartment_url' => $url,
        'apartment_district_id' => $_POST['city'],
        'apartment_address' => $_POST['adress'],
        'max_guest' => $_POST['maxgue'],
        'rooms' => $_POST['room'],
        'apartment_status' => $_POST['status'],
        'price' => $_POST['sellingprice'],
        'buying_price' => $_POST['buyingprice'],
        'currency' => $_POST['currency'],
        'gps' => $_POST['productlocation'],
        'pool_type' => $_POST['pool'],
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
        File::makeDirectory($mypath,0755,TRUE);
      }else {
        echo "Klasör İsmi Hatalı/Var";
        exit;
      }
      $affected =  DB::table('apartment')
                  ->insert($data);
      return redirect('/en/administrator/apartments-detail/'.$url);
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

      $deletevilla = \DB::table('apartment')
              ->where('apartment_url', $name)
              ->delete();

      $deletevillaprice = \DB::table('apartments_price')
            ->where('apartment_url', $name)
            ->delete();

      return redirect('/en/administrator/apartments/');
    }
  }

  public function addview(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $apart = $this->getApartments();
      $currency = $this->getCurrency();
      $district = $this->getDistrict();
      $supplier = $this->getSupplier();
      $data = [
        'local' => $local,
        'currency_iso' => $currency,
        'district' => $district,
        'supplier' => $supplier,
        'products' => $apart,
      ];
      return view('backend.default.apartments-add', $data);
    }
  }

  public function searchview($srcname)
  {
    $searchvilla = \DB::table('apartment')
            ->where('apartment_name_en', 'LIKE', "%{$srcname}%")
            ->orderByDesc('apartment.apartment_id')
            ->simplePaginate(150);
    return $searchvilla;
  }

  public function filterview($pool, $sprc, $bprc, $distar, $roomar)
  {
    $query = DB::table('apartment')
          ->orderByDesc('apartment.currency_price');
            if ($roomar) {
                $query->where('rooms', '=', $roomar);
            }
            if ($distar) {
                $query->where('apartment_district_id', '=', $distar);
            }
            $query->where('currency_price', '<', $bprc)
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

  public function getApartments()
  {
    $findThemTr = \DB::table('apartment')
            ->orderByDesc('apartment.apartment_id')
            ->simplePaginate(24);
    return $findThemTr;
  }
  public function getApartsDesc()
  {
    $findThemTr = \DB::table('apartment')
            ->orderByDesc('apartment.currency_price')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getApartsAsc()
  {
    $findThemTr = \DB::table('apartment')
            ->orderBy('apartment.currency_price', 'asc')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getactiveAparts()
  {
    $findThemTr = \DB::table('apartment')
            ->where('apartment_status', '1')
            ->get();
    return $findThemTr;
  }

  public function getFindAparts($name)
  {
    $findThemTr = \DB::table('apartment')
            ->where('apartment_url', $name)
            ->get();
    return $findThemTr;
  }

  public function getFindApartsPer($name)
  {
    $findThemTr = \DB::table('apartments_price')
            ->where('apartment_url', $name)
            ->get();
    return $findThemTr;
  }

  public function getDistrict()
  {
    $findThemTr = \DB::table('destination_district')
            ->where('status',1)
            ->get();
    return $findThemTr;
  }

  public function getSupplier()
  {
    $findThemTr = \DB::table('supplier')
            ->where('active',1)
            ->get();
    return $findThemTr;
  }

  public function getCurrency()
  {
    $findThemTr = \DB::table('currency')
            ->where('active',1)
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
          'apartment_data' => $villadat,
          'apartment_url' => $vln,
          'update_date' => date("Ymd")
        ];
        $affected =  DB::table('apartments_price')->where('apartment_url', $_POST['namevil'])->update($data);
        return back();
      }
    }
   $affected =  DB::table('apartments_price')
                   ->insert([
                     'apartment_data' => $villadat,
                     'apartment_url' => $vln,
                     'update_date' => date("Ymd")
                      ]);
   return back();
  }

  public function getFindPrice($url)
  {
    $findThemTr = \DB::table('apartments_price')
            ->where('apartment_url', $url)
            ->get();
    return $findThemTr;
  }
}
