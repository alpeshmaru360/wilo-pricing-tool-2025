@extends('frontend.layout.app')
@section('content')
<!-- mid section start-->
<section class="midContent" id="midContent">
    <div class="container">
        <div class="d-flex flex-center">
            <div class="componentMidSection">
            <h3>Select Component for Quotation</h3>
            <div class="componentList">
                @if(auth()->user()->booster_access == "1")
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('boosterset')}}">
                    <label for="">
                    <img src="{{asset('fassets/images/1.png')}}" alt="Component image">
                    <h4>Booster Set</h4>
                    </label>
                    </a>
                </div>
                @endif

                @if(auth()->user()->control_panel_access == "1")
                <div class="componentBox" data-aos="flip-right" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('cp.controlpanel')}}">

                    <label for="">
                    <img src="{{asset('fassets/images/control_panel.png')}}" alt="Component image" style="width:47%;">
                    <h4>Control Panel</h4>
                    </label>
                    </a>
                </div>
                @endif

                @if(auth()->user()->fire_fighting_access == "1")
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('fire-fighting.index')}}">
                    <label for="">
                    <img src="{{asset('fassets/images/fire-fighting.png')}}" alt="Component image">
                    <h4>Fire Fighting Pump</h4>
                    </label>
                    </a>
                </div>
                @endif

                @if(auth()->user()->scp_access == "1")
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('scp.pump')}}">
                    <label for="">
                    <img src="{{asset('fassets/images/scp_photo.JPG')}}" alt="Component image">
                    <h4>SCP Pump Assembly</h4>
                    </label>
                    </a>
                </div>
                @endif

                @if(auth()->user()->atmos_access == "1")
                <div class="componentBox" data-aos="flip-right" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    <a href="{{route('ag.atmos_giga')}}">
                    <label for="">
                    <img src="{{asset('fassets/images/atmosgiga.png')}}" alt="Component image">
                    <h4>Atmos GIGA</h4>
                    </label>
                    </a>
                </div>
                @endif
				{{--
                @if(auth()->user()->sch_access == "1")
                <div class="componentBox" data-aos="flip-left" data-aos-offset="300"
                data-aos-easing="ease-in-sine">
                    @if(isset($maintance_mode_atmos))
                        @if($maintance_mode_sch == "0.0" || $maintance_mode_sch == "0")
                            <a href="{{route('sch.pump')}}">
                        @else
                            <a href="{{route('is_maintance_mode',['label'=>'sch_maintance_mode'])}}">
                        @endif
                    @endif
                    <label for="">
                    <img src="{{asset('fassets/images/atmosgiga.png')}}" alt="No image found">
                    <h4>SCH Pump</h4>
                    </label>
                    </a>
                </div>
                @endif
				--}}
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
