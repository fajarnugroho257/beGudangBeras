<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <button id="connectBtn">Connect to Printer</button>
    <button id="lagiBtn">lagiBtn Printer</button>

    {{-- <div id="printArea">
        <h1>Struk Pembelian</h1>
        <p>Toko ABC</p>
        <p>Tanggal: 18 November 2024</p>
        <hr>
        <p>Produk A x2 - Rp10.000</p>
        <p>Produk B x1 - Rp20.000</p>
        <hr>
        <p>Total: Rp40.000</p>
    </div> --}}

    <table id="myTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Telepon</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>John Doe</td>
                <td>Jl. Raya No. 123</td>
                <td>08123456789</td>
            </tr>
            <tr>
                <td>Jane Smith</td>
                <td>Jl. Kebon No. 456</td>
                <td>08987654321</td>
            </tr>
        </tbody>
    </table>

</body>
<script>
    document.getElementById('lagiBtn').addEventListener('click', async () => {
        const savedDeviceId = localStorage.getItem('printerDeviceId');

        if (savedDeviceId) {
            try {
                // Temukan perangkat berdasarkan ID yang disimpan di localStorage
                const device = await navigator.bluetooth.requestDevice({
                    filters: [{
                        deviceId: savedDeviceId
                    }],
                    optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
                });

                console.log('Perangkat terhubung kembali:', device.name);

                // Hubungkan ke perangkat
                const server = await device.gatt.connect();
                const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                const characteristic = await service.getCharacteristic(
                    '00002af1-0000-1000-8000-00805f9b34fb');


                // Ambil data dari tabel dengan ID 'myTable'
                const table = document.getElementById('myTable');
                let tableText = '';

                // Iterasi melalui baris tabel dan buat string
                const rows = table.querySelectorAll('tr');
                rows.forEach((row, rowIndex) => {
                    const columns = row.querySelectorAll('td, th'); // Ambil th dan td
                    columns.forEach((column, colIndex) => {
                        // Tambahkan kolom ke string, dengan pemisah tab atau spasi
                        tableText += column.innerText + (colIndex < columns.length - 1 ?
                            '\t' : '');
                    });
                    // Tambahkan newline setelah setiap baris
                    if (rowIndex < rows.length - 1) tableText += '\n';
                });

                console.log('Data tabel untuk dicetak:', tableText);

                // Data yang akan dicetak
                const printData = new TextEncoder().encode(tableText);

                // Kirim data ke printer
                await characteristic.writeValue(printData);

                console.log('Data berhasil dikirim ke printer.');
            } catch (error) {
                console.error('Gagal menghubungkan ke perangkat:', error);
            }
        } else {
            console.log('Tidak ada perangkat yang tersimpan di localStorage.');
        }
    });

    document.getElementById('connectBtn').addEventListener('click', async () => {
        try {
            // Meminta perangkat Bluetooth
            const device = await navigator.bluetooth.requestDevice({
                acceptAllDevices: true,
                optionalServices: [
                    '000018f0-0000-1000-8000-00805f9b34fb'
                ] // Ganti dengan UUID service printer Anda
            });

            console.log('Perangkat ditemukan:', device.name);

            // Simpan device ID di localStorage untuk penggunaan ulang
            localStorage.setItem('printerDeviceId', device.id); // Simpan device ID
            localStorage.setItem('printerDeviceName', device.name); // Simpan nama perangkat

            // Hubungkan ke perangkat
            const server = await device.gatt.connect();
            const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
            const characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');

            // Data yang akan dicetak
            // const printData = new TextEncoder().encode('Hello, Printer!\n');

            // Ambil data dari tabel dengan ID 'myTable'
            const table = document.getElementById('myTable');
            let tableText = '';

            // Iterasi melalui baris tabel dan buat string
            const rows = table.querySelectorAll('tr');
            rows.forEach((row, rowIndex) => {
                const columns = row.querySelectorAll('td, th'); // Ambil th dan td
                columns.forEach((column, colIndex) => {
                    // Tambahkan kolom ke string, dengan pemisah tab atau spasi
                    tableText += column.innerText + (colIndex < columns.length - 1 ?
                        '\t' : '');
                });
                // Tambahkan newline setelah setiap baris
                if (rowIndex < rows.length - 1) tableText += '\n';
            });

            console.log('Data tabel untuk dicetak:', tableText);

            // Data yang akan dicetak
            const printData = new TextEncoder().encode(tableText);

            // Kirim data ke printer
            await characteristic.writeValue(printData);

            console.log('Data berhasil dikirim ke printer.');
        } catch (error) {
            console.error('Gagal mencetak:', error);
        }
        // ---------------
        // navigator.bluetooth.requestDevice({
        //         acceptAllDevices: true,
        //         optionalServices: [] // Kosongkan dulu untuk mendeteksi semua
        //     }).then(device => device.gatt.connect())
        //     .then(server => server.getPrimaryServices())
        //     .then(services => {
        //         services.forEach(service => {
        //             console.log('Service UUID:', service.uuid);
        //         });
        //     })
        //     .catch(error => console.error('Error:', error));
        // ----------------
        // navigator.bluetooth.requestDevice({
        //         acceptAllDevices: true,
        //         optionalServices: [
        //             '000018f0-0000-1000-8000-00805f9b34fb'
        //         ] // Ganti dengan UUID service printer Anda
        //     }).then(device => device.gatt.connect())
        //     .then(server => server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb'))
        //     .then(service => service.getCharacteristics())
        //     .then(characteristics => {
        //         characteristics.forEach(characteristic => {
        //             console.log('Characteristic UUID:', characteristic.uuid);
        //             console.log('Properties:', characteristic
        //                 .properties); // Menunjukkan Read/Write/Notify
        //         });
        //     })
        //     .catch(error => console.error('Error:', error));
    });
</script>

</html>
