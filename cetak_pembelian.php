<?php
$host = 'localhost';
$dbName = 'toko';
$user = 'root';
$pass = 'xxx';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error!: " . $e->getMessage();
    die();
}

// ambil data
$sql = "SELECT * FROM barang";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Histori Pembelian</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Satuan</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($purchases) > 0): ?>
                <?php foreach ($purchases as $index => $item): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($item['kode']); ?></td>
                        <td><?php echo htmlspecialchars($item['nama_barang']); ?></td>
                        <td><?php echo htmlspecialchars($item['harga_jual']); ?></td>
                        <td><?php echo htmlspecialchars($item['satuan']); ?></td>
                        <td><?php echo htmlspecialchars($item['diskon']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>
</html>