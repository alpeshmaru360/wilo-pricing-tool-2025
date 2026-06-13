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
                    Contractors
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">

                @include('backend.contractor.includes.header-buttons')
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
                        <input type="text" class="form-control" name="search_request" id="search_request" placeholder="Search Contractor">
                        <button type="button" class="btn btn-primary" onclick="page_reload()">Search</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <!-- <th>Id</th> -->
                                <th>Name</th>
                                <th>Logo</th>
                                <th>Actions</th>
                                <!-- <th>Created</th> -->
                                <!-- <th>Edit</th>
                                <th>Delete</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $a = 1 ?>
                            @foreach($data as $d)
                            <tr>
                                <!-- <td>{{ $d->id }}</td> -->
                                <td>{{ $d->name }}</td>

                                <td>
                                    <?php if($d->logo){ ?>
                                    <a data-lightbox="lb-<?php print $a ?>" href="{{url('logo/contractor_logo/'.$d->logo)}}"><img src="{{url('logo/contractor_logo/'.$d->logo)}}" alt="Contractor Logo" class="css-class" height="42"></a></td>
                                    <?php } ?>
                                <!-- <td>{{$d->created_at}}</td> -->
                                <td><a href="{{ route('admin.contractor.edit',$d) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fas fa-edit"></i></a></a>
                                    <!-- </td>
                                <td> -->
                                    <a href="{{ route('admin.contractor.destroy',$d) }}" data-method="delete" data-trans-button-cancel="{{ __('buttons.general.cancel') }}" data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}" data-trans-title="{{ __('strings.backend.general.are_you_sure') }}" class="btn btn-danger"><i class="fa fa-trash" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.delete') }}"></i></a>
                                </td>
                            </tr>

                            <?php $a++ ?>
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
    </div>
    <!--card-body-->
</div>
<!--card-->
@endsection
<script>
    function page_reload() {

        var search = document.getElementById("search_request").value;
        var url = window.location.href + '?filter=' + search;
        // if (url.indexOf('?') > -1){
        //    url += '&param='.$search
        // }
        //else{
        //    url += '?param='.$search
        // }
        // url += '?param='.$search
        window.location.href = url;
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