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
        /* Add your custom styles here */
        thead {
            border-bottom: 2px solid #ccc;
        }

        tbody {
            border-top: 1px solid #ccc;
        }

        /* Pagination Styling */
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

        /* Modal Styling */
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
                        <form action="{{ route('printpdfdatamekanik') }}" method="GET">
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
                        <th class="px-4 py-2 text-left text-xs">Kode Barang</th>
                        <th class="px-4 py-2 text-left text-xs">Nama Part</th>
                        <th class="px-4 py-2 text-left text-xs">STN</th>
                        <th class="px-4 py-2 text-left text-xs">Tipe</th>
                        <th class="px-4 py-2 text-left text-xs">Merk</th>
                        <th class="px-4 py-2 text-left text-xs">Tanggal Keluar</th>
                        <th class="px-4 py-2 text-left text-xs">Jumlah</th>
                        <th class="px-4 py-2 text-left text-xs">Harga Toko</th>
                        <th class="px-4 py-2 text-left text-xs">Harga Jual</th>
                        <th class="px-4 py-2 text-left text-xs">Margin Persen</th>
                        <th class="px-4 py-2 text-left text-xs">Discount</th>
                        <th class="px-4 py-2 text-left text-xs">Total</th>
                        <th class="px-4 py-2 text-left text-xs">Detail</th>
                        <th class="px-4 py-2 text-left text-xs">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($jualparts as $jualpart)
                        <tr>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2"></td>
                            <td class="px-4 py-2">
                                <button class="px-4 py-2 text-white bg-black rounded-full"
                                    onclick="toggleDescription({{ $jualpart->id }})">
                                    Lihat Detail
                                </button>
                            </td>
                            <td class="px-4 py-2">
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal()">
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
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <select name="kode_barang" id="kode_barang"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        onchange="fetchSparepartData()">
                        <option value="">Pilih Kode Barang
                        </option>
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
                    <input type="number" name="jumlah" placeholder="Jumlah"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required min="1">
                    <input type="text" id="harga_toko" name="harga_toko" placeholder="Harga Toko"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="margin_persen" name="margin_persen" placeholder="Margin Persen"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="text" id="harga_jual" name="harga_jual" placeholder="Harga Jual"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <input type="number" name="discount" id="discount" placeholder="Discount"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required
                        oninput="calculateTotal()">
                    <input type="number" name="total_harga_part" id="total_harga_part"
                        placeholder="Total Harga Part" class="w-full px-4 py-2 border border-gray-300 rounded-full"
                        required readonly>
                    <input type="text" name="status" placeholder="Status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required readonly>
                    <select name="metode_pembayaran" id="metode_pembayaran"
                        class="w-full px-4 py-2 border border-gray-300 rounded-full" required>
                        <option value="">Metode Pembayaran</option>
                        <option value="Tunai">Tunai</option>
                        <option value="Kredit">Kredit</option>
                        <option value="Bank_Transfer">Bank Transfer</option>
                    </select>
                    <input type="text" name="nama_pelanggan" placeholder="Nama Pelanggan"
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
        <div class="modal-content bg-white p-6 rounded-lg max-w-sm w-full">
            <h2 class="text-center text-xl font-semibold mb-4">Edit Data Mekanik</h2>
            <form method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-id" name="id">
                <input type="text" id="edit-nama_mekanik" name="nama_mekanik"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="text" id="edit-nomor_hp" name="nomor_hp"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="text" id="edit-alamat" name="alamat"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="date" id="edit-tanggal_lahir" name="tanggal_lahir"
                    class="mb-4 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="date" id="edit-tanggal_masuk_karyawan" name="tanggal_masuk_karyawan"
                    class="mb-4 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <button type="submit"
                    class="w-full px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700">Update Data</button>
            </form>
            <button onclick="closeEditModal()"
                class="mt-2 w-full px-4 py-2 bg-black text-white rounded-full hover:bg-red-700">Close</button>
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
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const cells = row.getElementsByTagName("td");
                let match = false;

                Array.from(cells).forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(input)) {
                        match = true;
                    }
                });

                row.style.display = match ? "" : "none";
            });
        });

        document.querySelector('form').addEventListener('submit', function(event) {
            const hargaToko = document.getElementById('harga_toko').value;
            const marginPersen = document.getElementById('margin_persen').value;
            const hargaJual = document.getElementById('harga_jual').value;

            // Hapus format Rupiah dan persentase sebelum mengirim data
            document.getElementById('harga_toko').value = removeFormatRupiah(hargaToko);
            document.getElementById('margin_persen').value = removeFormatPersen(marginPersen);
            document.getElementById('harga_jual').value = removeFormatRupiah(hargaJual);
        });

        function calculateTotal() {
            // Ambil nilai harga jual dan hapus format Rupiah (jika ada)
            const hargaJual = parseFloat(document.getElementById('harga_jual').value.replace(/[^0-9.-]+/g, ""));
            const discountPersen = parseFloat(document.getElementById('discount').value);

            if (!isNaN(hargaJual)) {
                let totalHargaPart = hargaJual;

                // Jika discount persen valid, hitung discount
                if (!isNaN(discountPersen)) {
                    const discountAmount = (hargaJual * discountPersen) / 100;
                    totalHargaPart -= discountAmount;
                }

                // Pastikan total harga part tidak negatif
                totalHargaPart = Math.max(totalHargaPart, 0);

                // Update nilai total harga part
                document.getElementById('total_harga_part').value = totalHargaPart.toFixed(2);
            }
        }

        // Panggil fungsi calculateTotal saat halaman dimuat atau saat nilai discount berubah
        document.getElementById('discount').addEventListener('input', calculateTotal);

        // Panggil fungsi calculateTotal saat halaman dimuat atau saat nilai discount berubah
        document.getElementById('discount').addEventListener('input', calculateTotal);

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
                            // Isi input fields dengan data yang diterima
                            document.getElementById('nama_part').value = data.nama_part;
                            document.getElementById('stn').value = data.stn;
                            document.getElementById('tipe').value = data.tipe;
                            document.getElementById('merk').value = data.merk;
                            document.getElementById('harga_toko').value = formatRupiah(data.harga_toko);
                            document.getElementById('margin_persen').value = formatPersen(data.margin_persen);
                            document.getElementById('harga_jual').value = formatRupiah(data.harga_jual);
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

        function clearForm() {
            document.getElementById('nama_part').value = '';
            document.getElementById('stn').value = '';
            document.getElementById('tipe').value = '';
            document.getElementById('merk').value = '';
            document.getElementById('harga_toko').value = '';
            document.getElementById('margin_persen').value = '';
            document.getElementById('harga_jual').value = '';
        }

        function formatRupiah(angka) {
            // Hilangkan angka 0 di belakang koma
            const angkaTanpaNol = parseFloat(angka).toString();
            return 'Rp ' + angkaTanpaNol.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatPersen(angka) {
            // Hilangkan angka 0 di belakang koma
            const angkaTanpaNol = parseFloat(angka).toString();
            return angkaTanpaNol + ' %';
        }

        // Date filter function
        document.getElementById("date-input").addEventListener("change", function() {
            const filterDate = this.value;
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const dateCell = row.cells[4];
                const dateText = dateCell ? dateCell.textContent.trim() : "";

                row.style.display = filterDate && dateText !== filterDate ? "none" : "";
            });
        });

        function openEditModal(id, kode_barang, nama_part, stn, tipe, merk, tanggal_keluar, jumlah, harga_toko,
            harga_jual,
            margin_persen, discount) {
            // Menetapkan nilai untuk input di modal edit
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-nama_mekanik").value = kode_barang;
            document.getElementById("edit-nomor_hp").value = nama_part;
            document.getElementById("edit-alamat").value = stn;
            document.getElementById("edit-tanggal_lahir").value = tipe;
            document.getElementById("edit-tanggal_masuk_mekanik").value = merk;
            document.getElementById("edit-tanggal_masuk_karyawan").value = tanggal_keluar;
            document.getElementById("edit-tanggal_keluar").value = jumlah;
            document.getElementById("edit-tanggal_masuk_karyawan").value = harga_toko;
            document.getElementById("edit-tanggal_masuk_karyawan").value = harga_jual;
            document.getElementById("edit-tanggal_masuk_karyawan").value = margin_persen;
            document.getElementById("edit-tanggal_masuk_karyawan").value = discount;

            // Update form action untuk mencocokkan dengan ID yang akan diupdate
            const formAction = document.querySelector("#edit-modal form");
            formAction.action = "/jualpart/" + id; // Update URL dengan ID mekanik

            // Tampilkan modal edit
            document.getElementById("edit-modal").classList.remove("hidden");
        }


        function closeEditModal() {
            document.getElementById("edit-modal").classList.add("hidden");
        }
    </script>
</body>

</html>
