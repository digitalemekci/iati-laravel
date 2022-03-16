<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TagController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(Request $request,$tag)
    {
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


      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'servicesm' => $servicesm,
            'eventsm' => $eventsm,
            'events' => $events
      ];
      // return view('index')->with('local', $data);
      return view('events', $data);
    }

    public function getTags()
    {
      $findThemTr = \DB::table('theme_tags')
              ->where('tags_status',1)
              ->get();
      return $findThemTr;
    }


    public function getFindTag($tag)
    {
      $findThemTr = \DB::table('theme_tags')
              ->where('tags_name', $tag)
              ->where('tags_status',1)
              ->get();
      return $findThemTr;
    }

    public function getFindTagLimit()
    {
      $findThemTr = \DB::table('theme_tags')
              ->where('tags_status',1)
              ->limit(8)
              ->get();
      return $findThemTr;
    }



}
