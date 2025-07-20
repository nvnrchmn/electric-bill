<div x-data="{ open: true }" class="flex min-h-screen bg-base-100">
    <!-- Sidebar -->
    <div :class="open ? 'w-64' : 'w-20'"
        class="transition-all duration-300 bg-base-300 shadow-md flex flex-col relative">
        <!-- Collapse Button -->
        <button @click="open = !open"
            class="absolute -right-3 top-4 z-10 btn btn-xs btn-circle shadow-lg bg-base-100 border border-base-content">
            <svg :class="{ 'rotate-180': !open }" class="w-4 h-4 transition-transform duration-300" fill="none"
                stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <!-- Sidebar Content -->
        <div class="flex-1 p-4 space-y-4">
            <h2 x-show="open" class="text-xl font-bold text-center text-primary">Admin Panel</h2>
            <ul class="menu text-base-content space-y-1">
                <li class="menu-title" x-show="open">Menu Navigasi</li>
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fa fa-home"></i>
                        <span x-show="open" class="ml-2">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.levels.index') }}"
                        class="{{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
                        <i class="fa fa-layer-group"></i>
                        <span x-show="open" class="ml-2">Manajemen Level</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="fa fa-users"></i>
                        <span x-show="open" class="ml-2">Manajemen User</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tarifs.index') }}"
                        class="{{ request()->routeIs('admin.tarifs.*') ? 'active' : '' }}">
                        <i class="fa fa-circle-info"></i>
                        <span x-show="open" class="ml-2">Manajemen Tarif</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pelanggans.index') }}"
                        class="{{ request()->routeIs('admin.pelanggans.*') ? 'active' : '' }}">
                        <i class="fa fa-person"></i>
                        <span x-show="open" class="ml-2">Manajemen Pelanggan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.penggunaans.index') }}"
                        class="{{ request()->routeIs('admin.penggunaans.*') ? 'active' : '' }}">
                        <i class="fa fa-bolt"></i>
                        <span x-show="open" class="ml-2">Manajemen Penggunaan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.tagihans.index') }}"
                        class="{{ request()->routeIs('admin.tagihans.*') ? 'active' : '' }}">
                        <i class="fa fa-rupiah-sign"></i>
                        <span x-show="open" class="ml-2">Manajemen Tagihan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.pembayarans.index') }}"
                        class="{{ request()->routeIs('admin.pembayarans.*') ? 'active' : '' }}">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span x-show="open" class="ml-2">Manajemen Pembayaran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="fa fa-folder-open"></i>
                        <span x-show="open" class="ml-2 text-bold">Laporan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout -->
        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-error w-full">
                    <i class="fa fa-sign-out-alt"></i>
                    <span x-show="open" class="ml-2">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Content (example placeholder) -->
    <div class="flex-1 p-6">
        @yield('content')
    </div>
</div>
