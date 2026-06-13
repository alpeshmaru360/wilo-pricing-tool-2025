
@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('labels.backend.access.users.management'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                   
                </h4>
            </div><!--col-->

            <div class="col-sm-7">
                @include('backend.usertomanager.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            
                            <th>ManagerName</th>
                            {{-- <th>Users</th> --}}
                            <th>Edit</th>
                            {{-- <th>View</th> --}}
                           
                        </tr>
                        </thead>
                        <tbody>
                           
                        @foreach($data as $da)
                            
                            <tr>
                                
                                <td>{{ $da['first_name'] }}</td>
                                {{-- <td>
                                    @foreach($data1 as $d1)
                                    @if($da['manager_id'] = $d1['manager_id'])
                                    {{$d1['first_name']}} ,
                                    @endif
                                    @endforeach
                                </td> --}}
                                <td><a href="{{ route('admin.usertomanager.edit',$da['id']) }}">edit</a></td>
                                {{-- <td><a href="{{ route('admin.usertomanager.show',$da) }}">View</a></td> --}}
                                {{-- <td>
                                <a href="{{ route('admin.usertomanager.destroy',$da) }}"
                                data-method="delete"
                                data-trans-button-cancel="{{ __('buttons.general.cancel') }}"
                                data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}"
                                data-trans-title="{{ __('strings.backend.general.are_you_sure') }}"
                                class="btn btn-danger"><i class="fa fa-trash" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.delete') }}"></i></a>
                                </td> --}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {{-- {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }} --}}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {{-- {!! $users->render() !!} --}}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
