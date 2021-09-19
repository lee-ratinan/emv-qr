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
  </head>
  <body>
      <?php
      include "_header.php";
      ?>
    <div class="container mt-5">
      <div class="row">
        <div class="col col-lg-8">
          <h1>Test QR Code Decoder</h1>
          <hr>
          <form method="POST">
            <input class="form-control mb-3" type="text" name="qrcode" value="<?= @$str ?>" placeholder="QR Code"
                   required/>
            <div class="text-end">
              <input class="btn btn-success mb-3" type="submit" value="Decode"/>
            </div>
          </form>
            <?= (isset($json) ? '<h2>Result</h2><pre>' . $json . '</pre>' : '') ?>
          <hr>
          <p>&copy; Ratinan Lee - 2021</p>
        </div>
      </div>
    </div>
  </body>
</html>