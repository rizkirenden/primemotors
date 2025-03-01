<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Sparepat</title>
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
                        <div class="flex items-center space-x-4 w-full">
                            <input type="text" id="search-input" placeholder="Search..."
                                class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                onkeyup="searchTable()">
                            <!-- Filter Date -->
                            <input type="date" id="date-input"
                                class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                onchange="filterByDate()">
                            <!-- Print PDF Button -->
                            <button
                                class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                <i class="fas fa-file-pdf text-black"></i> Print PDF
                            </button>
                        </div>

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
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Kode Barang</th>
                        <th class="px-4 py-2 text-left">Nama Part</th>
                        <th class="px-4 py-2 text-left">Harga Toko</th>
                        <th class="px-4 py-2 text-left">Harga Jual</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($sparepats as $sparepat)
                        <tr>
                            <td class="px-4 py-2">{{ $sparepat->id }}</td>
                            <td class="px-4 py-2">{{ $sparepat->kode_barang }}</td>
                            <td class="px-4 py-2">{{ $sparepat->nama_part }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($sparepat->harga_toko, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($sparepat->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $sparepat->jumlah }}</td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full"
                                    onclick="toggleDescription({{ $sparepat->id }})">
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <!-- Action Icons -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal({{ $sparepat->id }}, '{{ $sparepat->kode_barang }}','{{ $sparepat->nama_part }}','{{ $sparepat->stn }}','{{ $sparepat->tipe }}','{{ $sparepat->merk }}','{{ $sparepat->harga_toko }}','{{ $sparepat->harga_jual }}','{{ $sparepat->jumlah }}')">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('datasparepat.destroy', $sparepat->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <!-- Detail Row -->
                        <tr id="desc-{{ $sparepat->id }}" class="hidden description-row">
                            <td colspan="12">
                                <div class="description-container">
                                    <div>
                                        <strong>STN:</strong> {{ $sparepat->stn }}<br>
                                        <strong>Tipe:</strong> {{ $sparepat->tipe }}<br>
                                        <strong>Merk:</strong> {{ $sparepat->merk }}<br>
                                    </div>
                                    <div>
                                        <strong>Jumlah:</strong> {{ $sparepat->jumlah }}<br>
                                    </div>
                                    <div>
                                        <strong>Harga Toko:</strong> Rp
                                        {{ number_format($sparepat->harga_toko, 0, ',', '.') }}<br>
                                        <strong>Kode Barang:</strong> {{ $sparepat->kode_barang }}<br>
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
                    @if ($sparepats->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $sparepats->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $sparepats->lastPage(); $i++)
                        <a href="{{ $sparepats->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $sparepats->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($sparepats->hasMorePages())
                        <a href="{{ $sparepats->nextPageUrl() }}"
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
                            oninput="formatCurrency(this)">
                    </div>
                    <div>
                        <label for="harga_toko">Harga Jual</label>
                        <input type="text" id="harga_jual" name="harga_jual" required
                            oninput="formatCurrency(this)">
                    </div>
                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" value="0" readonly>
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
                        <label for="harga_toko-edit">Harga Jual</label>
                        <input type="text" id="harga_jual-edit" name="harga_jual" required
                            oninput="formatCurrency(this)">
                    </div>
                    <div>
                        <label for="jumlah-edit">Jumlah</label>
                        <input type="number" id="jumlah-edit" name="jumlah" required readonly>
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
            let hargaJualInput = document.getElementById('harga_jual');

            hargaTokoInput.value = hargaTokoInput.getAttribute('data-raw-value');
            hargaJualInput.value = hargaJualInput.getAttribute('data-raw-value');
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
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let found = false;
                for (let i = 0; i < cells.length; i++) {
                    let cell = cells[i];
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                        break;
                    }
                }
                row.style.display = found ? "" : "none";
            });
        }

        // Filter by date
        function filterByDate() {
            let input = document.getElementById("date-input");
            let filter = input.value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let date = cells[6] ? cells[6].textContent.trim() : ""; // Assuming the date is in the 6th column
                if (date) {
                    row.style.display = date.includes(filter) || filter === "" ? "" : "none";
                }
            });
        }

        function openEditModal(id, kode_barang, nama_part, stn, tipe, merk, harga_jual, harga_toko,
            jumlah) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Fill the edit form with data
            document.getElementById('kode-barang-edit').value = kode_barang;
            document.getElementById('nama-part-edit').value = nama_part;
            document.getElementById('stn-edit').value = stn;
            document.getElementById('tipe-edit').value = tipe;
            document.getElementById('merk-edit').value = merk;
            document.getElementById('harga_toko-edit').value = harga_toko;
            document.getElementById('harga_toko-edit').value = harga_jual;
            document.getElementById('jumlah-edit').value = jumlah;

            // Set the action to the edit route
            document.getElementById("inputFormEdit").action = "/datasparepat/" + id;
        }

        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }
    </script>
</body>

</html>
