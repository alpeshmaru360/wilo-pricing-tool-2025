@extends('frontend.layout.app')
@section('content')
<style type="text/css">
    #excel_image{
        width: 15%;
        height: 17px;
    }
    #edit_image{
        height: 17px;
    }
</style>
<!-- mid section start-->
<section class="midContent" id="midContent">
    <div class="container">
        <div class="d-flex flex-center">
            <div class="quotationMidSection">
                <h2>Quotation List- User</h2>

                <div class="quotationSection">
                    <div class="tableResponsive">
                        <table id="quotationsTable" class = "dataTable dataTables_info">
                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Quotation no</th>
                                    <th>Project Name</th>
                                    <th>Country</th>
                                    <th>Quotation Value</th>
                                    <th>Status</th>
                                    <th>Reason</th>                                 
                                    <th>Modification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $x = 1 
                                @endphp
                                @foreach($quotationsData as $key=>$val)
                                <tr>
								{{--<td style="display: none"></td>--}}
                                    <td>{{ $x}}</td>
                                    <td>{{$key}}<input type="hidden" class="quotation-no" value="{{$key}}"></td>
                                    <td>{{$val[0]['project_name']}}</td>
                                    <td>{{$val[0]['country']}}</td>
									<td>{{round($val[0]['price_data'])}}</td>
                                  
                                    <td> 
                                        <select name="" id="status" class="status formInput">
                                            <option value="Open" {{ isset($val[0]['status']) && $val[0]['status'] == "Open" ? "selected" : "" }}>Open</option>
                                            <option value="Won" {{ isset($val[0]['status']) && $val[0]['status'] == "Won" ? "selected" : "" }}>Won</option>
                                            <option value="Lost" {{ isset($val[0]['status']) && $val[0]['status'] == "Lost" ? "selected" : "" }}>Lost</option>

                                        </select>

                                    </td>
                                    <td>

                                        <select name="" id="reason" class="reason formInput">
                                            <option value="">Select Reason</option>
                                            <option value="Price" {{ isset($val[0]['reason']) && $val[0]['reason'] == "Price" ? "selected" : "" }}>Price</option>
                                            <option value="Delivery" {{ isset($val[0]['reason']) && $val[0]['reason'] == "Delivery" ? "selected" : "" }}>Delivery</option>
                                            <option value="Vendor List" {{ isset($val[0]['reason']) && $val[0]['reason'] == "Vendor List" ? "selected" : "" }}>Vendor List</option>
                                            <option value="COO" {{ isset($val[0]['reason']) && $val[0]['reason'] == "COO" ? "selected" : "" }}>COO</option>
                                            <option value="Spec. not compliance" {{ isset($val[0]['reason']) && $val[0]['reason'] == "Spec. not compliance" ? "selected" : "" }}>Spec. not compliance</option>
                                        </select>

										{{--
                                    <td width="15%">
									<a href="{{url('controlpanel/quotations/edit/'.$key)}}">Edit</a>
                                        <a href="{{ URL::to('controlpanel/quotations/pdf/'.$key )}}" target="_blank">
                                            <img src="{{asset('fassets/images/viewIcon.png')}}" />
                                        </a>
                                        <a href="{{ URL::to('controlpanel/quotations/pdf/'.$key )}}" download>
                                            <img src="{{asset('fassets/images/downloadIcon.png')}}" />
                                        </a>
                                           <!--<td>{!! !empty($val[0]['modification']) ? $val[0]['modification'] : '-' !!}</td>-->
 <a href="{{ URL::to('controlpanel/quotations/excel/'.$key )}}">
                                        Excel</a>--}}
									                                    <td width="15%">
                                        <a href="{{ URL::to('controlpanel/quotations/pdf/'.$key )}}" target="_blank">
                                        <img src="{{asset('fassets/images/viewIcon.png')}}" />
                                        </a>

                                        <a href="{{url('controlpanel/quotations/edit/'.$key)}}">
                                        <img src="{{asset('fassets/images/green_edit.png')}}" id="edit_image" />
                                        </a>
                                       
                                        <a href="{{ URL::to('controlpanel/quotations/pdf/'.$key )}}" download>
                                        <img src="{{asset('fassets/images/view2.png')}}" style="width:15%;"/>
                                        &nbsp;
                                        </a>

                                        <a href="{{ URL::to('controlpanel/quotations/excel/'.$key )}}">
                                        <img src="{{asset('fassets/images/excel1.png')}}" id="excel_image"/>
                                        </a>
                                    </td>
                                    

                                </tr>
                                @php
                                $x++
                                @endphp
                                @endforeach


                            </tbody>
                        </table>
				</div>
                </div>

            </div>
        </div>
        <div class="d-flex cusPagination">
            <div class="">
                <a  onclick="window.history.back()" href=""><img src="{{asset('fassets/images/arrowLefticon.png')}}" /> Back</a>
            </div>
            <!--            <div class="">
                            <button>Next <img src="assets/images/arrowLefticon.png" /></button>
                        </div>-->
        </div>
        <div class="d-flex formPageFooter">
            <div class="left">
                <!--Unit Price: <button class="clcBtn">Calculate</button> <span>750€</span>-->
            </div>
            <div class="right">
                <ul>
                    <li><a href="{{URL::to('/')}}" tooltip="Go to Home Page"><img src="{{asset('fassets/images/homeIcon.png')}}" /></a></li>                     
                    <li><a href="{{URL::to('controlpanel/cart/'.Auth::user()->id)}}" tooltip="Cart"><img src="{{asset('fassets/images/addIcon.png')}}" /></a></li>
<!--                    <li><a href="#" tooltip="Checkout"><img src="{{asset('fassets/images/goIcon.png')}}" /></a></li>-->
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- mid section end -->


@endsection
@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 
<script>
    $(document).ready(function () {
        $('#quotationsTable').DataTable({
            "pageLength": 100 // default rows per page
        });
    });
</script>
 

<script>

    $(".status").on('change', function () {
        var quotation_no = $(this).closest('tr').find('.quotation-no').val();
        var status = $(this).find('option:selected').val();
        $.ajax({
            type: "get",
            url: "{{url('controlpanel/quotations/status-update')}}",
            data: {quotation_no: quotation_no, status: status},
            dataType: 'json',
            success: function (response) {

                alert(response.msg);
            },
            error: function (response) {

            }

        });
    });
    $(".reason").on('change', function () {
        var quotation_no = $(this).closest('tr').find('.quotation-no').val();
        var reason = $(this).find('option:selected').val();
        $.ajax({
            type: "get",
            url: "{{url('controlpanel/quotations/reason-update')}}",
            data: {quotation_no: quotation_no, reason: reason},
            dataType: 'json',
            success: function (response) {

                alert(response.msg);
            },
            error: function (response) {

            }

        });
    });

</script>

@stop