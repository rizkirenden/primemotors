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
            <!-- Card 1 -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Mekanik</h2>
                <p>100</p>
                <!-- Icon -->
                <i class="fas fa-cogs absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 2 -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Sparepat</h2>
                <p>50</p>
                <!-- Icon -->
                <i class="fas fa-wrench absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 3 -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Service</h2>
                <p>50</p>
                <!-- Icon -->
                <i class="fas fa-tools absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- Card 4 -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg relative">
                <h2 class="text-lg font-semibold mb-2">Petugas</h2>
                <p>50</p>
                <!-- Icon -->
                <i class="fas fa-user-tie absolute top-2 right-2 text-xl"></i>
            </div>

            <!-- New Card Spanning 2 Columns -->
            <div class="bg-white text-black p-4 rounded-lg shadow-lg col-span-2 lg:col-span-2 relative">
                <h2 class="text-lg font-semibold mb-2">Showroom</h2>
                <p>50</p>
                <!-- Icon -->
                <i class="fas fa-chart-line absolute top-2 right-2 text-xl"></i>
            </div>
        </div>

        <!-- Transactions Chart -->
        <div class="mt-4 bg-white p-2 rounded-lg shadow-lg w-full mx-auto">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl text-black">Transactions</h2>

                <!-- Year Selection Dropdown at the right -->
                <div class="flex items-center">
                    <label for="yearSelect" class="text-black mr-2">Select Year:</label>
                    <select id="yearSelect" class="bg-white text-black border-black border-2 rounded-lg">
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                    </select>
                </div>
            </div>

            <canvas id="transactionsChart" class="w-full" style="height: 250px;"></canvas>
        </div>


        <script>
            const ctx = document.getElementById('transactionsChart').getContext('2d');

            // Default dataset for 2025
            const data2025 = [50, 45, 30, 10, 0, 20, 50, 30, 30, 41, 34, 50];
            const data2026 = [60, 55, 40, 20, 10, 30, 60, 40, 35, 50, 45, 60];
            const data2027 = [70, 65, 50, 30, 20, 40, 70, 50, 45, 55, 50, 70];

            // Create the chart with the default data
            const transactionsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Transactions',
                        data: data2025,
                        borderColor: '#000000', // Black line
                        backgroundColor: 'rgba(0, 0, 0, 0.1)', // Semi-transparent black fill
                        tension: 0.1,
                        borderWidth: 2,
                        pointBackgroundColor: '#000000',
                        pointRadius: 4,
                    }]
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
                }
            });

            // Update chart when year is selected
            document.getElementById('yearSelect').addEventListener('change', (event) => {
                const selectedYear = event.target.value;

                let newData;
                switch (selectedYear) {
                    case '2025':
                        newData = data2025;
                        break;
                    case '2026':
                        newData = data2026;
                        break;
                    case '2027':
                        newData = data2027;
                        break;
                    default:
                        newData = data2025;
                }

                // Update the chart's data
                transactionsChart.data.datasets[0].data = newData;
                transactionsChart.update();
            });
        </script>

</body>

</html>
