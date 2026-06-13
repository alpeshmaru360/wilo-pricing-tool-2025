@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('POST', route('admin.consultant.store'))->class('form-horizontal')->acceptsFiles()->open()  }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Consultants
                    <small class="text-muted">Add New Consultant</small>
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
{{ html()->label(__('Consultant Name'))->class('form-control-label')->for('name') }}
    <div class="form-group">
        {{ html()->text('name')
        ->class('form-control')
        ->placeholder(__('Consultant Name'))
        ->attribute('maxlength', 191)
        ->required()
        ->autofocus() }}
    </div>
</div>
<div class="col-md-6">
{{ html()->label(__('Logo'))->class('form-control-label')->for('logo') }}
<div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload Consultant Image</span>
            {{
            html()->file('logo')
            ->autofocus()
             
        }}

    </div>
</div>
</div>
                <div class="table-responsive" style="display:none;"> <!-- style by shahrukh -->

                    <div class="row mt-4 mb-4">
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
    
    </div></div></div><!-- 3 div added by shahrukh -->

                <div class="card-footer clearfix">
                    <div class="row">
                        <div class="col">
                            {{ form_cancel(route('admin.consultant.index'), __('buttons.general.cancel')) }}
                        </div>
                        <!--col-->

                        <div class="col text-right">
                            {{ form_submit(__('buttons.general.crud.add')) }}
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