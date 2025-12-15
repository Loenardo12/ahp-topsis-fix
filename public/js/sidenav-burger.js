// sidenav transition-burger

var sidenav = document.querySelector("aside");
var sidenav_trigger = document.querySelector("[sidenav-trigger]");
var sidenav_close_button = document.querySelector("[sidenav-close]");

// Hanya jalankan logika jika semua elemen ditemukan
if (sidenav && sidenav_trigger) {
    var burger = sidenav_trigger.firstElementChild;
    var top_bread = burger ? burger.firstElementChild : null;
    var bottom_bread = burger ? burger.lastElementChild : null;

    // Tambahkan event listener ke trigger
    sidenav_trigger.addEventListener("click", function () {
        if (sidenav_close_button) {
            sidenav_close_button.classList.toggle("hidden");
        }
        sidenav.classList.toggle("translate-x-0");
        sidenav.classList.toggle("shadow-soft-xl");
        if (top_bread) {
            top_bread.classList.toggle("translate-x-[5px]");
        }
        if (bottom_bread) {
            bottom_bread.classList.toggle("translate-x-[5px]");
        }
    });

    // Tambahkan event listener ke close button
    if (sidenav_close_button) {
        sidenav_close_button.addEventListener("click", function () {
            sidenav_trigger.click();
        });
    }

    // Tambahkan event listener ke window untuk menutup sidenav saat klik di luar
    window.addEventListener("click", function (e) {
        if (
            !sidenav.contains(e.target) &&
            !sidenav_trigger.contains(e.target)
        ) {
            if (sidenav.classList.contains("translate-x-0")) {
                sidenav_trigger.click();
            }
        }
    });
} else {
    // Jika elemen tidak ditemukan, jangan lakukan apa-apa dan tidak error
    console.log(
        "Sidenav elements not found on this page. Skipping sidenav logic."
    );
}
