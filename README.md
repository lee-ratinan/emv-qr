# emvQr
This repo is for reading and generating EMV QR Code for Singapore, Malaysia, Indonesia, and Thailand.

## Version

| Date       | Version | Details                          |
|------------|---------|----------------------------------|
| 2021-09-05 | 1.0     | Launch of the code for decoder.  |
|            | 1.1     | Planned launch of the generator. |

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