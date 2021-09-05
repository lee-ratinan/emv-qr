# emvQr
This repo is for reading and generating EMV QR Code for Singapore, Malaysia, Indonesia, and Thailand.

## How to use

### Decoder

Read the QR code from QR reader and pass the string to `decode()` to get the breakdown data in the QR in an object format. The variable `$result` in the code below contains the `EmvMerchant` object with all details in the QR string `$str`.

```PHP
$str = '...';
$emv = new \EMVQR\EmvMerchant();
$result = $emv->decode($str);
```

### Generator

Under Construction