<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Showroom - PDF</title>
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

        .table-footer {
            text-align: left;
            padding: 10px;
        }

        .container {
            display: flex;
            align-items: flex-start;
            margin-top: 20px;
        }

        .photo-section {
            flex: 1;
            margin-right: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .photo-section img {
            width: 200px;
            /* Set the desired width */
            height: 150px;
            /* Set the desired height */
            object-fit: cover;
            /* Ensures the image maintains its aspect ratio and fills the box without distortion */
            margin-bottom: 10px;
        }


        .photo-caption {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .data-section {
            flex: 3;
            display: flex;
            flex-direction: column;
        }

        .data-section p {
            margin: 5px 0;
            font-size: 14px;
        }

        .data-section strong {
            display: inline-block;
            width: 150px;
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

    <!-- Data Showroom -->
    <div class="center-text">
        <p><strong>Data Showroom</strong></p>
    </div>

    <!-- Current Date -->
    <p><strong>Tanggal Cetak:</strong> {{ date('d-m-Y') }}</p>

    <!-- Loop through each showroom -->
    @foreach ($showrooms as $showroom)
        <!-- Container for Photo and Data -->
        <div class="container">
            <!-- Photo Section -->
            <div class="photo-section">
                <img src="{{ $showroom->foto }}" alt="Foto">
            </div>

            <!-- Data Section -->
            <div class="data-section">
                <!-- Data di samping foto (menurun) -->
                <p><strong>Nomor Polisi:</strong> {{ $showroom->nomor_polisi }}</p>
                <p><strong>Merk/Model:</strong> {{ $showroom->merk_model }}</p>
                <p><strong>Tahun Pembuatan:</strong> {{ $showroom->tahun_pembuatan }}</p>
                <p><strong>Nomor Rangka:</strong> {{ $showroom->nomor_rangka }}</p>
                <p><strong>Nomor Mesin:</strong> {{ $showroom->nomor_mesin }}</p>
                <p><strong>Bahan Bakar:</strong> {{ $showroom->bahan_bakar }}</p>
                <p><strong>Kapasitas Mesin:</strong> {{ $showroom->kapasitas_mesin }}</p>
                <p><strong>Jumlah Roda:</strong> {{ $showroom->jumlah_roda }}</p>
                <p><strong>Harga:</strong> {{ $showroom->harga }}</p>

                <!-- Data di bawah foto (menurun) -->
                <p><strong>Tanggal Registrasi:</strong> {{ $showroom->tanggal_registrasi }}</p>
                <p><strong>Masa Berlaku STNK:</strong> {{ $showroom->masa_berlaku_stnk }}</p>
                <p><strong>Masa Berlaku Pajak:</strong> {{ $showroom->masa_berlaku_pajak }}</p>
                <p><strong>Status Kepemilikan:</strong> {{ $showroom->status_kepemilikan }}</p>
                <p><strong>Kilometer:</strong> {{ $showroom->kilometer }}</p>
                <p><strong>Fitur Keamanan:</strong> {{ $showroom->fitur_keamanan }}</p>
                <p><strong>Riwayat Servis:</strong> {{ $showroom->riwayat_servis }}</p>
                <p><strong>Status:</strong> {{ $showroom->status }}</p>
            </div>
        </div>

        <!-- Divider Line -->
        <div class="divider"></div>
    @endforeach

    <!-- Table Footer -->
    <div class="table-footer">
        <p><strong>Terima kasih atas kunjungan Anda!</strong></p>
    </div>

</body>

</html>
