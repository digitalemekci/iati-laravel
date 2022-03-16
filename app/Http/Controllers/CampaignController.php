<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CampaignController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall, $campaign)
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

      $event = new EventController();
      $eventsm = $event->getEvent();

      $campaigns = $this->getFindCampaign($campaign);
      $campaignsm = $this->getCampaign();
      $tags = DB::table('theme_tags')->get();

      $data = [
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'dailyex' => $dailyexc,
            'servicesm' => $servicesm,
            'campaignsm' => $campaignsm,
            'campaigns' => $campaigns,
            'eventsm' => $eventsm,
            'local' => $local,
            'tag' => $tags
      ];
      return view('campaign', $data);
    }

    public function getCampaign()
    {
      $findThemTr = \DB::table('campaign')
              ->where('campaign_status',1)
              ->get();
      return $findThemTr;
    }


    public function getFindCampaign($campaign)
    {
      $findThemTr = \DB::table('campaign')
              ->where('campaign_name_en', $campaign)
              ->where('campaign_status',1)
              ->get();
      return $findThemTr;
    }



}
