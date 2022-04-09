<?php

namespace EMVQR;

require_once 'EmvPayLoadFormatIndicator.php';
require_once 'EmvPointOfInitiation.php';
require_once 'EmvMerchantCategoryCode.php';
require_once 'EmvTransactionCurrency.php';

/**
 * Class EmvMerchant
 * @package EMVQR
 */
class EmvMerchant {

    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const ID_POINT_OF_INITIATION = '01';
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const ID_TRANSACTION_CURRENCY = '53';
    const ID_TRANSACTION_AMOUNT = '54';
    const ID_TIP_OR_CONVENIENCE_FEE_INDICATOR = '55';
    const ID_VALUE_OF_CONVENIENCE_FEE_FIXED = '56';
    const ID_VALUE_OF_CONVENIENCE_FEE_PERCENTAGE = '57';
    const ID_COUNTRY_CODE = '58';
    const ID_MERCHANT_NAME = '59';
    const ID_MERCHANT_CITY = '60';
    const ID_POSTAL_CODE = '61';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ID_CRC = '63';
    const ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE = '64';

    /**
     * ID 00
     * @var
     */
    public $payload_format_indicator;

    /**
     * ID 01
     * @var
     */
    public $point_of_initiation_method;

    /**
     * ID 52
     * @var
     */
    public $merchant_category_code;

    /**
     * ID 53
     * @var
     */
    public $transaction_currency;

    /**
     * ID 54
     * @var
     */
    private $transaction_amount;

    /**
     * ID 55
     * @var
     */
    private $tip_of_convenience_fee_indicator;

    /**
     * ID 56
     * @var
     */
    private $value_of_convenience_fee_fixed;

    /**
     * ID 57
     * @var
     */
    private $value_of_convenience_fee_percentage;

    /**
     * ID 58
     * @var
     */
    private $country_code;

    /**
     * ID 59
     * @var
     */
    private $merchant_name;

    /**
     * ID 60
     * @var
     */
    private $merchant_city;

    /**
     * ID 61
     * @var
     */
    private $postal_code;

    /**
     * ID 62
     * @var
     */
    private $additional_data_field_template;

    /**
     * ID 64
     * @var
     */
    private $merchant_information_language_template;

    /**
     * ID 63
     * @var
     */
    private $crc;

    /**
     * @param $qr_string
     */
    public function read($qr_string)
    {
        // SOME CLEANING
        $string = str_replace("\u{c2a0}", ' ', $qr_string);
        // LOOP
        while ( ! empty($string))
        {
            $strId = substr($string, 0, 2);
            $intId = intval($strId);
            $intLength = intval(substr($string, 2, 2));
            $strValue = substr($string, 4, $intLength);
            switch ($strId)
            {
                case self::ID_PAYLOAD_FORMAT_INDICATOR:
                    $this->payload_format_indicator = new EmvPayLoadFormatIndicator($strValue);
                    break;
                case self::ID_POINT_OF_INITIATION:
                    $this->point_of_initiation_method = new EmvPointOfInitiation($strValue);
                    break;
                case self::ID_MERCHANT_CATEGORY_CODE:
                    $this->merchant_category_code = new EmvMerchantCategoryCode($strValue);
                    break;
                case self::ID_TRANSACTION_CURRENCY:
                    $this->transaction_currency = new EmvTransactionCurrency($strValue);
                    break;
//                case self::ID_TRANSACTION_AMOUNT:
////                    $this->process_amount($strValue);
//                    break;
//                case self::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR:
////                    $this->process_fee_indicator($strValue);
//                    break;
//                case self::ID_VALUE_OF_CONVENIENCE_FEE_FIXED:
////                    $this->process_fee_value_fixed($strValue);
//                    break;
//                case self::ID_VALUE_OF_CONVENIENCE_FEE_PERCENTAGE:
////                    $this->process_fee_value_percentage($strValue);
//                    break;
//                case self::ID_COUNTRY_CODE:
////                    $this->process_country_code($strValue);
//                    break;
//                case self::ID_MERCHANT_NAME:
////                    $this->process_status[self::MERCHANT_NAME_KEY] = self::PROCESS_STATUS_SUCCESS;
////                    $this->merchant_name = [
////                        self::LABEL_ACCOUNT_ID          => self::ID_MERCHANT_NAME,
////                        self::LABEL_ACCOUNT_KEY         => self::MERCHANT_NAME_KEY,
////                        self::LABEL_ACCOUNT_VALUE       => $this->validate_ans_charset($strValue, self::MODE_SANITIZER),
////                        self::LABEL_ACCOUNT_DESCRIPTION => self::EMPTY_STRING
////                    ];
//                    break;
//                case self::ID_MERCHANT_CITY:
////                    $this->process_status[self::MERCHANT_CITY_KEY] = self::PROCESS_STATUS_SUCCESS;
////                    $this->merchant_city = [
////                        self::LABEL_ACCOUNT_ID          => self::ID_MERCHANT_CITY,
////                        self::LABEL_ACCOUNT_KEY         => self::MERCHANT_CITY_KEY,
////                        self::LABEL_ACCOUNT_VALUE       => $this->validate_ans_charset($strValue, self::MODE_SANITIZER),
////                        self::LABEL_ACCOUNT_DESCRIPTION => self::EMPTY_STRING
////                    ];
//                    break;
//                case self::ID_MERCHANT_POSTAL_CODE:
////                    $this->process_status[self::MERCHANT_POSTAL_CODE_KEY] = self::PROCESS_STATUS_SUCCESS;
////                    $this->merchant_postal_code = [
////                        self::LABEL_ACCOUNT_ID          => self::ID_MERCHANT_POSTAL_CODE,
////                        self::LABEL_ACCOUNT_KEY         => self::MERCHANT_POSTAL_CODE_KEY,
////                        self::LABEL_ACCOUNT_VALUE       => $this->validate_ans_charset($strValue, self::MODE_SANITIZER),
////                        self::LABEL_ACCOUNT_DESCRIPTION => self::EMPTY_STRING
////                    ];
//                    break;
//                case self::ID_ADDITIONAL_DATA_FIELDS:
////                    $this->process_additional_data($strValue);
//                    break;
//                case self::ID_CRC:
////                    $this->process_crc($strValue);
//                    break;
//                case self::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE:
////                    $this->add_message(self::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE, self::MESSAGE_TYPE_WARNING, self::WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED);
//                    break;
//                default:
////                    $this->process_accounts($intId, $strValue);
            }
            $string = substr($string, 4 + $intLength);
        }
    }

