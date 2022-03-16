<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ThematicController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $thematictour)
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

      $themetour = $this->getThematicTour();
      $themetour_det = $this->getFindThematicTour($thematictour);

      $tg = new TagController();
      $gettags = $tg->getTags();

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
            'themetours' => $themetour,
            'themetour_det' => $themetour_det,
            'dailyex' => $dailyexc,
            'tag' => $gettags
      ];
      // return view('index')->with('local', $data);
      return view('thematictours-detail', $data);
    }

    public function listing($locall, $tags)
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

      $themetour = $this->getThematicTour();


      $tg = new TagController();
      $gettags = $tg->getTags();
      $tag_det = $tg->getFindTag($tags);



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
            'themetours' => $themetour,
            'dailyex' => $dailyexc,
            'tag' => $gettags,
            'tag_det' => $tag_det

      ];
      // return view('index')->with('local', $data);
      return view('thematictours', $data);
    }

    public function getThematicTour()
    {
      $findThemTr = \DB::table('theme')
              ->where('theme_status',1)
              ->get();
      return $findThemTr;
    }


    public function getFindThematicTour($thematictour)
    {
      $findThemTr = \DB::table('theme')
              ->where('theme_name', $thematictour)
              ->where('theme_status',1)
              ->get();
      return $findThemTr;
    }



}
