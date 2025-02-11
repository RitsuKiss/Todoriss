<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popup Manual</title>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            text-align: center;
        }
        .close {
            color: red;
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <button onclick="openPopup('popup1')">Buka Popup 1</button>
    <button onclick="openPopup('popup2')">Buka Popup 2</button>
    <button onclick="openPopup('popup3')">Buka Popup 3</button>
    <button onclick="openPopup('popup4')">Buka Popup 4</button>

    <!-- Modal 1 -->
    <div id="popup1" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('popup1')">&times;</span>
            <iframe src="popup1.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>

    <!-- Modal 2 -->
    <div id="popup2" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('popup2')">&times;</span>
            <iframe src="popup2.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>

    <!-- Modal 3 -->
    <div id="popup3" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('popup3')">&times;</span>
            <iframe src="popup3.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>

    <!-- Modal 4 -->
    <div id="popup4" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePopup('popup4')">&times;</span>
            <iframe src="popup4.php" width="100%" height="300px" style="border: none;"></iframe>
        </div>
    </div>

    <script>
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
    </script>

</body>
</html>
