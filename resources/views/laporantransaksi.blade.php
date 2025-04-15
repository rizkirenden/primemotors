<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <h1 class="text-2xl text-white mb-4">Laporan Transaksi</h1>
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
        <!-- Card -->
        <div class="bg-white shadow-lg rounded-lg ">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form action="{{ route('invoice.printall') }}" method="GET">
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
                                    <i class="fas fa-file-pdf text-black"></i> Print PDF
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="min-w-full table-auto text-black">
                <thead class="bg-white">
                    <tr>
                        <th class="px-4 py-2 text-left">No Invoice</th>
                        <th class="px-4 py-2 text-left">Tanggal Invoice</th>
                        <th class="px-4 py-2 text-left">No SPK</th>
                        <th class="px-4 py-2 text-left">Nama Mekanik</th>
                        <th class="px-4 py-2 text-left">Nama Part</th>
                        <th class="px-4 py-2 text-left">Harga Part</th>
                        <th class="px-4 py-2 text-left">Discount Part</th>
                        <th class="px-4 py-2 text-left">Total Harga Part</th>
                        <th class="px-4 py-2 text-left">Jenis Pekerjaan</th>
                        <th class="px-4 py-2 text-left">Ongkos Pengerjaan</th>
                        <th class="px-4 py-2 text-left">Discount Ongkos</th>
                        <th class="px-4 py-2 text-left">Total Ongkos</th>
                        <th class="px-4 py-2 text-left">PPN</th>
                        <th class="px-4 py-2 text-left">Total Harga</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-2">{{ $invoice->no_invoice }}</td>
                            <td class="px-4 py-2">{{ $invoice->tanggal_invoice }}</td>
                            <td class="px-4 py-2">{{ $invoice->dataservice->no_spk }}</td>
                            <td class="px-4 py-2">{{ $invoice->nama_mekanik }}</td>
                            <td class="px-4 py-2">{{ $invoice->nama_part }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->harga_jual, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $invoice->discount_part }}%</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->total_harga_part, 0, ',', '.') }}</td>
                            @php
                                $jenisPekerjaan = json_decode($invoice->jenis_pekerjaan, true);
                                $firstItem = $jenisPekerjaan[0] ?? ''; // Ambil elemen pertama jika ada
                            @endphp
                            <td class="px-4 py-2">{{ $firstItem }}</td>
                            <td class="px-4 py-2">Rp
                                {{ number_format(rtrim(rtrim(json_decode($invoice->ongkos_pengerjaan)[0], '0'), '.'), 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-2">{{ $invoice->discount_ongkos_pengerjaan }}%</td>
                            <td class="px-4 py-2">Rp
                                {{ number_format($invoice->total_harga_uraian_pekerjaan, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">{{ $invoice->ppn }}%</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <button
                                    onclick="openUpdateModal({{ $invoice->id }}, {{ $invoice->discount_part }}, {{ $invoice->discount_ongkos_pengerjaan }}, {{ $invoice->ppn }})"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-save"></i>
                                </button>
                                <button onclick="confirmDelete({{ $invoice->id }})"
                                    class="text-red-500 hover:text-red-700 ml-3">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                                <form id="delete-form-{{ $invoice->id }}"
                                    action="{{ route('invoice.destroy', $invoice->id) }}" method="POST"
                                    class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                                <a href="{{ route('invoice.print', $invoice->id) }}"
                                    class="text-black hover:text-black ml-3">
                                    <i class="fas fa-print"></i> Print Invoice
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">
                <div class="pagination-container" id="pagination">
                    <!-- Previous Page Link -->
                    @if ($invoices->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $invoices->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $invoices->lastPage(); $i++)
                        <a href="{{ $invoices->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $invoices->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($invoices->hasMorePages())
                        <a href="{{ $invoices->nextPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Next</a>
                    @else
                        <span class="disabled m-1 px-4 py-2 rounded-full">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Update -->
    <div id="updateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <h2 class="text-lg font-bold mb-4">Update Invoice</h2>
            <form id="updateForm">
                <div class="space-y-4">
                    <!-- Discount Part -->
                    <div>
                        <label for="modal_discount_part" class="block text-sm font-medium text-gray-700">Discount Part
                            (%)</label>
                        <input type="number" id="modal_discount_part" name="discount_part"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <!-- Discount Ongkos -->
                    <div>
                        <label for="modal_discount_ongkos" class="block text-sm font-medium text-gray-700">Discount
                            Ongkos (%)</label>
                        <input type="number" id="modal_discount_ongkos" name="discount_ongkos"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                    <!-- PPN -->
                    <div>
                        <label for="modal_ppn" class="block text-sm font-medium text-gray-700">PPN (%)</label>
                        <input type="number" id="modal_ppn" name="ppn"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-black text-white rounded-md hover:bg-gray-600">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-black text-white rounded-md hover:bg-black">Update</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let currentInvoiceId = null;

        function searchTable() {
            let input = document.getElementById("search-input");
            let filter = input.value.toLowerCase();
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            Array.from(rows).forEach(row => {
                let cells = row.getElementsByTagName("td");
                let found = false;

                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(filter)) {
                        found = true;
                    }
                });

                row.style.display = found ? "" : "none";
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
                let cells = row.getElementsByTagName("td");
                let dateCell = cells[1]; // Kolom tanggal (index 1)

                if (dateCell) {
                    let rowDate = new Date(dateCell.textContent.trim());

                    let isWithinRange = true;

                    if (start && rowDate < start) {
                        isWithinRange = false;
                    }
                    if (end && rowDate > end) {
                        isWithinRange = false;
                    }

                    row.style.display = isWithinRange ? "" : "none";
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

                    if (start && rowDate < start) {
                        found = false;
                    }
                    if (end && rowDate > end) {
                        found = false;
                    }
                }

                row.style.display = found ? "" : "none";
            });
        }

        document.getElementById("search-input").addEventListener("keyup", filterTable);
        document.getElementById("date-start").addEventListener("change", filterTable);
        document.getElementById("date-end").addEventListener("change", filterTable);

        function openUpdateModal(id, discount_part, discount_ongkos, ppn) {
            currentInvoiceId = id;

            document.getElementById('modal_discount_part').value = discount_part;
            document.getElementById('modal_discount_ongkos').value = discount_ongkos;
            document.getElementById('modal_ppn').value = ppn;
            document.getElementById('updateModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }

        document.getElementById('updateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const discount_part = document.getElementById('modal_discount_part').value;
            const discount_ongkos = document.getElementById('modal_discount_ongkos').value;
            const ppn = document.getElementById('modal_ppn').value;

            fetch(`/invoice/update/${currentInvoiceId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        discount_part: discount_part,
                        discount_ongkos_pengerjaan: discount_ongkos,
                        ppn: ppn
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Invoice updated successfully!',
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        showErrorPopup(data.message || 'Failed to update invoice.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorPopup('An error occurred while updating the invoice.');
                });

            closeModal();
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</body>

</html>
