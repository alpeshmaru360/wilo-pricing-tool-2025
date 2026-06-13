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
                    Products
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">

                @include('backend.product.includes.header-buttons')
            </div>
            <!--col-->
        </div>
        <!--row-->
        <hr>
        <div class="new_search_wrap">
        
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-6">
                    <div class="form-group new_search_div">
                        <label for="sel1">Search:</label>
                        <input type="text" class="form-control" name="search_request" id="search_request" placeholder="Search Product" value="<?php if(isset($_GET['filter']) && $_GET['filter'] != "") echo $_GET['filter']; ?>">
                        <button type="button" class="btn btn-primary" onclick="page_reload()">Search</button>
                    </div>
                    
                </div>
                    @if(isset($_GET['filter']))
                        <div class="col-md-6 col-lg-6 p-0">
                            <a style="margin-top: 12px;" class="btn btn-danger" href="JavaScript:Void(0);" onclick="goBack()">Clear</a>
                        </div>
                        @endif
                {{--<div class="col-md-6 col-lg-6">
                    <a href="{{$product_list_url}}">Back</a>
            </div>--}}
            
        </div>
    </div>


    <div class="row mt-4">
        <div class="col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <!-- <th>Image</th> -->
                            <th>Product Name</th>
                            <th>Max. Head</th>
                            <th>Max. Flow</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($data)) {
                            ?>

                            <?php $a = 1 ?>
                            @foreach($data as $da)
                            <tr>
                             <!-- <td style="width: 100px;">
                                    @if(isset($da->media[0]))
                                    <a data-lightbox="lb-<?php print $a ?>" href="{{url('product/product_pictures/'.$da->media[0])}}"><img src="{{url('product/product_pictures/'.$da->media[0])}}" class="css-class" height="42"></a>
                                    @endif
                                </td>  -->
                                <td><strong>{{ $da->name }}</strong>
                                </td>
                                <td>{{ $da->max_head }}</td>
                                <td>{{ $da->max_flow }}</td>
                                <td><a href="{{ route('admin.products.edit',$da) }}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" data-original-title="Edit"><i class="fas fa-edit"></i></a>
                                    <!-- </td>
                                    <td style="width: 100px;"> -->
                                    <a href="{{ route('admin.products.show',$da) }}" class="btn btn-info" data-toggle="tooltip" data-placement="top" data-original-title="View"><i class="fas fa-eye"></i></a>
                                    <!-- </td>
                                    <td style="width:5px;"> -->
                                    <a href="{{ route('admin.products.destroy',$da) }}" data-method="delete" data-trans-button-cancel="{{ __('buttons.general.cancel') }}" data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}" data-trans-title="{{ __('strings.backend.general.are_you_sure') }}" class="btn btn-danger"><i class="fa fa-trash" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.delete') }}"></i></a>
                                </td>
                            </tr>
                            <?php $a++ ?>
                            @endforeach
                        <?php } ?>
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
<!--card-body-->
</div>
<!--card-->
@endsection
<script>
    function page_reload() {
        var url = window.location.origin + window.location.pathname;
        var search = document.getElementById("search_request").value;
        if (url.indexOf('?') > -1){
           url += '&filter='+search+'&page=1';
        }
        else{
           url += '?filter='+search;
        }
        window.location.href = url;
    }
</script>

<script>
    function goBack() {
        window.history.back();
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