    /**
     * WRITE QR CODE
     * @param string $point_of_initiation_method
     * @param string $merchant_category_code
     * @param string $transaction_currency
     */
    public function write($point_of_initiation_method, $merchant_category_code, $transaction_currency)
    {
        $this->payload_format_indicator = (new EmvPayLoadFormatIndicator())->generate();
        $this->point_of_initiation_method = (new EmvPointOfInitiation())->generate($point_of_initiation_method);
        $this->merchant_category_code = (new EmvMerchantCategoryCode())->generate($merchant_category_code);
        $this->transaction_currency = (new EmvTransactionCurrency())->generate($transaction_currency);
    }

}
//
//    /**
//     * MODES
//     */
//    const MODE_GENERATE = 'GENERATE';
//    const MODE_DECODE = 'DECODE';
//
//    /**
//     * PAYLOAD FORMAT INDICATOR (00)
//     */
//    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
//    const PAYLOAD_FORMAT_INDICATOR_VALUE = '01';
//    const PAYLOAD_FORMAT_INDICATOR_VALUE_ALT = '00';
//    const PAYLOAD_FORMAT_INDICATOR_KEY = 'payload_format_indicator';
//
//    /**
//     * POINT OF INITIATION (01)
//     */
//    const ID_POINT_OF_INITIATION = '01';
//    const POINT_OF_INITIATION_STATIC = '11';
//    const POINT_OF_INITIATION_STATIC_VALUE = 'STATIC';
//    const POINT_OF_INITIATION_DYNAMIC = '12';
//    const POINT_OF_INITIATION_DYNAMIC_VALUE = 'DYNAMIC';
//    const POINT_OF_INITIATION_KEY = 'point_of_initiation';
//
//    /**
//     * ACCOUNTS (02-51)
//     */
//    const ID_ACCOUNT_LOWER_BOUNDARY = 2;
//    const ID_ACCOUNT_START_INDEX = 26;
//    const ID_ACCOUNT_UPPER_BOUNDARY = 51;
//    const ID_ORIGINAL_LABEL = 'original_id';
//    const ID_PLAIN_VALUE_LABEL = 'value';
//    const ACCOUNT_KEY = 'accounts';
//
//    /**
//     * RESERVED AREA IDS
//     */
//    protected $reserved_ids = [
//        2 => 'Visa',
//        3 => 'Visa',
//        4 => 'MasterCard',
//        5 => 'MasterCard',
//        6 => 'EMVCo',
//        7 => 'EMVCo',
//        8 => 'EMVCo',
//        9 => 'Discover',
//        10 => 'Discover',
//        11 => 'AMEX',
//        12 => 'AMEX',
//        13 => 'JCB',
//        14 => 'JCB',
//        15 => 'UnionPay',
//        16 => 'UnionPay',
//        17 => 'EMVCo',
//        18 => 'EMVCo',
//        19 => 'EMVCo',
//        20 => 'EMVCo',
//        21 => 'EMVCo',
//        22 => 'EMVCo',
//        23 => 'EMVCo',
//        24 => 'EMVCo',
//        25 => 'EMVCo',
//    ];
//
//    /**
//     * MERCHANT CATEGORY CODE (52)
//     */
//    const ID_MERCHANT_CATEGORY_CODE = '52';
//    const MERCHANT_CATEGORY_CODE_KEY = 'merchant_category_code';
//    const MERCHANT_CATEGORY_CODE_GENERIC = '0000';
//    const MERCHANT_CATEGORY_UNKNOWN = 'UNKNOWN';
//
//    /**
//     * CURRENCY (53)
//     */
//    const ID_TRANSACTION_CURRENCY = '53';
//    const TRANSACTION_CURRENCY_KEY = 'transaction_currency';
//    const CURRENCY_HKD = 'HKD';
//    const CURRENCY_HKD_NUMERIC = '344';
//    const CURRENCY_IDR = 'IDR';
//    const CURRENCY_IDR_NUMERIC = '360';
//    const CURRENCY_INR = 'INR';
//    const CURRENCY_INR_NUMERIC = '356';
//    const CURRENCY_MYR = 'MYR';
//    const CURRENCY_MYR_NUMERIC = '458';
//    const CURRENCY_SGD = 'SGD';
//    const CURRENCY_SGD_NUMERIC = '702';
//    const CURRENCY_THB = 'THB';
//    const CURRENCY_THB_NUMERIC = '764';
//    /**
//     * @var string[] ISO4217
//     */
//    protected $currency_codes = [
//        //self::CURRENCY_HKD_NUMERIC => self::CURRENCY_HKD,
//        //self::CURRENCY_IDR_NUMERIC => self::CURRENCY_IDR,
//        //self::CURRENCY_INR_NUMERIC => self::CURRENCY_INR,
//        //self::CURRENCY_MYR_NUMERIC => self::CURRENCY_MYR,
//        self::CURRENCY_SGD_NUMERIC => self::CURRENCY_SGD,
//        self::CURRENCY_THB_NUMERIC => self::CURRENCY_THB
//    ];
//
//    /**
//     * TRANSACTION AMOUNT (54)
//     */
//    const ID_TRANSACTION_AMOUNT = '54';
//    const TRANSACTION_AMOUNT_KEY = 'transaction_amount';
//
//    /**
//     * TIP OR CONVENIENCE FEE (55-57)
//     */
//    const ID_TIP_OR_CONVENIENCE_FEE_INDICATOR = '55';
//    const TIP_OR_CONVENIENCE_FEE_INDICATOR_KEY = 'tip_or_convenience_fee_indicator';
//    const ID_VALUE_OF_FEE_FIXED = '56';
//    const VALUE_OF_FEE_FIXED_KEY = 'convenience_fee_fixed';
//    const ID_VALUE_OF_FEE_PERCENTAGE = '57';
//    const VALUE_OF_FEE_PERCENTAGE_KEY = 'convenience_fee_percentage';
//    const FEE_INDICATOR_TIP = '01';
//    const FEE_INDICATOR_TIP_VALUE = 'TIP';
//    const FEE_INDICATOR_CONVENIENCE_FEE_FIXED = '02';
//    const FEE_INDICATOR_CONVENIENCE_FEE_FIXED_VALUE = 'CONVENIENCE_FEE_FIXED';
//    const FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE = '03';
//    const FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE_VALUE = 'CONVENIENCE_FEE_PERCENTAGE';
//    protected $tip_or_convenience_fee_indicators = [
//        self::FEE_INDICATOR_TIP => self::FEE_INDICATOR_TIP_VALUE,
//        self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED => self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED_VALUE,
//        self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE => self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE_VALUE
//    ];
//
//    /**
//     * COUNTRY (58)
//     */
//    const ID_COUNTRY_CODE = '58';
//    const COUNTRY_CODE_KEY = 'country_code';
//    const COUNTRY_HK = 'HK';
//    const COUNTRY_HK_NAME = 'HONG KONG';
//    const COUNTRY_ID = 'ID';
//    const COUNTRY_ID_NAME = 'INDONESIA';
//    const COUNTRY_IN = 'IN';
//    const COUNTRY_IN_NAME = 'INDIA';
//    const COUNTRY_MY = 'MY';
//    const COUNTRY_MY_NAME = 'MALAYSIA';
//    const COUNTRY_SG = 'SG';
//    const COUNTRY_SG_NAME = 'SINGAPORE';
//    const COUNTRY_TH = 'TH';
//    const COUNTRY_TH_NAME = 'THAILAND';
//    /**
//     * @var string[] ISO3166
//     */
//    protected $country_codes = [
//        //self::COUNTRY_HK,
//        //self::COUNTRY_ID,
//        //self::COUNTRY_IN,
//        //self::COUNTRY_MY,
//        self::COUNTRY_SG,
//        self::COUNTRY_TH
//    ];
//
//    /**
//     * @var string[] COUNTRY NAMES
//     */
//    protected $country_names = [
//        //self::COUNTRY_HK => self::COUNTRY_HK_NAME,
//        //self::COUNTRY_ID => self::COUNTRY_ID_NAME,
//        //self::COUNTRY_IN => self::COUNTRY_IN_NAME,
//        //self::COUNTRY_MY => self::COUNTRY_MY_NAME,
//        self::COUNTRY_SG => self::COUNTRY_SG_NAME,
//        self::COUNTRY_TH => self::COUNTRY_TH_NAME
//    ];
//
//    /**
//     * MERCHANT NAME (59)
//     */
//    const ID_MERCHANT_NAME = '59';
//    const MERCHANT_NAME_KEY = 'merchant_name';
//
//    /**
//     * CITY (60)
//     */
//    const ID_MERCHANT_CITY = '60';
//    const MERCHANT_CITY_KEY = 'merchant_city';
//    const MERCHANT_CITY_SINGAPORE = 'SINGAPORE';
//    const MERCHANT_CITY_HONG_KONG = 'HONG KONG';
//    // THAILAND
//    const MERCHANT_CITY_BANGKOK = 'BANGKOK';
//    const MERCHANT_CITY_HAT_YAI = 'HAT YAI';
//    const MERCHANT_CITY_NAKHON_RATCHASIMA = 'NAKHON RATCHASIMA';
//    const MERCHANT_CITY_CHIANG_MAI = 'CHIANG MAI';
//    const MERCHANT_CITY_UDON_THANI = 'UDON THANI';
//    const MERCHANT_CITY_PATTAYA = 'PATTAYA';
//    const MERCHANT_CITY_KHON_KAEN = 'KHON KAEN';
//    const MERCHANT_CITY_PHUKET = 'PHUKET';
//    const MERCHANT_CITY_UBON_RATCHATHANI = 'UBON RATCHATHANI';
//    // INDONESIA
//    const MERCHANT_CITY_JAKARTA = 'JAKARTA';
//    const MERCHANT_CITY_SURABAYA = 'SURABAYA';
//    const MERCHANT_CITY_BANDUNG = 'BANDUNG';
//    const MERCHANT_CITY_MEDAN = 'MEDAN';
//    const MERCHANT_CITY_PALEMBANG = 'PALEMBANG';
//    const MERCHANT_CITY_DENPASAR = 'DENPASAR';
//    const MERCHANT_CITY_SEMARANG = 'SEMARANG';
//    // MALAYSIA
//    const MERCHANT_CITY_KUALA_LUMPUR = 'KUALA LUMPUR';
//    const MERCHANT_CITY_GEORGE_TOWN = 'GEORGE TOWN';
//    const MERCHANT_CITY_PENANG = 'PENANG';
//    const MERCHANT_CITY_IPOH = 'IPOH';
//    const MERCHANT_CITY_KUCHING = 'KUCHING';
//    const MERCHANT_CITY_JOHOR_BAHRU = 'JOHOR BAHRU';
//    const MERCHANT_CITY_KOTA_KINABALU = 'KOTA KINABALU';
//    const MERCHANT_CITY_MALACCA = 'MALACCA';
//    const MERCHANT_CITY_MIRI = 'MIRI';
//    const MERCHANT_CITY_ALOR_SETAR = 'ALOR SETAR';
//
//    /**
//     * POSTAL CODE (61)
//     */
//    const ID_MERCHANT_POSTAL_CODE = '61';
//    const MERCHANT_POSTAL_CODE_KEY = 'merchant_postal_code';
//
//    /**
//     * ADDITIONAL DATA (62)
//     */
//    const ID_ADDITIONAL_DATA_FIELDS = '62';
//    const ADDITIONAL_DATA_FIELDS_KEY = 'additional_fields';
//    const ID_ADDITIONAL_DATA_BILL_NUMBER = '01';
//    const ID_ADDITIONAL_DATA_BILL_NUMBER_KEY = 'bill_number';
//    const ID_ADDITIONAL_DATA_MOBILE_NUMBER = '02';
//    const ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY = 'mobile_number';
//    const ID_ADDITIONAL_DATA_STORE_LABEL = '03';
//    const ID_ADDITIONAL_DATA_STORE_LABEL_KEY = 'store_label';
//    const ID_ADDITIONAL_DATA_LOYALTY_NUMBER = '04';
//    const ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY = 'loyalty_number';
//    const ID_ADDITIONAL_DATA_REFERENCE_LABEL = '05';
//    const ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY = 'reference_label';
//    const ID_ADDITIONAL_DATA_CUSTOMER_LABEL = '06';
//    const ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY = 'customer_label';
//    const ID_ADDITIONAL_DATA_TERMINAL_LABEL = '07';
//    const ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY = 'terminal_label';
//    const ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION = '08';
//    const ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY = 'purpose_of_transaction';
//    const ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST = '09';
//    const ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY = 'additional_customer_data_request';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_ID = 'M';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_LABEL = 'MOBILE';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_ID = 'A';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_LABEL = 'ADDRESS';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_ID = 'E';
//    const ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_LABEL = 'EMAIL';
//    const ID_ADDITIONAL_DATA_MERCHANT_TAX_ID = '10';
//    const ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY = 'merchant_tax_id';
//    const ID_ADDITIONAL_DATA_MERCHANT_CHANNEL = '11';
//    const ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY = 'merchant_channel';
//    const MERCHANT_CHANNEL_CHAR_MEDIA_KEY = 'media';
//    const MERCHANT_CHANNEL_CHAR_LOCATION_KEY = 'transaction_location';
//    const MERCHANT_CHANNEL_CHAR_PRESENCE_KEY = 'merchant_presence';
//    protected $merchant_channel_medias = [
//        '0' => 'PRINT - MERCHANT STICKER',
//        '1' => 'PRINT - BILL/INVOICE',
//        '2' => 'PRINT - MAGAZINE/POSTER',
//        '3' => 'PRINT - OTHER',
//        '4' => 'SCREEN/ELECTRONIC - MERCHANT POS/POI',
//        '5' => 'SCREEN/ELECTRONIC - WEBSITE',
//        '6' => 'SCREEN/ELECTRONIC - APP',
//        '7' => 'SCREEN/ELECTRONIC - OTHER',
//    ];
//    protected $merchant_channel_locations = [
//        '0' => 'AT MERCHANT PREMISES/REGISTERED ADDRESS',
//        '1' => 'NOT AT MERCHANT PREMISES/REGISTERED ADDRESS',
//        '2' => 'REMOTE COMMERCE',
//        '3' => 'OTHER',
//    ];
//    protected $merchant_channel_presences = [
//        '0' => 'ATTENDED POI',
//        '1' => 'UNATTENDED',
//        '2' => 'SEMI-ATTENDED (SELF-CHECKOUT)',
//        '3' => 'OTHER',
//    ];
//
//    /**
//     * CRC (63)
//     */
//    const ID_CRC = '63';
//    const CRC_KEY = 'crc';
//    const CRC_LENGTH = '04';
//    const CRC_MARKED = '****';
//
//    /**
//     * MERCHANT INFORMATION - LANGUAGE TEMPLATE (64)
//     */
//    const ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE = '64';
//
//    /**
//     * Integers
//     */
//    const POS_ZERO = 0;
//    const POS_ONE = 1;
//    const POS_TWO = 2;
//    const POS_FOUR = 4;
//    const POS_SIX = 6;
//    const POS_EIGHT = 8;
//    const POS_TEN = 10;
//    const POS_TWELVE = 12;
//    const POS_MINUS_FOUR = -4;
//    const LENGTH_ONE = 1;
//    const LENGTH_TWO = 2;
//    const LENGTH_THREE = 3;
//    const LENGTH_FOUR = 4;
//    const LENGTH_TEN = 10;
//    const LENGTH_TWENTY = 20;
//    const LENGTH_TWENTY_FIVE = 25;
//    const INTEGER_TWO = 2;
//
//    /**
//     * Others
//     */
//    const TIMEZONE_SINGAPORE = 'Asia/Singapore';
//    const FORMAT_DATE = 'Y-m-d';
//    const FORMAT_DATE_READABLE = 'd M Y';
//    const EMPTY_STRING = '';
//    const STRING_DOT = '.';
//    const STRING_COMMA = ',';
//    const STR_CHANNEL = 'channel';
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | SINGAPORE
//       | -------------------------------------------------------------------------------------------------------- */
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | PAYNOW (PREFERABLY 26)
//       | -------------------------------------------------------------------------------------------------------- */
//    const PAYNOW_CHANNEL = 'SG.PAYNOW';
//    const PAYNOW_CHANNEL_NAME = 'PAYNOW';
//    const PAYNOW_ID_CHANNEL = '00';
//    const PAYNOW_ID_PROXY_TYPE = '01';
//    const PAYNOW_ID_PROXY_VALUE = '02';
//    const PAYNOW_ID_AMOUNT_EDITABLE = '03';
//    const PAYNOW_ID_EXPIRY_DATE = '04';
//    const PAYNOW_PROXY_MOBILE = '0';
//    const PAYNOW_PROXY_UEN = '2';
//    const PAYNOW_AMOUNT_EDITABLE_TRUE = '1';
//    const PAYNOW_AMOUNT_EDITABLE_FALSE = '0';
//    const PAYNOW_DEFAULT_EXPIRY_DATE = '20991231';
//    protected $paynow_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'proxy_type',
//        '02' => 'proxy_value',
//        '03' => 'amount_editable',
//        '04' => 'expiry_date',
//        '05' => '05' // unknown key, found in one QR code
//    ];
//    protected $paynow_proxy_type = [
//        '0' => 'MOBILE',
//        '2' => 'UEN'
//    ];
//    protected $paynow_amount_editable = [
//        '1' => 'EDITABLE',
//        '0' => 'NOT-EDITABLE'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | FAVEPAY (ANY)
//       | -------------------------------------------------------------------------------------------------------- */
//    const FAVE_CHANNEL = 'COM.MYFAVE';
//    const FAVE_CHANNEL_NAME = 'FAVEPAY';
//    const FAVE_URL = 'https://myfave.com/qr/';
//    const FAVE_ID_REVERSE_DOMAIN = '00';
//    const FAVE_ID_URL = '01';
//    protected $favepay_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'url'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | ALIPAY (ANY)
//       | -------------------------------------------------------------------------------------------------------- */
//    const ALIPAY_CHANNEL = 'COM.ALIPAY';
//    const ALIPAY_CHANNEL_NAME = 'ALIPAY';
//    const ALIPAY_URL = 'https://qr.alipay.com/';
//    const ALIPAY_ID_REVERSE_DOMAIN = '00';
//    const ALIPAY_ID_URL = '01';
//    protected $alipay_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'url'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | AIRPAY (ANY)
//       | -------------------------------------------------------------------------------------------------------- */
//    const AIRPAY_CHANNEL = 'SG.AIRPAY';
//    const AIRPAY_CHANNEL_NAME = 'SHOPEEPAY';
//    const AIRPAY_ID_REVERSE_DOMAIN = '00';
//    const AIRPAY_ID_MERCHANT_ACCOUNT_INFORMATION = '01';
//    protected $airpay_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'merchant_account_information'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | NETS (ANY)
//       | -------------------------------------------------------------------------------------------------------- */
//    const NETS_CHANNEL = 'SG.COM.NETS';
//    const NETS_CHANNEL_NAME = 'NETS';
//    const NETS_ID_REVERSE_DOMAIN = '00';
//    const NETS_ID_QR_METADATA = '01';
//    const NETS_ID_MERCHANT_ID = '02';
//    const NETS_ID_TERMINAL_ID = '03';
//    const NETS_ID_TRANSACTION_AMOUNT_MODIFIER = '04';
//    const NETS_ID_SIGNATURE = '99';
//    protected $nets_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'qr_metadata',
//        '02' => 'merchant_id',
//        '03' => 'terminal_id',
//        '04' => 'transaction_amount_modifier',
//        '99' => 'signature',
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | SGQR (51 FIXED)
//       | -------------------------------------------------------------------------------------------------------- */
//    const SGQR_CHANNEL = 'SG.SGQR';
//    const SGQR_CHANNEL_NAME = 'SGQR';
//    const SGQR_ID_REVERSE_DOMAIN = '00';
//    const SGQR_ID_IDENTIFICATION_NUMBER = '01';
//    const SGQR_ID_VERSION = '02';
//    const SGQR_ID_POSTAL_CODE = '03';
//    const SGQR_ID_LEVEL = '04';
//    const SGQR_ID_UNIT_NUMBER = '05';
//    const SGQR_ID_MISC = '06';
//    const SGQR_ID_VERSION_DATE = '07';
//    protected $sgqr_keys = [
//        '00' => 'reverse_domain',
//        '01' => 'sgqr_id_number',
//        '02' => 'version',
//        '03' => 'postal_code',
//        '04' => 'level',
//        '05' => 'unit_number',
//        '06' => 'miscellaneous',
//        '07' => 'new_version_date'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | THAILAND
//       | -------------------------------------------------------------------------------------------------------- */
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | PROMPTPAY CREDIT TRANSFER (29)
//       | -------------------------------------------------------------------------------------------------------- */
//    const PROMPTPAY_CHANNEL = 'A000000677010111';
//    const PROMPTPAY_CHANNEL_NAME = 'TH.PROMPTPAY';
//    const PROMPTPAY_ID = '29';
//    const PROMPTPAY_ID_APP_ID = '00';
//    const PROMPTPAY_ID_MOBILE = '01';
//    const PROMPTPAY_ID_TAX_ID = '02';
//    const PROMPTPAY_ID_EWALLET_ID = '03';
//    const PROMPTPAY_ID_BANK_ACCT_NO = '04';
//    const PROMPTPAY_PROXY_MOBILE = 'MOBILE';
//    const PROMPTPAY_PROXY_TAX_ID = 'TAX_ID';
//    const PROMPTPAY_PROXY_EWALLET_ID = 'EWALLET_ID';
//    const PROMPTPAY_PROXY_BANK_ACCT_NO = 'BANK_ACCOUNT_NO';
//    protected $promptpay_keys = [
//        '00' => 'guid',
//        '01' => 'mobile_number',
//        '02' => 'tax_id',
//        '03' => 'ewallet_id',
//        '04' => 'bank_account_number'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | PROMPTPAY BILL PAYMENT (30)
//       | -------------------------------------------------------------------------------------------------------- */
//    const PROMPTPAY_BILL_CHANNEL = 'A000000677010112';
//    const PROMPTPAY_BILL_CHANNEL_NAME = 'TH.PROMPTPAY.BILL';
//    const PROMPTPAY_BILL_ID = '30';
//    const PROMPTPAY_BILL_APP_ID = '00';
//    const PROMPTPAY_BILL_BILLER_ID = '01';
//    const PROMPTPAY_BILL_REF_1 = '02';
//    const PROMPTPAY_BILL_REF_2 = '03';
//    protected $promptpay_bill_keys = [
//        '00' => 'guid',
//        '01' => 'biller_id',
//        '02' => 'reference_no_1',
//        '03' => 'reference_no_2'
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | ERRORS/WARNINGS
//       | -------------------------------------------------------------------------------------------------------- */
//    const MESSAGE_TYPE_ERROR = 'ERR';
//    const MESSAGE_TYPE_WARNING = 'WRN';
//    const ERROR_VALUE_PLACEHOLDER = '???';
//    // ERROR CODES
//    const ERROR_ID_NOT_FOUND = 'E00X';
//    const ERROR_MESSAGE_TYPE_NOT_FOUND = 'E00Y';
//    const ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID = 'E001';
//    const ERROR_ID_TYPE_OF_INITIATION_INVALID = 'E002';
//    const ERROR_ID_CURRENCY_NOT_SUPPORTED = 'E003';
//    const ERROR_ID_AMOUNT_INVALID = 'E004';
//    const ERROR_ID_FEE_INDICATOR_INVALID = 'E005';
//    const ERROR_ID_FEE2_EXIST_BUT_INDICATOR_INVALID = 'E006';
//    const ERROR_ID_FEE3_EXIST_BUT_INDICATOR_INVALID = 'E007';
//    const ERROR_ID_CONVENIENT_FEE_INVALID = 'E008';
//    const ERROR_ID_COUNTRY_CODE_INVALID = 'E009';
//    const ERROR_ID_CRC_INVALID = 'E010';
//    const ERROR_ID_AMOUNT_MISSING = 'E011';
//    const ERROR_ID_ACCOUNT_OUT_OF_BOUND = 'E012';
//    const ERROR_ID_PAYNOW_INVALID_PROXY_VALUE = 'E013';
//    const ERROR_ID_PAYNOW_MISSING_PROXY_TYPE = 'E014';
//    const ERROR_ID_PAYNOW_EDITABLE_FALSE_BUT_STATIC = 'E015';
//    const ERROR_ID_PAYNOW_EDITABLE_TRUE_BUT_DYNAMIC = 'E015.1';
//    const ERROR_ID_PAYNOW_EDITABLE_INVALID = 'E015.2';
//    const ERROR_ID_PAYNOW_EXPIRED_QR = 'E016';
//    const ERROR_ID_PAYNOW_EXPIRY_DATE_INVALID = 'E017';
//    const ERROR_ID_PROMPTPAY_MISSING_PROXY = 'E018';
//    const ERROR_ID_PROMPTPAY_INVALID_PROXY = 'E019';
//    const ERROR_ID_GENERAL_INVALID_FIELD = 'E020';
//    const ERROR_ID_PROMPTPAY_INVALID_ID = 'E021';
//    const ERROR_ID_PROMPTPAY_BILL_INVALID_ID = 'E022';
//
//    const ERROR_ID_MISSING_FIELD = 'E999';
//    // WARNING CODES
//    const WARNING_ID_MCC_INVALID = 'W001';
//    const WARNING_ID_MCC_UNKNOWN = 'W002';
//    const WARNING_ID_POINT_OF_INITIATION_STATIC_WITH_AMOUNT = 'W003';
//    const WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED = 'W004';
//    const WARNING_ID_ADDITIONAL_DATA_INVALID = 'W005';
//    const WARNING_ID_INVALID_CUSTOMER_REQUEST_TYPE = 'W006';
//    const WARNING_ID_INVALID_MERCHANT_CHANNEL = 'W007';
//    protected $messages = [
//        // ERROR DECODER
//        self::ERROR_ID_NOT_FOUND => "Error ID not found.",
//        self::ERROR_MESSAGE_TYPE_NOT_FOUND => "Message type was not found.",
//        self::ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID => "Payload format indicator is invalid. Expected '01', found '???'.",
//        self::ERROR_ID_TYPE_OF_INITIATION_INVALID => "Type of initiation is invalid. Expected '11' or '12', found '???'.",
//        self::ERROR_ID_CURRENCY_NOT_SUPPORTED => "Currency is not supported. Found '???' as the currency code. Please check the latest release documentation for supported currencies.",
//        self::ERROR_ID_AMOUNT_INVALID => "Transaction amount is invalid. Expected positive floating point number, found '???'.",
//        self::ERROR_ID_FEE_INDICATOR_INVALID => "Tip or convenience fee indicator is invalid. Expected '01', '02', or '03', found '???'.",
//        self::ERROR_ID_FEE2_EXIST_BUT_INDICATOR_INVALID => "Convenience fee (fixed) was set but the indicator is invalid. Expected '02', found '???'.",
//        self::ERROR_ID_FEE3_EXIST_BUT_INDICATOR_INVALID => "Convenience fee (percentage) was set but the indicator is invalid. Expected '03', found '???'.",
//        self::ERROR_ID_CONVENIENT_FEE_INVALID => "Convenience fee is invalid. Expected a fixed or percentage value, found '???'.",
//        self::ERROR_ID_COUNTRY_CODE_INVALID => "Country code is not supported. Currently, this class only supports SG, TH, MY, ID, found '???'.",
//        self::ERROR_ID_CRC_INVALID => "CRC found in the QR Code is incorrect. Expected '???1', found '???2'.",
//        self::ERROR_ID_AMOUNT_MISSING => "The type of initiation of this QR Code requires the transaction amount but such amount does not exist.",
//        self::ERROR_ID_ACCOUNT_OUT_OF_BOUND => "ID is out-of-bound. Expected '02' to '51', found '???'.",
//        self::ERROR_ID_PAYNOW_INVALID_PROXY_VALUE => "Proxy value is invalid. Expected the value of type ???1, found '???2'.",
//        self::ERROR_ID_PAYNOW_MISSING_PROXY_TYPE => "Proxy type is missing.",
//        self::ERROR_ID_PAYNOW_EDITABLE_FALSE_BUT_STATIC => "PayNow transaction value is set to not editable but the point of initiation is static.",
//        self::ERROR_ID_PAYNOW_EDITABLE_TRUE_BUT_DYNAMIC => "PayNow transaction value is set to editable but the point of initiation is dynamic.",
//        self::ERROR_ID_PAYNOW_EDITABLE_INVALID => "Invalid editable flag. Expected 0 or 1, found '???'.",
//        self::ERROR_ID_PAYNOW_EXPIRED_QR => "This QR code is already expired. The expiry date was ???.",
//        self::ERROR_ID_PAYNOW_EXPIRY_DATE_INVALID => "The expiry date of this QR code is invalid. Expected the date in 'yyyymmdd' format, found '???'.",
//        self::ERROR_ID_PROMPTPAY_MISSING_PROXY => "The proxy value (mobile number, tax ID, eWallet ID, or bank account number) is missing.",
//        self::ERROR_ID_PROMPTPAY_INVALID_PROXY => "The proxy value is invalid. Expected a mobile phone number, tax ID, e-wallet ID, or bank account number, found '???'.",
//        self::ERROR_ID_GENERAL_INVALID_FIELD => "The field ???1 is invalid, expected the value of type ???2, found '???3'.",
//        self::ERROR_ID_PROMPTPAY_INVALID_ID => "The ID for PromptPay is invalid, expected 29, found '???'.",
//        self::ERROR_ID_PROMPTPAY_BILL_INVALID_ID => "The ID for PromptPay is invalid, expected 30, found '???'.",
//        // ERROR GENERATOR
//        self::ERROR_ID_MISSING_FIELD => "The field ID ??? has never been set.",
//        // WARNING
//        self::WARNING_ID_MCC_INVALID => "Merchant category code is invalid. Expected 4-digit string, found '???'.",
//        self::WARNING_ID_MCC_UNKNOWN => "Merchant category code is unknown or does not exist in the system. Found '???'.",
//        self::WARNING_ID_POINT_OF_INITIATION_STATIC_WITH_AMOUNT => "Point of initiation was set to STATIC (01) but found that transaction amount is set. Point of initiation was updated to DYNAMIC (02).",
//        self::WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED => "Merchant information language template (64) is currently not supported. Please check documentation for newer releases.",
//        self::WARNING_ID_ADDITIONAL_DATA_INVALID => "Additional data field (ID ???1) is invalid. Found '???2'.",
//        self::WARNING_ID_INVALID_CUSTOMER_REQUEST_TYPE => "Customer data request type is invalid. Expected either 'A', 'E', or 'M', found '???'.",
//        self::WARNING_ID_INVALID_MERCHANT_CHANNEL => "Merchant channel value contains invalid character in (???1) position, found '???2'.",
//    ];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | VALIDATOR / SANITIZER
//       | -------------------------------------------------------------------------------------------------------- */
//    const MODE_VALIDATOR = 'V';
//    const MODE_SANITIZER = 'S';
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | PUBLIC PROPERTIES
//       | -------------------------------------------------------------------------------------------------------- */
//    public $mode;
//    public $qr_string;
//    public $payload_format_indicator;
//    public $point_of_initiation;
//    public $accounts = [];
//    public $merchant_category_code;
//    public $transaction_currency;
//    public $transaction_amount;
//    public $tip_or_convenience_fee_indicator;
//    public $convenience_fee_fixed;
//    public $convenience_fee_percentage;
//    public $country_code;
//    public $merchant_name;
//    public $merchant_city;
//    public $merchant_postal_code;
//    public $additional_fields = [];
//    public $crc;
//    public $errors = [];
//    public $warnings = [];
//    public $statuses = [];
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | ENVIRONMENT
//       | -------------------------------------------------------------------------------------------------------- */
//    const ENV_PROD = 'PRODUCTION';
//    const ENV_DEV = 'DEVELOPMENT';
//    protected $environment = 'DEVELOPMENT';
//
//    /**
//     * EmvMerchant constructor.
//     */
//    public function __construct()
//    {
//    }
//
//    /**
//     * Set the environment of the class
//     * For development environment, some of the security features are removed, showing more details to the error messages
//     * @param string $environment
//     */
//    public function set_environment($environment)
//    {
//        if (in_array($environment, [self::ENV_DEV, self::ENV_PROD]))
//        {
//            $this->environment = $environment;
//        }
//    }
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | phpCrc16 v1.1 -- CRC16/CCITT implementation
//       |
//       | By Matteo Beccati <matteo@beccati.com>
//       |
//       | Original code by:
//       | Ashley Roll
//       | Digital Nemesis Pty Ltd
//       | www.digitalnemesis.com
//       | ash@digitalnemesis.com
//       |
//       | Test Vector: "123456789" (character string, no quotes)
//       | Generated CRC: 0x29B1
//       |
//       | -------------------------------------------------------------------------------------------------------- */
//    /**
//     * Returns CRC16 of a string as int value
//     * @param string $str The string to digest
//     * @return string
//     */
//    protected function CRC16($str)
//    {
//        static $CRC16_Lookup = array(
//            0x0000, 0x1021, 0x2042, 0x3063, 0x4084, 0x50A5, 0x60C6, 0x70E7,
//            0x8108, 0x9129, 0xA14A, 0xB16B, 0xC18C, 0xD1AD, 0xE1CE, 0xF1EF,
//            0x1231, 0x0210, 0x3273, 0x2252, 0x52B5, 0x4294, 0x72F7, 0x62D6,
//            0x9339, 0x8318, 0xB37B, 0xA35A, 0xD3BD, 0xC39C, 0xF3FF, 0xE3DE,
//            0x2462, 0x3443, 0x0420, 0x1401, 0x64E6, 0x74C7, 0x44A4, 0x5485,
//            0xA56A, 0xB54B, 0x8528, 0x9509, 0xE5EE, 0xF5CF, 0xC5AC, 0xD58D,
//            0x3653, 0x2672, 0x1611, 0x0630, 0x76D7, 0x66F6, 0x5695, 0x46B4,
//            0xB75B, 0xA77A, 0x9719, 0x8738, 0xF7DF, 0xE7FE, 0xD79D, 0xC7BC,
//            0x48C4, 0x58E5, 0x6886, 0x78A7, 0x0840, 0x1861, 0x2802, 0x3823,
//            0xC9CC, 0xD9ED, 0xE98E, 0xF9AF, 0x8948, 0x9969, 0xA90A, 0xB92B,
//            0x5AF5, 0x4AD4, 0x7AB7, 0x6A96, 0x1A71, 0x0A50, 0x3A33, 0x2A12,
//            0xDBFD, 0xCBDC, 0xFBBF, 0xEB9E, 0x9B79, 0x8B58, 0xBB3B, 0xAB1A,
//            0x6CA6, 0x7C87, 0x4CE4, 0x5CC5, 0x2C22, 0x3C03, 0x0C60, 0x1C41,
//            0xEDAE, 0xFD8F, 0xCDEC, 0xDDCD, 0xAD2A, 0xBD0B, 0x8D68, 0x9D49,
//            0x7E97, 0x6EB6, 0x5ED5, 0x4EF4, 0x3E13, 0x2E32, 0x1E51, 0x0E70,
//            0xFF9F, 0xEFBE, 0xDFDD, 0xCFFC, 0xBF1B, 0xAF3A, 0x9F59, 0x8F78,
//            0x9188, 0x81A9, 0xB1CA, 0xA1EB, 0xD10C, 0xC12D, 0xF14E, 0xE16F,
//            0x1080, 0x00A1, 0x30C2, 0x20E3, 0x5004, 0x4025, 0x7046, 0x6067,
//            0x83B9, 0x9398, 0xA3FB, 0xB3DA, 0xC33D, 0xD31C, 0xE37F, 0xF35E,
//            0x02B1, 0x1290, 0x22F3, 0x32D2, 0x4235, 0x5214, 0x6277, 0x7256,
//            0xB5EA, 0xA5CB, 0x95A8, 0x8589, 0xF56E, 0xE54F, 0xD52C, 0xC50D,
//            0x34E2, 0x24C3, 0x14A0, 0x0481, 0x7466, 0x6447, 0x5424, 0x4405,
//            0xA7DB, 0xB7FA, 0x8799, 0x97B8, 0xE75F, 0xF77E, 0xC71D, 0xD73C,
//            0x26D3, 0x36F2, 0x0691, 0x16B0, 0x6657, 0x7676, 0x4615, 0x5634,
//            0xD94C, 0xC96D, 0xF90E, 0xE92F, 0x99C8, 0x89E9, 0xB98A, 0xA9AB,
//            0x5844, 0x4865, 0x7806, 0x6827, 0x18C0, 0x08E1, 0x3882, 0x28A3,
//            0xCB7D, 0xDB5C, 0xEB3F, 0xFB1E, 0x8BF9, 0x9BD8, 0xABBB, 0xBB9A,
//            0x4A75, 0x5A54, 0x6A37, 0x7A16, 0x0AF1, 0x1AD0, 0x2AB3, 0x3A92,
//            0xFD2E, 0xED0F, 0xDD6C, 0xCD4D, 0xBDAA, 0xAD8B, 0x9DE8, 0x8DC9,
//            0x7C26, 0x6C07, 0x5C64, 0x4C45, 0x3CA2, 0x2C83, 0x1CE0, 0x0CC1,
//            0xEF1F, 0xFF3E, 0xCF5D, 0xDF7C, 0xAF9B, 0xBFBA, 0x8FD9, 0x9FF8,
//            0x6E17, 0x7E36, 0x4E55, 0x5E74, 0x2E93, 0x3EB2, 0x0ED1, 0x1EF0
//        );
//        $crc16 = 0xFFFF; // the CRC
//        $len = strlen($str);
//        for ($i = 0; $i < $len; $i++)
//        {
//            $t = ($crc16 >> 8) ^ ord($str[$i]); // High byte Xor Message Byte to get index
//            $crc16 = (($crc16 << 8) & 0xffff) ^ $CRC16_Lookup[$t]; // Update the CRC from table
//        }
//        // crc16 now contains the CRC value
//        return $crc16;
//    }
//
//    /**
//     * Returns CRC16 of a string as hexadecimal string
//     * @param string $str The string to digest
//     * @return string
//     */
//    protected function CRC16HexDigest($str)
//    {
//        return sprintf('%04X', $this->CRC16($str));
//    }
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | ERRORS
//       | -------------------------------------------------------------------------------------------------------- */
//    /**
//     * Add error or warning message in class public property
//     * @param string|int $field_id Field ID
//     * @param string $message_type Type of the message, MESSAGE_TYPE_ERROR or MESSAGE_TYPE_WARNING
//     * @param string $message_id The message ID as defined in the class
//     * @param string|array $params The string or array of the values to be passed to the message
//     */
//    protected function add_message($field_id, $message_type, $message_id, $params = '')
//    {
//        if (isset($this->messages[$message_id]))
//        {
//            $message = $this->messages[$message_id];
//            if ( ! empty($params))
//            {
//                if (is_array($params))
//                {
//                    $intCount = count($params);
//                    $search_array = [];
//                    for ($i = 1; $i <= $intCount; $i++)
//                    {
//                        $search_array[] = self::ERROR_VALUE_PLACEHOLDER . $i;
//                    }
//                    $message = str_replace($search_array, $params, $message);
//                } else
//                {
//                    $message = str_replace(self::ERROR_VALUE_PLACEHOLDER, $params, $message);
//                }
//            }
//            $array = [
//                'field_id' => intval($field_id),
//                'code' => $message_id,
//                'message' => $message
//            ];
//            switch ($message_type)
//            {
//                case self::MESSAGE_TYPE_ERROR:
//                    $this->errors[] = $array;
//                    break;
//                case self::MESSAGE_TYPE_WARNING:
//                    $this->warnings[] = $array;
//                    break;
//                default:
//                    $this->warnings[] = $array;
//                    $this->errors[] = [
//                        'field_id' => intval($field_id),
//                        'code' => self::ERROR_MESSAGE_TYPE_NOT_FOUND,
//                        'message' => $this->messages[self::ERROR_MESSAGE_TYPE_NOT_FOUND]
//                    ];
//            }
//        } else
//        {
//            $this->errors[] = [
//                'field_id' => intval($field_id),
//                'code' => self::ERROR_ID_NOT_FOUND,
//                'message' => $this->messages[self::ERROR_ID_NOT_FOUND]
//            ];
//        }
//    }
//
//    /* | --------------------------------------------------------------------------------------------------------
//       | VALIDATORS
//       | -------------------------------------------------------------------------------------------------------- */
//    /**
//     * Validate or clean characters for those in ANS format
//     * @param string $string The string to validate
//     * @param string $mode Either sanitizer or validator
//     * @return false|int|string
//     */
//    protected function validate_ans_charset($string, $mode)
//    {
//        switch ($mode)
//        {
//            case self::MODE_VALIDATOR:
//                return preg_match('/[\x20-\x7E]+/', $string);
//            case self::MODE_SANITIZER:
//                return filter_var($string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
//            default:
//                return FALSE;
//        }
//    }
//
//    /**
//     * Validate the character set and check length of the input string
//     * @param string $string String to check
//     * @param int $length Max length
//     * @return bool
//     */
//    protected function validate_ans_charset_len($string, $length)
//    {
//        return (preg_match('/[\x20-\x7E]+/', $string) && strlen($string) <= $length);
//    }
//
//    /**
//     * Validate and get transaction amount value
//     * @param string $amount
//     * @return false|float
//     */
//    protected function parse_money_amount($amount)
//    {
//        if (preg_match('/^(\d+|\d+\.|\d+\.\d+)$/', $amount))
//        {
//            return floatval($amount);
//        } else
//        {
//            return FALSE;
//        }
//    }
//
//    /**
//     * Validate and get percentage amount from 00.01 to 99.99
//     * @param string $amount
//     * @return false|float
//     */
//    protected function parse_percentage_amount($amount)
//    {
//        if (preg_match('/^\d{1,2}(\.\d{0,2}){0,1}$/', $amount) && 0.00 < floatval(($amount)))
//        {
//            return floatval($amount);
//        } else
//        {
//            return FALSE;
//        }
//    }
//
//    /**
//     * Parse date in yyyymmdd format to Y-m-d
//     * @param string $string
//     * @return false|string
//     */
//    protected function parse_date_yyyymmdd($string)
//    {
//        if (preg_match('/[2-9]\d{3}(0[1-9]|1[0-2])(0[1-9]|[1-2]\d|3[0-1])/', $string))
//        {
//            $year = substr($string, self::POS_ZERO, self::LENGTH_FOUR);
//            $month = substr($string, self::POS_FOUR, self::LENGTH_TWO);
//            $date = substr($string, self::POS_SIX, self::LENGTH_TWO);
//            return "{$year}-{$month}-{$date}";
//        } else
//        {
//            return FALSE;
//        }
//    }
//
//    /**
//     * Parse date in yyyy-mm-dd format to yyyymmdd
//     * @param string $string
//     * @param bool $check_future
//     * @return bool|string
//     */
//    protected function format_date_with_dash($string, $check_future = TRUE)
//    {
//        if (preg_match('/[2-9]\d{3}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2]\d|3[0-1])/', $string))
//        {
//            if ($check_future)
//            {
//                $now = strtotime('now');
//                $input = strtotime($string);
//                return ($now < $input);
//            } else
//            {
//                return str_replace('-', '', $string);
//            }
//        } else
//        {
//            return FALSE;
//        }
//    }
//}