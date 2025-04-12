<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sidebar</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .sidebar-item {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
        }

        .sidebar-item:hover {
            transform: translateY(-7px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .sidebar-container {
            overflow-y: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .sidebar-container::-webkit-scrollbar {
            display: none;
        }

        .sidebar-item a {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
            color: inherit;
        }

        .dropdown-toggle {
            justify-content: space-between;
        }

        .dropdown-menu a {
            display: block;
            padding: 0.3rem 0.5rem;
            text-decoration: none;
            color: inherit;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
            border-radius: 0.3rem;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="w-56 rounded-3xl bg-white text-black h-100 m-2 p-4 sidebar-container">
        <div class="flex justify-center mb-4">
            <img src="images/silver.PNG" alt="PremierMotors" class="w-36 h-36">
        </div>
        <ul>
            <li class="mb-4 sidebar-item">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt text-sm mr-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="mb-4 sidebar-item">
                <a href="{{ route('datamekanik') }}">
                    <i class="fas fa-tools text-sm mr-2"></i>
                    Data Mekanik
                </a>
            </li>
            <li class="mb-4 sidebar-item relative">
                <a href="#" class="dropdown-toggle">
                    <div class="flex items-center">
                        <i class="fas fa-cogs text-sm mr-2"></i>
                        Data Sparepart
                    </div>
                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                </a>
                <ul class="ml-4 mt-2 hidden dropdown-menu">
                    <li class="mb-2">
                        <a href="{{ route('datasparepat') }}">Data</a>
                    </li>
                    <hr class="border-t border-gray-300 my-2">
                    <li class="mb-2">
                        <a href="{{ route('partmasuk') }}">Masuk</a>
                    </li>
                    <hr class="border-t border-gray-300 my-2">
                    <li class="mb-2">
                        <a href="{{ route('partkeluar') }}">Keluar</a>
                    </li>
                </ul>
            </li>
            <li class="mb-4 sidebar-item relative">
                <a href="#" class="dropdown-toggle">
                    <div class="flex items-center">
                        <i class="fas fa-clipboard-list text-lg mr-2"></i>
                        Data
                    </div>
                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                </a>
                <ul class="ml-4 mt-2 hidden dropdown-menu">
                    <li class="mb-2">
                        <a href="{{ route('dataservice') }}">Service</a>
                    </li>
                    <hr class="border-t border-gray-300 my-2">
                    <li class="mb-2">
                        <a href="{{ route('uraianpekerjaan') }}">Uraian Pekerjaan</a>
                    </li>
                    <hr class="border-t border-gray-300 my-2">
                    <li class="mb-2">
                        <a href="{{ route('jualpart') }}">Jual Part</a>
                    </li>
                </ul>
            </li>
            <li class="mb-4 sidebar-item relative">
                <a href="#" class="dropdown-toggle">
                    <div class="flex items-center">
                        <i class="fas fa-file-alt text-lg mr-2"></i>
                        Laporan
                    </div>
                    <i class="fas fa-chevron-down text-xs ml-2"></i>
                </a>
                <ul class="ml-4 mt-2 hidden dropdown-menu">
                    <li class="mb-2">
                        <a href="{{ route('laporantransaksi') }}">Transaksi</a>
                    </li>
                    <hr class="border-t border-gray-300 my-2">
                    <li class="mb-2">
                        <a href="{{ route('insentif') }}">Insentif</a>
                    </li>
                </ul>
            </li>
            <li class="mb-4 sidebar-item">
                <a href="{{ route('pengguna') }}">
                    <i class="fas fa-users text-lg mr-2"></i>
                    Pengguna
                </a>
            </li>
            <li class="mb-4 sidebar-item">
                <a href="{{ route('datashowroom') }}">
                    <i class="fas fa-store text-lg mr-2"></i>
                    Data Showroom
                </a>
            </li>
            <li class="mb-4 sidebar-item">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt text-lg mr-2"></i>
                    Log Out
                </a>
                <form id="logout-form" action="" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');

            sidebarItems.forEach(item => {
                const link = item.querySelector('.dropdown-toggle');
                const dropdownMenu = item.querySelector('.dropdown-menu');

                if (dropdownMenu && link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        dropdownMenu.classList.toggle('hidden');
                    });
                }
            });

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
</body>

</html>
