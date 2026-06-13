@extends('backend.layouts.app')

@section('title', 'Product Management | View Product')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('Product')
                    <small class="text-muted">@lang('View Product')</small>
                </h4>
            </div>
            <!--col-->
        </div>
        <!--row-->


        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        {{-- <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                                        <td><img src="{{ $user->picture }}" class="user-profile-image" /></td>
                        </tr> --}}
                        <tr>
                            <!-- <th width="200">@lang('Used in Projects')</th>
                                        <td> -->
                            <?php
                            // $string = "";
                            // for($i =0 ; $i<count($data->project_names) ; $i++)
                            // {
                            //     $count = $i+1;
                            //     $string .= ucwords($count." ".$data->project_names[$i])."<br>";
                            // }
                            //  echo($string);
                            //  die;


                            //    echo("<strong>".$string."</strong>");
                            ?>
                            <!-- </td> -->
                        </tr>

                        <tr>
                            <th>@lang('Product Name')</th>
                            <td>{{ $data->name }}</td>
                        </tr>

                        <tr>
                            <th>@lang('Description')</th>
                            <td>{!! isset($data->description) ? $data->description : '' !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Max. Head')</th>
                            <td>{!! isset($data->max_head) ? $data->max_head : '' !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('Max. Flow')</th>
                            <td>{!! isset($data->max_flow) ? $data->max_flow : '' !!}</td>
                        </tr>
                        <!-- <tr>
                                            <th>@lang('Technical Specification')</th>
                                            @if($data->specification_document)
                                            <td><a href="{{url('product/document/'.$data->specification_document)}}">Technical specification document</a></td>
                                            @endif
                                    </tr> -->
                        <!-- <tr>
                                            <th>@lang('Video')</th>
                                            @if($data->video)
                                            <td><video  width="320" height="185" controls> <source src="{{url('product/video/'.$data->video)}}"></video></td>
                                            @endif
                                    </tr> -->
                        <tr>
                            <th>@lang('Technical Specification URL')</th>
                            <td>{!! isset($data->technical_spec_url) ? $data->technical_spec_url : '' !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('Video URL')</th>
                            <td>{!! isset($data->video_url) ? $data->video_url : '' !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('Product Images')</th>
                            <td>@foreach($data->pro_picture as $p_pic)
                                <span><a data-lightbox="lb-1" href="{{url('product/product_pictures/'.$p_pic)}}"><img src="{{url('product/product_pictures/'.$p_pic)}}" class="css-class" height="42"></a></span>
                                @endforeach</td>
                        </tr>
                        <!-- <tr>
                            <th>@lang('Technical Images')</th>
                            @if($data->tech_picture)
                            <td>@foreach($data->tech_picture as $t_pic)
                                <span><a data-lightbox="lb-2" href="{{url('product/technical_pictures/'.$t_pic)}}"><img src="{{url('product/technical_pictures/'.$t_pic)}}" class="css-class" height="42"></a></span>
                                @endforeach</td>
                            @endif
                        </tr> -->
                    </table>
                </div>
            </div>
            <!--col-->
        </div>
        <!--row-->

    </div>
    <!--card-body-->

    <div class="card-footer clearfix">
        <div class="row">


        </div>
        <!--row-->
    </div>
    <!--card-footer-->
</div>
<!--card-->
<!--model-->


@endsection