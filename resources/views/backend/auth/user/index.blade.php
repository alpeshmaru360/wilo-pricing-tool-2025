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
                    {{ __('labels.backend.access.users.management') }}
                    <!-- <small class="text-muted">{{ __('labels.backend.access.users.active') }}</small> -->
                </h4>
            </div>
            <!--col-->



            <div class="col-sm-7">
                @include('backend.auth.user.includes.header-buttons')
            </div>
            {{--@if(isset($user_list_url))
            <div class="col-md-6 col-lg-6">
                <a href="{{$user_list_url}}">Back</a>
        </div>
        @endif--}}
        <!-- @if(isset($_GET['filter']))
        
        <div class="text-right col">
            <span class="hvr_br"><a class="btn btn-danger" href="{{ url('admin/auth/user') }}">Back</a></span>
        </div>

        @endif -->
        <!--col-->
    </div>
    <hr>

    <!--row-->
    <div class="new_search_wrap">
        <div class="row align-items-center">

            <div class="col-md-6 col-lg-6">
                <div class="form-group new_search_div">
                    <label for="sel1">Search:</label>
                    <input type="text" class="form-control" value="<?php if (isset($_GET['filter']) && $_GET['filter'] != "") echo $_GET['filter']; ?>" name="search_request" id="search_request" placeholder="Search User with First Name, Last Name,  Email and Designation.">
                    <!-- <button type="button" class="btn btn-primary" onclick="page_reload()">Search</button> -->
                </div>
            </div>
            <!-- <div class="col-md-6 col-lg-2">
                    <div class="form-group">
                        <label for="sel1">Paginate:</label>
                        <select class="form-control" id="paginate" name="paginate">
                            <option>pages</option>
                            <option value='50'>50</option>
                            <option value='100'>100</option>
                            <option value='150'>150</option>
                            <option value='200'>200</option>
                        </select>
                    </div>
                </div> -->
            <div class="col-md-6 col-lg-2 select_user_type_wrap">
                <div class="form-group">
                    <label for="sel1">User Status:</label>
                    <select class="form-control" id="user_status" name="user_status">
                        <!-- <option>User Status</option> -->
                        <option value="-1">All</option>
                            <option <?php echo (isset($_GET['status']) && $_GET['status'] == 1 ? 'selected' : ''); ?> value='1'>Active</option>
                            <option <?php echo (isset($_GET['status']) && $_GET['status'] == 0 ? 'selected' : ''); ?> value='0'>Inactive</option>
                        <!-- <option value='0'>Deactive</option> -->
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 select_user_status">
                <div class="form-group">
                    <label for="sel1">User Type:</label>
                    <select class="form-control" id="user_type" name="user_type">
                        <option value="-1">All</option>
                        <!-- <option>User Type</option> -->
                        @foreach($user_types as $type)
                        <option <?php echo (isset($_GET['type']) && $_GET['type'] == $type->id ? 'selected' : ''); ?> value='{{$type->id}}'>{{$type->user_type_name}}</option>
                        @endforeach
                        <!-- <option value='2'>Sales Representative</option> -->
                    </select>
                </div>

                <div class="form-group user_mgm_aply_btn">
                    <!-- <button type="button" class="btn btn-primary" id="filter" onclick="filter()">Apply</button>
                        <button type="button" class="btn btn-primary" id="clear" onclick="clear_filter()">Clear</button> -->
                    <button type="button" class="btn btn-primary" id="filter" onclick="page_filter()">Search</button>
                    <button type="button" class="btn btn-danger" id="clear" onclick="clear_filter()">Clear</button>
                </div>
            </div>

        </div>
    </div><!-- end new search box -->
    <div id="result">
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('labels.backend.access.users.table.first_name')</th>
                                <th>@lang('labels.backend.access.users.table.last_name')</th>
                                <th>@lang('labels.backend.access.users.table.email')</th>
                                <th>@lang('labels.backend.access.users.table.designation')</th>
                                <th>@lang('labels.backend.access.users.table.user_type')</th>
                                <th>@lang('labels.backend.access.users.table.status')</th>
                                <!-- <th>@lang('labels.backend.access.users.table.roles')</th>
                                    <th>@lang('labels.backend.access.users.table.confirmed')</th>
                                    <th>@lang('labels.backend.access.users.table.other_permissions')</th>
                                    <th>@lang('labels.backend.access.users.table.social')</th>
                                    <th>@lang('labels.backend.access.users.table.last_updated')</th> -->
                                <th style="text-align:center;width:155px;">@lang('labels.general.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // echo "<pre>";
                            // print_R($users);
                            // echo "</pre>";
                            ?>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->designation }}</td>
                                <td>{{ $user->user_type == 1 ? 'Admin' : 'Sales Representative' }}</td>
                                <td>{!! $user->status_label !!}</td>
                                <!-- <td>{!! $user->roles_label !!}</td>
                                    <td>{!! $user->confirmed_label !!}</td>
                                    <td>{!! $user->permissions_label !!}</td>
                                    <td>{!! $user->social_buttons !!}</td>
                                    <td>{{ $user->updated_at->diffForHumans() }}</td> -->
                                {!! $user->action_buttons !!}
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
                    {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}
                </div>
            </div>
            <!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $users->render() !!}
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->
    </div>
