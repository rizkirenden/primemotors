<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Mekanik</title>
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
                        <th class="px-4 py-2 text-left">Nama Mekanik</th>
                        <th class="px-4 py-2 text-left">Nomor HP</th>
                        <th class="px-4 py-2 text-left">Alamat</th>
                        <th class="px-4 py-2 text-left">Tanggal Lahir</th>
                        <th class="px-4 py-2 text-left">Tanggal Masuk</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($mekaniks as $mekanik)
                        <tr>
                            <td class="px-4 py-2">{{ $mekanik->nama_mekanik }}</td>
                            <td class="px-4 py-2">{{ $mekanik->nomor_hp }}</td>
                            <td class="px-4 py-2">{{ $mekanik->alamat }}</td>
                            <td class="px-4 py-2">{{ $mekanik->tanggal_lahir }}</td>
                            <td class="px-4 py-2">{{ $mekanik->tanggal_masuk_karyawan }}</td>
                            <td class="px-4 py-2">
                                <a href="#" class="text-blue-500 hover:text-blue-700 mr-3"
                                    onclick="openEditModal({{ $mekanik->id }}, '{{ $mekanik->nama_mekanik }}', '{{ $mekanik->nomor_hp }}', '{{ $mekanik->alamat }}', '{{ $mekanik->tanggal_lahir }}')">
                                    <i class="fas fa-edit"></i>
                                </a>


                                <form action="{{ route('datamekanik.destroy', $mekanik->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination Section -->
            <div class="pagination-wrapper">
                <div class="pagination-container" id="pagination">
                    <!-- Previous Page Link -->
                    @if ($mekaniks->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $mekaniks->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $mekaniks->lastPage(); $i++)
                        <a href="{{ $mekaniks->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white
                @if ($mekaniks->currentPage() == $i) active @endif">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($mekaniks->hasMorePages())
                        <a href="{{ $mekaniks->nextPageUrl() }}"
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
        <div class="modal-content bg-white p-6 rounded-lg max-w-sm w-full">
            <h2 class="text-center text-xl font-semibold mb-4">Tambah Data Mekanik</h2>
            <form action="{{ route('datamekanik.store') }}" method="POST">
                @csrf
                <input type="text" name="nama_mekanik" placeholder="Nama Mekanik"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="text" name="nomor_hp" placeholder="Nomor HP"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="text" name="alamat" placeholder="Alamat"
                    class="mb-2 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="date" name="tanggal_lahir"
                    class="mb-4 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <input type="date" name="tanggal_masuk_karyawan"
                    class="mb-4 w-full px-4 py-2 border border-gray-300 rounded-full" required>
                <button type="submit"
                    class="w-full px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700">Tambah Data</button>
            </form>
            <button onclick="closeModal()"
                class="mt-2 w-full px-4 py-2 bg-black text-white rounded-full hover:bg-red-700">Close</button>
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

        function openEditModal(id, nama_mekanik, nomor_hp, alamat, tanggal_lahir, tanggal_masuk_karyawan) {
            // Menetapkan nilai untuk input di modal edit
            document.getElementById("edit-id").value = id;
            document.getElementById("edit-nama_mekanik").value = nama_mekanik;
            document.getElementById("edit-nomor_hp").value = nomor_hp;
            document.getElementById("edit-alamat").value = alamat;
            document.getElementById("edit-tanggal_lahir").value = tanggal_lahir;
            document.getElementById("edit-tanggal_masuk_mekanik").value = tanggal_masuk_karyawan;

            // Update form action untuk mencocokkan dengan ID yang akan diupdate
            const formAction = document.querySelector("#edit-modal form");
            formAction.action = "/datamekanik/" + id; // Update URL dengan ID mekanik

            // Tampilkan modal edit
            document.getElementById("edit-modal").classList.remove("hidden");
        }


        function closeEditModal() {
            document.getElementById("edit-modal").classList.add("hidden");
        }
    </script>
</body>

</html>
