<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('wilo_design_component/images/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{ asset('wilo_design_component/images/favicon.ico')}}" type="image/x-icon">
    <!-- <title>@yield('title', app_name())</title> -->
    <title>Wilo World | Administration</title>
    <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
    <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lightbox.css')}}">
    <link rel="stylesheet" href="{{asset('css/font-awesome.min.css')}}">
</head>
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css" integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" /> -->
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" /> -->
<link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />

@yield('meta')

{{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
@stack('before-styles')

<!-- Check if the language is set to RTL, so apply the RTL layouts -->
<!-- Otherwise apply the normal LTR layouts -->
{{ style(mix('css/backend.css')) }}

@stack('after-styles')
</head>

<body class="{{ config('backend.body_classes') }}">
    @include('backend.includes.header')

    <div class="app-body">
        @include('backend.includes.sidebar')

        <main class="main">
            @include('includes.partials.demo')
            @include('includes.partials.logged-in-as')
            {{--{!! Breadcrumbs::render() !!}--}}

            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="content-header">
                        @yield('page-header')
                    </div>
                    <!--content-header-->

                    @include('includes.partials.messages')
                    @yield('content')
                </div>
                <!--animated-->
            </div>
            <!--container-fluid-->
        </main>
        <!--main-->

        @include('backend.includes.aside')
    </div>
    <!--app-body-->

    @include('backend.includes.footer')

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/backend.js')) !!}
    @stack('after-scripts')
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/js/lightbox-plus-jquery.min.js"></script> -->
    <script src="{{asset('js/lightbox-plus-jquery.min.js')}}"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script> -->
    <script src="{{asset('js/select2.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="file"]').change(function(e) {
                var fileName = e.target.files[0].name;
                // alert('The file "' + fileName +  '" has been selected.');
                $(this).after("<span class='show_file_name'>" + fileName + "</span>");
            });
            // $('#select-client').selectize({
            //     sortField: 'text'
            // });
            $("#select-client, #select-consultant, #select-contractor, #project_segment, #typeproject, #year, #country, #client, #contractor, #consultant, #segment, #p-type, #application, #subapplication").select2();


        });

        function deleteImage(table, id, type='', image='', totalCount = 0, count = 0) {
            if (confirm('Are you sure you want Delete Image?')) {

            $.ajax({
                url: "{{ url('admin/ajax-delete-image')}}", // Send the data with your url.
                type: "GET",
                data: {
                    table: table,
                    id: id,
                    type: type,
                    image: image,
                }, // Here you have written as {GenderID: gender} , not {'GenderID': gender}
                success: function(data) {
                    if(data == "Add atleast one product picture")
                    {
                        alert("Cannot delete! atleast one product picture should be present against each product.");
                    }else{
                    $('#result').html(data);
                    $('#x').hide();
                    console.log(data);
                    if(totalCount > 0){
                        delMultiImage(totalCount, count);
                    }
                    }
                    
                }
            });
            }
            else {
                    return false;
                }
            }
            function delMultiImage(totalCount, count){

                if(totalCount > 0){
                    $('#x'+count).hide();
                    $('#btn_x'+count).hide();
                }
                

            }
    </script>
</body>

</html>