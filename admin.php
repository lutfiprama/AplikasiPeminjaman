<?php
session_start();
require_once("database.php");

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("location: login.php?msg=belum_login");
    exit();
}

$query = "SELECT * FROM transaksi";
$result = mysqli_query($dbconnect, $query);

if ($result) {
    $borrowers = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    echo "Error: " . mysqli_error($dbconnect);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

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

    <div class="legot">
        <a href="logout.php">Logout</a>
    </div>

    <div class="box">
        <h2>List Data Peminjam</h2>

        <?php if (!empty($borrowers)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Kode Barang</th>
                        <th>NIS</th>
                        <th>Tanggal Pinjam</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowers as $borrower) : ?>
                        <tr>
                            <td><?= $borrower['id_transaksi'] ?></td>
                            <td><?= $borrower['kode_barang'] ?></td>
                            <td><?= $borrower['nis'] ?></td>
                            <td><?= $borrower['tanggal_pinjam'] ?></td>
                            <td>
                                <button class="pinjam-btn" onclick="mengembalikan(<?= $borrower['id_transaksi'] ?>)">Kembalikan</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Tidak ada peminjam.</p>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>List Data Barang</h2>

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
                        <th colspan="2">Action</th>
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
                                <form action="tambah.php" method="post">
                                    <input type="hidden" name="kode_barang" value="<?= $barang['kode_barang'] ?>">
                                    <button type="submit" class="pinjam-btn">Tambah</button>
                                </form>
                            </td>
                            <td>
                                <form action="kurang.php" method="post">
                                    <input type="hidden" name="kode_barang" value="<?= $barang['kode_barang'] ?>">
                                    <button type="submit" class="pinjam-btn">Kurang</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Tidak ada barang.</p>
        <?php endif; ?>
    </div>

    <div class="box">
        <h2>List Data Pengguna</h2>

        <?php
        $queryUsers = "SELECT * FROM users";
        $resultUsers = mysqli_query($dbconnect, $queryUsers);

        if ($resultUsers) {
            $usersList = mysqli_fetch_all($resultUsers, MYSQLI_ASSOC);
        } else {
            echo "Error fetching users: " . mysqli_error($dbconnect);
            exit();
        }
        ?>

        <?php if (!empty($usersList)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Login</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersList as $user) : ?>
                        <tr>
                            <td><?= $user['id_login'] ?></td>
                            <td><?= $user['nis'] ?></td>
                            <td><?= $user['nama'] ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['role'] ?></td>
                            <td>
                                <button class="pinjam-btn" onclick="hapus(<?= $user['id_login'] ?>)">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>Tidak ada user.</p>
        <?php endif; ?>
    </div>

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    function mengembalikan(id_transaksi) {
        var confirmReturn = confirm("Apakah Anda yakin ingin mengembalikan barang?");

        if (confirmReturn) {
            $.ajax({
                url: "mengembalikan.php",
                type: "POST",
                data: {
                    id_transaksi: id_transaksi
                },
                success: function (response) {
                    alert(response);
                    location.reload();
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    }
</script>

</html>
