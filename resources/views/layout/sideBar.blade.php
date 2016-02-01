<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="{{ url("app/dashboard") }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="{{ url("app/dashboard") }}">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Store</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url("app/new-store/self") }}"><i class="fa fa-plus"></i> Add Store</a></li>
                    <li><a href="{{ url("app/new-store") }}"><i class="fa fa-plus"></i> Add From Yelp</a></li>
                    <li><a href="{{ url("app/store-list") }}"><i class="fa fa-list"></i> All Store</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="{{ url("store/category") }}">
                    <i class="fa fa-sitemap"></i>
                    <span>Category</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url("store/category/new") }}"><i class="fa fa-plus"></i> Add Category</a></li>
                    <li><a href="{{ url("store/category") }}"><i class="fa fa-list"></i> Category List</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="{{ url("store/category") }}">
                    <i class="fa fa-users"></i>
                    <span>Users</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    {{--<li><a href="{{ url("store/category/new") }}"><i class="fa fa-plus"></i> Add Category</a></li>--}}
                    <li><a href="{{ url("app/user") }}"><i class="fa fa-users"></i> App Users</a></li>
                    <li><a href="{{ url("app/admin") }}"><i class="fa fa-users"></i> App Admins</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="{{ url("admin/logout") }}">
                    <i class="fa fa-power-off"></i> <span> Logout</span>
                </a>
            </li>
        </ul>
    </section>
</aside>