<?php
require_once '../src/EmvMerchant.php';
$str = $_POST['qrcode'];
if ( ! empty($str))
{
	$emv = new \EMVQR\EmvMerchant();
	$result = $emv->decode($str);
	$json = json_encode($result, JSON_PRETTY_PRINT);
}
?>
<html>
  <head>
    <title>TEST</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col col-md-8 col-lg-6">
          <h1>Test QR Code Decoder</h1>
          <hr>
          <form method="POST">
            <input class="form-control mb-3" type="text" name="qrcode"/>
            <input class="btn btn-success mb-3" type="submit" value="Decode"/>
          </form>
          <?= (isset($json) ? '<h2>Result</h2><pre>' . $json . '</pre>' : '') ?>
        </div>
      </div>
    </div>
  </body>
</html>