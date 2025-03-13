<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi</title>
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
        <p class="text-white">Laporan Transaksi</p>
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
                        <th class="px-4 py-2 text-left">No SPK</th>
                        <th class="px-4 py-2 text-left">Tanggal Invoice</th>
                        <th class="px-4 py-2 text-left">Costumer</th>
                        <th class="px-4 py-2 text-left">No Polisi</th>
                        <th class="px-4 py-2 text-left">Nama Mekanik</th>
                        <th class="px-4 py-2 text-left">Sparepat</th>
                        <th class="px-4 py-2 text-left">Total Biaya Jasa</th>
                        <th class="px-4 py-2 text-left">Discount</th>
                        <th class="px-4 py-2 text-left">PPN</th>
                        <th class="px-4 py-2 text-left">Total</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="px-4 py-2">{{ $invoice->dataservice->no_spk }}</td>
                            <td class="px-4 py-2">{{ $invoice->tanggal_invoice }}</td>
                            <td class="px-4 py-2">{{ $invoice->dataservice->costumer }}</td>
                            <td class="px-4 py-2">{{ $invoice->dataservice->no_polisi }}</td>
                            <td class="px-4 py-2">{{ $invoice->dataservice->nama_mekanik }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->total_harga_part, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->biaya_jasa, 0, ',', '.') }}</td>
                            <td class="px-4 py-2"> {{ number_format($invoice->discount, 0, ',', '.') }} %</td>
                            <td class="px-4 py-2"> {{ number_format($invoice->ppn, 0, ',', '.') }} %</td>
                            <td class="px-4 py-2">Rp {{ number_format($invoice->total_harga, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <button
                                    onclick="openUpdateModal({{ $invoice->id }}, {{ $invoice->discount }}, {{ $invoice->ppn }})"
                                    class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-save"></i>
                                </button>
                                <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 ml-3">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
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
                    <!-- Discount -->
                    <div>
                        <label for="modal_discount" class="block text-sm font-medium text-gray-700">Discount (%)</label>
                        <input type="number" id="modal_discount" name="discount"
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
                // Skip rows that are description rows
                if (row.classList.contains('description-row')) {
                    return; // Jangan proses baris detail
                }

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

                // Tampilkan atau sembunyikan baris detail terkait
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

            // Konversi tanggal ke objek Date
            let start = startDate ? new Date(startDate) : null;
            let end = endDate ? new Date(endDate) : null;

            Array.from(rows).forEach(row => {
                // Skip rows that are description rows
                if (row.classList.contains('description-row')) {
                    return; // Jangan proses baris detail
                }

                let cells = row.getElementsByTagName("td");
                let dateCell = cells[1]; // Kolom tanggal (index 1)

                if (dateCell) {
                    let rowDate = new Date(dateCell.textContent.trim());

                    // Filter berdasarkan rentang tanggal
                    let isWithinRange = true;

                    if (start && rowDate < start) {
                        isWithinRange = false;
                    }
                    if (end && rowDate > end) {
                        isWithinRange = false;
                    }

                    // Tampilkan atau sembunyikan baris berdasarkan hasil filter
                    row.style.display = isWithinRange ? "" : "none";

                    // Jangan sembunyikan baris detail terkait
                    let descriptionRow = table.querySelector(`#desc-${row.dataset.id}`);
                    if (descriptionRow) {
                        descriptionRow.style.display = isWithinRange ? "" : "none";
                    }
                }
            });
        }

        // Tambahkan event listener ke input tanggal
        document.getElementById("date-start").addEventListener("change", filterByDateRange);
        document.getElementById("date-end").addEventListener("change", filterByDateRange);

        function filterTable() {
            let search = document.getElementById("search-input").value.toLowerCase();
            let startDate = document.getElementById("date-start").value;
            let endDate = document.getElementById("date-end").value;
            let table = document.querySelector("table tbody");
            let rows = table.getElementsByTagName("tr");

            // Konversi tanggal ke objek Date
            let start = startDate ? new Date(startDate) : null;
            let end = endDate ? new Date(endDate) : null;

            Array.from(rows).forEach(row => {
                // Skip rows that are description rows
                if (row.classList.contains('description-row')) {
                    return; // Jangan proses baris detail
                }

                let cells = row.getElementsByTagName("td");
                let found = false;

                // Cek apakah baris cocok dengan pencarian
                Array.from(cells).forEach(cell => {
                    if (cell && cell.textContent.toLowerCase().includes(search)) {
                        found = true;
                    }
                });

                // Cek apakah tanggal dalam rentang yang dipilih
                let dateCell = cells[1]; // Kolom tanggal (index 1)
                if (dateCell) {
                    let rowDate = new Date(dateCell.textContent.trim());

                    if (start && rowDate < start) {
                        found = false;
                    }
                    if (end && rowDate > end) {
                        found = false;
                    }
                }

                // Tampilkan atau sembunyikan baris berdasarkan hasil filter
                row.style.display = found ? "" : "none";

                // Tampilkan atau sembunyikan baris detail terkait
                let descriptionRow = table.querySelector(`#desc-${row.dataset.id}`);
                if (descriptionRow) {
                    descriptionRow.style.display = found ? "" : "none";
                }
            });
        }

        // Tambahkan event listener ke input pencarian dan tanggal
        document.getElementById("search-input").addEventListener("keyup", filterTable);
        document.getElementById("date-start").addEventListener("change", filterTable);
        document.getElementById("date-end").addEventListener("change", filterTable);

        // Fungsi untuk memformat input dengan "Rp"
        function formatCurrency(input) {
            let value = input.value.replace(/\D/g, '');
            if (value.length > 0) {
                input.value = 'Rp ' + parseInt(value).toLocaleString('id-ID');
            } else {
                input.value = '';
            }
        }

        // Fungsi untuk menghapus "Rp" dan mengembalikan nilai numerik
        function getNumericValue(currencyString) {
            return parseInt(currencyString.replace(/\D/g, ''));
        }

        // Fungsi untuk membuka modal dan mengisi data

        // Fungsi untuk membuka modal dan mengisi data
        function openUpdateModal(id, discount, ppn) {
            console.log("Opening modal for invoice ID:", id);
            console.log("Discount:", discount);
            console.log("PPN:", ppn);

            currentInvoiceId = id;

            // Isi nilai discount dan ppn
            document.getElementById('modal_discount').value = discount;
            document.getElementById('modal_ppn').value = ppn;
            document.getElementById('updateModal').classList.remove('hidden');
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }

        // Fungsi untuk mengirim data update ke server
        document.getElementById('updateForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const discount = document.getElementById('modal_discount').value;
            const ppn = document.getElementById('modal_ppn').value;

            fetch(`/invoice/update/${currentInvoiceId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        discount: discount,
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
                            location.reload(); // Reload halaman setelah update
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

        // Fungsi untuk menampilkan pop-up error menggunakan SweetAlert
        function showErrorPopup(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        }
    </script>
</body>

</html>
