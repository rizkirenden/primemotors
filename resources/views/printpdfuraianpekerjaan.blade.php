<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Uraian Pekerjaan - PDF</title>
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
            /* Membuat teks sejajar ke samping */
            margin-right: 20px;
            /* Jarak antara setiap bagian teks */
            white-space: nowrap;
            /* Mencegah teks berpindah baris */
        }

        .contact-info p span:last-child {
            margin-right: 0;
            /* Menghilangkan margin pada elemen terakhir */
        }

        .divider {
            border-top: 1px solid #000;
            margin: 20px 0;
        }

        table {
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #000000;
            color: white;
        }

        .center-text {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Header with Logo -->
    <div class="header">
        <img src="images/silver.PNG" alt="Logo"> <!-- Add your logo image here -->
    </div>

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
        <p><strong>Data Uraian Pekerjaan</strong></p>
    </div>

    <!-- Current Date -->
    <p><strong>Tanggal Cetak:</strong> {{ date('d-m-Y') }}</p>

    <!-- Table of Mekanik Data -->
    <table>
        <thead>
            <tr>
                <th>Jenis Pekerjaan</th>
                <th>Jenis Mobile</th>
                <th>Waktu Pengerjaan (Menit)</th>
                <th>Ongkos Pengerjaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($uraianPekerjaans as $pekerjaan)
                <tr>
                    <td>{{ $pekerjaan->jenis_pekerjaan }}</td>
                    <td>{{ $pekerjaan->jenis_mobil }}</td>
                    <td>{{ $pekerjaan->waktu_pengerjaan }}</td>
                    <td>Rp{{ number_format($pekerjaan->ongkos_pengerjaan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Divider Line -->
    <div class="divider"></div>

    <!-- Table Footer -->
</body>

</html>
