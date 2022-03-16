<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CurrencyController extends BaseController
{
  public function list(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $curall = $this->getCurrency();
      $data = [
            'local' => $local,
            'currencys' => $curall,
      ];
      return view('backend.default.currency', $data);
    }
  }

  public function updaterate(Request $request)
  {
    if ($request->session()->has('emp')) {
      $pq =  "https://www.tcmb.gov.tr/kurlar/today.xml";
      $pq_file_stored = dirname(__FILE__).'/xml/currency-auto-'.date("Ymd").'.xml';
      $buffer_start    = time();
      if(!file_exists($pq_file_stored))
      if(!copy($pq, $pq_file_stored))
        die('xml_file_not_copied');
      $cr 		= simplexml_load_file($pq_file_stored);
      $usd = 1;
      $dolar = $cr->Currency[0]->BanknoteSelling;
      $usdeur = $cr->Currency[3]->CrossRateOther;
      $usdgbp = $cr->Currency[4]->CrossRateOther;
      $usdrub = $cr->Currency[14]->CrossRateUSD;
      $tl_rate = number_format($usd/$dolar,6);
      $eur_rate = number_format($usd/$usdeur,6);
      $gbp_rate = number_format($usd/$usdgbp,6);
      $rub_rate = $usdrub;
      $affectedtr = DB::table('currency')
                ->where('iso_code', 'TRY')
                ->update(['conversion_rate' => $tl_rate]);
      $affectedgbp = DB::table('currency')
                ->where('iso_code', 'EUR')
                ->update(['conversion_rate' => $eur_rate]);
      $affectedeur = DB::table('currency')
                ->where('iso_code', 'GBP')
                ->update(['conversion_rate' => $gbp_rate]);
      $affectedrub = DB::table('currency')
                ->where('iso_code', 'RUB')
                ->update(['conversion_rate' => $rub_rate]);
      return back();
    }
  }

  public function updateproducts(Request $request)
  {
    if ($request->session()->has('emp')) {
      $curall = $this->getCurrency();

      $villas = new VillasController();
      $getvillas = $villas->getactiveVillas();

      $aparts = new ApartmentsController();
      $getaparts = $aparts->getactiveAparts();

      $yachts = new YachtsController();
      $getyachts = $yachts->getactiveYachts();

      foreach ($getvillas as $vlpr) {
          foreach ($curall as $rate) {
            if ($vlpr->currency == $rate->iso_code) {
              if ($vlpr->currency == "GBP") {
                $usdprc= number_format($vlpr->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($vlpr->currency == "EUR") {
                $usdprc= number_format($vlpr->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($vlpr->currency == "RUB") {
                $usdprc= number_format($vlpr->price*$rate->conversion_rate, 2, '.', '');
              }
              if ($vlpr->currency == "TRY") {
                $usdprc= number_format($vlpr->price*$rate->conversion_rate, 2, '.', '');
              }
              $affected = DB::table('villas')
                        ->where('villa_id', $vlpr->villa_id)
                        ->update(['currency_price' => $usdprc]);
            }
          }
      }
      foreach ($getaparts as $apts) {
          foreach ($curall as $rate) {
            if ($apts->currency == $rate->iso_code) {
              if ($apts->currency == "GBP") {
                $usdprc= number_format($apts->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($apts->currency == "EUR") {
                $usdprc= number_format($apts->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($apts->currency == "RUB") {
                $usdprc= number_format($apts->price*$rate->conversion_rate, 2, '.', '');
              }
              if ($apts->currency == "TRY") {
                $usdprc= number_format($apts->price*$rate->conversion_rate, 2, '.', '');
              }
              $affected = DB::table('apartment')
                        ->where('apartment_id', $apts->apartment_id)
                        ->update(['currency_price' => $usdprc]);
            }
          }
      }

      foreach ($getyachts as $ycht) {
          foreach ($curall as $rate) {
            if ($ycht->currency == $rate->iso_code) {
              if ($ycht->currency == "GBP") {
                $usdprc= number_format($ycht->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($ycht->currency == "EUR") {
                $usdprc= number_format($ycht->price/$rate->conversion_rate, 2, '.', '');
              }
              if ($ycht->currency == "RUB") {
                $usdprc= number_format($ycht->price*$rate->conversion_rate, 2, '.', '');
              }
              if ($ycht->currency == "TRY") {
                $usdprc= number_format($ycht->price*$rate->conversion_rate, 2, '.', '');
              }
              $affected = DB::table('yachts')
                        ->where('yachts_id', $ycht->yachts_id)
                        ->update(['currency_price' => $usdprc]);
            }
          }
      }
      echo "Updating..</br></br>";
      return back();
    }
  }

  public function getCurrency()
  {
    $findThemTr = \DB::table('currency')
            ->where('active', '1')
            ->get();
    return $findThemTr;
  }
}
