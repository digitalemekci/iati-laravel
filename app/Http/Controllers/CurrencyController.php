<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CurrencyController extends BaseController
{

  public function updaterate()
  {
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

  public function getCurrency()
  {
    $findThemTr = \DB::table('currency')
            ->where('active', '1')
            ->get();
    return $findThemTr;
  }










}
