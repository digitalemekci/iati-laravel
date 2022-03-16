<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Iati\IatiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HotelsController extends BaseController
{
  public function list(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      if (isset($_GET['searchAvab'])) {
        $check_in = $_GET['check_in'];
        $check_out = $_GET['check_out'];
        $nationality = $_GET['nationality'];
        $district = $_GET['district'];
        $adults = $_GET['adults'];
        $child = $_GET['child'];


        $iati = new IatiController();
        $auth = $iati->auth();
        $searchAvab = $iati->hotelAvailabilitySearch($auth, $check_in, $check_out, $nationality, $district, $adults, $child);
        $district = $this->getDistrict();
        $currency = new CurrencyController();
        $curall = $currency->getCurrency();

        $data = [
              'local' => $local,
              'district' => $district,
              'crrn' => $curall,
              'hotels' => $searchAvab,
        ];
        return view('backend.default.hotel-list', $data);

        exit;
      }else {
        $getHotels = $this->getHotels();
      
        $district = $this->getDistrict();
        $currency = new CurrencyController();
        $curall = $currency->getCurrency();
        $data = [
              'local' => $local,
              'district' => $district,
              'crrn' => $curall,
              'hotels' => $getHotels,
        ];
        return view('backend.default.hotel-list', $data);
      }
    }
  }

  public function getHotels()
  {
    $iaGetHotels = new IatiController();
    $auth = $iaGetHotels->auth();
    $contentDump = $iaGetHotels->hotelContentDump($auth);
    return $contentDump;
  }

  public function getDistrict()
  {
    $findThemTr = \DB::table('destination_district')
            ->where('status',1)
            ->orderBy('district_name', 'asc')
            ->get();
    return $findThemTr;
  }

  public function searchAvab()
  {
    if (isset($_GET['nationality'])) {
      echo $_GET['nationality']."</br>";
    }
    exit;
  }
}
