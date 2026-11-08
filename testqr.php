<?php
include "phpqrcode/qrlib.php";

QRcode::png("TEST QR", "test.png");
echo "DONE";