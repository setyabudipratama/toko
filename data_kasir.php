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

$sql = "SELECT id_kasir, nama_kasir, nip FROM kasir";
$stmt = $dbh->prepare($sql);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
?>