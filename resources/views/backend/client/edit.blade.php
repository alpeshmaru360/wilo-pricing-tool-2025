@extends('backend.layouts.app')

@section('title', 'Clients | Edit Client')

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('PATCH', route('admin.client.update',$data->id))->class('form-horizontal')->acceptsFiles()->open()  }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Clients
                    <small class="text-muted">Edit Client</small>
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
                        {{ html()->label(__('Client Name'))->class('form-control-label')->for('name') }}
                        <div class="form-group">
                            {{ html()->text('name')
        ->class('form-control')
        ->placeholder(__('Client Name'))
        ->value($data->name)
        ->attribute('maxlength', 191)
        ->required()
        ->autofocus() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-4">
                        {{ html()->label(__('Logo'))->class('form-control-label')->for('logo') }}
                        <div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload Client Image</span>
                            {{ html()->file('logo')
        
        ->autofocus() }}
                        </div>
                    </div><!-- col-md-4 end -->
                    <div class="col-md-2" id="imageDel">
                        <div id="result" style="color:green">
                            @if(!empty($data->logo))
                            {{--dd($data)--}}
                            
        
                                <button type="button" onclick="deleteImage('stake_holders', <?php echo $data->id ?>, 1)">X</button>
                                <!-- Current Logo <br> -->
                                <a data-lightbox="lb-1>" href="{{url('logo/client_logo/'.$data->logo)}}">
                                    <img src="{{url('logo/client_logo/'.$data->logo)}}" class="css-class" height="42">
                                </a>
                            </div>
                            @endif <!-- col-md-2 end -->
                    </div>
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
                <span class="hvr_br">{{ form_cancel(route('admin.client.index'), __('buttons.general.cancel')) }}</span>
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