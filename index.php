<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: login.php?msg=belum_login");
    exit();
}


if (isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
    header("location: admin.php");
    exit();
}


require_once('database.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kode_barang'])) {
    $kode_barang = mysqli_real_escape_string($dbconnect, $_POST['kode_barang']);

    $barangInfo = tampilanBarangById($kode_barang);

    if ($barangInfo) {
        $currentStock = $barangInfo['jumlah'];

        if ($currentStock > 0) {
       
            mysqli_autocommit($dbconnect, false);

            $newStock = $currentStock - 1;
            $updateStockQuery = "UPDATE barang SET jumlah = '$newStock' WHERE kode_barang = '$kode_barang'";

            if (mysqli_query($dbconnect, $updateStockQuery)) {
                mysqli_commit($dbconnect);
                echo "successfully.";
            } else {

                mysqli_rollback($dbconnect);
                echo "Error updating stock: " . mysqli_error($dbconnect);
            }


            mysqli_autocommit($dbconnect, true);
        } else {
            echo "Not enough stock available.";
        }
    } else {
        echo "Item not found.";
    }
} else {
    echo "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Barang</title>
</head>
<style>
       body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.box {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin: 20px;
    padding: 20px;
    box-sizing: border-box;
}

h2 {
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #4CAF50;
    color: #fff;
}

.pinjam-btn {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.pinjam-btn:hover {
    background-color: #45a049;
}

.legot {
    text-align: right;
    margin: 20px;
}

.legot a {
    text-decoration: none;
    color: #333;
    padding: 8px 12px;
    border: 1px solid #333;
    border-radius: 4px;
}

.legot a:hover {
    background-color: #333;
    color: #fff;
}

</style>
<body>
<div class="box">
<div class="legot">
        <a href="logout.php">Logout</a>
    </div>
    <div class="box">
        <h2>List Barang</h2>

        <?php
    
        $barangList = tampilanBarang();
        ?>

        <?php if (!empty($barangList)) : ?>
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Merk</th>
                        <th>Jumlah Stok</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($barangList as $barang) : ?>
                        <tr>
                            <td><?= $barang['kode_barang'] ?></td>
                            <td><?= $barang['nama_brg'] ?></td>
                            <td><?= $barang['kategori'] ?></td>
                            <td><?= $barang['merk'] ?></td>
                            <td><?= $barang['jumlah'] ?></td>
                            <td>
                              
                                <button class="pinjam-btn" onclick="meminjam(<?= $barang['kode_barang'] ?>)">Pinjam</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        <?php else : ?>
            <p>Tidak ada.</p>
        <?php endif; ?>
    </div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
function meminjam(kode_barang) {
    $.ajax({
        type: "POST",
        url: "meminjam.php",
        data: { kode_barang: kode_barang },
        success: function(response) {
            alert(response); 
            location.reload(); 
        },
        error: function(error) {
            alert("Error: " + error.responseText);
        }
    });
}
function confirmLogout() {
        var confirmLogout = confirm("Apakah anda yakin ingin logout?");
        
        if (confirmLogout) {
            window.location.href = "logout.php";
        }
    }
</script>
</html>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
