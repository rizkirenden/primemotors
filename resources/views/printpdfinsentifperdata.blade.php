<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INSENTIF - PDF</title>
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 150px;
            /* Adjust logo size */
        }

        .contact-info {
            text-align: center;
            font-size: 14px;
            padding: 10px;
        }

        .contact-info p {
            margin: 0;
        }

        .contact-info p span {
            display: inline-block;
            margin-right: 20px;
            white-space: nowrap;
        }

        .contact-info p span:last-child {
            margin-right: 0;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 20px 0;
        }

        .center-text {
            text-align: center;
            margin-top: 20px;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
        }

        .flex-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .data-label {
            font-weight: bold;
            margin-right: 10px;
            min-width: 100px;
        }

        .data-value {
            margin-right: 20px;
        }

        .two-columns {
            display: flex;
            justify-content: space-between;
        }

        .column {
            width: 48%;
        }

        .column .data-row {
            display: flex;
            margin-bottom: 10px;
        }

        .column .data-label {
            min-width: 120px;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            color: white;
            background-color: #000000;
            text-align: left;
            font-size: 12px;
        }

        .center-strong {
            text-align: center;
            display: block;
            width: 100%;
        }

        .page-break {
            page-break-before: always;
        }

        /* Optional: Ensure no extra space between pages when printed */
        @media print {
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body>
    <!-- Header with Logo -->
    @foreach ($laporanInsentif as $insentif)
        <!-- Page Break for every new data set -->
        @if ($loop->index > 0)
            <div class="page-break"></div>
        @endif
        <div class="header">
            <img src="images/silver.PNG" alt="Logo"> <!-- Add your logo image here -->
        </div>
        <strong class="center-strong">PT. PREMIER AUTO GROUP</strong>

        <!-- Contact Information with FontAwesome Icons -->
        <div class="contact-info">
            <p>
                <span>
                    <i class="fas fa-map-marker-alt"></i>Nama Jalan: Jalan XYZ, No. 123
                </span>
                <span>
                    <i class="fab fa-instagram"></i>Instagram: @yourinstagram
                </span>
                <span>
                    <i class="fas fa-phone"></i>Nomor Telepon: +62 123 456 789
                </span>
            </p>
        </div>

        <!-- Divider Line -->
        <div class="divider"></div>

        <!-- Data Mekanik -->
        <div class="center-text">
            <p><strong>Insentif Report</strong></p>
        </div>

        <!-- Table for Insentif Summary -->
        <div class="flex-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 30%;">Nama Mekanik</th>
                        <th style="width: 20%;">Total Pengerjaan</th>
                        <th style="width: 20%;">Total Insentif</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $insentif->nama_mekanik }}</td>
                        <td>{{ $insentif->total_pengerjaan }}</td>
                        <td>Rp. {{ number_format($insentif->total, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endforeach
</body>

</html>
