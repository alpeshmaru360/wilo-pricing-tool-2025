@extends('frontend.layout.app')
@section('content')

    <!-- mid section start-->

    <section class="midContent" id="midContent">
        <div class="container">
            <div class="d-flex flex-center">
                <div class="addQuotationMidSection">
                    <h2>Documents</h2>
                    <select id="component" class="form-control" name="component" style="font-size: 20px;padding: 10px;">
                        <option value="">Select Component</option>
                        <option value="booster">Booster</option>
                        <option value="control_panel">Control Panel</option>
                        <option value="atmos">Atmos</option>
                        <option value="scp">Scp</option>
                    </select>
                    <input style="font-size: 20px;padding: 10px;" type="text" id="article_number" name="article_number" placeholder="Article Number" value={{$query_param}}>
                    <input style="font-size: 20px;padding: 10px;" type="button" id="search" value="search">
                    <input style="font-size: 20px;padding: 10px;" type="button" id="clear" value="clear">
                    <div class="quotationBottomSection">
                        <div class="tableResponsive">
                            <table>
                                <thead>
                                <tr>

                                    <th width="25%">Item Description</th>
                                    <th width="25%">Article Number</th>
                                    <th width="25%">Component</th>
                                    <th width="25%">Documents</th>

                                </tr>


                                </thead>
                                <tbody>
                                @if(isset($controlPanelCartData))
                                @if($controlPanelCartData->isNotEmpty())
                                    @foreach($controlPanelCartData as $key=> $val)


                                        <tr>

                                            <td>Control Panel {{$val->noofpumps['value'] }} x {{ $val->powers['value'] }}KW {{$val->starter_code}}/AE</td>
                                            <td>{{$val['article_number']}}</td>
                                            <td>Control Panel </td>
                                            <td>
                                                @if(empty($val->documents))
                                                    <a href="javascript:void(0)">
                                                @else
                                                            @foreach($val->documents as $key=>$d)
                                                            <a href="{{ URL::to('public/articles/'.$d->file_name )}}" target="_blank">
                                                                <img src="{{asset('public/assets/icons/file.svg')}}" /> {{$d->file_name}} <br>
                                                            @endforeach
                                                                @endif
                                                            </a>
                                                    </a>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endif
                                @endif

                                @if(isset($atmosCartData))
                                @if($atmosCartData->isNotEmpty())
                                    @foreach($atmosCartData as $key=> $val)
                                        @php

                                            $short_code = DB::table('atmos_materials')->where('id',$val->material_id)->pluck("short_code")->first();


                                        @endphp
                                        <tr>

                                            <td>
                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{$val->pump_name }} -{{$short_code}}/{{$val->power}}KW/{{$val->no_of_pole}}/AE
                                                </a>
                                            </td>
                                            <td>
                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                                </a>
                                            </td>
                                            <td>Atmos Giga</td>
                                            <td>
                                                @if(empty($val->documents))
                                                    <a href="javascript:void(0)">
                                                        @else
                                                            @foreach($val->documents as $key=>$d)
                                                                <a href="{{ URL::to('public/articles/'.$d->file_name )}}" target="_blank">
                                                                    <img src="{{asset('public/assets/icons/file.svg')}}" /> {{$d->file_name}} <br>
                                                                    @endforeach
                                                                    @endif
                                                                </a>
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @endif

                                @if(isset($scpCartData))
                                @if($scpCartData->isNotEmpty())
                                    @foreach($scpCartData as $key=> $val)
                                        @php

                                            $short_code = DB::table('scp_materials')->where('id',$val->material_id)->pluck("short_code")->first();


                                        @endphp
                                        <tr>
                                            <td>
                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{$val->pump_name }} -{{$short_code}}/{{$val->power}}KW/{{$val->no_of_pole}}/AE
                                                </a>
                                            </td>
                                            <td>
                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                                </a>
                                            </td>
                                            <td>Scp Pump</td>
<!--                                            <td>{{ App\Helpers\CurrencyHelper::withCurrency($val['price'])}}</td>-->
                                            <td>
                                                @if(empty($val->documents))
                                                    <a href="javascript:void(0)">
                                                        @else
                                                            @foreach($val->documents as $key=>$d)
                                                                <a href="{{ URL::to('public/articles/'.$d->file_name )}}" target="_blank">
                                                                    <img src="{{asset('public/assets/icons/file.svg')}}" /> {{$d->file_name}} <br>
                                                                    @endforeach
                                                                    @endif
                                                                </a>
                                                    </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @endif

                                {{--                                booster cart starts--}}
                                @if(isset($boosterCartData))
                                @if($boosterCartData->isNotEmpty())
                                    @foreach($boosterCartData as $key=> $val)


                                        <tr>
                                            <td>

                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{$val->model_no }} x {{ $val->pump_type }} x {{$val->motor_power}}
                                                </a>
                                            </td>
                                            <td>
                                                <a class="detail-modal" href="javascript:void(0)">
                                                    {{ !empty($val['article_number']) ? $val['article_number'] : '--' }}
                                                </a>
                                            </td>
                                            <td>Booster</td>
                                            <td>
                                                @if(empty($val->documents))
                                                    <a href="javascript:void(0)">
                                                        @else
                                                            @foreach($val->documents as $key=>$d)
                                                                <a href="{{ URL::to('public/articles/'.$d->file_name )}}" target="_blank">
                                                                    <img src="{{asset('public/assets/icons/file.svg')}}" /> {{$d->file_name}} <br>
                                                                    @endforeach
                                                                    @endif
                                                                </a>
                                                    </a>
                                            </td>


                                        </tr>
                                    @endforeach
                                @endif
                                @endif
                                {{--                                booster cart ends--}}
                                </tbody>
                            </table>
{{--                            <div class="text-left" id=""><h4>Total Price: </h4><span class="" id="total-price-updated"> {{App\Helpers\CurrencyHelper::withCurrency($totalPrice) }}</span></div>--}}

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
                    <a  onclick="window.history.back()" href="javascript:void(0)"><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
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
                        <li><a href="{{URL::to('/')}}" tooltip="Cart"><img src="{{asset('fassets/images/addIcon.png')}}" /></a></li>
                    <!--<li><a href="#" tooltip="Checkout"><img src="{{asset('fassets/images/goIcon.png')}}" /></a></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- mid section end -->


<script>

$("#search").click(function(){

    current_url = window.location.href.split('?')[0]+"?component="+$("#component :selected").val()+"&article_number="+$("#article_number").val();
    if($("#component :selected").val() == "" || $("#article_number").val() == "")
    {

        alert("Article Number and Component Name are required to search document.");

    }else{
     
        window.location.href=current_url;

    }

});

$("#clear").click(function(){

    window.location.href=window.location.href.split('?')[0];

});

</script>
@endsection