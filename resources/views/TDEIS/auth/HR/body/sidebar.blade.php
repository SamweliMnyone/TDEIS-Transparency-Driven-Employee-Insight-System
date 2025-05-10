<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('hr.dashboard') }}">
                        <i class="menu-icon fa fa-laptop"></i> Dashboard
                    </a>
                </li>

                <li>
                    <a href="{{ route('hr.assignments') }}">
                        <i class="menu-icon fa fa-user"></i> Hiring Confimation
                    </a>
                </li>
                <!-- Employees -->
                <li>
                    <a href="{{ route('pm.employee&skills') }}">
                        <i class="menu-icon fa fa-users"></i> Employees & Skills
                    </a>
                </li>


                <!-- Profile -->
                <li>
                    <a href="{{ route('hr.dashboard.profile') }}">
                        <i class="menu-icon fa fa-user"></i> My Profile
                    </a>
                </li>

                <!-- Logout -->
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="menu-icon fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
<!-- /#left-panel -->
