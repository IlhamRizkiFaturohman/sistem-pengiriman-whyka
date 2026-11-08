document.addEventListener("DOMContentLoaded", function () {

    function loadStatus() {

        const resiInput = document.getElementById("resi_input");
        const statusBox = document.getElementById("status_live");

        // safety check
        if (!resiInput || !statusBox) return;

        const resi = resiInput.value.trim();

        if (resi.length > 0) {

            fetch("../user/cek_status.php?resi=" + encodeURIComponent(resi))
                .then(response => response.text())
                .then(data => {
                    statusBox.innerHTML = data;
                })
                .catch(error => {
                    console.log("Fetch error:", error);
                });

        } else {
            statusBox.innerHTML = "";
        }
    }

    // debounce biar tidak spam request saat ngetik
    let typingTimer;

    document.addEventListener("input", function (e) {
        if (e.target.id === "resi_input") {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(loadStatus, 500);
        }
    });

    // auto refresh tiap 3 detik (real-time mode)
    setInterval(loadStatus, 3000);

});