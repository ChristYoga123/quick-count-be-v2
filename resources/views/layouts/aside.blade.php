<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('logo.png') }}" class="img-fluid">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">Entry-RC</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        @foreach (config('admin_menu.admin') as $menu)
            @if (isset($menu['sub']))
                @if (Auth::user()->hasAnyPermission($menu['permission']))
                    <li class="menu-item {{ Request::is($menu['url']) ? 'active open' : '' }}">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                            <div data-i18n="{{ $menu['title'] }}">{{ $menu['title'] }}</div>
                        </a>

                        <ul class="menu-sub">
                            @foreach ($menu['sub'] as $sub)
                                @if (Auth::user()->can($sub['permission']))
                                    <li
                                        class="menu-item {{ Request::is($sub['url']) && Request::is($menu['url']) ? 'active' : '' }}">
                                        <a href="{{ url($sub['url']) }}" class="menu-link">
                                            <div data-i18n="{{ $sub['title'] }}">{{ $sub['title'] }}</div>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @endif
            @else
                @if (Auth::user()->can($menu['permission']))
                    <li class="menu-item {{ Request::is($menu['url']) ? 'active open' : '' }}">
                        <a href="{{ url($menu['url']) }}" class="menu-link">
                            <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                            <div data-i18n="{{ $menu['title'] }}">{{ $menu['title'] }}</div>
                        </a>
                    </li>
                @else
                    <li class="menu-item {{ Request::is($menu['url']) ? 'active open' : '' }}">
                        <a href="{{ url($menu['url']) }}" class="menu-link">
                            <i class="menu-icon tf-icons {{ $menu['icon'] }}"></i>
                            <div data-i18n="{{ $menu['title'] }}">{{ $menu['title'] }}</div>
                        </a>
                    </li>
                @endif
            @endif
        @endforeach
    </ul>
</aside>
