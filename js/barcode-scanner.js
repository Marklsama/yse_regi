document.addEventListener('DOMContentLoaded', function () {
  const scanBtn = document.getElementById('scan-barcode-btn');
  const scannerDiv = document.getElementById('barcode-scanner');
  const closeBtn = document.getElementById('close-scanner-btn');
  let html5QrcodeScanner;

  if (scanBtn && scannerDiv && closeBtn) {
    scanBtn.addEventListener('click', function () {
      scannerDiv.style.display = 'block';
      if (!html5QrcodeScanner) {
        html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start(
          { facingMode: "environment" },
          {
            fps: 10,
            qrbox: 250
          },
          (decodedText, decodedResult) => {
            document.getElementById('barcode-input').value = decodedText;
            // Хэрвээ бусад талбар автоматаар бөглөгдсөн бол:
            // document.querySelector('.barcode-form').submit();
            html5QrcodeScanner.stop();
            scannerDiv.style.display = 'none';
            html5QrcodeScanner.clear();
            html5QrcodeScanner = null;
          },
          (errorMessage) => {
            // ignore errors
          }
        );
      }
    });

    closeBtn.addEventListener('click', function () {
      if (html5QrcodeScanner) {
        html5QrcodeScanner.stop().then(() => {
          scannerDiv.style.display = 'none';
          html5QrcodeScanner.clear();
          html5QrcodeScanner = null;
        });
      } else {
        scannerDiv.style.display = 'none';
      }
    });
  }
});