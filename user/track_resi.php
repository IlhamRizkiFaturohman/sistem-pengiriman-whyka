<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tracking Resi - WHYKA EKSPRES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">   
    <style>
        :root{
          --bg-dark: #0f0f11;
          --card-dark: rgba(23, 23, 26, 0.7);
          --border-dark: rgba(255, 255, 255, 0.08);
          --red-primary: #E11D48;
          --red-hover: #BE123C;
          --red-glow: rgba(225, 29, 72, 0.4);
          --text-main: #F9FAFB;
          --text-muted: #9CA3AF;
          --blue: #3B82F6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Poppins', sans-serif; 
            color: var(--text-main); 
            min-height: 100vh; 
            background-color: var(--bg-dark);
            background-image: radial-gradient(circle at top left, rgba(225, 29, 72, 0.15), transparent 40%),
                              radial-gradient(circle at bottom right, rgba(225, 29, 72, 0.1), transparent 30%);
            position: relative; 
            overflow-x: hidden; 
        }

        /* --- SIDEBAR RED THEME --- */
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            position: fixed; 
            left: 0; 
            top: 0; 
            background: var(--card-dark);
            backdrop-filter: blur(12px); 
            padding: 25px; 
            border-right: 1px solid var(--border-dark);
            z-index: 100; 
            display: flex;
            flex-direction: column;
        }
        .brand{ /* Wrapper Logo + Teks */
            display: flex;
            align-items: center;
            gap: 12px;
            margin:0 0 30px 0;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-dark);
        }
        .brand img{
            width: 42px;
            height: 42px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .brand-text{
            color:var(--text-main);
            font-weight: 700;
            font-size: 18px;
            line-height: 1.2;
        }
        .brand-text span{color:var(--red-primary);}
        
        .sidebar a { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            color: var(--text-muted); 
            padding: 12px 15px; 
            margin-bottom: 5px; 
            text-decoration: none; 
            border-radius: 10px; 
            transition: all 0.3s ease; 
            font-weight: 500; 
            font-size: 0.95rem; 
        }
        .sidebar a:hover { 
            background: rgba(225, 29, 72, 0.15); 
            color: var(--text-main); 
            transform: translateX(5px); 
        }
        .sidebar a.active { 
            background: var(--red-primary); 
            color: white; 
            box-shadow: 0 4px 15px var(--red-glow);
        }
        .sidebar a.logout { 
            color: var(--text-muted); 
            margin-top: auto; /* Dorong ke bawah */
        }
        .sidebar a.logout:hover { 
            background: rgba(220, 38, 38, 0.2); 
            color: #fca5a5; 
        }

        .content { 
            margin-left: 260px; 
            padding: 40px; 
            min-height: 100vh; 
        }
        
        /* --- HEADER --- */
        .page-header { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            margin-bottom: 30px; 
        }
        .page-icon { 
            width: 55px; 
            height: 55px; 
            background: var(--red-primary); 
            border-radius: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 1.5rem; 
            box-shadow: 0 10px 30px var(--red-glow); 
        }
        .page-title { 
            font-size: 1.8rem; 
            font-weight: 700; 
            margin: 0;
        }
        .page-subtitle { 
            color: var(--text-muted); 
            font-size: 0.9rem; 
            margin: 0; 
        }

        /* --- BOX CARD --- */
        .box { 
            background: var(--card-dark);
            backdrop-filter: blur(12px); 
            padding: 30px; 
            border-radius: 16px; 
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border-dark); 
            position: relative; 
            overflow: hidden; 
        }
        .box::before { 
            content: ""; 
            position: absolute; 
            top: 0; 
            left: 0; 
            right: 0; 
            height: 3px; 
            background: linear-gradient(90deg, var(--red-primary), #FB7185, var(--red-primary)); 
            background-size: 200% 100%; 
            animation: gradientSlide 3s ease infinite; 
        }
        @keyframes gradientSlide { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }

        /* --- INPUT --- */
        input { 
            width: 100%; 
            padding: 16px 20px; 
            border-radius: 12px; 
            border: 1px solid var(--border-dark); 
            outline: none; 
            background: rgba(15, 10, 10, 0.8); 
            color: var(--text-main); 
            font-size: 1rem; 
            font-family: 'Poppins', sans-serif; 
            transition: all 0.3s ease; 
        }
        input::placeholder { color: var(--text-muted); }
        input:focus { 
            border-color: var(--red-primary); 
            box-shadow: 0 0 0 4px var(--red-glow); 
            background: rgba(20, 12, 12, 0.9); 
        }

        /* --- TRACK CARD --- */
        .track-card { 
            margin-top: 25px; 
            background: rgba(40, 20, 20, 0.5);
            color: var(--text-main); 
            padding: 20px; 
            border-radius: 12px; 
            animation: fadeSlide 0.4s ease; 
            border: 1px solid var(--border-dark); 
        }
        @keyframes fadeSlide { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .track-card b { color: #fca5a5; }

        /* --- ALERT --- */
        .alert-danger, .alert-info { 
            background: rgba(225, 29, 72, 0.1); 
            border: 1px solid rgba(225, 29, 72, 0.3); 
            color: #fca5a5; 
            border-radius: 12px; 
            padding: 15px 20px; 
        }
        
        /* --- MAP --- */
        #map { 
            height: 400px; 
            border-radius: 12px; 
            margin-top: 20px; 
            border: 1px solid var(--border-dark); 
            background: #1a1a1a;
        }
        .custom-marker{
            background: transparent !important;
            border: none !important;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <!-- LOGO PT BARU -->
    <div class="brand">
        <img src="../assets/img/logo PT.png" alt="Logo WHYKA">
        <div class="brand-text">
            WHYKA <span>EKSPRES</span><br>
            <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">LOGISTIK</span>
        </div>
    </div>

    <a href="dashboard.php"><span>📊</span> Dashboard</a>
    <a href="track_resi.php" class="active"><span>📍</span> Tracking</a>
    <a href="../logout.php" class="logout"><span>🚪</span> Logout</a>
</div>

<div class="content">
    <div class="page-header">
        <div class="page-icon">📍</div>
        <div>
            <h4 class="page-title">Tracking Resi Real-Time</h4>
            <p class="page-subtitle">Lacak status pengiriman secara langsung</p>
        </div>
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
        
        if(marker) map.removeLayer(marker);
        if(polyline) map.removeLayer(polyline);
        routeMarkers.forEach(m => map.removeLayer(m));
        routeMarkers = [];

        const warna = data.jalur === 'udara' ? '#E11D48' : '#3b82f6'; // Ganti merah jadi --red-primary

        if(marker){
            map.removeLayer(marker);
        }

        let iconHtml;
        if(data.jalur === 'laut'){
            iconHtml = `<div style="font-size:40px;text-align:center;">🚢</div>`;
        }else if(data.jalur === 'udara'){
            iconHtml = `<div style="font-size:40px;text-align:center;">✈️</div>`;
        }else{
            iconHtml = `<div style="font-size:40px;text-align:center;">📍</div>`;
        }

        const vehicleIcon = L.divIcon({
            html: iconHtml,
            className: 'custom-marker',
            iconSize: [40,40],
            iconAnchor: [20,20]
        });

        marker = L.marker([lat, lng], {
            icon: vehicleIcon
        }).addTo(map);

        marker.bindPopup(`
            <b>Status:</b> ${data.status}<br>
            <b>Lokasi:</b> ${data.location}<br>
            <b>Jalur:</b> ${data.jalur}
        `).openPopup();

        if(resp.route && resp.route.length > 1){
            const latlngs = resp.route.map(p => [Number(p.lat), Number(p.lng)]);
            
            polyline = L.polyline(latlngs, {
                color: warna,
                weight: 5,
                opacity: 0.8,
                dashArray: data.jalur == 'udara' ? '10, 10' : null
            }).addTo(map);
            
            map.fitBounds(polyline.getBounds());
            
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