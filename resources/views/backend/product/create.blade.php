@extends('backend.layouts.app')

@section('title', 'Product Management | Add New Product')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('POST', route('admin.products.store'))->class('form-horizontal')->acceptsFiles()->open()  }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Product Management
                    <small class="text-muted">Add New Product</small>
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">
                <!-- <span class="hvr_br">{{ form_cancel(route('admin.products.index'), __('buttons.general.back'))->class('back_btn') }}</span> -->                
            </div>
            <!--col-->
        </div>
        <!--row-->
        <hr>

        <div class="row mt-4">
            <div class="col">
                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label(__('Product Name *'))->class('form-control-label')->for('product_name') }}
                        <div class="form-group">
                            {{ html()->text('product_name')
                ->class('form-control')
                ->placeholder(__('Product Name'))
                ->attribute('maxlength', 191)
                ->autofocus() }}
                        </div>
                        {{ html()->label(__('Description'))->class('form-control-label')->for('description') }}
                        <div class="form-group">
                            {{ html()->textarea('description')
		    ->class('form-control')
		    ->placeholder(__('Description'))
		    ->attribute('rows', 5)
		    ->autofocus() }}
                        </div>
                        <label>Pumps Capacity</label>
                        <div class="row">
                        <div class="col-md-6">
                        {{ html()->label(__('Max. Head'))->class('form-control-label')->for('Max. Head') }}
                        <div class="form-group">
                            {{ html()->text('max_head')
		    ->class('form-control')
		    ->placeholder(__('Max. Head'))
		    ->attribute('maxlength', 191)
		    ->autofocus() }}
                            H (m)
                        </div>
                        </div>
                        <div class="col-md-6">
                        {{ html()->label(__('Max. Flow'))->class('form-control-label')->for('Max. Flow') }}
                        <div class="form-group">
                            {{ html()->text('max_flow')
		    ->class('form-control')
		    ->placeholder(__('Max. Flow'))
		    ->attribute('maxlength', 191)
		    ->autofocus() }}

                            Q(m<sup>3</sup>/h)
                        </div>
                        </div>
                    </div>
                            </div>
                    {{-- <div class="col-md-6">
                        {{ html()->label(__('Projects'))->class('form-control-label')->for('project_name') }}
                    <div class="form-group">
                        <select style="height: 116px;" multiple class="col-md-12 form-control form-control-label" name="project_name[]" required>
                            @foreach($data as $d)
                            <option value="{{$d->id}}">{{$d->project_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>--}}


                <!-- <div class="col-md-3"> -->
                {{--{{ html()->label(__('Technical specification'))->class('form-control-label')->for('document') }}--}}
                <!-- <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span> -->
                {{--{{ html()->file('document')
        ->attribute('maxlength', 191)
        ->required()
         }}--}}
                <!-- <input type="file" name="document">
                    </div>
                </div> -->
                <div class="col-md-6">
                    {{ html()->label(__('Technical Specification URL'))->class('form-control-label')->for('product_name') }}
                    <div class="form-group">
                        {{-- html()->text('technical_spec_url')
                ->class('form-control')
                ->placeholder(__('Technical Specification URL'))
                ->attribute('maxlength', 191)
                ->autofocus() --}}
                    <input type="url" class = "form-control" placeholder="Technical Specification URL" name="technical_spec_url">
                    </div>
                    {{ html()->label(__('Video URL <span class="text-danger" style="font-size:10px">Note:Enter YouTube\'s URL only.</span>'))->class('form-control-label')->for('product_name') }}
                    <div class="form-group">
                        {{-- html()->text('video_url')
                ->class('form-control')
                ->placeholder(__('Video URL'))
                ->attribute('maxlength', 191)
                ->autofocus() --}}
                            <input type="url" class="form-control" placeholder="Video URL" name="video_url">
                    </div>
                    {{--{{ html()->label(__('Video'))->class('form-control-label')->for('video') }}--}}
                    <!-- <div class="form-group cs_file_btn"> -->
                    <!-- <span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i>
                            Upload
                        </span> -->
                    {{--{{ html()->file('video')
		    ->attribute('maxlength', 191)
		     }}--}}
                    <!-- <input type="file" name="video"> -->
                    <!-- </div> -->
                    <!-- </div>
                <div class="col-md-6"> -->
                    {{ html()->label(__('Product Pictures'))->class('form-control-label input-group control-group increment')->for('p_pictures') }}
                    <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span>
                    <span style="font-size:10px" class="text-danger"> (Images to be uploaded should not be greater than 5MB).</span>
                        {{ html()->file('p_pictures[]')
            ->attribute('maxlength', 191)
            ->required()
             }}
                        {{-- <input type="file" name="p_pictures[]" multiple="true" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        {{ html()->label(__('Technical Pictures'))->class('form-control-label input-group control-group increment')->for('t_pictures') }}
                        <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload</span>--}}
                            {{--{{ html()->file('t_pictures[]')
                    
                    ->attribute('maxlength', 191)
                    ->required()
                     }}--}}
                            {{-- <input type="file" name="t_pictures[]" multiple="true">--}}
                        </div>
                    </div><!-- col-md-6 end -->

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
                <span class="hvr_br">{{ form_cancel(route('admin.products.index'), __('buttons.general.cancel')) }}</span>
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