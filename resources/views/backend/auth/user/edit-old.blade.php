@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.edit'))

@section('breadcrumb-links')
@include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')
{{ html()->modelForm($user, 'PATCH', route('admin.auth.user.update', $user->id))->class('form-horizontal')->open() }}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.access.users.management')
                    <small class="text-muted">@lang('labels.backend.access.users.edit')</small>
                </h4>
            </div>
            <!--col-->
        </div>
        <!--row-->

        <hr>

        <div class="row mt-4 mb-4">
            <div class="col">

                <div class="row">
                    <div class="col-md-6">
                        {{ html()->label(__('validation.attributes.backend.access.users.first_name'))->class('form-control-label')->for('first_name') }}
                        <div class="form-group">
                            {{ html()->text('first_name')
    ->class('form-control')
    ->placeholder(__('validation.attributes.backend.access.users.first_name'))
    ->attribute('maxlength', 191)
    ->required() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('validation.attributes.backend.access.users.last_name'))->class('form-control-label')->for('last_name') }}
                        <div class="form-group">
                            {{ html()->text('last_name')
    ->class('form-control')
    ->placeholder(__('validation.attributes.backend.access.users.last_name'))
    ->attribute('maxlength', 191)
    ->required() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('form-control-label')->for('email') }}
                        <div class="form-group">
                            {{ html()->email('email')
    ->class('form-control')
    ->placeholder(__('validation.attributes.backend.access.users.email'))
    ->attribute('maxlength', 191)
    ->required() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    <div class="col-md-6">
                        {{ html()->label(__('Designation'))->class('form-control-label')->for('designation') }}
                        <div class="form-group">
                            {{ html()->text('designation')
    ->class('form-control')
    ->placeholder(__('Designation'))
    ->attribute('maxlength', 191)
    ->required() }}
                        </div>
                    </div><!-- col-md-6 end -->
                    {{--
                    <div class="col-md-6">
                        {{ html()->label(__('Continent'))->class('form-control-label')->for('continent') }}
                    <div class="form-group">
                        <select name="continent" class="form-control">
                            @foreach($continent as $d)
                            <option value="{{$d->id}}">{{$d->name}}</option>
                            <option <?php echo ($continentname[0]->continent_id == $d->id ? 'selected' : ''); ?> value="{{$d->id}}">{{$d->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                --}}
                <!-- col-md-6 end -->
                <div class="col-md-6">
                    {{ html()->label(__('Country'))->class('form-control-label')->for('country') }}
                    <div class="form-group">
                        <select name="country" class="form-control">

                            <option value="{{$continentname[0]->id}}">{{$continentname[0]->name}}</option>
                            {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}

                        </select>
                    </div>
                </div><!-- col-md-6 end -->
                <div class="col-md-6">
                    {{ html()->label(__('User Type'))->class('form-control-label')->for('usertype') }}
                    <div class="form-group">
                        <select name="usertype" class="form-control">
                            <option value="0">Please Select user Type</option>
                            @foreach($usertype as $d)
                            <option <?php echo ($user->user_type == $d->id ? 'selected' : ''); ?> value="{{$d->id}}">{{$d->user_type_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div><!-- col-md-6 end -->
                <div class="col-md-6">
                    {{ html()->label(__('User Role'))->class('form-control-label')->for('parent') }}
                    <div class="form-group">
                        <select name="roles[]" id="role" class="form-control">
                            <option value="0">Please Select role</option>
                            @foreach($roles as $d)
                            {{-- <option value="{{$d->id}}">{{$d->name}}</option> --}}
                            <option <?php echo ($userRoles[0] == $d->name ? 'selected' : ''); ?> value="{{$d->id}}">{{$d->name}}</option>
                            @endforeach

                        </select>
                    </div>
                </div><!-- col-md-6 end -->

            </div><!-- row end -->


            <div class="form-group row" style="display:none;">
                {{ html()->label('Abilities')->class('col-md-2 form-control-label') }}

                <div class="table-responsive col-md-10">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>@lang('labels.backend.access.users.table.roles')</th>
                                <th>@lang('labels.backend.access.users.table.permissions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    @if($roles->count())
                                    @foreach($roles as $role)
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="checkbox d-flex align-items-center">
                                                {{ html()->label(
                                                                        html()->checkbox('roles[]', in_array($role->name, $userRoles), $role->name)
                                                                                ->class('switch-input')
                                                                                ->id('role-'.$role->id)
                                                                        . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                                                    ->class('switch switch-label switch-pill switch-primary mr-2')
                                                                    ->for('role-'.$role->id) }}
                                                {{ html()->label(ucwords($role->name))->for('role-'.$role->id) }}
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            @if($role->id != 1)
                                            @if($role->permissions->count())
                                            @foreach($role->permissions as $permission)
                                            <i class="fas fa-dot-circle"></i> {{ ucwords($permission->name) }}
                                            @endforeach
                                            @else
                                            @lang('labels.general.none')
                                            @endif
                                            @else
                                            @lang('labels.backend.access.users.all_permissions')
                                            @endif
                                        </div>
                                    </div>
                                    <!--card-->
                                    @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($permissions->count())
                                    @foreach($permissions as $permission)
                                    <div class="checkbox d-flex align-items-center">
                                        {{ html()->label(
                                                                html()->checkbox('permissions[]', in_array($permission->name, $userPermissions), $permission->name)
                                                                        ->class('switch-input')
                                                                        ->id('permission-'.$permission->id)
                                                                    . '<span class="switch-slider" data-checked="on" data-unchecked="off"></span>')
                                                                ->class('switch switch-label switch-pill switch-primary mr-2')
                                                            ->for('permission-'.$permission->id) }}
                                        {{ html()->label(ucwords($permission->name))->for('permission-'.$permission->id) }}
                                    </div>
                                    @endforeach
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!--col-->
            </div>
            <!--form-group-->
        </div>
        <!--col-->
    </div>
    <!--row-->
</div>
<!--card-body-->

<div class="card-footer">
    <div class="row">
        <div class="col">
            <span class="hvr_br">{{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}</span>
        </div>
        <!--col-->

        <div class="col text-right">
            <span class="hvr_br">{{ form_submit(__('buttons.general.crud.update')) }}</span>
        </div>
        <!--row-->
    </div>
    <!--row-->
</div>
<!--card-footer-->
</div>
<!--card-->
{{ html()->closeModelForm() }}
@endsection
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#role option[value =1]").hide();
        $('select[name=usertype]').change(function() {
            var value = $(this).val();
            if (value == '2') {
                $("#role option[value =1]").hide();
                $("#role option[value !=1]").show();
                $("#role").prop("selectedIndex", 0);
            } else if (value == '1') {
                $("#role option[value != 1]").hide();
                $("#role").prop("selectedIndex", 1);

            }
        });
    });


    $(function() {
        $('select[name=continent]').change(function() {

            var url = '{{ url('
            admin / continent ') }}' + "/" + $(this).val();
            console.log(url);
            $.get(url, function(data) {
                var select = $('form select[name= country]');

                select.empty();

                $.each(data, function(key, value) {
                    select.append('<option value=' + value.id + '>' + value.name + '</option>');
                });
            });
        });
    });



    $(document).ready(function() {
        $('.subject-list').click(function() {
            $('.subject-list').not(this).prop('checked', false);
        });
    });
    // $('.subject-list').on('change', function() {
    //     $('.subject-list').not(this).prop('checked', false);  
    // });
</script>