<?php
include '../koneksi.php';

// CHART NEGARA
$negaraChart = mysqli_query($conn, "
    SELECT negara, COUNT(*) as total 
    FROM pengiriman 
    GROUP BY negara
");

$negaraLabels = [];
$negaraValues = [];

while($n = mysqli_fetch_assoc($negaraChart)){
    $negaraLabels[] = $n['negara'];
    $negaraValues[] = $n['total'];
}

// CHART 7 HARI
$chart = mysqli_query($conn, "
    SELECT DATE(tanggal_masuk) as tgl, COUNT(*) as total 
    FROM pengiriman 
    GROUP BY DATE(tanggal_masuk)
    ORDER BY tgl ASC 
    LIMIT 7
");

$labels = [];
$values = [];

while($c = mysqli_fetch_assoc($chart)){
    $labels[] = date("d M", strtotime($c['tgl']));
    $values[] = $c['total'];
}

echo json_encode([
    "labels" => $labels,
    "values" => $values,
    "negaraLabels" => $negaraLabels,
    "negaraValues" => $negaraValues
]);