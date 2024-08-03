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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow">
  <div class="container">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active"><a class="nav-link active" href="#">Penjualan</a></li>
        <li class="nav-item"><a class="nav-link" href="pembelian.php">Pembelian</a></li>
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

<section class="container mt-3">
  <form id="kasirForm">
    <div class="row mt-3">
      <div class="col-md-3">
        <div class="form-group">
          <label for="namaKasir">Nama Kasir:</label>
          <select class="form-control" id="namaKasir" name="namaKasir" required>
            <option value="" disabled selected>Pilih Kasir</option>
            <?php 
            $sqlKasir = "SELECT * FROM kasir";
            foreach ($dbh->query($sqlKasir) as $row) {
                echo "<option value='{$row['id_kasir']}'>{$row['nama_kasir']}</option>";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="namaBarang">Nama Barang:</label>
          <select class="form-control" id="namaBarang" required>
            <option value="" disabled selected>Pilih Barang</option>
            <?php 
            $sqlBarang = "SELECT * FROM barang";
            foreach ($dbh->query($sqlBarang) as $row) {
                echo "<option value='{$row['id_barang']}' data-id-barang='{$row['id_barang']}' data-harga='{$row['harga_jual']}' data-kode='{$row['kode']}' data-satuan='{$row['satuan']}' data-diskon='{$row['diskon']}'>{$row['nama_barang']}</option>";
            }
            ?>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="jumlahBarang">Jumlah:</label>
          <input type="number" class="form-control" id="jumlahBarang" min="1" value="1" required>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="hargaBarang">Harga:</label>
          <input type="number" class="form-control" id="hargaBarang" readonly>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="diskonBarang">Diskon (%):</label>
          <input type="number" class="form-control" id="diskonBarang" readonly>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label>&nbsp;</label>
          <button class="btn mt-3" type="button" id="tambahBarang">Tambah</button>
        </div>
      </div>
    </div>
  </form>
</section>

<section class="container mt-3">
  <table class="table table-bordered table-striped table-light">
    <thead>
      <tr>
        <th>Kode</th>
        <th>Nama</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Harga</th>
        <th>Diskon (%)</th>
        <th>Subtotal</th>
        <th>Pajak</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="tabelBarang"></tbody>
  </table>

  <div class="row mt-4">
    <div class="col-md-3">
      <div class="form-group">
        <label for="totalDiskon">Total Diskon:</label>
        <input type="text" class="form-control" id="totalDiskon" readonly>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="totalPajak">Total Pajak:</label>
        <input type="text" class="form-control" id="totalPajak" readonly>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="totalHarga">Total Harga:</label>
        <input type="text" class="form-control" id="totalHarga" readonly>
      </div>
    </div>
    <div class="col-md-3">
      <div class="form-group">
        <label for="totalKeseluruhan">Total Keseluruhan:</label>
        <input type="text" class="form-control" id="totalKeseluruhan" readonly>
      </div>
    </div>
  </div>

  <div class="row mt-3">
    <div class="col-md-12">
      <button class="btn mt-3 ml-2" id="selesaiTransaksi">Selesai Transaksi</button>
    </div>
  </div>
</section>

<section class="container mt-3">
  <h3 class="text-center mt-3 mb-3">Histori Transaksi</h3>
  <div id="historiTransaksi"></div>
  <button class="btn mt-3 ml-2" id="printTransaksi">Cetak Transaksi</button>
</section>

<script>
$(document).ready(function() {
  // fungsi ketika opsi barang berubah
    $("#namaBarang").change(function() {
        let harga = $("#namaBarang option:selected").data("harga");
        let diskon = $("#namaBarang option:selected").data("diskon");
        // set value pada form
        $("#hargaBarang").val(harga);
        $("#diskonBarang").val(diskon);
    });

    // fungsi ketika tombol tambah barang diklik
    $("#tambahBarang").click(function() {
        let kode = $("#namaBarang option:selected").data("kode");
        let nama = $("#namaBarang option:selected").text();
        let jumlah = $("#jumlahBarang").val();
        let satuan = $("#namaBarang option:selected").data("satuan");
        let harga = $("#hargaBarang").val();
        let diskon = $("#diskonBarang").val();

        // menambahkan baris pada tabel transaksi
        let subtotal = jumlah * harga * (1 - diskon / 100);
        let pajak = subtotal * 0.1;

        // menambahkan baris pada tabel
        $("#tabelBarang").append(`<tr>
          <td data-id-barang="${$("#namaBarang").val()}">${kode}</td>
          <td>${nama}</td>
          <td>${jumlah}</td>
          <td>${satuan}</td>
          <td>${harga}</td>
          <td>${diskon}</td>
          <td>${subtotal.toFixed(2)}</td>
          <td>${pajak.toFixed(2)}</td>
          <td><button class="btn btn-danger btn-sm hapusBarang">Hapus</button></td>
        </tr>`);

        updateTotals();
    });

    // fungsi ketika tombol hapus diklik
    $("#tabelBarang").on("click", ".hapusBarang", function() {
        $(this).closest("tr").remove();
        updateTotals();
    });

    // fungsi untuk mengupdate total transaksi setiap kali ada perubahan pada tabel
    function updateTotals() {
        let totalDiskon = 0;
        let totalPajak = 0;
        let totalHarga = 0;
        let totalKeseluruhan = 0;

        // menghitung total transaksi
        $("#tabelBarang tr").each(function() {
            let diskon = parseFloat($(this).find("td:eq(5)").text());
            let subtotal = parseFloat($(this).find("td:eq(6)").text());
            let pajak = parseFloat($(this).find("td:eq(7)").text());

            // menghitung total diskon, pajak, dan total keseluruhan
            totalDiskon += diskon;
            totalPajak += pajak;
            totalHarga += subtotal;
            totalKeseluruhan += subtotal + pajak;
        });

        // menampilkan total transaksi
        $("#totalDiskon").val(totalDiskon.toFixed(2));
        $("#totalPajak").val(totalPajak.toFixed(2));
        $("#totalHarga").val(totalHarga.toFixed(2));
        $("#totalKeseluruhan").val(totalKeseluruhan.toFixed(2));
    }

    // ketika user menekan tombol selesai transaksi
    $("#selesaiTransaksi").click(function() {
        let transactionData = [];
        let totalHargaKeseluruhan = 0;
        let namaKasir = $("#namaKasir option:selected").text();
        let idKasir = $("#namaKasir").val();
        let tanggalTransaksi = new Date().toISOString().split('T')[0];

        //fungsi untuk menyimpan transaksi
        $("#tabelBarang tr").each(function() {
            let kode = $(this).find("td:eq(0)").text();
            let nama = $(this).find("td:eq(1)").text();
            let jumlah = $(this).find("td:eq(2)").text();
            let harga = $(this).find("td:eq(4)").text();
            let subtotal = parseFloat($(this).find("td:eq(6)").text());
            let pajak = parseFloat($(this).find("td:eq(7)").text());
            let id_barang = $(this).find("td:eq(0)").data("id-barang");

            // menyimpan data transaksi
            transactionData.push({ id_barang, kode, nama, jumlah, harga, subtotal, pajak, diskon: $("#diskonBarang").val() });
            totalHargaKeseluruhan += subtotal + pajak;
        });

        // Mengirimkan permintaan AJAX untuk menyimpan transaksi
        $.ajax({
            url: 'transaksi.php',
            type: 'POST',
            data: {
                transactionData: JSON.stringify(transactionData),
                namaKasir: namaKasir,
                idKasir: idKasir,
                totalHargaKeseluruhan: totalHargaKeseluruhan,
                tanggalTransaksi: tanggalTransaksi
            },
            // Menangani respons permintaan AJAX
            success: function(response) {
                // console.log(response);
                let data = JSON.parse(response);
                if (data.id_transaksi) {
                    alert("Transaksi berhasil disimpan!");

                    clearTransaction();
                    loadHistoriTransaksi();
                } else {
                    alert("Terjadi kesalahan saat menyimpan transaksi.");
                }
            },
            // Menangani kesalahan saat menyimpan transaksi
            error: function() {
                alert("Terjadi kesalahan saat menyimpan transaksi.");
            }
        });
    });

    // fungsi untuk mengosongkan transaksi
    function clearTransaction() {
        $("#tabelBarang").empty();
        $("#totalDiskon").val('');
        $("#totalPajak").val('');
        $("#totalHarga").val('');
        $("#totalKeseluruhan").val('');
    }

    // fungsi untuk memuat histori transaksi
    function loadHistoriTransaksi() {
            $.ajax({
                url: 'histori_transaksi.php',
                type: 'GET',
                success: function(response) {
                    $('#historiTransaksi').html(response);
                },
                error: function() {
                    alert('Terjadi kesalahan saat memuat histori transaksi.');
                }
            });
        }

        $(document).ready(function() {
            loadHistoriTransaksi();
        });

    // fungsi untuk mencetak transaksi
    $(document).ready(function() {
            $('#printTransaksi').on('click', function() {
                window.open('cetak_transaksi.php', '_blank');
            });
        });

    // panggil fungsi loadHistoriTransaksi saat halaman ditampilkan
    loadHistoriTransaksi();
});
</script>
</body>
</html>