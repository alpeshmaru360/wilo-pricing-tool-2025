@php
    $tool_tip = DB::table('tool_tip')->where('part_id',1)->get();
    foreach($tool_tip as $t){
        $key = $t->component_name;
        $t->$key = $t->tool_tip;
    }
@endphp

@extends('frontend.layout.app')

@section('content')
<section class="midContent" id="midContent">
    <div class="container">
        <div class="flex-center" style="min-height:400px;">
            <div class="pumpInfoMidSection">
                <div class="pumpInfoList">
                    <form id="firefightingForm">
                        <div class="accSec1">
                           <div class="panel formWidget mh-100">
                                <div class="panelBody">
                                    {{-- Main & Jockey Tab Nav --}}
                                    <div class="tabNav px-2 border-0 d-flex justify-content-between m-0 p-0 row">
                                        <button type="button" class="tabLinks col-5" id="tabDefaultOpen" onclick="openTab(event, 'main_pump_panel')" value="main_pump_panel">Main Pump</button>
                                        <button type="button" class="tabLinks col-5" onclick="openTab(event, 'jockey_pump_panel')" value="jockey_pump_panel">Jockey Pump</button>
                                    </div>
                                    <div class="tabContentWrapper">
                                        {{-- Main Pump Panel --}}
                                        <div class="tabContent" id="main_pump_panel">
                                            <div class="row">
                                                <div class="main_panel_selection_hide_show main_panel_selection_set">
                                                    <div class="formFields px-2">
                                                        <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                        <select name="main_panel_selection" id="main_panel_selection" class="formInput main_panel_selection">
                                                            <option value="">Select Pump Models*</option>
                                                            <option value="electrical">Electrical</option>
                                                            <option value="diesel">Diesel</option>
                                                            <option value="electrical-diesel">Electrical & Diesel</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Main Pump Wise --}}
                                            <div class="row mt-3">
                                                <div class="col-12 d-flex justify-content-around flex-nowrap">
                                                    {{-- Main -> Electrical --}}
                                                    <div class="px-2 w-100 main_panel_section-hide electrical-section-show electrical-diesel-section-show">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6 class="main_panel_selection_text">Electrical</h6>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="formFields main_panel_section-hide electrical-section-show">
                                                                    <input type="text" name="electrical_article_number" id="electrical_article_number" class="formInput" placeholder="Pump article number*">
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_pumptype" id="electrical_pumptype" class="formInput electrical-formInput">
                                                                        <option value="">Select Pump type*</option>
                                                                        @foreach($electrical_pump_type as $ek_pump_type => $e_pump_type)
                                                                        <option>{{ $e_pump_type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_frequency" id="electrical_frequency" class="formInput electrical-formInput">
                                                                        <option value="">Select Frequency*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_pump_approval" id="electrical_pump_approval" class="formInput electrical-formInput">
                                                                        <option value="">Select Pump approval*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_flow" id="electrical_flow" class="formInput electrical-formInput">
                                                                        <option value="">Select Flow*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_head" id="electrical_head" class="formInput electrical-formInput">
                                                                        <option value="">Select Head*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_speed" id="electrical_speed" class="formInput electrical-formInput">
                                                                        <option value="">Select Speed*</option>
                                                                    </select>
                                                                </div>
                                                                <!-- 20250108 add motor power field in electrical flow -->
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_motor_power" id="electrical_motor_power" class="formInput electrical-formInput">
                                                                        <option value="">Select Motor Power*</option>
                                                                    </select>
                                                                </div>
                                                                <!-- 20250108 add motor power field in electrical flow -->
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="electrical_control_panel_type" id="electrical_control_panel_type" class="formInput">
                                                                        <option value="">Select Control Panel Type*</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Main -> Diesel --}}
                                                    <div class="px-2 w-100 main_panel_section-hide diesel-section-show electrical-diesel-section-show">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <h6 class="main_panel_selection_text">Diesel</h6>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="formFields main_panel_section-hide diesel-section-show">
                                                                    <input type="text" name="diesel_article_number" id="diesel_article_number" class="formInput" placeholder="Pump article number*">
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_pumptype" id="diesel_pumptype" class="formInput diesel-formInput">
                                                                        <option value="">Select Pump type*</option>
                                                                        @foreach($diesel_pump_type as $dk_pump_type => $d_pump_type)
                                                                        <option>{{ $d_pump_type }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_frequency" id="diesel_frequency" class="formInput diesel-formInput">
                                                                        <option value="">Select Frequency*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_pump_approval" id="diesel_pump_approval" class="formInput diesel-formInput">
                                                                        <option value="">Select Pump approval*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_engine_approval" id="diesel_engine_approval" class="formInput diesel-formInput">
                                                                        <option value="">Select Engine approval*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_flow" id="diesel_flow" class="formInput diesel-formInput">
                                                                        <option value="">Select Flow*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_head" id="diesel_head" class="formInput diesel-formInput">
                                                                        <option value="">Select Head*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_speed" id="diesel_speed" class="formInput diesel-formInput">
                                                                        <option value="">Select Speed*</option>
                                                                    </select>
                                                                </div>
                                                                <div class="formFields" style="display: none;visibility: hidden;">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="diesel_control_panel_type" id="diesel_control_panel_type" class="formInput diesel_control_panel_type-formInput">
                                                                        <option value="">Select Control Panel Type*</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="optBtn m-0 d-flex justify-content-center"><a href="javascript:void(0)" id="optional-button" class="main_panel_section-hide diesel-section-show electrical-section-show electrical-diesel-section-show">Optional</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tabContent" id="jockey_pump_panel">
                                            <div class="row mt-3">
                                                <div class="col-12 px-3 mx-1">
                                                    <h6>Jockey Pump</h6>
                                                </div>
                                                <div class="col-12 d-flex justify-content-around flex-nowrap">
                                                    {{-- Jockey --}}
                                                    <div class="px-2 w-100 jockey_panel_section">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <!-- /** start 20241231 for jockey pump form auto fill***********/ -->
                                                                <div class="formFields">
                                                                    <input type="text" name="jockey_full_article_number" id="jockey_full_article_number" class="formInput" placeholder="Pump Full article number*">
                                                                </div>
                                                                <!-- /** end 20241231 for jockey pump form auto fill***********/ -->
                                                                <div class="formFields">
                                                                    <input type="text" name="jockey_article_number" id="jockey_article_number" class="formInput jockeypump-formInput" placeholder="Pump article number*">
                                                                </div>
                                                                <div class="formFields">
                                                                    <input type="text" name="jockey_pumppower" id="jockey_pumppower" class="formInput jockeypump-formInput" placeholder="Pump Power*">
                                                                </div>
                                                                <div class="formFields">
                                                                    <span class="formArrowIcon"><img src="{{url('fassets/images/arrowDownIcon.png')}}" /></span>
                                                                    <select name="jockey_frequency" id="jockey_frequency" class="formInput jockeypump-formInput">
                                                                        <option value="">Select Frequency*</option>
                                                                        <option>50</option>
                                                                        <option>60</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="optBtn m-0 d-flex justify-content-center"><a href="javascript:void(0)" id="jockey-optional-button" class="">Optional</a></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="d-flex formPageFooter">
            <div class="left">
                Unit Price:
                <button id="calculate" class="clcBtn">Calculate</button>  <span id="price"></span>
            </div>
            <div class="right">
                <ul>
                    <li><a href="#" tooltip="Generate Quotation"><img src="{{asset('fassets/images/generateIcon.png')}}" /></a></li>
                    <li><a href="{{URL::to('controlpanel/cart/'.Auth::user()->id)}}" tooltip="Cart"><img src="{{asset('fassets/images/addIcon.png')}}" /></a></li>
                </ul>
            </div>
        </div>

    </div>
</section>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <div class="modal-body" id="master-price-record">
        </div>
        <div class="modalBtns">
            <button id="addtocart">Add to Cart</button>
            <span class="close" onclick="refresh()">Cancel</span>
            <span class="close-cart-modal" >Close</span>
        </div>
    </div>
</div>

<div id="adder-optional-modal" class="modal">
    <div class="modal-content modal-backdrop">
        <div class="modal-body p-0" id="adder-optional-modal-table">
        </div>
        <div class="modalBtns">
            <span class="close" id="optional-button-add">Add</span>
            <span class="close" id="optional-button-close">Close</span>
        </div>
    </div>
</div>

<div id="error-modal" class="modal">
    <div class="modal-content">
        <div class="modal-body" id="error-modal-body">

        </div>
        <div class="modalBtns">
            <span class="close" id="error-close">Close</span>
        </div>
    </div>
</div>

<div id="price-modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <!-- <span class="close">&times;</span> -->
        <div class="modal-body" id="price-modal-body">
            <div class="formFields">
                <input type="text" name="unit_price_pi_modal" id="unit_price_pi_modal" class="formInput" placeholder="Pump Unit Price">
            </div>
        </div>
        <div class="modalBtns">
            <span class="close" id="price-close">Enter</span>
        </div>
    </div>
</div>

<div id="optional-add-success-modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <div class="modal-body" id="">
            <h4>Optional added Successful!</h4>
        </div>
        <div class="modalBtns">
            <span class="close" id="error-close">Close</span>
        </div>
    </div>
</div>

<div id="other-pump-modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <div class="modal-body" id="other-pump-modal-body">
        </div>
        <div class="modalBtns mt-0 pt-0">
            <span class="close" id="error-close">Close</span>
        </div>
    </div>
</div>

<div id="other-pump-success-modal" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
      <!-- <span class="close">&times;</span> -->
        <div class="modal-body" id="">
            <h4></h4>
        </div>
        <div class="modalBtns">
            <span class="close" id="error-close">Close</span>
        </div>
    </div>
</div>
@endsection

@section('script')
{{-- Full Article Flow --}}
<script>
    var article_modal_show = false;
    $(document).on('blur keyup', '#electrical_article_number', function (e) {
        if (e.keyCode === 13) {
            $(this).blur();
            $('#calculate').click();
            article_modal_show = true;
        }
        if (e.type === 'focusout') {
            if ($(this).val() != '') {
                adderIds = [];
                var articlenumber = $(this).val();
                var findarticlenumber = jQuery.grep(electricalpumparticle, function (filter) {
                    return filter.full_article_number == articlenumber;
                });
             
                if (findarticlenumber.length > 0) {
                    var field_val = findarticlenumber[0].field_val;
                    field_val.forEach(function (val) {
                        
                        for (var vkey in val) {
                            if ($('#'+vkey).length) {
                                $('#'+vkey).val(val[vkey]).change();
                            }
                            if (vkey == 'id') {
                                other_pump_modal_id = val[vkey];
                            }
                            if (vkey == 'electrical_pumpmodels') {
                                other_pump_modal = val[vkey];
                            }
                        }
                    });
                    var adderIdsfind = findarticlenumber[0].all_prices.adderpricelist;
                    if (adderIdsfind.length > 0) {
                        adderIdsfind.forEach(function (val) {
                            adderIds.push('' + val.code);
                        });
                    }

                } else {
                    $("#error-modal-body").html('');
                    $("#error-modal-body").html('<h4>Pump Article Data not found.</h4>');
                    $("#error-modal").show();
                    return;
                }
            }
        }
    });

    $(document).on('blur keyup', '#diesel_article_number', function (e) {
        if (e.keyCode === 13) {
            $(this).blur();
            $('#calculate').click();
            article_modal_show = true;
        }
        if (e.type === 'focusout') {
            if ($(this).val() != '') {
                adderIds = [];
                var articlenumber = $(this).val();
                var findarticlenumber = jQuery.grep(dieselpumparticle, function (filter) {
                    return filter.full_article_number == articlenumber;
                });

                if (findarticlenumber.length > 0) {
                    var field_val = findarticlenumber[0].field_val;
                    field_val.forEach(function (val) {
                        for (var vkey in val) {
                            if ($('#'+vkey).length) {
                                $('#'+vkey).val(val[vkey]).change();
                            }
                            if (vkey == 'id') {
                                other_pump_modal_id = val[vkey];
                            }
                            if (vkey == 'diesel_pumpmodels') {
                                other_pump_modal = val[vkey];
                            }
                        }
                    });
                    var adderIdsfind = findarticlenumber[0].all_prices.adderpricelist;
                    if (adderIdsfind.length > 0) {
                        adderIdsfind.forEach(function (val) {
                            adderIds.push('' + val.code);
                        });
                    }
                } else {
                    $("#error-modal-body").html('');
                    $("#error-modal-body").html('<h4>Pump Article Data not found.</h4>');
                    $("#error-modal").show();
                    return;
                }
            }
        }
    });
</script>

{{-- Disable Flow --}}
<script>
    var adderIds = [];
    var adderprice = 0;
    var pump_type = '';
    $('.main_panel_section-hide').hide();

    $('.tabLinks').each(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    });

    $('.main_panel_selection_hide_show').hide();
    $(document).on('click', '.tabLinks', function () {
        if ($(this).val() == 'main_pump_panel') {
            $('.main_panel_selection_hide_show').show();
        } else if ($(this).val() == 'jockey_pump_panel') {
            $('.tabNav').hide();
            $('.tabNav').removeClass('d-flex');
            jockeyPumpAjax();
        }
    });

    mainPanelShow($('.main_panel_selection:checked'));
    $(document).on('change', '.main_panel_selection', function () {
        mainPanelShow($(this));
        $('.main_panel_selection_hide_show').hide();
        $('.tabNav').hide();
        $('.tabNav').removeClass('d-flex');
        mainPumpAjax();
    });

    disableElectricalInput();
    $(document).on('change', '.electrical-formInput', function () {
        disableElectricalInput($(this).attr('name'));
    });

    disableDieselInput();
    $(document).on('change', '.diesel-formInput', function () {
        disableDieselInput($(this).attr('name'));
    });

    disableInput('jockeypump-formInput');
    $(document).on('change', '.jockeypump-formInput', function () {
        disableInput('jockeypump-formInput');
    });
    
    $(document).on('change', '#diesel_engine_approval', function () {
        if ($('#main_panel_selection').val() == 'electrical-diesel') {
            disableElectricalInput('electrical_flow');
        }
    });

    $(document).on('change', '#diesel_head', function () {
        if ($('#main_panel_selection').val() == 'electrical-diesel') {
            if ($(this).val() != '') {
                // Add options in diesel pump data
                dieselpumptemp = dieselpump;
                var dataSetForTemp = [];
                var disableDieselSelection = {
                    'diesel_pumptype': 'pump_type',
                    'diesel_frequency': 'frequency',
                    'diesel_pump_approval': 'pump_approval',
                    'diesel_engine_approval': 'engine_approval',
                    'diesel_flow': 'flow',
                    'diesel_head': 'head',
                    'diesel_speed': 'speed_rpm'
                };
                var db_original_key = '';
                var original_key_val = '';

                $('.diesel-formInput').each(function() {
                    if ($(this).attr('id') != 'diesel_speed') {

                        db_original_key = disableDieselSelection[$(this).attr('id')];
                        original_key_val = $(this).val();
                        dieselpumptemp = jQuery.grep(dieselpumptemp, function(filter) {
                            if (db_original_key in filter) {
                                return filter[db_original_key] == original_key_val;
                            }
                        });
                    }
                });
                dieselpumptemp.forEach(function(element) {
                    dataSetForTemp.push(element.speed_rpm);
                });

                dataSetForTemp = dataSetForTemp.filter(function(el, index, arr) {
                    return index === arr.indexOf(el);
                });
                dataSetForTemp.sort();
                $('#diesel_speed').find('option').not(':first').remove();
                if (dataSetForTemp.length > 0) {
                    dataSetForTemp.forEach(function(element) {
                        $('#diesel_speed').append($("<option></option>").text(element));
                    });
                } else {
                    alert('Diesel Speed RPM Data not found..!!');
                }
            }
        }
    });

    function disableElectricalInput(changed = '') {
        var disableElectricalList = ['electrical_pumptype', 'electrical_frequency', 'electrical_pump_approval',
            'electrical_flow', 'electrical_head', 'electrical_speed','electrical_motor_power' //20250108 add motor power field in electrical flow
        ];

        var disableDieselList = ['diesel_pumptype', 'diesel_frequency', 'diesel_pump_approval',
            'diesel_engine_approval', 'diesel_flow', 'diesel_head', 'diesel_speed'
        ];

        var disableElectricalSelection = {
            'electrical_pumpmodels': 'wilo_pump_models',
            'electrical_pumptype': 'pump_type',
            'electrical_frequency': 'frequency',
            'electrical_pump_approval': 'pump_approval',
            'electrical_flow': 'flow',
            'electrical_head': 'head',
            'electrical_speed': 'speed_rpm',
            'electrical_motor_power': 'motor_power' //20250108 add motor power field in electrical flow
        };

        var disableElectricalSelectionOptionsText = {
            'electrical_pumpmodels': 'Select Pump Models*',
            'electrical_pumptype': 'Select Pump type*',
            'electrical_frequency': 'Select Frequency*',
            'electrical_pump_approval': 'Select Pump approval*',
            'electrical_flow': 'Select Flow*',
            'electrical_head': 'Select Head*',
            'electrical_speed': 'Select Speed*',
            'electrical_motor_power': 'Select Motor Power', //20250108 add motor power field in electrical flow
            'electrical_control_panel_type': 'Select Control Panel Type*',
        };

        var changeElectricalwithDiesel = {
            'electrical_pumpmodels': 'diesel_pumpmodels',
            'electrical_pumptype': 'diesel_pumptype',
            'electrical_frequency': 'diesel_frequency',
            'electrical_pump_approval': 'diesel_pump_approval',
            'electrical_flow': 'diesel_flow',
            'electrical_head': 'diesel_head',
            'electrical_speed': 'diesel_speed'
        };

        var disableDieselSelection = {
            // 'diesel_pumpmodels':'pump_models',
            'diesel_pumptype': 'pump_type',
            'diesel_frequency': 'frequency',
            'diesel_pump_approval': 'pump_approval',
            'diesel_engine_approval': 'engine_approval',
            'diesel_flow': 'flow',
            'diesel_head': 'head',
            'diesel_speed': 'speed_rpm'
        };
        var dataSetForTemp = [];
        var main_panel_selection = $('#main_panel_selection').val();
        $('#electrical_control_panel_type').prop('disabled', true);
        $('#diesel_control_panel_type').prop('disabled', true);

        if (changed == '') {
            $.each(disableElectricalList, function(key, value) {
                if (key != 0) {
                    $('#' + value).prop('disabled', true);
                }
            });
        } else {
            var selected_options = origin_selected_options = 0;
            $.each(disableElectricalList, function(key, value) {
                if (value == changed) {
                    selected_options = origin_selected_options = key;
                }
            });

            var electricalSelectedData = $('#' + disableElectricalList[selected_options]).val();
            if (electricalSelectedData != '') {
               
                // If Both Select
                if (main_panel_selection == 'electrical-diesel') {
                    if (changed == 'electrical_frequency') {
                        electricalSelectedData = '50/60';
                    }
                    var electrical_with_diesel_change = [];
                    electrical_with_diesel_change.push('<option value="">' + disableElectricalSelectionOptionsText[
                        changed] + '</option>');
                    electrical_with_diesel_change.push('<option selected>' + electricalSelectedData + '</option>');

                    // If Head or Speed from Diesel
                    if (changed == 'electrical_head' || changed == 'electrical_speed' || changed == 'electrical_motor_power') {//20250108 add motor power field in electrical flow

                    } else {
                        $('#' + changeElectricalwithDiesel[changed]).html(electrical_with_diesel_change.join(''));
                    }
                }

                // Filter from array
                var electricalFilterData = [];
                electricalpumptemp = electricalpump;
                for (var i = 0; i <= selected_options; i++) {
                    var original_key = disableElectricalList[i];
                    var db_original_key = disableElectricalSelection[original_key];
                    var original_key_val = $('#' + original_key).val();

                    electricalpumptemp = electricalFilterData = jQuery.grep(electricalpumptemp, function(filter) {
                        if (db_original_key in filter) {
                            return filter[db_original_key] == original_key_val;
                        }
                    });
                }
                
                // Select Next
                selected_options += 1;
                var new_changed = disableElectricalList[selected_options];
                var new_changed_selection = disableElectricalSelection[new_changed];
                var new_changed_selection_text = disableElectricalSelectionOptionsText[new_changed];

                if (main_panel_selection == 'electrical-diesel') {
                    // change custom diesel engine approval
                    if (new_changed == 'electrical_flow') {
                        var electricalDieselFilterData = [];
                        dieselpumptemp = dieselpump;
                        for (var i = 0; i <= selected_options - 1; i++) {
                            var original_key = disableElectricalList[i];
                            var db_original_key = disableDieselSelection[changeElectricalwithDiesel[original_key]];
                            var original_key_val = $('#' + original_key).val();
                            if (original_key == 'electrical_frequency' && (original_key_val == '50' ||
                                    original_key_val == '60')) {
                                original_key_val = '50/60';
                            }
                            dieselpumptemp = electricalDieselFilterData = jQuery.grep(dieselpumptemp, function(filter) {
                                if (db_original_key in filter) {
                                    return filter[db_original_key] == original_key_val;
                                }
                            });
                        }

                        var select_val_electricalDieselFilterData = [];
                        electricalDieselFilterData = $.each(electricalDieselFilterData, function(key, value) {
                            select_val_electricalDieselFilterData.push(value.engine_approval);
                        });

                        electricalDieselFilterData = select_val_electricalDieselFilterData;
                        electricalDieselFilterData = groupSimilar(electricalDieselFilterData);
                        if (electricalDieselFilterData.length > 0) {
                            var diesel_engine_approval_option = [];
                            diesel_engine_approval_option.push('<option value="">Select Engine approval*</option>');
                            $.each(electricalDieselFilterData, function(key, value) {
                                if (value != '' && value != null) {
                                    diesel_engine_approval_option.push('<option>' + value + '</option>');
                                }
                            });
                            $('#diesel_engine_approval').html(diesel_engine_approval_option.join(''));
                        } else {
                            alert('Engine approval data not found.');
                        }
                    }
                }
                console.log(selected_options);
                $('#' + new_changed).prop('disabled', false);
                $('#' + new_changed).prop('disabled', false);

                if (main_panel_selection == 'electrical' || main_panel_selection == 'electrical-diesel') {
                    
                    if (changed == 'electrical_speed' || changed == 'electrical_motor_power') {//20250108 add motor power field in electrical flow
                        
                        if($('#electrical_control_panel_type').val() == '')
                        {
                            // On Head Change recreate options 
                            var dataSetForControlPanelType = true;
                            electrical_control_panel_type_temp = electrical_control_panel_type;
                            //for (var i = 0; i <= selected_options; i++) {
                            
                                var original_key1 = 'electrical_frequency';
                                var db_original_key1 = 'frequency';
                                var original_key_val1 = $('#' + original_key1).val();

                                var original_key2 = 'electrical_pump_approval';
                                var db_original_key2 = 'approval';
                                var original_key_val2 = $('#' + original_key2).val();

                                
                                if (electrical_control_panel_type_temp.length > 0) {
                                    var original_key3 = 'motor_power';
                                    var motor_power = electricalpumptemp[0].motor_power;
                                    //var motor_power = electrical_control_panel_type_temp[0].motor_power;
                                    var db_original_key3 = 'motor_power';
                                    var original_key_val3 = motor_power;
                                }

                                var main_pump_selection = $('.main_panel_selection').find(":selected").val();

                                if (main_pump_selection == 'electrical' || main_pump_selection == 'electrical-diesel') {
                                    var original_key4 = 'category';
                                    var db_original_key4 = 'category';
                                    var original_key_val4 = 'Electrical';
                                }else{
                                    var original_key4 = 'category';
                                    var db_original_key4 = 'category';
                                    var original_key_val4 = 'Diesel';
                                }


                                if (original_key_val1 == undefined || original_key_val1 == '' || original_key_val2 == undefined || original_key_val2 == '' || original_key_val3 == undefined || original_key_val3 == '' || original_key_val4 == undefined || original_key_val4 == '') {
                                    dataSetForControlPanelType = false;
                                } else {
                                    
                                    /** start 20241231 for jockey pump form auto fill***********/
                                    // electrical_control_panel_type_temp = jQuery.grep(electrical_control_panel_type_temp, function(filter) {
                  
                                    //     if (db_original_key1 in filter) {
                                    //         return filter[db_original_key1] == original_key_val1;
                                    //     }
                                    // });
                                   
                                    // electrical_control_panel_type_temp = jQuery.grep(electrical_control_panel_type_temp, function(filter) {

                                    //     if (db_original_key2 in filter) {
                                    //         return filter[db_original_key2] == original_key_val2;
                                    //     }
                                    // });

                                    // electrical_control_panel_type_temp = jQuery.grep(electrical_control_panel_type_temp, function(filter) {

                                    //     if (db_original_key3 in filter) {
                                    //         return filter[db_original_key3] == original_key_val3;
                                    //     }

                                    // });
                                    /** end 20241231 for jockey pump form auto fill***********/

                                    dataSetForTempVar = [];
                                    electrical_control_panel_type_temp.forEach(function(element) {
                                        dataSetForTempVar.push(element.type);
                                    });

                                    dataSetForTempVar = dataSetForTempVar.filter(function(el, index, arr) {
                                        return index === arr.indexOf(el);
                                    });
                                    dataSetForTempVar.sort();
                                    $('#electrical_control_panel_type').prop('disabled', false);
                                    $('#electrical_control_panel_type').find('option').not(':first').remove();

                                    dataSetForTempVar.forEach(function(element) {
                                        $('#electrical_control_panel_type').append($("<option></option>").text(element));
                                    });
                                }
                            //}
                            if (electrical_control_panel_type_temp.length <= 0) {
                                //alert('Electrical Control Type Data not found..!!');
                                $("#error-modal-body").html('');
                                $("#error-modal-body").html('<h4>Electrical Control Type Data not found..!!</h4>');
                                $("#error-modal").show();
                                return;
                            }
                        }
                        else
                        {
                            $("#electrical_control_panel_type").prop('disabled', false);
                        }
                    }
                }

                // Custom Option for Electrical & Diesel Options
                if (main_panel_selection == 'electrical-diesel') {
                    // change custom diesel head
                    if (new_changed == 'electrical_head') {
                        var dataSetForHead = true;
                        dieselpumptemp = dieselpump;
                        for (var i = 0; i <= selected_options; i++) {

                            var original_key = disableDieselList[i];
                            var db_original_key = disableDieselSelection[original_key];
                            var original_key_val = $('#' + original_key).val();

                            if (original_key_val == undefined || original_key_val == '') {
                                dataSetForHead = false;
                            } else {
                                dieselpumptemp