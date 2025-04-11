<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jual Part</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        thead {
            border-bottom: 2px solid #ccc;
        }

        tbody {
            border-top: 1px solid #ccc;
        }

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

        .modal {
            display: none;
        }

        .modal-content {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
        }

        .hidden {
            display: none;
        }

        .description-row td {
            padding: 0 !important;
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
                        <form action="{{ route('jualpart') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" name="search" id="search-input" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}">
                                <!-- Print PDF Button -->
                                <button type="submit"
                                    class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                    <i class="fas fa-search text-black"></i> Search
                                </button>
                            </div>
                        </form>

                        <!-- Add Data Button -->
                        <button onclick="openModal()"
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
                        <th class="px-4 py-2 text-left text-xs">Nama Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs">Kode Barang</th>
                        <th class="px-4 py-2 text-left text-xs">Nama Part</th>
                        <th class="px-4 py-2 text-left text-xs">Tanggal Keluar</th>
                        <th class="px-4 py-2 text-left text-xs">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs">Harga Jual</th>
                        <th class="px-4 py-2 text-left text-xs">Discount</th>
                        <th class="px-4 py-2 text-left text-xs">Total</th>
                        <th class="px-4 py-2 text-left text-xs">Detail</th>
                        <th class="px-4 py-2 text-left text-xs">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($jualparts as $jualpart)
                        <tr>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->nama_pelanggan }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->kode_barang }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->nama_part }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->tanggal_keluar }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->jumlah }}</td>
                            <td class="px-4 py-2 text-xs">
                                {{ 'Rp ' . number_format($jualpart->harga_jual / 1000, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2 text-xs">
                                {{ $jualpart->discount == intval($jualpart->discount) ? intval($jualpart->discount) : number_format($jualpart->discount, 2, ',', '.') }}%
                            </td>
                            <td class="px-4 py-2 text-xs">
                                {{ 'Rp ' . number_format($jualpart->total_harga_part / 1000, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full mr-2"
                                    onclick="toggleDescription({{ $jualpart->id }})">
                                    Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal(
                                        '{{ $jualpart->id }}',
                                        '{{ $jualpart->kode_barang }}',
                                        '{{ $jualpart->nama_part }}',
                                        '{{ $jualpart->stn }}',
                                        '{{ $jualpart->tipe }}',
                                        '{{ $jualpart->merk }}',
                                        '{{ $jualpart->tanggal_keluar }}',
                                        '{{ $jualpart->jumlah }}',
                                        '{{ $jualpart->harga_toko }}',
                                        '{{ $jualpart->harga_jual }}',
                                        '{{ $jualpart->margin_persen }}',
                                        '{{ $jualpart->discount }}',
                                        '{{ $jualpart->total_harga_part }}',
                                        '{{ $jualpart->status }}',
                                        '{{ $jualpart->metode_pembayaran }}',
                                        '{{ $jualpart->nama_pelanggan }}',
                                        '{{ $jualpart->tanggal_pembayaran }}',
                                        '{{ $jualpart->alamat_pelanggan }}',
                                        '{{ $jualpart->nomor_pelanggan }}'
                                    )">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jualpart.destroy', $jualpart->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <tr id="desc-{{ $jualpart->id }}" class="hidden description-row">
                            <td colspan="10">
                                <div class="description-container p-4">
                                    <div class="grid grid-cols-3 gap-6">
                                        <div><strong>Alamat Pelanggan:</strong> {{ $jualpart->alamat_pelanggan }}</div>
                                        <div><strong>No Telp:</strong> {{ $jualpart->nomor_pelanggan }}</div>
                                        <div><strong>STN:</strong> {{ $jualpart->stn }}</div>

                                        <div><strong>MERK:</strong> {{ $jualpart->merk }}</div>
                                        <div><strong>Tipe:</strong> {{ $jualpart->tipe }}</div>
                                        <div><strong>Harga Toko:</strong>
                                            {{ 'Rp ' . number_format($jualpart->harga_toko / 1000, 0, ',', '.') }}
                                        </div>

                                        <div><strong>Metode Pembayaran:</strong> {{ $jualpart->metode_pembayaran }}
                                        </div>
                                        <div><strong>Tanggal Pembayaran:</strong> {{ $jualpart->tanggal_pembayaran }}
                                        </div>
                                        <div><strong>Margin:</strong> {{ $jualpart->margin_persen }}%</div>
                                        @foreach ($jualpart->partkeluar as $part)
                                            <div><strong>Status Part Keluar:</strong> {{ $part->status }}</div>
                                        @endforeach
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
                    @if ($jualparts->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $jualparts->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $jualparts->lastPage(); $i++)
                        <a href="{{ $jualparts->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white
                @if ($jualparts->currentPage() == $i) active @endif">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($jualparts->hasMorePages())
                        <a href="{{ $jualparts->nextPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Next</a>
                    @else
                        <span class="disabled m-1 px-4 py-2 rounded-full">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding data -->
    <div id="modal"
        class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="modal-content bg-white p-6 rounded-lg max-w-4xl w-full">
            <h2 class="text-center text-xl font-semibold mb-4">Tambah Data Jual Part</h2>
            <form action="{{ route('jualpart.store') }}" method="POST" class="w-full space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <select name="kode_barang" id="kode_barang"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        onchange="fetchSparepartData()">
                        <option value="">Pilih Kode Barang</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="nama_part" name="nama_part" placeholder="Nama Part"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="stn" name="stn" placeholder="STN"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="tipe" name="tipe" placeholder="Tipe"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="merk" name="merk" placeholder="Merk"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="date" name="tanggal_keluar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <input type="number" name="jumlah" id="jumlah" placeholder="Jumlah"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required min="1"
                        oninput="calculateTotal()">
                    <input type="text" id="harga_toko" name="harga_toko" placeholder="Harga Toko"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="margin_persen" name="margin_persen" placeholder="Margin Persen"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="harga_jual" name="harga_jual" placeholder="Harga Jual"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="number" name="discount" id="discount" placeholder="Discount (%)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        oninput="calculateTotal()">
                    <input type="text" name="total_harga_part" id="total_harga_part"
                        placeholder="Total Harga Part" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required readonly>
                    <select name="metode_pembayaran" id="metode_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                        <option value="">Metode Pembayaran</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Kredit">Kredit</option>
                        <option value="Bank_Transfer">Bank Transfer</option>
                    </select>
                    <input type="text" name="nama_pelanggan" placeholder="Nama Pelanggan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <input type="text" name="alamat_pelanggan" placeholder="Alamat Pelanggan"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <input type="text" name="nomor_pelanggan" placeholder="Nomor Telepon"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <input type="date" name="tanggal_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700 mr-2">Tambah
                        Data</button>
                    <button type="button" onclick="closeModal()"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-red-700 ml-2">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal for Editing Data -->
    <div id="edit-modal"
        class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="modal-content bg-white p-6 rounded-lg max-w-4xl w-full">
            <h2 class="text-center text-xl font-semibold mb-4">Edit Data Jual Part</h2>
            <form method="POST" id="edit-form" class="w-full space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <select name="kode_barang" id="edit-kode_barang"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        onchange="fetchSparepartDataEdit()">
                        <option value="">Pilih Kode Barang</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="edit-nama_part" name="nama_part" placeholder="Nama Part"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="edit-stn" name="stn" placeholder="STN"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="edit-tipe" name="tipe" placeholder="Tipe"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="edit-merk" name="merk" placeholder="Merk"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="date" id="edit-tanggal_keluar" name="tanggal_keluar"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <input type="number" id="edit-jumlah" name="jumlah" placeholder="Jumlah"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required min="1"
                        oninput="calculateTotalEdit()">
                    <input type="text" id="edit-harga_toko" name="harga_toko" placeholder="Harga Toko"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="edit-margin_persen" name="margin_persen" placeholder="Margin Persen"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="edit-harga_jual" name="harga_jual" placeholder="Harga Jual"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="number" id="edit-discount" name="discount" placeholder="Discount (%)"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        oninput="calculateTotalEdit()">
                    <input type="text" id="edit-total_harga_part" name="total_harga_part"
                        placeholder="Total Harga Part" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required readonly>
                    <select name="metode_pembayaran" id="edit-metode_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                        <option value="">Metode Pembayaran</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Kredit">Kredit</option>
                        <option value="Bank_Transfer">Bank Transfer</option>
                    </select>
                    <input type="text" id="edit-nama_pelanggan" name="nama_pelanggan"
                        placeholder="Nama Pelanggan" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required>
                    <input type="text" id="edit-alamat_pelanggan" name="alamat_pelanggan"
                        placeholder="Alamat Pelanggan" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required>
                    <input type="text" id="edit-nomor_pelanggan" name="nomor_pelanggan"
                        placeholder="Nomor Telepon" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required>
                    <input type="date" id="edit-tanggal_pembayaran" name="tanggal_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700 mr-2">Update
                        Data</button>
                    <button type="button" onclick="closeEditModal()"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-red-700 ml-2">Close</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        function openModal() {
            document.getElementById("modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("modal").classList.add("hidden");
        }

        function toggleDescription(rowId) {
            const descriptionRow = document.getElementById('desc-' + rowId);
            descriptionRow.classList.toggle('hidden');
        }

        // Search function (client-side)
        document.getElementById("search-input").addEventListener("keyup", function() {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr:not(.description-row)");

            rows.forEach(row => {
                const cells = row.getElementsByTagName("td");
                let match = false;

                Array.from(cells).forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        match = true;
                    }
                });

                row.style.display = match ? "" : "none";
                // Hide the description row if main row is hidden
                const descRow = document.getElementById('desc-' + row.dataset.id);
                if (descRow) {
                    descRow.style.display = match ? "" : "none";
                }
            });
        });

        function formatRupiah(angka) {
            const angkaString = angka.toString().replace(/\D/g, '');
            const angkaNumber = parseInt(angkaString) || 0;
            return 'Rp ' + angkaNumber.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function removeFormatRupiah(angka) {
            return angka.replace(/\D/g, '');
        }

        function calculateTotal() {
            const hargaJual = parseFloat(removeFormatRupiah(document.getElementById('harga_jual').value));
            const discountPersen = parseFloat(document.getElementById('discount').value) || 0;
            const jumlah = parseFloat(document.getElementById('jumlah').value) || 1;

            if (!isNaN(hargaJual)) {
                let totalHargaPart = hargaJual * jumlah;

                if (!isNaN(discountPersen)) {
                    const discountAmount = (totalHargaPart * discountPersen) / 100;
                    totalHargaPart -= discountAmount;
                }

                totalHargaPart = Math.max(totalHargaPart, 0);
                document.getElementById('total_harga_part').value = formatRupiah(totalHargaPart.toString());
            }
        }

        function calculateTotalEdit() {
            const hargaJual = parseFloat(removeFormatRupiah(document.getElementById('edit-harga_jual').value));
            const discountPersen = parseFloat(document.getElementById('edit-discount').value) || 0;
            const jumlah = parseFloat(document.getElementById('edit-jumlah').value) || 1;

            if (!isNaN(hargaJual)) {
                let totalHargaPart = hargaJual * jumlah;

                if (!isNaN(discountPersen)) {
                    const discountAmount = (totalHargaPart * discountPersen) / 100;
                    totalHargaPart -= discountAmount;
                }

                totalHargaPart = Math.max(totalHargaPart, 0);
                document.getElementById('edit-total_harga_part').value = formatRupiah(totalHargaPart.toString());
            }
        }

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
                            document.getElementById('harga_toko').value = formatRupiah(data.harga_toko.toString());
                            document.getElementById('margin_persen').value = data.margin_persen;
                            document.getElementById('harga_jual').value = formatRupiah(data.harga_jual.toString());
                            calculateTotal();
                        } else {
                            alert('Kode barang tidak ditemukan!');
                            clearForm();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data sparepart.');
                        clearForm();
                    });
            } else {
                clearForm();
            }
        }

        function fetchSparepartDataEdit() {
            const kodeBarang = document.getElementById('edit-kode_barang').value;

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
                            document.getElementById('edit-nama_part').value = data.nama_part;
                            document.getElementById('edit-stn').value = data.stn;
                            document.getElementById('edit-tipe').value = data.tipe;
                            document.getElementById('edit-merk').value = data.merk;
                            document.getElementById('edit-harga_toko').value = formatRupiah(data.harga_toko.toString());
                            document.getElementById('edit-margin_persen').value = data.margin_persen;
                            document.getElementById('edit-harga_jual').value = formatRupiah(data.harga_jual.toString());
                            calculateTotalEdit();
                        } else {
                            alert('Kode barang tidak ditemukan!');
                            clearEditForm();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data sparepart.');
                        clearEditForm();
                    });
            } else {
                clearEditForm();
            }
        }

        function clearForm() {
            document.getElementById('nama_part').value = '';
            document.getElementById('stn').value = '';
            document.getElementById('tipe').value = '';
            document.getElementById('merk').value = '';
            document.getElementById('harga_toko').value = '';
            document.getElementById('margin_persen').value = '';
            document.getElementById('harga_jual').value = '';
            document.getElementById('total_harga_part').value = '';
        }

        function clearEditForm() {
            document.getElementById('edit-nama_part').value = '';
            document.getElementById('edit-stn').value = '';
            document.getElementById('edit-tipe').value = '';
            document.getElementById('edit-merk').value = '';
            document.getElementById('edit-harga_toko').value = '';
            document.getElementById('edit-margin_persen').value = '';
            document.getElementById('edit-harga_jual').value = '';
            document.getElementById('edit-total_harga_part').value = '';
        }

        function openEditModal(
            id,
            kode_barang,
            nama_part,
            stn,
            tipe,
            merk,
            tanggal_keluar,
            jumlah,
            harga_toko,
            harga_jual,
            margin_persen,
            discount,
            total_harga_part,
            status,
            metode_pembayaran,
            nama_pelanggan,
            tanggal_pembayaran,
            alamat_pelanggan,
            nomor_pelanggan
        ) {
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-kode_barang").value = kode_barang;
            document.getElementById("edit-nama_part").value = nama_part;
            document.getElementById("edit-stn").value = stn;
            document.getElementById("edit-tipe").value = tipe;
            document.getElementById("edit-merk").value = merk;
            document.getElementById("edit-tanggal_keluar").value = tanggal_keluar;
            document.getElementById("edit-jumlah").value = jumlah;
            document.getElementById("edit-harga_toko").value = formatRupiah(harga_toko);
            document.getElementById("edit-harga_jual").value = formatRupiah(harga_jual);
            document.getElementById("edit-margin_persen").value = margin_persen;
            document.getElementById("edit-discount").value = discount;
            document.getElementById("edit-total_harga_part").value = formatRupiah(total_harga_part);
            document.getElementById("edit-metode_pembayaran").value = metode_pembayaran;
            document.getElementById("edit-nama_pelanggan").value = nama_pelanggan;
            document.getElementById("edit-alamat_pelanggan").value = alamat_pelanggan;
            document.getElementById("edit-nomor_pelanggan").value = nomor_pelanggan;
            document.getElementById("edit-tanggal_pembayaran").value = tanggal_pembayaran;

            document.getElementById("edit-form").action = "/jualpart/" + id;
            document.getElementById("edit-modal").classList.remove("hidden");
        }

        function closeEditModal() {
            document.getElementById("edit-modal").classList.add("hidden");
        }

        // Format input harga saat form submit
        document.querySelector('form').addEventListener('submit', function(event) {
            const hargaFields = ['harga_toko', 'harga_jual', 'total_harga_part'];

            hargaFields.forEach(field => {
                if (document.getElementById(field)) {
                    const value = document.getElementById(field).value;
                    document.getElementById(field).value = removeFormatRupiah(value);
                }
            });
        });

        // Format input harga saat edit form submit
        document.getElementById('edit-form').addEventListener('submit', function(event) {
            const hargaFields = ['edit-harga_toko', 'edit-harga_jual', 'edit-total_harga_part'];

            hargaFields.forEach(field => {
                if (document.getElementById(field)) {
                    const value = document.getElementById(field).value;
                    document.getElementById(field).value = removeFormatRupiah(value);
                }
            });
        });
    </script>
</body>

</html>
