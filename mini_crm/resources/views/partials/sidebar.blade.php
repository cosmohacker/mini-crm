<div class="p-4">
    <a href="{{ route('users.index') }}" class="flex items-center gap-2">
        <img src="{{ asset('images/logos/favicon.png') }}" alt="Logo" class="h-8 w-auto" />
        <span class="text-gray-500 font-semibold">Mini CRM</span>
    </a>
</div>
<div class="scroll-sidebar" data-simplebar="">
    <nav class="w-full flex flex-col sidebar-nav px-4 mt-5">
        <ul id="sidebarnav" class="text-gray-600 text-sm">
            <li class="text-xs font-bold pb-[5px]">
                <span class="text-xs text-gray-400 font-semibold">MENU</span>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('home') || request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <i class="ti ti-users ps-2 text-2xl"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <i class="ti ti-user-circle ps-2 text-2xl"></i>
                    <span>Customers</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('tickets.*') ? 'active' : '' }}" href="{{ route('tickets.index') }}">
                    <i class="ti ti-ticket ps-2 text-2xl"></i>
                    <span>Tickets</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('files.*') ? 'active' : '' }}" href="{{ route('files.index') }}">
                    <i class="ti ti-folder ps-2 text-2xl"></i>
                    <span>File Management</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a class="sidebar-link gap-3 py-2.5 my-1 text-base flex items-center relative rounded-md text-gray-500 w-full {{ request()->routeIs('widgets.*') ? 'active' : '' }}" href="{{ route('widgets.index') }}" target="_blank">
                    <i class="ti ti-layout-grid ps-2 text-2xl"></i>
                    <span>Widget</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
