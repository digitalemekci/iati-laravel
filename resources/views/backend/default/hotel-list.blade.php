@extends('backend.layout')

@section('title')
@lang('translation.Products')
@endsection


@section('content')
<?php
  $cur = session('currency');
  // var_dump($hotels);

 ?>
    <div class="row">
        <div class="col-xl-2 col-lg-4">
          <!-- <form action="{{route('filtervillas')}}" method="post"> -->

          <form action="" method="get">
            @CSRF
            <div class="card">
              <div class="p-4 border-top">
                <div class="form-group">
                    <label>Date Range</label>
                    <div>
                        <div class="input-daterange input-group" data-provide="datepicker" data-date-format="yyyy-mm-dd" data-date-autoclose="true">
                            <input type="text" class="form-control" name="check_in" placeholder="check-in"/>
                            <input type="text" class="form-control" name="check_out" placeholder="check-out" />
                        </div>
                    </div>
                </div>
                <div class="form-group mt-3 mt-lg-0">
                    <label class="control-label">Customer Nationality</label>
                    <select name="nationality" class="form-control select2-search-disable">
                        <option>Select</option>
                        <optgroup label="BDT">
                            <option value="RU">Russian</option>
                            <option value="UA">Ukraine</option>
                            <option value="BY">Belarus</option>
                            <option value="KZ">Kazakhstan</option>
                        </optgroup>
                        <optgroup label="Other">
                            <option value="TR">Turkey</option>
                            <option value="DE">Germany</option>
                            <option value="GB">United Kingdom</option>
                            <option value="ES">Spain</option>
                            <option value="IT">Italy</option>
                            <option value="FR">France</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="EG">Egypt</option>
                            <option value="KW">Kuwait</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group mt-3 mt-lg-0">
                    <label class="control-label">District</label>
                    <select name="district" class="form-control select2-search-disable">
                        <option>Select</option>
                        <optgroup label="Antalya">
                            <option value="4116">Alanya</option>
                            <option value="4129">Belek</option>
                            <option value="4122">Kalkan</option>
                            <option value="13072">Kemer</option>
                            <option value="13250">Konyaaltı</option>
                            <option value="11049">Manavgat</option>
                            <option value="26">Muratpaşa</option>
                        </optgroup>
                        <optgroup label="Muğla">
                            <option value="72">Bodrum</option>
                            <option value="4103">Fethiye</option>
                            <option value="4143">Göcek</option>
                            <option value="4415">Marmaris</option>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Adults</label>
                    <select name="adults" class="form-control select2-search-disable">
                        <option value="0">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Childs</label>
                    <select name="child" class="form-control select2-search-disable">
                        <option value="0">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                    </select>
                </div>
              </div>
            </div>
             <input type="submit" class="col-12 btn btn-outline-warning" name="searchAvab" value="Submit">
          </form>
        </div>
        <div class="col-xl-10 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="row">
                            <div class="col-md-6">
                                <div>
                                    <h5>Showing result for Hotels</h5>
                                    <ol class="breadcrumb p-0 bg-transparent mb-2">
                                      <?php
                                        $findThemVil = \DB::table('villas')
                                                ->where('villa_status', 1)
                                                ->count();
                                        $findThemVilpas = \DB::table('villas')
                                                ->where('villa_status', 0)
                                                ->count();
                                        $cont = $findThemVil + $findThemVilpas;
                                       ?>
                                        <b><li class="breadcrumb-item"><a href="javascript: void(0);">Hotels </a>


                                          <span> - Active () / Passive () </span>
                                        </li></b>
                                    </ol>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-inline float-md-right">
                                    <div class="search-box ml-2">
                                      <form action="" method="GET">
                                        <div class="position-relative">
                                            <input type="text" name="search" class="form-control bg-light border-light rounded" placeholder="Search...">
                                            <i class="fas fa-search-location search-icon"></i>
                                        </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                          @foreach ($hotels as $pro_det)
                            <div class="col-xl-4 col-sm-6">
                                <div class="product-box">
                                    <div class="product-img pt-4 px-4">

                                        <a href="{{ url('/'.$local.'/administrator/hotels-detail/'.$pro_det["hotel_code"]) }}" class="text-dark">
                                          <?php
                                            if (isset($pro_det["thumbnail"])) {
                                                $uri = $pro_det["thumbnail"];
                                            }else {
                                              $uri = "https://melinotravel.com/img/logo-nav.png";
                                            }
                                          ?>
                                              <img src="{{ url($uri) }}" alt="" style=" height: 300px; object-fit: cover;" class="img-fluid mx-auto d-block">
                                        </a>

                                    </div>
                                    <div class="text-center product-content p-4">
                                        <h5 class="mb-1"><a href="https://melinotravel.com/img/logo-nav.png" class="text-dark">{{$pro_det["hotel_name"]}}</a></h5>
                                        <span class="mt-3 mb-0">
                                          @if(isset($pro_det["address"]))
                                            {{$pro_det["address"]}}
                                          @endif
                                      </span>
                                    </div>
                                </div>
                            </div>
                          @endforeach
                        </div>
                        <!-- end row -->
                        <div class="row mt-4">
                            <div class="col-sm-6">
                                <div>

                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-sm-right">
                                  <div class="d-flex justify-content-center">
                                  </div>
                                  <div class="row"></div></br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- end row -->
@endsection
