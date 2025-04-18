    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Data Masuk</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="icon" type="image/png" href="images/silver.PNG">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
        @include('loading')
        <div class="flex-1 p-3 overflow-x-auto">
            <h1 class="text-2xl text-white mb-4">Data Part Masuk</h1>
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button"
                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                    <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none'">
                        <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </span>
                </div>
            @endif <!-- Card -->
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

                                    <!-- Filter Date -->
                                    <input type="date" id="date-start" name="date_start"
                                        class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                        value="{{ request('date-start') }}">

                                    <input type="date" id="date-end" name="date_end"
                                        class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                        value="{{ request('date-end') }}">

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
                            <th class="px-4 py-2 text-left">Kode Barang</th>
                            <th class="px-4 py-2 text-left">Nama Part</th>
                            <th class="px-4 py-2 text-left">Stn</th>
                            <th class="px-4 py-2 text-left">Merk</th>
                            <th class="px-4 py-2 text-left">Tipe</th>
                            <th class="px-4 py-2 text-left">Tanggal Masuk</th>
                            <th class="px-4 py-2 text-left">Jumlah</th>
                            <th class="px-4 py-2 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($partMasuks as $partMasuk)
                            <tr>
                                <td class="px-4 py-2">{{ $partMasuk->kode_barang }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->nama_part }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->stn }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->merk }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->tipe }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->tanggal_masuk }}</td>
                                <td class="px-4 py-2">{{ $partMasuk->jumlah }}</td>
                                <td class="px-4 py-2">
                                    <!-- Tombol Edit -->
                                    <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                        onclick="openEditModal(
                                            '{{ $partMasuk->id }}',
                                            '{{ $partMasuk->kode_barang }}',
                                            '{{ $partMasuk->nama_part }}',
                                            '{{ $partMasuk->stn }}',
                                            '{{ $partMasuk->tipe }}',
                                            '{{ $partMasuk->merk }}',
                                            '{{ $partMasuk->tanggal_masuk }}',
                                            '{{ $partMasuk->jumlah }}'
                                        )">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <!-- Tombol Hapus -->
                                    <button onclick="confirmDelete({{ $partMasuk->id }})"
                                        class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pagination-wrapper">
                    <div class="pagination-container" id="pagination">
                        <!-- Previous Page Link -->
                        @if ($partMasuks->onFirstPage())
                            <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                        @else
                            <a href="{{ $partMasuks->previousPageUrl() }}"
                                class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                        @endif

                        <!-- Page Number Links -->
                        @for ($i = 1; $i <= $partMasuks->lastPage(); $i++)
                            <a href="{{ $partMasuks->url($i) }}"
                                class="px-4 py-2 m-1 rounded-full {{ $i == $partMasuks->currentPage() ? 'bg-black text-white' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        <!-- Next Page Link -->
                        @if ($partMasuks->hasMorePages())
                            <a href="{{ $partMasuks->nextPageUrl() }}"
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
                <form id="inputFormAdd" method="POST" action="{{ route('partmasuk.store') }}">
                    @csrf
                    <div class="modal-input-row">
                        <div style="margin-bottom: 1rem;">
                            <label for="kode_barang"
                                style="font-weight: bold; display: block; margin-bottom: 0.5rem;">Kode
                                Barang</label>
                            <select id="kode_barang" name="kode_barang" required onchange="fetchSparepartData()"
                                style="width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; font-size: 1rem;">
                                <option value="">Pilih Kode Barang</option>
                                @foreach ($spareparts as $sparepart)
                                    <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}
                                    </option>
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
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk" name="tanggal_masuk" required>
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

        <!-- Modal Add Edit -->
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
                            <label for="tanggal_masuk_edit">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk_edit" name="tanggal_masuk" required>
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

            // Fungsi untuk mengambil data sparepart berdasarkan kode barang
            function fetchSparepartData() {
                const kodeBarang = document.getElementById('kode_barang').value;

                if (kodeBarang) {
                    fetch(`/spareparts/${kodeBarang}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
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
                            alert('Terjadi kesalahan saat mengambil data sparepart.');
                        });
                } else {
                    document.getElementById('nama_part').value = '';
                    document.getElementById('stn').value = '';
                    document.getElementById('tipe').value = '';
                    document.getElementById('merk').value = '';
                }
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
            function openEditModal(id, kode_barang, nama_part, stn, tipe, merk, tanggal_masuk, jumlah) {
                document.getElementById("modal-edit").classList.remove("hidden");

                // Isi form edit dengan data
                document.getElementById('kode_barang_edit').value = kode_barang;
                document.getElementById('nama_part_edit').value = nama_part;
                document.getElementById('stn_edit').value = stn;
                document.getElementById('tipe_edit').value = tipe;
                document.getElementById('merk_edit').value = merk;
                document.getElementById('tanggal_masuk_edit').value = tanggal_masuk;
                document.getElementById('jumlah_edit').value = jumlah;

                // Set action form edit
                document.getElementById("inputFormEdit").action = `/partmasuk/${id}`;
            }

            // Fungsi untuk menutup modal edit
            function closeEditModal() {
                document.getElementById("modal-edit").classList.add("hidden");
            }
            // Fungsi untuk konfirmasi delete
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data part masuk akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deletePartMasuk(id);
                    }
                });
            }

            // Fungsi untuk menghapus data
            function deletePartMasuk(id) {
                // Buat form delete secara dinamis
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/partmasuk/${id}`;

                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfToken);

                // Tambahkan method spoofing untuk DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Tambahkan form ke body dan submit
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </body>

    </html>
