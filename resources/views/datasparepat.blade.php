<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Sparepat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="images/silver.PNG">
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
    @include('loading')
    <div class="flex-1 p-3 overflow-x-auto">
        <h1 class="text-2xl text-white mb-4">Data Sparepart</h1>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3"
                    onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20">
                        <title>Close</title>
                        <path
                            d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
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
        @endif
        <!-- Card -->
        <div class="bg-white shadow-lg rounded-lg ">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form id="print-pdf-form" action="{{ route('printpdfdatasparepat') }}" method="GET"
                            target="_blank">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" name="search" id="search-input" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">

                                <!-- Print PDF Button -->
                                <button type="button" onclick="printPDF()"
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
                        <th class="px-4 py-2 text-left">Harga Toko</th>
                        <th class="px-4 py-2 text-left">Margin Keuntungan</th>
                        <th class="px-4 py-2 text-left">Harga Jual</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($sparepats as $sparepat)
                        <tr>

                            <td class="px-4 py-2">{{ $sparepat->kode_barang }}</td>
                            <td class="px-4 py-2">{{ $sparepat->nama_part }}</td>
                            <td class="px-4 py-2">{{ $sparepat->stn }}</td>
                            <td class="px-4 py-2">{{ $sparepat->merk }}</td>
                            <td class="px-4 py-2">{{ $sparepat->tipe }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($sparepat->harga_toko, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ number_format($sparepat->margin_persen, 0, ',', '.') }}%</td>
                            <td class="px-4 py-2">Rp {{ number_format($sparepat->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $sparepat->jumlah }}</td>
                            <td class="px-4 py-2">
                                <!-- Action Icons -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal({{ $sparepat->id }}, '{{ $sparepat->kode_barang }}', '{{ $sparepat->nama_part }}', '{{ $sparepat->stn }}', '{{ $sparepat->tipe }}', '{{ $sparepat->merk }}', '{{ $sparepat->harga_toko }}', '{{ $sparepat->harga_jual }}', '{{ $sparepat->jumlah }}', '{{ str_replace('%', '', $sparepat->margin_persen) }}')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $sparepat->id }})"
                                    class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        <!-- Detail Row -->
                        <tr id="desc-{{ $sparepat->id }}" class="hidden description-row">
                            <td colspan="12">
                                <div class="description-container">
                                    <div style="display: flex; gap: 20px;">
                                        <!-- Tambahkan display: flex dan gap untuk jarak -->
                                        <div><strong>STN:</strong> {{ $sparepat->stn }}</div>
                                        <div><strong>Tipe:</strong> {{ $sparepat->tipe }}</div>
                                        <div><strong>Merk:</strong> {{ $sparepat->merk }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Section -->
            <div class="pagination-wrapper">
                <div class="pagination-container" id="pagination">
                    <!-- Previous Page Link -->
                    <!-- Di bagian pagination -->
                    @if ($sparepats->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $sparepats->previousPageUrl() }}&search={{ request('search') }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    @for ($i = 1; $i <= $sparepats->lastPage(); $i++)
                        <a href="{{ $sparepats->url($i) }}?search={{ request('search') }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $sparepats->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    @if ($sparepats->hasMorePages())
                        <a href="{{ $sparepats->nextPageUrl() }}&search={{ request('search') }}"
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
            <form id="inputFormAdd" method="POST" action="{{ route('datasparepat.store') }}">
                @csrf
                <div class="modal-input-row">
                    <div>
                        <label for="kode-barang">Kode Barang</label>
                        <input type="text" id="kode_barang" name="kode_barang" required>
                    </div>
                    <div>
                        <label for="nama-part">Nama Part</label>
                        <input type="text" id="nama_part" name="nama_part" required>
                    </div>
                    <div>
                        <label for="stn">STN</label>
                        <input type="text" id="stn" name="stn" required>
                    </div>
                    <div>
                        <label for="tipe">Tipe</label>
                        <input type="text" id="tipe" name="tipe" required>
                    </div>
                    <div>
                        <label for="merk">Merk</label>
                        <input type="text" id="merk" name="merk" required>
                    </div>
                    <div>
                        <label for="harga_toko">Harga Toko</label>
                        <input type="text" id="harga_toko" name="harga_toko" required
                            oninput="formatCurrency(this); calculateHargaJual()" placeholder="Masukkan Harga Toko">
                    </div>
                    <div>
                        <label for="margin_persen">Margin Persen (%)</label>
                        <input type="text" id="margin_persen" name="margin_persen" required
                            oninput="calculateHargaJual()" placeholder="Masukkan Margin Persen">
                    </div>
                    <div>
                        <label for="harga_jual">Harga Jual</label>
                        <input type="text" id="harga_jual" name="harga_jual" required readonly
                            placeholder="Harga Jual Akan Dihitung Otomatis">
                        <!-- Hidden input to send the value to the server -->
                        <input type="hidden" id="harga_jual_hidden" name="harga_jual">
                    </div>
                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" value="0" disabled>
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

    <!-- Modal Edit Form -->
    <div id="modal-edit" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data</h2>
            <form id="inputFormEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-input-row">
                    <div>
                        <label for="kode-barang-edit">Kode Barang</label>
                        <input type="text" id="kode-barang-edit" name="kode_barang" required>
                    </div>
                    <div>
                        <label for="nama-part-edit">Nama Part</label>
                        <input type="text" id="nama-part-edit" name="nama_part" required>
                    </div>
                    <div>
                        <label for="stn-edit">STN</label>
                        <input type="text" id="stn-edit" name="stn" required>
                    </div>
                    <div>
                        <label for="tipe-edit">Tipe</label>
                        <input type="text" id="tipe-edit" name="tipe" required>
                    </div>
                    <div>
                        <label for="merk-edit">Merk</label>
                        <input type="text" id="merk-edit" name="merk" required>
                    </div>
                    <div>
                        <label for="harga_toko-edit">Harga Toko</label>
                        <input type="text" id="harga_toko-edit" name="harga_toko" required
                            oninput="formatCurrency(this)">
                    </div>
                    <div>
                        <label for="margin_persen-edit">Margin Persen (%)</label>
                        <input type="text" id="margin_persen-edit" name="margin_persen" required
                            onfocus="removePercent(this)" onblur="addPercent(this)"
                            placeholder="Masukkan Persentase">
                    </div>
                    <div>
                        <label for="harga_toko-edit">Harga Jual</label>
                        <input type="text" id="harga_jual-edit" name="harga_jual" required readonly
                            oninput="formatCurrency(this)">
                    </div>
                    <div>
                        <label for="jumlah-edit">Jumlah</label>
                        <input type="number" id="jumlah-edit" name="jumlah" required disabled>
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
        function printPDF() {
            // Ambil form
            var form = document.getElementById('print-pdf-form');

            // Buka form di tab baru
            form.submit();
        }
        // Fungsi untuk menghapus tanda persen saat input mendapatkan fokus
        function removePercent(input) {
            if (input.value.includes('%')) {
                input.value = input.value.replace('%', ''); // Hapus tanda persen
            }
        }

        // Fungsi untuk menambahkan tanda persen saat input kehilangan fokus
        function addPercent(input) {
            if (input.value && !input.value.includes('%')) {
                input.value += '%'; // Tambahkan tanda persen
            }
        }

        // Sebelum submit, pastikan tanda persen dihapus
        document.getElementById('inputFormAdd').addEventListener('submit', function(event) {
            let marginPersenInput = document.getElementById('margin_persen');
            marginPersenInput.value = marginPersenInput.value.replace('%', ''); // Hapus tanda persen
        });

        // Untuk form edit
        document.getElementById('inputFormEdit').addEventListener('submit', function(event) {
            let marginPersenInput = document.getElementById('margin_persen-edit');
            marginPersenInput.value = marginPersenInput.value.replace('%', ''); // Hapus tanda persen
        });

        // Fungsi untuk menghitung harga jual
        function calculateHargaJual() {
            let hargaToko = document.getElementById('harga_toko').value.replace(/\D/g, '');
            let marginPersen = document.getElementById('margin_persen').value.replace('%', '');

            hargaToko = parseFloat(hargaToko);
            marginPersen = parseFloat(marginPersen);

            if (!isNaN(hargaToko) && !isNaN(marginPersen)) {
                let hargaJual = hargaToko + (hargaToko * (marginPersen / 100));
                document.getElementById('harga_jual').value = 'Rp ' + hargaJual.toLocaleString();
                document.getElementById('harga_jual_hidden').value = hargaJual; // Set the hidden input value
            } else {
                document.getElementById('harga_jual').value = '';
                document.getElementById('harga_jual_hidden').value = ''; // Clear the hidden input value
            }
        }
        // Fungsi untuk menghitung harga jual di modal edit
        function calculateHargaJualEdit() {
            // Ambil nilai dari input harga_toko dan margin_persen di modal edit
            let hargaToko = document.getElementById('harga_toko-edit').value.replace(/\D/g, ''); // Hapus semua non-digit
            let marginPersen = document.getElementById('margin_persen-edit').value.replace('%', ''); // Hapus simbol '%'

            // Konversi nilai menjadi angka
            hargaToko = parseFloat(hargaToko);
            marginPersen = parseFloat(marginPersen);

            // Cek apakah nilai valid
            if (!isNaN(hargaToko) && !isNaN(marginPersen)) {
                // Hitung harga jual
                let hargaJual = hargaToko + (hargaToko * (marginPersen / 100));

                // Format harga jual dengan format lokal, tambahkan prefix 'Rp '
                document.getElementById('harga_jual-edit').value = 'Rp ' + hargaJual.toLocaleString();
            } else {
                // Kosongkan field jika input tidak valid
                document.getElementById('harga_jual-edit').value = '';
            }
        }

        // Tambahkan event listener untuk menghitung harga jual saat input berubah
        document.getElementById('harga_toko-edit').addEventListener('input', calculateHargaJualEdit);
        document.getElementById('margin_persen-edit').addEventListener('input', calculateHargaJualEdit);

        // Fungsi untuk memformat input sebagai mata uang
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
            if (value) {
                let formattedValue = parseInt(value, 10).toLocaleString();
                input.value = 'Rp ' + formattedValue;
                input.setAttribute('data-raw-value', value); // Simpan nilai asli (tanpa "Rp") di atribut data
            }
        }

        // Sebelum submit, pastikan nilai yang dikirim adalah nilai asli
        document.getElementById('inputFormAdd').addEventListener('submit', function(event) {
            let hargaTokoInput = document.getElementById('harga_toko');
            let marginPersenInput = document.getElementById('margin_persen');
            let hargaJualInput = document.getElementById('harga_jual');

            // Hapus "Rp" dan tanda pemisah ribuan sebelum submit
            hargaTokoInput.value = hargaTokoInput.getAttribute('data-raw-value');
            marginPersenInput.value = marginPersenInput.value.replace('%', '');
            hargaJualInput.value = hargaJualInput.value.replace(/\D/g, '');
        });
        // Modal logic for opening and closing add/edit modals
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        function toggleDescription(rowId) {
            const descriptionRow = document.getElementById('desc-' + rowId);
            descriptionRow.classList.toggle('hidden');
        }

        function searchTable() {
            let input = document.getElementById("search-input");
            let filter = input.value.toLowerCase();

            // Kirim request ke server dengan parameter search
            window.location.href = "{{ route('datasparepat') }}?search=" + encodeURIComponent(filter);
        }


        // Format nilai input saat mengisi form edit
        function openEditModal(id, kode_barang, nama_part, stn, tipe, merk, harga_toko, harga_jual, jumlah, margin_persen) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Format harga toko dan harga jual dengan "Rp"
            let formattedHargaToko = 'Rp ' + parseInt(harga_toko).toLocaleString();
            let formattedHargaJual = 'Rp ' + parseInt(harga_jual).toLocaleString();

            // Format margin_persen dengan tanda persen
            let formattedMarginPersen = margin_persen + '%';

            // Fill the edit form with data
            document.getElementById('kode-barang-edit').value = kode_barang;
            document.getElementById('nama-part-edit').value = nama_part;
            document.getElementById('stn-edit').value = stn;
            document.getElementById('tipe-edit').value = tipe;
            document.getElementById('merk-edit').value = merk;
            document.getElementById('harga_toko-edit').value = formattedHargaToko;
            document.getElementById('harga_jual-edit').value = formattedHargaJual;
            document.getElementById('jumlah-edit').value = jumlah;
            document.getElementById('margin_persen-edit').value = formattedMarginPersen;

            // Set the action to the edit route
            document.getElementById("inputFormEdit").action = "/datasparepat/" + id;
        }

        // Sebelum submit, pastikan tanda persen dihapus
        document.getElementById('inputFormEdit').addEventListener('submit', function(event) {
            let hargaTokoInput = document.getElementById('harga_toko-edit');
            let hargaJualInput = document.getElementById('harga_jual-edit');
            let marginPersenInput = document.getElementById('margin_persen-edit');

            // Hapus "Rp" dan tanda pemisah ribuan sebelum submit
            hargaTokoInput.value = hargaTokoInput.value.replace(/\D/g, '');
            hargaJualInput.value = hargaJualInput.value.replace(/\D/g, '');

            // Hapus tanda persen sebelum submit
            marginPersenInput.value = marginPersenInput.value.replace('%', '');
        });

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user mengkonfirmasi, kirim request delete
                    deleteSparepat(id);
                }
            });
        }

        function deleteSparepat(id) {
            // Buat form secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/datasparepat/${id}`;

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
