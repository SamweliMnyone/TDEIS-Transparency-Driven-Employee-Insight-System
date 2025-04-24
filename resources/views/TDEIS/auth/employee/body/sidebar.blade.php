<style>
    /* Style the logout link to match other menu items */
    .navbar-nav li a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        transition: all 0.3s;
    }

    .navbar-nav li a:hover {
        background-color: #f5f5f5;
    }

    .menu-icon {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    /* Align toggle and logo */
    .navbar-header {
        display: flex;
        align-items: center;
    }

    .menutoggle {
        font-size: 22px;
        color: #000;
        margin-right: 10px;
        cursor: pointer;
    }
</style>

<!-- Left Panel -->
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
        <ul class="nav navbar-nav">
    <li>
        <a href="{{ route('employee.dashboard') }}" class="ajax-link">
            <i class="menu-icon fa fa-laptop"></i>Employee Dashboard
        </a>
    </li>

    <li>
        <a href="{{ route('employee.skills') }}" class="ajax-link">
            <i class="menu-icon fa fa-code"></i>Skills & Expertise
        </a>
    </li>

    <li class="menu-item-has-children dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            <i class="menu-icon fa fa-trophy"></i>Contributions
        </a>
        <ul class="sub-menu children dropdown-menu">
            <li>
                <i class="fa fa-plus-circle"></i>
                <a href="{{ route('contributions.create') }}" class="ajax-link">
                    Add Certificate/Project Details
                </a>
            </li>
            <li>
                <i class="fa fa-list-alt"></i>
                <a href="{{ route('contributions.index') }}" class="ajax-link">
                    View Contributions
                </a>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{ url('notifications') }}" class="ajax-link">
            <i class="menu-icon fa fa-bell"></i>Notifications
        </a>
    </li>

    <li>
        <a href="{{ route('employee.dashboard.profile') }}" class="ajax-link">
            <i class="menu-icon fa fa-user"></i>My Profile
        </a>
    </li>

    <!-- Logout Button -->
    <li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="menu-icon fa fa-sign-out"></i>Logout
        </a>
    </li>
</ul>

        </div>
    </nav>
</aside>
<!-- /#left-panel -->