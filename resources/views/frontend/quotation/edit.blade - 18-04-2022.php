@extends('frontend.layout.app')
@section('content')

<!-- mid section start-->
<section class="midContent" id="midContent">
    <div class="container">
        <div class="d-flex flex-center">
            <div class="addQuotationMidSection">
                <h2>Quotation</h2>
                <div class="quotationTopSection">
                    <div class="tableResponsive">
                        <table>
                            <thead>
                                <tr>
                                    <th align="left">Quotation No</th>
                                    <th align="right">{{$quotation->quotation_number}}</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
                <?php $totalPrice = 0.00; ?>
                <div class="quotationBottomSection">
                    <div class="tableResponsive">
                        <table>
                            <thead>
                                <tr>

                                    <th width="15%">Item Description</th>
                                    <th width="10%">Article Number</th>
                                    <th width="10%">Component</th>

                                    <th width="5%">Unit Price</th>
                                    <th width="15%">Qty</th>
                                    <th width="10%">Total Price</th>
                                    <th width="05%">Selection</th>

                                </tr>


                            </thead>
                            <tbody>
                                @if($controlPanelCartData->isNotEmpty())
                                @foreach($controlPanelCartData as $key=> $val)
                                <tr>
                                    <td>
                                        <a class="detail-modal" href="javascript:void(0)">
                                            {{$val->applications['value'] }} {{$val->noofpumps['value'] }} x {{ $val->powers['value'] }} {{$val->starter_code}}
                                        </a>
                                    </td>
                                    <td><a class="detail-modal" href="javascript:void(0)">
                                            {{$val['article_number']}}
                                        </a>
                                    </td>
                                    <td>Control Panel </td>

                                    <td>{{ App\Helpers\CurrencyHelper::withCurrency($val['price'])}}</td>
                                    <td>
                                        <div class="qty_input">
                                            <div class="qty">
                                                <span class="minus qtyBtn">-</span>
                                                <input type="number" class="icount quantity"  id="quantity" name="quantity" value="{{$val->qty}}"  min="1" max="" />
                                                <span class="plus qtyBtn">+</span>
                                            </div>
                                        </div>
                                    </td>
                            <input type="hidden" class="cp-id" value="{{$val['id']}}">
                            <input type="hidden"  id="cp-{{$val['id']}}" >
                            <input type="hidden" class="total-price-input" name="" value="{{$val->total_price}}"  min="1" max="" />
                            <td class="total-price">{{ App\Helpers\CurrencyHelper::withCurrency($val->price*$val->qty) }}</td>
                            <td>
                                <a href="{{ URL::to('controlpanel/cart-item/'.$val['id'] )}}" target="_blank"><img src="{{asset('fassets/images/viewIcon.png')}}" />
                                </a>
                                <!--<button><img src="{{asset('fassets/images/downloadIcon.png')}}" /></button>-->
                            </td>

                            </tr>
                            <?php $totalPrice += round($val->price * $val->qty); ?>
                            @endforeach
                            @endif
                            @if($atmosCartData->isNotEmpty())
                            @foreach($atmosCartData as $key=> $val)


                            <tr>
                                <td style="display: none;"><input type="checkbox" checked name="atmos_checked_id" value="{{$val['id']}}"></td>
                                <td>

                                    <a class="detail-modal" href="javascript:void(0)">
                                        {{$val->pump_name }} x {{ $val->brand }} x {{$val->power}}
                                    </a>
                                </td>
                                <td>
                                    <a class="detail-modal" href="javascript:void(0)">
                                        {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                    </a>
                                </td>
                                <td>Atmos Giga</td>
                                <td>{{ App\Helpers\CurrencyHelper::withCurrency($val['price'])}}</td>
                                <td>
                                    <div class="qty_input">
                                        <div class="qty">
                                            <span class="atmos-minus qtyBtn">-</span>
                                            <input type="number" class="icount quantity"  id="quantity" name="quantity" value="{{$val->qty}}"  min="1" max="" />
                                            <span class="atmos-plus qtyBtn">+</span>
                                        </div>
                                    </div>
                                </td>

                            <input type="hidden" class="atmos-cart-id" value="{{$val['id']}}">
                            <input type="hidden"  id="at-{{$val['id']}}" >
                            <input type="hidden" class="total-price-input" name="" value="{{$val->total_price}}"  min="1" max="" />
                            <td class="total-price">{{ App\Helpers\CurrencyHelper::withCurrency($val->price*$val->qty) }}</td>
                            <td>
                                @if($val['is_accesories_manual'])
                                <a href="javascript:void(0)">
                                    @else
                                    <a href="{{ URL::to('atmos/cart-item/'.$val['id'] )}}" target="_blank">
                                        @endif
                                        <img src="{{asset('fassets/images/viewIcon.png')}}" />
                                    </a>
                                    <!--<button><img src="{{asset('fassets/images/downloadIcon.png')}}" /></button>-->
                            </td>
                            </tr>
                            <?php $totalPrice += round($val->price * $val->qty); ?>
                            @endforeach
                            @endif
                            @if($scpCartData->isNotEmpty())
                            @foreach($scpCartData as $key=> $val)


                            <tr>
                                <td style="display: none;"><input type="checkbox" checked name="scp_checked_id" value="{{$val['id']}}"></td>
                                <td>

                                    <a class="detail-modal" href="javascript:void(0)">
                                        {{$val->pump_name }} x {{ $val->brand }} x {{$val->power}}
                                    </a>
                                </td>
                                <td>
                                    <a class="detail-modal" href="javascript:void(0)">
                                        {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                    </a>
                                </td>
                                <td>Scp</td>
                                <td>{{ App\Helpers\CurrencyHelper::withCurrency($val['price'])}}</td>
                                <td>
                                    <div class="qty_input">
                                        <div class="qty">
                                            <span class="scp-minus qtyBtn">-</span>
                                            <input type="number" class="icount quantity"  id="quantity" name="quantity" value="{{$val->qty}}"  min="1" max="" />
                                            <span class="scp-plus qtyBtn">+</span>
                                        </div>
                                    </div>
                                </td>

                            <input type="hidden" class="scp-cart-id" value="{{$val['id']}}">
                            <input type="hidden" class="total-price-input" name="" value="{{$val->total_price}}"  min="1" max="" />
                            <input type="hidden"  id="scp-{{$val['id']}}" >
                            <td class="total-price">{{ App\Helpers\CurrencyHelper::withCurrency($val->price*$val->qty) }}</td>
                            <td>
                                @if($val['is_accesories_manual'])
                                <a href="javascript:void(0)">
                                    @else
                                    <a href="{{ URL::to('scp/cart-item/'.$val['id'] )}}" target="_blank">

                                        @endif
                                        <img src="{{asset('fassets/images/viewIcon.png')}}" />
                                    </a>
                                    <!--<button><img src="{{asset('fassets/images/downloadIcon.png')}}" /></button>-->
                            </td>
                            <!--<td><button class="delete-scp-cart"><img src="{{asset('fassets/images/delIcon.png')}}" /></button></td>-->
                            </tr>
                            <?php $totalPrice += round($val->price * $val->qty); ?>
                            @endforeach
                            @endif
                            {{--                                booster cart starts--}}
                            @if($boosterCartData->isNotEmpty())
                            @foreach($boosterCartData as $key=> $val)


                            <tr>
                                <td style="display: none;"><input type="checkbox" checked name="booster_checked_id" value="{{$val['id']}}"></td>
                                <td>

                                    <a class="detail-modal-booster" href="javascript:void(0)">
                                        @php
                                            $const =null;
                                            // dd(str_starts_with($val->boosterCpData[0]->table_name, 'standard_'));
                                            if(str_starts_with($val->boosterCpData[0]->table_name, 'basic_')  == true)
                                                $const = "COE";
                                            else{
                                                 $const = 'CO';
                                                $array_check = array(3,4,7);
                                                if(in_array($val->boosterCpData[0]->stater_type_id,$array_check) ){
                                                    $const = 'COR';
                                                }
                                            }
                                        @endphp
                                        {{$const}} {{$val->boosterCpData[0]->noofpumps['value'] }} {{$val->model_no }}/{{$val->boosterCpData[0]->starter_code}}/AE
                                    </a>
                                </td>
                                <td>
                                    <a class="detail-modal" href="javascript:void(0)">
                                        {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                    </a>
                                </td>
                                <td>
                                    Booster
                                </td>

                                <td>{{ App\Helpers\CurrencyHelper::withCurrency($val['price'])}}</td>
                                <td>
                                    <div class="qty_input">
                                        <div class="qty">
                                            <span class="booster-minus qtyBtn">-</span>
                                            <input type="number" class="icount quantity"  id="quantity" name="quantity" value="{{$val->qty}}"  min="1" max="" />
                                            <span class="booster-plus qtyBtn">+</span>
                                        </div>
                                    </div>
                                </td>

                            <input type="hidden" class="booster-cart-id" value="{{$val['id']}}">
                            <input type="hidden" class="total-price-input" name="" value="{{$val->total_price}}"  min="1" max="" />
                            <input type="hidden"  id="booster-{{$val['id']}}" >
                            <td class="total-price">{{ App\Helpers\CurrencyHelper::withCurrency($val->price*$val->qty) }}</td>
                            <td>
                                <a href="{{ URL::to('booster-set/cart-item/'.$val['id'] )}}" target="_blank">
                                    <img src="{{asset('fassets/images/viewIcon.png')}}" />
                                </a>
                                <!--<button><img src="{{asset('fassets/images/downloadIcon.png')}}" /></button>-->
                            </td>

                            </tr>
                            <?php $totalPrice += round($val->price * $val->qty); ?>
                            @endforeach
                            @endif
                            {{--                                booster cart ends--}}
                            </tbody>
                        </table>
                        <div class="text-right txt-ttl" id=""><h4>Total Price: </h4><span class="" id="total-price-updated"> {{App\Helpers\CurrencyHelper::withCurrency($totalPrice) }}</span></div>

                    </div>
                </div>

            </div>
        </div>
        <div class="d-flex cusPagination">
            <!--            <div class="">
            <?php $cpId = Request::get('cp_id'); ?>
                           <a href="{{URL::to('controlpanel/customer-information/' . $customer->id ) }}"><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
                        </div>-->
            <div class="">
                <a  onclick="window.history.back()" href=""><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
            </div>
            <!--            <div class="">
                            <button>Next <img src="{{asset('fassets/images/arrowLefticon.png')}}" /></button>
                        </div>-->
        </div>
        <div class="d-flex formPageFooter">
            <div class="left">

            </div>
            <div class="right">
                <ul>

                    <li><a href="{{URL::to('/')}}" tooltip="Go to Home Page"><img src="{{asset('fassets/images/homeIcon.png')}}" /></a></li>
                    <li><a href="{{URL::to('controlpanel/cart/'.Auth::user()->id)}}" tooltip="Cart"><img src="{{asset('fassets/images/addIcon.png')}}" /></a></li>
                    <!--<li><a href="#" tooltip="Checkout"><img src="{{asset('fassets/images/goIcon.png')}}" /></a></li>-->
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- mid section end -->

<div id="detail-control-panel-modal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <div class="modal-body" id="detail-control-panel-modal-body">
            <!--Table-->
        </div>
        <div class="modalBtns">
            <span class="close-detail-control-panel-modal" >Close</span>
        </div>
    </div>

</div>
<input id="quotation-number" type="hidden" value="{{$quotation->quotation_number}}"/>
@endsection

@section('script')


<script>
    $(".plus").on("click", function () {
        var cp_id = $(this).closest('tr').find('.cp-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) + 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
//        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
//        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('controlpanel/ajax-qty-update')}}",
                data: {qty: qty, cp_id: cp_id},
                success: function (response) {
                    $("#cp-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();

                },
                error: function () {

                }

            });
        }
    });

    $(".minus").on("click", function () {
        var cp_id = $(this).closest('tr').find('.cp-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) - 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
//        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
//        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('controlpanel/ajax-qty-update')}}",
                data: {qty: qty, cp_id: cp_id},
                success: function (response) {

                    $("#cp-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();
                },
                error: function () {

                }

            });
        }

    });

    $(".atmos-plus").on("click", function () {
        var atmos_cart_id = $(this).closest('tr').find('.atmos-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) + 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
        if (totalPrice > 0) {
            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('atmos/ajax-qty-update')}}",
                data: {qty: qty, atmos_cart_id: atmos_cart_id},
                success: function (response) {
                    $("#at-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)

                    UpdatedTotalPriceQuotation();


                },
                error: function () {

                }

            });
        }
    });

    $(".atmos-minus").on("click", function () {
        var atmos_cart_id = $(this).closest('tr').find('.atmos-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) - 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('atmos/ajax-qty-update')}}",
                data: {qty: qty, atmos_cart_id: atmos_cart_id},
                success: function (response) {
                    $("#at-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();
                },
                error: function () {

                }

            });
        }

    });
