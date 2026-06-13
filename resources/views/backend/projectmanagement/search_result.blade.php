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
                    Project Management
                </h4>
            </div>
            <!--col-->

            <div class="col-sm-7">
                @include('backend.projectmanagement.includes.header-buttons')
            </div>
            <!--col-->
        </div>
        <!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <!-- <th>Id</th> -->
                                <th>Project Name</th>
                                <th>Country</th>
                                <th>Type of Project</th>
                                <th>Type of Segment</th>
                                <th>Type of Application</th>
                                <th>Type of Sub-Application </th>
                                <!-- <th>Edit</th>
                            <th>View</th>
                            <th>Delete</th> -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $da)

                            <tr>
                                <!-- <td>{{ $data[0]->id }}</td> -->
                                <td>{{ $da->project_name }}</td>
                                <td>{{ $da->c_name }}</td>
                                <td>{{ $da->t_name }}</td>
                                <td>{{ $da->s_name }}</td>

                                <td>{{ $da->a_name }}</td>
                                @if (isset($da->sa_name))
                                <td>{{ $da->sa_name }}</td>
                                @else
                                <td></td>
                                @endif
                                <td>
                                    <a href="{{ route('admin.projectmanagement.show',$da->id) }}" data-toggle="tooltip" data-placement="top" title="View" class="btn btn-info"><i class="fas fa-eye" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.view') }}"></i></a>
                                    <a href="{{ route('admin.projectmanagement.edit',$da->id) }}" data-toggle="tooltip" data-placement="top" title="Edit" class="btn btn-primary"><i class="fas fa-edit" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.edit') }}"></i></a>
                                    <!-- </td>
                                <td> -->
                                    <!-- </td>
                                <td> -->
                                    <a href="{{ route('admin.projectmanagement.destroy',$da->id) }}" data-method="delete" data-trans-button-cancel="{{ __('buttons.general.cancel') }}" data-trans-button-confirm="{{ __('buttons.general.crud.delete') }}" data-trans-title="{{ __('strings.backend.general.are_you_sure') }}" class="btn btn-danger"><i class="fa fa-trash" data-toggle="" data-placement="top" title="{{ __('buttons.general.crud.delete') }}"></i></a>
                                </td>
                            </tr>
                            <!--  -->
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
</div>
<!--card-->
@endsection