<ul id="menu" class="page-sidebar-menu">
    <li {!! (Request::is('dashboard') ? 'class="active"' : '' ) !!}>
        <a href="">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Dashboard</span>
        </a>
    </li>

    <li {!! (Request::is('sources') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.sources.index') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Quản lý source mẫu</span>
        </a>
    </li>

    <li {!! (Request::is('packages') ? 'class="active"' : '' ) !!}>
        <a href="{{ route('admin.packages.index') }}">
            <i class="livicon" data-name="dashboard" data-size="18" data-c="#418BCA" data-hc="#418BCA" data-loop="true"></i>
            <span class="title">Quản lý packages</span>
        </a>
    </li>

</ul>
