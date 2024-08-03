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

// Tambahkan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = $_POST['kode'];
    $nama_barang = $_POST['nama_barang'];
    $harga_jual = $_POST['harga_jual'];
    $satuan = $_POST['satuan'];
    $diskon = $_POST['diskon'];

    // Insert barang ke database
    $sql = "INSERT INTO barang (kode, nama_barang, harga_jual, satuan, diskon) VALUES (:kode, :nama_barang, :harga_jual, :satuan, :diskon)";
    $stmt = $dbh->prepare($sql);
    // Bindparam untuk memasukkan data
    $stmt->bindParam(':kode', $kode);
    $stmt->bindParam(':nama_barang', $nama_barang);
    $stmt->bindParam(':harga_jual', $harga_jual);
    $stmt->bindParam(':satuan', $satuan);
    $stmt->bindParam(':diskon', $diskon);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Data barang berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data barang.']);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        body {
            width: 100%;
            height: auto;
            background-color: #4f4f4f;
        }

        .alert {
            position: fixed; /* Atau 'absolute' jika Anda ingin relative terhadap elemen parent */
            bottom: 10px; /* Jarak dari bagian bawah halaman */
            left: 50%; /* Menempatkan di tengah secara horizontal */
            transform: translateX(-50%); /* Menyelaraskan elemen ke tengah horizontal */
            display: none; /* Sembunyikan pesan secara default */
            z-index: 9999; /* Agar berada di atas elemen lainnya */
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            background-color: #007bff; /* Warna latar belakang biru */
        }

        .alert-danger {
            background-color: #dc3545; /* Warna latar belakang merah untuk pesan kesalahan */
        }

        section {
            background-color: #fddafe;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: box-shadow 0.2s ease-in-out;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 5px solid #000000;
            animation: animateSpin 2s linear infinite;
        }

        .form-control:hover, .form-control:focus {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 1);
            border-color: #000000;
        }

        @keyframes animateSpin {
            10% { border-color: #ffdb3b; }
            45% { border-color: #fe53bb; }
            67% { border-color: #8f51ea; }
            87% { border-color: #0010f3; }
        }

        .btn {
            justify-content: flex-end;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            transition: box-shadow 0.2s ease-in-out;
            border-radius: 10px;
            margin-bottom: 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }

        .btn:hover {
           box-shadow: 0 8px 16px rgba(0, 0, 0, 1);
            color: #fff;
            background-color: rgb(96, 8, 8);
            border-color: #000000;
        }

        th, td {
            display: flexbox;
           text-align: center;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" href="penjualan.php">Penjualan</a></li>
        <li class="nav-item active"><a class="nav-link active" href="#">Pembelian</a></li>
        <li class="nav-item"><a class="nav-link" href="kasir.php">Kasir</a></li>
      </ul>
    </div> 
  </div> 
  <div class="col-md-6 text-right text-white mt-3 pb--3">
    <div class="form-group">
      <label for="tanggalSekarang">Tanggal:<h5><?php echo date('Y-m-d'); ?></h5></label>
    </div>
  </div>
</nav>

<section class="container mt-3 mb-3">
    <h2 class="text-center">Pembelian Barang</h2>
    <div id="alert" class="alert"></div>
    <form id="pembelianForm">
        <div class="row mt-3 d-flex justify-content-center">
            <div class="col-md-8 mt-3 ">
                <div class="form-group">
                    <label for="kode">Kode Barang</label>
                    <input type="text" class="form-control" id="kode" name="kode" placeholder="Kode Barang.." required>
                </div>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang.." required>
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" placeholder="Harga Jual.." required>
                </div>
                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Satuan.." required>
                </div>
                <div class="form-group">
                    <label for="diskonBarang">Diskon (%):</label>
                    <input type="number" step="0.01" class="form-control" id="diskonBarang" name="diskon" placeholder="Diskon.. (Opsional)" required>
                </div>
                <button type="submit" class="btn mt-3 ml-3">Simpan</button>
            </div>
        </div>
    </form>
</section>

<section class="container mt-3 mb-3">
    <h2 class="mt-3 text-center">Histori Pembelian Barang</h2>
    <div id="alert" class="alert"></div>
    <table class="table table-bordered table-striped table-light mt-3">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Harga Jual</th>
                <th>Satuan</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody id="historiTable"></tbody>
    </table>
    <button id="printButton" class="btn mt-3 ml-3">Cetak Pembelian</button>
</section>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Tambahkan event listener untuk tombol "Simpan"
    $('#pembelianForm').on('submit', function(event) {
        event.preventDefault();
        // Kirim permintaan AJAX ke server untuk menyimpan data
        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                // Tampilkan alert dari server menggunakan java script
                var alert = $('#alert');
                if (response.status === 'success') {
                    alert.removeClass('alert-danger').addClass('alert-success');
                } else {
                    alert.removeClass('alert-success').addClass('alert-danger');
                }
                alert.text(response.message).slideDown().delay(3000).slideUp();
            },
            error: function() {
                $('#alert').removeClass('alert-success').addClass('alert-danger').text('Terjadi kesalahan saat menyimpan data.').slideDown().delay(3000).slideUp();
            }
        });
    });
    function loadHistoriPembelian() {
        $.ajax({
            url: 'histori_pembelian.php', // URL file PHP untuk mengambil histori pembelian
            type: 'GET',
            success: function(response) {
                const data = typeof response === 'string' ? JSON.parse(response) : response;
                const tableBody = $('#historiTable');
                tableBody.empty(); // Clear existing data

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach((item, index) => {
                        const row = `<tr>
                            <td>${index + 1}</td>
                            <td>${item.kode}</td>
                            <td>${item.nama_barang}</td>
                            <td>${item.harga_jual}</td>
                            <td>${item.satuan}</td>
                            <td>${item.diskon}</td>
                        </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<tr><td colspan="6">Tidak ada data.</td></tr>');
                }
            },
            error: function() {
                $('#alert').addClass('alert-danger').text('Terjadi kesalahan saat mengambil data.').slideDown().delay(3000).slideUp();
            }
        });
    }

    // Load data on page load
    loadHistoriPembelian();

    // Load data on button click
    $('#printButton').on('click', function() {
        window.open('cetak_pembelian.php', '_blank');
    });
});
</script>
</body>
</html>