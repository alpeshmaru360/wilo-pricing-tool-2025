@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Document
    </div>
    @if (Session::has('message'))
    <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif

    @if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ Session::get('error') }}</div>
    @endif
    <form action="{{ route("admin.manual_upload") }}" method="POST" enctype="multipart/form-data">
            @csrf
    <div class="card-body">

        <div class="form-group ">
            <label for="name">Module*</label>
            <select name="module" id="module" class="form-control" required="">
                <option value="">Select </option>
                <option value="booster_set">Booster Set</option>
                <option value="control_panel">Control Panel</option>
                <option value="scp_pump_assembly">SCP Pump Assembly</option>
                <option value="atmos_giga">Atmos GIGA</option>
				<option value="firefighting">Fire Fighting</option>
            </select>


        </div>

       
           
            <div class="form-group ">
                 <table width="100%" border="1px">
                    <thead>
                        <tr>
                        <th>Module Name</th>
                      
                        <th>File Upload</th>
                        </tr>
                    </thead>
                    <tbody id="manual_detail_table">
                        
                        
                    </tbody>
                    
                </table>

            </div>
            <div>
                <input class="btn btn-danger" type="submit"    value="{{ trans('global.save') }}">
            </div>
        
        <!-- <form action="{{ route("admin.master.price.upload") }}" method="POST" enctype="multipart/form-data">
              @csrf
             
            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                <label for="file_import">File</label>
                <input type="file" id="file" name="file" class="form-control">
                @if($errors->has('file-import'))
                <em class="invalid-feedback">
                    {{ $errors->first('file-import') }}
                </em>
                @endif
                <p class="helper-block">
                    {{ trans('global.product.fields.price_helper') }}
                </p>
            </div>
            <div>
                <input class="btn btn-danger" type="submit" value="{{ trans('global.save') }}">
            </div>
        </form> -->
    </div>
    </form>
</div>

@endsection



@section('scripts')
<script type="text/javascript">


    $(document).ready(function () {
        $(document).on('change', '#module', function () {
             var module = $(this).val();
             
             $("#article_detail_table").html("");
            $.ajax({
                url: "{{ URL::to('admin/get_manual_by_module') }}",
                type: 'get',
                data: {module: module},
                dataType: 'html',
                success: function (response) {
                    
                    $("#manual_detail_table").html(response);
                   
                }
            });
        });

        $(document).on('change', '#article', function () {
             var article = $(this).val();
             var module = $("#module").val();
// alert("AAAAAAAAAAAAAA"+ article);
            $.ajax({
                url: "{{ URL::to('admin/get_artical_detail') }}",
                type: 'get',
                data: {article: article, module: module},
                dataType: 'html',
                success: function (response) {
                    
                    $("#article_detail_table").html(response);
                   
                }
            });
        });



    });
</script>
@endsection