<?php
// kurang.php
session_start();
require_once("database.php");

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: login.php?msg=belum_login");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kode_barang'])) {
    $kode_barang = mysqli_real_escape_string($dbconnect, $_POST['kode_barang']);

    // Retrieve item information
    $barangInfo = tampilanBarangById($kode_barang);

    if ($barangInfo && $barangInfo['jumlah'] > 0) {
        mysqli_autocommit($dbconnect, false);

        // Decrease stock
        $newStock = $barangInfo['jumlah'] - 1;
        $updateStockQuery = "UPDATE barang SET jumlah = '$newStock' WHERE kode_barang = '$kode_barang'";

        if (mysqli_query($dbconnect, $updateStockQuery)) {
            mysqli_commit($dbconnect);
            header("Location: admin.php");
            exit();
        } else {
            mysqli_rollback($dbconnect);
            echo "Error updating stock: " . mysqli_error($dbconnect);
        }

        mysqli_autocommit($dbconnect, true);
    } else {
        echo "Item not found or stock is already 0.";
    }
} else {
    echo "Invalid request.";
}
?>
