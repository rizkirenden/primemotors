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
    <style>
        .main-content {
            display: grid;
            grid-template-columns: auto 1fr;
            min-height: 100vh;
        }

        .dashboard-content {
            display: grid;
            grid-template-rows: auto auto auto;
            gap: 1rem;
            padding: 1rem;
            overflow-y: auto;
            height: 100vh;
        }

        .card-row {
            display: grid;
            gap: 1rem;
        }

        .row-1 {
            grid-template-columns: repeat(3, 1fr);
        }

        .row-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        @media (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
            }

            .card-row,
            .row-1,
            .row-2 {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body class="bg-black">

    <!-- Main Container -->
    <div class="main-content">
        <!-- Sidebar -->
        @include('sidebar')

        <!-- Right Content Area -->
        <div class="dashboard-content">
            <!-- Header -->

            <h1 class="text-2xl font-bold text-white">Welcome to the Dashboard</h1>


            <!-- Row 1: Mekanik, Sparepart, Service -->
            <div class="card-row row-1">
                <!-- Card Mekanik -->
                <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                    <h2 class="text-lg font-semibold mb-2">Mekanik</h2>
                    <p class="text-2xl font-bold">{{ $mekanik }}</p>
                    <i class="fas fa-cogs absolute top-4 right-4 text-2xl text-black"></i>
                </div>

                <!-- Card Sparepart -->
                <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                    <h2 class="text-lg font-semibold mb-2">Sparepart</h2>
                    <p class="text-2xl font-bold">{{ $sparepat }}</p>
                    <i class="fas fa-wrench absolute top-4 right-4 text-2xl text-black"></i>
                </div>

                <!-- Card Service -->
                <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                    <h2 class="text-lg font-semibold mb-2">Service</h2>
                    <p class="text-2xl font-bold">{{ $service }}</p>
                    <i class="fas fa-tools absolute top-4 right-4 text-2xl text-black"></i>
                </div>
            </div>

            <!-- Row 2: Petugas, Showroom -->
            <div class="card-row row-2">
                <!-- Card Petugas -->
                <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                    <h2 class="text-lg font-semibold mb-2">Petugas</h2>
                    <p class="text-2xl font-bold">50</p>
                    <i class="fas fa-user-tie absolute top-4 right-4 text-2xl text-black"></i>
                </div>

                <!-- Card Showroom -->
                <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                    <h2 class="text-lg font-semibold mb-2">Showroom</h2>
                    <p class="text-2xl font-bold">{{ $showroom }}</p>
                    <i class="fas fa-chart-line absolute top-4 right-4 text-2xl text-black"></i>
                </div>
            </div>

            <!-- Row 3: Charts -->
            <div class="card-row row-2">
                <!-- Financial Transactions Chart -->
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Financial Transactions</h2>
                        <!-- Year Selection Dropdown -->
                        <div class="flex items-center">
                            <label for="yearSelect" class="text-gray-700 mr-2">Year:</label>
                            <select id="yearSelect"
                                class="bg-white text-gray-800 border border-gray-300 rounded-lg px-3 py-1">
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="financialChart"></canvas>
                    </div>
                </div>

                <!-- Monthly Parts Movement Chart -->
                <div class="bg-white p-4 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Monthly Parts Movement</h2>
                        <div class="text-sm text-gray-500">
                            <span class="inline-block w-3 h-3 bg-red-500 rounded-full mr-1"></span> Keluar
                            <span class="inline-block w-3 h-3 bg-blue-500 rounded-full ml-3 mr-1"></span> Masuk
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyPartsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data dari controller
        const invoiceData = @json($invoiceData);
        const jualPartData = @json($jualPartData);
        const partKeluarData = @json($partKeluarData);
        const partMasukData = @json($partMasukData);

        // Load gambar logo (silver.PNG)
        const logo = new Image();
        logo.src = '{{ asset('images/silver.PNG') }}';

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

                    const logoWidth = width * 0.3;
                    const logoHeight = (logo.height / logo.width) * logoWidth;

                    const x = left + width / 2 - logoWidth / 2;
                    const y = top + height / 2 - logoHeight / 2;

                    ctx.drawImage(logo, x, y, logoWidth, logoHeight);
                } else {
                    logo.onload = () => chart.draw();
                }
            }
        };

        // Financial Chart (Line Chart)
        const financialCtx = document.getElementById('financialChart').getContext('2d');
        const financialChart = new Chart(financialCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                        label: 'Service Income (Rp)',
                        data: invoiceData,
                        borderColor: '#000000',
                        backgroundColor: 'rgba(0, 0, 0, 0.1)',
                        tension: 0.1,
                        borderWidth: 2,
                        pointBackgroundColor: '#000000',
                        pointRadius: 4,
                    },
                    {
                        label: 'Part Sales (Rp)',
                        data: jualPartData,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.1,
                        borderWidth: 2,
                        pointBackgroundColor: '#3B82F6',
                        pointRadius: 4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            color: '#6b7280',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: '#374151',
                            font: {
                                size: 12
                            }
                        },
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                                return label;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            },
            plugins: [plugin],
        });

        // Monthly Parts Movement Chart (Bar Chart)
        const monthlyPartsCtx = document.getElementById('monthlyPartsChart').getContext('2d');
        const monthlyPartsChart = new Chart(monthlyPartsCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                        label: 'Part Keluar',
                        data: partKeluarData,
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Part Masuk',
                        data: partMasukData,
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            color: '#6b7280',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return value.toLocaleString('id-ID') + ' items';
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y.toLocaleString('id-ID') + ' items';
                                }
                                return label;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
            }
        });

        // Update charts when year is selected
        document.getElementById('yearSelect').addEventListener('change', (event) => {
            const selectedYear = event.target.value;

            fetch(`/get-data-by-year?year=${selectedYear}`)
                .then(response => response.json())
                .then(data => {
                    // Update financial chart
                    financialChart.data.datasets[0].data = data.invoiceData;
                    financialChart.data.datasets[1].data = data.jualPartData;
                    financialChart.update();

                    // Update parts chart
                    monthlyPartsChart.data.datasets[0].data = data.partKeluarData;
                    monthlyPartsChart.data.datasets[1].data = data.partMasukData;
                    monthlyPartsChart.update();
                });
        });
    </script>
</body>

</html>
