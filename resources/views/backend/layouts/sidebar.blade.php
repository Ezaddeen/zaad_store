@php
$route = request()->route()->getName();
@endphp

<div class="sidebar">

    <!-- قائمة التنقل الجانبية -->
    <nav class="mt-2 text-right">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            @can('dashboard_view')
            <li class="nav-item">
                <a href="{{ route('backend.admin.dashboard') }}"
                    class="nav-link {{ $route === 'backend.admin.dashboard' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>لوحة التحكم</p>
                </a>
            </li>
            @endcan

            @can('sale_create')
            <li class="nav-item">
                <a href="{{ route('backend.admin.cart.index') }}"
                    class="nav-link {{ $route === 'backend.admin.cart.index' ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-plus"></i>
                    <p>نقطة البيع (POS)</p>
                </a>
            </li>
            @endcan

            {{-- العملاء والموردون --}}
            @if (auth()->user()->hasAnyPermission([
                'customer_create', 'customer_view', 'customer_update', 'customer_delete', 'customer_sales',
                'supplier_create', 'supplier_view', 'supplier_update', 'supplier_delete',
            ]))
            <li class="nav-item {{ request()->routeIs(['backend.admin.customers.*','backend.admin.suppliers.*']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-circle nav-icon"></i>
                    <p>الأشخاص <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    @canany(['customer_create','customer_view','customer_update','customer_delete'])
                    <li class="nav-item">
                        <a href="{{route('backend.admin.customers.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.customers.*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>العملاء</p>
                        </a>
                    </li>
                    @endcanany
                    @canany(['supplier_create','supplier_view','supplier_update','supplier_delete'])
                    <li class="nav-item">
                        <a href="{{route('backend.admin.suppliers.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.suppliers.*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>الموردون</p>
                        </a>
                    </li>
                    @endcanany
                </ul>
            </li>
            @endif

            {{-- المنتجات --}}
            @if (auth()->user()->hasAnyPermission([
                'product_create','product_view','product_update','product_delete',
                'product_import','product_purchase'
            ]))
            <li class="nav-item {{ request()->routeIs(['backend.admin.products.*','backend.admin.brands.*','backend.admin.categories.*','backend.admin.units.*']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-box nav-icon"></i>
                    <p>المنتجات <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('backend.admin.products.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.products.index') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>قائمة المنتجات</p>
                        </a>
                    </li>
                    @can('product_create')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.products.create')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.products.create') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>إضافة منتج</p>
                        </a>
                    </li>
                    @endcan
                    @can('product_import')
                    <li class="nav-item">
                        <a href="{{route('backend.admin.products.import')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.products.import') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>استيراد منتجات</p>
                        </a>
                    </li>
                    @endcan
                    <li class="nav-item">
                        <a href="{{route('backend.admin.brands.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.brands.*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>العلامات التجارية</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.categories.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>الفئات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.units.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.units.*') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>الوحدات</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- المبيعات --}}
            @can('sale_view')
            <li class="nav-item {{ request()->routeIs('backend.admin.orders.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-tags nav-icon"></i>
                    <p>المبيعات <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('backend.admin.orders.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.orders.index') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>قائمة المبيعات</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endcan

            {{-- المشتريات --}}
            @if (auth()->user()->hasAnyPermission(['purchase_create','purchase_view','purchase_update','purchase_delete']))
            <li class="nav-item {{ request()->routeIs('backend.admin.purchase.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-shopping-bag nav-icon"></i>
                    <p>المشتريات <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('backend.admin.purchase.index')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.purchase.index') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>قائمة المشتريات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.purchase.create')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.purchase.create') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>إضافة شراء</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif

            {{-- ================================================== --}}
            {{-- ⬇️ هذا هو القسم الذي تم تعديله بالكامل ⬇️ --}}
            {{-- ================================================== --}}
            {{-- التقارير --}}
            {{-- تم إضافة 'reports_profit' هنا لضمان ظهور القسم --}}
            @if (auth()->user()->hasAnyPermission(['reports_summary','reports_sales','reports_inventory', 'reports_profit']))
            {{-- تم إضافة مسار تقرير الأرباح هنا لضمان بقاء القائمة مفتوحة --}}
            <li class="nav-item {{ request()->routeIs(['backend.admin.sale.report','backend.admin.sale.summery', 'backend.admin.inventory.report', 'backend.admin.profit.report']) ? 'menu-open' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar nav-icon"></i>
                    <p>التقارير <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('backend.admin.sale.summery')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.sale.summery') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>ملخص المبيعات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.sale.report')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.sale.report') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>تقرير المبيعات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('backend.admin.inventory.report')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.inventory.report') ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>تقرير المخزون</p>
                        </a>
                    </li>
                    
                    {{-- ⬇️ تم تعطيل شرط الصلاحية مؤقتاً كما طلبت ⬇️ --}}
                    @can('reports_profit') 
                    <li class="nav-item">
                        <a href="{{route('backend.admin.profit.report')}}"
                            class="nav-link {{ request()->routeIs('backend.admin.profit.report') ? 'active' : '' }}">
                            <i class="fas fa-chart-line nav-icon"></i>
                            <p>تقرير الأرباح</p>
                        </a>
                    </li>
                     @endcan 
                </ul>
            </li>
            @endif

            {{-- الإعدادات --}}
            @if (auth()->user()->hasAnyPermission([
                'currency_create','currency_view','currency_update','currency_delete','currency_set_default',
                'role_create','role_view','role_update','role_delete','permission_view',
                'user_create','user_view','user_update','user_delete','user_suspend',
                'website_settings','contact_settings','socials_settings','style_settings','custom_settings',
                'notification_settings','website_status_settings','invoice_settings',
            ]))
            <li class="nav-header">الإعدادات</li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog nav-icon"></i>
                    <p>إعدادات النظام <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.settings.website.general') }}"
                            class="nav-link {{ $route === 'backend.admin.settings.website.general' ? 'active' : '' }}">
                            <i class="fas fa-circle nav-icon"></i>
                            <p>إعدادات الموقع</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.currencies.index') }}"
                            class="nav-link {{ request()->routeIs('backend.admin.currencies.*') ? 'active' : '' }}">
                            <i class="fas fa-coins nav-icon"></i>
                            <p>العملة</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.roles') }}"
                            class="nav-link {{ $route === 'backend.admin.roles' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الأدوار</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.permissions') }}"
                            class="nav-link {{ $route === 'backend.admin.permissions' ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>الصلاحيات</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('backend.admin.users') }}"
                            class="nav-link {{ $route === 'backend.admin.users' ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <p>المستخدمون</p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
        </ul>
    </nav>
</div>

<script>
    const treeviewElements = document.querySelectorAll('.nav-treeview');
    treeviewElements.forEach(treeviewElement => {
        const navLinkElements = treeviewElement.querySelectorAll('.nav-link.active');
        if (navLinkElements.length > 0) {
            const parentNavItem = treeviewElement.closest('.nav-item');
            if (parentNavItem) parentNavItem.classList.add('menu-open');
            const childNavLink = parentNavItem.querySelector('.nav-link');
            if (childNavLink) childNavLink.classList.add('active');
        }
    });
</script>
