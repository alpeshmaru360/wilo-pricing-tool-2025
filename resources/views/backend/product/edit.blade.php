{{--dd($data)--}}

<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
@extends('backend.layouts.app')

@section('title', 'Product Management | Edit Product')


@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('PUT', route('admin.products.update',$data[0]->id))->class('form-horizontal')->acceptsFiles()->open()  }}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Product Management
                    <small class="text-muted">Edit Product</small>
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">

            </div>
            <!--col-->
        </div>
        <!--row-->
        <hr>
        <div class="row mt-4" id="edit_product_page">
            <div class="col">
                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label(__('Product Name *'))->class('form-control-label')->for('product_name') }}
                        <div class="form-group">
                            {{ html()->text('product_name')
            ->class('form-control')
            ->placeholder(__('product name'))
            ->value($data[0]->name)
            ->attribute('maxlength', 191)
            ->autofocus() }}
                        </div>
                        {{ html()->label(__('Description'))->class('form-control-label')->for('description') }}
                        <div class="form-group">
                            {{ html()->textarea('description')
            ->class('form-control')
            ->placeholder(__('description'))
            ->value($data[0]->description)
            ->attribute('rows', 5)
            ->autofocus() }}
                        </div>
                        <label>Pumps Capacity</label>
                        <div class="row">
                            <div class="col-md-6">
                                {{ html()->label(__('Max. Head'))->class('form-control-label')->for('product_name') }}
                                <div class="form-group">
                                    {{ html()->text('max_head')
            ->class('form-control')
            ->placeholder(__('Max. Head'))
            ->value($data[0]->max_head)
            ->attribute('maxlength', 191)
            ->autofocus() }}

                                    H (m)
                                </div>
                            </div>
                            <div class="col-md-6">

                                {{ html()->label(__('Max. Flow'))->class('form-control-label')->for('product_name') }}
                                <div class="form-group">
                                    {{ html()->text('max_flow')
            ->class('form-control')
            ->placeholder(__('Max. Flow'))
            ->value($data[0]->max_flow)
            ->attribute('maxlength', 191)
            ->autofocus() }}
                                    Q(m<sup>3</sup>/h)
                                </div>
                            </div>
                        </div>
                        {{--{{ html()->label(__('Quantity'))->class('form-control-label')->for('quantity') }}--}}
                        <div class="form-group">
                            {{--{{ html()->text('qunatity')
            ->class('form-control')
            ->placeholder(__('quantity'))
            ->value($data[0]->quantity)
            ->attribute('maxlength', 191)
            ->autofocus() }}--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{--{{ html()->label(__('Projects'))->class('form-control-label')->for('project_name') }}--}}
                        <!-- <div class="form-group">
                            <select style="height: 116px;" multiple class="col-md-12 form-control form-control-label" name="project_name[]"> -->
                        <!-- @foreach($project_name as $name)
                                <option <?php echo (in_array($name->id, $data[0]['selected_val']) ? 'selected' : ''); ?> value="{{$name->id}}">{{$name->project_name}}</option>
                        @endforeach -->
                        <!-- </select> -->
                        {{ html()->label(__('Technical Specification URL'))->class('form-control-label')->for('product_name') }}
                        <div class="form-group">
                            {{-- html()->text('technical_spec_url')
                ->class('form-control')
                ->placeholder(__('Technical Specification URL'))
                ->value($data[0]->technical_spec_url)
                ->attribute('maxlength', 191)
                ->autofocus() --}}
                            <input class="form-control" type="url" placeholder='Technical Specification URL' name="technical_spec_url" value="{{$data[0]->technical_spec_url}}" autofocus />
                        </div>
                        {{ html()->label(__('Video URL <span class="text-danger" style="font-size:10px">Note:Enter YouTube\'s URL only.</span>'))->class('form-control-label')->for('product_name') }}
                        <div class="form-group">
                            <!-- {{ html()->text('video_url')
                ->class('form-control')
                ->placeholder(__('Video URL'))
                ->value($data[0]->video_url)
                ->attribute('maxlength', 191)
                ->autofocus() }} -->
                            <input class="form-control" type="url" placeholder='Video URL' name="video_url" value="{{$data[0]->video_url}}" autofocus />
                        </div>
                        <!-- <div class="col-md-12"> -->
                        {{ html()->label(__('Product Pictures'))->class('form-control-label input-group control-group increment')->for('p_pictures') }}
                        <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span>
                        <span style="font-size:10px" class="text-danger"> (Images to be uploaded should not be greater than 5MB).</span>
                            {{-- {{ html()->file('p_pictures[]')
                                        
                    ->attribute('maxlength', 191)
                    ->required()
                     }}--}}
                        <?php 
                            $count = 1;
                            $totalCount = isset($data[0]->product_media) ? count($data[0]->product_media) : 0; 
                            
                        ?>
                     <input type="file" name="p_pictures[]" multiple="true">
                            <span class="thumbnail">

                                @foreach($data[0]->product_media as $tech_images)
                                <div class="result_multi" >                                
                                @if(!empty($tech_images))
                                    <button type="button" class="delete" id="btn_x<?php echo $count; ?>" onclick="deleteImage('wilo_product', <?php echo $data[0]->id ?>,'', '<?php echo $tech_images; ?>', <?php echo $totalCount;?>,<?php echo $count; ?>);">X</button>
                                @endif


                                <a id = "x<?php echo $count;?>" data-lightbox="lb-1" class="delete" href="{{url('product/product_pictures/'.$tech_images)}}">
                                    <img  src="{{url('product/product_pictures/'.$tech_images)}}" class="css-class" height="42">
                                </a>
                                </div>
                                <?php $count++ ?>
                                @endforeach

                            </span>

                    </div>
                        <!-- </div> -->
                    </div>


                    <!-- <div class="col-md-3"> -->
                    {{--{{ html()->label(__('Technical specification'))->class('form-control-label')->for('document') }}--}}
                    <!-- <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span> -->
                    {{--{{ html()->file('document')
    ->attribute('maxlength', 191)
    ->required()
     }}--}}
                    <!-- <input type="file" name="document">
                    </div> -->
                    <!-- </div> -->
                    <!-- <div class="col-md-3"> -->
                    {{--{{ html()->label(__('Video'))->class('form-control-label')->for('video') }}--}}
                    <!-- <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span> -->
                    {{--{{ html()->file('video')
           
            ->attribute('maxlength', 191)
            ->required()
             }}--}}
                    <!-- <input type="file" name="video">
                </div>
            </div> -->
                    <!-- <div class="col-md-12"> -->
                    {{--{{ html()->label(__('Technical Pictures'))->class('form-control-label input-group control-group increment')->for('t_pictures') }}--}}
                    <!-- <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span> -->
                    {{--{{ html()->file('t_pictures[]')
    
    ->attribute('maxlength', 191)
    ->required()
     }}--}}
                    <!-- <input type="file" name="t_pictures[]" multiple="true">
                    @if($data[0]->technical_media != null)
                    <span class="thumbnail">@foreach($data[0]->technical_media as $tech_images)
                        <a data-lightbox="lb-1" href="{{url('product/technical_pictures/'.$tech_images)}}"><img src="{{url('product/technical_pictures/'.$tech_images)}}" class="css-class" height="42"></a>
                        @endforeach
                        @endif
                        <p><i class="fa fa-warning"></i> Don't upload any data if you don't want to edit Technical images.</p>
                    </span>
                </div>
            </div> -->
                    <!-- col-md-6 end -->

                </div><!-- row end -->

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
            <!--col-->
        </div>
        <!--row-->
    </div>
    <!--card-body-->

    <div class="card-footer clearfix">
        <div class="row">
            <div class="col">
                <span class="hvr_br"> {{ form_cancel(route('admin.products.index'), __('buttons.general.cancel')) }}</span>
            </div>
            <!--col-->

            <div class="col text-right">
                <span class="hvr_br">{{ form_submit(__('buttons.general.crud.update')) }}</span>
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