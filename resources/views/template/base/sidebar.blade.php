
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
        <img src="{{ asset('images/putraCabe.png') }}" alt="putraCabe"
            class="brand-image img-circle elevation-3" style="opacity: .8; background-color: white;">
        <span class="brand-text font-weight-light">Admin Web</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($rs_heading as $heading)
                <li class="nav-item {{$heading->app_heading_id == $currentInduk ? 'menu-open' : ''}}">
                    <a href="#" class="nav-link {{$heading->app_heading_id == $currentInduk ? 'nav-link active' : ''}}">
                        <i class="nav-icon {{$heading->app_heading_icon}}"></i>
                        <p>
                            {{$heading->app_heading_name}}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @foreach ($heading->data_menu as $dt_menu)
                        <li class="nav-item">
                            <a href="{{route($dt_menu->menu->menu_url)}}" class="nav-link {{$dt_menu->menu->menu_url == $currentChild ? 'active' : ''}}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{$dt_menu->menu->menu_name}}</p>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </li>
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


