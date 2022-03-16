<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class YachtsController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function listing($locall)
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

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $branding = new BrandingController();
      $branding_yachts = $branding->getYachts();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);


      // $yachts = DB::table('yachts')->simplePaginate(15);
      $yachts = $this->getYachtsTour();
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
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'yachts' => $yachts,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'ycht_det' => $branding_yachts,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('yachts', $data);
    }


    public function index($locall, $yachtsname)
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

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $yachts = $this->getYachtsTour();

      $yachtsdet = $this->getFindYachtsTour($yachtsname);
      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);

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
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'yachts' => $yachts,
            'yachtsdet' => $yachtsdet,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('yachts-detail', $data);
    }

    public function getYachtsTour()
    {
      $findThemTr = \DB::table('yachts')
              ->where('yachts_status',1)
              ->orderByDesc('price')
              ->simplePaginate(15);
      return $findThemTr;
    }


    public function getFindYachtsTour($villa)
    {
      $findThemTr = \DB::table('yachts')
              ->where('yachts_url', $villa)
              ->where('yachts_status',1)
              ->get();
      return $findThemTr;
    }


}
