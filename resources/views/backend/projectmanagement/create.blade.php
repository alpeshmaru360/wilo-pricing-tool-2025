@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.projectmanagement.store'))->class('form-horizontal')->acceptsFiles()->open() }}

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('Project Management')
                        <small class="text-muted">@lang('Add New Project')</small>
                    </h4>
                </div>
                <!--col-->
                <div class="col-sm-7 text-right">
                    {{-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal"
                        onclick="showuser()"><i class="fas fa-plus-circle"></i> Add Users</button> --}}
                </div>
            </div>
            <!--row-->

            <hr>

            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="row">
                        <div class="col-md-6">
                            {{ html()->label(__('Project Name <span class="text-danger">*</span>'))->class('form-control-label')->for('first_name') }}
                            <div class="form-group">
                                {{ html()->text('project_name')->class('form-control')->placeholder(__('Project Name'))->attribute('maxlength', 191)->autofocus() }}
                            </div>
                            <!--form-group-->
                        </div><!-- col-md-6 end -->

                        <div class="col-md-6">
                            {{ html()->label(__('Project Segment'))->class('form-control-label')->for('project_segment') }}
                            <div class="form-group">
                                <select name="project_segment" id="project_segment" class="form-control">
                                    <option value="0">Select Project Segment</option>
                                    @foreach($project_segment as $d)

                                        <option value="{{$d->id}}" {{ old('project_segment') == $d->id ? 'selected' : '' }}>
                                            {{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div><!-- col-md-6 end -->

                        <div class="col-md-6">
                            {{ html()->label(__('Application Type <span class="text-danger">*</span>'))->class('form-control-label')->for('apptype') }}
                            <div class="form-group">
                                <select multiple name="apptype[]" id="apptype" class="form-control">
                                    @foreach($get_application as $d)
                                        <option value="{{$d->id}}" {{ old('apptype') == $d->id ? 'selected' : '' }}>{{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div><!-- col-md-6 end -->


                        <div class="col-md-6">
                            {{ html()->label(__('Sub Application Type <span class="text-danger"></span>'))->class('form-control-label')->for('subapptype') }}
                            <div class="form-group">
                                <select multiple name="subapptype[]" id="subapptype" class="form-control">
                                    @foreach($get_sub_application as $subapp)
                                        <!-- <option value="{{$subapp->id}}" {{ old('subapptype') == $subapp->id ? 'selected' : '' }}>{{$subapp->name}}</option> -->
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div><!-- col-md-6 end -->



                        <div class="row m-0 w-100">


                            <div class="col-md-6">
                                {{ html()->label(__('Type of Project <span class="text-danger">*</span>'))->class('form-control-label')->for('typeproject') }}
                                <div class="form-group">
                                    <select multiple name="typeproject[]" id="typeproject" class="form-control">
                                        <option>Select Type of Project</option>
                                        @foreach($project_type as $d)

                                            <option value="{{ $d->id }}" {{ old('typeproject') && in_array($d->id, old('typeproject')) ? 'selected' : '' }}>{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{ html()->label(__('Country <span class="text-danger">*</span>'))->class('form-control-label')->for('country') }}
                                <div class="form-group">
                                    <select name="country" id="country" class="form-control">
                                        <option value="0">Select Country</option>
                                        @foreach($country as $d)
                                            {{-- <option>Country</option> --}}
                                            <option value="{{$d->id}}" {{ old('country') == $d->id ? 'selected' : '' }}>
                                                {{$d->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--form-group-->

                                <!-- {{-- html()->label(__('Year of Installation <span class="text-danger"></span>'))->class('form-control-label')->for('bf') --}} -->
                                <!-- <div class="form-group"> -->
                                <!-- {{ html()->text('year')
                                                    ->class('form-control')
                                                    ->placeholder(__('Year of Installation'))
                                                    ->attribute('maxlength', 190)

                                                    ->autofocus() }} -->
                                <!-- <select name="year" id="year" class="form-control">
                                                    <option value="">Select Year of Installation</option>
                                                    @for($i=1990; $i<= 2050; $i++) <option value="{{$i}}" {{ old('year') == $i ? 'selected' : '' }}>{{$i}}</option>
                                                        @endfor
                                                </select>
                                            </div> -->
                                <!--form-group-->
                            </div><!-- col-md-6 end -->


                            <div class="col-md-6">
                                {{ html()->label(__('Project Brief <span class="text-danger">* <span style="font-size:10px">(Max. Length: 999 characters)</span></span>'))->class('form-control-label')->for('bf') }}
                                <div class="form-group">
                                    {{ html()->textarea('project_brief')
                                                ->class('form-control')
                                                ->placeholder(__('Project Brief'))
                                                ->attribute('maxlength', 999)
                                                ->autofocus() }}
                                </div>
                                <!--form-group-->
                            </div><!-- col-md-6 end -->

                        </div><!-- end row -->
                        <!--form-group-->

                        {{--
                        <div class="col-md-6">

                        </div><!--form-group-->
                    </div><!-- col-md-6 end -->
                    {{--
                    <div class="col-md-6">

                        {{ html()->label(__('Remarks'))->class('form-control-label')->for('remarks') }}
                        <div class="form-group">
                            {{ html()->textarea('remarks')
                            ->class('form-control')
                            ->placeholder(__('Remarks'))
                            ->attribute('maxlength', 300)

                            ->autofocus() }}
                        </div>
                        <!--form-group-->
                    </div><!-- col-md-6 end -->
                    --}}

                    <div class="col-md-6">
                        {{ html()->label(__('City <span class="text-danger">*</span>'))->class('form-control-label')->for('city') }}
                        <div class="form-group">
                            {{ html()->text('city')
                                    ->class('form-control')
                                    ->placeholder(__('City'))
                                    ->attribute('maxlength', 190)
                                    ->autofocus() }}
                        </div>
                        <!--form-group-->
                    </div><!-- col-md-6 end -->

                    <div class="col-md-6">
                        <!-- <div class="img_left">
                                        <span id="clientimg"><img src="{{ asset('images/placeholder_img.png') }}" height="64px" width="64px"></span>
                                    </div> -->
                        <div class="field_content">
                            {{ html()->label(__('Client'))->class('form-control-label')->for('client') }}
                            <div class="form-group">
                                <select id="select-client" name="client" class="form-control">
                                    <option value="0">Select Client</option>
                                    @foreach($stakeholder_client as $d)
                                        <option value="{{$d->id}}" {{ old('client') == $d->id ? 'selected' : '' }}>{{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div>
                    </div><!-- col-md-6 end -->

                    <div class="col-md-6">
                        <!-- <div class="img_left">
                                        <span id="contractorimg"><img src="{{ asset('images/placeholder_img.png') }}" height="64px" width="64px"></span>
                                    </div> -->
                        <div class="field_content">
                            {{ html()->label(__('Contractor'))->class('form-control-label')->for('contractor') }}
                            <div class="form-group">
                                <select id="select-contractor" name="contractor" class="form-control">
                                    <option value="0">Select Contractor</option>
                                    @foreach($stakeholder_contractor as $d)
                                        <option value="{{$d->id}}" {{ old('contractor') == $d->id ? 'selected' : '' }}>
                                            {{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div>
                    </div><!-- col-md-6 end -->

                    <div class="col-md-6">
                        <!-- <div class="img_left">
                                        <span id="consultantimg"><img src="{{ asset('images/placeholder_img.png') }}" height="64px" width="64px"></span>
                                    </div> -->
                        <div class="field_content">
                            {{ html()->label(__('Consultant'))->class('form-control-label')->for('consultant') }}
                            <div class="form-group">
                                <select id="select-consultant" name="consultant" class="form-control">
                                    <option value="0">Select Consultant</option>
                                    @foreach($stakeholder_consultant as $d)
                                        <option value="{{$d->id}}" {{ old('consultant') == $d->id ? 'selected' : '' }}>
                                            {{$d->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div>
                    </div><!-- col-md-6 end -->

                    <div class="col-md-6">
                        <div class="field_content">
                            {{ html()->label(__('Factory Manufacturer'))->class('form-control-label')->for('factory_manufacturer') }}
                            <div class="form-group">
                                <select id="select-factory_manufacturer" name="factory_manufacturer[]" class="form-control"
                                    multiple>
                                    @foreach($factory_manufacturer as $fm)
                                        <option value="{{$fm->id}}">{{$fm->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div>
                    </div><!-- col-md-6 end -->



                    <div class="col-md-6">
                        <div class="field_content">
                            {{ html()->label(__('Year Manufactured'))->class('form-control-label')->for('manufacturer_year') }}
                            <div class="form-group">
                                <select id="select-manufacturer_year" name="manufacturer_year" class="form-control">
                                    <option value="" disabled selected></option>
                                    @foreach ($years as $year => $label)
                                        <option value="{{ $year }}">
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <!--form-group-->
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('Project image '))->class('form-control-label')->for('projectimage') }}
                        <div class="form-group cs_file_btn"><span class="up_btn btn-info">
                            <i class="fas fa-cloud-upload-alt"></i> Upload Project Image</span>
                            {{ html()->file('projectimage')->required() }}

                        </div>
                        <span style="font-size:10px" class="text-danger"> (Min. Width: 800px, Min. Height: 400px required).
                        </span>
                        <br>
                        <span style="font-size:10px" class="text-danger"> (Width: 1330px, Height: 550px suggested). </span>
                        <br>
                        <span style="font-size:10px" class="text-danger"> Upload a high resolution image.</span>

                        <!--form-group-->
                    </div><!-- col-md-6 end -->

                </div> <!-- row end -->

                <div id="showdiv">
                    <span></span>
                </div>

            </div>
            <!--col-->

            <div id="showdiv">

            </div>
        </div>
        <!--row-->

    </div>
    <!--card-body-->

    <!--model-->

    <!-- Modal -->
    <div class="modal my_modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><i class="far fa-times-circle"></i></button>
                    <h4 class="modal-title">Modal Header</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row align-items-center">
                        {{ html()->label(__('Add Users'))->class('col-md-4 form-control-label')->for('User') }}
                        <div class="col-md-8">
                            <Select class="form-control">
                                <option value="">1</option>
                                <option value="">2</option>
                                <option value="">3</option>
                            </Select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div id="show_users">
                            <ul> </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="hvr_br"><button type="button" class="btn btn-info " data-dismiss="modal"
                            onclick="">Add</button></span>
                    <span class="hvr_br"><button type="button" class="btn btn-danger"
                            data-dismiss="modal">Close</button></span>
                </div>
            </div>

        </div>
    </div>
    <!--End Model -->



    <div class="card-footer clearfix">
        <div class="row">
            <div class="col">
                <span
                    class="hvr_br">{{ form_cancel(route('admin.projectmanagement.index'), __('buttons.general.cancel')) }}</span>
            </div>
            <!--col-->

            <div class="col text-right">
                <span class="hvr_br">{{ form_submit(__('buttons.general.crud.create')) }}</span>
            </div>
            <!--col-->
        </div>
        <!--row-->
    </div>
    <!--card-footer-->
    </div>
    <!--card-->

    {{ html()->form()->close() }}
@endsection

<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
    $(function () {
        $('#showdiv').hide();
        // $('select[name=apptype]').change(function() {

        //     var url = '{{url('admin/ajaxsubapplication')}}' + "/" + $(this).val();
        //     console.log(url);
        //     $.get(url, function(data) {
        //         var select = $('form select[name= subapptype]');

        //         select.empty();
        //         select.append('<option value="0">Type of Sub Application</option>');
        //         $.each(data, function(key, value) {
        //             select.append('<option value=' + value.id + '>' + value.name + '</option>');
        //         });
        //     });
        // });

        // get client image
        // $('select[name=client]').change(function() {

        //     var url = '{{url('admin/ajaximage')}}' + "/" + $(this).val();
        //     console.log(url);
        //     $.get(url, function(data) {
        //         $('#clientimg').empty().append('<img src="' + data + '" height="64px" width="64px">');
        //     });
        // });
        // $('select[name=contractor]').change(function() {

        //     var url = '{{url('admin/ajaximage')}}' + "/" + $(this).val();
        //     console.log(url);
        //     $.get(url, function(data) {
        //         $('#contractorimg').empty().append('<img src="' + data + '" height="64px" width="64px">');
        //     });
        // });
        // $('select[name=consultant]').change(function() {

        //     var url = '{{url('admin/ajaximage')}}' + "/" + $(this).val();
        //     console.log(url);
        //     $.get(url, function(data) {
        //         $('#consultantimg').empty().append('<img src="' + data + '" height="64px" width="64px">');
        //     });
        // });

    });
</script>
<script>
    $(document).ready(function () {
        $("#apptype").select2({
            placeholder: 'Select Application Type',
            maximumSelectionLength: 3
        });
        $("#subapptype").select2({
            placeholder: 'Select Sub Application Type',
            maximumSelectionLength: 3
        });
        $("#select-factory_manufacturer").select2({
            placeholder: 'Select Factory Manufacturer',
            allowClear: true
        });

        $("#select-manufacturer_year").select2({
            placeholder: 'Select Manufactured Year',
        });

        $('#apptype').on('select2:close', function (evt) {
            var subappvalue = '';
            if ($(this).val() == '') {
                subappvalue = 0;
                $('#subapptype').empty().trigger('change');
            }
            else {
                subappvalue = $(this).val();
                var url = '{{ url('admin/ajaxsubapplication') }}' + "/" + subappvalue;
                $.get(url, function (data) {
                    if (data != 0) {
                        // console.log("data length"+data.length);
                        // console.log(data[0]['parent']);
                        //var select = $('#subapptype');
                        $('#subapptype').empty().trigger('change');
                        //select.append('<option value="0" id="sub">Select Sub Application</option>'); 
                        // var count = 0;
                        // var first = 0;
                        // var datacount = 0;
                        // var par;
                        var i = 0;
                        var d = 0;
                        var html = "";
                        $.each(data, function (key, value) {

                            html += "<optgroup label='" + key + "'>";
                            $.each(value, function (i, d) {
                                html += "<option value='" + d.id + "'>" + d.name + "</option>"
                            })
                            //  var par = value.parent;   

                            // if(datacount > 0)
                            // {
                            //     i--;
                            //     // data[i]['parent'];
                            // }

                            // if(first == 0)
                            // {
                            //     $('#subapptype').append('<option disabled>'+data[0]['parent']+'</option>');
                            //     first++;
                            // }

                            // console.log(value.parent);
                            // // if(d > 0){
                            // if(data[i]['parent'] != value.parent && count == 0){
                            //     $('#subapptype').append('<option disabled>'+value.parent+'</option>');
                            //     count++;
                            // }
                            // // }
                            $('#subapptype').html(html).trigger('change');
                            // datacount++;
                            // i+2;
                            // d++;
                        });
                        //$('#subapptype').trigger('change');
                    } else {
                        $('#subapptype').empty().trigger('change');
                    }
                });
            }
        });
    });
</script>