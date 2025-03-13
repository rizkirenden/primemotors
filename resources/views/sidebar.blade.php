<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil semua elemen sidebar-item yang memiliki dropdown
        const sidebarItems = document.querySelectorAll('.sidebar-item');

        sidebarItems.forEach(item => {
            const link = item.querySelector('a');
            const dropdownMenu = item.querySelector('.dropdown-menu');

            if (dropdownMenu) {
                link.addEventListener('click', function(e) {
                    e.preventDefault(); // Mencegah navigasi default
                    dropdownMenu.classList.toggle('hidden'); // Toggle dropdown
                });
            }
        });

        // Tutup dropdown saat mengklik di luar dropdown
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.sidebar-item')) {
                sidebarItems.forEach(item => {
                    const dropdownMenu = item.querySelector('.dropdown-menu');
                    if (dropdownMenu && !dropdownMenu.classList.contains('hidden')) {
                        dropdownMenu.classList.add('hidden');
                    }
                });
            }
        });
    });
</script>
<style>
    .sidebar-item {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
    }

    .sidebar-item:hover {
        transform: translateY(-7px);
        /* "timbul" effect - raises the item */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Optional: gives a shadow for a pop-up effect */
    }

    .sidebar-container {
        overflow-y: auto;
        /* Aktifkan scroll */
        scrollbar-width: none;
        /* Untuk browser Firefox */
        -ms-overflow-style: none;
        /* Untuk browser Internet Explorer dan Edge */
    }

    .sidebar-container::-webkit-scrollbar {
        display: none;
        /* Untuk browser Chrome, Safari, dan Opera */
    }
</style>
<div class="w-56 rounded-3xl bg-white text-black h-100 m-2 p-4 sidebar-container">
    <div class="flex justify-center mb-4">
        <img src="images/silver.PNG" alt="PremierMotors" class="w-36 h-36">
    </div>
    <ul>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-tachometer-alt text-sm mr-2"></i>
            <a href="{{ route('dashboard') }}" class="text-sm ">Dashboard</a>
        </li>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-tools text-sm mr-2"></i>
            <a href="{{ route('datamekanik') }}" class="text-sm ">Data Mekanik</a>
        </li>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-cogs text-sm mr-2"></i>
            <a href="#" class="text-sm flex items-center justify-between">Data Sparepart
                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </a>
            <!-- Dropdown Menu -->
            <ul class="ml-4 mt-2 hidden dropdown-menu">
                <li class="mb-2">
                    <a href="{{ route('datasparepat') }}" class="text-sm">Data</a>
                </li>
                <hr class="border-t border-gray-300 my-2">
                <li>
                <li class="mb-2">
                    <a href="{{ route('partmasuk') }}" class="text-sm">Masuk</a>
                </li>
                <hr class="border-t border-gray-300 my-2">
                <li class="mb-2">
                    <a href="{{ route('partkeluar') }}" class="text-sm">Keluar</a>
                </li>
            </ul>
        </li>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-clipboard-list text-lg mr-2"></i>
            <a href="#" class="text-sm flex items-center justify-between">Data
                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </a>
            <!-- Dropdown Menu -->
            <ul class="ml-4 mt-2 hidden dropdown-menu">
                <li class="mb-2">
                    <a href="{{ route('dataservice') }}" class="text-sm">Service</a>
                </li>
                <hr class="border-t border-gray-300 my-2"> <!-- Divider -->
                <li class="mb-2">
                    <a href="{{ route('uraianpekerjaan') }}" class="text-sm">Uraian Pekerjaan</a>
                </li>
            </ul>
        </li>

        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-file-alt text-lg mr-2"></i>
            <a href="#" class="text-sm flex items-center justify-between">Laporan
                <i class="fas fa-chevron-down text-xs ml-2"></i>
            </a>
            <!-- Dropdown Menu -->
            <ul class="ml-4 mt-2 hidden dropdown-menu">
                <li class="mb-2">
                    <a href="{{ route('laporantransaksi') }}" class="text-sm">Transaksi</a>
                </li>
                <hr class="border-t border-gray-300 my-2"> <!-- Divider -->
                <li class="mb-2">
                    <a href="{{ route('insentif') }}" class="text-sm">Insentif</a>
                </li>
            </ul>
        </li>

        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-users text-lg mr-2"></i>
            <a href="{{ route('pengguna') }}" class="text-sm ">Pengguna</a>
        </li>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-store text-lg mr-2"></i>
            <a href="{{ route('datashowroom') }}" class="text-sm">Data Showroom</a>
        </li>
        <li class="mb-4 p-2 rounded flex items-center sidebar-item">
            <i class="fas fa-sign-out-alt text-lg mr-2"></i>
            <a href="" class="text-sm"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Log Out
            </a>
            <!-- Log Out Form -->
            <form id="logout-form" action="" method="POST" style="display: none;">
                @csrf
            </form>
        </li>
    </ul>
</div>
