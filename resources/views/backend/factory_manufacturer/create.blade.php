@extends('backend.layouts.app')

@section('title', 'Factory Manufacturer | Add New Manufacturer')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('POST', route('admin.factory_manufacturer.store'))->class('form-horizontal')->acceptsFiles()->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Factory Manufacturer
                    <small class="text-muted">Add New Manufacturer</small>
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
                        {{ html()->label(__('Factory Manufacturer'))->class('form-control-label')->for('name') }}
                        <div class="form-group">
                            {{ html()->text('name')
                                ->class('form-control')
                                ->placeholder(__('Factory Manufacturer'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div>
                    </div><!-- col-md-6 end -->
                </div><!-- row end -->

                <div class="row">
                    <div class="col-7">
                        <div class="float-left">
                            {{-- Optional pagination info --}}
                        </div>
                    </div>
                    <!--col-->

                    <div class="col-5">
                        <div class="float-right">
                            {{-- Optional pagination links --}}
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
                <span class="hvr_br">{{ form_cancel(route('admin.factory_manufacturer.index'), __('buttons.general.cancel')) }}</span>
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
