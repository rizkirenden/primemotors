<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INVOICE - Penjualan Part</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 150px;
        }

        .center-strong {
            text-align: center;
            display: block;
            width: 100%;
            font-weight: bold;
            font-size: 18px;
        }

        .contact-info {
            text-align: center;
            font-size: 14px;
            padding: 10px;
        }

        .contact-info p {
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .contact-info p span {
            display: inline-block;
        }

        .divider {
            border-top: 1px solid #000;
            margin: 20px 0;
        }

        .invoice-container {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .invoice-column {
            width: 48%;
        }

        .invoice-column p {
            margin: 5px 0;
            font-size: 14px;
        }

        .center-text {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #000;
            color: #fff;
        }

        .right-text {
            text-align: right;
            margin-top: 20px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .invoice-container {
                flex-direction: column;
            }

            .invoice-column {
                width: 100%;
                margin-bottom: 10px;
            }

            .contact-info p {
                flex-direction: column;
                gap: 5px;
                align-items: center;
            }

            .right-text {
                text-align: left;
            }
        }
    </style>
</head>

<body>

    <!-- Header Logo -->
    <div class="header">
        <img src="images/silver.PNG" alt="Logo">
    </div>

    <strong class="center-strong">PT. PREMIER AUTO GROUP</strong>

    <!-- Contact Info -->
    <div class="contact-info">
        <p>
            <span><i class="fas fa-map-marker-alt"></i>Nama Jalan: Jalan XYZ, No. 123</span>
            <span><i class="fab fa-instagram"></i>Instagram: @yourinstagram</span>
            <span><i class="fas fa-phone"></i>Nomor Telepon: +62 123 456 789</span>
        </p>
    </div>

    <!-- Divider -->
    <div class="divider"></div>

    <!-- Invoice Info (Kolom Terpisah) -->
    <div style="width: 100%;">
        <!-- Left Column (4 items) -->
        <div style="width: 48%; display: inline-block; vertical-align: top; margin: 0; padding: 0;">
            <p style="margin: 2px 0;"><strong>Invoice:</strong> {{ $jualpart->invoice_number }}</p>
            <p style="margin: 2px 0;"><strong>Nama Pelanggan:</strong> {{ $jualpart->nama_pelanggan }}</p>
            <p style="margin: 2px 0;"><strong>Alamat:</strong> {{ $jualpart->alamat_pelanggan }}</p>
            <p style="margin: 2px 0;"><strong>No. Telp:</strong> {{ $jualpart->nomor_pelanggan }}</p>
        </div>

        <!-- Right Column (3 items) -->
        <div style="width: 48%; display: inline-block; vertical-align: top; margin: 0; padding: 0;">
            <p style="margin: 2px 0;"><strong>Tanggal Pembayaran:</strong>
                {{ \Carbon\Carbon::parse($jualpart->tanggal_pembayaran)->format('d-m-Y') }}</p>
            <p style="margin: 2px 0;"><strong>Metode Pembayaran:</strong> {{ $jualpart->metode_pembayaran }}</p>
            <p style="margin: 2px 0;"><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d-m-Y') }}</p>
        </div>
    </div>
    <div class="center-text">
        <p><strong>Rincian Penjualan Part</strong></p>
    </div>

    <!-- Tabel Rincian Part -->
    <table>
        <thead>
            <tr>
                <th>Nama Part</th>
                <th>Merk</th>
                <th>Stn</th>
                <th>Tipe</th>
                <th>Tanggal Keluar</th>
                <th>Jumlah</th>
                <th>Harga Jual</th>
                <th>Dicount</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            @if ($items->isEmpty())
                <tr>
                    <td colspan="9">Tidak ada data part keluar.</td>
                </tr>
            @else
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->nama_part }}</td>
                        <td>{{ $item->merk }}</td>
                        <td>{{ $item->stn }}</td>
                        <td>{{ $item->tipe }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_keluar)->format('d-m-Y') }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->discount, 0) }}%</td>
                        <td>Rp {{ number_format($item->total_harga_part, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="right-text">
        <p>Total Transaksi: Rp {{ number_format($jualpart->total_transaksi, 0, ',', '.') }}</p>
    </div>

</body>

</html>
