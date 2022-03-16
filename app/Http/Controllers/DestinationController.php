<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DestinationController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $place)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $pq =  "https://www.tcmb.gov.tr/kurlar/today.xml";
      $pq_file_stored = dirname(__FILE__).'/xml/currency-'.date("Ymdhi").'.xml';

      $buffer_start    = time();
  		if(!file_exists($pq_file_stored))
  		if(!copy($pq, $pq_file_stored))
  			die('xml_file_not_copied');

      $cr 		= simplexml_load_file($pq_file_stored);

        if ($cr->Currency[0]["Kod"] == "USD") {
          $usdtrycrn = $cr->Currency[0]->BanknoteSelling;
        }
        if ($cr->Currency[3]["Kod"] == "EUR") {
          $eurtrycrn = $cr->Currency[3]->BanknoteSelling;
          $eurusdcrn = $cr->Currency[3]->CrossRateOther;
          $eurrubcrn = $eurusdcrn*$cr->Currency[14]->CrossRateUSD;
        }
        if ($cr->Currency[14]["Kod"] == "RUB") {
          $usdrubcrn = $cr->Currency[14]->CrossRateUSD;
          $rubtrycrn = $cr->Currency[14]->ForexSelling;
        }

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();
      $destn_cty_lmt = $destination->getDestinationCityLimit();
      $plc = $destination->getFindCity($place);

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();
      $tags = DB::table('theme_tags')->get();

      $data = [
            'local' => $local,
            'usdtry' => $usdtrycrn,
            'eurrub' => $eurrubcrn,
            'usdrub' => $usdrubcrn,
            'tryrub' => $rubtrycrn,
            'eurusd' => $eurusdcrn,
            'eurtry' => $eurtrycrn,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'places' => $plc,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'themetours' => $themetours,
            'theme' => $thematictour,
            'dailyex' => $dailyexc,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('destinations', $data);
    }

    public function getDestinationCountry()
    {
      $dest = \DB::table('destination_country')
              ->where('destination_status',1)
              ->get();
      return $dest;
    }

    public function getDestinationCity()
    {
      $city = \DB::table('destination_city')
              ->where('city_status',1)
              ->get();
      return $city;
    }

    public function getFindCity($place)
    {
      $findDest = \DB::table('destination_city')
              ->where('city_name', $place)
              ->where('city_status',1)
              ->get();
      return $findDest;
    }

    public function getDestinationCityLimit()
    {
      $city = \DB::table('destination_city')
              ->where('city_status',1)
              ->limit(4)
              ->get();
      return $city;
    }

    // public function getFindCityAllProduct($place)
    // {
    //   $findDest = \DB::table('destination_city', 'theme', 'villas')
    //           ->where('city_name', $place)
    //           ->where('city_status',1)
    //           ->get();
    //   return $findDest;
    // }



}
