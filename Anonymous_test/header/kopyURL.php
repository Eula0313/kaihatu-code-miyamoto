<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>URL表示</title>
<style>
.popup {
  display: none;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #f1f1f1;
  border: 1px solid #ccc;
  padding: 20px;
  z-index: 9999;
  text-align: center; 
}
.popup-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);
  z-index: 9998;
}
.popup button {
  padding: 10px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
}
.popup button:hover {
  background-color: #45a049;
}
</style>
</head>
<body>

<button onclick="togglePopup()">URL</button>

<div class="popup" id="popup">
  <div>
    <p>URL: <span id="websiteUrl"></span></p>
    <button onclick="copyUrl()">URLをコピー</button>
  </div>
</div>

<div class="popup-overlay" id="overlay" onclick="closePopup()"></div>

<script>
function togglePopup() {
  var popup = document.getElementById("popup");
  var overlay = document.getElementById("overlay");
  var websiteUrl = document.getElementById("websiteUrl");
  websiteUrl.textContent = window.location.href; // ウェブサイトのURLを取得して表示
  popup.style.display = "block";
  overlay.style.display = "block";
}

function closePopup() {
  var popup = document.getElementById("popup");
  var overlay = document.getElementById("overlay");
  popup.style.display = "none";
  overlay.style.display = "none";
}

function copyUrl() {
  var url = document.getElementById("websiteUrl").textContent;
  navigator.clipboard.writeText(url).then(function() {
    alert("URLがコピーされました: " + url);
  }, function(err) {
    console.error('コピーに失敗しました:', err);
  });
}
</script>

</body>
</html>
