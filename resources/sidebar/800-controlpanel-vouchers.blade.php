@can('controlpanel.vouchers.read')
    <li class="nav-item {{ request()->routeIs('controlpanel.vouchers.index') ? 'active' : '' }}">
        <a href="{{ route('controlpanel.vouchers.index') }}" class="nav-link">
                    <span class="sidebar-icon me-3">
                        <i class="fas fa-money-bill fa-fw"></i>
                    </span>
            <span class="sidebar-text">{{ __('Vouchers') }}</span>
        </a>
    </li>
@endcan
