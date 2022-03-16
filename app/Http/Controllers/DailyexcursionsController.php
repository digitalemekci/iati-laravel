<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DailyexcursionsController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $excurname)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();


      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $dailyexc = $this->getDailyexcursion();
      $dailyexcdet = $this->getFindDailyexcursion($excurname);
      $tags = DB::table('theme_tags')->get();

      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'dailyex' => $dailyexc,
            'dailyexdet' => $dailyexcdet,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('dailyexcursions-detail', $data);
    }

    public function listing($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();


      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $dailyexc = $this->getDailyexcursion();
      $tags = DB::table('theme_tags')->get();

      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'dailyex' => $dailyexc,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('dailyexcursions', $data);
    }


    public function getDailyexcursion()
    {
      $findThemTr = \DB::table('daily_excursions')
              ->where('excursion_status',1)
              ->get();
      return $findThemTr;
    }

    public function getFindDailyexcursion($excurname)
    {
      $findThemTr = \DB::table('daily_excursions')
              ->where('url', $excurname)
              ->where('excursion_status',1)
              ->get();
      return $findThemTr;
    }



}
