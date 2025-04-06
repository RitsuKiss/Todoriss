
function logout() {
    window.location.href = 'logout.php';
}
function edprofile() {
    window.location.href = 'editprofile.php';
}
function setting() {
    window.location.href = 'setting.php';
}
if (/Mobi|Android/i.test(navigator.userAgent)) {
    alert("Silakan aktifkan mode desktop di browser Anda untuk pengalaman terbaik.");
  }

  let musik = document.getElementById("musik");

    if (sessionStorage.getItem("musikDiputar")) {
        musik.currentTime = sessionStorage.getItem("musikWaktu") || 0;
        musik.muted = false;
        musik.volume = 0.02;
    } else {
        musik.muted = true;
        musik.volume = 0; 
    }

    document.addEventListener("click", function() {
        musik.muted = false;
        musik.play();

        let fadeVolume = 0;
        let fadeIn = setInterval(() => {
            if (fadeVolume < 0.02) { 
                fadeVolume += 0.001; 
                musik.volume = fadeVolume;
            } else {
                clearInterval(fadeIn);
            }
        }, 100);

        sessionStorage.setItem("musikDiputar", "true");
    }, { once: true });

    setInterval(() => {
        sessionStorage.setItem("musikWaktu", musik.currentTime);
    }, 1000);

    document.querySelector('form').addEventListener('submit', function(event) {
        var deadline = document.querySelector('input[name="deadline"]').value;
        var currentDate = new Date().toISOString().split('T')[0];

        if (deadline < currentDate) {
            alert("Tanggal deadline tidak boleh lebih kecil dari hari ini.");
            event.preventDefault();
        }
    });

    function openPopup(id) {
        document.getElementById(id).style.display = "block";
    }

    function closePopup(id) {
        document.getElementById(id).style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = "none";
        }
    }
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var toggleIcon = document.getElementById('togglePassword');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }