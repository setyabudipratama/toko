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
    $nama = $_POST['nama_kasir'];
    $nip = $_POST['nip'];

    // Insert barang ke database
    $sql = "INSERT INTO kasir (nama_kasir, nip) VALUES (?, ?)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$nama, $nip]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Data kasir berhasil ditambahkan!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan data kasir.']);
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

        .alert {
            position: fixed;
            bottom: -100px; /* Mulai dari luar layar */
            left: 50%;
            transform: translateX(-50%);
            display: none;
            z-index: 9999;
            padding: 15px;
            border-radius: 5px;
            color: #fff;
            background-color: #007bff;
            transition: bottom 0.5s ease-in-out;
        }

        .alert.show {
            bottom: 10px; /* Muncul di layar */
        }

        .alert-danger {
            background-color: #dc3545;
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
        <li class="nav-item"><a class="nav-link" href="pembelian.php">Pembelian</a></li>
        <li class="nav-item active"><a class="nav-link active" href="#">Kasir</a></li>
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
    <h2 class="text-center">Data Kasir Baru</h2>
    <form id="kasirBaru">
        <div class="row mt-3 d-flex justify-content-center">
            <div class="col col-md-6">
                <div class="form-group">
                    <label for="nama_kasir">Nama Kasir</label>
                    <input type="text" class="form-control" id="nama_kasir" name="nama_kasir" placeholder="Nama Kasir.." required>
                </div>
                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="number" class="form-control" id="nip" name="nip" placeholder="NIP.." required>
                </div>
                <button type="submit" class="btn mt-3 ml-3">Simpan</button>
            </div>
        </div>
    </form>
</section>

<section class="container mt-3 mb-3">
    <h2 class="mt-3 text-center">Data Kasir</h2>
    <table class="table table-bordered table-striped table-light mt-3">
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama Kasir</th>
                <th>NIP</th>            
            </tr>
        </thead>
        <tbody id="historiTable"></tbody>
    </table>
    <button id="printButton" class="btn mt-3 ml-3">Cetak Data Kasir</button>
</section>

<div id="alert" class="alert"></div>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    // Tambahkan event listener untuk tombol "Simpan"
    $('#kasirBaru').on('submit', function(event) {
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
                alert.text(response.message).addClass('show').slideDown();

                setTimeout(function() {
                    alert.removeClass('show').slideUp();
                }, 3000); // Menghilangkan alert setelah 3 detik
            },
            error: function() {
                var alert = $('#alert');
                alert.removeClass('alert-success').addClass('alert-danger');
                alert.text('Terjadi kesalahan saat menyimpan data.').addClass('show').slideDown();

                setTimeout(function() {
                    alert.removeClass('show').slideUp();
                }, 3000); // Menghilangkan alert setelah 3 detik
            }
        });
    });

    function loadDataKasir() {
    $.ajax({
        url: 'data_kasir.php', // URL file PHP untuk mengambil data kasir
        type: 'GET',
        success: function(response) {
            const data = typeof response === 'string' ? JSON.parse(response) : response;
            const tableBody = $('#historiTable');
            tableBody.empty(); // Clear existing data

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(function(item, index) {
                    const row = `<tr>
                        <td>${item.id_kasir}</td>
                        <td>${item.nama_kasir}</td>
                        <td>${item.nip}</td>
                    </tr>`;
                    tableBody.append(row);
                });
            } else {
                tableBody.append('<tr><td colspan="3">Tidak ada data.</td></tr>');
            }
        },
        error: function() {
            $('#alert').addClass('alert-danger').text('Terjadi kesalahan saat mengambil data.').addClass('show').slideDown();

            setTimeout(function() {
                $('#alert').removeClass('show').slideUp();
            }, 3000); // Menghilangkan alert setelah 3 detik
        }
    });
}

// Load data on page load
$(document).ready(function() {
    loadDataKasir();
});

    // Load data on button click
    $('#printButton').on('click', function() {
        window.open('cetak_data_kasir.php', '_blank');
    });
});
</script>
</body>
</html>