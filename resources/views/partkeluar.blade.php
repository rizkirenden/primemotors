<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Keluar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <form action="{{ route('printpdfpartkeluar') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" id="search-input" name="search" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}">

                                <!-- Filter Date -->
                                <input type="date" id="date-start" name="date_start"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('date_start') }}">

                                <input type="date" id="date-end" name="date_end"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('date_end') }}">

                                <!-- Print PDF Button -->
                                <button type="submit"
                                    class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                    <i class="fas fa-file-pdf text-black"></i> Print PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto text-black">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Kode Barang</th>
                        <th class="px-4 py-2 text-left">Nama Part</th>
                        <th class="px-4 py-2 text-left">Stn</th>
                        <th class="px-4 py-2 text-left">Tipe</th>
                        <th class="px-4 py-2 text-left">Merk</th>
                        <th class="px-4 py-2 text-left">Tanggal Keluar</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($partKeluars as $partKeluar)
                        <tr>
                            <td class="px-4 py-2">{{ $partKeluar->kode_barang }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->nama_part }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->stn }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->tipe }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->merk }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->tanggal_keluar }}</td>
                            <td class="px-4 py-2">{{ $partKeluar->jumlah }}</td>
                            <td class="px-4 py-2">
                                @if ($partKeluar->status === 'pending')
                                    <form action="{{ route('partkeluar.approve', $partKeluar->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-500 hover:text-green-700 mr-3">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('partkeluar.cancel', $partKeluar->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500">{{ ucfirst($partKeluar->status) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <!-- Tombol Edit -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal(
                                        '{{ $partKeluar->id }}',
                                        '{{ $partKeluar->kode_barang }}',
                                        '{{ $partKeluar->nama_part }}',
                                        '{{ $partKeluar->stn }}',
                                        '{{ $partKeluar->tipe }}',
                                        '{{ $partKeluar->merk }}',
                                        '{{ $partKeluar->tanggal_keluar }}',
                                        '{{ $partKeluar->jumlah }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <form action="{{ route('partkeluar.destroy', $partKeluar->id) }}" method="POST"
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
                    @if ($partKeluars->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $partKeluars->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $partKeluars->lastPage(); $i++)
                        <a href="{{ $partKeluars->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $partKeluars->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($partKeluars->hasMorePages())
                        <a href="{{ $partKeluars->nextPageUrl() }}"
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
            <form id="inputFormAdd" method="POST" action="{{ route('partkeluar.store') }}">
                @csrf
                <div class="modal-input-row">
                    <div style="margin-bottom: 1rem;">
                        <label for="kode_barang" style="font-weight: bold; display: block; margin-bottom: 0.5rem;">Kode
                            Barang</label>
                        <select id="kode_barang" name="kode_barang" required onchange="fetchSparepartData()"
                            style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;">
                            <option value="">Pilih Kode Barang</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="nama_part">Nama Part</label>
                        <input type="text" id="nama_part" name="nama_part" readonly>
                    </div>
                    <div>
                        <label for="stn">STN</label>
                        <input type="text" id="stn" name="stn" readonly>
                    </div>
                    <div>
                        <label for="tipe">Tipe</label>
                        <input type="text" id="tipe" name="tipe" readonly>
                    </div>
                    <div>
                        <label for="merk">Merk</label>
                        <input type="text" id="merk" name="merk" readonly>
                    </div>
                    <div>
                        <label for="tanggal_keluar">Tanggal Keluar</label>
                        <input type="date" id="tanggal_keluar" name="tanggal_keluar" required>
                    </div>
                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" required>
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
    <div id="modal-edit" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data</h2>
            <form id="inputFormEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-input-row">
                    <div>
                        <label for="kode_barang_edit">Kode Barang</label>
                        <input type="text" id="kode_barang_edit" name="kode_barang" readonly>
                    </div>
                    <div>
                        <label for="nama_part_edit">Nama Part</label>
                        <input type="text" id="nama_part_edit" name="nama_part" readonly>
                    </div>
                    <div>
                        <label for="stn_edit">STN</label>
                        <input type="text" id="stn_edit" name="stn" readonly>
                    </div>
                    <div>
                        <label for="tipe_edit">Tipe</label>
                        <input type="text" id="tipe_edit" name="tipe" readonly>
                    </div>
                    <div>
                        <label for="merk_edit">Merk</label>
                        <input type="text" id="merk_edit" name="merk" readonly>
                    </div>
                    <div>
                        <label for="tanggal_keluar_edit">Tanggal Keluar</label>
                        <input type="date" id="tanggal_keluar_edit" name="tanggal_keluar" required>
                    </div>
                    <div>
                        <label for="jumlah_edit">Jumlah</label>
                        <input type="number" id="jumlah_edit" name="jumlah" required>
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
        // Fungsi untuk menampilkan pop-up error menggunakan SweetAlert
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
        // Fungsi untuk memfilter tabel berdasarkan rentang tanggal
        function filterByDateRange() {
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let date = cells[5] ? cells[5].textContent.trim() : ""; // Kolom tanggal (index 4)
                if (date) {
                    let rowDate = new Date(date);
                    let start = new Date(startDate);
                    let end = new Date(endDate);

                    // Jika tanggal dalam rentang yang dipilih, tampilkan baris
                    if ((!startDate || rowDate >= start) && (!endDate || rowDate <= end)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        }

        // Tambahkan event listener ke input tanggal
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

                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                row.style.display = found ? "" : "none";
            });
        }

        document.getElementById("search-input").addEventListener("keyup", searchTable);

        // Fungsi untuk memfilter tabel berdasarkan pencarian dan rentang tanggal
        function filterTable() {
            let search = document.getElementById("search-input").value.toLowerCase();
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let found = false;

                // Cek apakah baris cocok dengan pencarian
                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(search)) {
                        found = true;
                    }
                });

                // Cek apakah tanggal dalam rentang yang dipilih
                let date = cells[5] ? cells[5].textContent.trim() : ""; // Kolom tanggal (index 4)
                if (date) {
                    let rowDate = new Date(date);
                    let start = new Date(startDate);
                    let end = new Date(endDate);

                    if (startDate && rowDate < start) {
                        found = false;
                    }
                    if (endDate && rowDate > end) {
                        found = false;
                    }
                }

                // Tampilkan atau sembunyikan baris berdasarkan hasil filter
                row.style.display = found ? "" : "none";
            });
        }

        // Tambahkan event listener ke input pencarian dan tanggal
        document.getElementById("search-input").addEventListener("keyup", filterTable);
        document.getElementById("date-start").addEventListener("change", filterTable);
        document.getElementById("date-end").addEventListener("change", filterTable);

        // Panggil fungsi filter saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            filterTable();
        });

        function fetchSparepartData() {
            const kodeBarang = document.getElementById('kode_barang').value;

            if (kodeBarang) {
                fetch(`/spareparts/${kodeBarang}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('nama_part').value = data.nama_part;
                            document.getElementById('stn').value = data.stn;
                            document.getElementById('tipe').value = data.tipe;
                            document.getElementById('merk').value = data.merk;
                        } else {
                            alert('Kode barang tidak ditemukan!');
                            document.getElementById('nama_part').value = '';
                            document.getElementById('stn').value = '';
                            document.getElementById('tipe').value = '';
                            document.getElementById('merk').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                document.getElementById('nama_part').value = '';
                document.getElementById('stn').value = '';
                document.getElementById('tipe').value = '';
                document.getElementById('merk').value = '';
            }
        }

        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        function openEditModal(id, kode_barang, nama_part, stn, tipe, merk, tanggal_keluar, jumlah) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Isi form edit dengan data
            document.getElementById('kode_barang_edit').value = kode_barang;
            document.getElementById('nama_part_edit').value = nama_part;
            document.getElementById('stn_edit').value = stn;
            document.getElementById('tipe_edit').value = tipe;
            document.getElementById('merk_edit').value = merk;
            document.getElementById('tanggal_keluar_edit').value = tanggal_keluar;
            document.getElementById('jumlah_edit').value = jumlah;

            // Set action form edit
            document.getElementById("inputFormEdit").action = `/partkeluar/${id}`;
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }

        function showErrorPopup(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        }

        // Cek jika ada pesan error dari controller
        @if (session('error'))
            showErrorPopup("{{ session('error') }}");
        @endif
    </script>
</body>

</html>
