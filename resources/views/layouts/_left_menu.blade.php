<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('dashboard') ? 'class="active"' : '' ) !!}>
        <a href="">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>

    <li {!! (Request::is('admin/sources*') || Request::is('admin/source-configs*') ? 'class="active"' : '' ) !!}>
        <a href="#">
            <i class="livicon" data-name="medal" data-size="18" data-c="#6CC66C" data-hc="#6CC66C" data-loop="true"></i>
            <span class="title">Quản lý source mẫu</span>
            <span class="fa arrow"></span>
        </a>
        <ul class="sub-menu">
            <li {!! (Request::is('admin/sources') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/sources') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Source mẫu
                </a>
            </li>
            <li {!! (Request::is('admin/source-configs') ? 'class="active"' : '' ) !!}>
                <a href="{{ URL::to('admin/source-configs') }}">
                    <i class="fa fa-angle-double-right"></i>
                    Cấu hình source mẫu
                </a>
            </li>
        </ul>
    </li>

    <li {!! (Request::is('packages') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.packages.index') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Quản lý packages</span>
        </a>
    </li>

    <li {!! (Request::is('app-customers') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.app_customer.index') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Quản lý app khách hàng</span>
        </a>
    </li>

</ul>
