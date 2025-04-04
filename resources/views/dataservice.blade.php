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
            white-space: normal;
            word-wrap: break-word;
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

        td {
            font-size: 0.75rem;
            white-space: nowrap;
            padding: 4px 8px;
        }

        th {
            font-size: 0.75rem;
            padding: 4px 8px;
        }

        .modal-container {
            max-width: 90vw;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-input-row input,
        .modal-input-row select,
        .modal-input-row textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .modal-input-row textarea {
            resize: vertical;
            min-height: 100px;
        }

        .part-keluar-row {
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        #part-keluar-container-edit {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .bg-red-500 {
            background-color: #ef4444;
        }

        .bg-red-500:hover {
            background-color: #dc2626;
        }

        .flex.items-end {
            display: flex;
            align-items: flex-end;
        }

        .description-row {
            display: none;
        }

        .description-row.visible {
            display: table-row;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        td,
        th {
            word-wrap: break-word;
            overflow: hidden;
            text-overflow: ellipsis;
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
                        <form action="{{ route('printpdfdataspkawal') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" id="search-input" name="search" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">

                                <!-- Filter Date Range -->
                                <input type="date" id="date-start" name="date_start"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('date_start') }}">
                                <input type="date" id="date-end" name="date_end"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('date_end') }}">

                                <!-- Print PDF Button -->
                                <button type="submit"
                                    class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                    <i class="fas fa-file-pdf text-black"></i> Awal
                                </button>
                            </div>
                        </form>
                        <form action="{{ route('printpdfdataspkakhir') }}" method="GET">
                            <button type="submit"
                                class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                <i class="fas fa-file-pdf text-black"></i> Akhir
                            </button>
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
                        <th class="px-4 py-2 text-left">Costumer</th>
                        <th class="px-4 py-2 text-left">Masuk</th>
                        <th class="px-4 py-2 text-left">Keluar</th>
                        <th class="px-4 py-2 text-left">No Polisi</th>
                        <th class="px-4 py-2 text-left">Mekanik</th>
                        <th class="px-4 py-2 text-left">Detail</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @if (isset($dataservices) && $dataservices->count() > 0)
                        @foreach ($dataservices as $dataservice)
                            <tr data-id="{{ $dataservice->id }}">
                                <td class="px-4 py-2">{{ $dataservice->no_spk }}</td>
                                <td class="px-4 py-2">{{ $dataservice->costumer }}</td>
                                <td class="px-4 py-2">{{ $dataservice->masuk }}</td>
                                <td class="px-4 py-2">{{ $dataservice->keluar }}</td>
                                <td class="px-4 py-2">{{ $dataservice->no_polisi }}</td>
                                <td class="px-4 py-2">{{ $dataservice->nama_mekanik }}</td>
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
                                            '{{ $dataservice->kilometer }}',
                                            '{{ $dataservice->keluhan_costumer }}',
                                            '{{ $dataservice->kode_barang }}',
                                            '{{ $dataservice->nama_part }}',
                                            '{{ $dataservice->stn }}',
                                            '{{ $dataservice->tipe }}',
                                            '{{ $dataservice->merk }}',
                                            '{{ $dataservice->jumlah }}',
                                            '{{ $dataservice->uraian_jasa_perbaikan }}',
                                            '{{ $dataservice->status }}',
                                            '{{ $dataservice->tanggal_keluar ?? '' }}'
                                        )">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-green-500 hover:text-green-700 mr-3"
                                        onclick="openEditModalAwal(
                                            '{{ $dataservice->id }}',
                                            '{{ $dataservice->no_spk }}',
                                            '{{ $dataservice->costumer }}',
                                            '{{ $dataservice->contact_person }}',
                                            '{{ $dataservice->masuk }}',
                                            '{{ $dataservice->keluar }}',
                                            '{{ $dataservice->no_polisi }}',
                                            '{{ $dataservice->tahun }}',
                                            '{{ $dataservice->tipe_mobile }}',
                                            '{{ $dataservice->warna }}',
                                            '{{ $dataservice->no_rangka }}',
                                            '{{ $dataservice->no_mesin }}',
                                            '{{ $dataservice->kilometer }}',
                                            '{{ $dataservice->keluhan_costumer }}',
                                            '{{ $dataservice->status }}',
                                            '{{ $dataservice->jenis_pekerjaan }}',
                                            '{{ $dataservice->jenis_mobil }}',
                                            '{{ $dataservice->waktu_pengerjaan }}',
                                            '{{ $dataservice->ongkos_pengerjaan }}'
                                        )">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </a>
                                    <form action="{{ route('dataservice.destroy', $dataservice->id) }}" method="POST"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('printpdfdataspkawal.perdata', $dataservice->id) }}"
                                        class="text-black hover:text-black ml-3">
                                        <i class="fas fa-print"></i> Awal
                                    </a>
                                    <a href="{{ route('printpdfdataspkakhir.perdata', $dataservice->id) }}"
                                        class="text-black hover:text-black ml-3">
                                        <i class="fas fa-print"></i> Akhir
                                    </a>
                                    <form action="{{ route('invoice.store', $dataservice->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="text-black hover:text-black ml-3">
                                            <i class="fas fa-print"></i> Invoice
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <!-- Detail Row -->
                            <tr id="desc-{{ $dataservice->id }}" class="description-row">
                                <td colspan="20">
                                    <div class="description-container">
                                        <!-- First Row: Contact Person, Year, Type, Color, Chassis Number, Engine Number, Kilometer, Status -->
                                        <div
                                            style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                            <div><strong>Contact Person:</strong> {{ $dataservice->contact_person }}
                                            </div>
                                            <div><strong>Tahun:</strong> {{ $dataservice->tahun }}</div>
                                            <div><strong>Tipe:</strong> {{ $dataservice->tipe_mobile }}</div>
                                            <div><strong>Warna:</strong> {{ $dataservice->warna }}</div>
                                            <div><strong>No Rangka:</strong> {{ $dataservice->no_rangka }}</div>
                                            <div><strong>No Mesin:</strong> {{ $dataservice->no_mesin }}</div>
                                            <div><strong>Kilometer:</strong>
                                                {{ rtrim(number_format($dataservice->kilometer, 2), '.00') }} KM</div>
                                            <div><strong>Status:</strong> {{ $dataservice->status }}</div>
                                        </div>

                                        <!-- Second Row: Customer Complaints -->
                                        <div style="margin-bottom: 20px;">
                                            <div><strong>Keluhan Customer:</strong></div>
                                            <textarea style="width: 100%; height: 80px; resize: vertical;" readonly>{{ $dataservice->keluhan_costumer }}</textarea>
                                        </div>

                                        <!-- Third Row: Work Descriptions -->
                                        <div style="margin-bottom: 20px;">
                                            <div><strong>Uraian Pekerjaan:</strong></div>
                                            <table style="width: 100%; border-collapse: collapse;">
                                                <thead>
                                                    <tr>
                                                        <th style="border: 1px solid #000; padding: 5px;"><strong>Jenis
                                                                Pekerjaan:</strong></th>
                                                        <th style="border: 1px solid #000; padding: 5px;"><strong>Jenis
                                                                Mobil:</strong></th>
                                                        <th style="border: 1px solid #000; padding: 5px;"><strong>Waktu
                                                                Pengerjaan (jam):</strong></th>
                                                        <th style="border: 1px solid #000; padding: 5px;">
                                                            <strong>Ongkos Pengerjaan:</strong>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $jenisPekerjaan = json_decode(
                                                            $dataservice->jenis_pekerjaan,
                                                            true,
                                                        );
                                                        $jenisMobil = json_decode($dataservice->jenis_mobil, true);
                                                        $waktuPengerjaan = json_decode(
                                                            $dataservice->waktu_pengerjaan,
                                                            true,
                                                        );
                                                        $ongkosPengerjaan = json_decode(
                                                            $dataservice->ongkos_pengerjaan,
                                                            true,
                                                        );
                                                    @endphp

                                                    @if (!empty($jenisPekerjaan) && is_array($jenisPekerjaan))
                                                        @foreach ($jenisPekerjaan as $index => $jenis)
                                                            <tr>
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    {{ $jenis }}</td>
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    {{ $jenisMobil[$index] ?? '-' }}</td>
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    {{ $waktuPengerjaan[$index] ?? '-' }}</td>
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    @php
                                                                        $ongkosPekerjaan = json_decode(
                                                                            $dataservice->ongkos_pengerjaan,
                                                                            true,
                                                                        );
                                                                    @endphp
                                                                    {{ isset($ongkosPekerjaan[$index]) ? 'Rp. ' . number_format((float) $ongkosPekerjaan[$index], 0, ',', '.') : '-' }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="4"
                                                                style="border: 1px solid #000; padding: 5px; text-align: center;">
                                                                Tidak ada data uraian pekerjaan.
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Fourth Row: Part Details -->
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
                                                        <th style="border: 1px solid #000; padding: 5px;">
                                                            <strong>Tanggal Keluar</strong>
                                                        </th>
                                                        <th style="border: 1px solid #000; padding: 5px;">
                                                            <strong>Jumlah</strong>
                                                        </th>
                                                        <th style="border: 1px solid #000; padding: 5px;">
                                                            <strong>Status</strong>
                                                        </th>
                                                        <th style="border: 1px solid #000; padding: 5px;">
                                                            <strong>Uraian Jasa Perbaikan</strong>
                                                        </th>
                                                        <th style="border: 1px solid #000; padding: 5px;"><strong>Harga
                                                                Jasa Perbaikan</strong></th>
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
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    {{ $part->status }}</td>
                                                                <td style="border: 1px solid #000; padding: 5px;">
                                                                    {{ $part->uraian_jasa_perbaikan }}</td>
                                                                <td style="border: 1px solid #000; padding: 5px;">Rp.
                                                                    {{ number_format($part->harga_jasa_perbaikan, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="10"
                                                                style="border: 1px solid #000; padding: 5px; text-align: center;">
                                                                Tidak ada data part yang keluar.</td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="10" class="text-center py-4">Tidak ada data ditemukan.</td>
                        </tr>
                    @endif
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
                        <label for="masuk">Tanggal Masuk</label>
                        <input type="datetime-local" id="masuk" name="masuk" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <!-- Baris 2 -->
                    <div>
                        <label for="keluar">Tanggal Keluar</label>
                        <input type="datetime-local" id="keluar" name="keluar"
                            class="w-full p-2 border rounded">
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
                        <label for="kilometer">Jarak (Kilometer)</label>
                        <input type="text" id="kilometer" name="kilometer" class="w-full p-2 border rounded"
                            oninput="formatKilometer(this)" required>
                    </div>
                    <div>
                        <label for="keluhan_costumer">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer" name="keluhan_costumer" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div class="col-span-5">
                        <label for="status">Status Costumer</label>
                        <select id="status" name="status" class="w-full p-2 border rounded" required>
                            <option value="menunggu">Menunggu</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="col-span-5">
                        <label for="uraian_pekerjaan"></label>
                        <div id="uraian-container"></div>
                        <button type="button" onclick="tambahUraian()"
                            class="bg-black text-white px-4 py-2 rounded-full mt-4">
                            <i class="fas fa-plus"></i> Tambah Uraian
                        </button>
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

    <!-- Modal Edit Awal -->
    <div id="modal-edit-awal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-[90vw] overflow-y-auto" style="max-height: 90vh;">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">Edit Data Service Awal</h2>
                <button onclick="closeEditAwalModal()" class="text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="inputFormEditAwal" method="POST"
                action="{{ route('dataservice.updateawal', $dataservice->id ?? '') }}">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_awal_id" name="id">

                <div class="grid grid-cols-5 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label for="no_spk_edit_awal">No SPK</label>
                        <input type="text" id="no_spk_edit_awal" name="no_spk" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="costumer_edit_awal">Costumer</label>
                        <input type="text" id="costumer_edit_awal" name="costumer"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="contact_person_edit_awal">Contact Person</label>
                        <input type="text" id="contact_person_edit_awal" name="contact_person"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="masuk_edit_awal">Tanggal Masuk</label>
                        <input type="datetime-local" id="masuk_edit_awal" name="masuk"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <!-- Baris 2 -->
                    <div>
                        <label for="keluar_edit_awal">Tanggal Keluar</label>
                        <input type="datetime-local" id="keluar_edit_awal" name="keluar"
                            class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label for="no_polisi_edit_awal">No Polisi</label>
                        <input type="text" id="no_polisi_edit_awal" name="no_polisi"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="tahun_edit_awal">Tahun</label>
                        <input type="text" id="tahun_edit_awal" name="tahun" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="tipe_mobile_edit_awal">Tipe Mobil</label>
                        <input type="text" id="tipe_mobile_edit_awal" name="tipe_mobile"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <!-- Baris 3 -->
                    <div>
                        <label for="warna_edit_awal">Warna</label>
                        <input type="text" id="warna_edit_awal" name="warna" class="w-full p-2 border rounded"
                            required>
                    </div>
                    <div>
                        <label for="no_rangka_edit_awal">No Rangka</label>
                        <input type="text" id="no_rangka_edit_awal" name="no_rangka"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="no_mesin_edit_awal">No Mesin</label>
                        <input type="text" id="no_mesin_edit_awal" name="no_mesin"
                            class="w-full p-2 border rounded" required>
                    </div>
                    <div>
                        <label for="kilometer_edit_awal">Jarak (Kilometer)</label>
                        <input type="text" id="kilometer_edit_awal" name="kilometer"
                            class="w-full p-2 border rounded" oninput="formatKilometer(this)" required>
                    </div>
                    <div>
                        <label for="keluhan_costumer_edit_awal">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer_edit_awal" name="keluhan_costumer" class="w-full p-2 border rounded" required></textarea>
                    </div>
                    <div class="col-span-5">
                        <label for="status_edit_awal">Status Costumer</label>
                        <select id="status_edit_awal" name="status" class="w-full p-2 border rounded" required>
                            <option value="menunggu">Menunggu</option>
                            <option value="pulang">Pulang</option>
                        </select>
                    </div>
                    <div class="col-span-5">
                        <label for="uraian_pekerjaan">Uraian Pekerjaan</label>
                        <div id="uraian-container-edit-awal"></div>
                        <button type="button" onclick="addPekerjaanFieldEditAwal()"
                            class="bg-black text-white px-4 py-2 rounded-full mt-4">
                            <i class="fas fa-plus"></i> Tambah Uraian
                        </button>
                    </div>
                </div>
                <div class="mt-4 flex space-x-4 justify-center">
                    <button type="button" onclick="closeEditAwalModal()"
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
            <form id="inputFormEdit" method="POST"
                action="{{ route('dataservice.update', $dataservice->id ?? '') }}">
                @csrf
                @method('PUT')
                <!-- Input fields untuk dataservice -->
                <div class="grid grid-cols-5 gap-4">
                    <!-- Baris 1 -->
                    <div>
                        <label for="no_spk_edit">No SPK</label>
                        <input type="text" id="no_spk_edit" name="no_spk" class="w-full p-2 border rounded"
                            value="{{ $dataservice->no_spk ?? '' }}" required>
                    </div>
                    <div>
                        <label for="costumer_edit">Costumer</label>
                        <input type="text" id="costumer_edit" name="costumer" class="w-full p-2 border rounded"
                            value="{{ $dataservice->costumer ?? '' }}" required>
                    </div>
                    <div>
                        <label for="contact_person_edit">Contact Person</label>
                        <input type="text" id="contact_person_edit" name="contact_person"
                            class="w-full p-2 border rounded" value="{{ $dataservice->contact_person ?? '' }}"
                            required>
                    </div>
                    <div>
                        <label for="masuk_edit">Tanggal Masuk</label>
                        <input type="datetime-local" id="masuk_edit" name="masuk"
                            class="w-full p-2 border rounded" value="{{ $dataservice->masuk ?? '' }}" required>
                    </div>

                    <!-- Baris 2 -->
                    <div>
                        <label for="keluar_edit">Tanggal Keluar</label>
                        <input type="datetime-local" id="keluar_edit" name="keluar"
                            class="w-full p-2 border rounded" value="{{ $dataservice->keluar ?? '' }}">
                    </div>
                    <div>
                        <label for="no_polisi_edit">No Polisi</label>
                        <input type="text" id="no_polisi_edit" name="no_polisi" class="w-full p-2 border rounded"
                            value="{{ $dataservice->no_polisi ?? '' }}" required>
                    </div>
                    <div>
                        <label for="nama_mekanik_edit">Nama Mekanik</label>
                        <select id="nama_mekanik_edit" name="nama_mekanik" class="w-full p-2 border rounded"
                            required>
                            <option value="">Pilih Mekanik</option>
                            @foreach ($mekaniks as $mekanik)
                                <option value="{{ $mekanik->nama_mekanik }}"
                                    {{ ($dataservice->nama_mekanik ?? '') == $mekanik->nama_mekanik ? 'selected' : '' }}>
                                    {{ $mekanik->nama_mekanik }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_edit">Tahun</label>
                        <input type="text" id="tahun_edit" name="tahun" class="w-full p-2 border rounded"
                            value="{{ $dataservice->tahun ?? '' }}" required>
                    </div>
                    <div>
                        <label for="tipe_mobile_edit">Tipe Mobile</label>
                        <input type="text" id="tipe_mobile_edit" name="tipe_mobile"
                            class="w-full p-2 border rounded" value="{{ $dataservice->tipe_mobile ?? '' }}"
                            required>
                    </div>

                    <!-- Baris 3 -->
                    <div>
                        <label for="warna_edit">Warna</label>
                        <input type="text" id="warna_edit" name="warna" class="w-full p-2 border rounded"
                            value="{{ $dataservice->warna ?? '' }}" required>
                    </div>
                    <div>
                        <label for="no_rangka_edit">No Rangka</label>
                        <input type="text" id="no_rangka_edit" name="no_rangka" class="w-full p-2 border rounded"
                            value="{{ $dataservice->no_rangka ?? '' }}" required>
                    </div>
                    <div>
                        <label for="no_mesin_edit">No Mesin</label>
                        <input type="text" id="no_mesin_edit" name="no_mesin" class="w-full p-2 border rounded"
                            value="{{ $dataservice->no_mesin ?? '' }}" required>
                    </div>
                    <div>
                        <label for="kilometer_edit">Kilometer</label>
                        <input type="text" id="kilometer_edit" name="kilometer" class="w-full p-2 border rounded"
                            value="{{ $dataservice->kilometer ?? '' }}" oninput="formatKilometer(this)" required>
                    </div>

                    <div>
                        <label for="keluhan_costumer_edit">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer_edit" name="keluhan_costumer" class="w-full p-2 border rounded" required>{{ $dataservice->keluhan_costumer ?? '' }}</textarea>
                    </div>
                    <div>
                        <label for="status_edit">Status</label>
                        <select id="status_edit" name="status" class="w-full p-2 border rounded" required>
                            <option value="menunggu"
                                {{ ($dataservice->status ?? '') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="pulang" {{ ($dataservice->status ?? '') == 'pulang' ? 'selected' : '' }}>
                                pulang</option>
                        </select>
                    </div>
                </div>

                <!-- Part Keluar -->
                <div class="col-span-5 mt-4">
                    <div id="part-keluar-container-edit" class="mt-4">
                        @if (isset($dataservice) && $dataservice->partkeluar->count() > 0)
                            @foreach ($dataservice->partkeluar as $part)
                                <div class="part-keluar-row grid grid-cols-5 gap-4 mt-4">
                                    <div>
                                        <label for="kode_barang_edit">Kode Barang</label>
                                        <select name="kode_barang[]"
                                            class="w-full p-2 border rounded kode-barang-select"
                                            onchange="fetchSparepartDataEdit(event)">
                                            <option value="">Pilih Kode Barang</option>
                                            @foreach ($spareparts as $sparepart)
                                                <option value="{{ $sparepart->kode_barang }}"
                                                    {{ $part->kode_barang == $sparepart->kode_barang ? 'selected' : '' }}>
                                                    {{ $sparepart->kode_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="nama_part_edit">Nama Part</label>
                                        <input type="text" name="nama_part[]"
                                            class="w-full p-2 border rounded nama-part-input"
                                            value="{{ $part->nama_part }}" readonly>
                                    </div>
                                    <div>
                                        <label for="stn_edit">STN</label>
                                        <input type="text" name="stn[]"
                                            class="w-full p-2 border rounded stn-input" value="{{ $part->stn }}"
                                            readonly>
                                    </div>
                                    <div>
                                        <label for="merk_edit">Merk</label>
                                        <input type="text" name="merk[]"
                                            class="w-full p-2 border rounded merk-input" value="{{ $part->merk }}"
                                            readonly>
                                    </div>
                                    <div>
                                        <label for="jumlah_edit">Jumlah</label>
                                        <input type="number" name="jumlah[]"
                                            class="w-full p-2 border rounded jumlah-input"
                                            value="{{ $part->jumlah }}">
                                    </div>
                                    <div>
                                        <label for="tanggal_keluar">tanggal_keluar</label>
                                        <input type="date" name="tanggal_keluar[]"
                                            class="w-full p-2 border rounded" value="{{ $part->tanggal_keluar }}">
                                    </div>
                                    <div>
                                        <label for="uraian_jasa_perbaikan_edit">Uraian Jasa Perbaikan</label>
                                        <textarea name="uraian_jasa_perbaikan[]" class="w-full p-2 border rounded">{{ $part->uraian_jasa_perbaikan }}</textarea>
                                    </div>
                                    <div>
                                        <label for="harga_jasa_perbaikan_edit">Harga Jasa Perbaikan</label>
                                        <input type="text" id="harga_jasa_perbaikan_edit"
                                            name="harga_jasa_perbaikan[]"
                                            class="w-full p-2 border rounded harga-jasa-input"
                                            value="{{ $part->harga_jasa_perbaikan ? number_format($part->harga_jasa_perbaikan, 0, ',', '.') : '' }}">
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Tidak ada data part yang keluar.</p>
                        @endif
                    </div>

                    <!-- Tombol Tambah Part -->
                    <button type="button" onclick="tambahPartKeluarEdit()"
                        class="bg-black text-white px-4 py-2 rounded-full mt-4">
                        <i class="fas fa-plus"></i> Tambah Part
                    </button>
                </div>
                <!-- Tombol Submit -->
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
        // Function to open edit awal modal
        // Function to open edit awal modal
        function openEditModalAwal(
            id, no_spk, costumer, contact_person, masuk, keluar, no_polisi, tahun,
            tipe_mobile, warna, no_rangka, no_mesin, kilometer, keluhan_costumer, status,
            jenis_pekerjaan, jenis_mobil, waktu_pengerjaan, ongkos_pengerjaan
        ) {
            // Populate form fields
            document.getElementById('edit_awal_id').value = id;
            document.getElementById('no_spk_edit_awal').value = no_spk;
            document.getElementById('costumer_edit_awal').value = costumer;
            document.getElementById('contact_person_edit_awal').value = contact_person;
            document.getElementById('masuk_edit_awal').value = masuk.replace(' ', 'T');
            document.getElementById('keluar_edit_awal').value = keluar ? keluar.replace(' ', 'T') : '';
            document.getElementById('no_polisi_edit_awal').value = no_polisi;
            document.getElementById('tahun_edit_awal').value = tahun;
            document.getElementById('tipe_mobile_edit_awal').value = tipe_mobile;
            document.getElementById('warna_edit_awal').value = warna;
            document.getElementById('no_rangka_edit_awal').value = no_rangka;
            document.getElementById('no_mesin_edit_awal').value = no_mesin;
            document.getElementById('kilometer_edit_awal').value = kilometer + ' KM';
            document.getElementById('keluhan_costumer_edit_awal').value = keluhan_costumer;
            document.getElementById('status_edit_awal').value = status;

            // Clear existing pekerjaan fields
            const pekerjaanContainer = document.getElementById('uraian-container-edit-awal');
            pekerjaanContainer.innerHTML = '';

            // Parse JSON data for pekerjaan
            try {
                const pekerjaanList = JSON.parse(jenis_pekerjaan || '[]');
                const jenisMobilList = JSON.parse(jenis_mobil || '[]');
                const waktuList = JSON.parse(waktu_pengerjaan || '[]');
                const ongkosList = JSON.parse(ongkos_pengerjaan || '[]');

                // Add pekerjaan fields
                pekerjaanList.forEach((pekerjaan, index) => {
                    addPekerjaanFieldEditAwal(
                        pekerjaan,
                        jenisMobilList[index] || '',
                        waktuList[index] || '',
                        ongkosList[index] || ''
                    );
                });
            } catch (e) {
                console.error('Error parsing pekerjaan data:', e);
            }

            // Set form action
            document.getElementById('inputFormEditAwal').action = `/dataservice/${id}/updateawal`;

            // Show modal
            document.getElementById('modal-edit-awal').classList.remove('hidden');
        }

        function closeEditAwalModal() {
            document.getElementById('modal-edit-awal').classList.add('hidden');
        }

        function addPekerjaanFieldEditAwal(pekerjaan = '', jenisMobil = '', waktu = '', ongkos = '') {
            const container = document.getElementById('uraian-container-edit-awal');
            const index = container.children.length;

            const fieldHtml = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2 mb-2 pekerjaan-field-edit-awal">
                <div>
                    <label class="block text-sm">Jenis Pekerjaan</label>
                    <select name="jenis_pekerjaan[]" class="w-full p-2 border rounded jenis-pekerjaan-select" onchange="fillUraianDataEditAwal(event)">
                        <option value="">Pilih Jenis Pekerjaan</option>
                        @foreach ($uraianPekerjaans as $uraian)
                            <option value="{{ $uraian->jenis_pekerjaan }}"
                                data-mobil="{{ $uraian->jenis_mobil }}"
                                data-waktu="{{ $uraian->waktu_pengerjaan }}"
                                data-ongkos="{{ $uraian->ongkos_pengerjaan }}"
                                ${pekerjaan === "{{ $uraian->jenis_pekerjaan }}" ? 'selected' : ''}>
                                {{ $uraian->jenis_pekerjaan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm">Jenis Mobil</label>
                    <input type="text" name="jenis_mobil[]" value="${jenisMobil}"
                        class="w-full p-2 border rounded jenis-mobil-input" readonly>
                </div>
                <div>
                    <label class="block text-sm">Waktu (jam)</label>
                    <input type="number" name="waktu_pengerjaan[]" value="${waktu}"
                        class="w-full p-2 border rounded waktu-pengerjaan-input" readonly>
                </div>
                <div class="flex items-end">
                    <div class="flex-1">
                        <label class="block text-sm">Ongkos</label>
                        <input type="text" name="ongkos_pengerjaan[]" value="${ongkos}"
                            class="w-full p-2 border rounded ongkos-pengerjaan-input" readonly>
                    </div>
                    <button type="button" onclick="removePekerjaanFieldEditAwal(this)"
                        class="ml-2 bg-red-500 text-white px-2 py-2 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', fieldHtml);
        }

        function fillUraianDataEditAwal(event) {
            const selectedOption = event.target.selectedOptions[0];
            const row = event.target.closest('.pekerjaan-field-edit-awal');

            if (selectedOption.value) {
                const jenisMobil = selectedOption.getAttribute('data-mobil');
                const waktuPengerjaan = selectedOption.getAttribute('data-waktu');
                const ongkosPengerjaan = selectedOption.getAttribute('data-ongkos');

                row.querySelector('.jenis-mobil-input').value = jenisMobil || '';
                row.querySelector('.waktu-pengerjaan-input').value = waktuPengerjaan || '';
                row.querySelector('.ongkos-pengerjaan-input').value = ongkosPengerjaan || '';
            } else {
                row.querySelector('.jenis-mobil-input').value = '';
                row.querySelector('.waktu-pengerjaan-input').value = '';
                row.querySelector('.ongkos-pengerjaan-input').value = '';
            }
        }

        function removePekerjaanFieldEditAwal(button) {
            button.closest('.pekerjaan-field-edit-awal').remove();
        }
        // Common functions
        function tambahUraian() {
            const container = document.getElementById('uraian-container');
            const newUraian = document.createElement('div');
            newUraian.classList.add('uraian-row', 'grid', 'grid-cols-4', 'gap-4', 'mt-4');
            newUraian.innerHTML = `
                <div>
                    <label for="jenis_pekerjaan">Pilih Jenis Pekerjaan:</label>
                    <select name="jenis_pekerjaan[]" class="w-full p-2 border rounded jenis-pekerjaan-select" onchange="fillUraianData(event)">
                        <option value="">Pilih Jenis Pekerjaan</option>
                        @foreach ($uraianPekerjaans as $uraian)
                            <option value="{{ $uraian->jenis_pekerjaan }}" data-mobil="{{ $uraian->jenis_mobil }}" data-waktu="{{ $uraian->waktu_pengerjaan }}" data-ongkos="{{ $uraian->ongkos_pengerjaan }}">
                                {{ $uraian->jenis_pekerjaan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="jenis_mobil">Jenis Mobil</label>
                    <input type="text" name="jenis_mobil[]" class="w-full p-2 border rounded jenis-mobil-input" readonly>
                </div>
                <div>
                    <label for="waktu_pengerjaan">Waktu Pengerjaan (jam)</label>
                    <input type="number" name="waktu_pengerjaan[]" class="w-full p-2 border rounded waktu-pengerjaan-input" readonly>
                </div>
                <div>
                    <label for="ongkos_pengerjaan">Ongkos Pengerjaan</label>
                    <input type="text" name="ongkos_pengerjaan[]" class="w-full p-2 border rounded ongkos-pengerjaan-input" readonly>
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="hapusUraian(this)" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-700">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            `;

            container.appendChild(newUraian);
        }

        function fillUraianData(event) {
            const selectedOption = event.target.selectedOptions[0];
            const row = event.target.closest('.uraian-row');

            if (selectedOption.value) {
                const jenisMobil = selectedOption.getAttribute('data-mobil');
                const waktuPengerjaan = selectedOption.getAttribute('data-waktu');
                const ongkosPengerjaan = selectedOption.getAttribute('data-ongkos');

                row.querySelector('.jenis-mobil-input').value = jenisMobil || '';
                row.querySelector('.waktu-pengerjaan-input').value = waktuPengerjaan || '';
                row.querySelector('.ongkos-pengerjaan-input').value = ongkosPengerjaan || '';
            } else {
                row.querySelector('.jenis-mobil-input').value = '';
                row.querySelector('.waktu-pengerjaan-input').value = '';
                row.querySelector('.ongkos-pengerjaan-input').value = '';
            }
        }

        function hapusUraian(button) {
            button.closest('.uraian-row').remove();
        }

        function formatKilometer(input) {
            let value = input.value.replace(/[^0-9.]/g, '');
            input.value = value + ' KM';
        }

        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        function toggleDescription(rowId) {
            const descriptionRow = document.getElementById('desc-' + rowId);
            if (descriptionRow.classList.contains('hidden')) {
                descriptionRow.classList.remove('hidden');
                descriptionRow.style.display = 'table-row';
            } else {
                descriptionRow.classList.add('hidden');
                descriptionRow.style.display = 'none';
            }
        }

        function searchTable() {
            let input = document.getElementById("search-input");
            let filter = input.value.toLowerCase();
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                if (row.classList.contains('description-row')) return;

                let cells = row.getElementsByTagName("td");
                let found = false;

                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                row.style.display = found ? "" : "none";
                let descriptionRow = table.querySelector(`#desc-${row.dataset.id}`);
                if (descriptionRow) {
                    descriptionRow.style.display = found ? "" : "none";
                }
            });
        }

        function filterByDateRange() {
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            let start = startDate ? new Date(startDate) : null;
            let end = endDate ? new Date(endDate) : null;

            Array.from(rows).forEach(row => {
                if (row.classList.contains('description-row')) return;

                let cells = row.getElementsByTagName("td");
                let dateCell = cells[1];

                if (dateCell) {
                    let rowDate = new Date(dateCell.textContent.trim());
                    let isWithinRange = true;

                    if (start && rowDate < start) isWithinRange = false;
                    if (end && rowDate > end) isWithinRange = false;

                    row.style.display = isWithinRange ? "" : "none";
                    let descriptionRow = table.querySelector(`#desc-${row.dataset.id}`);
                    if (descriptionRow) {
                        descriptionRow.style.display = isWithinRange ? "" : "none";
                    }
                }
            });
        }

        document.getElementById("date-start").addEventListener("change", filterByDateRange);
        document.getElementById("date-end").addEventListener("change", filterByDateRange);

        function filterTable() {
            let search = document.getElementById("search-input").value.toLowerCase();
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            let start = startDate ? new Date(startDate) : null;
            let end = endDate ? new Date(endDate) : null;

            Array.from(rows).forEach(row => {
                if (row.classList.contains('description-row')) return;

                let cells = row.getElementsByTagName("td");
                let found = false;

                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(search)) {
                        found = true;
                    }
                });

                let dateCell = cells[1];
                if (dateCell) {
                    let rowDate = new Date(dateCell.textContent.trim());
                    if (start && rowDate < start) found = false;
                    if (end && rowDate > end) found = false;
                }

                row.style.display = found ? "" : "none";
                let descriptionRow = table.querySelector(`#desc-${row.dataset.id}`);
                if (descriptionRow) {
                    descriptionRow.style.display = found ? "" : "none";
                }
            });
        }

        document.getElementById("search-input").addEventListener("keyup", filterTable);
        document.getElementById("date-start").addEventListener("change", filterTable);
        document.getElementById("date-end").addEventListener("change", filterTable);

        function fetchSparepartDataEdit(event) {
            const kodeBarang = event.target.value;
            const row = event.target.closest('.part-keluar-row');

            if (kodeBarang) {
                fetch(`/spareparts/${kodeBarang}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data) {
                            row.querySelector('.nama-part-input').value = data.nama_part;
                            row.querySelector('.stn-input').value = data.stn;
                            row.querySelector('.merk-input').value = data.merk;
                            row.querySelector('.tipe-input').value = data.merk;
                        } else {
                            alert('Kode barang tidak ditemukan!');
                            row.querySelector('.nama-part-input').value = '';
                            row.querySelector('.stn-input').value = '';
                            row.querySelector('.merk-input').value = '';
                            row.querySelector('.tipe-input').value = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                row.querySelector('.nama-part-input').value = '';
                row.querySelector('.stn-input').value = '';
                row.querySelector('.merk-input').value = '';
            }
        }

        function openEditModal(
            id, no_spk, costumer, contact_person, masuk, keluar, no_polisi, nama_mekanik, tahun,
            tipe_mobile, warna, no_rangka, no_mesin, kilometer, keluhan_costumer, kode_barang, nama_part, stn,
            tipe, merk, jumlah, uraian_jasa_perbaikan, status, tanggal_keluar
        ) {
            document.getElementById("modal-edit").classList.remove("hidden");

            // Isi form edit dengan data yang dipilih
            document.getElementById('no_spk_edit').value = no_spk;
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
            document.getElementById('kilometer_edit').value = kilometer;
            document.getElementById('keluhan_costumer_edit').value = keluhan_costumer;
            document.getElementById('status_edit').value = status;
            document.getElementById("inputFormEdit").action = "/dataservice/" + id;
        }

        function closeEditModal() {
            document.getElementById("modal-edit").classList.add("hidden");
        }

        function tambahPartKeluarEdit() {
            const container = document.getElementById('part-keluar-container-edit');
            const newRow = document.createElement('div');
            newRow.classList.add('part-keluar-row', 'grid', 'grid-cols-6', 'gap-4', 'mt-4');

            newRow.innerHTML = `
                <div>
                    <label for="kode_barang_edit">Kode Barang</label>
                    <select name="kode_barang[]" class="w-full p-2 border rounded kode-barang-select" onchange="fetchSparepartDataEdit(event)">
                        <option value="">Pilih Kode Barang</option>
                        @foreach ($spareparts as $sparepart)
                            <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="nama_part_edit">Nama Part</label>
                    <input type="text" name="nama_part[]" class="w-full p-2 border rounded nama-part-input" readonly>
                </div>
                <div>
                    <label for="stn_edit">STN</label>
                    <input type="text" name="stn[]" class="w-full p-2 border rounded stn-input" readonly>
                </div>
                <div>
                    <label for="merk_edit">Merk</label>
                    <input type="text" name="merk[]" class="w-full p-2 border rounded merk-input" readonly>
                </div>
                <div>
                    <label for="jumlah_edit">Jumlah</label>
                    <input type="number" name="jumlah[]" class="w-full p-2 border rounded jumlah-input" value="0">
                </div>
                <div>
                    <label for="tanggal_keluar_edit">Tanggal Keluar</label>
                    <input type="date" name="tanggal_keluar[]" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label for="uraian_jasa_perbaikan_edit">Uraian Pekerjaan</label>
                    <textarea name="uraian_jasa_perbaikan[]" class="w-full p-2 border rounded"></textarea>
                </div>
                <div>
                    <label for="harga_jasa_perbaikan_edit">Harga Jasa Perbaikan</label>
                    <input type="text" name="harga_jasa_perbaikan[]" class="w-full p-2 border rounded harga-jasa-input">
                </div>
                <div class="flex items-end">
                    <button type="button" onclick="hapusPartKeluar(this)" class="bg-red-500 text-white px-4 py-2 rounded-full hover:bg-red-700">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            `;

            container.appendChild(newRow);
        }

        function hapusPartKeluar(button) {
            button.closest('.part-keluar-row').remove();
        }

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
    </script>
</body>

</html>
