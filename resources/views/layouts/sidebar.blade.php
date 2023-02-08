<!-- sidebar menu area start -->
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="index.html"><img src="{{ asset('assets/images/icon/logo.png') }}" alt="logo"></a>
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
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>User</span></a>
                        <ul class="collapse">
                            <li><a href="{{ route('users.create') }}">Create User</a></li>
                            <li><a href="{{ route('users.index') }}">Data User</a></li>
                        </ul>
                    </li>
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