</div>
<!--card-body-->
</div>
<!--card-->
@endsection
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script>
    // $(document).ready(function() {
    //     $("#filter").click(function() {
    //         var status = $("#user_status").val();
    //         var type = $("#user_type").val();
    //         // alert(type);
    //         $.ajax({
    //             url: "{{ url('admin/auth/user/search_result') }}", // Send the data with your url.
    //             type: "GET",
    //             data: {
    //                 status: status,
    //                 type: type
    //             }, // Here you have written as {GenderID: gender} , not {'GenderID': gender}
    //             success: function(data) {
    //                 $('#result').html(data);
    //                 console.log(data);
    //             }
    //         });
    //     });
    // });
</script>
<script>
    // function page_reload() {

    //     var search = document.getElementById("search_request").value;
    //     var url = window.location.href + '?filter=' + search;

    //     if (url.indexOf('?') > -1 || url.indexOf('&') > -1) {
    //         url = window.location.pathname + '?filter=' + search;;
    //     }
    //     // if (url.indexOf('?') > -1){
    //     //    url += '&param='.$search
    //     // }
    //     //else{
    //     //    url += '?param='.$search
    //     // }
    //     // url += '?param='.$search
    //     window.location.href = url;
    // }

    function page_filter() {

        var url = window.location.origin + window.location.pathname;
        // var search = document.getElementById("search_request").value;
        // Filters
        var status_search = $('#user_status').val();
        var type_search = $('#user_type').val();
        var search_request = $('#search_request').val();
        // alert(status_search + ' type: ' + type_search);
        // return false;
        // var p_type_search = $('#p-type').val();
        // var application_search = $('#application').val();
        // var subapplication_search = $('#subapplication').val();

        // alert(search+">>"+country_search +">>"+segment_search+">>"+p_type_search+">>"+application_search+">>"+subapplication_search);
        // return false;

        if (url.indexOf('?') > -1) {
            url += '&filter=' + search_request + '&page=1' + '&status=' + status_search + '&type=' + type_search;
            // alert(url);
            // return false;
        } else {
            url += '?filter=' + search_request + '&status=' + status_search + '&type=' + type_search;
            // alert(url);
            // return false;
        }
        window.location.href = url;
    }

    // function filter() {
    // alert('filter');
    // var paginate = document.getElementById('paginate').value;
    // var type = document.getElementById('user_type').value;
    // var status = document.getElementById('user_status').value;

    // if (!isNaN(type) && !isNaN(status)) {
    //     var url = window.location.href;
    //     if (url.indexOf('?') > -1) {
    //         url += '&status=' + status + '&type=' + type;
    //     } else {
    //         url += '?status=' + status + '&type=' + type;
    //     }
    //     window.location.href = url;
    // }
    // else
    // if (!isNaN(type)) {
    //     var url = window.location.href;
    //     if (url.indexOf('?') > -1) {
    //         url += '&type=' + type;
    //     } else {
    //         url += '?type=' + type;
    //     }
    //     window.location.href = url;
    // } else if (!isNaN(status)) {
    //     var url = window.location.href;
    //     if (url.indexOf('?') > -1) {
    //         url += '&status=' + status;
    //     } else {
    //         url += '?status=' + status;
    //     }
    //     window.location.href = url;
    // }
    // clear_filter();
    // else if (!isNaN(paginate)) {
    //     var url = window.location.href;
    //     if (url.indexOf('?') > -1) {
    //         url += '&paginate=' + paginate;
    //     } else {
    //         url += '?paginate=' + paginate;
    //     }
    //     window.location.href = url;
    // }
    // var url = window.location.href;
    // if(url.indexOf('?') > -1)
    // {
    //     url+='&pagin='+pagiante+'&type='+type;
    // }
    // else
    // {
    //     url+='?pagin='+pagiante+'&type='+type;
    // }
    // // var url = window.location.href+'?pagin='+pagiante+'&type='+type;
    // window.location.href = url;
    // }

    function clear_filter() {
        var url = window.location.href;
        if (url.indexOf('?') > -1 || url.indexOf('&') > -1) {
            window.location.href = window.location.pathname;
        }
        $('#paginate').val('pages');
        $('#user_type').val('');
        $('#user_status').val('');
    }
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