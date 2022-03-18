# emv-qr

[![GitHub last commit](https://img.shields.io/github/last-commit/lee-ratinan/emv-qr)](https://github.com/lee-ratinan/emv-qr/commits/main)
[![GitHub](https://img.shields.io/github/license/lee-ratinan/emv-qr)](https://github.com/lee-ratinan/emv-qr/blob/main/LICENSE)
[![GitHub all releases](https://img.shields.io/github/downloads/lee-ratinan/emv-qr/total)](https://github.com/lee-ratinan/emv-qr/releases)
[![GitHub issues](https://img.shields.io/github/issues/lee-ratinan/emv-qr)](https://github.com/lee-ratinan/emv-qr/issues)

This repo is for reading and generating EMV QR Code for various countries, currently supports:

  * Singapore
  * Thailand

Possible expansion: Indonesia, Malaysia, Hong Kong, India.

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

Table 1: Data Objects Under the Root of a QR Code

| ID | Length  | Format | Description              | Presence | Notes |
|----|---------|--------|--------------------------|----------|-------|
| 00 | 02      | N      | Payload format indicator | M | A fixed value of '01' (or '00', as used by Singapore SGQR) |
| 01 | 02      | N      | Point of initiation      | O | '11' for static QR code or '12' for dynamic QR code |
| 02-51 | <=99 | ANS    | Merchant account information | M | At least 1 account for the payment to be made to, refer to Table 2 below for the allocation of the IDs, and Table 5 for the data objects under each account |
| 52 | 04      | N      | Merchant category code   | M |  ISO 18245 code for retail financial services, use '0000' if unknown or not required |
| 53 | 03      | N      | Transaction currency     | M | ISO 4217 numeric currency code |
| 54 | <=13    | ANS    | Transaction amount       | C | The amount to be transferred, required if the QR type is dynamic (the customers do not enter the amount themselves) |
| 55 | 02      | N      | Tip or Convenience Indicator        | O |  |
| 56 | <=13    | ANS    | Value of Convenience Fee Fixed      | C |  |
| 57 | <=5     | ANS    | Value of Convenience Fee Percentage | C |  |
| 58 | 02      | ANS    | Country code             | M | ISO 3166-1 alpha-2 country code |
| 59 | <=25    | ANS    | Merchant name            | M |                                 |
| 60 | <=15    | ANS    | Merchant city            | M |                                 |
| 61 | <=10    | ANS    | Merchant postal code     | O |                                 |
| 62 | <=99    | S      | Additional data field template | O | Refer to Table 3 below          |
| 64 | <=99    | S      | Merchant information template language | O | Refer to Table 4 below / * Not supported by this library |
| 65-79 | <=99 | S      | Reserved for future use  | O | * Not supported by this library |
| 80-99 | <=99 | S      | Unreserved templates     | O | * Not supported by this library |
| 63 | 04      | ANS    | CRC                      | M | Security code                   |

The IDs 02-51 are for merchant account information, where the IDs 26-51 are open for private use. The length for each of the account is up to 99.

Table 2: Allocation of Merchant Account Information (ID 26-51)

| ID    | Length | Format | Description           | Presence |
|-------|--------|--------|-----------------------|----------|
| 02-03 | <=99   | ANS    | Reserved for Visa     | O        |
| 04-05 | <=99   | ANS    | Reserved for MasterCard | O      |
| 06-08 | <=99   | ANS    | Reserved by EMVCo     | O        |
| 09-10 | <=99   | ANS    | Reserved for Discover | O        |
| 11-12 | <=99   | ANS    | Reserved for AMEX     | O        |
| 13-14 | <=99   | ANS    | Reserved for JCB      | O        |
| 15-16 | <=99   | ANS    | Reserved for UnionPay | O        |
| 17-25 | <=99   | ANS    | Reserved by EMVCo     | O        |
| 26-51 | <=99   | ANS    | Open for private use  | O        |
| 26    | <=99   | ANS    | HK: Reserved for Faster Payment System for use in HK. | O |
| 26    | <=99   | ANS    | SG: Preferred ID for PayNow | O |
| 29    | <=99   | ANS    | TH: Generally found PromptPay account information under this ID | O |
| 51    | <=99   | ANS    | SG: Reserved for SGQR merchant information | O |

Table 3: Data Objects for Additional Data Field Template (ID 62)

| ID | Length | Format | Description            | Presence |
|----|--------|--------|------------------------|----------|
| 01 | <=25   | ANS    | Bill number            | O        |
| 02 | <=25   | ANS    | Mobile number          | O        |
| 03 | <=25   | ANS    | Store label            | O        |
| 04 | <=25   | ANS    | Loyalty number         | O        |
| 05 | <=25   | ANS    | Reference label        | O        |
| 06 | <=25   | ANS    | Customer label         | O        |
| 07 | <=25   | ANS    | Terminal label         | O        |
| 08 | <=25   | ANS    | Purpose of transaction | O        |
| 09 | <=3    | ANS    | Additional customer data request | O |
| 10 | <=20   | ANS    | Merchant tax ID        | O        |
| 11 | 3      | ANS    | Merchant channel       | O        |
| 12-49 | any | S      | Reserved for future use (Not supported by this library) | O |
| 50-99 | any | S      | Payment system specific templates (Not supported by this library) | O |

Table 4: Data Objects for Merchant Information in Other Language (ID 64) (Not supported by this library)

| ID | Length | Format | Description              | Presence |
|----|--------|--------|--------------------------|----------|
| 00 | 2      | ANS    | Language Preference (2-char alphabetic code as defined in ISO 639) | M        |
| 01 | <=25   | S      | Merchant Name            | M        |
| 02 | <=15   | S      | Merchant City            | O        |
| 03-99 | any | S      | Reserved for future use  | O        |

Table 5: Data Objects for Each Merchant Account

| ID | Length | Format | Description                  | Presence |
|----|--------|--------|------------------------------|----------|
| 00 | <=32   | ANS    | Globally Unique Identifier * | M        |
| 01-99 | any | S      | Payment network specific     | O        |

* A globally unique identifier can be an application identifier (AID), a UUID without hyphen separators, or a reversed domain name. 

## How to use

### 1 `EmvMerchant`

#### 1.1 Class Public Properties

Table 6: Class Public Properties

| #  | Property                   | Type   | Notes |
|----|----------------------------|--------|-------|
| 1  | mode                       | string | Always 'DECODE' or 'GENERATE' |
| 2  | qr_string                  | string |  |
| 3  | payload_format_indicator   | string | A fixed value of '01' |
| 4  | point_of_initiation        | string | Always '11' or '12' |
| 5  | accounts                   | array  |  |
| 6  | merchant_category_code     | string | ISO 18245, 4-character in length |
| 7  | transaction_currency       | string | ISO 4217 alphabetic code (3-character) |
| 8  | transaction_amount         | float  | optional |
| 9  | tip_or_convenience_fee_indicator | string | optional |
| 10 | convenience_fee_fixed      | float  | optional |
| 11 | convenience_fee_percentage | float  | optional  |
| 12 | country_code               | string | ISO 3166-1 alpha-2 code |
| 13 | merchant_name              | string |  |
| 14 | merchant_city              | string |  |
| 15 | merchant_postal_code       | string |  |
| 16 | additional_fields          | array  |  |
| 17 | crc                        | string | A CRC string, 4-character in length |
| 18 | errors                     | array  |  |
| 19 | warnings                   | array  |  |

### 2 `EmvMerchantDecoder`

#### 2.1 Description

Receive the string read from the QR code and structure it in the object format as seen in Table 6.

#### 2.2 How to Use

* Create an object of class `EmvMerchantDecoder` with a parameter `$string` which is the string read from the QR code (optional).
* If the `$string` is not passed into the constructor in the previous step, call the `decode($string)` function to pass the parameter instead.
* The public properties of class `EmvMerchantDecoder` will be filled with the data read from the QR code. If there are any problems in the `$string` input, the arrays `errors` and/or `warnings` will be filled.

#### 2.3 Example

```PHP
$string = '00020101021126490009SG.PAYNOW010120210202012345X0301104082021123151820007SG.SGQR0113202012345X123020701.000103068286710402010503123060400000708201912315204000053037025802SG5911RATINAN LEE6009SINGAPORE610682876162140110987654321X630429FD';
$emv    = new \EMVQR\EmvMerchantDecoder($string);
$json   = json_encode($emv, JSON_PRETTY_PRINT);
```

Result:

```JSON
{
    "mode": "DECODE",
    "qr_string": "00020101021126490009SG.PAYNOW010120210202012345X0301104082021123151820007SG.SGQR0113202012345X123020701.000103068286710402010503123060400000708201912315204000053037025802SG5911RATINAN LEE6009SINGAPORE610682876162140110987654321X630429FD",
    "payload_format_indicator": "01",
    "point_of_initiation": "STATIC",
    "accounts": {
        "SG.PAYNOW": {
            "original_id": 26,
            "reverse_domain": "SG.PAYNOW",
            "proxy_type": "UEN",
            "proxy_value": "202012345X",
            "amount_editable": true
        },
        "SG.SGQR": {
            "original_id": 51,
            "reverse_domain": "SG.SGQR",
            "sgqr_id_number": "202012345X123",
            "version": "01.0001",
            "postal_code": "828671",
            "level": "01",
            "unit_number": "123",
            "miscellaneous": "0000",
            "new_version_date": "20191231"
        }
    },
    "merchant_category_code": {
        "code": "0000",
        "value": "Generic"
    },
    "transaction_currency": "SGD",
    "transaction_amount": null,
    "tip_or_convenience_fee_indicator": null,
    "convenience_fee_fixed": null,
    "convenience_fee_percentage": null,
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

### 3 `EmvMerchantGenerator`

#### 3.1 Description

Under construction.

#### 3.2 Functions and Parameters

Under construction.

#### 3.3 Return Values

Under construction.

#### 3.4 Example

Under construction.

### 4 Account Templates

#### 4.1 Singapore

##### PayNow

```JSON
{
    "SG.PAYNOW": {
        "original_id": 26,
        "reverse_domain": "SG.PAYNOW",
        "proxy_type": "UEN",
        "proxy_value": "202012345X",
        "amount_editable": true,
        "expiry_date": "2099-12-31"
    }
}
```
##### FavePay

```JSON
{
    "FavePay": {
        "original_id": 27,
        "channel": "FavePay",
        "reverse_domain": "COM.MYFAVE",
        "url": "https://myfave.com/qr/xxxxxx"
    }
}
```

##### SGQR

```JSON
{
    "SG.SGQR": {
        "original_id": 51,
        "reverse_domain": "SG.SGQR",
        "sgqr_id_number": "202012345X123",
        "version": "01.0001",
        "postal_code": "828671",
        "level": "01",
        "unit_number": "123",
        "miscellaneous": "0000",
        "new_version_date": "20191231"
    }
}
```

#### 4.2 Thailand

##### PromptPay

```JSON
{
    "TH.PROMPTPAY": {
        "original_id": 29,
        "channel_name": "TH.PROMPTPAY",
        "guid": "A000000677010111",
        "proxy_type": "MOBILE",
        "proxy_value": "0066899999999",
        "mobile_number": "+66899999999"
    }
}
```
#### 4.3 Indonesia

##### QRIS

```JSON
{
    "ID.CO.QRIS.WWW": {
        "original_id": 51,
        "reverse_domain": "ID.CO.QRIS.WWW",
        "nmid": "ID1234567890123",
        "03": "UMI"
    }
}
```

#### 4.4 Malaysia

The QR Code for Malaysia, DuitNow, has not yet been implemented.

#### 4.5 Hong Kong

The QR Code for Hong Kong has not yet been implemented.