//Scp
    $(".scp-plus").on("click", function () {
        var scp_cart_id = $(this).closest('tr').find('.scp-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) + 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('scp/ajax-qty-update')}}",
                data: {qty: qty, scp_cart_id: scp_cart_id},
                success: function (response) {
                    $("#scp-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();


                },
                error: function () {

                }

            });
        }
    });

    $(".scp-minus").on("click", function () {
        var scp_cart_id = $(this).closest('tr').find('.scp-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) - 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('scp/ajax-qty-update')}}",
                data: {qty: qty, scp_cart_id: scp_cart_id},
                success: function (response) {
                    $("#scp-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();

                },
                error: function () {

                }

            });
        }

    });


    $(".detail-modal").on("click", function () {
        var cp_id = $(this).closest('tr').find('.cp-id').val();
        $.ajax({
            type: "get",
            url: "{{url('controlpanel/ajax-detail-modal-cp')}}",
            data: {cp_id: cp_id},
            success: function (response) {
                if (response.data.html) {

                    $("#detail-control-panel-modal-body").html('');
                    $("#detail-control-panel-modal-body").html(response.data.html);
                    $("#detail-control-panel-modal").show();
                }
            },
            error: function () {

            }

        });


    });


    //BOOSTER
    $(".booster-plus").on("click", function () {
        var booster_cart_id = $(this).closest('tr').find('.booster-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) + 1;
        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
//        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
//        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('booster-set/ajax-qty-update')}}",
                data: {qty: qty, booster_cart_id: booster_cart_id},
                success: function (response) {
                    $("#booster-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();
                },
                error: function () {

                }

            });
        }
    });

    $(".booster-minus").on("click", function () {
        var booster_cart_id = $(this).closest('tr').find('.booster-cart-id').val();
        var qty = parseInt($(this).closest('tr').find('.quantity').val()) - 1;
//        var totalPrice = parseFloat($(this).closest('tr').find('.total-price-input').val());
//        if (totalPrice > 0) {
//            var tpPriceHtml = totalPrice * qty;
//            $(this).closest('tr').find('.total-price').html(
//                    withCurrency(tpPriceHtml)
//                    );
//        }
        if (qty >= 1) {
            $.ajax({
                type: "get",
                url: "{{url('booster-set/ajax-qty-update')}}",
                data: {qty: qty, booster_cart_id: booster_cart_id},
                success: function (response) {
                    $("#booster-" + response.data.id).closest('tr').find('.total-price').html(response.data.total_price_update)
                    UpdatedTotalPriceQuotation();
                },
                error: function () {

                }

            });
        }

    });
    $(document).on("click", '.close-detail-control-panel-modal', function (event) {
        $("#detail-control-panel-modal").hide();
    });

    function withCurrency(price) {
//        price = price.toFixed(2);
        let dollarUSLocale = Intl.NumberFormat('en-US');
        return dollarUSLocale.format(price) + '$';
    }


    function UpdatedTotalPriceQuotation() {
        var quotation_number = $("#quotation-number").val();
        $.ajax({
            type: "get",
            url: "{{url('controlpanel/quotations/updatedTotalPrice')}}",
            data: {quotation_number: quotation_number},
            success: function (response) {
                if (response.data.total_price_updated) {

                    $("#total-price-updated").html('');
                    $("#total-price-updated").html(response.data.total_price_updated);

                }
            },
            error: function () {

            }

        });
    }

    $(".detail-modal-booster").on("click", function () {
        var booster_id = $(this).closest('tr').find('.booster-cart-id').val();
        $.ajax({
            type: "get",
            url: "{{url('booster/ajax-detail-modal-booster')}}",
            data: {booster_id: booster_id},
            success: function (response) {
                if (response.data.html) {

                    $("#detail-control-panel-modal-body").html('');
                    $("#detail-control-panel-modal-body").html(response.data.html);
                    $("#detail-control-panel-modal").show();
                }
            },
            error: function () {

            }

        });


    });


</script>
@endsection
