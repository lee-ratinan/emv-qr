# emvQr

[![GitHub last commit](https://img.shields.io/github/last-commit/lee-ratinan/emvQr)](https://github.com/lee-ratinan/emvQr/commits/main)
[![GitHub](https://img.shields.io/github/license/lee-ratinan/emvQr)](https://github.com/lee-ratinan/emvQr/blob/main/LICENSE)
[![GitHub all releases](https://img.shields.io/github/downloads/lee-ratinan/emvQr/total)](https://github.com/lee-ratinan/emvQr/releases)
[![GitHub issues](https://img.shields.io/github/issues/lee-ratinan/emvQr)](https://github.com/lee-ratinan/emvQr/issues)

This repo is for reading and generating EMV QR Code for various countries, currently supports:

  * Singapore
  * Thailand
  * (Indonesia)
  * (Malaysia)

## EMVCo QR Code Specification for Merchant-Presented QR Code

The specification for EMVCo QR Code defined as the blocks of string: [ID, Length, Value]. The ID and length consist of 2-digit number ranging from '00' to '99', and the value is the value of the item identified by the ID.
For example, '000201' is the block of string for ID '00' (payload format indicator) with the value's length of '02' (2-character long), hence the value is '01', which is the 2-character behind the length.
The tables below describe the IDs, lengths, and their descriptions.

Notes:

  * Formats:
    * N = numeric
    * ANS = alphanumeric and special character string (ASCII 32-126)
    * S = string of any unicode characters
  * Presence
    * M = mandatory
    * O = optional
    * C = conditional

Table 1:

| ID | Length  | Format | Name | Presence | Notes |
|----|---------|--------|------|----------|-------|
| 00 | 02      | N      | Payload format indicator | M | A fixed value of '01' |
| 01 | 02      | N      | Point of initiation      | M | '11' for static QR code or '12' for dynamic QR code |
| 02-51 | <=99 | ANS    | Merchant account information | M | At least 1 account for the payment to be made to, refer to Table 2 below |
| 52 | 04      | N      | Merchant category code   | M |  ISO 18245 code for retail financial services, use '0000' if unknown or not required |
| 53 | 03      | N      | Transaction currency     | N | ISO 4217 numeric currency code |
| 54 | <=13    | ANS    | Transaction amount       | C | The amount to be transferred, required if the QR type is dynamic (the customers do not enter the amount themselves) |
| 55 | 02      | N      | Tip or Convenience Indicator        | O |  |
| 56 | <=13    | ANS    | Value of Convenience Fee Fixed      | C |  |
| 57 | <=5     | ANS    | Value of Convenience Fee Percentage | C |  |
| 58 | 02      | ANS    | Country code             | M | ISO 3166-1 alpha-2 country code |
| 59 | <=25    | ANS    | Merchant name            | M |                                 |
| 60 | <=15    | ANS    | Merchant city            | M |                                 |
| 61 | <=10    | ANS    | Merchant postal code     | O |                                 |
| 62 | <=99    | S      | Additional data template | O | Refer to Table 3 below          |
| 64 | <=99    | S      | Merchant information template language | O | * Not supported by this library |
| 65-79 | <=99 | S      | Reserved for future use  | O | * Not supported by this library |
| 80-99 | <=99 | S      | Unreserved templates     | O | * Not supported by this library |
| 63 | 04      | ANS    | CRC                      | M | Security code                   |

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
| 09 | <=3    | ANS    | Additional customer data request |
| 10 | <=20   | ANS    | Merchant tax ID        |
| 11 | 3      | ANS    | Merchant channel       |
| 12-49 | any | S      | Reserved for future use (Not supported by this library) |
| 50-99 | any | S      | Payment system specific templates (Not supported by this library) |

## How to use

### 1 EmvMerchant

#### 1.1 Class Public Properties

Table 4:

| Property                   | Type   | Notes |
|----------------------------|--------|-------|
| mode                       | string | Always 'DECODE' or 'GENERATE' |
| qr_string                  | string |  |
| payload_format_indicator   | string | A fixed value of '01' |
| point_of_initiation        | string | Always '11' or '12' |
| accounts                   | array  |  |
| merchant_category_code     | string | ISO 18245, 4-character in length |
| transaction_currency       | string | ISO 4217 alphabetic code (3-character) |
| transaction_amount         | float  | optional |
| tip_or_convenience_fee_indicator | string | optional |
| convenience_fee_fixed      | float  | optional |
| convenience_fee_percentage | float  | optional  |
| country_code               | string | ISO 3166-1 alpha-2 code |
| merchant_name              | string |  |
| merchant_city              | string |  |
| merchant_postal_code       | string |  |
| additional_fields          | array  |  |
| crc                        | string | A CRC string, 4-character in length |
| errors                     | array  |  |
| warnings                   | array  |  |

### 2 EmvMerchantDecoder

#### 2.1 Decode

Read the QR code from QR reader and pass the string to `decode()` to get the breakdown data in the QR in an object format.

##### 2.1.1 Description

`decode(string $string): EmvMerchantDecoder`

##### 2.1.2 Parameters

`$string` The string read from the QR code.

##### 2.1.3 Return Values

An object of type `EmvMerchantDecoder` containing all values read from the QR code along with the arrays of warning and error messages.

##### 2.1.4 Example

```PHP
$str = '00020101021126490009SG.PAYNOW010120210202012345X0301104082021123151820007SG.SGQR0113202012345X123020701.000103068286710402010503123060400000708201912315204000053037025802SG5911RATINAN LEE6009SINGAPORE610682876162140110987654321X630429FD';
$emv = new \EMVQR\EmvMerchantDecoder();
$result = $emv->decode($str);
echo json_encode($result);
```

Result:

```JSON
{
    "mode": "DECODE",
    "qr_string": "00020101021126490009SG.PAYNOW010120210202012345X0301104082021123151820007SG.SGQR0113202012345X123020701.000103068286710402010503123060400000708201912315204000053037025802SG5911RATINAN LEE6009SINGAPORE610682876162140110987654321X630429FD",
    "payload_format_indicator": "01",
    "point_of_initiation": "STATIC",
    "accounts": [
        {
            "original_id": 26,
            "channel": "SG.PAYNOW",
            "proxy_type": "UEN",
            "proxy_value": "202012345X",
            "amount_editable": true,
            "expiry_date": "20211231"
        },
        {
            "original_id": 51,
            "channel": "SG.SGQR",
            "sgqr_id_number": "202012345X123",
            "version": "01.0001",
            "postal_code": "828671",
            "level": "01",
            "unit_number": "123",
            "miscellaneous": "0000",
            "new_version_date": "20191231"
        }
    ],
    "merchant_category_code": {
        "code": "0000",
        "value": "Generic"
    },
    "transaction_currency": "SGD",
    "transaction_amount": null,
    "country_code": "SG",
    "merchant_name": "RATINAN LEE",
    "merchant_city": "SINGAPORE",
    "merchant_postal_code": "828761",
    "additional_fields": {
        "bill_number": "987654321X"
    },
    "crc": "29FD",
    "errors": [],
    "warnings": []
}
```

### 2 `EmvMerchantGenerator()`

In Progress

### 3 Account Information

#### 3.1 Singapore

##### 3.1.1 PayNow

##### 3.1.2 SGQR

#### 3.2 Thailand (ประเทศไทย)

##### 3.2.1 PromptPay (พร้อมเพย์)

#### 3.3 Indonesia

##### 3.3.1 QRIS

#### 3.4 Malaysia

##### 3.4.1 DuitNow
