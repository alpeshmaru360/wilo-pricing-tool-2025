@extends('backend.layouts.app')

@section('title', 'Type of Sub-Application | Edit Sub-Application')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('PATCH', route('admin.subapplication.update',$data->id))->class('form-horizontal')->acceptsFiles()->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Type of Sub-Application
                    <small class="text-muted">Edit Sub-Application</small>
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
                        {{ html()->label(__('Type of Application'))->class('form-control-label')->for('parent') }}
                        <div class="form-group">
                            <select name="parent" class="form-control">
                                @foreach($drop as $d)
                                <option <?php echo ($data->root_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}">{{$d->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('Type of Sub-Application'))->class('form-control-label')->for('name') }}
                        <div class="form-group">
                            {{ html()->text('name')
        ->class('form-control')
        ->placeholder('Type of Sub-Application')
        ->attribute('maxlength', 191)
        ->value($data->name)
        ->required()
        ->autofocus() }}
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
                <span class="hvr_br">{{ form_cancel(route('admin.subapplication.index'), __('buttons.general.cancel')) }}</span>
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