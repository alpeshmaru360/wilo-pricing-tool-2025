@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

<style>
  .button {
      font-size:17px;
      margin-top:20px;
      display: block;
      width: 228px;
      height: 49px;
      background: #169e88;
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      color: white;
      font-weight: bold;
      line-height: 25px;
  }
  .button:hover{
    text-decoration:none;
    color:white;
  }
  .text-primary {
    color: #169e88 !important;
  }
  .bg-primary {
    background: #169e88 !important;
  }
  a.text-primary:focus, a.text-primary:hover {
    color: #0baa91 !important;
    text-decoration: none;
  }
  .blink_me {
    animation: blinker 1s linear infinite;
  }

  @keyframes blinker {
    50% {
      opacity: 0;
    }
  }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ trans('global.dashboard') }}</h1>
            <div class="row w-100 pl-3 d-flex align-items-baseline show-download-log">
              <select class="mr-3 d-flex font-weight-bold border-dark  mt-3 text-primary year_selection" style="border-color: #169e88 !important;">
                  <option class="">Select a year</option>
                @foreach($years as $val)
                  <option value="{{$val}}">{{$val}}</option>
                @endforeach
              </select>
              <a href="javascript:;" class="button download-quotation-log">Download Quotation Log</a>
              <div class="download-quotation-process ml-2 d-flex align-items-center blink_me" style="display: none !important;">
                <i class="fa fa-spinner fa-spin text-primary"></i><p class="p-0 m-0 ml-1 text-primary ">Initialize Processes</p>
              </div>
            </div>
        </div><!-- /.col -->
        <div class="col-sm-6">
            @include('layouts.breadcrumbs')
            <div class="row w-100 pl-3 d-flex align-items-baseline show-download-log">
                <a href="{{ url('admin/all-quotation-list') }}" class="d-flex font-weight-bold justify-content-end mt-3 pt-4 text-primary w-100 all-qutaion-log">All Quotation Log</a>
            </div>
        </div><!-- /.col -->
        </div><!-- /.row -->
        <div class="row m-0 p-0 w-100 progress-show" style="display: none;">
          <div class="progress w-100">
            <div class="progress-bar" role="progressbar" style="width: 0%;background: #169e88;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
          </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
@include('layouts.message')
<div class="container-fluid">
  <div class="row">
  </div>
</div>

<div class = container-fluid>
  <div class = "row">
    <div class="col-sm-6">
    <h3>Pie Chart - Country wise total Quotation</h3>
        <canvas id="myChart"></canvas>
    </div>
    <div class="col-sm-6">
    <h3>Pie Chart - Country wise total Quotation value</h3>
        <canvas id="myChart1"></canvas>
    </div>
  </div>
</div>
<br><br>
<div class = container-fluid>
  <div class = "row">
    <div class="col-sm-6">
    </div>
    <div class="col-sm-6">
    </div>
  </div>
</div>

</section>
@endsection

