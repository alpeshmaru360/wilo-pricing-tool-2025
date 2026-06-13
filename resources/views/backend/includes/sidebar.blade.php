<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">
                <!-- @lang('menus.backend.sidebar.general') -->
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link {{
                    active_class(Active::checkUriPattern('admin/dashboard'))
                }}" href="{{ route('admin.dashboard') }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    @lang('menus.backend.sidebar.dashboard')
                </a>
            </li> -->

            <!-- <li class="nav-title">
                @lang('menus.backend.sidebar.system')
            </li> -->

            @if ($logged_in_user->isAdmin())
            <li class="nav-item nav-dropdown {{
                    active_class(Active::checkUriPattern('admin/auth/user*'), 'open')
                }}">
                <a class="nav-link {{
                                active_class(Active::checkUriPattern('admin/auth/user*'))
                            }}" href="{{ route('admin.auth.user.index') }}">
                    @lang('labels.backend.access.users.management')testing

                    <!-- @if ($pending_approval > 0)
                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                    @endif -->
                </a>
                <!-- <a class="nav-link nav-dropdown-toggle {{
                        active_class(Active::checkUriPattern('admin/auth*'))
                    }}" href="#">
                    <i class="nav-icon far fa-user"></i>
                    @lang('menus.backend.access.title')

                    @if ($pending_approval > 0)
                    <span class="badge badge-danger">{{ $pending_approval }}</span>
                    @endif
                </a> -->

                <!-- <ul class="nav-dropdown-items">
                    <li class="nav-item">
                        <a class="nav-link {{
                                active_class(Active::checkUriPattern('admin/auth/user*'))
                            }}" href="{{ route('admin.auth.user.index') }}">
                            @lang('labels.backend.access.users.management')

                            @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                                    active_class(Active::checkUriPattern('admin/auth/user*'))
                                }}" href="{{ route('admin.auth.user.deactivated') }}">
                            @lang('Deactivated Users')

                            @if ($pending_approval > 0)
                            <span class="badge badge-danger">{{ $pending_approval }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                                active_class(Active::checkUriPattern('admin/auth/role*'))
                            }}" href="{{ route('admin.auth.role.index') }}">
                            @lang('labels.backend.access.roles.management')
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/auth*'))
                        }}" href="{{ route('admin.usertomanager.index') }}">
                        @lang('User Manager')
                    </a>
                    </li>
                </ul> -->
            </li>

            {{-- Product management End --}}
            <li class="nav-item nav-dropdown {{
                    active_class(Active::checkUriPattern('admin/product*'), 'open')
                }}">
                <!-- <a class="nav-link nav-dropdown-toggle {{
                    active_class(Active::checkUriPattern('admin/auth*'))
                }}" href="#">
                <i class="nav-icon fa fa-cog"></i>
                Product Management
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"> -->
                    <a class="nav-link {{
                        active_class(Active::checkUriPattern('admin/product*'))
                    }}" href="{{ route('admin.products.index') }}">
                    <!-- @lang('labels.backend.access.users.management') -->
                    Product Management
                    
                </a>
                <!-- </li>
            </ul> -->
        </li>

        {{-- project management start --}}
        <li class="nav-item nav-dropdown {{
                active_class(Active::checkUriPattern('admin/projectmanagement*'), 'open')
            }}">
            <!-- <a class="nav-link nav-dropdown-toggle {{
                    active_class(Active::checkUriPattern('admin/auth*'))
                }}" href="#">
                <i class="nav-icon fa fa-cog"></i>
                Project Management
            </a>
            <ul class="nav-dropdown-items">
                <li class="nav-item"> -->
            <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/projectmanagement*'))
                        }}" href="{{ route('admin.projectmanagement.index') }}">
                <!-- @lang('labels.backend.access.users.management') -->
                Project Management

            </a>
            <!-- </li>
    </ul> -->
        </li>
        {{-- project management end --}}
        
        <li class="nav-item nav-dropdown {{
                active_class(Active::checkUriPattern('admin/customField*'), 'open')
            }}">
                <a class="nav-link nav-dropdown-toggle {{
                    active_class(Active::checkUriPattern('admin/customField*'))
                    }}" href="#">
                    <!-- <i class="nav-icon fa fa-cog"></i> -->
                    Custom Fields
                </a>
                <ul class="nav-dropdown-items">
                    <li class="nav-item">
                       
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link {{
                                    active_class(Active::checkUriPattern('admin/auth*'))
                                }}" href="{{ route('admin.usertomanager.index') }}">
                            @lang('User Manager')
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/application*'))
                            }}" href="{{ route('admin.application.index') }}">
                            <!-- @lang('labels.backend.access.users.management') -->
                            Type of Application

                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/subapplication*'))
                        }}" href="{{ route('admin.subapplication.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Type of Sub-Application
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/typeproject*'))
                        }}" href="{{ route('admin.typeproject.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Type of Project
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/projectsegment*'))
                        }}" href="{{ route('admin.projectsegment.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Type of Segment
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/factory_manufacturer*'))
                        }}" href="{{ route('admin.factory_manufacturer.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Factory Manufacturer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                                active_class(Active::checkUriPattern('admin/contractor*'))
                            }}" href="{{ route('admin.contractor.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Contractors
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                                active_class(Active::checkUriPattern('admin/consultant*'))
                            }}" href="{{ route('admin.consultant.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Consultants
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/client*'))
                            }}" href="{{ route('admin.client.index') }}">
                            <!-- @lang('labels.backend.access.roles.management') -->
                            Clients
                        </a>
                    </li>
                </ul>
            </li>


            <li class="divider"></li>

            {{-- <li class="nav-item nav-dropdown {{
                active_class(Active::checkUriPattern('admin/log-viewer*'), 'open')
            }}">
            <a class="nav-link nav-dropdown-toggle {{
                active_class(Active::checkUriPattern('admin/log-viewer*'))
            }}" href="#">
                <i class="nav-icon fas fa-list"></i> @lang('menus.backend.log-viewer.main')
            </a>

            <ul class="nav-dropdown-items">
                <li class="nav-item">
                    <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/log-viewer'))
                        }}" href="{{ route('log-viewer::dashboard') }}">
                        @lang('menus.backend.log-viewer.dashboard')
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{
                            active_class(Active::checkUriPattern('admin/log-viewer/logs*'))
                        }}" href="{{ route('log-viewer::logs.list') }}">
                        @lang('menus.backend.log-viewer.logs')
                    </a>
                </li>
            </ul>
            </li> --}}
            @endif
        </ul>
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
<!--sidebar-->