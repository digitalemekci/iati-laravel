<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SupplierController extends BaseController
{
  public function list(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $pq =  "https://www.tcmb.gov.tr/kurlar/today.xml";
      $pq_file_stored = dirname(__FILE__).'/xml/currency-'.date("Ymdhi").'.xml';
      $buffer_start    = time();
      if(!file_exists($pq_file_stored))
      if(!copy($pq, $pq_file_stored))
        die('xml_file_not_copied');
      $cr 		= simplexml_load_file($pq_file_stored);
        if ($cr->Currency[0]["Kod"] == "USD") {
          $usdtrycrn = $cr->Currency[0]->BanknoteSelling;
        }
        if ($cr->Currency[3]["Kod"] == "EUR") {
          $eurtrycrn = $cr->Currency[3]->BanknoteSelling;
          $eurusdcrn = $cr->Currency[3]->CrossRateOther;
          $eurrubcrn = $eurusdcrn*$cr->Currency[14]->CrossRateUSD;
        }
        if ($cr->Currency[14]["Kod"] == "RUB") {
          $usdrubcrn = $cr->Currency[14]->CrossRateUSD;
          $rubtrycrn = $cr->Currency[14]->ForexSelling;
        }
      $supplier = $this->getSupplier();
      $data = [
            'local' => $local,
            'usdtry' => $usdtrycrn,
            'eurrub' => $eurrubcrn,
            'usdrub' => $usdrubcrn,
            'tryrub' => $rubtrycrn,
            'eurusd' => $eurusdcrn,
            'supplier' => $supplier,
            'eurtry' => $eurtrycrn,
      ];
      return view('backend.default.supplier-list', $data);
    }
  }

  public function select(Request $request, $locall, $id)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $supdet = $this->getFindSupplier($id);
      $suptpro = $this->getVillastoSupp($id);
      $suptapt = $this->getAparttoSupp($id);
      $crncy = new CurrencyController();
      $currency = $crncy->getCurrency();
      $supplier = $this->getSupplier();
      $data = [
            'local' => $local,
            'currency_iso' => $currency,
            'supplier' => $supplier,
            'sup' => $supdet,
            'suppro' => $suptpro,
            'supapt' => $suptapt
      ];
      return view('backend.default.supplier-detail', $data);
    }
  }

  public function addview(Request $request, $locall)
  {
    if ($request->session()->has('emp')) {
      $l = \App::setLocale($locall);
      $local= \App::getLocale();
      $data = [
        'local' => $local
      ];
      return view('backend.default.supplier-add', $data);
    }
  }

  public function add(Request $request)
  {
    if ($request->session()->has('emp')) {
      $spsta = 1;
      $local= \App::getLocale();
      $crncy = new CurrencyController();
      $currency = $crncy->getCurrency();
      $supplier = $this->getSupplier();
      $checkname = $this->getFindSupplierName($_POST['suppname']);


      // foreach ($checkname as $ce) {
      //   echo $ce->supplier_id;
      //   if (empty($ce->supplier_id)) {
      //     echo "string";
      //   }
      // }
      //
      // exit;

      $loco = [
            'local' => $local,
            'currency_iso' => $currency,
            'supplier' => $supplier
      ];
      $data = [
        'supplier_name' => $_POST['suppname'],
        'supplier_phone' => $_POST['suppphone'],
        'supplier_mail' => $_POST['suppemail'],
        'department' => $_POST['spec'],
        'note' => $_POST['note'],
        'active' => $spsta
      ];

      $affected =  DB::table('supplier')->insert($data);
      return view('backend.default.supplier-list', $loco);
    }
  }

public function updateNote()
{
  $affected = DB::table('supplier')
            ->where('supplier_id', $_POST['id'])
            ->update(['note' => $_POST['note']]);
  return back();
}
  public function getSupplier()
  {
    $findThemTr = \DB::table('supplier')
            ->orderByDesc('supplier.supplier_id')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getFindSupplier($id)
  {
    $findThemTr = \DB::table('supplier')
            ->where('supplier_id', $id)
            ->orderByDesc('supplier.supplier_id')
            ->simplePaginate(24);
    return $findThemTr;
  }

  public function getFindSupplierName($name)
  {
    $findThemTr = \DB::table('supplier')
            ->where('supplier_name', $name)
            ->get();
    return $findThemTr;
  }

  public function getVillastoSupp($id)
  {
      $findThemTr = \DB::table('villas')
            ->where('supplier_id',$id)
            ->get();
      return $findThemTr;
  }

  public function getAparttoSupp($id)
  {
      $findThemTr = \DB::table('apartment')
            ->where('supplier_id',$id)
            ->get();
      return $findThemTr;
  }


}
