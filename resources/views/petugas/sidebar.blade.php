<div x-data="{ open: true }" class="bg-base-300 shadow-md min-h-screen flex flex-col transition-all duration-300"
    :class="open ? 'w-64' : 'w-20'">
    <div>
        <div class="p-4 flex justify-between items-center">
            <h2 x-show="open" class="text-2xl font-bold text-primary"><i class="fa-solid fa-user-gear me-2"></i>Petugas
                Panel</h2>
            <button @click="open = !open" class="btn btn-sm btn-ghost text-primary">
                <i :class="open ? 'fa-solid fa-angle-left' : 'fa-solid fa-angle-right'"></i>
            </button>
        </div>

        <ul class="menu text-base-content space-y-1 p-4">
            <li class="menu-title" x-show="open"><i class="fa-solid fa-bars me-2"></i> Menu Navigasi</li>

            <li>
                <a href="{{ route('petugas.dashboard') }}"
                    class="{{ request()->routeIs('petugas.dashboard') ? 'active font-bold text-primary' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span x-show="open" class="ms-2">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.penggunaan.index') }}"
                    class="{{ request()->routeIs('petugas.penggunaan.index') ? 'active font-bold text-primary' : '' }}">
                    <i class="fa-solid fa-pen-ruler"></i>
                    <span x-show="open" class="ms-2">Catat Penggunaan Listrik</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.tagihan.index') }}"
                    class="{{ request()->routeIs('petugas.tagihan.index') ? 'active font-bold text-primary' : '' }}">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                    <span x-show="open" class="ms-2">Lihat Tagihan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('petugas.pembayaran.index') }}"
                    class="{{ request()->routeIs('petugas.pembayaran.index') ? 'active font-bold text-primary' : '' }}">
                    <i class="fa-solid fa-credit-card"></i>
                    <span x-show="open" class="ms-2">Manajemen Pembayaran</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="p-4 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-error w-full">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span x-show="open" class="ms-2">Logout</span>
            </button>
        </form>
    </div>
</div>
