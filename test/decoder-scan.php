<?php
require_once '../src/EmvMerchantDecoder.php';
$str = $_POST['qrcode'];
if ( ! empty($str))
{
    $emv = new \EMVQR\EmvMerchantDecoder($str);
    $json = json_encode($emv, JSON_PRETTY_PRINT);
}
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="emvco qr code">
    <meta name="author" content="Ratinan Lee">
    <title>TEST DECODER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        #qr-reader button, #qr-reader__dashboard_section_swaplink, #qr-reader__filescan_input, #qr-reader__camera_selection {
            color: #6c757d;
            border: 1px solid #6c757d;
            display: inline-block;
            font-weight: 400;
            line-height: 1.5;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            background-color: transparent;
            padding: .375rem .75rem;
            font-size: 1rem;
            border-radius: .25rem;
            margin-bottom: 3px;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
            text-decoration: none !important;
        }

        #qr-reader__dashboard_section_fsr span {
            display: none;
        }
    </style>
  </head>
  <body>
      <?php
      include "_header.php";
      ?>
    <div class="container mt-5">
      <div class="row">
        <div class="col col-lg-8">
          <h1>
            Test QR Code Decoder
            <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" style="height:1em;width:1em;">
              <path fill="currentColor"
                    d="M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"
                    class=""></path>
            </svg>
          </h1>
          <hr>
          <div id="qr-reader" style="width:100%"></div>
          <div id="qr-reader-results"></div>
          <form method="POST" action="decoder.php" id="qrform">
            <input type="hidden" name="qrcode" id="qrcode"/>
          </form>
          <hr>
          <p>&copy; Ratinan Lee - 2021 - QR Scanner from <a href="https://github.com/mebjas/html5-qrcode"
                                                            target="_blank">mebjas/html5-qrcode</a> repository.</p>
        </div>
      </div>
    </div>
  </body>
  <script src="html5-qrcode.min.js"></script>
  <script>
      function docReady(fn) {
          // see if DOM is already available
          if (document.readyState === "complete"
              || document.readyState === "interactive") {
              // call on next available tick
              setTimeout(fn, 1);
          } else {
              document.addEventListener("DOMContentLoaded", fn);
          }
      }

      docReady(function () {
          var resultContainer = document.getElementById('qr-reader-results');
          var lastResult, countResults = 0;

          function onScanSuccess(decodedText, decodedResult) {
              if (decodedText !== lastResult) {
                  ++countResults;
                  lastResult = decodedText;
                  document.getElementById('qrcode').value = decodedText;
                  document.getElementById('qrform').submit();
                  // console.log(`Scan result ${decodedText}`, decodedResult);
              }
          }

          var html5QrcodeScanner = new Html5QrcodeScanner(
              "qr-reader", {fps: 10, qrbox: 250});
          html5QrcodeScanner.render(onScanSuccess);
      });
  </script>
</html>