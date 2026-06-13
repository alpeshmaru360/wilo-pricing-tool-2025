<div class="row mt-4">
    <div class="col">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('labels.backend.access.users.table.first_name')</th>
                        <th>@lang('labels.backend.access.users.table.last_name')</th>
                        <th>@lang('labels.backend.access.users.table.email')</th>
                        <th>@lang('labels.backend.access.users.table.designation')</th>
                        <th>@lang('labels.backend.access.users.table.user_type')</th>
                        <th>@lang('labels.backend.access.users.table.status')</th>
                        <!-- <th>@lang('labels.backend.access.users.table.roles')</th>
                                    <th>@lang('labels.backend.access.users.table.confirmed')</th>
                                    <th>@lang('labels.backend.access.users.table.other_permissions')</th>
                                    <th>@lang('labels.backend.access.users.table.social')</th>
                                    <th>@lang('labels.backend.access.users.table.last_updated')</th> -->
                        <th style="text-align:center">@lang('labels.general.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // echo "<pre>";
                    // print_R($users);
                    // echo "</pre>";
                    ?>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->designation }}</td>
                        <td>{{ $user->user_type == 1 ? 'Admin' : 'Sales Representative' }}</td>
                        <td>{!! $user->status_label !!}</td>
                        <!-- <td>{!! $user->roles_label !!}</td>
                        <td>{!! $user->confirmed_label !!}</td>
                        <td>{!! $user->permissions_label !!}</td>
                        <td>{!! $user->social_buttons !!}</td>
                        <td>{{ $user->updated_at->diffForHumans() }}</td> -->
                        {!! $user->action_buttons !!}
                    </tr>
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
            {!! $users->total() !!} {{ trans_choice('labels.backend.access.users.table.total', $users->total()) }}
        </div>
    </div>
    <!--col-->

    <div class="col-5">
        <div class="float-right">
            {!! $users->render() !!}
        </div>
    </div>
    <!--col-->
</div>