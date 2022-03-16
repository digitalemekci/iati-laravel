<?php

namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;


class MainController extends BaseController
{
  protected $local;

    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index($locall)
    {
      // echo $locall." was here</br></br>";
      // $l = \App::setLocale($locall);

      // if (is_null($local)) {
      //   Route::get('/', 'MainController@index');
      // }

       \App::setLocale($locall);
      $local= \App::getLocale();

      $pq =  "https://www.tcmb.gov.tr/kurlar/today.xml";
      $pq_file_stored = dirname(__FILE__).'/xml/currency-'.date("Ymdh").'.xml';

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

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $eventsm = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $branding = new BrandingController();
      $blogs = $branding->getBlogs();

      $tg = new TagController();
      $gettags = $tg->getTags();
      $gettagslmt = $tg->getFindTagLimit();
      $sliders = $this->getSliders();

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
            'citys_lmt' => $destn_cty_lmt,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'eventsm' => $eventsm,
            'servicesm' => $servicesm,
            'taglmt' => $gettagslmt,
            'blogs' => $blogs,
            'sliders' => $sliders,
            'tag' => $gettags

      ];
      // return view('index')->with('local', $data);
      return view('index', $data);

    }

    public function nlindex()
    {

      // $l = \App::setLocale($locall);
      // $client  = @$_SERVER['REMOTE_ADDR'];
      // $local = file_get_contents("http://ipinfo.io/".$client."/country");
      $local = "RU";
      // if (is_null($local)) {
      //   Route::get('/', 'MainController@index');
      // }
        $locRU = "RU";
        $konumRU = strpos($local, $locRU);
      if ($konumRU !== false) {
          $local = "ru";
      }else{
        $local = "en";
      }
       \App::setLocale($local);
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

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $eventsm = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $branding = new BrandingController();
      $blogs = $branding->getBlogs();

      $tg = new TagController();
      $gettags = $tg->getTags();
      $gettagslmt = $tg->getFindTagLimit();
      $sliders = $this->getSliders();

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
            'citys_lmt' => $destn_cty_lmt,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'eventsm' => $eventsm,
            'servicesm' => $servicesm,
            'taglmt' => $gettagslmt,
            'blogs' => $blogs,
            'sliders' => $sliders,
            'tag' => $gettags

      ];
      // return view('index')->with('local', $data);
      return view('index', $data);

    }

    public function getSliders()
    {
      $findThemTr = \DB::table('slider')
              ->where('slider_status',1)
              ->get();
      return $findThemTr;
    }

    public function sendMail(Request $request)
    {
      return $request->all();

    }





}
