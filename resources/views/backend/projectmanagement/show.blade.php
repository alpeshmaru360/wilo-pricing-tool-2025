
@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->form('PATCH', route('admin.projectmanagement.update',$data[0]->id))->class('form-horizontal')->acceptsFiles()->open() }}

<div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('Project Management')
                        <small class="text-muted">@lang('Show project')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4 mb-4">
                <div class="col">
                        <div class="table-responsive">
                                <table class="table table-hover">
                                    {{-- <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                                        <td><img src="{{ $user->picture }}" class="user-profile-image" /></td>
                                    </tr> --}}
                        
                                    <tr>
                                        <th>@lang('Project Name')</th>
                                        <td>{{ $data[0]->project_name }}</td>
                                        <th>@lang('Application Type')</th>
                                        <td>{{ $data[0]->application->name }}</td>
                                    </tr>
                        
                                    <tr>
                                        @if(!empty($data[0]->subapplication->name))
                                        <th>@lang('Type of Sub Application')</th>
                                        <td>{!! $data[0]->subapplication->name  !!}</td>
                                        @endif

                                        @if(!empty($data[0]->projectSegment->name))
                                        <th>@lang('Project Segment')</th>
                                        <td>{!! $data[0]->projectSegment->name !!}</td>
                                        @endif
                                    </tr>

                                    <tr>

                                        @if(!empty($data[0]->projectType->name))
                                            <th>@lang('Type of Project')</th>
                                            <td>{!! $data[0]->projectType->name!!}</td>
                                        @endif 

                                        @if(!empty($data[0]->project_brief))   
                                            <th>@lang('project Brief')</th>
                                            <td>{!! $data[0]->project_brief !!}</td>
                                        @endif    
                                    </tr>
                                    
                                    <tr>

                                        @if(!empty($data[0]->remarks))
                                            <th>@lang('Remarks')</th>
                                            <td>{!! $data[0]->remarks!!}</td>
                                        @endif 

                                        @if(!empty($data[0]->year_of_installation))   
                                            <th>@lang('Year Of Installation')</th>
                                            <td>{!! $data[0]->year_of_installation!!}</td>
                                        @endif    
                                    </tr> 
                                    
                                    <tr>

                                        @if(!empty($data[0]->country->name))
                                            <th>@lang('Country')</th>
                                            <td>{!! $data[0]->country->name !!}</td>
                                        @endif    

                                        @if(!empty($data[0]->city))
                                            <th>@lang('City')</th>
                                            <td>{{ $data[0]->city }}</td>
                                        @endif    
                                    </tr>
                                    
                                    <tr>
                                    @if(!empty($data[0]->stakeholdercontractor->name))
                                            <th>@lang('Client')</th>
                                             @if($data[0]->stakeholder->type == 1)
                                            <td>{{ $data[0]->stakeholder->name }}</td>
                                            <td colspan="2"><a data-lightbox="lb-1" href="{{url('logo/client_logo/'.$data[0]->stakeholder->logo)}}">
                                            <img src="{{url('logo/client_logo/'.$data[0]->stakeholder->logo)}}" height="64px" width="64px"></a>
                                               </td>
                                             @endif
                                    @endif         
                                    </tr>
                                    <tr>    

                                    @if(!empty($data[0]->stakeholdercontractor->name))
                                            <th>@lang('Contractor')</th>
                                             @if($data[0]->stakeholdercontractor->type == 3)
                                            <td>{{ $data[0]->stakeholdercontractor->name }}</td>
                                            <td colspan="2">
                                            <a data-lightbox="lb-2" href="{{url('logo/contractor_logo/'.$data[0]->stakeholdercontractor->logo)}}">
                                            <img src="{{url('logo/contractor_logo/'.$data[0]->stakeholdercontractor->logo)}}" height="64px" width="64px"></a>
                                               </td>
                                             @endif
                                    @endif         
                                    </tr>
                                    <tr>

                                    @if(!empty($data[0]->stakeholderconsultant->name))
                                            <th>@lang('Consultant')</th>
                                             @if($data[0]->stakeholderconsultant->type == 2)
                                            <td>{{ $data[0]->stakeholderconsultant->name }}</td>
                                            <td colspan="2">
                                            <a data-lightbox="lb-3" href="{{url('logo/consultant_logo/'.$data[0]->stakeholderconsultant->logo)}}">
                                            <img src="{{url('logo/consultant_logo/'.$data[0]->stakeholderconsultant->logo)}}" height="64px" width="64px"></a>
                                               </td>
                                             @endif
                                    @endif            
                                    </tr>
                                    <tr>
                                            <th>@lang('Project Image')</th>
                                            <td colspan="3">
                                            <a data-lightbox="lb-4" href="{{url('logo/project_details/main_image/'.$data[0]->project_image)}}">
                                            <img src="{{url('logo/project_details/main_image/'.$data[0]->project_image)}}" height="64px" width="64px"></a>
                                               </td>
                                             
                                    </tr>
                                    {{-- <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.confirmed')</th>
                                        <td>{!! $user->confirmed_label !!}</td>
                                    </tr> --}}
                        
                                    {{-- <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.timezone')</th>
                                        <td>{{ $user->timezone }}</td>
                                    </tr> --}}
                        {{-- 
                                    <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_at')</th>
                                        <td>
                                            @if($user->last_login_at)
                                                {{ timezone()->convertToLocal($user->last_login_at) }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                        
                                    <tr>
                                        <th>@lang('labels.backend.access.users.tabs.content.overview.last_login_ip')</th>
                                        <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
                                    </tr> --}}
                                </table>
                            </div>                
                </div><!--col-->
            </div><!--row-->
            {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> --}}
        </div><!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">
               

            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
