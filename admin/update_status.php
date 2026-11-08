<?php
include '../koneksi.php';

$id = (int) $_POST['id'];
$status = mysqli_real_escape_string($conn,$_POST['status']);

/* update status rfq */
mysqli_query($conn,"
UPDATE rfq
SET status='$status'
WHERE id='$id'
");

/*
|--------------------------------------------------------------------------
| JIKA RFQ DEAL
|--------------------------------------------------------------------------
*/
if($status == "deal"){

    $data = mysqli_fetch_assoc(
        mysqli_query($conn,"
        SELECT *
        FROM rfq
        WHERE id='$id'
        ")
    );

    $id_user      = $data['id_user'];
    $nama         = $data['nama'];
    $produk       = $data['produk'];
    $kota_tujuan  = $data['kota_tujuan'];

    /*
    |--------------------------------------------------------------------------
    | GENERATE RESI
    |--------------------------------------------------------------------------
    */
    $resi_lama = $data['resi'];

    if(empty($resi_lama) || $resi_lama == '-'){

        $resi = "WHYKA-" . date("Ymd") . "-" . rand(1000,9999);

        mysqli_query($conn,"
        UPDATE rfq
        SET resi='$resi'
        WHERE id='$id'
        ");

    }else{

        $resi = $resi_lama;

    }

    /*
    |--------------------------------------------------------------------------
    | CEK DUPLIKAT PENGIRIMAN
    |--------------------------------------------------------------------------
    */
    $cek = mysqli_num_rows(
        mysqli_query($conn,"
        SELECT id
        FROM pengiriman
        WHERE id_rfq='$id'
        ")
    );

    if($cek == 0){

    mysqli_query($conn,"
    INSERT INTO pengiriman
    (
        id_rfq,
        id_user,
        nama_pengirim,
        nama_penerima,
        nama_barang,
        kota,
        status,
        no_resi,
        tanggal_masuk
    )
    VALUES
    (
        '$id',
        '$id_user',
        '$nama',
        '$nama',
        '$produk',
        '$kota_tujuan',
        'booking',
        '$resi',
        NOW()
    )
    ");

   mysqli_query($conn,"
INSERT INTO tracking_history
(
    no_resi,
    status,
    lokasi,
    catatan,
    lat,
    lng,
    jalur,
    created_at
)
VALUES
(
    '$resi',
    'booking',
    'Shanghai Pudong Airport',
    'Pengiriman berhasil dibuat',
    31.1443,
    121.8083,
    'udara',
    NOW()
)
");

}
}

if($status == 'ditolak'){

    mysqli_query($conn,"
    DELETE FROM pengiriman
    WHERE id_rfq='$id'
    ");

    mysqli_query($conn,"
    DELETE FROM tracking_history
    WHERE no_resi = (
        SELECT resi
        FROM rfq
        WHERE id='$id'
    )
    ");

    mysqli_query($conn,"
    UPDATE rfq
    SET resi='-'
    WHERE id='$id'
    ");
}

header('Location: admin_rfq.php');
exit;
?>