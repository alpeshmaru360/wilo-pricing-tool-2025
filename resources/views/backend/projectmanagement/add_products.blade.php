{{--dd($data[0])--}}
@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
@if($data[0]['on_boarding'] == "true")
{{ html()->form('POST', route('admin.on_boarding',$data[0]->id))->class('form-horizontal')->acceptsFiles()->open() }}
@elseif($data[0]['on_boarding'] == "false")
{{ html()->form('PATCH', route('admin.projectmanagement.update',$data[0]->id))->class('form-horizontal')->acceptsFiles()->open() }}
@endif
<div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('Project Management')
                        @if($data[0]['on_boarding'] == "true")
                        <small class="text-muted">@lang('Add Attributes')</small>
                        @elseif($data[0]['on_boarding'] == "false")
                        <small class="text-muted">@lang('Edit project')</small>
                        @endif
                    </h4>
                </div><!--col-->
                <div class="col-sm-7 text-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal1" onclick=show_products()><i class="fas fa-plus-circle"></i> Add Products</button>
                {{--<button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal" onclick=showuser()><i class="fas fa-plus-circle"></i> Add Sales Representatives</button>--}}

                </div>
            </div><!--row-->

            <hr>
            <input type="hidden"  name="project_id" id="project_id" value={{$data[0]->id}}>
            <div class="row mt-4 mb-4">
                <div class="col">
<div class="row">
    <div class="col-md-6">
    {{ html()->label(__('Project Name'))->class('form-control-label')->for('first_name') }}
    <div class="form-group">
        {{ html()->text('project_name')
            ->class('form-control')
            ->placeholder(__('Project Name'))
            ->attribute('maxlength', 191)
            ->required()
            ->value($data[0]->project_name)
            ->autofocus() }}
    </div><!--form-group-->
    </div><!-- col-md-6 end -->
    <div class="col-md-6">
    {{ html()->label(__('Application Type'))->class('form-control-label')->for('apptype') }}
    <div class="form-group">
    <select name = "apptype" class="form-control">
                            @foreach($get_application as $d)
                            {{-- <option></option> --}}
                            <option <?php echo ( $data[0]->application_type == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                            @endforeach
    
                            </select>
    </div><!--form-group-->
    </div><!-- col-md-6 end -->
    <div class="col-md-6">
    {{ html()->label(__('Type of Sub Application'))->class('form-control-label')->for('subapptype') }}
	<div class="form-group">
    <select name = "subapptype" class="form-control"  required>
                                @foreach($get_application as $d)
                                {{-- <option>Type of Sub Application</option> --}}
                                <option  <?php echo ( $data[0]->sub_application_type == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                                {{-- <option value="{{$d->id}}">{{$d->user_type_name}}</option> --}}
                                @endforeach
                                </select>
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
{{ html()->label(__('Project Segment'))->class('form-control-label')->for('project_segment') }}
	<div class="form-group">
    <select name = "project_segment" class="form-control">
                                @foreach($project_segment as $d)
                                {{-- <option>Project Segment</option> --}}
                                <option <?php echo ( $data[0]->project_segment == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                                
                                {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                                @endforeach
                                </select>
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
{{ html()->label(__('Type Of Project'))->class('form-control-label')->for('typeproject') }}
	<div class="form-group">
    <select name = "typeproject" class="form-control">
                                    @foreach($project_type as $d)
                                    {{-- <option>Type Of Project</option> --}}
                                <option <?php echo ( $data[0]->project_type == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                                    
                                    {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                                    @endforeach
                                    </select>
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
{{ html()->label(__('Project Brief'))->class('form-control-label')->for('bf') }}
	<div class="form-group">
    {{ html()->textarea('project_brief')
                                                    ->class('form-control')
                                                    ->placeholder(__('Project Name'))
                                                    ->attribute('maxlength', 300)
                                                    ->required()
                                                    ->value($data[0]->project_brief)
                                                    ->autofocus() }}
    </div><!--form-group-->
</div><!-- col-md-6 end -->
{{--
<div class="col-md-6">
{{ html()->label(__('Remarks'))->class('form-control-label')->for('remarks') }}
	<div class="form-group">
    {{ html()->textarea('remarks')
                                        ->class('form-control')
                                        ->placeholder(__('Remarks'))
                                        ->attribute('maxlength', 300)
                                        ->required()
                                        ->value($data[0]->remarks)
                                        ->autofocus() }}
    </div><!--form-group-->
</div><!-- col-md-6 end -->
--}}
<div class="col-md-6">
{{ html()->label(__('Year Of Installation'))->class('form-control-label')->for('bf') }}
	<div class="form-group">
    {{ html()->text('year')
                            ->class('form-control')
                            ->placeholder(__('Year Of Installation'))
                            ->attribute('maxlength', 190)
                            ->required()
                            ->value($data[0]->year_of_installation)
                            ->autofocus() }}
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
{{ html()->label(__('Country'))->class('form-control-label')->for('country') }}
	<div class="form-group">
    <select name = "country" class="form-control">
                    @foreach($country as $d)
                    {{-- <option>Country</option> --}}
                    <option <?php echo ( $data[0]->country_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>

                    {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                    @endforeach
                    </select>
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
{{ html()->label(__('City'))->class('form-control-label')->for('city') }}
	<div class="form-group">
    {{ html()->text('city')
                                ->class('form-control')
                                ->placeholder(__('City'))
                                ->attribute('maxlength', 190)
                                ->required()
                                ->value($data[0]->city)
                                ->autofocus() }}
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
<div class="img_left">
    <span id="clientimg"><img src="{{url('logo/client_logo/'.$data[0]->stakeholder->logo)}}" height="64px" width="64px"></span>
</div>    
<div class="field_content">
{{ html()->label(__('Client'))->class('form-control-label')->for('client') }}
<div class="form-group">
    <select name = "client" class="form-control">
                               
                               @foreach($stakeholder as $d)
                               {{-- <option>Client</option> --}}
                               @if($d->type == 1)
                           <option <?php echo ( $data[0]->client_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                               @endif
                               {{-- @if($d->type == 1) --}}
                               {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                               {{-- @endif --}}
                               @endforeach
                               </select>
    </div>                               
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
<div class="img_left">
<span id="contractorimg"><img src="{{url('logo/contractor_logo/'.$data[0]->stakeholdercontractor->logo)}}" height="64px" width="64px"></span>
</div>    
<div class="field_content">
{{ html()->label(__('Contractor'))->class('form-control-label')->for('contractor') }}
<div class="form-group">
<select name = "contractor" class="form-control">
                                   
                                    @foreach($stakeholder as $d)
                                    {{-- <option>Client</option> --}}
                                    @if($d->type == 3)
                    <option <?php echo ( $data[0]->contractor_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                                   @endif
                                    {{-- @if($d->type == 3) --}}
                                    {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                                    {{-- @endif --}}
                                    @endforeach
                            </select>
    </div>                               
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
<div class="img_left">
<span id="consultantimg"><img src="{{url('logo/consultant_logo/'.$data[0]->stakeholderconsultant->logo)}}" height="64px" width="64px"></span>
</div>    
<div class="field_content">
{{ html()->label(__('Consultant'))->class('form-control-label')->for('consultant') }}
<div class="form-group">
<select name = "consultant" class="form-control">
                                        {{-- <option>Select Consultant</option> --}}
                                        @foreach($stakeholder as $d)
                                        @if($d->type == 2)
                    <option <?php echo ( $data[0]->consultant_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}" >{{$d->name}}</option>
                                        @endif
                                        {{-- @if($d->type == 2) --}}
                                        {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                                        {{-- @endif --}}
                                        @endforeach
                                </select>
    </div>                               
    </div><!--form-group-->
</div><!-- col-md-6 end -->
<div class="col-md-6">
<div class="img_left">
<span id="projectimage"><img src="{{url('logo/project_details/main_image/'.$data[0]->project_image)}}" height="64px" width="64px"></span>
</div>    
<div class="field_content">
{{ html()->label(__('Project image'))->class('form-control-label')->for('projectimage') }}
<div class="form-group cs_file_btn"><span class="up_btn btn-info"><i class="fas fa-cloud-upload-alt"></i> Upload Project Image</span>
{{ html()->file('projectimage')}}

    </div>
    <div id="selected_products"></div>                               
    </div><!--form-group-->
</div><!-- col-md-6 end -->
</div><!-- row end -->                    

                                             
                </div><!--col-->
            </div><!--row-->
            {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button> --}}
        </div><!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">
                <div class="col">
                <span class="hvr_br">{{ form_cancel(route('admin.projectmanagement.index'), __('buttons.general.cancel')) }}</span>
                </div><!--col-->

                <div class="col text-right">
                @if($data[0]['on_boarding'] == "true")
                <span class="hvr_br">{{ form_submit(__('buttons.general.crud.create')) }}</span>
                @elseif($data[0]['on_boarding'] == "false")
                <span class="hvr_br">{{ form_submit(__('buttons.general.crud.update')) }}</span>
                @endif
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
<!--model-->
<!-- Modal -->
<div class="modal my_modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add Sales Representatives</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group row align-items-center">
                            
                            
                        </div>
                    <div class="form-group row">
                            
                            <!-- {{ html()->label(__('Add Users'))->class('col-md-12 form-control-label')->for('User') }} -->
                            {{ html()->label(__('Add Sales Representatives'))->class('col-md-12 form-control-label')->for('User') }}
        
                            
                            <div class="col-md-12">
                                   <div id="show_users">
                                       
                                   </div> 
                            </div>
                        </div>   
            </div>
            <div class="modal-footer">
            <span class="hvr_br"><button type="button" class="btn btn-info" data-dismiss="modal"onclick="" >Add</button></span>
            <span class="hvr_br"><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></span>
            </div>
          </div>
          
        </div>
      </div>
      <!--End Model -->
      <div class="modal my_modal fade" id="myModal1" role="dialog">
        <div class="modal-dialog">
        
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Add Products</h4>
            </div>
            <div class="modal-body">
                    <div class="form-group row align-items-center">
                                    
                            
                            
                            <!-- {{ html()->label(__('Add Products'))->class('col-md-12 form-control-label')->for('products') }} -->
                            {{ html()->label(__('Add Products'))->class('col-md-12 form-control-label')->for('products') }}
        
                            
                            <div class="col-md-12">
                                   <div id="show_products">
                                       
                                   </div> 
                                  
                            </div>
                        </div>   
            </div>
            <div class="modal-footer">
            <span class="hvr_br"><button type="button" class="btn btn-info" data-dismiss="modal"onclick="" >Add</button></span>
            <span class="hvr_br"><button type="button" class="btn btn-danger" data-dismiss="modal">Close</button></span>
            </div>
          </div>
          
        </div>
      </div>
    {{ html()->form()->close() }}
@endsection



<script src="{{asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">

var ajax_user_fired = false;
function showuser()
{
    // var url2 = '{{ url('admin/roles') }}';
    // $.get(url2, function(data) {
    //     console.log(data);
    // });
    var idw = document.getElementById("project_id").value;
    // console.log(idw);
     var url3 = '{{url('admin/associated_data')}}'+"/"+idw;
     var users = [];
     var roles = [];
     var products = [];
     $.get(url3, function(data2){
        // console.log(data2.users_roles);
        for(i = 0 ; i<data2.users_roles.length; i++)
        {
            console.log(data2);
            users.push(data2.users_roles[i].userid);
            roles.push(data2.users_roles[i].user_role);
        }
     });
    
    console.log(users);
    console.log(roles);
    arr_ud= new Array();
   // var arr = [ "John", "Peter", "Sally", "Jane" ];
    // user_data['users'] = users;
    // user_data['roles'] = roles;
    // console.log(user_data);
    // $.get(url3, function(product){
    //      console.log(product.products_quantity);
    //     for(j = 0 ; j<product.products_quantity.length; j++)
    //     {
    //         products.push(product.products_quantity[j]);
    //     }
    //  });

    //  console.log(products);
    var url = '{{ url('admin/users') }}';
    var url2 = '{{ url('admin/roles') }}';
    
// ['user_1'] = "<select></select>";



            //sconsole.log(url);
           if(ajax_user_fired == false){
            $.get(url, function(data) {
                //console.log(data);
                var c = 0;
                $.each(data,function(key, value) {
                    console.log(value.first_name);
                    if(users.includes(value.id)){
                     $("#show_users").append('<div class="form-group row"><div class="col-md-6"><input type="checkbox" id="'+value.id+'" name="users[]"  checked value="'+value.id+'"  onclick=roles(this.value)><span>'+value.full_name+'</span> </div><div class="col-md-6"><select   name="role_of_'+value.id+'" id ="role_for_'+value.id+'" > <option value="Select Role">Select Role</option></select> </div></div><hr>');
                     arr_ud[c] = "#role_for_"+value.id;
                    c++;
                //     console.log("role_for_"+value.id+"");
                //     $.get(url2, function(data2) {
                //   $.each(data2,function(key, value2) {
                //       console.log(value2.id);
                //       if(roles.includes(value2.id) && users.includes(value.id) ){
                //                 // console.log("role_for_"+value.id+"");
                //          $("#role_for_"+value.id+"").append("'<option value="+value2.id+" selected>"+value2.name+"</option>'");
                //          }
                //          else
                //          {
                //              $("#role_for_"+value.id+"").append("'<option value="+value2.id+" >"+value2.name+"</option>'");
                //          }
                //      });
                // });
                    }
                    else
                    {
                        $("#show_users").append('<div class="form-group row"><div class="col-md-6"><input type="checkbox" id="'+value.id+'" name="users[]"  value="'+value.id+'"  onclick=roles(this.value)><span>'+value.full_name+'</span> </div><div class="col-md-6"><select  name="role_of_'+value.id+'" id ="role_for_'+value.id+'" style="display:none;" > <option value="Select Role">Select Role</option></select></div></div><hr>');
                        $.get(url2, function(data3) {
                        $.each(data3,function(key, value3) {
                        $("#role_for_"+value.id+"").append("'<option value="+value3.id+">"+value3.name+"</option>'");
                    });
                    });  
                    }

                });
                console.log(arr_ud[1]);
                for(x=0;x<arr_ud.length;x++)
     {
         console.log(arr_ud[x]);
         $.get(url2, function(data3) {
         $.each(data3,function(key, value3) 
         {
             if(roles.includes(value3.id)){
          $(arr_ud[x]).append("'<option value="+value3.id+" selected>"+value3.name+"</option>'");
        }
         });
         });
         }
            });
           }
           ajax_user_fired = true;
          
                        
}

// var ajax_roles_fired = false;

function roles(val){
    var x = document.getElementById(val).checked;
    console.log(x);
    if(x == true)
    {
        console.log("role_for_"+val);
        
        document.getElementById("role_for_"+val).style.display = "block";
        var url2 = '{{ url('admin/roles') }}';
        // if(ajax_roles_fired == false){
        // $.get(url2, function(data) {
        //         console.log(data);
        //         $("#role_for_"+val+"").empty();
        //         // $("#role_for_"+val+"").append("'<option>"'Select Role'"</option>'");
        //          $.each(data,function(key, value) {
        //              // console.log(value.first_name);
                     
        //              $("#role_for_"+val+"").append("'<option value="+value.id+">"+value.name+"</option>'");
        //          });
               
        //     });
        // }
        // ajax_roles_fired = true;
    }

    else
    {
        document.getElementById("role_for_"+val).style.display = "none";
    }
    }

    var ajax_products = false
 function show_products()
 {
    var idw = document.getElementById("project_id").value;
    var url3 = '{{url('admin/associated_data')}}'+"/"+idw;
    var products = [];
    var products_name = "";
     $.get(url3, function(data2){
        // console.log(data2.users_roles);
        for(i = 0 ; i<data2.products_quantity.length; i++)
        {
            products.push(data2.products_quantity[i].product_id);
            // roles.push(data2.users_roles[i].user_role);
        }
     });
    
    var url2 = '{{ url('admin/products-ajax') }}';
    if(ajax_products == false){
    $.get(url2, function(data) {
        $.each(data,function(key, value) {
                     // console.log(value.first_name);
                     if(products.includes(value.id)){
                         
                        var url_for_quantity = '{{url('admin/product_quantity')}}'+'/'+value.id+'/'+idw;
                        $.get(url_for_quantity, function(quantity) {
                     $("#show_products").append('<div class="form-group row"><div class="col-md-6"><input type="checkbox" id="'+value.id+'" checked name="products[]" value="'+value.id+'"  onclick=quantity(this.value); ><span>'+value.name+' </span></div><div class="col-md-6"><input type="text" id ="quantity_for_'+value.id+'" value='+quantity[0].quantity+' name="quantity_of_'+value.id+'" "> </div></div><hr>');
                        });
                        // products_name.concat(value.name);
                        $("#selected_products").append("<p id="+value.id+">"+value.name+"</p>");    
                     }
                     else
                     {

                        // $("#show_products").append('<div class="form-group row"><div class="col-md-6"><input type="checkbox" id="'+value.id+'"  name="products[]" value="'+value.id+'"  onclick=quantity(this.value)><span>'+value.name+' </span></div><div class="col-md-6"><input type="text" id ="quantity_for_'+value.id+'" name="quantity_of_'+value.id+'" style="display:none;" placeholder="Quantity"><input type="text" id ="max_head_for_'+value.id+'" name="max_head_of_'+value.id+'" style="display:none;" placeholder="max head"> <input type="text" id ="max_flow_for_'+value.id+'" name="max_flow_of_'+value.id+'" style="display:none;" placeholder="max flow"> </div></div><hr>');    
                        console.log("show_products");

                        $("#show_products").append('<div class="form-group row"><div class="col-md-6"><input type="checkbox" id="'+value.id+'"  name="products[]" value="'+value.id+'"  onclick=quantity(this.value)><span>'+value.name+' </span></div><div class="col-md-6"><input type="text" id ="quantity_for_'+value.id+'" name="quantity_of_'+value.id+'" style="display:none;" placeholder="Quantity"> </div></div><hr>');    

                        // $("#show_products").append('<input type="checkbox" id="'+value.id+'"  name="products[]" value="'+value.id+'"  onclick=quantity(this.value)>'+value.name+' <input type="text" id ="quantity_for_'+value.id+'" name="quantity_of_'+value.id+'" style="display:none;" placeholder="Quantity"> <br>');    

                     }
                 });
    });
    ajax_products = true;
    }
     console.log("here");
 }

 function quantity(val)
 {
  var x = document.getElementById(val).checked;
    console.log(x);
    if(x == true)
    {      
        document.getElementById("quantity_for_"+val).style.display = "block";
        // document.getElementById("max_head_for_"+val).style.display = "block";
        // document.getElementById("max_flow_for_"+val).style.display = "block";
    }

    else
    {
        document.getElementById("quantity_for_"+val).style.display = "none";
        // document.getElementById("max_head_for_"+val).style.display = "none";
        // document.getElementById("max_flow_for_"+val).style.display = "none";
    }
    }

 function show_in_div()
 {
     alert("fads");
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
        $('#clientimg').empty().append('<img src="'+data+'" height="64px" width="64px">');
   
    
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
           $('#contractorimg').empty().append('<img src="'+data+'" height="64px" width="64px">');
      
       
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
           $('#consultantimg').empty().append('<img src="'+data+'" height="64px" width="64px">');
      
       
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
           $('#consultantimg').empty().append('<img src="'+data+'" height="64px" width="64px">');
      
       
       // var select = $('form select[name= subapptype]');
   
       // select.empty();
   
       // $.each(data,function(key, value) {
       //     select.append('<option value=' + value.id + '>' + value.name + '</option>');
       // });
   });
   });
    });
</script>
