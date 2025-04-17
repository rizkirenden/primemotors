<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Jual Part</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            max-height: 80vh;
            /* Set maximum height */
            overflow-y: auto;
            /* Enable vertical scrolling */
        }

        .modal-content.large {
            max-width: 90vw;
            /* Wider for larger content */
            max-height: 90vh;
            /* Taller for larger content */
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
        <h1 class="text-2xl text-white mb-4">Data Jual Part</h1>
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
        <div class="bg-white shadow-lg rounded-lg ">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form action="{{ route('printpdfjualpart') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" name="search" id="search-input" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}">
                                <!-- Print PDF Button -->
                                <button type="submit"
                                    class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                    <i class="fas fa-file-pdf text-black"></i> Print PDF
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
                        <th class="px-4 py-2 text-left text-xs">No Invoice</th>
                        <th class="px-4 py-2 text-left text-xs">Nama Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs">Alamat Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs">No Telp Pelanggan</th>
                        <th class="px-4 py-2 text-left text-xs">Tanggal Pembayaran</th>
                        <th class="px-4 py-2 text-left text-xs">Total Harga</th>
                        <th class="px-4 py-2 text-left text-xs">Metode Pembayaran</th>
                        <th class="px-4 py-2 text-left text-xs">Detail</th>
                        <th class="px-4 py-2 text-left text-xs">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($jualparts as $jualpart)
                        <tr>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->invoice_number }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->nama_pelanggan }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->alamat_pelanggan }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->nomor_pelanggan }}</td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->tanggal_pembayaran }}</td>
                            <td class="px-4 py-2 text-xs">
                                {{ 'Rp ' . number_format($jualpart->total_transaksi, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2 text-xs">{{ $jualpart->metode_pembayaran }}</td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full mr-2"
                                    onclick="toggleDescription({{ $jualpart->id }})">
                                    Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <!-- Tombol Edit -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal({{ $jualpart->id }})">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Tombol Hapus dengan konfirmasi -->
                                <button onclick="confirmDelete({{ $jualpart->id }})"
                                    class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>

                                @php
                                    $hasPendingItems = false;
                                    foreach ($jualpart->items as $item) {
                                        if (($item->part_status ?? '') === 'pending') {
                                            $hasPendingItems = true;
                                            break;
                                        }
                                    }
                                @endphp

                                @if (!$hasPendingItems)
                                    <a href="{{ route('printpdfjualpart.perdata', $jualpart->id) }}"
                                        class="text-black hover:text-black ml-3">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        <tr id="desc-{{ $jualpart->id }}" class="hidden description-row">
                            <td colspan="10">
                                <div class="description-container p-4">
                                    @if ($jualpart->items && count($jualpart->items) > 0)
                                        <table class="w-full">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Kode Barang</th>
                                                    <th class="text-left">Nama Part</th>
                                                    <th class="text-left">Merk</th>
                                                    <th class="text-left">Tipe</th>
                                                    <th class="text-left">Tanggal Pembayaran</th>
                                                    <th class="text-left">Jumlah</th>
                                                    <th class="text-left">Harga Jual</th>
                                                    <th class="text-left">Discount</th>
                                                    <th class="text-left">Total</th>
                                                    <th class="text-left">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($jualpart->items as $item)
                                                    <tr>
                                                        <td class="px-2 py-1">{{ $item->kode_barang }}</td>
                                                        <td class="px-2 py-1">{{ $item->nama_part }}</td>
                                                        <td class="px-2 py-1">{{ $item->merk }}</td>
                                                        <td class="px-2 py-1">{{ $item->tipe }}</td>
                                                        <td class="px-2 py-1">{{ $item->tanggal_keluar }}</td>
                                                        <td class="px-2 py-1">{{ $item->jumlah }}</td>
                                                        <td class="px-2 py-1">
                                                            {{ 'Rp ' . number_format($item->harga_jual, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            {{ rtrim(rtrim(number_format($item->discount, 2, '.', ''), '0'), '.') }}%
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            {{ 'Rp ' . number_format($item->total_harga_part, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-2 py-1">
                                                            {{ $item->part_status ?? 'N/A' }}
                                                            <!-- Gunakan field yang sudah di-select -->
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <p class="text-center text-gray-500">Tidak ada item pada transaksi ini</p>
                                    @endif
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


    <div id="modal"
        class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="modal-content bg-white p-6 rounded-lg max-w-4xl w-full large">
            <h2 class="text-center text-xl font-semibold mb-4">Tambah Data Jual Part</h2>
            <form action="{{ route('jualpart.store') }}" method="POST" class="w-full space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Customer Info -->
                    <input type="date" name="tanggal_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                    <select name="metode_pembayaran" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required>
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
                </div>


                <div id="items-container" class="mt-4">
                    <div class="item-row grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <select name="items[0][kode_barang]"
                            class="kode_barang px-4 py-2 border border-gray-300 rounded-full" required
                            onchange="fetchSparepartData(this, 0)">
                            <option value="">Pilih Kode Barang</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="items[0][nama_part]" placeholder="Nama Part"
                            class="nama_part px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="text" name="items[0][stn]" placeholder="STN"
                            class="stn px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="text" name="items[0][tipe]" placeholder="Tipe"
                            class="tipe px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="text" name="items[0][merk]" placeholder="Merk"
                            class="merk px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="date" name="items[0][tanggal_keluar]"
                            class="tanggal_keluar px-4 py-2 border border-gray-300 rounded-full" required>
                        <input type="text" name="items[0][harga_toko]" placeholder="Harga Toko"
                            class="harga_toko px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="text" name="items[0][margin_persen]" placeholder="Margin %"
                            class="margin_persen px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="text" name="items[0][harga_jual]" placeholder="Harga Jual"
                            class="harga_jual px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <input type="number" name="items[0][jumlah]" placeholder="Jumlah"
                            class="jumlah px-4 py-2 border border-gray-300 rounded-full" required min="1"
                            oninput="calculateItemTotal(0)">
                        <input type="number" name="items[0][discount]" placeholder="Discount (%)"
                            class="discount px-4 py-2 border border-gray-300 rounded-full" required
                            oninput="calculateItemTotal(0)">
                        <input type="text" name="items[0][total_harga_part]" placeholder="Total"
                            class="total_harga_part px-4 py-2 border border-gray-300 rounded-full" required readonly>
                        <button type="button" onclick="removeItem(this)"
                            class="px-4 py-2 bg-red-500 text-white rounded-full">Hapus</button>
                    </div>
                </div>

                <button type="button" onclick="addItem()" class="px-4 py-2 bg-black text-white rounded-full">Tambah
                    Item</button>

                <div class="mt-4 flex justify-between">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700 mr-2">
                        Simpan Transaksi
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-red-700 ml-2">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>


    <div id="edit-modal"
        class="modal hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="modal-content bg-white p-6 rounded-lg max-w-4xl w-full large">
            <h2 class="text-center text-xl font-semibold mb-4">Edit Data Jual Part</h2>
            <form method="POST" id="edit-form" class="w-full space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">

                <!-- Customer Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <input type="date" id="edit-tanggal_pembayaran" name="tanggal_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
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
                </div>

                <!-- Items Section -->
                <div id="edit-items-container" class="mt-4">
                    <!-- Items will be added dynamically here -->
                </div>

                <button type="button" onclick="addEditItem()"
                    class="px-4 py-2 bg-black text-white rounded-full">Tambah Item</button>

                <div class="mt-4 flex justify-between">
                    <button type="submit"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700 mr-2">
                        Update Transaksi
                    </button>
                    <button type="button" onclick="closeEditModal()"
                        class="w-1/2 px-4 py-2 bg-black text-white rounded-full hover:bg-red-700 ml-2">
                        Close
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let itemCounter = 1;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'item-row grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4';
            newItem.innerHTML = `
        <select name="items[${itemCounter}][kode_barang]" class="kode_barang px-4 py-2 border border-gray-300 rounded-full" required onchange="fetchSparepartData(this, ${itemCounter})">
            <option value="">Pilih Kode Barang</option>
            @foreach ($spareparts as $sparepart)
                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
            @endforeach
        </select>
        <input type="text" name="items[${itemCounter}][nama_part]" placeholder="Nama Part" class="nama_part px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="text" name="items[${itemCounter}][stn]" placeholder="STN" class="stn px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="text" name="items[${itemCounter}][tipe]" placeholder="Tipe" class="tipe px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="text" name="items[${itemCounter}][merk]" placeholder="Merk" class="merk px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="date" name="items[${itemCounter}][tanggal_keluar]" class="tanggal_keluar px-4 py-2 border border-gray-300 rounded-full" required>
        <input type="text" name="items[${itemCounter}][harga_toko]" placeholder="Harga Toko" class="harga_toko px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="text" name="items[${itemCounter}][margin_persen]" placeholder="Margin %" class="margin_persen px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="text" name="items[${itemCounter}][harga_jual]" placeholder="Harga Jual" class="harga_jual px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <input type="number" name="items[${itemCounter}][jumlah]" placeholder="Jumlah" class="jumlah px-4 py-2 border border-gray-300 rounded-full" required min="1" oninput="calculateItemTotal(${itemCounter})">
        <input type="number" name="items[${itemCounter}][discount]" placeholder="Discount (%)" class="discount px-4 py-2 border border-gray-300 rounded-full" required oninput="calculateItemTotal(${itemCounter})">
        <input type="text" name="items[${itemCounter}][total_harga_part]" placeholder="Total" class="total_harga_part px-4 py-2 border border-gray-300 rounded-full" required readonly>
        <button type="button" onclick="removeItem(this)" class="px-4 py-2 bg-red-500 text-white rounded-full">Hapus</button>
    `;
            container.appendChild(newItem);
            itemCounter++;
        }

        function removeItem(button) {
            const itemRow = button.closest('.item-row');
            if (document.querySelectorAll('.item-row').length > 1) {
                itemRow.remove();
            } else {
                alert('Anda harus memiliki setidaknya satu item');
            }
        }

        function fetchSparepartData(selectElement, index) {
            const kodeBarang = selectElement.value;
            const itemRow = selectElement.closest('.item-row');

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
                            itemRow.querySelector('.nama_part').value = data.nama_part || '';
                            itemRow.querySelector('.stn').value = data.stn || '';
                            itemRow.querySelector('.tipe').value = data.tipe || '';
                            itemRow.querySelector('.merk').value = data.merk || '';
                            itemRow.querySelector('.harga_toko').value = formatRupiah(data.harga_toko?.toString() ||
                                '0');
                            itemRow.querySelector('.margin_persen').value = data.margin_persen || '0';
                            itemRow.querySelector('.harga_jual').value = formatRupiah(data.harga_jual?.toString() ||
                                '0');

                            // Hitung total otomatis
                            calculateItemTotal(index);
                        } else {
                            console.error('No data received');
                            clearItemFields(itemRow);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data sparepart.');
                        clearItemFields(itemRow);
                    });
            } else {
                clearItemFields(itemRow);
            }
        }

        function clearItemFields(itemRow) {
            const fields = [
                'nama_part', 'stn', 'tipe', 'merk',
                'harga_toko', 'margin_persen', 'harga_jual', 'total_harga_part'
            ];

            fields.forEach(field => {
                const element = itemRow.querySelector(`.${field}`);
                if (element) element.value = '';
            });
        }

        function calculateItemTotal(index) {
            const itemRow = document.querySelectorAll('.item-row')[index];
            if (!itemRow) return;

            const hargaJual = parseFloat(removeFormatRupiah(itemRow.querySelector('.harga_jual').value)) || 0;
            const discount = parseFloat(itemRow.querySelector('.discount').value) || 0;
            const jumlah = parseFloat(itemRow.querySelector('.jumlah').value) || 1;

            let total = hargaJual * jumlah;
            if (!isNaN(discount)) {
                total -= (total * discount) / 100;
            }

            itemRow.querySelector('.total_harga_part').value = formatRupiah(total.toString());
        }

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

        // Edit modal functions
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

        function calculateTotalEdit() {
            const hargaJual = parseFloat(removeFormatRupiah(document.getElementById('edit-harga_jual').value)) || 0;
            const discountPersen = parseFloat(document.getElementById('edit-discount').value) || 0;
            const jumlah = parseFloat(document.getElementById('edit-jumlah').value) || 1;

            let totalHargaPart = hargaJual * jumlah;
            if (!isNaN(discountPersen)) {
                totalHargaPart -= (totalHargaPart * discountPersen) / 100;
            }

            document.getElementById('edit-total_harga_part').value = formatRupiah(totalHargaPart.toString());
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

        function openEditModal(id) {
            document.getElementById("edit-modal").classList.remove("hidden");

            fetch(`/jualpart/${id}/edit`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    if (!data) throw new Error('No data received');

                    // Set basic info
                    document.getElementById('edit-id').value = data.id;
                    document.getElementById('edit-tanggal_pembayaran').value = data.tanggal_pembayaran;
                    document.getElementById('edit-metode_pembayaran').value = data.metode_pembayaran;
                    document.getElementById('edit-nama_pelanggan').value = data.nama_pelanggan;
                    document.getElementById('edit-alamat_pelanggan').value = data.alamat_pelanggan;
                    document.getElementById('edit-nomor_pelanggan').value = data.nomor_pelanggan;

                    // Clear and populate items
                    const container = document.getElementById('edit-items-container');
                    container.innerHTML = '';

                    if (data.items && data.items.length > 0) {
                        // Tampilkan SEMUA item, tidak hanya yang pending
                        data.items.forEach((item, index) => {
                            addEditItem(index, item);
                        });
                    } else {
                        addEditItem(0);
                    }

                    // Set form action
                    document.getElementById('edit-form').action = `/jualpart/${data.id}`;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengambil data.');
                    closeEditModal();
                });
        }

        function addEditItem(index = 0, itemData = null) {
            const container = document.getElementById('edit-items-container');
            const itemId = itemData ? itemData.id : `new-${index}`;
            const itemIndex = container.children.length;

            // Skip if item is approved
            if (itemData && itemData.part_status === 'approved') {
                return;
            }

            const newItem = document.createElement('div');
            newItem.className = 'item-row grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-4';
            newItem.dataset.itemId = itemId;

            newItem.innerHTML = `
        <input type="hidden" name="items[${itemIndex}][id]" value="${itemId}">
        <select name="items[${itemIndex}][kode_barang]" class="kode_barang px-4 py-2 border border-gray-300 rounded-full" required
            onchange="fetchEditSparepartData(this, ${itemIndex})">
            <option value="">Pilih Kode Barang</option>
            @foreach ($spareparts as $sparepart)
                <option value="{{ $sparepart->kode_barang }}" ${itemData && itemData.kode_barang === '{{ $sparepart->kode_barang }}' ? 'selected' : ''}>
                    {{ $sparepart->kode_barang }}
                </option>
            @endforeach
        </select>
        <input type="text" name="items[${itemIndex}][nama_part]" placeholder="Nama Part"
            class="nama_part px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? itemData.nama_part : ''}">
        <input type="text" name="items[${itemIndex}][stn]" placeholder="STN"
            class="stn px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? itemData.stn : ''}">
        <input type="text" name="items[${itemIndex}][tipe]" placeholder="Tipe"
            class="tipe px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? itemData.tipe : ''}">
        <input type="text" name="items[${itemIndex}][merk]" placeholder="Merk"
            class="merk px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? itemData.merk : ''}">
        <input type="date" name="items[${itemIndex}][tanggal_keluar]"
            class="tanggal_keluar px-4 py-2 border border-gray-300 rounded-full" required
            value="${itemData ? itemData.tanggal_keluar : ''}">
        <input type="text" name="items[${itemIndex}][harga_toko]" placeholder="Harga Toko"
            class="harga_toko px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? formatRupiah(itemData.harga_toko.toString()) : ''}">
        <input type="text" name="items[${itemIndex}][margin_persen]" placeholder="Margin %"
            class="margin_persen px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? itemData.margin_persen : ''}">
        <input type="text" name="items[${itemIndex}][harga_jual]" placeholder="Harga Jual"
            class="harga_jual px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? formatRupiah(itemData.harga_jual.toString()) : ''}">
        <input type="number" name="items[${itemIndex}][jumlah]" placeholder="Jumlah"
            class="jumlah px-4 py-2 border border-gray-300 rounded-full" required min="1"
            oninput="calculateEditItemTotal(${itemIndex})"
            value="${itemData ? itemData.jumlah : 1}">
        <input type="number" name="items[${itemIndex}][discount]" placeholder="Discount (%)"
            class="discount px-4 py-2 border border-gray-300 rounded-full" required
            oninput="calculateEditItemTotal(${itemIndex})"
            value="${itemData ? itemData.discount : 0}">
        <input type="text" name="items[${itemIndex}][total_harga_part]" placeholder="Total"
            class="total_harga_part px-4 py-2 border border-gray-300 rounded-full" required readonly
            value="${itemData ? formatRupiah(itemData.total_harga_part.toString()) : ''}">
        <button type="button" onclick="removeEditItem(this)"
            class="px-4 py-2 bg-red-500 text-white rounded-full">Hapus</button>
    `;

            container.appendChild(newItem);

            // If itemData exists, fetch sparepart data if needed
            if (itemData) {
                const itemRow = container.lastElementChild;
                itemRow.querySelector('.nama_part').value = itemData.nama_part || '';
                itemRow.querySelector('.stn').value = itemData.stn || '';
                itemRow.querySelector('.tipe').value = itemData.tipe || '';
                itemRow.querySelector('.merk').value = itemData.merk || '';
                itemRow.querySelector('.harga_toko').value = formatRupiah(itemData.harga_toko?.toString() || '0');
                itemRow.querySelector('.margin_persen').value = itemData.margin_persen || '0';
                itemRow.querySelector('.harga_jual').value = formatRupiah(itemData.harga_jual?.toString() || '0');
                itemRow.querySelector('.jumlah').value = itemData.jumlah || 1;
                itemRow.querySelector('.discount').value = itemData.discount || 0;
                itemRow.querySelector('.total_harga_part').value = formatRupiah(itemData.total_harga_part?.toString() ||
                    '0');
                itemRow.querySelector('.tanggal_keluar').value = itemData.tanggal_keluar || '';
            }
        }


        function removeEditItem(button) {
            const itemRow = button.closest('.item-row');
            if (document.querySelectorAll('#edit-items-container .item-row').length > 1) {
                itemRow.remove();
                // Re-index remaining items
                document.querySelectorAll('#edit-items-container .item-row').forEach((row, index) => {
                    Array.from(row.children).forEach(field => {
                        if (field.name && field.name.includes('items[')) {
                            field.name = field.name.replace(/items\[\d+\]/, `items[${index}]`);
                        }
                    });
                });
            } else {
                alert('Anda harus memiliki setidaknya satu item');
            }
        }

        function fetchEditSparepartData(selectElement, index) {
            const kodeBarang = selectElement.value;
            const itemRow = selectElement.closest('.item-row');

            if (kodeBarang) {
                fetch(`/spareparts/${kodeBarang}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.json();
                    })
                    .then(data => {
                        if (data) {
                            itemRow.querySelector('.nama_part').value = data.nama_part || '';
                            itemRow.querySelector('.stn').value = data.stn || '';
                            itemRow.querySelector('.tipe').value = data.tipe || '';
                            itemRow.querySelector('.merk').value = data.merk || '';
                            itemRow.querySelector('.harga_toko').value = formatRupiah(data.harga_toko?.toString() ||
                                '0');
                            itemRow.querySelector('.margin_persen').value = data.margin_persen || '0';
                            itemRow.querySelector('.harga_jual').value = formatRupiah(data.harga_jual?.toString() ||
                                '0');
                            calculateEditItemTotal(index);
                        } else {
                            console.error('No data received');
                            clearEditItemFields(itemRow);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengambil data sparepart.');
                        clearEditItemFields(itemRow);
                    });
            } else {
                clearEditItemFields(itemRow);
            }
        }

        function calculateEditItemTotal(index) {
            const itemRow = document.querySelectorAll('#edit-items-container .item-row')[index];
            if (!itemRow) return;

            const hargaJual = parseFloat(removeFormatRupiah(itemRow.querySelector('.harga_jual').value)) || 0;
            const discount = parseFloat(itemRow.querySelector('.discount').value) || 0;
            const jumlah = parseFloat(itemRow.querySelector('.jumlah').value) || 1;

            let total = hargaJual * jumlah;
            if (!isNaN(discount)) {
                total -= (total * discount) / 100;
            }

            itemRow.querySelector('.total_harga_part').value = formatRupiah(total.toString());
        }

        function closeEditModal() {
            document.getElementById("edit-modal").classList.add("hidden");
        }

        document.getElementById('edit-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // Format all currency values before submit
            document.querySelectorAll('#edit-items-container .item-row').forEach((row) => {
                const fields = ['harga_toko', 'harga_jual', 'total_harga_part'];
                fields.forEach(field => {
                    const element = row.querySelector(`.${field}`);
                    if (element) {
                        element.value = removeFormatRupiah(element.value);
                    }
                });
            });

            // Submit form
            this.submit();
        });

        // Helper functions
        function formatRupiah(angka) {
            const angkaString = angka.toString().replace(/\D/g, '');
            const angkaNumber = parseInt(angkaString) || 0;
            return 'Rp ' + angkaNumber.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function removeFormatRupiah(angka) {
            return angka.replace(/\D/g, '');
        }
        // Fungsi untuk konfirmasi delete
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data penjualan part akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // First check if there are items
                    fetch(`/jualpart/${id}/check-items`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.has_items) {
                                Swal.fire(
                                    'Gagal!',
                                    'Data Part Masih Ada, Tolong Kordinasi Ke Bengkel',
                                    'error'
                                );
                            } else {
                                deleteJualPart(id);
                            }
                        });
                }
            });
        }
        // Fungsi untuk menghapus data
        function deleteJualPart(id) {
            // Buat form delete secara dinamis
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/jualpart/${id}`;

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
