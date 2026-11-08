<?php
include '../koneksi.php';

$data = mysqli_query($conn, "SELECT * FROM rfq ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Data RFQ</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

body{
    background:#f4f6f9;
    padding:30px;
}

.container{
    max-width:1200px;
    margin:auto;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.header h2{
    color:#333;
}

.btn-dashboard{
    text-decoration:none;
    background:#0d6efd;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    transition:.3s;
}

.btn-dashboard:hover{
    background:#0b5ed7;
}

.card{
    background:white;
    border-radius:12px;
    padding:20px;
    box-shadow:0 4px 15px rgba(0,0,0,.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#0d6efd;
    color:white;
    padding:14px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f8f9fa;
}

.badge{
    padding:6px 12px;
    border-radius:20px;
    color:white;
    font-size:13px;
    font-weight:bold;
}

.pending{
    background:#ffc107;
    color:#000;
}

.diproses{
    background:#17a2b8;
}

.dikirim{
    background:#28a745;
}

select{
    padding:8px;
    border:1px solid #ddd;
    border-radius:6px;
}

.btn-update{
    background:#28a745;
    color:white;
    border:none;
    padding:8px 12px;
    border-radius:6px;
    cursor:pointer;
    margin-top:5px;
}

.btn-update:hover{
    background:#218838;
}

.resi{
    font-weight:bold;
    color:#0d6efd;
}

.no-data{
    text-align:center;
    padding:20px;
    color:#888;
}
.deal{
    background:#28a745;
}

.ditolak{
    background:#dc3545;
}
</style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2>📦 Data Request For Quotation (RFQ)</h2>

        <!-- GANTI dashboard.php sesuai halaman dashboard admin lu -->
        <a href="dashboard.php" class="btn-dashboard">
            ← Kembali ke Dashboard
        </a>
    </div>

    <div class="card">

        <table>

            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Produk</th>
                <th>Status</th>
                <th>Resi</th>
                <th>Aksi</th>
            </tr>

            <?php
            if(mysqli_num_rows($data) > 0){
                while($d = mysqli_fetch_assoc($data)){
            ?>

            <tr>

                <td><?= htmlspecialchars($d['nama']) ?></td>

                <td><?= htmlspecialchars($d['email']) ?></td>

                <td><?= htmlspecialchars($d['produk']) ?></td>

                <td>
                    <span class="badge <?= $d['status'] ?>">
                        <?= ucfirst($d['status']) ?>
                    </span>
                </td>

                <td class="resi">

<?php if(!empty($d['resi']) && $d['resi'] != '-') { ?>

    <?= htmlspecialchars($d['resi']) ?>
    <br>
    <small style="color:green;">
        Pengiriman Sudah Dibuat
    </small>

<?php } else { ?>

    -
    <br>
    <small style="color:#999;">
        Belum Ada Resi
    </small>

<?php } ?>

</td>

                <td>

                    <form method="POST" action="update_status.php">

                        <input type="hidden" name="id"
                               value="<?= $d['id'] ?>">

                       <select name="status" required>

    <option value="pending"
        <?= $d['status']=='pending'?'selected':'' ?>>
        Pending
    </option>

    <option value="diproses"
        <?= $d['status']=='diproses'?'selected':'' ?>>
        Diproses
    </option>

    <option value="deal"
        <?= $d['status']=='deal'?'selected':'' ?>>
        Deal
    </option>

    <option value="ditolak"
        <?= $d['status']=='ditolak'?'selected':'' ?>>
        Ditolak
    </option>

</select>

                        <br>

                        <button type="submit"
                                class="btn-update">
                            Update
                        </button>

                    </form>

                </td>

            </tr>

            <?php
                }
            }else{
            ?>

            <tr>
                <td colspan="6" class="no-data">
                    Belum ada data RFQ
                </td>
            </tr>

            <?php } ?>

        </table>

    </div>

</div>

</body>
</html>