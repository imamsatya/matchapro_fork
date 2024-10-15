@php
    $configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed {{ $configData['theme'] === 'dark' || $configData['theme'] === 'semi-dark' ? 'menu-dark' : 'menu-light' }} menu-accordion menu-shadow "
    data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <span class="brand-logo">

                        <img src="{{ asset('images/logo/logoFRS.png') }}" alt="Logo" height="32" />

                    </span>
                    <h2 class="brand-text" style="color: #78b34d">MatchaPro</h2>
                </a>
            </li>
            <li class="nav-item nav-toggle">
                <a class="nav-link modern-nav-toggle pe-0" data-toggle="collapse">
                    <i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i>
                    <i class="d-none d-xl-block collapse-toggle-icon font-medium-4 text-primary" data-feather="disc"
                        data-ticon="disc"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">

        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="nav-item {{ Route::currentRouteName() == 'home' ? 'active text-white' : '' }}">
                <a href="{{ route('home') }}" class="d-flex align-items-center" target="">
                    <i data-feather="home"></i>
                    <span class="menu-title text-truncate">Home</span>
                </a>
            </li>

            <li class="nav-item {{ Route::currentRouteName() == 'dashboard.index' ? 'active text-white' : '' }}">
                <a href="{{ route('dashboard.index') }}" class="d-flex align-items-center" target="">
                    <i data-feather="grid"></i>
                    <span class="menu-title text-truncate">Dashboard</span>

                </a>
            </li>

            <li
                class="nav-item {{ Route::currentRouteName() == 'progress_wilayah.index' || Route::currentRouteName() == 'progress_profiler.index' ? 'open' : '' }}">
                <a href="" class="d-flex align-items-center" target="">
                    <i data-feather="loader"></i>
                    <span class="menu-title text-truncate">Progress <br>Profiling</span>
                    <span class="badge rounded-pill badge-light-primary ms-auto me-1">2</span>
                </a>

                <ul class="menu-content">
                    <li class="">
                        <a href="{{ route('progress_wilayah.index') }}"
                            class="d-flex align-items-center {{ Route::currentRouteName() == 'progress_wilayah.index' ? 'active text-white' : '' }}"
                            target="">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">Wilayah</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('progress_profiler.index') }}"
                            class="d-flex align-items-center {{ Route::currentRouteName() == 'progress_profiler.index' ? 'active text-white' : '' }}"
                            target="">
                            <i data-feather="circle"></i>
                            <span class="menu-item text-truncate">Profiler</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item {{ Route::currentRouteName() == 'direktori_usaha.index' ? 'active text-white' : '' }}">
                <a href="{{ route('direktori_usaha.index') }}"
                    class="d-flex align-items-center {{ Route::currentRouteName() == 'direktori_usaha.index' || Route::currentRouteName() == 'direktori_usaha_profiling.index' ? 'active white-text' : '' }}""
                    target="">
                    <i data-feather="database"></i>
                    <span class="menu-title text-truncate">Direktori Usaha</span>

                </a>
            </li>
            @if (auth()->user()->getPermissionsViaRoles()->contains('name', 'view-list-usaha-profiling-user'))
                <li class="nav-item {{ Route::currentRouteName() == 'profiling.index' ? 'active text-white' : '' }}">
                    <a href="{{ route('profiling.index') }}"
                        class="d-flex align-items-center {{ Route::currentRouteName() == 'profiling.index' || Route::currentRouteName() == 'direktori_usaha_profiling.index' ? 'active white-text' : '' }}""
                        target="">
                        <i data-feather="file-text"></i>
                        <span class="menu-title text-truncate">Profiling</span>

                    </a>
                </li>
            @endif
            @if (auth()->user()->getPermissionsViaRoles()->contains('name', 'create-new-usaha-provinsi') ||
                    auth()->user()->getPermissionsViaRoles()->contains('name', 'create-new-usaha-kabkot'))
                <li
                    class="nav-item {{ Route::currentRouteName() == 'form_create_usaha.index' ? 'active text-white' : '' }}">
                    <a href="{{ route('form_create_usaha.index') }}" class="d-flex align-items-center" target="">
                        <i data-feather="file-plus"></i>
                        <span class="menu-title text-truncate">Tambah Usaha</span>
                    </a>
                </li>
            @endif
            <li
                class="nav-item {{ Route::currentRouteName() == 'profilng_mandiri.index' ? 'active text-white' : '' }}">
                <a href="{{ route('profilng_mandiri.index') }}" class="d-flex align-items-center" target="">
                    <i data-feather="database"></i>
                    <span class="menu-title text-truncate">Tabel Mandiri</span>
                </a>
            </li>

            <li class="nav-item {{ Route::currentRouteName() == 'user.list' ? 'active text-white' : '' }}">
                <a href="{{ route('user.list') }}" class="d-flex align-items-center" target="">
                    <i data-feather="users"></i>
                    <span class="menu-title text-truncate">User</span>
                </a>    
            </li>


        </ul>
    </div>
</div>
<!-- END: Main Menu-->
