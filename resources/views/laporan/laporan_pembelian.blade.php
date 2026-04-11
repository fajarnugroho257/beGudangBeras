<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembelian</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Data Pembelian</h1>
    <table>
        <thead>
            <tr>
                <th style="text-align: center">No</th>
                <th style="text-align: center">Suplier</th>
                <th style="text-align: center">Tanggal</th>
                <th style="text-align: center">Nama Barang</th>
                <th style="text-align: center">Tonase Kotor</th>
                <th style="text-align: center">Potongan</th>
                <th style="text-align: center">Tonase Bersih</th>
                <th style="text-align: center">Harga</th>
                <th style="text-align: center">Total</th>
                <th style="text-align: center">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($reindexdata as $data)
                @php
                    $ttl = count($data->listPembelian);
                @endphp
                <tr>
                    <td rowspan="{{ $ttl }}" style="text-align: center">{{ $no++ }}</td>
                    <td rowspan="{{ $ttl }}">{{ $data->suplier_nama }}</td>
                    <td rowspan="{{ $ttl }}">{{ $data->suplier_tgl }}</td>
                    <td>{{ $data->listPembelian[0]['pembelian_nama'] }}</td>
                    <td style="text-align: center">{{ $data->listPembelian[0]['pembelian_kotor'] }}</td>
                    <td style="text-align: center">{{ $data->listPembelian[0]['pembelian_potongan'] }}</td>
                    <td style="text-align: center">{{ $data->listPembelian[0]['pembelian_bersih'] }}</td>
                    <td>Rp. {{ number_format($data->listPembelian[0]['pembelian_harga'], 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($data->listPembelian[0]['pembelian_total'], 0, ',', '.') }}</td>
                    <td rowspan="{{ $ttl }}">Rp. {{ number_format($data->ttlPembelian, 0, ',', '.') }}</td>
                </tr>
                @foreach ($data->listPembelian as $key => $item)
                    @if ($key != 0)
                        <tr>
                            <td>{{ $item->pembelian_nama }}</td>
                            <td style="text-align: center">{{ $item->pembelian_kotor }}</td>
                            <td style="text-align: center">{{ $item->pembelian_potongan }}</td>
                            <td style="text-align: center">{{ $item->pembelian_bersih }}</td>
                            <td>Rp. {{ number_format($item->pembelian_harga, 0, ',', '.') }}</td>
                            <td>Rp. {{ number_format($item->pembelian_total, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
