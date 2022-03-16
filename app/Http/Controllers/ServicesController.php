<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ServicesController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $service)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $event = new EventController();
      $eventsm = $event->getEvent();

      $servicesm = $this->getServices();
      $service = $this->getFindService($service);
      $tags = DB::table('theme_tags')->get();


      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'eventsm' => $eventsm,
            'servicesm' => $servicesm,
            'service' => $service,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('services', $data);
    }

    public function getServices()
    {
      $findThemTr = \DB::table('services')
              ->where('service_status',1)
              ->get();
      return $findThemTr;
    }


    public function getFindService($service)
    {
      $findThemTr = \DB::table('services')
              ->where('service_name', $service)
              ->where('service_status',1)
              ->get();
      return $findThemTr;
    }



}
