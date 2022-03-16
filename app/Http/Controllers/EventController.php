<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EventController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $event)
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

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $events = $this->getFindEvent($event);
      $eventsm = $this->getEvent();
      $tags = DB::table('theme_tags')->get();

      $data = [
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'servicesm' => $servicesm,
            'eventsm' => $eventsm,
            'events' => $events,
            'local' => $local,
            'tag' => $tags
      ];
      return view('events', $data);
    }

    public function getEvent()
    {
      $findThemTr = \DB::table('events')
              ->where('event_status',1)
              ->get();
      return $findThemTr;
    }


    public function getFindEvent($event)
    {
      $findThemTr = \DB::table('events')
              ->where('event_name', $event)
              ->where('event_status',1)
              ->get();
      return $findThemTr;
    }



}
