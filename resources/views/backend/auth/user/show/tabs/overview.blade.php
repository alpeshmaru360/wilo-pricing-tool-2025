<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            {{-- <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                <td><img src="{{ $user->picture }}" class="user-profile-image" /></td>
            </tr> --}}

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                <td>{{ $user->name }}</td>
            </tr>

            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                <td>{{ $user->email }}</td>
            </tr>

            <tr>
                <th>@lang('Country')</th>
                <td>{!! $country_name !!}</td>
            </tr>

            <tr>
                <th>@lang('Designation')</th>
                <td>{!! $user->designation !!}</td>
            </tr>
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.user_type')</th>
                <td>{{ $user->user_type == 1 ? 'Admin' : 'Sales Representative' }}</td>
            </tr>
            <tr>
                <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                <td>{!! $user->status_label !!}</td>
            </tr>
            {{--
            <tr>
                <th>@lang('Continent')</th>
                <td>{!! $continentname[0]->name !!}</td>
            </tr>
            <tr>
                <th>@lang('Country')</th>
                <td>{!! $continentid[0]->name !!}</td>
            </tr>
            <tr>
                <th>@lang('User Role')</th>
                <td>{!! $userRoles[0]!!}</td>
            </tr>
            --}}
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
</div>
<!--table-responsive-->