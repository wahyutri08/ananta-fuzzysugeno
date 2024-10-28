<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pasien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .report {
            width: 100%;
            max-width: 600px;
            margin: auto;
            border-collapse: collapse;
        }

        .report th,
        .report td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }

        .report-header {
            margin-bottom: 10px;
        }

        .report-header td {
            padding: 5px;
        }

        .result {
            margin-top: 15px;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="report-header" style="text-align: center;">
        <table width="100%">
            <tr>
                <td><strong>Laporan Pasien</strong></td>
            </tr>
            <tr>
                <td>No. 0001/I/2023</td>
            </tr>
        </table>
    </div>

    <div class="report-header">
        <table width="100%">
            <tr>
                <td>Nama Pasien:</td>
                <td>Rahmat</td>
            </tr>
        </table>
    </div>

    <table class="report">
        <thead>
            <tr>
                <th>NO</th>
                <th>Nama Gejala</th>
                <th>Nilai Bobot</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>Sakit kepala terus menerus.</td>
                <td>Sangat Yakin</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Mual dan muntah.</td>
                <td>Yakin</td>
            </tr>
            <tr>
                <td>3</td>
                <td>Perubahan siklus menstruasi</td>
                <td>Cukup Yakin</td>
            </tr>
            <tr>
                <td>4</td>
                <td>Mengantuk pada waktu yang tidak biasa.</td>
                <td>Kurang Yakin</td>
            </tr>
            <tr>
                <td>5</td>
                <td>Tidur terlalu banyak atau terlalu sedikit</td>
                <td>Yakin</td>
            </tr>
        </tbody>
    </table>

    <div class="result">
        Bahwa Hasil dari deteksi Masyarakat berindikasi memiliki Cancer Brain berjenis Tumor Pineal 98.27 %
    </div>

</body>

</html>