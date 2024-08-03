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

$data = json_decode($_POST['transactionData'], true);
$namaKasir = $_POST['namaKasir'];
$idKasir = $_POST['idKasir'];
$totalHargaKeseluruhan = $_POST['totalHargaKeseluruhan'];
$tanggalTransaksi = $_POST['tanggalTransaksi'];

// Insert into transaksi
$sql = "INSERT INTO transaksi (id_kasir, nama_kasir, total_harga, tanggal_transaksi) VALUES (:idKasir, :namaKasir, :totalHargaKeseluruhan, :tanggalTransaksi)";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':idKasir', $idKasir);
$stmt->bindParam(':namaKasir', $namaKasir);
$stmt->bindParam(':totalHargaKeseluruhan', $totalHargaKeseluruhan);
$stmt->bindParam(':tanggalTransaksi', $tanggalTransaksi);

if ($stmt->execute()) {
    $idTransaksi = $dbh->lastInsertId();

    // Insert into transaksi_items
    $sqlItems = "INSERT INTO transaksi_items (id_transaksi, id_barang, kode, nama_barang, jumlah, harga, diskon, subtotal, pajak) VALUES (:idTransaksi, :idBarang, :kode, :namaBarang, :jumlah, :harga, :diskon, :subtotal, :pajak)";
    $stmtItems = $dbh->prepare($sqlItems);

    foreach ($data as $item) {
        $stmtItems->bindParam(':idTransaksi', $idTransaksi);
        $stmtItems->bindParam(':idBarang', $item['id_barang']);
        $stmtItems->bindParam(':kode', $item['kode']);
        $stmtItems->bindParam(':namaBarang', $item['nama']);
        $stmtItems->bindParam(':jumlah', $item['jumlah']);
        $stmtItems->bindParam(':harga', $item['harga']);
        $stmtItems->bindParam(':diskon', $item['diskon']);
        $stmtItems->bindParam(':subtotal', $item['subtotal']);
        $stmtItems->bindParam(':pajak', $item['pajak']);
        $stmtItems->execute();
    }

    echo json_encode(['id_transaksi' => $idTransaksi]);
} else {
    echo json_encode(['error' => 'Gagal menyimpan transaksi.']);
}
?>