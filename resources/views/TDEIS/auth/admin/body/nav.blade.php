<style>
    .avatar-container {
        width: 40px;
        /* Adjust size as needed */
        height: 40px;
        /* Should match width for perfect circle */
        border-radius: 50%;
        overflow: hidden;
        display: inline-block;
    }

    .user-avatar {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }
</style>

<!-- Right Panel -->
<div id="right-panel" class="right-panel">
    <!-- Header-->
    <header id="header" class="header">
        <div class="top-left">
            <div class="navbar-header">
                <!-- Menu Toggle Button - now properly left-aligned -->
                <a id="menuToggle" class="menutoggle">
                    <i class="fa fa-bars"></i>
                </a>

                <!-- Logo -->
                <a class="navbar-brand" href="./">
                    <img src="{{ asset('LOGO_3.png') }}" alt="Logo" class="rounded-circle img-fluid"
                        style="max-height: 40px;">
                </a>
            </div>
        </div>
        <div class="top-right">
            <div class="header-menu">
                <div class="header-left">

                    <div class="form-inline">
                        <form class="search-form">
                            <input class="form-control mr-sm-2" type="text" placeholder="Search ..."
                                aria-label="Search">
                            <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                        </form>
                    </div>
                </div>

                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <div class="avatar-container">
                            <img class="user-avatar rounded-circle"
                                src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : asset('images/user.png') }}"
                                alt="User Avatar">
                        </div>
                    </a>



                    <div class="user-menu dropdown-menu">
                        <a class="nav-link" href="{{ route('admin.dashboard.profile') }}"><i class="fa fa-user"></i>My
                            Profile</a>
                        <a class="nav-link" href="#"><i class="fa fa-bell"></i>Notifications <span
                                class="count">13</span></a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">
                                <i class="fa fa-power-off"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- /#header -->

    <!-- Content -->
    <div class="content">
        <!-- Animated -->
        <div class="animated fadeIn">
            <!-- Widgets -->