<!--model-->
{{-- 
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Modal Header</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group row">
                            
                            {{ html()->label(__('Consultant'))->class('col-md-2 form-control-label')->for('consultant') }}
        
                            
                            <div class="col-md-10">
                            <select name = "consultant" class="form-control">
                                    <option>Select Consultant</option>
                                    @foreach($stakeholder as $d)
                                    
                                    @if($d->type == 2)
                                    <option value="{{$d->id}}">{{$d->name}}</option>
                                    @endif
                                    @endforeach
                            </select>
                            <span id="consultantimg"><img src="https://lh3.googleusercontent.com/-7HspFx-zfNU/AAAAAAAAAAI/AAAAAAAAAAA/ACHi3rc940r-rSqZbw7P-a0q19NtJtWOMQ/photo.jpg?sz=46" height="64px" width="64px"></span>
                            </div>
                        </div>   
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </div>
          
        </div>
      </div>
       --}}
    {{ html()->form()->close() }}
@endsection



<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">


function showuser()
{

    var url = '{{ url('admin/users') }}';
            //sconsole.log(url);
           
            $.get(url, function(data) {
                //console.log(data);
                $.each(data,function(key, value) {
                    console.log(value.first_name);
                    $("#show_users").append('<input type="checkbox" id="check_id" name="users[]":checked value="'+value.id+'" >'+value.full_name+' <br>');
                });
               
            });
}
 


 $(function() {
        $('select[name=apptype]').change(function() {

            var url = '{{ url('admin/ajaxsubapplication') }}' + "/"+$(this).val();
            console.log(url);
            $.get(url, function(data) {
                var select = $('form select[name= subapptype]');

                select.empty();

                $.each(data,function(key, value) {
                    select.append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            });
        });

// get client image
$('select[name=client]').change(function() {
   
var url = '{{ url('admin/ajaximage') }}' + "/"+$(this).val();
console.log(url);
$.get(url, function(data) {
        $('#clientimg').empty().append('<a data-lightbox="lb-1" href="'+data+'"><img src="'+data+'" height="64px" width="64px"></a>');
   
    
    // var select = $('form select[name= subapptype]');

    // select.empty();

    // $.each(data,function(key, value) {
    //     select.append('<option value=' + value.id + '>' + value.name + '</option>');
    // });
});
});
$('select[name=contractor]').change(function() {
   
   var url = '{{ url('admin/ajaximage') }}' + "/"+$(this).val();
   console.log(url);
   $.get(url, function(data) {
           $('#contractorimg').empty().append('<a data-lightbox="lb-2" href="'+data+'"><img src="'+data+'" height="64px" width="64px"></a>');
      
       
       // var select = $('form select[name= subapptype]');
   
       // select.empty();
   
       // $.each(data,function(key, value) {
       //     select.append('<option value=' + value.id + '>' + value.name + '</option>');
       // });
   });
   });
   $('select[name=consultant]').change(function() {
   
   var url = '{{ url('admin/ajaximage') }}' + "/"+$(this).val();
   console.log(url);
   $.get(url, function(data) {
           $('#consultantimg').empty().append('<a data-lightbox="lb-3" href="'+data+'"><img src="'+data+'" height="64px" width="64px"></a>');
      
       
       // var select = $('form select[name= subapptype]');
   
       // select.empty();
   
       // $.each(data,function(key, value) {
       //     select.append('<option value=' + value.id + '>' + value.name + '</option>');
       // });
   });
   });

$('input[type="file"]').change(function(e) {
   
   //var url = '{{ url('admin/projectimage') }}' + "/"+$(this).path();
   var fileName =e.files['name'];

   console.log(fileName);
   $.get(url, function(data) {
           $('#consultantimg').empty().append('<a data-lightbox="lb-3" href="'+data+'"><img src="'+data+'" height="64px" width="64px"></a>');
      
       
       // var select = $('form select[name= subapptype]');
   
       // select.empty();
   
       // $.each(data,function(key, value) {
       //     select.append('<option value=' + value.id + '>' + value.name + '</option>');
       // });
   });
   });
    });
</script>