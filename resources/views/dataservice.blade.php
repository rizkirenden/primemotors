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
                        <th class="px-4 py-2 text-left">Contact Person</th>
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
                            <td class="px-4 py-2">{{ $dataservice->contact_person }}</td>
                            <td class="px-4 py-2">{{ $dataservice->masuk }}</td>
                            <td class="px-4 py-2">{{ $dataservice->keluar }}</td>
                            <td class="px-4 py-2">{{ $dataservice->no_polisi }}</td>
                            <td class="px-4 py-2">{{ $dataservice->nama_mekanik }}</td>
                            <td class="px-4 py-2">{{ $dataservice->status }}</td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full"
                                    onclick="toggleDescription()">
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <!-- Action Icons -->
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal({{ $dataservice->id }})">
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
                                        <div><strong>Tahun:</strong> {{ $dataservice->tahun }}</div>
                                        <div><strong>Tipe:</strong> {{ $dataservice->tipe }}</div>
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
                                                        <strong>Tanggal Keluar</strong>
                                                    </th>
                                                    <th style="border: 1px solid #000; padding: 5px;">
                                                        <strong>Jumlah</strong>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($dataservice->partkeluar as $part)
                                                    <tr>
                                                        <td style="border: 1px solid #000; padding: 5px;">
                                                            {{ $part->kode_barang }}</td>
                                                        <td style="border: 1px solid #000; padding: 5px;">
                                                            {{ $part->nama_part }}</td>
                                                        <td style="border: 1px solid #000; padding: 5px;">
                                                            {{ $part->tanggal_keluar }}</td>
                                                        <td style="border: 1px solid #000; padding: 5px;">
                                                            {{ $part->jumlah }}</td>
                                                    </tr>
                                                @endforeach
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
                {{ $dataservices->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Add Form -->
    <div id="modal-add" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Tambah Data Service</h2>
            <form id="inputFormAdd" method="POST" action="{{ route('dataservice.store') }}">
                @csrf
                <div class="modal-input-row">
                    <div>
                        <label for="no_spk">No SPK</label>
                        <input type="text" id="no_spk" name="no_spk" required>
                    </div>
                    <div>
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" required>
                    </div>
                    <div>
                        <label for="costumer">Costumer</label>
                        <input type="text" id="costumer" name="costumer" required>
                    </div>
                    <div>
                        <label for="contact_person">Contact Person</label>
                        <input type="text" id="contact_person" name="contact_person" required>
                    </div>
                    <div>
                        <label for="masuk">Masuk</label>
                        <input type="time" id="masuk" name="masuk" required>
                    </div>
                    <div>
                        <label for="keluar">Keluar</label>
                        <input type="time" id="keluar" name="keluar">
                    </div>
                    <div>
                        <label for="no_polisi">No Polisi</label>
                        <input type="text" id="no_polisi" name="no_polisi" required>
                    </div>
                    <div>
                        <label for="nama_mekanik">Nama Mekanik</label>
                        <select id="nama_mekanik" name="nama_mekanik" required>
                            <option value="">Pilih Mekanik</option>
                            @foreach ($mekaniks as $mekanik)
                                <option value="{{ $mekanik->nama_mekanik }}">{{ $mekanik->nama_mekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun">Tahun</label>
                        <input type="text" id="tahun" name="tahun" required>
                    </div>
                    <div>
                        <label for="tipe">Tipe</label>
                        <input type="text" id="tipe" name="tipe" required>
                    </div>
                    <div>
                        <label for="warna">Warna</label>
                        <input type="text" id="warna" name="warna" required>
                    </div>
                    <div>
                        <label for="no_rangka">No Rangka</label>
                        <input type="text" id="no_rangka" name="no_rangka" required>
                    </div>
                    <div>
                        <label for="no_mesin">No Mesin</label>
                        <input type="text" id="no_mesin" name="no_mesin" required>
                    </div>
                    <div>
                        <label for="keluhan_costumer">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer" name="keluhan_costumer" required></textarea>
                    </div>
                    <div>
                        <label for="kode_barang">Kode Barang</label>
                        <select id="kode_barang" name="kode_barang">
                            <option value="">Pilih Kode Barang</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }} -
                                    {{ $sparepart->nama_part }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nama_part">Nama Part</label>
                        <input type="text" id="nama_part" name="nama_part">
                    </div>
                    <div>
                        <label for="jumlah">Jumlah</label>
                        <input type="number" id="jumlah" name="jumlah" value="0">
                    </div>
                    <div>
                        <label for="uraian_pekerjaan">Uraian Pekerjaan</label>
                        <textarea id="uraian_pekerjaan" name="uraian_pekerjaan"></textarea>
                    </div>
                    <div>
                        <label for="uraian_jasa_perbaikan">Uraian Jasa Perbaikan</label>
                        <textarea id="uraian_jasa_perbaikan" name="uraian_jasa_perbaikan"></textarea>
                    </div>
                    <div>
                        <label for="status">Status</label>
                        <select id="status" name="status" required>
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
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-lg">
            <h2 class="text-xl font-bold mb-4">Edit Data Service</h2>
            <form id="inputFormEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-input-row">
                    <div>
                        <label for="no_spk_edit">No SPK</label>
                        <input type="text" id="no_spk_edit" name="no_spk" required>
                    </div>
                    <div>
                        <label for="tanggal_edit">Tanggal</label>
                        <input type="date" id="tanggal_edit" name="tanggal" required>
                    </div>
                    <div>
                        <label for="costumer_edit">Costumer</label>
                        <input type="text" id="costumer_edit" name="costumer" required>
                    </div>
                    <div>
                        <label for="contact_person_edit">Contact Person</label>
                        <input type="text" id="contact_person_edit" name="contact_person" required>
                    </div>
                    <div>
                        <label for="masuk_edit">Masuk</label>
                        <input type="time" id="masuk_edit" name="masuk" required>
                    </div>
                    <div>
                        <label for="keluar_edit">Keluar</label>
                        <input type="time" id="keluar_edit" name="keluar">
                    </div>
                    <div>
                        <label for="no_polisi_edit">No Polisi</label>
                        <input type="text" id="no_polisi_edit" name="no_polisi" required>
                    </div>
                    <div>
                        <label for="nama_mekanik_edit">Nama Mekanik</label>
                        <select id="nama_mekanik_edit" name="nama_mekanik" required>
                            <option value="">Pilih Mekanik</option>
                            @foreach ($mekaniks as $mekanik)
                                <option value="{{ $mekanik->nama_mekanik }}">{{ $mekanik->nama_mekanik }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tahun_edit">Tahun</label>
                        <input type="text" id="tahun_edit" name="tahun" required>
                    </div>
                    <div>
                        <label for="tipe_edit">Tipe</label>
                        <input type="text" id="tipe_edit" name="tipe" required>
                    </div>
                    <div>
                        <label for="warna_edit">Warna</label>
                        <input type="text" id="warna_edit" name="warna" required>
                    </div>
                    <div>
                        <label for="no_rangka_edit">No Rangka</label>
                        <input type="text" id="no_rangka_edit" name="no_rangka" required>
                    </div>
                    <div>
                        <label for="no_mesin_edit">No Mesin</label>
                        <input type="text" id="no_mesin_edit" name="no_mesin" required>
                    </div>
                    <div>
                        <label for="keluhan_costumer_edit">Keluhan Costumer</label>
                        <textarea id="keluhan_costumer_edit" name="keluhan_costumer" required></textarea>
                    </div>
                    <div>
                        <label for="kode_barang_edit">Kode Barang</label>
                        <select id="kode_barang_edit" name="kode_barang">
                            <option value="">Pilih Kode Barang</option>
                            @foreach ($spareparts as $sparepart)
                                <option value="{{ $sparepart->kode_barang }}">{{ $sparepart->kode_barang }} -
                                    {{ $sparepart->nama_part }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="nama_part_edit">Nama Part</label>
                        <input type="text" id="nama_part_edit" name="nama_part">
                    </div>
                    <div>
                        <label for="jumlah_edit">Jumlah</label>
                        <input type="number" id="jumlah_edit" name="jumlah" value="0">
                    </div>
                    <div>
                        <label for="uraian_pekerjaan_edit">Uraian Pekerjaan</label>
                        <textarea id="uraian_pekerjaan_edit" name="uraian_pekerjaan"></textarea>
                    </div>
                    <div>
                        <label for="uraian_jasa_perbaikan_edit">Uraian Jasa Perbaikan</label>
                        <textarea id="uraian_jasa_perbaikan_edit" name="uraian_jasa_perbaikan"></textarea>
                    </div>
                    <div>
                        <label for="status_edit">Status</label>
                        <select id="status_edit" name="status" required>
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
        @if (session('error'))
            showErrorPopup("{{ session('error') }}");
        @endif
        // Modal logic for opening and closing add/edit modals
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        // Fungsi untuk membuka modal tambah
        function openAddModal() {
            document.getElementById("modal-add").classList.remove("hidden");
        }

        // Fungsi untuk menutup modal tambah
        function closeAddModal() {
            document.getElementById("modal-add").classList.add("hidden");
        }

        // Fungsi untuk membuka modal edit dan mengisi data
        function openEditModal(id, no_spk, tanggal, costumer, contact_person, masuk, keluar, no_polisi, nama_mekanik, tahun,
            tipe, warna, no_rangka, no_mesin, keluhan_costumer, kode_barang, nama_part, jumlah, uraian_pekerjaan,
            uraian_jasa_perbaikan, status) {
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
            document.getElementById('tipe_edit').value = tipe;
            document.getElementById('warna_edit').value = warna;
            document.getElementById('no_rangka_edit').value = no_rangka;
            document.getElementById('no_mesin_edit').value = no_mesin;
            document.getElementById('keluhan_costumer_edit').value = keluhan_costumer;
            document.getElementById('kode_barang_edit').value = kode_barang;
            document.getElementById('nama_part_edit').value = nama_part;
            document.getElementById('jumlah_edit').value = jumlah;
            document.getElementById('uraian_pekerjaan_edit').value = uraian_pekerjaan;
            document.getElementById('uraian_jasa_perbaikan_edit').value = uraian_jasa_perbaikan;
            document.getElementById('status_edit').value = status;

            // Set action form edit
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
