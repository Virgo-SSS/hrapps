<!-- sidebar menu area start -->
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('home') }}" style="text-decoration: none">
                <span style="font-size:30px;color:white; font-family: 'Lucida Bright'; font-weight: bold">
                    PT. ABP
                </span>
                {{--  <img src="{{ asset('assets/images/icon/logo.png') }}" alt="logo">--}}
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <li>
                        <a href="{{ route('home') }}" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>Employee</span></a>
                        <ul class="collapse">
                            <li><a href="{{ route('users.create') }}">Create Employee</a></li>
                            <li><a href="{{ route('users.index') }}">Data Employee</a></li>
                        </ul>
                    </li>
                    @can('view role')
                        <li>
                            <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tags"></i><span>Role</span></a>
                            <ul class="collapse">
                                <li><a href="{{ route('permission.index') }}">Permissions</a></li>
                                @can('create role')
                                <li><a href="{{ route('role.create') }}">Create Role</a></li>
                                @endcan
                                <li><a href="{{ route('role.index') }}">Data Role</a></li>
                            </ul>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ route('divisi.index') }}" aria-expanded="true"><i class="fa fa-suitcase"></i><span>Divisi</span></a>
                    </li>
                    <li>
                        <a href="{{ route('posisi.index') }}" aria-expanded="true"><i class="fa fa-legal"></i><span>Posisi</span></a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-hourglass"></i><span>Cuti</span></a>
                        <ul class="collapse">
                            <li><a href="{{ route('cuti.request') }}">Request Cuti</a></li>
                            <li><a href="{{ route('cuti.index') }}">Data Cuti</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
