<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Service</title>
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
            flex-direction: column;
            gap: 20px;
        }

        .description-container>div {
            width: 100%;
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
            white-space: nowrap;
            padding: 4px 8px;
        }

        th {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        /* Modal Input Form Adjustments */
        /* Modal Container */
        .modal-container {
            max-width: 90vw;
            /* Lebar maksimum 90% dari viewport */
            max-height: 90vh;
            /* Tinggi maksimum 90% dari viewport */
            overflow-y: auto;
            /* Scroll jika konten terlalu panjang */
            padding: 1.5rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Input dan Textarea */
        .modal-input-row input,
        .modal-input-row select,
        .modal-input-row textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        /* Textarea */
        .modal-input-row textarea {
            resize: vertical;
            min-height: 100px;
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
                        <form action="{{ route('dataservice') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" name="search" id="search-input" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">
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
                        <th class="px-4 py-2 text-left">No SPK</th>
                        <th class="px-4 py-2 text-left">Tanggal</th>
                        <th class="px-4 py-2 text-left">Costumer</th>
                        <th class="px-4 py-2 text-left">Masuk</th>
                        <th class="px-4 py-2 text-left">Keluar</th>
                        <th class="px-4 py-2 text-left">No Polisi</th>
                        <th class="px-4 py-2 text-left">Mekanik</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($dataservices as $dataservice)
                        <tr>
                            <td class="px-4 py-2">{{ $dataservice->no_spk }}</td>
                            <td class="px-4 py-2">{{ $dataservice->tanggal }}</td>
                            <td class="px-4 py-2">{{ $dataservice->costumer }}</td>
                            <td class="px-4 py-2">{{ $dataservice->masuk }}</td>
                            <td class="px-4 py-2">{{ $dataservice->keluar }}</td>
                            <td class="px-4 py-2">{{ $dataservice->no_polisi }}</td>
                            <td class="px-4 py-2">{{ $dataservice->nama_mekanik }}</td>
                            <td class="px-4 py-2">{{ $dataservice->status }}</td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full"
                                    onclick="toggleDescription({{ $dataservice->id }})">
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <!-- Action Icons -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal(
        '{{ $dataservice->id }}',
        '{{ $dataservice->no_spk }}',
        '{{ $dataservice->tanggal }}',
        '{{ $dataservice->costumer }}',
        '{{ $dataservice->contact_person }}',
        '{{ $dataservice->masuk }}',
        '{{ $dataservice->keluar }}',
        '{{ $dataservice->no_polisi }}',
        '{{ $dataservice->nama_mekanik }}',
        '{{ $dataservice->tahun }}',
        '{{ $dataservice->tipe_mobile }}',
        '{{ $dataservice->warna }}',
        '{{ $dataservice->no_rangka }}',
        '{{ $dataservice->no_mesin }}',
        '{{ $dataservice->keluhan_costumer }}',
        '{{ $dataservice->kode_barang }}',
         '{{ $dataservice->stn }}',
          '{{ $dataservice->tipe }}',
           '{{ $dataservice->merk }}',
        '{{ $dataservice->nama_part }}',
        '{{ $dataservice->jumlah }}',
        '{{ $dataservice->uraian_pekerjaan }}',
        '{{ $dataservice->uraian_jasa_perbaikan }}',
        '{{ $dataservice->status }}',
        '{{ $dataservice->tanggal_keluar ?? '' }}'
    )">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dataservice.destroy', $dataservice->id) }}" method="POST"
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
                        <tr id="desc-{{ $dataservice->id }}" class="hidden description-row">
                            <td colspan="12">
                                <div class="description-container">
                                    <!-- Baris Pertama: Tahun, Tipe, Warna, No Rangka, No Mesin -->
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                        <div><strong>Tahun:</strong> {{ $dataservice->contact_person }}</div>
                                        <div><strong>Tahun:</strong> {{ $dataservice->tahun }}</div>
                                        <div><strong>Tipe:</strong> {{ $dataservice->tipe_mobile }}</div>
                                        <div><strong>Warna:</strong> {{ $dataservice->warna }}</div>
                                        <div><strong>No Rangka:</strong> {{ $dataservice->no_rangka }}</div>
                                        <div><strong>No Mesin:</strong> {{ $dataservice->no_mesin }}</div>
                                    </div>

                                    <!-- Baris Kedua: Keluhan Customer (Textarea) -->
                                    <div style="margin-bottom: 20px;">
                                        <div><strong>Keluhan Customer:</strong></div>
                                        <textarea style="width: 100%; height: 80px; resize: vertical;" readonly>{{ $dataservice->keluhan_costumer }}</textarea>
                                    </div>

                                    <!-- Baris Ketiga: Nama Part, Qty, Kode Barang (Tabel Kecil) -->
                                    <div style="margin-bottom: 20px;">
                                        <div><strong>Detail Part:</strong></div>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #000; padding: 5px;"><strong>Kode
                                                            Barang</strong></th>
                                                    <th style="border: 1px solid #000; padding: 5px;"><strong>Nama
                                                            Part</strong></th>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>STN</strong>
                                                    </th>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Tipe</strong>
                                                    </th>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Merk</strong>
                                                    </th>
                                                    <th style="border: 1px solid #000; padding: 5px;"><strong>Tanggal
                                                            Keluar</strong></th>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Jumlah</strong>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($dataservice->partkeluar->count() > 0)
                                                    @foreach ($dataservice->partkeluar as $part)
                                                        <tr>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->kode_barang }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->nama_part }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->stn }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->tipe }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->merk }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->tanggal_keluar ?? '-' }}</td>
                                                            <td style="border: 1px solid #000; padding: 5px;">
                                                                {{ $part->jumlah }}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="7"
                                                            style="border: 1px solid #000; padding: 5px; text-align: center;">
                                                            Tidak ada data part yang keluar.
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Baris Keempat: Uraian Pekerjaan (Tabel) -->
                                    <div style="margin-bottom: 20px;">
                                        <div><strong>Uraian Pekerjaan:</strong></div>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Deskripsi</strong>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid #000; padding: 5px;">
                                                        {{ $dataservice->uraian_pekerjaan }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Baris Kelima: Uraian Jasa Perbaikan (Tabel) -->
                                    <div style="margin-bottom: 20px;">
                                        <div><strong>Uraian Jasa Perbaikan:</strong></div>
                                        <table style="width: 100%; border-collapse: collapse;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Deskripsi</strong>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="border: 1px solid #000; padding: 5px;">
                                                        {{ $dataservice->uraian_jasa_perbaikan }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
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
                    @if ($dataservices->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $dataservices->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $dataservices->lastPage(); $i++)
                        <a href="{{ $dataservices->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $dataservices->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($dataservices->hasMorePages())
                        <a href="{{ $dataservices->nextPageUrl() }}"
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
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-[90vw] overflow-y-auto" style="max-height: 90vh;">
            <h2 class="text-xl font-bold mb-4">Tambah Data Service</h2>
            <form id="inputFormAdd" method="POST" action="{{ route('dataservice.store') }}">
                @csrf
                <div class="modal-input-row grid grid-cols-5 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label for="no_spk">No SPK</label>
                        <input type="text" id="no_spk" name="no_spk" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="costumer">Costumer</label>
                        <input type="text" id="costumer" name="costumer" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="contact_person">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="masuk">Masuk</label>
                        <input type="date" id="masuk" name="masuk" class="w-full p-2 border rounded"
                            required>
                    </div>

                    <!-- Baris 2 -->
                    <div>
                        <label for="keluar">Keluar</label>
                        <input type="date" id="keluar" name="keluar" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="no_polisi">No Polisi</label>
                        <input type="text" id="no_polisi" name="no_polisi" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tahun">Tahun</label>
                        <input type="text" id="tahun" name="tahun" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tipe_mobile">Tipe Mobil</label>
                        <input type="text" id="tipe_mobile" name="tipe_mobile" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <!-- Baris 3 -->
                    <div>
                        <label for="warna">Warna</label>
                        <input type="text" id="warna" name="warna" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="no_rangka">No Rangka</label>
                        <input type="text" id="no_rangka" name="no_rangka" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="no_mesin">No Mesin</label>
                        <input type="text" id="no_mesin" name="no_mesin" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="keluhan_costumer">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer" name="keluhan_costumer" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div class="col-span-5">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="w-full p-2 border rounded" required>
                            <option value="menunggu">Menunggu</option>
                            <option value="sedang pengerjaan">Sedang Pengerjaan</option>
                            <option value="selesai">Selesai</option>
                        </select>
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
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-[90vw] overflow-y-auto" style="max-height: 90vh;">
            <h2 class="text-xl font-bold mb-4">Edit Data Service</h2>
            <form id="inputFormEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-input-row grid grid-cols-5 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label for="no_spk_edit">No SPK</label>
                        <input type="text" id="no_spk_edit" name="no_spk" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tanggal_edit">Tanggal</label>
                        <input type="date" id="tanggal_edit" name="tanggal" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="costumer_edit">Costumer</label>
                        <input type="text" id="costumer_edit" name="costumer" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="contact_person_edit">Contact Person</label>
                        <input type="text" id="contact_person_edit" name="contact_person"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="masuk_edit">Masuk</label>
                        <input type="date" id="masuk_edit" name="masuk" class="w-full p-2 border rounded"
                            required>
                    </div>

                    <!-- Baris 2 -->
                    <div>
                        <label for="keluar_edit">Keluar</label>
                        <input type="date" id="keluar_edit" name="keluar" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="no_polisi_edit">No Polisi</label>
                        <input type="text" id="no_polisi_edit" name="no_polisi" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="nama_mekanik_edit">Nama Mekanik</label>
                        <select id="nama_mekanik_edit" name="nama_mekanik" class="w-full p-2 border rounded"
                            required>
                            <option value="">Pilih Mekanik</option>
                            @foreach ($mekaniks as $mekanik)
                                <option value="{{ $mekanik->nama_mekanik }}">{{ $mekanik->nama_mekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_edit">Tahun</label>
                        <input type="text" id="tahun_edit" name="tahun" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tipe_mobile_edit">Tipe Mobile</label>
                        <input type="text" id="tipe_mobile_edit" name="tipe_mobile"
                            class="w-full p-2 border rounded" required>
                    </div>

                    <!-- Baris 3 -->
                    <div>
                        <label for="warna_edit">Warna</label>
                        <input type="text" id="warna_edit" name="warna" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="no_rangka_edit">No Rangka</label>
                        <input type="text" id="no_rangka_edit" name="no_rangka" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="no_mesin_edit">No Mesin</label>
                        <input type="text" id="no_mesin_edit" name="no_mesin" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="keluhan_costumer_edit">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer_edit" name="keluhan_costumer" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div>
                        <label for="kode_barang_edit">Kode Barang</label>
                        <select id="kode_barang_edit" name="kode_barang" class="w-full p-2 border rounded">
                            <option value="">Pilih Kode Barang</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nama_part_edit">Nama Part</label>
                        <input type="text" id="nama_part_edit" name="nama_part" class="w-full p-2 border rounded"
                            readonly>
                    </div>
                    <div>
                        <label for="stn_edit">STN</label>
                        <input type="text" id="stn_edit" name="stn" class="w-full p-2 border rounded"
                            readonly>
                    </div>
                    <div>
                        <label for="merk_edit">Merk</label>
                        <input type="text" id="merk_edit" name="merk" class="w-full p-2 border rounded"
                            readonly>
                    </div>
                    <div>
                        <label for="tipe_edit">Tipe</label>
                        <input type="text" id="tipe_edit" name="tipe" class="w-full p-2 border rounded"
                            readonly>
                    </div>
                    <div>
                        <label for="jumlah_edit">Jumlah</label>
                        <input type="number" id="jumlah_edit" name="jumlah" class="w-full p-2 border rounded"
                            value="0">
                    </div>
                    <div>
                        <label for="tanggal_keluar_edit">Tanggal Keluar</label>
                        <input type="date" id="tanggal_keluar_edit" name="tanggal_keluar"
                            class="w-full p-2 border rounded">
                    </div>
                    <!-- Baris 5 -->
                    <div class="col-span-5">
                        <label for="uraian_pekerjaan_edit">Uraian Pekerjaan</label>
                        <textarea id="uraian_pekerjaan_edit" name="uraian_pekerjaan" class="w-full p-2 border rounded"></textarea>
                    </div>
                    <div class="col-span-5">
                        <label for="uraian_jasa_perbaikan_edit">Uraian Jasa Perbaikan</label>
                        <textarea id="uraian_jasa_perbaikan_edit" name="uraian_jasa_perbaikan" class="w-full p-2 border rounded"></textarea>
                    </div>
                    <div class="col-span-5">
                        <label for="status_edit">Status</label>
                        <select id="status_edit" name="status" class="w-full p-2 border rounded" required>
                            <option value="menunggu">Menunggu</option>
                            <option value="sedang pengerjaan">Sedang Pengerjaan</option>
                            <option value="selesai">Selesai</option>
                        </select>
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
        function showErrorPopup(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        }

        @if (session('error'))
            showErrorPopup("{{ session('error') }}");
        @endif


        document.getElementById('kode_barang_edit').addEventListener('change', fetchSparepartDataEdit);

        function fetchSparepartDataEdit() {
            const kodeBarang = document.getElementById('kode_barang_edit').value;

            if (kodeBarang) {
                fetch(`/spareparts/${kodeBarang}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            document.getElementById('nama_part_edit').value = data.nama_part;
                            document.getElementById('stn_edit').value = data.stn;
                            document.getElementById('tipe_edit').value = data.tipe;
                            document.getElementById('merk_edit').value = data.merk;
                        } else {
                            alert('Kode barang tidak ditemukan!');
                            document.getElementById('nama_part_edit').value = '';
                            document.getElementById('stn_edit').value = '';
                            document.getElementById('tipe_edit').value = '';
                            document.getElementById('merk_edit').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                document.getElementById('nama_part_edit').value = '';
                document.getElementById('stn_edit').value = '';
                document.getElementById('tipe_edit').value = '';
                document.getElementById('merk_edit').value = '';
            }
        }
        // Modal logic for opening and closing add/edit modals
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
            fetchSparepartData();
        }

        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        // Fungsi untuk membuka modal tambah
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
            fetchSparepartData();
        }

        // Fungsi untuk menutup modal tambah
        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        function toggleDescription(rowId) {
            const descriptionRow = document.getElementById('desc-' + rowId);
            descriptionRow.classList.toggle('hidden');
        }

        // Fungsi untuk membuka modal edit dan mengisi data
        function openEditModal(
            id, no_spk, tanggal, costumer, contact_person, masuk, keluar, no_polisi, nama_mekanik, tahun,
            tipe_mobile, warna, no_rangka, no_mesin, keluhan_costumer, kode_barang, nama_part, stn, merk, tipe, jumlah,
            uraian_pekerjaan,
            uraian_jasa_perbaikan, status, tanggal_keluar // Pastikan parameter ini ada
        ) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Isi form edit dengan data yang dipilih
            document.getElementById('no_spk_edit').value = no_spk;
            document.getElementById('tanggal_edit').value = tanggal;
            document.getElementById('costumer_edit').value = costumer;
            document.getElementById('contact_person_edit').value = contact_person;
            document.getElementById('masuk_edit').value = masuk;
            document.getElementById('keluar_edit').value = keluar;
            document.getElementById('no_polisi_edit').value = no_polisi;
            document.getElementById('nama_mekanik_edit').value = nama_mekanik;
            document.getElementById('tahun_edit').value = tahun;
            document.getElementById('tipe_mobile_edit').value = tipe_mobile;
            document.getElementById('warna_edit').value = warna;
            document.getElementById('no_rangka_edit').value = no_rangka;
            document.getElementById('no_mesin_edit').value = no_mesin;
            document.getElementById('keluhan_costumer_edit').value = keluhan_costumer;
            document.getElementById('kode_barang_edit').value = kode_barang;
            document.getElementById('stn_edit').value = stn;
            document.getElementById('merk_edit').value = merk;
            document.getElementById('tipe_edit').value = tipe;
            document.getElementById('nama_part_edit').value = nama_part;
            document.getElementById('jumlah_edit').value = jumlah;
            document.getElementById('uraian_pekerjaan_edit').value = uraian_pekerjaan;
            document.getElementById('uraian_jasa_perbaikan_edit').value = uraian_jasa_perbaikan;
            document.getElementById('status_edit').value = status;
            document.getElementById('tanggal_keluar_edit').value = tanggal_keluar; // Pastikan ini diisi
            document.getElementById("inputFormEdit").action = "/dataservice/" + id;
        }

        // Fungsi untuk menutup modal edit
        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }

        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }

        function toggleDescription(id) {
            const descriptionRow = document.getElementById('desc-' + id);
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
    </script>
</body>

</html>
