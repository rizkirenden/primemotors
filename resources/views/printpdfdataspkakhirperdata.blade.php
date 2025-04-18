<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK AKHIR - PDF</title>
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

        textarea {
            width: 100%;
            height: auto;
            border: none;
            resize: none;
            font-family: inherit;
            font-size: inherit;
        }
    </style>
</head>

<body>

    <!-- Header with Logo -->
    <div class="header">
        <img src="images/silver.PNG" alt="Logo">
    </div>
    <strong class="center-strong">PT. PREMIER AUTO GROUP</strong>

    <!-- Contact Information -->
    <div class="contact-info">
        <p>
            <span><i class="fas fa-map-marker-alt"></i> Nama Jalan: Jalan XYZ, No. 123</span>
            <span><i class="fab fa-instagram"></i> Instagram: @yourinstagram</span>
            <span><i class="fas fa-phone"></i> Nomor Telepon: +62 123 456 789</span>
        </p>
    </div>

    <!-- Divider Line -->
    <div class="divider"></div>

    <!-- Title -->
    <div class="center-text">
        <p><strong>SPK</strong></p>
    </div>

    <!-- Data Header -->
    <div class="data-row">
        <div class="data-label">No Spk: {{ $dataservices->no_spk }}</div>
        <div class="data-label">Tanggal: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</div>
        <div class="data-label">Teknisi: {{ $dataservices->nama_mekanik }}</div>
        <div class="data-label">Status: {{ $dataservices->status }}</div>
    </div>

    <!-- Customer & Vehicle Info -->
    <div style="width: 100%;">
        <div style="width: 48%; display: inline-block; vertical-align: top;">
            <p style="margin: 2px 0;">Costumer: {{ $dataservices->costumer }}</p>
            <p style="margin: 2px 0;">Contact Person: {{ $dataservices->contact_person }}</p>
            <p style="margin: 2px 0;">Tanggal Masuk: {{ $dataservices->masuk }}</p>
            <p style="margin: 2px 0;">Tanggal Keluar: {{ $dataservices->keluar }}</p>
        </div>
        <div style="width: 48%; display: inline-block; vertical-align: top;">
            <p style="margin: 2px 0;">No Polisi: {{ $dataservices->no_polisi }}</p>
            <p style="margin: 2px 0;">Tahun: {{ $dataservices->tahun }}</p>
            <p style="margin: 2px 0;">Tipe: {{ $dataservices->tipe_mobile }}</p>
            <p style="margin: 2px 0;">Warna: {{ $dataservices->warna }}</p>
            <p style="margin: 2px 0;">No Rangka: {{ $dataservices->no_rangka }}</p>
            <p style="margin: 2px 0;">No Mesin: {{ $dataservices->no_mesin }}</p>
        </div>
    </div>

    <!-- Customer Complaint -->
    <div class="data-container">
        <div class="data-row">
            <div class="data-label">Keluhan Costumer:</div>
            <textarea class="data-value">{{ $dataservices->keluhan_costumer }}</textarea>
        </div>
    </div>

    <!-- Parts Table -->
    <div class="flex-container w-max">
        <table>
            <thead>
                <tr>
                    <th style="width: 30%;">NAMA PART</th>
                    <th style="width: 15%;">JUMLAH</th>
                    <th style="width: 25%;">KODE BARANG</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataservices->partkeluar as $part)
                    <tr>
                        <td>{{ $part->nama_part }}</td>
                        <td>{{ $part->jumlah }}</td>
                        <td>{{ $part->kode_barang }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Service Details Table (Fix JSON Decode) -->
    @php
        $jenisPekerjaan = json_decode($dataservices->jenis_pekerjaan, true);
        $ongkosPengerjaan = json_decode($dataservices->ongkos_pengerjaan, true);
    @endphp

    <div class="flex-container w-max" style="margin-top: 20px;">
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">URAIAN PEKERJAAN</th>
                    <th style="width: 50%;">URAIAN JASA PERBAIKAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jenisPekerjaan as $index => $pekerjaan)
                    <tr>
                        <td>{{ $pekerjaan }}</td>
                        <td>
                            {{ isset($ongkosPengerjaan[$index]) ? 'Rp ' . number_format((float) $ongkosPengerjaan[$index], 0, ',', '.') : '-' }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>
