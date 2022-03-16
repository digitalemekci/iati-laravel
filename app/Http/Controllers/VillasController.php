<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class VillasController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function listing($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $branding = new BrandingController();
      $branding_villas = $branding->getVillas();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);

      $villas = $this->getVillasTour();
      $apartment = $this->getApartmentTour();

      $tags = DB::table('theme_tags')->get();

      $data = [
            'local' => $local,
            'crrn' => $curall,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'villas' => $villas,
            'aparts' => $apartment,
            'themetours' => $themetours,
            'vls_det' => $branding_villas,
            'dailyex' => $dailyexc,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('villas', $data);
    }


    public function listdesc($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillasDesc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $branding = new BrandingController();
      $branding_villas = $branding->getVillas();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);
      $tags = DB::table('theme_tags')->get();

        if (isset($_GET['search'])) {
          $villas  = $this->searchview($_GET['search']);
        }
      if (isset($_GET['submitfilter'])) {
        $arrayroom = array();
          for ($i=1; $i < 100 ; $i++) {
            if (isset($_GET['room'.$i])) {
              array_push($arrayroom, $i);
            }
          }
          $arraydist = array();
          for ($d=1; $d < 15 ; $d++) {
            if (isset($_GET['district'.$d])) {
              echo $_GET['district'.$d]."</br>";
              array_push($arraydist, $d);
            }
          }
        $prc = explode(";", $_GET['pricerange']);
        $villas  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }

      $district = $this->getDistrict();
      $data = [
            'local' => $local,
            'district' => $district,
            'crrn' => $curall,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'eventsm' => $events,
            'themetours' => $themetours,
            'servicesm' => $servicesm,
            'vls_det' => $branding_villas,
            'villas' => $villas,
            'tag' => $tags
      ];
      return view('villas', $data);
    }

    public function listasc($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $villas = $this->getVillasAsc();
      $currency = new CurrencyController();
      $curall = $currency->getCurrency();
      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $branding = new BrandingController();
      $branding_villas = $branding->getVillas();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);
      $tags = DB::table('theme_tags')->get();
        if (isset($_GET['search'])) {
          $villas  = $this->searchview($_GET['search']);
        }
      if (isset($_GET['submitfilter'])) {
        $arrayroom = array();
          for ($i=1; $i < 13 ; $i++) {
            if (isset($_GET['room'.$i])) {
              array_push($arrayroom, $i);
            }
          }
          $arraydist = array();
          for ($d=1; $d < 13 ; $d++) {
            if (isset($_GET['district'.$d])) {
              array_push($arraydist, $d);
            }
          }
        $prc = explode(";", $_GET['pricerange']);
        $villas  = $this->filterview($_GET['pool'], $prc[0], $prc[1], $arraydist, $arrayroom);
      }
      if (!isset($_GET['search']) && !isset($_GET['submitfilter']))
      {
      }

      $district = $this->getDistrict();
      $data = [
        'local' => $local,
        'district' => $district,
        'crrn' => $curall,
        'countrys' => $destn,
        'citys' => $destn_cty,
        'eventsm' => $events,
        'themetours' => $themetours,
        'servicesm' => $servicesm,
        'vls_det' => $branding_villas,
        'villas' => $villas,
        'tag' => $tags
      ];
      return view('villas', $data);
    }




    public function index($locall, $villasname)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $currency = new CurrencyController();
      $curall = $currency->getCurrency();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();

      $villas = $this->getVillasTour();
      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour($thematictour);

      $tags = DB::table('theme_tags')->get();
      if (strstr($villasname,"flat")) {
        $apartmentdet = $this->getFindApartmentTour($villasname);

        $data = [
              'local' => $local,
              'crrn' => $curall,
              'countrys' => $destn,
              'citys' => $destn_cty,
              'eventsm' => $events,
              'servicesm' => $servicesm,
              'themetours' => $themetours,
              'dailyex' => $dailyexc,
              'villas' => $villas,
              'apartment' => $apartmentdet,
              'tag' => $tags
        ];
        // return view('index')->with('local', $data);
        return view('apartment-detail', $data);
  	  }else{
        $villasdet = $this->getFindVillasTour($villasname);
        $meta = new MetaController();
        $metadata = $meta->getFindMeta($villasname);

        $data = [
              'local' => $local,
              'crrn' => $curall,
              'countrys' => $destn,
              'citys' => $destn_cty,
              'eventsm' => $events,
              'servicesm' => $servicesm,
              'themetours' => $themetours,
              'dailyex' => $dailyexc,
              'villas' => $villas,
              'villasdet' => $villasdet,
              'meta' => $metadata,
              'tag' => $tags
        ];
        // return view('index')->with('local', $data);
        return view('villas-detail', $data);
      }
    }

    public function getVillasTour()
    {
      $findThemTr = \DB::table('villas')
              ->where('villa_status',1)
              ->orderByDesc('villas.price')
              ->simplePaginate(8);
      return $findThemTr;
    }

    public function getVillasDesc()
    {
      $findThemTr = \DB::table('villas')
              ->where('villa_status',1)
              ->orderByDesc('villas.currency_price')
              ->simplePaginate(24);
      return $findThemTr;
    }

    public function getVillasAsc()
    {
      $findThemTr = \DB::table('villas')
              ->where('villa_status',1)
              ->orderBy('villas.currency_price', 'asc')
              ->simplePaginate(24);
      return $findThemTr;
    }

    public function getApartmentTour()
    {
      $findThemTr = \DB::table('apartment')
              ->where('apartment_status',1)
              ->orderByDesc('price')
              ->simplePaginate(8);
      return $findThemTr;
    }


    public function getFindVillasTour($villa)
    {
      $findThemTr = \DB::table('villas')
              ->where('villa_url', $villa)
              ->where('villa_status',1)
              ->get();
      return $findThemTr;
    }

    public function getFindApartmentTour($villa)
    {
      $findThemTr = \DB::table('apartment')
              ->where('apartment_url', $villa)
              ->where('apartment_status',1)
              ->get();
      return $findThemTr;
    }

    public function getDistrict()
    {
      $findThemTr = \DB::table('destination_district')
              ->where('status',1)
              ->orderBy('district_name', 'asc')
              ->get();
      return $findThemTr;
    }

}
