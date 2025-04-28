<!-- Settings Menu -->
<li class="nav-item has-treeview {{ request()->is('admin/settings*') || request()->is('admin/terms*') ? 'menu-open' : '' }}">
    <a href="#" class="nav-link {{ request()->is('admin/settings*') || request()->is('admin/terms*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-cog"></i>
        <p>
            Settings
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin.settings.customer-care') }}" class="nav-link {{ request()->is('admin/settings/customer-care') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Customer Care</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.settings.header-announcement') }}" class="nav-link {{ request()->is('admin/settings/header-announcement') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Header Announcement</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.terms.index') }}" class="nav-link {{ request()->is('admin/terms*') ? 'active' : '' }}">
                <i class="far fa-circle nav-icon"></i>
                <p>Terms & Conditions</p>
            </a>
        </li>
        <!-- Add other settings menu items here -->
    </ul>
</li> 