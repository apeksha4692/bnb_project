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
            <!-- <form class="form-horizontal onSubmitdisableButton" action="{{ route('presale.buyCSM.post') }}" method="post"> -->
                <!-- {{ csrf_field() }} -->
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
                               onkeypress="return isNumberKey(event)" required>
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
                               onkeypress="return isNumberKey(event)" readonly="" id="czm_amount">
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
            <!-- </form> -->
            </div>
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
            updateStatus('transferToken');

          var bnb_amount = $('#inp_amount').val();
          console.log(bnb_amount);


          if (bnb_amount < 0 || bnb_amount == '') {
                alert('Please Enter Valid Amount');
                return;
          }

          $('#czm_amount').val('120');
             


            const account = await getCurrentAccount();
            // // console.log(loadContract);
            // console.log(account);
            // const owner = "0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223";
            // const amount = "10";
            // const numTokens = "5";

            // const transferToken = await window.contract.methods.transferToken(owner,amount,numTokens).call({from: account,gas: 3000000});

            // console.log(transferToken);
            
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

// eth.sendTransaction({from:eth.accounts[0], to:'0x[ADDRESS_HERE]', value: web3.toWei(5, "ether"), gas:100000});
          

          await window.contract.methods
            .transferToken("0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223", bnb_amount,"120")
            .send({ from: receiverAddress, value: bnb_amount,gas: 4000000 }, function (err, res) {
              if (err) {
                console.log("An error occured", err)
                return
              }
              console.log('done');
              console.log("Hash of the transaction: " + res)
          });


          // await window.contract.methods
          //   .transferToken("0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223","1200","120")
          //   .send({ from: receiverAddress, value:"1200",gas: 4000000 }, function (err, res) {
          //     if (err) {
          //       console.log("An error occured", err)
          //       return
          //     }
          //     console.log('done');
          //     console.log("Hash of the transaction: " + res)
          // });

          await window.contract.methods.balanceOf(receiverAddress).call(function (err, received_res) {
            if (err) {
              console.log("An error occured", err)
              return
            }
            console.log("The balance is: ", received_res)
          });


          await window.contract.methods.balanceOf(senderAddress).call(function (err, res) {
            if (err) {
              console.log("An error occured", err)
              return
            }
            console.log("The balance is: ", res)
          });

            //  await window.contract.methods
            // .transferToken("0x79319A973Be6C6F0cbad2206ea4F6573A9ecF223", "1200","120")
            // .send({ from: receiverAddress, value:"1200",gas: 4000000 }, function (err, res) {
            //   if (err) {
            //     console.log("An error occured", err)
            //     return
            //   }
            //   console.log('done');
            //   console.log("Hash of the transaction: " + res)
            // });

          // await window.contract.methods.transferToken(owner,amount,numTokens).send({ from: account }, async function (error, transactonHash) {
          //     console.log(transactonHash);
          // })
            // console.log(await window.contract.methods.balanceOf(address).toNumber())
            // const coolNumber = await window.contract.methods.coolNumber().call();
        }

    //        function setCoolNumber(uint _coolNumber) public {
    //     coolNumber = _coolNumber;
    // }

        // async function changeCoolNumber() {
        //     const value = Math.floor(Math.random() * 100);
        //     updateStatus(`Updating coolNumber with ${value}`);
        //     const account = await getCurrentAccount();
        //     const coolNumber = await window.contract.methods.setCoolNumber(value).send({ from: account });
        //     updateStatus('Updated.');
        // }

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
