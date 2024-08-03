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

// Ambil data kasir
$sql = "SELECT id_kasir, nama_kasir, nip FROM kasir";
$stmt = $dbh->prepare($sql);

if ($stmt->execute()) {
    $kasirData = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $kasirData = [];
}
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
                <th>Nomor</th>
                <th>Nama Kasir</th>
                <th>NIP</th>
            </tr>
        </thead>
        <tbody>
        <?php if (count($kasirData) > 0): ?>
                <?php foreach ($kasirData as $index => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id_kasir']) ?></td>
                        <td><?= htmlspecialchars($item['nama_kasir']) ?></td>
                        <td><?= htmlspecialchars($item['nip']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="3">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>
</html>