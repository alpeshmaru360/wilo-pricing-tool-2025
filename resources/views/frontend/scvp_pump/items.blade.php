@php

if(count($items) >= 0){
    $scpData = App\ScpCart::where('id', $cartId)->first();
}
elseif(!Schema::hasColumn('scp_carts', 'scp_cart_id') && $is_manual == "1"){
    $atmos = App\ScpCart::where('id', $cartId)->first();
}
else{
    $scpData = App\ScpCart::where('id', $items[0]->scp_cart_id)->get()[0];
}                 

//$motor_price = DB::table('scp_master_motor_prices')->where('id',$scpData->master_id)->pluck('price')[0] ?? 0;
                  $article_number = DB::table('scp_pump_types')->where('id',$scpData->pump_id)->pluck('bare_shaft_article_number')->first();
                  //dd($motor_price); 
$motor_price =  DB::table('scp_master_motor_prices')
                                    ->where('brand',$scpData->brand)
                                    ->where('power',$scpData->power)
                                    ->where('no_of_pole',$scpData->no_of_pole)
                                    ->where('frequency',$scpData->frequency)
                                    ->where('voltage',$scpData->voltage)
                                    // ->where('price',$scpData->accessories_price)
                                    ->get();
                                    if($scpData->application == 2)             {     
                                        $motor_price = $motor_price[0]->price + $motor_price[0]->insulate_bearing;
                                    }else{
                                        $motor_price = $motor_price[0]->price;
                                    }				  

@endphp
@extends('frontend.layout.app')
@section('content')

<!-- mid section start-->
<section class="midContent" id="midContent">
    <div class="container">
        <div class="d-flex flex-center">
            <div class="cartMidSection">
                <h2>Bill Of Material</h2>
                <div class="cartSection">
                    <div class="tableResponsive">
                        <table>
                            <thead>
                                <tr>
                                    <th width="8%">S.No</th>
                                    <th width="25%">Description</th>
                                    <th width="25%">Article No.</th>
                                    <th width="8%">Adder Code</th>
                                    @if (auth()->user()->isAdmin())
                                    <th width="10%">Unit Price</th>
                                    @endif

                                    <th width="29%">Qty</th>

                                    @if (auth()->user()->isAdmin())
                                    <th width="10%">Total Price</th>


                                    @endif

                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $j = 1
                                @endphp
                                @if($items->isNotEmpty())
                                @foreach($items as $key=> $item)
                                @if($item->item_description != null)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$item->item_description}}</td>
                                    <td>{{$item->wilo_artilce_no}}</td>
                                    <td></td>
                                        <!--<td>
                                        <?php
                                        $txtArr = explode("x", $item->adder_code);
                                        $i = array_search("x", explode("x", $item->adder_code));
                                        unset($txtArr[$i + 2]);
                                        unset($txtArr[$i + 1]);
                                        echo implode(" ", $txtArr);
                                        ?>
                                    </td>-->
                                    @if (auth()->user()->isAdmin())
                                    <td>{{$item->unit_price}}</td>  
                                    @endif
                                    <td>
                                        {{$item->qty}}
                                    </td>
                                    @if (auth()->user()->isAdmin())
                                    <td>{{ round($item->unit_price*$item->qty,2) }}</td>
                                    @endif
                                </tr>
                                @php
                                $j++
                                @endphp
                                @endif
                                @endforeach
                                @endif

                                @if(count($adderData) > 0)
                                @foreach($adderData as $key=> $item)
                                    <tr>
                                        <td>{{$j}}</td>
                                        <td>{{$item['name']}}</td>
                                        <td></td>
                                        <td>{{$item['id']}}</td>
                                        @if(auth()->user()->isAdmin())
                                        <td>{{$item['price']}}</td>
                                        @endif
                                        <td>1</td>
                                        @if(auth()->user()->isAdmin())
                                            <td>{{$item['price']}}</td>
                                        @endif
                                    </tr>
                                    @php
                                    $j++
                                    @endphp
                                @endforeach
                                @endif
                                <tr>
                                <td>
                                        {{$j}}
                                </td>
                                <td>
                                        {{$scpData->power}}KW {{$scpData->no_of_pole}}P {{$scpData->effieciency}} {{$scpData->voltage}}V {{$scpData->frequency}}Hz {{$scpData->brand}} {{$scpData->application == 1 ? "constant" : "Variable"}} Speed  
                                </td>
                                @if (auth()->user()->isAdmin())
                                <td>
								{{--$motor_price--}}
                                </td>
                                
                                @endif
								<td></td>
								@if (auth()->user()->isAdmin())
                                <td>
								{{$motor_price}}
                                </td>
								@else
								<td></td>
								@endif
                                <td>
                                        1
                                </td>
                                @if (auth()->user()->isAdmin())
                                <td>
                                                {{$motor_price}}
                                </td>
                                            @endif   
                                </tr>
                                <tr>
                                <td>
                                        {{$j+1}}
                                </td>
                                <td>
                                        {{$scpData->pump_name}}
                                </td>
                                <td>
                                        {{$article_number}}
                                </td>
                                <td></td>
                                @if (auth()->user()->isAdmin()) 
                                <td>
								{{$scpData->bare_pump_price}}
							</td>
                                @endif
                                <td>
                                        1
                                </td>
                                @if (auth()->user()->isAdmin())
                                <td>    
                                                {{$scpData->bare_pump_price}}
                                </td>
                                            @endif
                                </tr>

                                @if($scpData->is_accesories_manual == "1")
                                    <tr>
                                        <td>{{$j+2}}</td>
                                        <td>
                                            Accessories-Manual
                                        </td>
                                        <td>
                                        </td>
                                         <td>
                                        </td>
                                        @if (auth()->user()->isAdmin())
                                        <td>{{$scpData->accesories_price}}</td>
                                        @endif
                                        <td>1</td>
                                        @if(auth()->user()->isAdmin())
                                        <td>
                                        {{$scpData->accesories_price}}
                                        </td>
                                        @endif
                                    </tr>
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex cusPagination d-none">
            <div class="">
                <a href=""><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
            </div>
            <div class="">
                <button>Next <img src="{{asset('fassets/images/arrowLefticon.png')}}" /></button>
            </div>
        </div>
        <div class="d-flex formPageFooter">
            <div class="left">
                <!-- Unit Price: <button class="clcBtn">Calculate</button> <span>750€</span> -->
            </div>
            <div class="right">
                <?php $cartId = Request::segment(3); ?>
                <ul>
                    <!--<li><a href="#" tooltip="Generate Quotation"><img src="{{asset('fassets/images/generateIcon.png')}}" /></a></li>-->
                    <li><a href="{{URL::to('/')}}" tooltip="Go to Home Page"><img src="{{asset('fassets/images/homeIcon.png')}}" /></a></li>                     
                    <li><a href="{{URL::to('controlpanel/cart/' . $cartId) }}" tooltip="Cart"><img src="{{asset('fassets/images/addIcon.png')}}" /></a></li>
                    <!--<li><a href="{{URL::to('controlpanel/customer-information?cp_id=' . $cartId) }}" tooltip="Generate Quotation"><img src="{{asset('fassets/images/goIcon.png')}}" /></a></li>-->
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- mid section end -->
@endsection