<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BrandingController extends BaseController
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();
      $destn_cty_lmt = $destination->getDestinationCityLimit();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();
      $tags = DB::table('theme_tags')->get();

      $aboutus = $this->getAboutUs();

      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'dailyex' => $dailyexc,
            'aboutus' => $aboutus,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('about-us', $data);
    }

    public function contact($locall)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();
      $destn_cty_lmt = $destination->getDestinationCityLimit();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();
      $tags = DB::table('theme_tags')->get();

      $aboutus = $this->getAboutUs();
      $blogs = $this->getBlogs();


      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'dailyex' => $dailyexc,
            'aboutus' => $aboutus,
            'blogs' => $blogs,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('contact', $data);
    }


    public function blog($locall, $name)
    {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();

      $destination = new DestinationController();
      $destn = $destination->getDestinationCountry();
      $destn_cty = $destination->getDestinationCity();
      $destn_cty_lmt = $destination->getDestinationCityLimit();

      $thematictour = new ThematicController();
      $themetours = $thematictour->getThematicTour();

      $dailyex = new DailyexcursionsController();
      $dailyexc = $dailyex->getDailyexcursion();

      $event = new EventController();
      $events = $event->getEvent();

      $services = new ServicesController();
      $servicesm = $services->getServices();
      $tags = DB::table('theme_tags')->get();

      $aboutus = $this->getAboutUs();
      $blogs = $this->getBlogs();
      $blog = $this->getBlog($name);

      $data = [
            'local' => $local,
            'countrys' => $destn,
            'citys' => $destn_cty,
            'themetours' => $themetours,
            'eventsm' => $events,
            'servicesm' => $servicesm,
            'dailyex' => $dailyexc,
            'aboutus' => $aboutus,
            'blogs' => $blogs,
            'blog' => $blog,
            'tag' => $tags
      ];
      // return view('index')->with('local', $data);
      return view('blog-detail', $data);
    }

    public function getAboutUs()
    {
      $dest = \DB::table('brand')
              ->where('desc_id',1)
              ->get();
      return $dest;
    }

    public function getBlogs()
    {
      $dest = \DB::table('blog')
              ->where('blog_status',1)
              ->get();
      return $dest;
    }

    public function getBlog($name)
    {
      $dest = \DB::table('blog')
              ->where('blog_title_en',$name)
              ->get();
      return $dest;
    }

    public function getYachts()
    {
      $dest = \DB::table('brand')
              ->where('desc_id',2)
              ->get();
      return $dest;
    }

    public function getVillas()
    {
      $dest = \DB::table('brand')
              ->where('desc_id',3)
              ->get();
      return $dest;
    }
    //
    // public function getDestinationCity()
    // {
    //   $city = \DB::table('destination_city')
    //           ->where('city_status',1)
    //           ->get();
    //   return $city;
    // }
    //
    // public function getFindCity($place)
    // {
    //   $findDest = \DB::table('destination_city')
    //           ->where('city_name', $place)
    //           ->where('city_status',1)
    //           ->get();
    //   return $findDest;
    // }
    //
    // public function getDestinationCityLimit()
    // {
    //   $city = \DB::table('destination_city')
    //           ->where('city_status',1)
    //           ->limit(4)
    //           ->get();
    //   return $city;
    // }

    // public function getFindCityAllProduct($place)
    // {
    //   $findDest = \DB::table('destination_city', 'theme', 'villas')
    //           ->where('city_name', $place)
    //           ->where('city_status',1)
    //           ->get();
    //   return $findDest;
    // }



}
