<?php
include '../koneksi.php';

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=data_pengiriman.xls");

?>

<table border="1">
<tr>
    <th>No Resi</th>
    <th>Barang</th>
    <th>Status</th>
</tr>

<?php
$data = mysqli_query($conn,"SELECT * FROM pengiriman");

while($d = mysqli_fetch_array($data)){
?>

<tr>
    <td><?= $d['no_resi']; ?></td>
    <td><?= $d['nama_barang']; ?></td>
    <td><?= $d['status']; ?></td>
</tr>

<?php } ?>

</table>