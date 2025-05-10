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
                <li >
                    <a href="{{ route('pm.dashboard') }}"><i class="menu-icon fa fa-laptop"></i> PM Dashboard</a>
                </li>




                <li>
                    <a href="{{ route('pm.projects') }}"><i class="menu-icon fa fa-briefcase"></i>Project Management</a>
                </li>

                <li>
                    <a href="{{ route('pm.assignments') }}" class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="menu-icon fa fa-bell"></i>
                            <span class="ms-2">Notifications</span>
                        </div>
                        <span id="notificationCount" class="notification-count" data-count="{{ $totalAssignmentCount ?? 0 }}">
                            0
                        </span>
                    </a>
                </li>



                <style>
                    .notification-count {
                        background-color: #dc3545;
                        color: white;
                        border-radius: 50%;
                        display: inline-flex;
                        align-items: center;
                        justify-content: center;
                        min-width: 24px;
                        height: 24px;
                        padding: 0 6px;
                        font-size: 0.8em;
                        font-weight: bold;
                        transition: all 0.2s ease-in-out;
                    }
                    </style>




                <li>
                    <a href="{{ route('pm.dashboard.profile') }}"><i class="menu-icon fa fa-user"></i>My Profile</a>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countEl = document.getElementById('notificationCount');
        const finalCount = parseInt(countEl.getAttribute('data-count'));
        let current = 0;
        const speed = 20; // Faster animation

        const counter = setInterval(() => {
            current++;
            countEl.innerText = current;

            // Adjust size dynamically (optional)
            countEl.style.minWidth = (current.toString().length > 2) ? '30px' : '24px';

            if (current >= finalCount) {
                clearInterval(counter);
            }
        }, speed);
    });
    </script>
