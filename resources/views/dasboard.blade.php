<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-black flex h-screen">

    <!-- Sidebar -->
    @include('sidebar')

    <!-- Main Content -->
    <div class="flex-1 p-4 overflow-y-auto">
        <h1 class="text-2xl text-white mb-4">Welcome to the Dashboard</h1>

        <!-- Cards Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
            <!-- Card 1: Mekanik -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Mekanik</h2>
                <p>{{ $mekanik }}</p>
                <!-- Icon -->
                <i class="fas fa-cogs absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 2: Sparepat -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Sparepat</h2>
                <p>{{ $sparepat }}</p>
                <!-- Icon -->
                <i class="fas fa-wrench absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 3: Service -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Service</h2>
                <p>{{ $service }}</p>
                <!-- Icon -->
                <i class="fas fa-tools absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 4: Showroom -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Showroom</h2>
                <p>{{ $showroom }}</p>
                <!-- Icon -->
                <i class="fas fa-chart-line absolute top-2 right-2 text-xl"></i>
            </div>
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Petugas</h2>
                <p>50</p>
                <!-- Icon -->
                <i class="fas fa-user-tie absolute top-2 right-2 text-xl"></i>
            </div>
        </div>

        <!-- Transactions Chart -->
        <div class="mt-4 bg-white p-2 rounded-lg shadow-lg w-full mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl text-black">Transactions (Invoice, Part Keluar, Part Masuk)</h2>

                <!-- Year Selection Dropdown -->
                <div class="flex items-center">
                    <label for="yearSelect" class="text-black mr-2">Select Year:</label>
                    <select id="yearSelect" class="bg-white text-black border-black border-2 rounded-lg">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <canvas id="transactionsChart" class="w-full" style="height: 250px;"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('transactionsChart').getContext('2d');

            // Data dari controller
            const invoiceData = @json($invoiceData);
            const partKeluarData = @json($partKeluarData);
            const partMasukData = @json($partMasukData);

            // Load gambar logo (silver.PNG)
            const logo = new Image();
            logo.src = '{{ asset('images/silver.PNG') }}'; // Path ke gambar silver.PNG

            // Plugin untuk menambahkan background gambar
            const plugin = {
                id: 'customCanvasBackgroundImage',
                beforeDraw: (chart) => {
                    if (logo.complete) {
                        const ctx = chart.ctx;
                        const {
                            top,
                            left,
                            width,
                            height
                        } = chart.chartArea;

                        // Atur ukuran gambar (misalnya, 50% dari lebar chart)
                        const logoWidth = width * 0.3; // 50% dari lebar chart
                        const logoHeight = (logo.height / logo.width) * logoWidth; // Pertahankan rasio aspek

                        // Hitung posisi x dan y untuk menempatkan gambar di tengah
                        const x = left + width / 2 - logoWidth / 2;
                        const y = top + height / 2 - logoHeight / 2;

                        // Gambar gambar di tengah chart
                        ctx.drawImage(logo, x, y, logoWidth, logoHeight);
                    } else {
                        logo.onload = () => chart.draw();
                    }
                }
            };

            // Create the chart with the data
            const transactionsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                            label: 'Transactions',
                            data: invoiceData,
                            borderColor: '#000000', // Black line
                            backgroundColor: 'rgba(0, 0, 0, 0.1)', // Semi-transparent black fill
                            tension: 0.1,
                            borderWidth: 2,
                            pointBackgroundColor: '#000000',
                            pointRadius: 4,
                        },
                        {
                            label: 'Part Keluar',
                            data: partKeluarData,
                            borderColor: '#FF0000', // Red line
                            backgroundColor: 'rgba(255, 0, 0, 0.1)', // Semi-transparent red fill
                            tension: 0.1,
                            borderWidth: 2,
                            pointBackgroundColor: '#FF0000',
                            pointRadius: 4,
                        },
                        {
                            label: 'Part Masuk',
                            data: partMasukData,
                            borderColor: '#00FF00', // Green line
                            backgroundColor: 'rgba(0, 255, 0, 0.1)', // Semi-transparent green fill
                            tension: 0.1,
                            borderWidth: 2,
                            pointBackgroundColor: '#00FF00',
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            grid: {
                                color: '#d3d3d3' // Light gray grid
                            },
                            ticks: {
                                color: '#000000', // Black tick labels
                            }
                        },
                        y: {
                            grid: {
                                color: '#d3d3d3' // Light gray grid
                            },
                            ticks: {
                                color: '#000000', // Black tick labels
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#000000', // Black legend labels
                            }
                        }
                    },
                    layout: {
                        padding: 10,
                    },
                },
                plugins: [plugin], // Tambahkan plugin di sini
            });

            // Update chart when year is selected
            document.getElementById('yearSelect').addEventListener('change', (event) => {
                const selectedYear = event.target.value;

                // Lakukan AJAX request untuk mengambil data berdasarkan tahun yang dipilih
                fetch(`/get-data-by-year?year=${selectedYear}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update the chart's data
                        transactionsChart.data.datasets[0].data = data.invoiceData;
                        transactionsChart.data.datasets[1].data = data.partKeluarData;
                        transactionsChart.data.datasets[2].data = data.partMasukData;
                        transactionsChart.update();
                    });
            });
        </script>
    </div>
</body>

</html>
