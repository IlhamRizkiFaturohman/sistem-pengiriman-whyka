<?php

$resi = "";

if(isset($_GET['resi'])){
    $resi = trim($_GET['resi']);
}

?>

<!DOCTYPE html>
<html>
<head>

<title>WHYKA TRACKING SYSTEM</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>

body{
    font-family:Arial;
    margin:20px;
}

input{
    padding:10px;
    width:300px;
}

button{
    padding:10px 20px;
    cursor:pointer;
}

#map{
    height:600px;
    width:100%;
    margin-top:20px;
}

.info{
    margin-top:15px;
    padding:15px;
    background:#f5f5f5;
    border-radius:8px;
}

/* ICON EMOJI */
.emoji-icon{
    font-size: 26px;
    text-align: center;
    line-height: 30px;
}

</style>

</head>
<body>

<h2>🚚 WHYKA TRACKING SYSTEM</h2>

<form method="GET">
    <input type="text" name="resi"
        placeholder="Masukkan Nomor Resi"
        value="<?= htmlspecialchars($resi) ?>"
        required>

    <button type="submit">TRACK</button>
</form>

<div id="info"></div>
<div id="map"></div>

<script>

var resi = "<?= $resi ?>";

/* ICON EMOJI (AMAN 100%) */
var planeIcon = L.divIcon({
    html: "✈️",
    className: "emoji-icon",
    iconSize: [30, 30]
});

var shipIcon = L.divIcon({
    html: "🚢",
    className: "emoji-icon",
    iconSize: [30, 30]
});

var movingIcon = L.divIcon({
    html: "✈️",
    className: "emoji-icon",
    iconSize: [35, 35]
});

if(resi !== ""){

fetch("api/get_tracking.php?resi=" + encodeURIComponent(resi))
.then(res => res.json())
.then(tracking => {

    if(!Array.isArray(tracking) || tracking.length === 0){
        document.getElementById("info").innerHTML =
        "<h3>Resi tidak ditemukan</h3>";
        return;
    }

    // urut perjalanan
    tracking.sort((a,b) =>
        new Date(a.created_at) - new Date(b.created_at)
    );

    let last = tracking[tracking.length - 1];

    document.getElementById("info").innerHTML = `
        <div class="info">
            <b>No Resi:</b> ${last.no_resi}<br>
            <b>Status:</b> ${last.status}<br>
            <b>Lokasi:</b> ${last.lokasi}<br>
            <b>Update:</b> ${last.created_at}
        </div>
    `;

    var map = L.map('map').setView([0,0],2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution:'© OpenStreetMap'
    }).addTo(map);

    var route = [];

    tracking.forEach(item => {

        var pos = [parseFloat(item.lat), parseFloat(item.lng)];
        route.push(pos);

        var mode = (item.mode || '').toLowerCase();

        var icon = (mode === 'sea') ? shipIcon : planeIcon;

        L.marker(pos, { icon: icon })
        .addTo(map)
        .bindPopup(
            "<b>" + item.no_resi + "</b><br>" +
            item.status + "<br>" +
            item.lokasi + "<br>" +
            "Mode: " + item.mode
        );
    });

    // GARIS TRACKING
    var line = L.polyline(route, {
        color: 'blue',
        weight: 4
    }).addTo(map);

    map.fitBounds(line.getBounds());

    // ANIMASI PESAWAT JALAN
    var marker = L.marker(route[0], { icon: movingIcon }).addTo(map);

    let i = 0;

    function move(){
        if(i < route.length){
            marker.setLatLng(route[i]);
            map.panTo(route[i]);
            i++;
            setTimeout(move, 1200);
        }
    }

    move();

})

.catch(err => {
    console.log(err);
    document.getElementById("info").innerHTML =
    "<h3>Gagal mengambil data tracking</h3>";
});

}

</script>

</body>
</html>