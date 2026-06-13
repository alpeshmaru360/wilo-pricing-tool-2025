<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a href="{{ route("admin.home") }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
               <i class="nav-icon fas fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                    {{ trans('global.userManagement.title') }}
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route("admin.permissions.index") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                        {{ trans('global.permission.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.roles.index") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                        {{ trans('global.role.title') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                        {{ trans('global.user.title') }}
                    </a>
                </li>
            </ul>
        </li>
		
		 <li class="nav-item">
            <a href="{{ url('admin/country/show') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Country
            </a>
        </li>
		
         <li class="nav-item">
            <a href="{{ url('/admin/cp/control-panel-import') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
               CP File selection Upload/Import
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/file-import') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
               CP  All Components Import
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/master-price-file-import') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
               CP Master Sheet Price Import
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('/admin/adder-optional-file-import') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                CP Electric Adder Code Import
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('/admin/adder-optional-list-file-import') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                CP Adder Optional Components Import
            </a>
        </li>

        <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                    Atmos GIGA File Import
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
			<li class="nav-item">
                    <a href="{{ route('admin.atmos.pump_assmebly_cost.import.view') }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="nav-icon fas fa-sign-out-alt"></i>
                       Pump assmebly cost
                    </a>
                </li>
				<li class="nav-item">
                    <a href="{{ route('admin.atmos.pump_bom.import.view') }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="nav-icon fas fa-sign-out-alt"></i>
                      BOM
                    </a>
                </li>
				<li class="nav-item">
                    <a href="{{ route('admin.atmos.pump_master_sheet.import.view') }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="nav-icon fas fa-sign-out-alt"></i>
                      Pump Master Price
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.atmos.pumptype.import.view") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="nav-icon fas fa-sign-out-alt"></i>
                        Bare Shaft Import
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.atmos.accessories.import.view") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                       Accessories Import
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.atmos.master.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                       Master Import
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{ route('admin.atmos.costpaint.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                    Assembly Cost
                    </a>
                </li>

                   <li class="nav-item">
                    <a href="{{ route('admin.atmos.adder.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                  Adder
                    </a>
                </li>
            </ul>
        </li>

          <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                    SCP File Import
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route("admin.scp.pumptype.import.view") }}" class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                       <i class="nav-icon fas fa-sign-out-alt"></i>
                        Bare Shaft Import
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route("admin.scp.accessories.import.view") }}" class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                       Accessories Import
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.scp.master.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                       Master Import
                    </a>
                </li>
                 <li class="nav-item">
                    <a href="{{ route('admin.scp.costpaint.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                    Assembly Cost
                    </a>
                </li>

                   <li class="nav-item">
                    <a href="{{ route('admin.scp.adder.import.view') }}" class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                       <i class="far fa-circle nav-icon"></i>
                  Adder
                    </a>
                </li>
            </ul>
        </li>


        <li class="nav-item {{ request()->is(['admin/booster','admin/booster/*','admin/booster','admin/booster/*','admin/booster','admin/booster/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/booster','admin/booster/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                    Booster Import
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item {{ request()->is(['admin/booster/pump-price','admin/booster/pump-price/*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is(['admin/booster/pump-price','admin/booster/pump-price/*']) ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Pump Price                        </p>

                        <i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('admin/booster/pump-price/full-pump-price-import')}}" class="nav-link {{ request()->is(['admin/booster/pump-price/full-pump-price-import','admin/booster/pump-price/full-pump-price-import/*']) ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    Full Pump Price
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/booster/pump-price/bareshaft-pump-motor-price-import')}}" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    Bareshaft Pump &<br> Motor Price
                                </p>
                            </a>

                        </li>
                    </ul>
                </li>
            </ul>
            <ul class="nav nav-treeview">
                <li class="nav-item {{ request()->is(['admin/booster/mechanical-component','admin/booster/mechanical-component/*']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is(['admin/booster/mechanical-component','admin/booster/mechanical-component/*']) ? 'active' : '' }}">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Mechanical Component                       </p>

                        <i class="right fas fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{url('admin/booster/mechanical-component/master-sheet-price-import')}}" class="nav-link {{ request()->is(['admin/booster/mechanical-component/master-sheet-price-import','admin/booster/mechanical-component/master-sheet-price-import/*']) ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                  Master Sheet Price
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/booster/mechanical-component/ptp-distance-import')}}" class="nav-link {{ request()->is(['admin/booster/mechanical-component/ptp-distance-import','admin/booster/mechanical-component/ptp-distance-import/*']) ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    PTP Distance Sheet
                                </p>
                            </a>

                        </li>
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{url('admin/booster/mechanical-component/bom-pn16-import')}}" class="nav-link {{ request()->is(['admin/booster/mechanical-component/bom-pn16-import','admin/booster/mechanical-component/bom-pn16-import/*']) ? 'active' : '' }}">--}}
{{--                                <i class="nav-icon far fa-user"></i>--}}
{{--                                <p>--}}
{{--                                   BOM-PN16--}}
{{--                                </p>--}}
{{--                            </a>--}}

{{--                        </li>--}}
                        <li class="nav-item">
                            <a href="{{url('admin/booster/bom-file-import')}}" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    BOM File Import
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/booster/mechanical-component/base-frame-calculation-import')}}" class="nav-link {{ request()->is(['admin/booster/mechanical-component/base-frame-calculation-import','admin/booster/mechanical-component/base-frame-calculation-import/*']) ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                    Base Frame Size Calculation
                                </p>
                            </a>

                        </li>
                        <li class="nav-item">
                            <a href="{{url('admin/booster/mechanical-component/cable-selection-import')}}" class="nav-link {{ request()->is(['admin/booster/mechanical-component/cable-selection-import','admin/booster/mechanical-component/cable-selection-import/*']) ? 'active' : '' }}">
                                <i class="nav-icon far fa-user"></i>
                                <p>
                                   Cable Selection
                                </p>
                            </a>

                        </li>
                    </ul>
                </li>
            </ul>

        </li>
		
		
        <li class="nav-item {{ request()->is(['admin/fire-fighting/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/fire-fighting*']) ? 'active' : '' }}">
                <i class="nav-icon fa-fire fa"></i>
                <p>Fire Fighting Import<i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item {{ request()->is(['admin/fire-fighting/diesel-pump-import*']) ? 'menu-open' : '' }}">
                    <a href="{{ route('admin.fire-fighting.diesel-pump-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/diesel-pump-import*']) ? 'active' : '' }}"><p>Diesel Pump Import</p></a>
                    <a href="{{ route('admin.fire-fighting.electrical-pump-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/electrical-pump-import*']) ? 'active' : '' }}"><p>Electrical Pump Import</p></a>
                    <a href="{{ route('admin.fire-fighting.jockey-pump-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/jockey-pump-import*']) ? 'active' : '' }}"><p>Jockey Pump Import</p></a>
                    <a href="{{ route('admin.fire-fighting.battery-master-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/battery-master-import*']) ? 'active' : '' }}"><p>Battery Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.diesel-tank-master-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/diesel-tank-master-import*']) ? 'active' : '' }}"><p>Diesel Tank Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.optional-master-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/optional-master-import*']) ? 'active' : '' }}"><p>control Panel Optional Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.control-panel-master-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/control-panel-master-import*']) ? 'active' : '' }}"><p>Control Panel Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.adders') }}" class="nav-link {{ request()->is(['admin/fire-fighting/adders*']) ? 'active' : '' }}"><p>Adder Master Import</p></a>



                    <a href="{{ route('admin.fire-fighting.motor-master-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/motor-master-import*']) ? 'active' : '' }}"><p>Motor Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.flow-meter-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/flow-meter-import*']) ? 'active' : '' }}"><p>Flow Meter Master Import</p></a>
                    <a href="{{ route('admin.fire-fighting.pressure-relief-valve') }}" class="nav-link {{ request()->is(['admin/fire-fighting/pressure-relief-valve*']) ? 'active' : '' }}"><p>Pressure Relief Valve Import</p></a>
                    <a href="{{ route('admin.fire-fighting.waste-cone-import') }}" class="nav-link {{ request()->is(['admin/fire-fighting/waste-cone-import*']) ? 'active' : '' }}"><p>Waste Cone Import</p></a>
                </li>
            </ul>
        </li>
        <li class="nav-item {{ request()->is(['admin/fire-fighting-documents*']) ? 'menu-open' : '' }}">
            <a href="{{ route('admin.fire-fighting-documents.index') }}" class="nav-link">
                <i class="nav-icon fa-fire fa"></i>
                Fire Fighting Document
            </a>
        </li>
		
		
        <li class="nav-item">
            <a href="{{ route('admin.document') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Document
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.manual') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Manual
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.manage_tooltips') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Manage Tooltips
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.setup') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Setup Configuration
            </a>
        </li>


        <li class="nav-item">
            <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
           <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                Inter Country Margin
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.ic_margin') }}?part_id=2" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        Inter Country Margin Control Panel
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ic_margin') }}?part_id=1" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        Inter Country Margin Booster Set
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ic_margin') }}?part_id=3" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        Inter Country Margin SCP
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.ic_margin') }}?part_id=4" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        Inter Country Margin AtmosGiga
                </a>
            </li>
			<li class="nav-item">
                <a href="{{ route('admin.ic_margin') }}?part_id=5" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        Inter Country Margin Fire Fighting
                </a>
            </li>
            </ul>
        </li>
<!-- OTP margin code starts -->
           <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
                OTP Margin
				<i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
            <li class="nav-item">
                <a href="{{ route('admin.otp_margin') }}?part_id=2" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        OTP Margin Control Panel
                </a>
            </li>
			<li class="nav-item">
                <a href="{{ route('admin.otp_margin') }}?part_id=1" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        OTP Margin Booster Set
                </a>
            </li>
			<li class="nav-item">
                <a href="{{ route('admin.otp_margin') }}?part_id=3" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        OTP Margin SCP
                </a>
            </li>
			<li class="nav-item">
                <a href="{{ route('admin.otp_margin') }}?part_id=4" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        OTP Margin AtmosGiga
                </a>
            </li>
			<li class="nav-item">
                <a href="{{ route('admin.otp_margin') }}?part_id=5" class="nav-link">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                        OTP Margin Fire Fighting
                </a>
            </li>
			<li class="nav-item">
                    <a href="{{ route('admin.maintance_mode',['lable'=>'fire-fighting_maintance_mode']) }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                         Fire-fighting
                    </a>
                </li>
<!-- Under maintance code starts -->
        <li class="nav-item {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ request()->is(['admin/permissions','admin/permissions/*','admin/roles','admin/roles/*','admin/users','admin/users/*']) ? 'active' : '' }}">
                <i class="nav-icon far fa-user"></i>
                <p>
				Under Maintance Mode
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="{{ route('admin.maintance_mode',['lable'=>'atmos_maintance_mode']) }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                         Atmos Giga
                    </a>
                </li>
				<li class="nav-item">
                    <a href="{{ route('admin.maintance_mode',['lable'=>'sch_maintance_mode']) }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                         SCH Pump
                    </a>
                </li>
				<li class="nav-item">
                    <a href="{{ route('admin.maintance_mode',['lable'=>'fire-fighting_maintance_mode']) }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                         Fire-fighting
                    </a>
                </li>
            </ul>
        </li>
           <li class="nav-item">
            <a href="{{ url('/change-password') }}" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                Change Password
            </a>
        </li>
    </ul>
</nav>




















