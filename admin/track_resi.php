<!DOCTYPE html>
<html>
<head>
    <title>Admin Tracking - Whyka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">   
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: white; min-height: 100vh; background: linear-gradient(135deg, #1a0a0a 0%, #2d1010 50%, #1a0a0a 100%); position: relative; overflow-x: hidden; }
        body::before { content: ""; position: fixed; top: -50%; left: -50%; width: 200%; height: 200%; background: radial-gradient(circle at 20% 80%, rgba(220, 38, 38, 0.15) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(248, 113, 113, 0.1) 0%, transparent 50%), radial-gradient(circle at 40% 40%, rgba(185, 28, 28, 0.08) 0%, transparent 40%); animation: bgFloat 20s ease-in-out infinite; z-index: -1; }
        @keyframes bgFloat { 0%, 100% { transform: translate(0, 0) rotate(0deg); } 33% { transform: translate(30px, -30px) rotate(5deg); } 66% { transform: translate(-20px, 20px) rotate(-5deg); } }
        .particles { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: -1; overflow: hidden; }
        .particle { position: absolute; width: 4px; height: 4px; background: rgba(248, 113, 113, 0.4); border-radius: 50%; animation: particleFloat 15s infinite; }
        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 20s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 18s; }
        .particle:nth-child(3) { left: 30%; animation-delay: 4s; animation-duration: 22s; }
        .particle:nth-child(4) { left: 50%; animation-delay: 1s; animation-duration: 19s; }
        .particle:nth-child(5) { left: 70%; animation-delay: 3s; animation-duration: 21s; }
        .particle:nth-child(6) { left: 85%; animation-delay: 2s; animation-duration: 17s; }
        @keyframes particleFloat { 0% { transform: translateY(100vh) scale(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-100vh) scale(1); opacity: 0; } }
        .sidebar { width: 260px; height: 100vh; position: fixed; left: 0; top: 0; background: linear-gradient(180deg, rgba(30, 10, 10, 0.98), rgba(20, 5, 5, 0.99)); backdrop-filter: blur(20px); padding: 25px 20px; box-shadow: 5px 0 30px rgba(0, 0, 0, 0.5); border-right: 1px solid rgba(220, 38, 38, 0.2); z-index: 100; }
        .logo-box { text-align: center; margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid rgba(220, 38, 38, 0.2); }
        .logo-box h5 { font-weight: 700; font-size: 1.1rem; background: linear-gradient(135deg, #f87171, #fca5a5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; letter-spacing: 1px; margin: 0; }
        .logo-box small { color: #a1a1aa; font-size: 0.7rem; letter-spacing: 3px; display: block; margin-top: 5px; }
        .sidebar a { display: flex; align-items: center; gap: 12px; color: #d4d4d8; padding: 14px 18px; margin-bottom: 8px; text-decoration: none; border-radius: 12px; transition: all 0.3s ease; font-weight: 500; font-size: 0.9rem; position: relative; overflow: hidden; }
        .sidebar a::before { content: ""; position: absolute; left: 0; top: 0; width: 3px; height: 100%; background: linear-gradient(180deg, #dc2626, #f87171); transform: scaleY(0); transition: transform 0.3s ease; }
        .sidebar a:hover { background: rgba(220, 38, 38, 0.15); color: #fca5a5; transform: translateX(5px); }
        .sidebar a:hover::before { transform: scaleY(1); }
        .sidebar a.active { background: linear-gradient(135deg, rgba(220, 38, 38, 0.3), rgba(185, 28, 28, 0.2)); color: #fca5a5; }
        .sidebar a.active::before { transform: scaleY(1); }
        .sidebar a.logout { color: #f87171; margin-top: 20px; }
        .sidebar a.logout:hover { background: rgba(220, 38, 38, 0.2); color: #fca5a5; }
        .content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .page-header { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
        .page-icon { width: 55px; height: 55px; background: linear-gradient(135deg, #dc2626, #991b1b); border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 10px 30px rgba(220, 38, 38, 0.4); }
        .page-title { font-size: 1.6rem; font-weight: 700; background: linear-gradient(135deg, #f87171, #fca5a5); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin: 0; }
        .page-subtitle { color: #71717a; font-size: 0.85rem; margin: 0; }
        .box { background: linear-gradient(145deg, rgba(30, 15, 15, 0.95), rgba(20, 10, 10, 0.98)); backdrop-filter: blur(20px); padding: 35px; border-radius: 24px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 100px rgba(220, 38, 38, 0.08), inset 0 1px 0 rgba(255, 255, 255, 0.03); border: 1px solid rgba(220, 38, 38, 0.15); position: relative; overflow: hidden; }
        .box::before { content: ""; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #dc2626, #f87171, #dc2626); background-size: 200% 100%; animation: gradientSlide 3s ease infinite; }
        @keyframes gradientSlide { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
        input { width: 100%; padding: 18px 20px; border-radius: 16px; border: 2px solid rgba(220, 38, 38, 0.2); outline: none; background: rgba(15, 10, 10, 0.8); color: #fafafa; font-size: 1rem; font-family: 'Poppins', sans-serif; transition: all 0.3s ease; }
        input::placeholder { color: #71717a; }
        input:focus { border-color: #dc2626; box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15), 0 0 30px rgba(220, 38, 38, 0.1); background: rgba(20, 12, 12, 0.9); }
        .track-card { margin-top: 25px; background: linear-gradient(145deg, rgba(40, 20, 20, 0.9), rgba(30, 15, 15, 0.95)); color: #e4e4e7; padding: 25px; border-radius: 18px; animation: fadeSlide 0.4s ease; border: 1px solid rgba(220, 38, 38, 0.2); box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3); }
        @keyframes fadeSlide { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .track-card b { color: #fca5a5; }
        .alert-danger { background: linear-gradient(135deg, rgba(220, 38, 38, 0.2), rgba(185, 28, 28, 0.15)); border: 1px solid rgba(220, 38, 38, 0.3); color: #fca5a5; border-radius: 12px; padding: 15px 20px; }
        .alert-info { background: linear-gradient(135deg, rgba(220, 38, 38, 0.2), rgba(185, 28, 28, 0.15)); border: 1px solid rgba(220, 38, 38, 0.3); color: #fca5a5; border-radius: 12px; padding: 15px 20px; }
        #map { height: 400px; border-radius: 16px; margin-top: 20px; border: 2px solid rgba(220, 38, 38, 0.2); }
        .custom-marker{
        background: transparent !important;
        border: none !important;
    }
    </style>
</head>
<body>
<div class="particles">
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
    <div class="particle"></div><div class="particle"></div><div class="particle"></div>
</div>
<div class="sidebar">
    <div class="logo-box"><h5>WHYKA</h5><small>EXSPRES LOGISTICS</small></div>
    <a href="dashboard.php"><span>📊</span> Dashboard</a>
    <a href="track_resi.php" class="active"><span>🔍</span> Tracking</a>
    <a href="pengiriman.php"><span>📦</span> Pengiriman</a>
    <a href="../logout.php" class="logout"><span>🚪</span> Logout</a>
</div>
<div class="content">
    <div class="page-header">
        <div class="page-icon">🚚</div>
        <div><h4 class="page-title">Tracking Resi Real-Time</h4><p class="page-subtitle">Lacak status pengiriman secara langsung</p></div>
    </div>
    <div class="box">
        <input type="text" id="resi_input" class="form-control" placeholder="Masukkan No Resi, contoh: WHYKA-20260602-2897">
        <div id="result_box" class="mt-3"></div>
        <div id="map"></div>
    </div>  
</div>

<script>
let map;
let marker = null;
let polyline = null;
let routeMarkers = [];
let interval = null;

document.addEventListener("DOMContentLoaded", function () {
    map = L.map('map').setView([-6.9, 107.6], 10);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    setTimeout(() => map.invalidateSize(), 300);

    const input = document.getElementById("resi_input");
    input.addEventListener("input", function () {
        loadTracking();
        if (interval) clearInterval(interval);
        interval = setInterval(loadTracking, 3000);
    });
});

function loadTracking() {
    const resi = document.getElementById("resi_input").value.trim();
    if(resi === "") return;

    fetch('../api/get_tracking.php?resi=' + encodeURIComponent(resi))
    .then(res => res.json())
    .then(resp => {
        if(resp.status !== "success") {
            document.getElementById("result_box").innerHTML = `<div class="alert alert-danger">${resp.msg}</div>`;
            return;
        }

        const data = resp.data;
        document.getElementById("result_box").innerHTML = `
            <div class="track-card">
                <b>📦 Resi:</b> ${data.resi} <br>
                <b>📍 Status:</b> ${data.status} <br>
                <b>🌍 Lokasi:</b> ${data.location} <br>
                <b>📝 Catatan:</b> ${data.catatan} <br>
                <b>🛫 Jalur:</b> ${data.jalur} <br>
                <b>🕒 Update:</b> ${data.updated_at}
            </div>
        `;

        const lat = Number(data.lat);
        const lng = Number(data.lng);
        map.setView([lat, lng], 13);
        
        // HAPUS SEMUA LAMA
        if(marker) map.removeLayer(marker);
        if(polyline) map.removeLayer(polyline);
        routeMarkers.forEach(m => map.removeLayer(m));
        routeMarkers = [];

        // WARNA BEDAIN JALUR
const warna = data.jalur === 'udara' ? '#ef4444' : '#3b82f6';

// HAPUS MARKER LAMA
if(marker){
    map.removeLayer(marker);
}

// ICON BERDASARKAN JALUR
let iconHtml;

if(data.jalur === 'laut'){

    iconHtml = `
        <div style="
            font-size:40px;
            text-align:center;
        ">
            🚢
        </div>
    `;

}else if(data.jalur === 'udara'){

    iconHtml = `
        <div style="
            font-size:40px;
            text-align:center;
        ">
            ✈️
        </div>
    `;

}else{

    iconHtml = `
        <div style="
            font-size:40px;
            text-align:center;
        ">
            📍
        </div>
    `;

}

const vehicleIcon = L.divIcon({
    html: iconHtml,
    className: 'custom-marker',
    iconSize: [40,40],
    iconAnchor: [20,20]
});

// MARKER POSISI TERAKHIR
marker = L.marker([lat, lng], {
    icon: vehicleIcon
}).addTo(map);

marker.bindPopup(`
    <b>Status:</b> ${data.status}<br>
    <b>Lokasi:</b> ${data.location}<br>
    <b>Jalur:</b> ${data.jalur}
`).openPopup();

        // GAMBAR GARIS JALUR + TITIK TRANSIT
        if(resp.route && resp.route.length > 1){
            const latlngs = resp.route.map(p => [Number(p.lat), Number(p.lng)]);
            
            polyline = L.polyline(latlngs, {
                color: warna,
                weight: 5,
                opacity: 0.8,
                dashArray: data.jalur == 'udara' ? '10, 10' : null
            }).addTo(map);
            
            map.fitBounds(polyline.getBounds());
            
            // TITIK KECIL TIAP TRANSIT
             resp.route.forEach(p => {
            const m = L.circleMarker([Number(p.lat), Number(p.lng)], {
            radius: 7,
            fillColor: warna,
            color: '#fff',
            weight: 3,
            fillOpacity: 1
            }).addTo(map).bindPopup(`
            <b>${p.nama_tempat}</b><br>
            Status: ${p.status}
            `);

            routeMarkers.push(m);
        });
        }
    })
    .catch(err => {
        console.log(err);
        document.getElementById("result_box").innerHTML = `<div class="alert alert-danger">Gagal ambil data</div>`;
    });
}
</script>
</body>
</html>