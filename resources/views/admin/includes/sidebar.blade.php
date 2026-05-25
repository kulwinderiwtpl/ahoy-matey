@php

$menus = [
    [
        'icon' => 'fas fa-file-alt',
        'name' => 'Feed',
        'route' => 'home',
    ],
    [
        'icon' => 'far fa-compass',
        'name' => 'Explore',
        'route' => 'explore',
    ],
    [
        'icon' => 'fas fa-search',
        'name' => 'Search',
        'route' => 'test',
    ],
    [
        'icon' => 'fas fa-bell',
        'name' => 'Notifications',
        'route' => 'notifications',
    ],
    [
        'icon' => 'far fa-id-badge',
        'name' => 'Profile',
        'route' => 'profile',
    ],
];

@endphp
<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="d-flex justify-content-between align-items-center px-3 mt-3">
                <div class="sb-sidenav-menu-heading p-0">{{ auth()->user()->name }}</div>
                <ul class="navbar-nav no-arrow">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-2 rounded" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                class="fas fa-ellipsis-h text-secondary fa-fw"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            {{-- <li><a class="dropdown-item" href="#!">Administration</a></li> --}}
                            <li><a class="dropdown-item" href="{{ route('setting') }}">Account Settings</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0);" id="logout-link">Logout</a></li>
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf
                            </form>
                        </ul>
                    </li>
                </ul>
            </div>

            @foreach ($menus as $menu)
                @if ($menu['name'] == 'Search')
                    <a class="nav-link py-2" href="javascript:void(0);" id="search-link" data-bs-toggle="modal"
                        data-bs-target="#search-modal">
                        <div class="sb-nav-link-icon"><i class="{{ $menu['icon'] }}"></i></div>
                        {{ $menu['name'] }}
                    </a>
                @else
                    <a class="nav-link py-2 
                    @if (request()->route()->getName() === $menu['route'] &&
                        $menu['route'] != 'test') active @endif"
                        href="{{ route($menu['route']) }}" @if ($menu['name'] == 'Notifications')
                            id="notification-menu"
                        @endif
                        >
                        <div class="sb-nav-link-icon"><i class="{{ $menu['icon'] }}"></i></div>
                        {{ $menu['name'] }}
                        @if ($menu['name'] == 'Notifications' &&
    auth()->user()->unreadNotifications()->count() > 0)
                            <span class="badge bg-success ms-4 rounded-circle" id="notification-count">
                                {{ auth()->user()->unreadNotifications()->count() < 100
    ? auth()->user()->unreadNotifications()->count()
    : '99+' }}
                            </span>
                        @endif

                    </a>
                @endif
            @endforeach
            <a class="nav-link py-2 mt-5" href="javascript:void(0);" id="invite-btn">
                <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                Invite People
            </a>

            {{-- <a class="nav-link py-2" data-bs-toggle="modal" href="javascript:void(0);" data-bs-target="#exampleModal"
                data-whatever="@mdo">
                <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                New Collection
            </a>

            <a class="nav-link py-2" data-bs-toggle="modal" href="#exampleModal2" data-target="#exampleModal2"
                data-whatever="@mdo">
                <div class="sb-nav-link-icon"><i class="fas fa-hands-helping"></i></div>
                Help and Community
            </a> --}}
            <a class="nav-link py-2 mt-5 collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                aria-expanded="true" aria-controls="collapseLayouts">
                <div class="sb-nav-link-icon"></div>
                Spaces
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne"
                data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav pt-1">
                    @forelse (userSpaces() as $userSpace)
                        <a class="nav-link py-2" href="{{ route('show-spaces', ['id' => $userSpace->spaces->id]) }}">
                            {{ $userSpace->spaces->name }}
                        </a>
                    @empty
                        <span>No Space added yet</span>
                    @endforelse

                </nav>
            </div>
        </div>
    </div>
    <div class="sb-sidenav-footer">
        <div class="small mb-2">© Copyright {{ date('Y') }}, Textnext</div>
        <small>Powered by .com</small>
    </div>
</nav>