@section('scripts')    
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script type="text/javascript">
      var filename = '';
      var page = 1;
      var content = 'new';
      var beforeptxt = '';
      var progress = '';
      var percentage = 0;
      var selected_year = '';
      $(document).on('change','.year_selection',function(){
        selected_year = $(this).val();
      });
      
      $(document).on('click', '.download-quotation-log', function () {
        if(selected_year == "" || selected_year == "Select a year"){
          alert("Please select a year");
        }
        else{
              $('.download-quotation-process').show();
              beforeptxt = 'Initialize Process..';
              ajaxCall(filename, page, content, beforeptxt, progress, percentage,selected_year);
        }
      });

      function ajaxCall(filename, page, content, beforeptxt, progress, percentage,selected_year) {
          if (progress != 'show') {
              $('.progress-show').hide();
          } else {
              $('.progress-show').show();
          }
          $.ajax({
              url: "{{ url('admin/dashboard/new-export-quotation') }}",
              method: 'POST',
              data: {
                  _token: "{{csrf_token()}}",
                  filename: filename,
                  page: page,
                  content: content,
                  selected_year : selected_year,
              },
              beforeSend: function() {
                  $(".download-quotation-log").prop('disabled', true).css('cursor', 'no-drop');
                  $('.download-quotation-process').find('i').removeAttr('class').addClass('fa fa-spinner fa-spin text-primary');
                  $('.download-quotation-process').find('p').removeClass('').addClass('');
                  $('.download-quotation-process').find('p').removeAttr('class').addClass('p-0 m-0 ml-1 text-primary').text(beforeptxt);
              },
              success:function(data) {
                  if (data['success'] == 1) {
                      content = data['content'];
                      filename = data['filename'];
                      page = data['nextPage'];
                      beforeptxt = data['msg'];
                      progress = data['progress'];
                      percentage = data['percentage'];
                      if (percentage > 0) {
                          $('.progress-show').find('.progress').find('.progress-bar').css('width', percentage+'%');
                          $('.progress-show').find('.progress').find('.progress-bar').attr('aria-valuenow', percentage);
                          $('.progress-show').find('.progress').find('.progress-bar').text( percentage+'%');
                      }
                      if (data['currentPage'] != data['lastPage']) {
                          ajaxCall(filename, page, content, beforeptxt, progress, percentage);
                      } else {

                          // Complete 100%
                          ajaxCall(filename, page, 'movefile', 'File Generaing...', 'hide', percentage);

                      }
                  }

                  if (data['success'] == 2) {
                      $('.download-quotation-process').attr('style', 'display: none !important;');
                      $(".download-quotation-log").prop('disabled', false).css('cursor', 'pointer');
                      window.open(data['url']);
                  }
                  if (data['success'] == 'no-data') {
                      alert('No quotation data available in this year.');
                      location.reload();
                  }
              },
              error: function () {
                  $(".download-quotation-log").prop('disabled', false).css('cursor', 'pointer');
                  $('.progress-show').hide();
                  $('.download-quotation-process').find('i').removeAttr('class').addClass('fa fa-exclamation-triangle text-danger');
                  $('.download-quotation-process').find('p').removeAttr('class').addClass('p-0 m-0 ml-1 text-danger').text('Process Failed');
              }
          });
      }
  </script>

  <script type="text/javascript">
    var labelArray = [];
    var dataArray =  [];
    var data = `<?php echo $data3;?>`;
    var cData = jQuery.parseJSON(data);

    var lable =   $(cData).each(function(i,val){
        $.each(val,function(k,v){
          labelArray.push(k); 
          dataArray.push(v);
        });
    });

    var ctx = document.getElementById("myChart").getContext('2d');

    var lableDataArray = labelArray.map((e, i) => e + '\xa0\xa0(' + dataArray[i] +')');

    //console.log(lableDataArray);
    
    var myChart = new Chart(ctx,{
      type: 'pie',
      radius: '80%',
        center: ['10%', '57.5%'],


    data: {
      // labels: ["C1", "C2", "C3", "C4", "C5", "C6", "C7","C8","C9","C10","C11","C12","C13","C14","C15","C16","C17","C18","C19","C20"],
      // lables:dataArray,
      labels:lableDataArray,
      datasets: [{
        backgroundColor: [
          "#2ecc71",
          "#3498db",
          "#1B4F72",
          "#9b59b6",
          "#784212",
          "#e74c3c",
          "#34495e",
          "orange",
          "white",
          "black",
          "cyan",
          "blue",
          "Blue-gray",
          "Blue-violet",
          "violet",
          "Brown",
          "Cream",
          "green",
          "gold",
          "pink"
      ],
      //data: [12, 19, 25, 17, 28, 24, 7,10,11,12,13,14,15,16,17,18,19,20,25,30]
      data:dataArray
      }]
    }

    });
  </script>

  <script type="text/javascript">
      var labelArray = [];
      var dataArray =  [];
      var data = `<?php echo $array_merge;?>`;
      var cData = jQuery.parseJSON(data);

      var lable = $(cData).each(function(i,val){
          $.each(val,function(k,v){
            labelArray.push(k); 
            dataArray.push(v);
          });
      });

      var ctx = document.getElementById("myChart1").getContext('2d');

      var lableDataArray = labelArray.map((e, i) => e + '\xa0\xa0(' + dataArray[i] +' $)');

      var myChart = new Chart(ctx,{
        type: 'pie',
        radius: '80%',
          center: ['10%', '57.5%'],
      data: {
        // labels: ["C1", "C2", "C3", "C4", "C5", "C6", "C7","C8","C9","C10","C11","C12","C13","C14","C15","C16","C17","C18","C19","C20"],
        // lables:dataArray,
        labels:lableDataArray,
        datasets: [{
          backgroundColor: [
            "#2ecc71",
            "#3498db",
            "#1B4F72",
            "#9b59b6",
            "#784212",
            "#e74c3c",
            "#34495e",
            "orange",
            "white",
            "black",
            "cyan",
            "blue",
            "Blue-gray",
            "Blue-violet",
            "violet",
            "Brown",
            "Cream",
            "green",
            "gold",
            "pink"
        ],
        //data: [12, 19, 25, 17, 28, 24, 7,10,11,12,13,14,15,16,17,18,19,20,25,30]
        data:dataArray
        }]
      }
      });
  </script>

  <script type="text/javascript">
      var labelArray = [];
      var dataArray =  [];
      var data = `<?php echo $array_merge;?>`;
      var cData = jQuery.parseJSON(data);

      var lable =   $(cData).each(function(i,val){
          $.each(val,function(k,v){
            labelArray.push(k); 
            dataArray.push(v);
          });
      });

      var ctx = document.getElementById("myChart2").getContext('2d');

      var lableDataArray = labelArray.map((e, i) => e + '\xa0\xa0(' + dataArray[i] +')');

      //console.log(lableDataArray);
      
      var myChart = new Chart(ctx,{
        type: 'pie',
        radius: '80%',
          center: ['10%', '57.5%'],
      data: {
        // labels: ["C1", "C2", "C3", "C4", "C5", "C6", "C7","C8","C9","C10","C11","C12","C13","C14","C15","C16","C17","C18","C19","C20"],
        // lables:dataArray,
        labels:lableDataArray,
        datasets: [{
          backgroundColor: [
            "#2ecc71",
            "#3498db",
            "#1B4F72",
            "#9b59b6",
            "#784212",
            "#e74c3c",
            "#34495e",
            "orange",
            "white",
            "black",
            "cyan",
            "blue",
            "Blue-gray",
            "Blue-violet",
            "violet",
            "Brown",
            "Cream",
            "green",
            "gold",
            "pink"
        ],
        //data: [12, 19, 25, 17, 28, 24, 7,10,11,12,13,14,15,16,17,18,19,20,25,30]
        data:dataArray
        }]
      }
      });
  </script>

  <script type="text/javascript">
      var labelArray = [];
      var dataArray =  [];
      var data = `<?php echo $array_merge;?>`;
      var cData = jQuery.parseJSON(data);

      var lable =   $(cData).each(function(i,val){
          $.each(val,function(k,v){
            labelArray.push(k); 
            dataArray.push(v);
          });
      });

      var ctx = document.getElementById("myChart3").getContext('2d');

      var lableDataArray = labelArray.map((e, i) => e + '\xa0\xa0(' + dataArray[i] +')');

      //console.log(lableDataArray);
      
      var myChart = new Chart(ctx,{
        type: 'pie',
        radius: '80%',
          center: ['10%', '57.5%'],
      data: {
        // labels: ["C1", "C2", "C3", "C4", "C5", "C6", "C7","C8","C9","C10","C11","C12","C13","C14","C15","C16","C17","C18","C19","C20"],
        // lables:dataArray,
        labels:lableDataArray,
        datasets: [{
          backgroundColor: [
            "#2ecc71",
            "#3498db",
            "#1B4F72",
            "#9b59b6",
            "#784212",
            "#e74c3c",
            "#34495e",
            "orange",
            "white",
            "black",
            "cyan",
            "blue",
            "Blue-gray",
            "Blue-violet",
            "violet",
            "Brown",
            "Cream",
            "green",
            "gold",
            "pink"
        ],
        //data: [12, 19, 25, 17, 28, 24, 7,10,11,12,13,14,15,16,17,18,19,20,25,30]
        data:dataArray
        }]
      }
      });
  </script>

@parent
@endsection