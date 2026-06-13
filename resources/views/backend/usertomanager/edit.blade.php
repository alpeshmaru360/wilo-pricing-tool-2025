
@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('PATCH', route('admin.usertomanager.update',$data3[0]->id))->class('form-horizontal')->acceptsFiles()->open() }}

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                   User Relationships
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                
            </div><!--col-->
        </div><!--row-->

                   
                <div class="row mt-4 mb-4">
                    <div class="col">
                    <div class="form-group row">
                            
                            {{ html()->label(__('Manager'))->class('col-md-2 form-control-label')->for('parent') }}

                            
                            <div class="col-md-10">
                            <select name = "manager" class="form-control">
                                <option value='0'>Please Select Manager </option>
                            @foreach($data as $d)
                            
                            {{-- <option value="{{$d->id}}">{{$d->first_name}}</option> --}}
                            <option <?php echo ( $data3[0]->id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->first_name}}</option>
                            @endforeach
                            </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            
                                {{ html()->label(__('Users'))->class('col-md-2 form-control-label')->for('User') }}
            
                                
                                <div class="col-md-10">
                                    


                                    {{-- {{dd($data2)}} --}}
                                    @foreach($data2 as $d1)
                                    
                                       <input type="checkbox" id="check_id" name="users[]":checked value="{{$d1->id}}" <?php echo $d1->check?'Checked' : '' ?> > {{$d1->first_name}}
                                       
                                    @endforeach

                                    
                                    </div>
                            </div>   
          
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
<div class="card-footer clearfix">
                <div class="row">
                    <div class="col">
                        {{-- {{ form_cancel(route('admin.application.index'), __('buttons.general.cancel')) }} --}}
                    </div><!--col-->

                    <div class="col text-right">
                        {{ form_submit(__('buttons.general.crud.update')) }}
                    </div><!--col-->
                </div><!--row-->
            </div><!--card-footer-->
        </div><!--card-->
    {{ html()->form()->close() }}
@endsection
