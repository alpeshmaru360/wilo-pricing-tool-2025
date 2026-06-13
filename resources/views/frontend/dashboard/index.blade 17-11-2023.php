@extends('frontend.layout.app')
@section('content')
<!-- mid section start-->
<section class="midContent" id="midContent">
    <div class="container">
        <div class="d-flex flex-center">
            <div class="componentMidSection">
            <input type = "hidden" value="">
            <h3>Select Component for Quotation</h3>
            <div class="componentList">
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    {{--<input type="checkbox">--}}
                    @if(isset($quotation_no))
                        @if($quotation_no == "admin")
                            <a href="{{route('boosterset')}}">
                        @else
                            <a href="{{url('booster-set-by-quotation/'.$quotation_no)}}">
                        @endif
                    @else
                        <a href="{{route('boosterset')}}">
                    @endif
                    
                    <label for="">
                    <img src="{{asset('fassets/images/1.png')}}" alt="Component image">
                    <h4>Booster Set</h4>
                    </label>
                    </a>
                </div>
                <div class="componentBox" data-aos="flip-right" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <!-- <input type="checkbox"> -->
                    @if(isset($quotation_no))
						@if($quotation_no == "admin")
                        <a href="{{route('cp.controlpanel')}}">
                        @else
                        <a href="{{url('controlPanel-set-by-quotation/'.$quotation_no)}}">
                        @endif
                    @else
                    <a href="{{route('cp.controlpanel')}}">
                    @endif
                    <label for="">
                    <img src="{{asset('fassets/images/3.png')}}" alt="Component image">
                    <h4>Control Panel</h4>
                    </label>
                    </a>
                </div>
				
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('fire-fighting.index')}}">
                    <label for="">
                    <img src="{{asset('fassets/images/fire-fighting.png')}}" alt="Component image">
                    <h4>Fire Fighting Pump</h4>
                    </label>
                    </a>
                </div>

                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    {{--<input type="checkbox">--}}
                    @if(isset($quotation_no))
						@if($quotation_no == "admin")
                        <a href="{{route('scp.pump')}}">
                        @else
                        <a href="{{url('scp_pump-set-by-quotation/'.$quotation_no)}}">
                        @endif
                    @else
                    <a href="{{route('scp.pump')}}">
                    @endif
                    <label for="">
                    <img src="{{asset('fassets/images/scp_photo.JPG')}}" alt="Component image">
                    <h4>SCP Pump Assembly</h4>
                    </label>
                    </a>
                </div>
                <div class="componentBox" data-aos="flip-right" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <!-- <input type="checkbox"> -->
                    @if(isset($quotation_no))
						@if($quotation_no == "admin")
                        <a href="{{route('ag.atmos_giga')}}">
                        @else
                        <a href="{{url('atmos_giga-set-by-quotation/'.$quotation_no)}}">
                        @endif
                    @else
                    <a href="{{route('ag.atmos_giga')}}">
                    @endif
                    <label for="">
                    <img src="{{asset('fassets/images/atmosgiga.png')}}" alt="Component image">
                    <h4>Atmos GIGA</h4>
                    </label>
                    </a>
                </div>
            </div>
            </div>
        </div>
        <div class="d-flex cusPagination">
            <div class="">
                <a href=""><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
            </div>
            <div class="">
                <button>Next <img src="{{asset('fassets/images/arrowLefticon.png')}}" /></button>
            </div>
        </div>
    </div>
</section>
<!-- mid section end -->
@endsection
