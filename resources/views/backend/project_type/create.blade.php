@extends('backend.layouts.app')

@section('title', 'Type of Project | Add New Project')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('POST', route('admin.typeproject.store'))->class('form-horizontal')->acceptsFiles()->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Type of Project
                    <small class="text-muted">Add New Project</small>
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">

            </div>
            <!--col-->
        </div>
        <!--row-->
        <hr>
        <div class="row mt-4">
            <div class="col">
                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label(__('Type of Project'))->class('form-control-label')->for('name') }}
                        <div class="form-group">
                            {{ html()->text('name')
            ->class('form-control')
            ->placeholder(__('Type of Project'))
            ->attribute('maxlength', 191)
            ->required()
            ->autofocus() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('Logo'))->class('form-control-label')->for('logo') }}
                        <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload Project Image</span>
                            {{ html()->file('logo')
        ->attribute('maxlength', 191)
        ->autofocus() 
        ->required()}}
                        </div>
                    </div><!-- col-md-6 end -->
                </div><!-- row end -->
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

    <div class="card-footer clearfix">
        <div class="row">
            <div class="col">
                <span class="hvr_br">{{ form_cancel(route('admin.projectsegment.index'), __('buttons.general.cancel')) }}</span>
            </div>
            <!--col-->

            <div class="col text-right">
                <span class="hvr_br">{{ form_submit(__('buttons.general.crud.add')) }}</span>
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