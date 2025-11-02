<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav mr-auto">

        @can('sale_create')
        <li class="nav-item dropdown">
            <a class="nav-link btn bg-gradient-primary text-white" href="{{route('backend.admin.cart.index')}}">
                <i class="fas fa-cart-plus"> نقطة البيع (POS)</i>
            </a>
        </li>
        @endcan
        
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle"></i>
                <i class="fas fa-angle-double-down"></i>
            </a>
            <div class="dropdown-menu ">
                <a href="{{ route('backend.admin.profile') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-address-card"></i>
                    الملف الشخصي
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item dropdown-footer">
                    <i class="fas fa-sign-out-alt"></i>
                    تسجيل الخروج
                </a>
            </div>
        </li>
    </ul>
</nav>