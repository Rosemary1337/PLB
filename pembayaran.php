<?php
session_start();
include 'config.php';

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ambil total dari keranjang
$user_id = $_SESSION['user_id'];
$query = mysqli_query($conn, "SELECT SUM(harga * jumlah) AS total FROM keranjang WHERE user_id='$user_id'");
$data = mysqli_fetch_assoc($query);
$total = $data['total'] ?? 0;

// Jika form disubmit
if (isset($_POST['bayar'])) {
    $metode = $_POST['metode'];

    // Upload bukti transfer (opsional)
    $bukti = null;
    if (!empty($_FILES['bukti']['name'])) {
        $namaFile = "bukti_" . time() . "_" . $_FILES['bukti']['name'];
        $temp = $_FILES['bukti']['tmp_name'];
        move_uploaded_file($temp, "images/" . $namaFile);
        $bukti = $namaFile;
    }

    // Simpan ke database
    mysqli_query($conn, "INSERT INTO pembayaran (user_id, total, metode, bukti) 
                         VALUES ('$user_id', '$total', '$metode', '$bukti')");

    // Hapus keranjang setelah pembayaran
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id='$user_id'");

    echo "<script>alert('Pembayaran berhasil! Pesanan kamu sedang diproses.'); window.location='profil.php';</script>";
}
?>

<?php include 'includes/header.php'; ?>

<h2>Pembayaran</h2>

<p>Total yang harus dibayar: <b>Rp <?= number_format($total) ?></b></p>

<form method="POST" enctype="multipart/form-data">
    <label>Metode Pembayaran:</label>
    <select name="metode" class="form-control" required>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="Dana">Dana</option>
        <option value="OVO">OVO</option>
        <option value="ShopeePay">ShopeePay</option>
        <option value="COD">COD (Bayar di Tempat)</option>
    </select>

    <br>

    <label>Upload Bukti Pembayaran (jika transfer):</label>
    <input type="file" name="bukti" class="form-control">

    <br>

    <button type="submit" name="bayar" class="btn btn-success">Bayar Sekarang</button>
</form>

<?php include 'includes/footer.php'; ?>

