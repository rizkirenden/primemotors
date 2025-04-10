<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Uraian Pekerjaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Add borders to the table header and body */
        thead {
            border-bottom: 2px solid #ccc;
        }

        tbody {
            border-top: 1px solid #ccc;
        }

        .description-row td {
            padding: 20px 10px;
        }

        .description-container {
            display: flex;
            justify-content: space-between;
        }

        .description-container div {
            width: 30%;
        }

        .hidden {
            display: none;
        }

        /* Style pagination buttons */
        #pagination button:hover {
            background-color: #000000;
            border-color: #888;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: center;
            width: 100%;
            padding-top: 10px;
            padding-bottom: 10px;
            border-top: 2px solid #ccc;
        }

        #pagination button {
            padding: 5px 10px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 10px;
            border: 1px solid #ccc;
            background-color: white;
            text-align: center;
            font-weight: bold;
        }

        #pagination button:hover {
            background-color: #000000;
            border-color: #888;
            color: white;
        }

        #pagination .disabled {
            background-color: #f0f0f0;
            color: #ccc;
            cursor: not-allowed;
        }

        #pagination .active {
            background-color: #000000;
            color: white;
            border-color: #000000;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        /* Make the text smaller */
        td {
            font-size: 0.75rem;
            /* Small font size */
            white-space: nowrap;
            /* Prevent text from wrapping to the next line */
            padding: 4px 8px;
            /* Reduced padding for a more compact layout */
        }

        th {
            font-size: 0.75rem;
            /* Smaller font size for header */
            padding: 4px 8px;
            /* Reduced padding for header */
        }

        /* Modal Input Form Adjustments */
        .modal-input-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .modal-input-row>div {
            display: flex;
            flex-direction: column;
        }

        .modal-input-row label {
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .modal-input-row input {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.375rem;
        }
    </style>
</head>

<body class="bg-black flex h-screen">

    @include('sidebar')
    <div class="flex-1 p-3 overflow-x-auto">
        <!-- Card -->
        <div class="bg-white shadow-lg rounded-lg ">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form action="{{ route('printpdfpartmasuk') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" id="search-input" name="search" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">

                                <!-- Print PDF Button -->
                                <button type="submit"
                                    class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                    <i class="fas fa-file-pdf text-black"></i> Print PDF
                                </button>
                            </div>
                        </form>
                        <!-- Add Data Button -->
                        <button onclick="openAddModal()"
                            class="px-6 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300 ml-auto flex items-center space-x-2">
                            <i class="fas fa-plus text-black"></i>
                            <span>Tambah</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto text-black">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Jenis Pekerjaan</th>
                        <th class="px-4 py-2 text-left">Jenis Mobil</th>
                        <th class="px-4 py-2 text-left">Waktu Pengerjaan (Menit)</th>
                        <th class="px-4 py-2 text-left">Ongkos Pengerjaan</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($uraianPekerjaans as $uraian)
                        <tr>
                            <td class="px-4 py-2">{{ $uraian->jenis_pekerjaan }}</td>
                            <td class="px-4 py-2">{{ $uraian->jenis_mobil }}</td>
                            <td class="px-4 py-2">{{ $uraian->waktu_pengerjaan }} Menit</td>
                            <td class="px-4 py-2">RP {{ number_format($uraian->ongkos_pengerjaan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <!-- Tombol Edit -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal(
                                        '{{ $uraian->id }}',
                                        '{{ $uraian->jenis_pekerjaan }}',
                                        '{{ $uraian->jenis_mobil }}',
                                        '{{ $uraian->waktu_pengerjaan }}',
                                        '{{ $uraian->ongkos_pengerjaan }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('uraianpekerjaan.destroy', $uraian->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">
                <div class="pagination-container" id="pagination">
                    <!-- Previous Page Link -->
                    @if ($uraianPekerjaans->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $uraianPekerjaans->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $uraianPekerjaans->lastPage(); $i++)
                        <a href="{{ $uraianPekerjaans->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $uraianPekerjaans->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($uraianPekerjaans->hasMorePages())
                        <a href="{{ $uraianPekerjaans->nextPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Next</a>
                    @else
                        <span class="disabled m-1 px-4 py-2 rounded-full">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Form -->
    <div id="modal-add" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Tambah Data</h2>
            <form id="inputFormAdd" method="POST" action="{{ route('uraianpekerjaan.store') }}">
                @csrf
                <div class="modal-input-row">
                    <div>
                        <label for="jenis_pekerjaan">Jenis Pekerjaan</label>
                        <input type="text" id="jenis_pekerjaan" name="jenis_pekerjaan" required>
                    </div>
                    <div>
                        <label for="jenis_mobil">Jenis Mobil</label>
                        <input type="text" id="jenis_mobil" name="jenis_mobil" required>
                    </div>
                    <div>
                        <label for="waktu_pengerjaan">Waktu Pengerjaan (Menit)</label>
                        <input type="number" id="waktu_pengerjaan" name="waktu_pengerjaan" required>
                    </div>
                    <div>
                        <label for="ongkos_pengerjaan">Ongkos Pengerjaan</label>
                        <input type="text" id="ongkos_pengerjaan" name="ongkos_pengerjaan" required>
                    </div>
                </div>
                <div class="mt-4 flex space-x-4 justify-center">
                    <button type="button" onclick="closeAddModal()"
                        class="bg-black text-white hover:bg-red-700 px-4 py-2 rounded-full w-full sm:w-auto">Cancel</button>
                    <button type="submit"
                        class="bg-black text-white hover:bg-gray-700 px-4 py-2 rounded-full w-full sm:w-auto">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Add Edit -->
    <div id="modal-edit" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data</h2>
            <form id="inputFormEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-input-row">
                    <div>
                        <label for="jenis_pekerjaan_edit">Jenis Pekerjaan</label>
                        <input type="text" id="jenis_pekerjaan_edit" name="jenis_pekerjaan" required>
                    </div>
                    <div>
                        <label for="jenis_mobil_edit">Jenis Mobil</label>
                        <input type="text" id="jenis_mobil_edit" name="jenis_mobil" required>
                    </div>
                    <div>
                        <label for="waktu_pengerjaan_edit">Waktu Pengerjaan (Menit)</label>
                        <input type="number" id="waktu_pengerjaan_edit" name="waktu_pengerjaan" required>
                    </div>
                    <div>
                        <label for="ongkos_pengerjaan_edit">Ongkos Pengerjaan</label>
                        <input type="text" id="ongkos_pengerjaan_edit" name="ongkos_pengerjaan" required>
                    </div>
                </div>
                <div class="mt-4 flex space-x-4 justify-center">
                    <button type="button" onclick="closeEditModal()"
                        class="bg-black text-white hover:bg-red-700 px-4 py-2 rounded-full w-full sm:w-auto">Cancel</button>
                    <button type="submit"
                        class="bg-black text-white hover:bg-gray-700 px-4 py-2 rounded-full w-full sm:w-auto">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('ongkos_pengerjaan').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus semua karakter non-angka
            e.target.value = 'RP ' + new Intl.NumberFormat('id-ID').format(value); // Format dengan "RP"
        });

        // Format input ongkos_pengerjaan_edit saat mengetik
        document.getElementById('ongkos_pengerjaan_edit').addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9]/g, ''); // Hapus semua karakter non-angka
            e.target.value = 'RP ' + new Intl.NumberFormat('id-ID').format(value); // Format dengan "RP"
        });
        document.querySelector('form').addEventListener('submit', function(e) {
            let search = document.getElementById('search-input').value;
            let dateStart = document.getElementById('date-start').value;
            let dateEnd = document.getElementById('date-end').value;

            if (!search && !dateStart && !dateEnd) {
                e.preventDefault(); // Mencegah pengiriman form
                alert('Silakan isi kolom pencarian atau rentang tanggal terlebih dahulu.');
            }
        });
        // Fungsi untuk memfilter tabel berdasarkan rentang tanggal
        function filterByDateRange() {
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let date = cells[5] ? cells[5].textContent.trim() : ""; // Column 5 is the date
                if (date) {
                    let rowDate = new Date(date);
                    let start = new Date(startDate);
                    let end = new Date(endDate);

                    // If the row date is within the selected range, show it
                    if ((!startDate || rowDate >= start) && (!endDate || rowDate <= end)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        // Add event listeners to the date fields so the table updates when they change
        document.getElementById("date-start").addEventListener("change", filterByDateRange);
        document.getElementById("date-end").addEventListener("change", filterByDateRange);

        // Fungsi untuk mencari data di tabel
        function searchTable() {
            let input = document.getElementById("search-input");
            let filter = input.value.toLowerCase();
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let found = false;

                // Loop through all the columns of the row
                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                // Show or hide the row based on the search result
                row.style.display = found ? "" : "none";
            });
        }

        // Fungsi untuk membuka modal tambah data
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        // Fungsi untuk menutup modal tambah data
        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        // Fungsi untuk membuka modal edit dan mengisi data
        function openEditModal(id, jenis_pekerjaan, jenis_mobil, waktu_pengerjaan, ongkos_pengerjaan) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Isi form edit dengan data
            document.getElementById('jenis_pekerjaan_edit').value = jenis_pekerjaan;
            document.getElementById('jenis_mobil_edit').value = jenis_mobil;
            document.getElementById('waktu_pengerjaan_edit').value = waktu_pengerjaan;
            document.getElementById('ongkos_pengerjaan_edit').value = 'RP ' + ongkos_pengerjaan;

            // Set action form edit
            document.getElementById("inputFormEdit").action = `/uraianpekerjaan/${id}`;
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }
    </script>
</body>

</html>
