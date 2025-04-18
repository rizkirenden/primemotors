<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="images/silver.PNG">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Add borders to the table header and body */
        thead {
            border-bottom: 2px solid #ccc;
        }

        tbody {
            border-top: 1px solid #ccc;
        }

        /* Style pagination buttons */
        #pagination button:hover {
            background-color: #000000;
        }

        /* Border separator between tbody and pagination */
        .pagination-container {
            border-top: 2px solid #ccc;
            margin-top: 20px;
            padding-top: 10px;
        }

        /* Align pagination container */
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
        }

        /* Adjust pagination container for center alignment */
        .pagination-container {
            display: flex;
            justify-content: center;
            flex-grow: 1;
        }

        #pagination button {
            padding: 5px 10px;
            width: 40px;
            border-radius: 50%;
        }
    </style>
</head>

<body class="bg-black flex h-screen">

    @include('sidebar')
    @include('loading')
    <div class="flex-1 p-3 overflow-x-auto">
        <h1 class="text-2xl text-white mb-4">Data Pengguna</h1>

        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

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

                            <!-- Print PDF Button -->
                            <button
                                class="px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 border border-gray-300">
                                <i class="fas fa-file-pdf text-black"></i> Print PDF
                            </button>
                        </div>

                        <!-- Add Data Button -->
                        <button onclick="openModal('add')"
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
                        <th class="px-4 py-2 text-left">Nama Pengguna</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Level</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($penggunas as $pengguna)
                        <tr>
                            <td class="px-4 py-2">{{ $pengguna->id }}</td>
                            <td class="px-4 py-2">{{ $pengguna->nama }}</td>
                            <td class="px-4 py-2">{{ $pengguna->email }}</td>
                            <td class="px-4 py-2">
                                @switch($pengguna->role)
                                    @case('superadmin')
                                        Super Admin
                                    @break

                                    @case('admin')
                                        Admin
                                    @break

                                    @case('bengkel')
                                        Bengkel
                                    @break

                                    @case('owner')
                                        Owner
                                    @break
                                @endswitch
                            </td>
                            <td class="px-4 py-2">
                                <button
                                    onclick="openModal('edit', {{ $pengguna->id }}, '{{ $pengguna->nama }}', '{{ $pengguna->email }}', '{{ $pengguna->role }}')"
                                    class="text-blue-500 hover:text-blue-700 mr-3">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('pengguna.destroy', $pengguna->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
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
                <div class="pagination-container" id="pagination"></div>
            </div>
        </div>
    </div>

    <!-- Modal for adding/editing user -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4" id="modalTitle">Tambah Pengguna</h2>
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">
                <input type="hidden" id="userId" name="id">

                <div class="mb-4">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Pengguna</label>
                    <input type="text" id="nama" name="nama"
                        class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-full" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email"
                        class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-full" required>
                </div>
                <!-- Password field with show/hide functionality -->
                <div class="mb-4" id="passwordField">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password"
                        class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-full">
                    <div class="mt-2 flex items-center">
                        <input type="checkbox" id="showPassword" class="mr-2" onclick="togglePasswordVisibility()">
                        <label for="showPassword" class="text-sm text-gray-600">Tampilkan Password</label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-sm font-medium text-gray-700">Level</label>
                    <select id="role" name="role"
                        class="mt-1 px-4 py-2 w-full border border-gray-300 rounded-full" required>
                        <option value="superadmin">Super Admin</option>
                        <option value="admin">Admin</option>
                        <option value="bengkel">Bengkel</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-black text-white rounded-full hover:bg-red-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700">Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Function to toggle password visibility
        function togglePasswordVisibility() {
            var passwordField = document.getElementById('password');
            var showPasswordCheckbox = document.getElementById('showPassword');
            if (showPasswordCheckbox.checked) {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }

        // Function to open modal for add or edit
        function openModal(action, id = null, nama = null, email = null, role = null) {
            const modal = document.getElementById('userModal');
            const form = document.getElementById('userForm');
            const modalTitle = document.getElementById('modalTitle');
            const passwordField = document.getElementById('passwordField');

            if (action === 'add') {
                modalTitle.textContent = 'Tambah Pengguna';
                form.action = "{{ route('pengguna.store') }}";
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('userId').value = '';
                document.getElementById('nama').value = '';
                document.getElementById('email').value = '';
                document.getElementById('password').value = '';
                document.getElementById('role').value = 'owner';
                passwordField.style.display = 'block';
            } else if (action === 'edit') {
                modalTitle.textContent = 'Edit Pengguna';
                form.action = "/pengguna/" + id;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('userId').value = id;
                document.getElementById('nama').value = nama;
                document.getElementById('email').value = email;
                document.getElementById('role').value = role;
                passwordField.style.display = 'none';
            }

            modal.classList.remove('hidden');
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
        }

        // JavaScript for auto-search
        function searchTable() {
            const input = document.getElementById("search-input");
            const filter = input.value.toLowerCase();
            const rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const columns = row.getElementsByTagName("td");
                let match = false;

                // Check each column in the row
                for (let i = 0; i < columns.length; i++) {
                    if (columns[i].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break; // If there's a match, go to the next row
                    }
                }

                // Show or hide row based on search match
                if (match) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Pagination function
        let currentPage = 1;
        const rowsPerPage = 5;

        function paginate() {
            const rows = document.querySelectorAll("tbody tr");
            const totalRows = rows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            // Show or hide rows based on the current page
            rows.forEach((row, index) => {
                const startIdx = (currentPage - 1) * rowsPerPage;
                const endIdx = currentPage * rowsPerPage;

                if (index >= startIdx && index < endIdx) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            // Update pagination controls
            const paginationContainer = document.getElementById("pagination");
            paginationContainer.innerHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement("button");
                pageButton.innerText = i;
                pageButton.classList.add("px-6", "py-3", "border", "border-gray-300", "m-1", "rounded", "bg-black",
                    "text-white", "hover:bg-gray-700");
                pageButton.addEventListener("click", () => {
                    currentPage = i;
                    paginate();
                });
                paginationContainer.appendChild(pageButton);
            }
        }

        window.onload = paginate; // Run pagination when the page loads
    </script>
</body>

</html>
