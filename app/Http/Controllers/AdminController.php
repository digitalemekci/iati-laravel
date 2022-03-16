<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminController extends BaseController
{


  public function index($locall)
  {
    $l = \App::setLocale($locall);
    $local= \App::getLocale();
  
    return view('backend.default.index');
  }
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // public function index($locall)
    // {
    //   $l = \App::setLocale($locall);
    //   $local= \App::getLocale();

      // $destination = new DestinationController();
      // $destn = $destination->getDestinationCountry();
      // $destn_cty = $destination->getDestinationCity();
      // $destn_cty_lmt = $destination->getDestinationCityLimit();
      //
      // $thematictour = new ThematicController();
      // $themetours = $thematictour->getThematicTour();
      //
      // $dailyex = new DailyexcursionsController();
      // $dailyexc = $dailyex->getDailyexcursion();
      //
      // $event = new EventController();
      // $events = $event->getEvent();
      //
      // $services = new ServicesController();
      // $servicesm = $services->getServices();
      // $tags = DB::table('theme_tags')->get();



      // $data = [
            // 'local' => $local
            // 'countrys' => $destn,
            // 'citys' => $destn_cty,
            // 'themetours' => $themetours,
            // 'eventsm' => $events,
            // 'servicesm' => $servicesm,
            // 'dailyex' => $dailyexc,
            // 'tag' => $tags
      // ];
      // return view('index')->with('local', $data);
    //   return redirect('/backend/layout');
    // }
}
