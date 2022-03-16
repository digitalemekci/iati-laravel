<?php

namespace App\Http\Controllers\Iati;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class IatiController extends BaseController
{
      public function index(Request $request, $locall)
      {
        // if ($request->session()->has('emp')) {
          $l = \App::setLocale($locall);
          $local= \App::getLocale();
          // auth api return token
          $authtoken = $this->auth();

          // IATI Hotel API Class
          $this->hotelContentDump($authtoken);
          // $htavail = $this->hotelAvailabilitySearch($authtoken);
          // foreach ($htavail as $key) {
          //   echo "<pre>";
          //   var_dump($key);
          //   echo "</pre>";
          // }

////////////////////////////////////////////////////////////////////////////////

          // IATI Flight API Class
          // $flgprt = $this->getAirports($authtoken);
          // foreach ($flgprt as $key) {
            // echo "<pre>";
            // var_dump($flgprt);
            // echo "</pre>";
          // }
        exit;
      }

      public function auth()
      {
        $authcode = "";
        $secret = "";


        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '. base64_encode("$authcode:$secret")
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
                  array(
                    "Authorization: Basic " . base64_encode($authcode.":".$secret)
                  ));
        curl_setopt($ch, CURLOPT_URL, 'http://testapi.iati.com/rest/auth/token');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION , 1 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER , true );
        $obj = curl_exec($ch);
        $objen = json_encode($obj);
        $objdec = json_decode($objen);
        $array = json_decode($obj, true /*[bool $assoc = false]*/);
        $in1step = $array["result"];
        foreach ($in1step as $key => $value) {
          $token = $value;
        }
        curl_close($ch);
        return $token;
      }


      // IATI Hotel API Class
      public function hotelContentDump($token)
      {
        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, 'http://testapi.iati.com/rest/hotel/v2/content/hotels');
        $result = curl_exec($ch);
        $array = json_decode($result, true /*[bool $assoc = false]*/);
        var_dump($result);
        exit;
        $in2step = $array["result"]["hotels"];
        curl_close($ch);
        return $in2step;
      }

      public function hotelContentDumpLang($token)
      {
        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        );
        $post =  array('locale' => "TR", );
        $jsenc = json_encode($post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsenc);
        curl_setopt($ch, CURLOPT_URL, 'http://testapi.iati.com/rest/hotel/v2/content/hotels');
        $result = curl_exec($ch);
        $array = json_decode($result, true /*[bool $assoc = false]*/);
        $in2step = $array["result"]["hotels"];
        curl_close($ch);
        return $in2step;
      }

      public function hotelAvailabilitySearch($token, $check_in, $check_out, $nationality, $district, $adults, $child)
      {
        $ch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        );
        $post =  array(
          'check_in' => $check_in,
          'check_out' => $check_out,
          'customer_nationality' => $nationality,
          "destination" => array(
            "code" => $district,
            "type" => "DISTRICT",
          ),
          "rooms" => [array(
            "adults" => (int)$adults,
            "children" => (int)$child,
            "children_ages" => [3],
          )]
        );
        $jsenc = json_encode($post);
        var_dump($jsenc);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsenc);
        curl_setopt($ch, CURLOPT_URL, 'http://testapi.iati.com/rest/hotel/v2/hotels');
        $result = curl_exec($ch);
        $array = json_decode($result, true /*[bool $assoc = false]*/);
        $in2step = $array["result"]["hotels"];
        curl_close($ch);
        return $in2step;
      }

      // IATI Flight API Class

      public function getAirports($token)
      {
        $ch = curl_init();
          $lg = "TR";
        $headers = array(
          'Content-Type: application/json',
          'Authorization: Bearer '.$token
        );
         $post =  array('language_code' => "TR", );
         $jsenc = json_encode($post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsenc);
        curl_setopt($ch, CURLOPT_URL, 'http://testapi.iati.com/rest/flight/v2/airport');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        $array = json_decode($result, true /*[bool $assoc = false]*/);
        $in2step = $array["result"];
        curl_close($ch);
        return $in2step;
      }

}
