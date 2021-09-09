# emvQr
This repo is for reading and generating EMV QR Code for Singapore, Malaysia, Indonesia, and Thailand.

## EMVCo QR Code Specification for Merchant-Presented QR Code

Table 1:

| ID | Length  | Format | Name | Presence | Notes |
|----|---------|--------|------|----------|-------|
| 00 | 02      | N      | Payload format indicator | M | A fixed value of '01' |
| 01 | 02      | N      | Point of initiation      | M | '11' for static QR code or '12' for dynamic QR code |
| 02-51 | <=99 | ANS    | Merchant account information | M | At least 1 account for the payment to be made to, refer to Table 2 below |
| 52 | 04      | N      | Merchant category code   | M |  ISO 18245 code for retail financial services, use '0000' if unknown or not required |
| 53 | 03      | N      | Transaction currency     | N | ISO 4217 numeric currency code |
| 54 | <=13    | ANS    | Transaction amount       | C | The amount to be transferred, required if the QR type is dynamic |
| 58 | 02      | ANS    | Country code             | M | ISO 3166-1 alpha-2 country code |
| 59 | <=25    | ANS    | Merchant name            | M |     |
| 60 | <=15    | ANS    | Merchant city            | M |     |
| 61 | <=10    | ANS    | Merchant postal code     | O |     |
| 62 | <=99    | ANS    | Additional data template | O | Refer to Table 3 below |
| 63 | 04      | N      | CRC                      | M | Security code |
| 64 | <=99    | S      | Merchant information template language | O |   |

The IDs 02-51 are for merchant account information, where the IDs 26-51 are open for private use. The length for each of the account is up to 99.

Table 2:

| ID    | Length | Format | Description           |
|-------|--------|--------|-----------------------|
| 02-03 | <=99   | ANS    | Reserved for Visa     |
| 04-05 | <=99   | ANS    | Reserved for MasterCard |
| 06-08 | <=99   | ANS    | Reserved by EMVCo     |
| 09-10 | <=99   | ANS    | Reserved for Discover |
| 11-12 | <=99   | ANS    | Reserved for AMEX     |
| 13-14 | <=99   | ANS    | Reserved for JCB      |
| 15-16 | <=99   | ANS    | Reserved for UnionPay |
| 17-25 | <=99   | ANS    | Reserved by EMVCo     |
| 26-51 | <=99   | ANS    | Open for private use  |
| 26    | <=99   | ANS    | HK: Reserved for Faster Payment System for use in HK. |
| 26    | <=99   | ANS    | SG: Preferred ID for PayNow |
| 29    | <=99   | ANS    | TH: Generally found PromptPay account information under this ID |
| 51    | <=99   | ANS    | SG: Reserved for SGQR merchant information |

Table 3:

| ID | Length | Format | Description            |
|----|--------|--------|------------------------|
| 01 | <=25   | ANS    | Bill number            |
| 02 | <=25   | ANS    | Mobile number          |
| 03 | <=25   | ANS    | Store label            |
| 04 | <=25   | ANS    | Loyalty number         |
| 05 | <=25   | ANS    | Reference label        |
| 06 | <=25   | ANS    | Customer label         |
| 07 | <=25   | ANS    | Terminal label         |
| 08 | <=25   | ANS    | Purpose of transaction |
| 09 | <=25   | ANS    | Additional customer data request |
| 10 | <=25   | ANS    | Merchant tax ID        |
| 11 | <=25   | ANS    | Merchant channel       |

## How to use

### 1 EmvMerchant

#### 1.1 Class Public Properties

| Property | Type | Notes |
|----------|------|-------|
| mode     | string | Always 'DECODE' or 'GENERATE' |
| qr_string | string |  |
| payload_format_indicator | string | A fixed value of '01' |
| point_of_initiation | string | Always '11' or '12' |
| accounts | array |  |
| merchant_category_code | string | ISO 18245, 4-character in length |
| transaction_currency | string | ISO 4217 alphabetic code (2-character) |
| transaction_amount | float | optional | 
| country_code | string | ISO 3166-1 alpha-2 code |
| merchant_name | string |  |
| merchant_city | string |  |
| merchant_postal_code | string |  |
| additional_fields | array |  |
| crc | string | A CRC string, 4-character in length |
| errors | array |  |
| warnings | array |  |

### 2 EmvMerchantDecoder

#### 2.1 Decode

Read the QR code from QR reader and pass the string to `decode()` to get the breakdown data in the QR in an object format.

##### 2.1.1 Description

`decode(string $string): EmvMerchantDecoder`

##### 2.1.2 Parameters

`$string` The string read from the QR code.

##### 2.1.3 Return Values

An object of type `EmvMerchantDecoder` containing all values read of the QR code along with the arrays of warning and error messages.

##### 2.1.4 Example

```PHP
$str = '...';
$emv = new \EMVQR\EmvMerchantDecoder();
$result = $emv->decode($str);
echo json_encode($result);
```

Result:

```JSON
{
  'mode': 'DECODE',
  'qr_string': '',
  'payload_format_indicator': '01',
  ...
}
```

### 2 `EmvMerchantGenerator()`

Under Construction

```PHP
$emv = new \EMVQR\EmvMerchantGenerator();
$emv->add();
$string = $emv->create_code_sg();
```

#### `create_code_sg(): string`
