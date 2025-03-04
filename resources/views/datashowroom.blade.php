<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Showroom Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        thead {
            border-bottom: 2px solid #ccc;
        }

        tbody {
            border-top: 1px solid #ccc;
        }

        td,
        th {
            font-size: 0.75rem;
            padding: 4px 8px;
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

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal.hidden {
            display: none;
        }

        .modal:not(.hidden) {
            display: flex;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            max-width: 600px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .modal-body {
            display: grid;
            gap: 10px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            border-top: 1px solid #ccc;
            padding-top: 10px;
            margin-top: 20px;
        }
    </style>
</head>

<body class="bg-black flex h-screen">
    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-3 overflow-x-auto">
        <!-- Card -->
        <div class="bg-white shadow-lg rounded-lg">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form action="{{ route('printpdfshowroom') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" id="search-input" name="search" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">

                                <!-- Filter Date -->
                                <input type="date" id="date-input" name="date"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('date') }}" onchange="filterByDate()">

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
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Nomor Polisi</th>
                        <th class="px-4 py-2 text-left">Merk/Model</th>
                        <th class="px-4 py-2 text-left">Tahun Pembuatan</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                        <th class="px-4 py-2 text-left">Foto</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($showrooms as $showroom)
                        <tr>
                            <td class="px-4 py-2">{{ $showroom->id }}</td>
                            <td class="px-4 py-2">{{ $showroom->nomor_polisi }}</td>
                            <td class="px-4 py-2">{{ $showroom->merk_model }}</td>
                            <td class="px-4 py-2">{{ $showroom->tahun_pembuatan }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($showroom->harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                @if ($showroom->foto)
                                    <img src="{{ asset('storage/' . $showroom->foto) }}" alt="Foto Showroom"
                                        width="100">
                                @else
                                    No Foto
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $showroom->status }}</td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full"
                                    onclick="toggleDescription({{ $showroom->id }})">
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <!-- Action Icons -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal(
                                    '{{ $showroom->id }}',
                                    '{{ $showroom->nomor_polisi }}',
                                    '{{ $showroom->merk_model }}',
                                    '{{ $showroom->tahun_pembuatan }}',
                                    '{{ $showroom->nomor_rangka }}',
                                    '{{ $showroom->nomor_mesin }}',
                                    '{{ $showroom->bahan_bakar }}',
                                    '{{ $showroom->kapasitas_mesin }}',
                                    '{{ $showroom->jumlah_roda }}',
                                    '{{ $showroom->harga }}',
                                    '{{ $showroom->tanggal_registrasi }}',
                                    '{{ $showroom->masa_berlaku_stnk }}',
                                    '{{ $showroom->masa_berlaku_pajak }}',
                                    '{{ $showroom->status_kepemilikan }}',
                                    '{{ $showroom->kilometer }}',
                                    '{{ $showroom->fitur_keamanan }}',
                                    '{{ $showroom->riwayat_servis }}',
                                    '{{ $showroom->status }}',
                                    '{{ $showroom->foto }}'
                                )">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('datashowroom.destroy', $showroom->id) }}" method="POST"
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
                        <tr id="desc-{{ $showroom->id }}" class="hidden description-row">
                            <td colspan="12">
                                <div class="description-container">
                                    <div>
                                        <strong>Nomor Polisi:</strong> {{ $showroom->nomor_polisi }}<br>
                                        <strong>Merk/Model:</strong> {{ $showroom->merk_model }}<br>
                                        <strong>Tahun Pembuatan:</strong> {{ $showroom->tahun_pembuatan }}<br>
                                        <strong>Nomor Rangka:</strong> {{ $showroom->nomor_rangka }}<br>
                                    </div>
                                    <div>
                                        <strong>Nomor Mesin:</strong> {{ $showroom->nomor_mesin }}<br>
                                        <strong>Bahan Bakar:</strong> {{ $showroom->bahan_bakar }}<br>
                                        <strong>Kapasitas Mesin:</strong> {{ $showroom->kapasitas_mesin }}<br>
                                        <strong>Jumlah Roda:</strong> {{ $showroom->jumlah_roda }}<br>
                                    </div>
                                    <div>
                                        <strong>Tanggal Registrasi:</strong> {{ $showroom->tanggal_registrasi }}<br>
                                        <strong>Masa Berlaku STNK:</strong> {{ $showroom->masa_berlaku_stnk }}<br>
                                        <strong>Masa Berlaku Pajak:</strong> {{ $showroom->masa_berlaku_pajak }}<br>
                                        <strong>Status Kepemilikan:</strong> {{ $showroom->status_kepemilikan }}<br>
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
                    @if ($showrooms->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $showrooms->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $showrooms->lastPage(); $i++)
                        <a href="{{ $showrooms->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $showrooms->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($showrooms->hasMorePages())
                        <a href="{{ $showrooms->nextPageUrl() }}"
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
        <div class="modal-content w-full max-w-6xl mx-auto bg-white rounded-lg shadow-xl p-6">
            <div class="modal-header flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-bold">Tambah Data Kendaraan</h2>
            </div>
            <div class="modal-body p-6">
                <form id="inputFormAdd" method="POST" action="{{ route('datashowroom.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Nomor Polisi -->
                        <div>
                            <label for="nomor-polisi" class="block text-xs">Nomor Polisi:</label>
                            <input type="text" id="nomor-polisi" name="nomor_polisi" required maxlength="10"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Merk/Model -->
                        <div>
                            <label for="merk-model" class="block text-xs">Merk/Model:</label>
                            <input type="text" id="merk-model" name="merk_model" required maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Tahun Pembuatan -->
                        <div>
                            <label for="tahun-pembuatan" class="block text-xs">Tahun Pembuatan:</label>
                            <input type="date" id="tahun-pembuatan" name="tahun_pembuatan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Nomor Rangka -->
                        <div>
                            <label for="nomor-rangka" class="block text-xs">Nomor Rangka:</label>
                            <input type="text" id="nomor-rangka" name="nomor_rangka" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Nomor Mesin -->
                        <div>
                            <label for="nomor-mesin" class="block text-xs">Nomor Mesin:</label>
                            <input type="text" id="nomor-mesin" name="nomor_mesin" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Bahan Bakar -->
                        <div>
                            <label for="bahan-bakar" class="block text-xs">Bahan Bakar:</label>
                            <input type="text" id="bahan-bakar" name="bahan_bakar" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Kapasitas Mesin -->
                        <div>
                            <label for="kapasitas-mesin" class="block text-xs">Kapasitas Mesin:</label>
                            <input type="number" id="kapasitas-mesin" name="kapasitas_mesin" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Jumlah Roda -->
                        <div>
                            <label for="jumlah-roda" class="block text-xs">Jumlah Roda:</label>
                            <input type="number" id="jumlah-roda" name="jumlah_roda" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Tanggal Registrasi -->
                        <div>
                            <label for="tanggal-registrasi" class="block text-xs">Tanggal Registrasi:</label>
                            <input type="date" id="tanggal-registrasi" name="tanggal_registrasi" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Masa Berlaku STNK -->
                        <div>
                            <label for="masa-berlaku-stnk" class="block text-xs">Masa Berlaku STNK:</label>
                            <input type="date" id="masa-berlaku-stnk" name="masa_berlaku_stnk" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Masa Berlaku Pajak -->
                        <div>
                            <label for="masa-berlaku-pajak" class="block text-xs">Masa Berlaku Pajak:</label>
                            <input type="date" id="masa-berlaku-pajak" name="masa_berlaku_pajak" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Status Kepemilikan -->
                        <div>
                            <label for="status-kepemilikan" class="block text-xs">Status Kepemilikan:</label>
                            <input type="text" id="status-kepemilikan" name="status_kepemilikan" required
                                maxlength="20" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Kilometer -->
                        <div>
                            <label for="kilometer" class="block text-xs">Kilometer:</label>
                            <input type="number" id="kilometer" name="kilometer" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Fitur Keamanan -->
                        <div>
                            <label for="fitur-keamanan" class="block text-xs">Fitur Keamanan:</label>
                            <textarea id="fitur-keamanan" name="fitur_keamanan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Riwayat Servis -->
                        <div>
                            <label for="riwayat-servis" class="block text-xs">Riwayat Servis:</label>
                            <textarea id="riwayat-servis" name="riwayat_servis" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs">Status:</label>
                            <select id="status" name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="tersedia">Tersedia</option>
                                <option value="terjual">Terjual</option>
                            </select>
                        </div>
                        <div>
                            <label for="harga" class="block text-xs">Harga:</label>
                            <input type="text" id="harga" name="harga" required
                                oninput="formatCurrency(this)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                        <!-- Foto Kendaraan -->
                        <div>
                            <label for="foto" class="block text-xs">Foto Kendaraan:</label>
                            <input type="file" id="foto" name="foto"
                                accept="image/jpeg,image/png,image/jpg" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>
                    </div>
                    <div class="modal-footer p-6 flex justify-end border-t">
                        <button type="button" onclick="closeAddModal()"
                            class="px-4 py-3 bg-black text-white rounded-md hover:bg-red-600">Batal</button>
                        <button type="submit"
                            class="px-4 py-3 bg-black text-white rounded-md hover:bg-gray-700 ml-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Form -->
    <!-- Modal Edit Form -->
    <div id="modal-edit" class="modal hidden">
        <div class="modal-content w-full max-w-6xl mx-auto bg-white rounded-lg shadow-xl p-6">
            <div class="modal-header flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-bold">Edit Data Kendaraan</h2>
                <button onclick="closeEditModal()" class="text-black hover:text-gray-700">&times;</button>
            </div>
            <div class="modal-body p-6">
                <form id="inputFormEdit" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Nomor Polisi -->
                        <div>
                            <label for="nomor-polisi-edit" class="block text-xs">Nomor Polisi:</label>
                            <input type="text" id="nomor-polisi-edit" name="nomor_polisi" required maxlength="10"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Merk/Model -->
                        <div>
                            <label for="merk-model-edit" class="block text-xs">Merk/Model:</label>
                            <input type="text" id="merk-model-edit" name="merk_model" required maxlength="255"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Tahun Pembuatan -->
                        <div>
                            <label for="tahun-pembuatan-edit" class="block text-xs">Tahun Pembuatan:</label>
                            <input type="date" id="tahun-pembuatan-edit" name="tahun_pembuatan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Nomor Rangka -->
                        <div>
                            <label for="nomor-rangka-edit" class="block text-xs">Nomor Rangka:</label>
                            <input type="text" id="nomor-rangka-edit" name="nomor_rangka" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Nomor Mesin -->
                        <div>
                            <label for="nomor-mesin-edit" class="block text-xs">Nomor Mesin:</label>
                            <input type="text" id="nomor-mesin-edit" name="nomor_mesin" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Bahan Bakar -->
                        <div>
                            <label for="bahan-bakar-edit" class="block text-xs">Bahan Bakar:</label>
                            <input type="text" id="bahan-bakar-edit" name="bahan_bakar" required maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Kapasitas Mesin -->
                        <div>
                            <label for="kapasitas-mesin-edit" class="block text-xs">Kapasitas Mesin:</label>
                            <input type="number" id="kapasitas-mesin-edit" name="kapasitas_mesin" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Jumlah Roda -->
                        <div>
                            <label for="jumlah-roda-edit" class="block text-xs">Jumlah Roda:</label>
                            <input type="number" id="jumlah-roda-edit" name="jumlah_roda" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Harga -->
                        <div>
                            <label for="harga-edit" class="block text-xs">Harga:</label>
                            <input type="text" id="harga-edit" name="harga" required
                                oninput="formatCurrency(this)"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Tanggal Registrasi -->
                        <div>
                            <label for="tanggal-registrasi-edit" class="block text-xs">Tanggal Registrasi:</label>
                            <input type="date" id="tanggal-registrasi-edit" name="tanggal_registrasi" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Masa Berlaku STNK -->
                        <div>
                            <label for="masa-berlaku-stnk-edit" class="block text-xs">Masa Berlaku STNK:</label>
                            <input type="date" id="masa-berlaku-stnk-edit" name="masa_berlaku_stnk" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Masa Berlaku Pajak -->
                        <div>
                            <label for="masa-berlaku-pajak-edit" class="block text-xs">Masa Berlaku Pajak:</label>
                            <input type="date" id="masa-berlaku-pajak-edit" name="masa_berlaku_pajak" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Status Kepemilikan -->
                        <div>
                            <label for="status-kepemilikan-edit" class="block text-xs">Status Kepemilikan:</label>
                            <input type="text" id="status-kepemilikan-edit" name="status_kepemilikan" required
                                maxlength="20" class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Kilometer -->
                        <div>
                            <label for="kilometer-edit" class="block text-xs">Kilometer:</label>
                            <input type="number" id="kilometer-edit" name="kilometer" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                        </div>

                        <!-- Fitur Keamanan -->
                        <div>
                            <label for="fitur-keamanan-edit" class="block text-xs">Fitur Keamanan:</label>
                            <textarea id="fitur-keamanan-edit" name="fitur_keamanan" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Riwayat Servis -->
                        <div>
                            <label for="riwayat-servis-edit" class="block text-xs">Riwayat Servis:</label>
                            <textarea id="riwayat-servis-edit" name="riwayat_servis" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status-edit" class="block text-xs">Status:</label>
                            <select id="status-edit" name="status" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                                <option value="tersedia">Tersedia</option>
                                <option value="terjual">Terjual</option>
                            </select>
                        </div>
                        <!-- Foto Kendaraan -->
                        <div>
                            <label for="foto-edit" class="block text-xs">Foto Kendaraan:</label>
                            <input type="file" id="foto-edit" name="foto" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md">
                            <p id="foto-preview" class="text-gray-500 mt-2"></p>
                        </div>
                        <!-- Preview Foto -->
                        <div class="mt-2" id="foto-container-edit">
                            <!-- Foto lama ditampilkan di sini -->
                        </div>
                    </div>

                    <div class="modal-footer p-6 flex justify-end border-t">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-3 bg-black text-white rounded-md hover:bg-red-600">Batal</button>
                        <button type="submit"
                            class="px-4 py-3 bg-black text-white rounded-md hover:bg-gray-700 ml-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
            if (value) {
                let formattedValue = parseInt(value, 10).toLocaleString(); // Format angka dengan pemisah ribuan
                input.value = 'Rp ' + formattedValue; // Tambahkan "Rp" di depan
                input.setAttribute('data-raw-value', value); // Simpan nilai asli (tanpa "Rp") di atribut data
            }
        }

        // Untuk form Add
        // Untuk form Add
        document.getElementById('inputFormAdd').addEventListener('submit', function(event) {
            let hargaInput = document.getElementById('harga');
            hargaInput.value = hargaInput.getAttribute('data-raw-value'); // Kirim nilai asli (tanpa "Rp")
        });

        // Untuk form Edit
        document.getElementById('inputFormEdit').addEventListener('submit', function(event) {
            let hargaInput = document.getElementById('harga-edit');
            hargaInput.value = hargaInput.getAttribute('data-raw-value'); // Kirim nilai asli (tanpa "Rp")
        });
        // JavaScript for auto-search
        function searchTable() {
            const input = document.getElementById("search-input");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            let visibleRows = 0;

            rows.forEach(row => {
                const columns = row.getElementsByTagName("td");
                let match = false;

                for (let i = 0; i < columns.length; i++) {
                    if (columns[i].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                if (match) {
                    row.style.display = "";
                    visibleRows++;
                } else {
                    row.style.display = "none";
                }
            });

            paginate(visibleRows);
        }

        // JavaScript for toggling description visibility
        function toggleDescription(rowId) {
            const descriptionRow = document.getElementById('desc-' + rowId);
            descriptionRow.classList.toggle('hidden');
        }

        // Modal functions
        function openAddModal() {
            console.log("Opening Add Modal");
            document.getElementById("modal-add").classList.remove("hidden");
        }

        function closeAddModal() {
            console.log("Closing Add Modal");
            document.getElementById("modal-add").classList.add("hidden");
        }
        // Untuk form Add
        document.getElementById('inputFormAdd').addEventListener('submit', function(event) {
            let hargaInput = document.getElementById('harga');
            hargaInput.value = hargaInput.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
        });

        // Untuk form Edit
        document.getElementById('inputFormEdit').addEventListener('submit', function(event) {
            let hargaInput = document.getElementById('harga-edit');
            hargaInput.value = hargaInput.value.replace(/\D/g, ''); // Hapus semua karakter non-angka
        });

        function openEditModal(
            id, nomor_polisi, merk_model, tahun_pembuatan, nomor_rangka, nomor_mesin,
            bahan_bakar, kapasitas_mesin, jumlah_roda, harga, tanggal_registrasi,
            masa_berlaku_stnk, masa_berlaku_pajak, status_kepemilikan, kilometer,
            fitur_keamanan, riwayat_servis, status, foto
        ) {
            console.log("Opening Edit Modal");
            document.getElementById("modal-edit").classList.remove("hidden");

            // Format harga ke dalam bentuk "Rp x.xxx.xxx"
            let formattedHarga = 'Rp ' + parseInt(harga).toLocaleString();

            // Isi form edit dengan data
            document.getElementById('nomor-polisi-edit').value = nomor_polisi;
            document.getElementById('merk-model-edit').value = merk_model;
            document.getElementById('tahun-pembuatan-edit').value = tahun_pembuatan;
            document.getElementById('nomor-rangka-edit').value = nomor_rangka;
            document.getElementById('nomor-mesin-edit').value = nomor_mesin;
            document.getElementById('bahan-bakar-edit').value = bahan_bakar;
            document.getElementById('kapasitas-mesin-edit').value = kapasitas_mesin;
            document.getElementById('jumlah-roda-edit').value = jumlah_roda;
            document.getElementById('harga-edit').value = formattedHarga;
            document.getElementById('harga-edit').setAttribute('data-raw-value', harga); // Simpan nilai asli
            document.getElementById('tanggal-registrasi-edit').value = tanggal_registrasi;
            document.getElementById('masa-berlaku-stnk-edit').value = masa_berlaku_stnk;
            document.getElementById('masa-berlaku-pajak-edit').value = masa_berlaku_pajak;
            document.getElementById('status-kepemilikan-edit').value = status_kepemilikan;
            document.getElementById('kilometer-edit').value = kilometer;
            document.getElementById('fitur-keamanan-edit').value = fitur_keamanan;
            document.getElementById('riwayat-servis-edit').value = riwayat_servis;
            document.getElementById('status-edit').value = status;

            // Set action form ke route update
            document.getElementById("inputFormEdit").action = "/datashowroom/" + id;

            // Tampilkan foto lama (jika ada)
            if (foto) {
                document.getElementById('foto-container-edit').innerHTML = `
            <img src="{{ asset('storage/') }}/${foto}" alt="Foto Kendaraan" width="100" class="rounded-md">
            <p class="text-gray-500 mt-2">Foto Lama</p>
        `;
            } else {
                document.getElementById('foto-container-edit').innerHTML = 'Tidak ada foto';
            }
        }

        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }
    </script>
</body>

</html>
