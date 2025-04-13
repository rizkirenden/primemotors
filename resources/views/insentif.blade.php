<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Insentif</title>
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
        <p class="text-white">Laporan Insentif</p>
        <!-- Card -->
        <div class="bg-white shadow-lg rounded-lg ">
            <!-- Filter Section -->
            <div class="bg-black border-2 border-white rounded-tl-lg rounded-tr-lg">
                <div class="mb-1 p-2">
                    <div class="flex justify-between items-center space-x-4">
                        <!-- Search -->
                        <form action="{{ route('printpdfinsentif') }}" method="GET">
                            <div class="flex items-center space-x-4 w-full">
                                <!-- Search Input -->
                                <input type="text" id="search-input" name="search" placeholder="Search..."
                                    class="px-4 py-2 rounded-full text-black w-64 bg-white border border-gray-300"
                                    value="{{ request('search') }}" onkeyup="searchTable()">

                                <!-- Month Filter -->
                                <input type="month" id="month-filter" name="month" placeholder="Filter by Month"
                                    class="px-4 py-2 rounded-full text-black bg-white border border-gray-300"
                                    value="{{ request('month') }}">

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
                        <th class="px-4 py-2 text-left">Nama Mekanik</th>
                        <th class="px-4 py-2 text-left">Total Pengerjaan</th>
                        <th class="px-4 py-2 text-left">Total Insentif</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($laporanInsentif as $insentif)
                        <tr>
                            <td class="px-4 py-2">{{ $insentif->nama_mekanik }}</td>
                            <td class="px-4 py-2">{{ $insentif->total_pengerjaan }}</td>
                            <td class="px-4 py-2">Rp. {{ number_format($insentif->total, 0, ',', '.') }}</td>
                            <td class="px-4 py-2">
                                <form action="{{ route('insentif.print', $insentif->nama_mekanik) }}" method="GET">
                                    <input type="hidden" name="month" value="{{ request('month') }}">
                                    <button type="submit" class="text-black hover:text-black ml-3">
                                        <i class="fas fa-print"></i> Print Insentif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrapper">
                <div class="pagination-container" id="pagination">
                    <!-- Previous Page Link -->
                    @if ($laporanInsentif->onFirstPage())
                        <span class="disabled m-1 px-4 py-2 rounded-full">Prev</span>
                    @else
                        <a href="{{ $laporanInsentif->previousPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Prev</a>
                    @endif

                    <!-- Page Number Links -->
                    @for ($i = 1; $i <= $laporanInsentif->lastPage(); $i++)
                        <a href="{{ $laporanInsentif->url($i) }}"
                            class="px-4 py-2 m-1 rounded-full {{ $i == $laporanInsentif->currentPage() ? 'bg-black text-white' : '' }}">
                            {{ $i }}
                        </a>
                    @endfor

                    <!-- Next Page Link -->
                    @if ($laporanInsentif->hasMorePages())
                        <a href="{{ $laporanInsentif->nextPageUrl() }}"
                            class="px-4 py-2 m-1 rounded-full hover:bg-black hover:text-white">Next</a>
                    @else
                        <span class="disabled m-1 px-4 py-2 rounded-full">Next</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById("search-input").value.toLowerCase();
            const table = document.querySelector("table");
            const trs = table.querySelectorAll("tbody tr");

            trs.forEach(tr => {
                const namaMekanik = tr.children[0].textContent.toLowerCase();
                if (namaMekanik.includes(input)) {
                    tr.style.display = "";
                } else {
                    tr.style.display = "none";
                }
            });
        }

        function showErrorPopup(message) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
            });
        }
        document.getElementById('month-filter').addEventListener('change', function() {
            const month = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            window.location.href = url.toString();
        });
    </script>
</body>

</html>
