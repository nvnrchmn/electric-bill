<div x-data="{ open: true }" class="flex min-h-screen">
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
            <h2 x-show="open" class="text-xl font-bold text-center text-primary">Pelanggan Menu</h2>
            <ul class="menu text-base-content space-y-1 min-w-full">
                <li class="menu-title" x-show="open">Menu Navigasi</li>

                <li>
                    <a href="{{ route('pelanggan.dashboard') }}"
                        class="{{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                        <i class="fa-solid fa-home"></i>
                        <span x-show="open" class="ml-2">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pelanggan.penggunaan.index') }}"
                        class="{{ request()->routeIs('pelanggan.penggunaan.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-bolt"></i>
                        <span x-show="open" class="ml-2">Penggunaan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pelanggan.tagihan.index') }}"
                        class="{{ request()->routeIs('pelanggan.tagihan.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                        <span x-show="open" class="ml-2">Tagihan</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('pelanggan.pembayaran.index') }}"
                        class="{{ request()->routeIs('pelanggan.pembayaran.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-receipt"></i>
                        <span x-show="open" class="ml-2">Riwayat Tagihan</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Button -->
        <div class="p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-error w-full">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span x-show="open" class="ml-2">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-6">
        @yield('content')
    </div>
</div>
