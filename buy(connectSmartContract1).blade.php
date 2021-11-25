@extends('layouts.master')
@section('page-content')
<main>
  <section>
    <div class="rows">
        
            <h1 class="main_heading">{{trans('presale/buy.buy_now')}}</h1>
            <div class="progress">
                <!--
                  <div class="progress-bar" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                  <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                  <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                -->
                @foreach ($presales as $pre_sale)
                    <?php
                    //~ $sold_coin = $pre_sale->total_coin_unit - $pre_sale->remaining_volume;
                    $sold_coin = $pre_sale->sold_coin;
                    //$sold_percent = round(($sold_coin/$pre_sale->total_coin_unit)*100);
                    $width_percent = round(100/sizeof($presales));
                    if($pre_sale->end_date < date('Y-m-d')){
                        $progress_class = "progress-bar-warning";
                    }else if($pre_sale->start_date<=date('Y-m-d') && $pre_sale->end_date>=date('Y-m-d')){
                        $progress_class = "progress-bar-success";
                    }else{
                        $progress_class = "";
                    }
                    ?>
                    <div
                            class="{{$progress_class}} progress-bar progress-bar-striped"
                            role="progressbar"
                            style="width: {{$width_percent}}%"
                            aria-valuenow="{{$width_percent}}"
                            aria-valuemin="0"
                            aria-valuemax="100">{{ $pre_sale->remaining_volume }}/{{ $pre_sale->total_coin_unit }}</div>
                @endforeach
            </div>
            <div class="box box-inbox">@include('flash::message')
            <!-- <form class="form-horizontal onSubmitdisableButton" action="{{ route('presale.buy.post') }}" method="post"> -->
            <form class="form-horizontal onSubmitdisableButton" action="{{ route('presale.buyCSM.post') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <!-- <label>{{trans('presale/buy.pay_currency')}} (USD):</label> -->
                            <!--<label> Buy Currency (ETH):</label>-->
                            <label> Buy Currency (ADA):</label>

                            <!-- <input type="text" class="form-control" name="amount" id="inp_amount" aria-describedby="helpId" placeholder="Enter Amount In ETH"> -->

                            <input id="inp_amount" type="text" class="form-control"
                               value="{{ request()->usd_amount }}"
                               name="eth_amount" autocomplete="off"
                               placeholder="0.00" type="text"
                               onpaste="return false" oncut="return false"
                               data-original-title="Enter Token Aomunt you want to purchase"
                               onkeypress="return isNumberKey(event)">
                            @if($errors->has('usd_amount'))
                                <span class="help-text text-danger">{{ $errors->first('usd_amount') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <div class="form-group">
                            <label>
                                Receive Currency (CSM)
                            </label>
                            <input type="text" class="form-control"
                               value="{{ request()->eth }}"
                               name="czm_amount" autocomplete="off"
                               placeholder="0.00000000" type="text"
                               onpaste="return false" oncut="return false"
                               data-original-title="Enter Token Aomunt you want to purchase"
                               onkeypress="return isNumberKey(event)" readonly="">
                        @if($errors->has('eth'))
                            <span class="help-text">{{ $errors->first('eth') }}</span>
                        @endif
                        </div>
                    </div>
                </div>

                <!-- <div class="form-group">
                    <label for="" class="control-label col-md-10" style="padding-top:0px;">
                        Note : 1 USD = 0.00047 ETH
                    </label>
                </div> -->

         {{--<div class="form-group">
          <label for="" class="control-label col-md-3" style="padding-top:0px;">{{trans('presale/buy.Bonus_Coins')}}:</label>
          <div class="col-md-9"><span id="bonus_amount">0.00000000</span></div>
                 </div>--}}
                 
                <!--  <div class="form-group">
          <label for="" class="control-label col-md-10" style="padding-top:0px;">{{trans('presale/buy.Total_Received_Coins')}}:</label>
          <div class="col-md-2 text-right"> <span id="total_coins">0.00</span></div>
                 </div>

                <div class="form-group">
                    <label for="" class="control-label col-md-10" style="padding-top:0px;">{{trans('presale/buy.amount')}}:</label>
                    <div class="col-md-2 text-right"><i class="fa fa-dollar"></i> <span id="total_amount">0.00</span></div>
                </div>

                <div class="form-group">
                    <label for="" class="control-label col-md-10" style="padding-top:0px;">{{trans('presale/buy.discount')}}:</label>
                    <div class="col-md-2 text-right"><i class="fa fa-dollar"></i> <span id="discount_amount">0.00</span></div>
                </div>

                 <div class="form-group">
          <label for="" class="control-label col-md-10" style="padding-top:0px;">{{trans('presale/buy.net_amount')}}:</label>
          <div class="col-md-2 text-right"><i class="fa fa-dollar"></i> <span id="usd_amount">0.00</span></div>
                 </div> -->

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="text-center">
                            <!-- <button class="btn btn-info submitButton">{{trans('presale/buy.Buy')}}</button> -->
                            <!-- <button class="btn btn-info submitButton">BUY CSM</button> -->
                             <!-- <button type="button" onClick="startProcess()" class="btn btn-info btn-block submitButton">{{trans('deposit/usd.Proceed')}}</button>  -->
   Status: <span id="status">Loading...</span>
                             <!-- <button class="btn btn-info btn-block submitButton" onclick="printCoolNumber();">Print Cool Number</button> -->
                              <button class="btn btn-info btn-block" onclick="changetransferToken();">Buy</button>
                        </div>
                    </div>
                </div>
            </form>
            </div>
           
          <!--  
           <div class="box box-inbox">
      <div class="panel">
          <div class="panel-body">
            {{-- @php $allPresale=App\Presale::orderBy('start_date','ASC')->paginate(10); @endphp
              <div class="table-responsive table-scrollable">
                        <table class="table">
                            <thead>
                                <tr>
                                   
                                    <th class="text-center">Special Rebate</th>
                                    <th class="text-center">Coin Price</th>
                                    <th class="text-center">Sold Amount</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
              @php                
                $activePresale=App\Presale::where('status',1)->whereRaw('presales.total_coin_unit > presales.sold_coin')->whereDate('end_date', '>=', date('Y-m-d'))->orderBy('start_date','ASC')->first();
                $divclass='';
              @endphp
              
                            @forelse($allPresale as $presale)
                            
                @if($activePresale)
                  @if($presale->id==$activePresale->id)
                    @php $divclass="background:#5cb85c!important;" @endphp
                  @else
                    @php $divclass=""; @endphp
                  @endif
                @endif
                                <tr style="{{$divclass}}">
                                    <td class="text-center">{{ $presale->total_coin_unit }} CSM</td>
                                    <td class="text-center">${{ $presale->discount_percent?:$presale->unit_price }}</td>
                                    <td class="text-center">
                    
                    @if($activePresale)
                      @if($presale->id!=$activePresale->id)
                        @if($presale->sold_coin==0)
                          opening date {{ $presale->start_date }}
                        @else
                          {{ $presale->sold_coin }} CSM
                        @endif
                      @else
                        {{ $presale->sold_coin }} CSM
                      @endif
                    @else
                    {{ $presale->sold_coin }} CSM
                    @endif
                  </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-danger">{{trans('transaction/index.no_transaction_exists')}}</td>
                                </tr>
                            @endforelse
                            </tbody>
                            
                        </table>
                        <div class="portlet-footer">
                <span>{{ __('Showing') }} {{ $allPresale->firstItem() }} {{ __('to') }} {{ $allPresale->lastItem() }} of {{ $allPresale->total() }} {{ __('records') }}</span>
                <span class="text-right">{{ $allPresale->links() }}</span>
              </div>--}}
      @php 
          $getPresale=App\Presale::active(); 
          $presale_id=''; 
          if($getPresale->exists()) {
            $presale=$getPresale->first();
            $presale_id = $presale->id;
          }
          $totalCoin=App\Presale::sum('total_coin_unit');
           $totalSold=App\Presale::sum('sold_coin');
      @endphp       
      <div class="table-responsive">
                <table class="table table-bordered" style="color:#fff;">
                   <tbody class="text-center">
                        <tr>
                            <td colspan="5">Pre -Sale ITO  </td>
                            <td>{{ round($totalSold,2) }} CSM / {{ $totalCoin }} CSM </td>
                         </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td colspan="2">Completed ITO Sales</td>
                        </tr>
                        <tr>
                            <td>Tier</td>
                            <td>ITO Sales</td>
                            <td>Special Rebate</td>
                            <td>Coin Price</td>
                            <td>USD</td>
                            <td>CSM</td>
                        </tr>
                        @php $presale_row1=App\Presale::where('total_coin_unit',1000000)->where('discount_percent',0.10)->first(); @endphp
                         @if($presale_row1)
                         @php 
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row1->id){
                  $activeCheck=1;
                }
              }
              $coin_price=$presale_row1->discount_percent?:$presale_row1->unit_price;
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                            <td>1</td>
                            <td>{{$presale_row1->total_coin_unit}}</td>
                            <td>-</td>
                            <td>${{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row1->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row1->sold_coin}}</td>
                            @else
                @if($presale_row1->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row1->start_date}}</td>
                @else
                  <td>${{ ($presale_row1->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row1->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                        
                        @php $presale_row2=App\Presale::where('total_coin_unit',55110)->where('discount_percent',0.10)->first(); @endphp
                         @if($presale_row2)
                         @php 
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row2->id){
                  $activeCheck=1;
                }
              }
              $coin_price=$presale_row2->discount_percent?:$presale_row2->unit_price;
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">

                            <td rowspan="4">2</td>
                            <td rowspan="4">1000000</td>
                            <td>{{$presale_row2->total_coin_unit}}</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row2->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row2->sold_coin}}</td>
                            @else
                @if($presale_row2->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row2->start_date}}</td>
                @else
                  <td>${{ ($presale_row2->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row2->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                        @php $presale_row3=App\Presale::where('total_coin_unit',110000)->where('discount_percent',0.12)->first(); @endphp
                         @if($presale_row3)
                        @php 
              $coin_price=$presale_row3->discount_percent?:$presale_row3->unit_price;
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row3->id){
                  $activeCheck=1;
                }
              }
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                            <td>{{$presale_row3->total_coin_unit}}</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row3->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row3->sold_coin}}</td>
                            @else
                @if($presale_row3->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row3->start_date}}</td>
                @else
                  <td>${{ ($presale_row3->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row3->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                        @php $presale_row4=App\Presale::where('total_coin_unit',275000)->where('discount_percent',0.14)->first(); @endphp
                         @if($presale_row4)
                         
                         @php 
              $coin_price=$presale_row4->discount_percent?:$presale_row4->unit_price;
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row4->id){
                  $activeCheck=1;
                }
              }
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                           <td>{{$presale_row4->total_coin_unit}}</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row4->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row4->sold_coin}}</td>
                            @else
                @if($presale_row4->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row4->start_date}}</td>
                @else
                  <td>${{ ($presale_row4->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row4->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                         @endif
                         @php $presale_row5=App\Presale::where('total_coin_unit',559890)->where('discount_percent',0.15)->first(); @endphp
                         @if($presale_row5)
                        @php 
              $coin_price=$presale_row5->discount_percent?:$presale_row5->unit_price;
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row5->id){
                  $activeCheck=1;
                }
              }
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                           <td>{{$presale_row5->total_coin_unit}}</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row5->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row5->sold_coin}}</td>
                            @else
                @if($presale_row5->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row5->start_date}}</td>
                @else
                  <td>${{ ($presale_row5->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row5->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                        @php $presale_row6=App\Presale::where('total_coin_unit',1000000)->where('discount_percent',0.20)->first(); @endphp
                         @if($presale_row6)
              @php 
              $coin_price=$presale_row6->discount_percent?:$presale_row6->unit_price;
              if($activeCheck!=1){
                if($presale_id){
                  if($presale_id==$presale_row6->id){
                    $activeCheck=1;
                  }
                }
              }
              @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                            <td>3</td>
                            <td>{{$presale_row6->total_coin_unit}}</td>
                            <td>-</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row6->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row6->sold_coin}}</td>
                            @else
                @if($presale_row6->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row6->start_date}}</td>
                @else
                  <td>${{ ($presale_row6->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row6->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                        @php $presale_row7=App\Presale::where('total_coin_unit',1000000)->where('discount_percent',0.25)->first(); @endphp
                         @if($presale_row7)
                        @php 
                        $coin_price=$presale_row7->discount_percent?:$presale_row7->unit_price;
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row7->id){
                  $activeCheck=1;
                }
              }
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                            <td>4</td>
                            <td>{{$presale_row7->total_coin_unit}}</td>
                            <td>-</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row7->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row7->sold_coin}}</td>
                            @else
                @if($presale_row7->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row7->start_date}}</td>
                @else
                  <td>${{ ($presale_row7->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row7->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
                        @endif
                         @php $presale_row8=App\Presale::where('total_coin_unit',1000000)->where('discount_percent',0.35)->first(); @endphp
                         @if($presale_row8)
                        @php 
                        $coin_price=$presale_row8->discount_percent?:$presale_row8->unit_price;
              $activeCheck=0;
              if($presale_id){
                if($presale_id==$presale_row8->id){
                  $activeCheck=1;
                }
              }
                         @endphp
                         <tr class="{{ ($activeCheck==1)?'bg-warning':'' }}">
                            <td>5</td>
                            <td>{{$presale_row8->total_coin_unit}}</td>
                            <td>-</td>
                            <td>$ {{$coin_price}}</td>
                            @if($activeCheck==1)
                <td>${{ ($presale_row8->sold_coin)*($coin_price) }}</td>
                <td>{{$presale_row8->sold_coin}}</td>
                            @else
                @if($presale_row8->sold_coin==0)
                   <td colspan="2">Start Date : {{$presale_row8->start_date}}</td>
                @else
                  <td>${{ ($presale_row8->sold_coin)*($coin_price) }}</td>
                  <td>{{$presale_row8->sold_coin}}</td>
                @endif
                           
                            @endif
                        </tr>
            @endif
                    </tbody>
                </table>
            </div>  
                    </div>
                    
            
          </div>
        </div>
            </div>
           
    </div> -->
  </section>
</main> 








@endsection
@push('js')
<!-- <script>
  $(document).on('submit', '.onSubmitdisableButton', function(e) {  
      if (confirm("Are You Sure ?") == true) {
      $('.submitButton').attr('disabled',true);
      return true;
      } else {
      return false;
      }
  });
    $(document).ready(function(){
        // var remainingCoins, discountPercent, coinPrice, basePrice;
        // $.ajax({
        //     type: "GET",
        //     url: baseURL+'/presale/presale-data',
        //     dataType:"json",
        //     success: function( result ) {
        //         if(result.status) {
        //             remainingCoins = result.data.remainingCoins;
        //             discountPercent = result.data.discountPercent;
        //             coinPrice = result.data.coinPrice;
        //             basePrice = result.data.basePrice
        //         } else {
        //             console.log(result.message);
        //         }
        //     }
        // });

   //      $('input[name=jpc_coin]').keyup(function(){

   //          var amount = $(this).val();

   //          var elementUSD = $('input[name=usd_amount]');

   //          //var elementBonus = $('span[id=bonus_amount]');
   //          var elementTotalJPC = $('span[id=total_coins]');
   //          var elementTotalUSD = $('span[id=usd_amount]');
   //          var elementUSDTotal = $('span[id=total_amount]');
   //          var elementUSDDiscount = $('span[id=discount_amount]');

      //  var amountUSD = amount * coinPrice;
      // if(discountPercent > 0){
      //  var amountUSD = amount * discountPercent;
      // }
           
   //          //var bonusCoin = amount * (bonusPercent/100);
   //          var totalJPC = (parseFloat(amount) + parseFloat(bonusCoin));
   //          var usdTotal = amount * basePrice;
   //          var usdDiscount = usdTotal - amountUSD;
          

   //          if(isNaN(amountUSD)) {
   //              amountUSD = 0;
   //          }

   //          /*if(isNaN(bonusCoin)) {
   //              bonusCoin = 0;
   //          }*/

   //          if(isNaN(totalJPC)) {
   //              totalJPC = 0;
   //          }


   //          elementUSD.val(amountUSD.toFixed(2));

   //          //elementBonus.text(bonusCoin.toFixed(8));
   //          elementTotalJPC.text(totalJPC.toFixed(8));
   //          elementTotalUSD.text(amountUSD.toFixed(2));
   //          elementUSDTotal.text(usdTotal.toFixed(2));
   //          elementUSDDiscount.text(usdDiscount.toFixed(2));

   //      });

   //      $('input[name=usd_amount]').keyup(function(){

   //          var amount = $(this).val();

   //          var elementJPC = $('input[name=jpc_coin]');

   //          //var elementBonus = $('span[id=bonus_amount]');
   //          var elementTotalJPC = $('span[id=total_coins]');
   //          var elementTotalUSD = $('span[id=usd_amount]');
   //          var elementUSDTotal = $('span[id=total_amount]');
   //          var elementUSDDiscount = $('span[id=discount_amount]');
  
      // var amountJPC = amount / coinPrice;
      // if(discountPercent > 0){
      //  var amountJPC = amount / discountPercent;
      // }
           
   //          //var bonusCoin = amountJPC * (bonusPercent/100);
   //          var totalJPC = (parseFloat(amountJPC) /*+ parseFloat(bonusCoin)*/);

   //          var usdTotal = amountJPC * basePrice;
   //          var usdDiscount =  (amountJPC * basePrice) - ((amount / coinPrice) * basePrice);
   //          //var usdDiscount = usdTotal - amount;

   //          if(isNaN(amountJPC)) {
   //              amountJPC = 0;
   //          }

   //          /*if(isNaN(bonusCoin)) {
   //              bonusCoin = 0;
   //          }*/

   //          if(isNaN(totalJPC)) {
   //              totalJPC = 0;
   //          }

   //          elementJPC.val(amountJPC.toFixed(8));

   //          elementJPC.val(amountJPC.toFixed(8));
   //          //elementBonus.text(bonusCoin.toFixed(8));
   //          elementTotalJPC.text(totalJPC.toFixed(8));
   //          elementTotalUSD.text(parseFloat(amount).toFixed(2));
   //          elementUSDTotal.text(usdTotal.toFixed(2));
   //          elementUSDDiscount.text(usdDiscount.toFixed(2));

   //      });

         $('input[name=eth_amount]').keyup(function(){

            var amount = $(this).val();

            var elementEth = $('input[name=czm_amount]');

            //var elementBonus = $('span[id=bonus_amount]');
            // var elementTotalJPC = $('span[id=total_coins]');
            // var elementTotalUSD = $('span[id=usd_amount]');
            // var elementUSDTotal = $('span[id=total_amount]');
            // var elementUSDDiscount = $('span[id=discount_amount]');
            var usdEth = 10000;
           
            //var bonusCoin = amountJPC * (bonusPercent/100);
            // var totalJPC = (parseFloat(amountJPC) /*+ parseFloat(bonusCoin)*/);

            var amountEth = usdEth * amount;


            if(isNaN(amountEth)) {
                amountEth = 0;
            }

            // if(isNaN(totalJPC)) {
            //     totalJPC = 0;
            // }

            elementEth.val(amountEth.toFixed(6));
            
            elementEth.val(amountEth.toFixed(6));

        });

    });

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }

    function startProcess() {
            if ($('#inp_amount').val() > 0) {
                // run metamsk functions here
                EThAppDeploy.loadEtherium();
            } else {
                alert('Please Enter Valid Amount');
            }
        }

        EThAppDeploy = {
            loadEtherium: async () => {
                if (typeof window.ethereum !== 'undefined') {
                    EThAppDeploy.web3Provider = ethereum;
                    EThAppDeploy.requestAccount(ethereum);
                } else {
                    alert(
                        "Not able to locate an Ethereum connection, please install a Metamask wallet"
                    );
                }
            },
            /****
             * Request A Account
             * **/
            requestAccount: async (ethereum) => {
                ethereum
                    .request({
                        method: 'eth_requestAccounts'
                        // method: 'Transfer'
                    })
                    .then((resp) => {
                        //do payments with activated account
                        EThAppDeploy.payNow(ethereum, resp[0]);
                        // EThAppDeploy.Transfer(ethereum, resp[0]);
                    })
                    .catch((err) => {
                        // Some unexpected error.
                        console.log(err);
                    });
            },
            /***
             *
             * Do Payment
             * */
            payNow: async (ethereum, to) => {
                var amount = $('#inp_amount').val();
                // ethereum
                //     .request({
                //         method: 'eth_sendTransaction',
                //         // method: 'Transfer',
                //         params: [{
                //             from: "0x89b4c5319339690b11cA551B12066E46e6d1Dc83",
                //             // from: "0x76b6dea613f1e56ccb45294ac5787c77e91c335b",
                //             // to: to,
                //             to: "0x76b6dea613f1e56ccb45294ac5787c77e91c335b",
                //             // to: "0xb1f0aec7458886443B7524Bc43cCdB1b46cC13F1",
                //             // value: '0x' + ((amount * 1000000000000000000).toString(16)),
                //             value: '0x' + ((amount * 10000000).toString(6)),
                                    

                //         }, ],
                //     })
                //     .then((txHash) => {
                //         if (txHash) {
                //             console.log(txHash);
                //             storeTransaction(txHash, amount);
                //         } else {
                //             console.log("Something went wrong. Please try again");
                //         }
                //     })
                //     .catch((error) => {
                //         console.log(error);
                //     });
            },
        }
     function storeTransaction(txHash, amount) {
      console.log(txHash)
      console.log(amount)
      // console.log(value)
        }

</script> -->
 <script src="https://cdn.jsdelivr.net/gh/ethereum/web3.js@1.0.0-beta.34/dist/web3.min.js"></script>
 <script>
  function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode != 46 && charCode > 31
                && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
        // function startProcess() {
        //     if ($('#inp_amount').val() > 0) {
        //         // run metamsk functions here
        //         EThAppDeploy.loadEtherium();
        //     } else {
        //         alert('Please Enter Valid Amount');
        //     }
        // }
        // EThAppDeploy = {
        //     loadEtherium: async () => {
        //         if (typeof window.ethereum !== 'undefined') {
        //             EThAppDeploy.web3Provider = ethereum;
        //             EThAppDeploy.requestAccount(ethereum);
        //         } else {
        //             alert(
        //                 "Not able to locate an Ethereum connection, please install a Metamask wallet"
        //             );
        //         }
        //     },
        //     /****
        //      * Request A Account
        //      * **/
        //     requestAccount: async (ethereum) => {
        //         ethereum
        //             .request({
        //                 method: 'eth_requestAccounts'
        //             })
        //             .then((resp) => {
        //                 //do payments with activated account
        //                 EThAppDeploy.payNow(ethereum, resp[0]);
        //             })
        //             .catch((err) => {
        //                 // Some unexpected error.
        //                 console.log(err);
        //             });
        //     },
        //     /***
        //      *
        //      * Do Payment
        //      * */
        //     payNow: async (ethereum, from) => {
        //         var amount = $('#inp_amount').val();
        //         ethereum
        //             .request({
        //                 method: 'eth_sendTransaction',
        //                 params: [{
        //                     from: from,
        //                     to: "0x6cA0323B6DB3bbC5331614b1E5aFf01C0b1771d6",
        //                     value: amount,
        //                 }, ],
        //             })
        //             .then((txHash) => {
        //                 if (txHash) {
        //                     console.log(txHash);
        //                     storeTransaction(txHash, amount);
        //                 } else {
        //                     console.log("Something went wrong. Please try again");
        //                 }
        //             })
        //             .catch((error) => {
        //                 console.log(error);
        //             });
        //     },
        // }
        // /***
        //  *
        //  * @param Transaction id
        //  *
        //  */
        // function storeTransaction(txHash, amount) {
        //   console.log(txHash);
        //   console.log(amount);
        // }
    </script>

 

    <script type="text/javascript">
        async function loadWeb3() {

            if (window.ethereum) {
                window.web3 = new Web3(window.ethereum);
                window.ethereum.enable();
            }
        }

        async function loadContract() {
            // let web3js;
            return await new window.web3.eth.Contract([
  {
    "inputs": [],
    "stateMutability": "nonpayable",
    "type": "constructor"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "address",
        "name": "tokenOwner",
        "type": "address"
      },
      {
        "indexed": true,
        "internalType": "address",
        "name": "spender",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "tokens",
        "type": "uint256"
      }
    ],
    "name": "Approval",
    "type": "event"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "delegate",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "numTokens",
        "type": "uint256"
      }
    ],
    "name": "approve",
    "outputs": [
      {
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }
    ],
    "name": "Bought",
    "type": "event"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      }
    ],
    "name": "Sold",
    "type": "event"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "receiver",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "numTokens",
        "type": "uint256"
      }
    ],
    "name": "transfer",
    "outputs": [
      {
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": true,
        "internalType": "address",
        "name": "from",
        "type": "address"
      },
      {
        "indexed": true,
        "internalType": "address",
        "name": "to",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "tokens",
        "type": "uint256"
      }
    ],
    "name": "Transfer",
    "type": "event"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "owner",
        "type": "address"
      },
      {
        "internalType": "address",
        "name": "buyer",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "numTokens",
        "type": "uint256"
      }
    ],
    "name": "transferFrom",
    "outputs": [
      {
        "internalType": "bool",
        "name": "",
        "type": "bool"
      }
    ],
    "stateMutability": "nonpayable",
    "type": "function"
  },
  {
    "anonymous": false,
    "inputs": [
      {
        "indexed": false,
        "internalType": "address",
        "name": "_from",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "address",
        "name": "_destAddr",
        "type": "address"
      },
      {
        "indexed": false,
        "internalType": "uint256",
        "name": "_amount",
        "type": "uint256"
      }
    ],
    "name": "TransferSent",
    "type": "event"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "owner",
        "type": "address"
      },
      {
        "internalType": "uint256",
        "name": "amount",
        "type": "uint256"
      },
      {
        "internalType": "uint256",
        "name": "numTokens",
        "type": "uint256"
      }
    ],
    "name": "transferToken",
    "outputs": [
      {
        "internalType": "string",
        "name": "",
        "type": "string"
      },
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "payable",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "owner",
        "type": "address"
      },
      {
        "internalType": "address",
        "name": "delegate",
        "type": "address"
      }
    ],
    "name": "allowance",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [
      {
        "internalType": "address",
        "name": "tokenOwner",
        "type": "address"
      }
    ],
    "name": "balanceOf",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "decimals",
    "outputs": [
      {
        "internalType": "uint8",
        "name": "",
        "type": "uint8"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "name",
    "outputs": [
      {
        "internalType": "string",
        "name": "",
        "type": "string"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "symbol",
    "outputs": [
      {
        "internalType": "string",
        "name": "",
        "type": "string"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "token",
    "outputs": [
      {
        "internalType": "contract BEP20",
        "name": "",
        "type": "address"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  },
  {
    "inputs": [],
    "name": "totalSupply",
    "outputs": [
      {
        "internalType": "uint256",
        "name": "",
        "type": "uint256"
      }
    ],
    "stateMutability": "view",
    "type": "function"
  }
], '0xd4c3c31789c05712310e345efb90fb401b71cf0c');
        }

        

        async function getCurrentAccount() {
            const accounts = await window.web3.eth.getAccounts();
            return accounts[0];r
        }


        async function changetransferToken() {

          var amount = $('#inp_amount').val();
          console.log(amount);

           if (amount < 0 || amount == '') {
                alert('Please Enter Valid Amount');
                return;
            }
            updateStatus('transferToken');
            const account = await getCurrentAccount();
            
            const senderAddress = "0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223";
            const receiverAddress = account;


            console.log(senderAddress);
            console.log(receiverAddress);

          //   await window.contract.methods.balanceOf(receiverAddress).call(function (err, res) {
          //   if (err) {
          //     console.log("An error occured", err)
          //     return
          //   }
          //   console.log("The balance is: ", res)
          // });

          

            await window.contract.methods
            .transferToken("0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223", "12000","120")
            .send({ from: receiverAddress, value: "12000",gas: 4000000 }, function (err, res) {
              if (err) {
                console.log("An error occured", err)
                return
              }
              console.log("Hash of the transaction: " + res)
            });

          // await window.contract.methods.transferToken(owner,amount,numTokens).send({ from: account }, async function (error, transactonHash) {
          //     console.log(transactonHash);
          // })
            // console.log(await window.contract.methods.balanceOf(address).toNumber())
            // const coolNumber = await window.contract.methods.coolNumber().call();
        }


        async function load() {
            await loadWeb3();
            window.contract = await loadContract();
            updateStatus('Ready!');
        }

        function updateStatus(status) {
            const statusEl = document.getElementById('status');
            statusEl.innerHTML = status;
            console.log(status);
        }

        load();
    </script>
@endpush
