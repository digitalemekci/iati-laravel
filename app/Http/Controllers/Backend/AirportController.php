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

class AirportController extends BaseController
{
  public function getAirport()
  {
    $iaGetAir = new IatiController();
    $auth = $iaGetAir->auth();
    $contentDump = $iaGetAir->getAirports($auth);
    return $contentDump;
  }

  public function saveAirport()
  {
    $getAir = $this->getAirport();
    exit;
    foreach ($getAir as $key) {
        $data = [
        'iata_code' => $key["iata_code"],
        'name' => $key["name"],
        'city_code' => $key["city_code"],
        'city_name' => $key["city_name"],
        'country_name' => $key["country_name"],
        'country_code' => $key["country_code"],
        'latitude' => $key["latitude"],
        'longitude' => $key["longitude"]
      ];
      $affected =  DB::table('airport')
                  ->insert($data);
    }
    echo "added";
    exit;

  }

  public function checkAirports()
  {
    $findThemTr = \DB::table('airport')
            ->where('villa_url', $name)
            ->get();
  }
}
