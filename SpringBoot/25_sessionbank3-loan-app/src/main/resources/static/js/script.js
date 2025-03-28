document.getElementById('screenshotButton').addEventListener('click', function() {
    let hostName = document.getElementById('hostName');
    hostName.style.display = 'block';

    html2canvas(document.getElementById('captureArea')).then(function(canvas) {
        let imageData = canvas.toDataURL("image/png");
        let downloadLink = document.getElementById('downloadLink');
        downloadLink.href = imageData;
        downloadLink.click();
    });
});
