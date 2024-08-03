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

$sql = "SELECT t.id_transaksi, t.tanggal_transaksi, t.nama_kasir, t.total_harga, ti.id_item, ti.id_barang, ti.kode, ti.nama_barang, ti.jumlah, ti.harga, ti.diskon, ti.subtotal, ti.pajak 
        FROM transaksi t 
        JOIN transaksi_items ti ON t.id_transaksi = ti.id_transaksi 
        ORDER BY t.id_transaksi ASC";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table class="table table-striped table-light" style="transform: translateX(-1%);">
    <thead>
        <tr>
            <th>Nomor</th>
            <th>ID Transaksi</th>
            <th>Tanggal Transaksi</th>
            <th>Nama Kasir</th>
            <th>Total Harga</th>
            <th>ID Item</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Diskon</th>
            <th>Subtotal</th>
            <th>Pajak</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $index => $transaction): ?>
            <tr>
                <td><?= $index + 1; ?></td>
                <td><?= htmlspecialchars($transaction['id_transaksi']) ?></td>
                <td><?= htmlspecialchars($transaction['tanggal_transaksi']) ?></td>
                <td><?= htmlspecialchars($transaction['nama_kasir']) ?></td>
                <td><?= htmlspecialchars($transaction['total_harga']) ?></td>
                <td><?= htmlspecialchars($transaction['id_item']) ?></td>
                <td><?= htmlspecialchars($transaction['kode']) ?></td>
                <td><?= htmlspecialchars($transaction['nama_barang']) ?></td>
                <td><?= htmlspecialchars($transaction['jumlah']) ?></td>
                <td><?= htmlspecialchars($transaction['harga']) ?></td>
                <td><?= htmlspecialchars($transaction['diskon']) ?></td>
                <td><?= htmlspecialchars($transaction['subtotal']) ?></td>
                <td><?= htmlspecialchars($transaction['pajak']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>