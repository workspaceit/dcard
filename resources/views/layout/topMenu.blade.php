<header class="main-header">
    <a href="{{ url("app/dashboard") }}" class="logo">
        <span class="logo-mini"><b>d</b>C</span>
        <span class="logo-lg"><b>d</b>Card</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
    </nav>
    @yield("menuItems")
</header>