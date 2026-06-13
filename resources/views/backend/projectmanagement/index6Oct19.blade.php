{{--dd($data)--}}
@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Project Management
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">
                @include('backend.projectmanagement.includes.header-buttons')
            </div>
            <!--col-->
        </div>
        <!--row-->
        <hr>
        <div class="new_search_wrap">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="form-group new_search_div">
                        <label for="sel1">Search:</label>
                        <input title="Search Project name" type="text" class="form-control" name="search_request" id="search_request" placeholder="Search Project" value="<?php if (isset($_GET['filter']) && $_GET['filter'] != "") echo $_GET['filter']; ?>">

                    </div>
                </div>
                <!-- <div class="text-right">
                <span class="hvr_br"><a href="JavaScript:Void(0);" onclick="goBack()">Back</a></span>
            </div> -->
            </div>

        </div>
        <!-- <form method="POST" action="{{url('admin/projectmanagement_search')}}" accept-charset="UTF-8"> -->
        <div class="row">
            <div class="form-group col-lg-2 col-md-6">
                <select class="form-control" id="country" name="country">
                    <option value="0">Select Country</option>
                    @foreach($country as $c)
                    <option <?php if (isset($_GET['country']) && $_GET['country'] == $c->id) echo 'selected'; ?> value="{{$c->id}}">{{$c->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-6">
                <select class="form-control" id="p-type" name="p_type">
                    <option value="0">Type of Project</option>
                    @foreach($type as $t)
                    <option <?php if (isset($_GET['p_type']) && $_GET['p_type'] == $t->id) echo 'selected'; ?> value="{{$t->id}}">{{$t->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-6">
                <select class="form-control" id="segment" name="segment">
                    <option value="0">Type of Segment</option>
                    @foreach($segment as $s)
                    <option <?php if (isset($_GET['segment']) && $_GET['segment'] == $s->id) echo 'selected'; ?> value="{{$s->id}}">{{$s->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-6">
                <select class="form-control" id="application" name="application">
                    <option value="0">Type of Application</option>
                    @foreach($application as $a)
                    <option <?php if (isset($_GET['application']) && $_GET['application'] == $a->id) echo 'selected'; ?> value="{{$a->id}}">{{$a->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-6">
                <select class="form-control" id="subapplication" name="subapplication">
                    <option value="0">Type of Sub Application</option>
                    @foreach($sa as $sa)
                    <option <?php if (isset($_GET['subapplication']) && $_GET['subapplication'] == $sa->id) echo 'selected'; ?> value="{{$sa->id}}">{{$sa->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-lg-2 col-md-6">
                <button type="button" class="btn btn-primary" onclick="page_reload();" title="Search records">Search</button>
                <button type="button" class="btn btn-primary" onclick="goBack()" title="Clear search filters">Clear</button>
            </div>
        </div>
        <!-- </form> -->
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <!-- <th>Id</th> -->
                                <th>Project Name</th>
                                <th>Country</th>
                                <th>Type of Project</th>
                                <th>Type of Segment</th>
                                <th>Type of Application</th>
                                <th>Type of Sub Application</th>
                                <th style="text-align:center;width:160px;">Actions</th>

                                <!-- <th>Edit</th>
                                <th>View</th>
                                <th>Delete</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $da)

                            <tr>
                                <!-- <td>{{ $da->id }}</td> -->
                                <td>{{ $da->project_name }}</td>
                                @if(empty($da->country->name))
                                <td></td>
                                @else
                                <td>{{ $da->country->name }}</td>
                                @endif

                                @if(empty($da->projectType->name))
                                <td></td>
                                @else
                                <td>{{ $da->projectType->name }}</td>
                                @endif

                                @if(empty($da->projectSegment->name))
                                <td></td>
                                @else
                                <td>{{ $da->projectSegment->name }}</td>
                                @endif

                                <td>{{ $da->application->name }}</td>

                                @if (!empty($da->subapplication->name))
                                <td>{{ $da->subapplication->name }}</td>
                                @else
                                <td></td>
                                @endif

                                <td style="text-align:center;"><a href="{{ route('admin.projectmanagement.edit',$da) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fas fa-edit"></i></a>
                                    <!-- </td>
                                <td> -->
                                    <a href="{{ route('admin.projectmanagement.show',$da) }}" class="btn btn-info" data-toggle="tooltip" data-placement="top" data-original-title="View"><i class="fas fa-eye"></i></a>
                                    <!-- </td>
                                <td> -->
                                    <a href="{{ route('admin.projectmanagement.destroy',$da) }}" data-method="delete" data-trans-button-cancel="{{ __('buttons.general.cancel') }}" data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}" data-trans-title="{{ __('strings.backend.general.are_you_sure') }}" class="btn btn-danger"><i class="fa fa-trash" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.delete') }}"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {{-- {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }} --}}
                </div>
            </div>
            <!--col-->

            <div class="col-5">
                <div class="float-right">
                    {{-- {!! $users->render() !!} --}}
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->
        {{$data->links()}}
    </div>

</div>
<!--card-->
@endsection
<script src="{{asset('js/jquery.min.js')}}"></script>

<script>
    function page_reload() {

        var url = window.location.origin + window.location.pathname;
        var search = document.getElementById("search_request").value;
        // Filters
        var country_search = $('#country').val();
        var segment_search = $('#segment').val();
        var p_type_search = $('#p-type').val();
        var application_search = $('#application').val();
        var subapplication_search = $('#subapplication').val();

        // alert(search+">>"+country_search +">>"+segment_search+">>"+p_type_search+">>"+application_search+">>"+subapplication_search);
        // return false;

        if (url.indexOf('?') > -1) {
            url += '&page=1' + '&filter=' + search + '&country=' + country_search + '&segment=' + segment_search + '&p_type=' + p_type_search + '&application=' + application_search + '&subapplication=' + subapplication_search;
        } else {
            url += '?filter=' + search + '&country=' + country_search + '&segment=' + segment_search + '&p_type=' + p_type_search + '&application=' + application_search + '&subapplication=' + subapplication_search;
        }
        window.location.href = url;
    }
</script>

<script>
    function goBack() {
        //window.history.back();
        window.location.href = window.location.origin + window.location.pathname;
    }
</script>

<script>
    // function page_reload(){

    //    var search = document.getElementById("search_request").value;
    //    var url = window.location.href+'?filter='+search;    
    //    // if (url.indexOf('?') > -1){
    //    //    url += '&param='.$search
    //    // }
    //    //else{
    //    //    url += '?param='.$search
    //    // }
    //       // url += '?param='.$search
    //        window.location.href = url;
    //    }


    // function page_reload() {
    //     // alert("sigma"); 
    //     var search = document.getElementById("search_request").value;
    //     var url = window.location.href + '?filter=' + search;
    //     // if (url.indexOf('?') > -1){
    //     //    url += '&param='.$search
    //     // }
    //     //else{
    //     //    url += '?param='.$search
    //     // }
    //     // url += '?param='.$search
    //     window.location.href = url;
    // }

    function GetSelectedTextValue(value) {
        if (!isNaN(value)) {
            var url = '{{ url('
            admin / ajaxsubapplication ') }}' + '/' + value;
            // 
            console.log(url);

            $("#subapplication").empty();
            $('#subapplication').append('<option value="" id="sub">Type of Sub Application</option>');
            $.get(url, function(data) {
                if (data.length > 0) {

                    $.each(data, function(key, value) {

                        $("#subapplication").append('<option value=' + value.id + '>' + value.name + '</option>');

                    });
                    // $("#subapplication").val('yourvalue').value("chosen:updated");
                } else {
                    $("#subapplication").append('<option selected value="0"> No Sub Application</option>');

                }
            });
        }
    }

    function clear_filter() {
        $("#country").append('<option selected value="0"> No Sub Application</option>');
        $('#segment').val('Select Project Segment');
        $('#p-type').val('Select Project Type');
        $('#application').val('Select Application Type');
        $('#subapplication').val('Select Sub Application');
    }

    // var e = document.getElementById("application");
    // var strUser = e.options[e.selectedIndex].value;
    // console.log(strUser);
</script>
<style>
    .new_search_wrap {}

    .new_search_wrap .form-control {
        width: 100%;
    }

    .new_search_div {
        position: relative;
    }

    .new_search_div button[type="button"] {
        position: absolute;
        right: 0;
        bottom: 0;
    }

    .select_user_type {}

    .select_user_type>div:first-child {
        width: 65%;
    }

    .select_user_type .form-group {
        display: inline-block;
        vertical-align: bottom;
    }
</style>