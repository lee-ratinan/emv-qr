<?php

namespace EMVQR;
require_once 'EmvMerchant.php';

/**
 * Class EmvMerchantDecoder
 * @package EMVQR
 */
class EmvMerchantDecoder extends EmvMerchant {

    /**
     * EmvMerchantDecoder constructor.
     * If $string is given, the constructor automatically call decode() and proceed to decode the QR code string.
     * Otherwise, the decode($string) needs to be called explicitly in the program.
     * @param string $string (optional) Input string read from the QR Code
     */
    public function __construct($string = '')
    {
        parent::__construct();
        $this->mode = parent::MODE_DECODE;
        if ( ! empty($string))
        {
            $this->decode($string);
        }
    }

    /**
     * Read and decode the EMV QR string
     * @param string $string Input string read from the QR Code
     */
    public function decode($string)
    {
        $this->qr_string = $string;
        $string = str_replace("\u{c2a0}", ' ', $string);
        while ( ! empty($string))
        {
            $strId = substr($string, parent::POS_ZERO, parent::LENGTH_TWO);
            $intId = intval($strId);
            $intLength = intval(substr($string, parent::POS_TWO, parent::LENGTH_TWO));
            $strValue = substr($string, parent::POS_FOUR, $intLength);
            switch ($strId)
            {
                case parent::ID_PAYLOAD_FORMAT_INDICATOR:
                    $this->process_payload_format_indicator($strValue);
                    break;
                case parent::ID_POINT_OF_INITIATION:
                    $this->process_point_of_initiation($strValue);
                    break;
                case parent::ID_MERCHANT_CATEGORY_CODE:
                    $this->process_merchant_category_code($strValue);
                    break;
                case parent::ID_TRANSACTION_CURRENCY:
                    $this->process_currency($strValue);
                    break;
                case parent::ID_TRANSACTION_AMOUNT:
                    $this->process_amount($strValue);
                    break;
                case parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR:
                    $this->process_fee_indicator($strValue);
                    break;
                case parent::ID_VALUE_OF_FEE_FIXED:
                    $this->process_fee_value_fixed($strValue);
                    break;
                case parent::ID_VALUE_OF_FEE_PERCENTAGE:
                    $this->process_fee_value_percentage($strValue);
                    break;
                case parent::ID_COUNTRY_CODE:
                    $this->process_country_code($strValue);
                    break;
                case parent::ID_MERCHANT_NAME:
                    $this->merchant_name = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
                    break;
                case parent::ID_MERCHANT_CITY:
                    $this->merchant_city = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
                    break;
                case parent::ID_MERCHANT_POSTAL_CODE:
                    $this->merchant_postal_code = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
                    break;
                case parent::ID_ADDITIONAL_DATA_FIELDS:
                    $this->process_additional_data($strValue);
                    break;
                case parent::ID_CRC:
                    $this->process_crc($strValue);
                    break;
                case parent::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE:
                    $this->add_message(parent::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_LANGUAGE_TEMPLATE_NOT_SUPPORTED);
                    break;
                default:
                    $this->process_accounts($intId, $strValue); // @todo check
            }
            $string = substr($string, parent::LENGTH_FOUR + $intLength);
        }
        if (parent::POINT_OF_INITIATION_DYNAMIC_VALUE == $this->point_of_initiation && is_null($this->transaction_amount))
        {
            $this->add_message(parent::ID_TRANSACTION_AMOUNT, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_AMOUNT_MISSING);
        }
    }

    /**
     * Validate and assign payload format indicator to the class
     * @param string $strValue
     */
    private function process_payload_format_indicator($strValue)
    {
        if (parent::PAYLOAD_FORMAT_INDICATOR_VALUE == $strValue)
        {
            $this->payload_format_indicator = $strValue;
        } else
        {
            $this->add_message(parent::ID_PAYLOAD_FORMAT_INDICATOR, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID, $strValue);
        }
    }

    /**
     * Validate and assign point of initiation to the class
     * @param string $strValue
     */
    private function process_point_of_initiation($strValue)
    {
        switch ($strValue)
        {
            case parent::POINT_OF_INITIATION_STATIC:
                $this->point_of_initiation = parent::POINT_OF_INITIATION_STATIC_VALUE;
                break;
            case parent::POINT_OF_INITIATION_DYNAMIC:
                $this->point_of_initiation = parent::POINT_OF_INITIATION_DYNAMIC_VALUE;
                break;
            default:
                $this->add_message(parent::ID_POINT_OF_INITIATION, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_TYPE_OF_INITIATION_INVALID, $strValue);
        }
    }

    /**
     * Assign merchant category code and its value (according to ISO 18245; if any) to the class
     * @param string $strValue
     */
    private function process_merchant_category_code($strValue)
    {
        $this->merchant_category_code['code'] = $strValue;
        if (isset($this->merchant_category_codes[$strValue]))
        {
            $this->merchant_category_code['value'] = $this->merchant_category_codes[$strValue];
        } else
        {
            $this->merchant_category_code['value'] = parent::MERCHANT_CATEGORY_UNKNOWN;
            if (preg_match('/\d{4}/', $strValue))
            {
                $this->add_message(parent::ID_MERCHANT_CATEGORY_CODE, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_MCC_INVALID, $strValue);
            } else
            {
                $this->add_message(parent::ID_MERCHANT_CATEGORY_CODE, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_MCC_UNKNOWN, $strValue);
            }
        }
    }

    /**
     * Validate and assign currency to the class
     * @param string $strValue
     */
    private function process_currency($strValue)
    {
        if (isset($this->currency_codes[$strValue]))
        {
            $this->transaction_currency = $this->currency_codes[$strValue];
        } else
        {
            $this->add_message(parent::ID_TRANSACTION_CURRENCY, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_CURRENCY_NOT_SUPPORTED, $strValue);
        }
    }

    /**
     * Validate and assign amount to the class
     * Matching:
     * - \d+ or \d+\. An integer
     * or
     * - \d+\.\d+ A floating point number
     * @param string $strValue
     */
    private function process_amount($strValue)
    {
        $value = $this->parse_money_amount($strValue);
        if (FALSE == $value)
        {
            $this->add_message(parent::ID_TRANSACTION_AMOUNT, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_AMOUNT_INVALID, $strValue);
        } else
        {
            $this->transaction_amount = number_format($value, 2, '', '');
            if (parent::POINT_OF_INITIATION_STATIC_VALUE == $this->point_of_initiation)
            {
                $this->point_of_initiation = parent::POINT_OF_INITIATION_DYNAMIC_VALUE;
                $this->add_message(parent::ID_TRANSACTION_AMOUNT, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_POINT_OF_INITIATION_STATIC_WITH_AMOUNT);
            }
        }
    }

    /**
     * Validate and assign fee indicator to the class
     * @param string $strValue
     */
    private function process_fee_indicator($strValue)
    {
        if (isset($this->tip_or_convenience_fee_indicators[$strValue]))
        {
            $this->tip_or_convenience_fee_indicator = $this->tip_or_convenience_fee_indicators[$strValue];
        } else
        {
            $this->add_message(parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_FEE_INDICATOR_INVALID, $strValue);
        }
    }

    /**
     * Validate and assign fee value (fixed amount) to the class
     * @param string $strValue
     */
    private function process_fee_value_fixed($strValue)
    {
        if (self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED_VALUE == $this->tip_or_convenience_fee_indicator)
        {
            $value = $this->parse_money_amount($strValue);
            if (FALSE == $value)
            {
                $this->add_message(parent::ID_VALUE_OF_FEE_FIXED, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_CONVENIENT_FEE_INVALID, $strValue);
            } else
            {
                $this->convenience_fee_fixed = number_format($value, 2, '', '');
            }
        } else
        {
            $this->add_message(parent::ID_VALUE_OF_FEE_FIXED, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_FEE2_EXIST_BUT_INDICATOR_INVALID, $strValue);
        }
    }

    /**
     * Validate and assign fee value (percentage) to the class
     * @param string $strValue
     */
    private function process_fee_value_percentage($strValue)
    {
        if (self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE_VALUE == $this->tip_or_convenience_fee_indicator)
        {
            $value = $this->parse_percentage_amount($strValue);
            if (FALSE == $value)
            {
                $this->add_message(parent::ID_VALUE_OF_FEE_PERCENTAGE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_CONVENIENT_FEE_INVALID, $strValue);
            } else
            {
                $this->convenience_fee_percentage = number_format($value, 2, '', '');
            }
        } else
        {
            $this->add_message(parent::ID_VALUE_OF_FEE_PERCENTAGE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_FEE3_EXIST_BUT_INDICATOR_INVALID, $strValue);
        }
    }

    /**
     * Validate and assign country code to the class
     * @param string $strValue
     */
    private function process_country_code($strValue)
    {
        if (in_array($strValue, $this->country_codes))
        {
            $this->country_code = $strValue;
        } else
        {
            $this->add_message(parent::ID_COUNTRY_CODE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_COUNTRY_CODE_INVALID, $strValue);
        }
    }

    /**
     * Process additional data fields
     * @param string $string
     */
    private function process_additional_data($string)
    {
        while ( ! empty($string))
        {
            $strId = substr($string, parent::POS_ZERO, parent::LENGTH_TWO);
            $intLength = intval(substr($string, parent::POS_TWO, parent::LENGTH_TWO));
            $strValue = substr($string, parent::POS_FOUR, $intLength);
            switch ($strId)
            {
                case parent::ID_ADDITIONAL_DATA_BILL_NUMBER:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_BILL_NUMBER, parent::ID_ADDITIONAL_DATA_BILL_NUMBER_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER, parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_STORE_LABEL:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_STORE_LABEL, parent::ID_ADDITIONAL_DATA_STORE_LABEL_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER, parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL, parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL, parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL, parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY_FIVE, parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION, parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST:
                    $this->process_additional_customer_data_request($strValue);
                    break;
                case parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID:
                    $this->process_additional_data_field($strValue, parent::LENGTH_TWENTY, parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID, parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY);
                    break;
                case parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL:
                    $this->process_additional_data_channel($strValue);
                    break;
                default:
                    $this->additional_fields[$strId] = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
            }
            $string = substr($string, parent::LENGTH_FOUR + $intLength);
        }
    }

    /**
     * Process individual additional data field
     * @param string $strValue
     * @param int $length
     * @param string $field_id
     * @param string $field_name
     */
    private function process_additional_data_field($strValue, $length, $field_id, $field_name)
    {
        if ($this->validate_ans_charset_len($strValue, $length))
        {
            $this->additional_fields[$field_name] = $strValue;
        } else
        {
            $this->add_message(parent::ID_ADDITIONAL_DATA_FIELDS . '.' . $field_id, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_ADDITIONAL_DATA_INVALID, [$field_name, $strValue]);
        }
    }

    /**
     * // @todo check
     * Process additional customer data request
     * @param string $string
     */
    private function process_additional_customer_data_request($string)
    {
        while ( ! empty($string))
        {
            $key = substr($string, parent::POS_ZERO, parent::LENGTH_ONE);
            switch ($key)
            {
                case parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_ID:
                    $this->additional_fields[parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY][] = parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_MOBILE_LABEL;
                    break;
                case parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_ID:
                    $this->additional_fields[parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY][] = parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_ADDRESS_LABEL;
                    break;
                case parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_ID:
                    $this->additional_fields[parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY][] = parent::ID_ADDITIONAL_DATA_CUSTOMER_DATA_REQUEST_EMAIL_LABEL;
                    break;
                default:
                    $this->add_message(parent::ID_ADDITIONAL_DATA_FIELDS . '.' . parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_INVALID_CUSTOMER_REQUEST_TYPE, $key);
            }
            $string = substr($string, parent::POS_ONE);
        }
    }

    /**
     * // @todo check
     * Process and verify additional data channel
     * @param string $string
     */
    private function process_additional_data_channel($string)
    {
        $media = substr($string, parent::POS_ZERO, parent::LENGTH_ONE);
        $location = substr($string, parent::POS_ONE, parent::LENGTH_ONE);
        $presence = substr($string, parent::POS_TWO, parent::LENGTH_ONE);
        if (isset($this->merchant_channel_medias[$media]))
        {
            $this->additional_fields[parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY][parent::MERCHANT_CHANNEL_CHAR_MEDIA_KEY] = $this->merchant_channel_medias[$media];
        } else
        {
            $this->add_message(parent::ID_ADDITIONAL_DATA_FIELDS . '.' . parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_INVALID_MERCHANT_CHANNEL, [parent::MERCHANT_CHANNEL_CHAR_MEDIA_KEY, $media]);
        }
        if (isset($this->merchant_channel_locations[$location]))
        {
            $this->additional_fields[parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY][parent::MERCHANT_CHANNEL_CHAR_LOCATION_KEY] = $this->merchant_channel_locations[$location];
        } else
        {
            $this->add_message(parent::ID_ADDITIONAL_DATA_FIELDS . '.' . parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_INVALID_MERCHANT_CHANNEL, [parent::MERCHANT_CHANNEL_CHAR_LOCATION_KEY, $location]);
        }
        if (isset($this->merchant_channel_presences[$presence]))
        {
            $this->additional_fields[parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY][parent::MERCHANT_CHANNEL_CHAR_PRESENCE_KEY] = $this->merchant_channel_presences[$presence];
        } else
        {
            $this->add_message(parent::ID_ADDITIONAL_DATA_FIELDS . '.' . parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_INVALID_MERCHANT_CHANNEL, [parent::MERCHANT_CHANNEL_CHAR_PRESENCE_KEY, $presence]);
        }
    }

    /**
     * Process and verify the CRC field
     * @param string $strValue
     */
    private function process_crc($strValue)
    {
        $this->crc = $strValue;
        $checkData = substr($this->qr_string, parent::POS_ZERO, parent::POS_MINUS_FOUR);
        $newCrc = $this->CRC16HexDigest($checkData);
        if ($strValue != $newCrc)
        {
            $this->add_message(parent::ID_CRC, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_CRC_INVALID, [$newCrc, $strValue]);
        }
    }

    /**
     * // @todo check
     * Process accounts
     * @param int $intId
     * @param string $strValue
     */
    private function process_accounts($intId, $strValue)
    {
        if (parent::ID_ACCOUNT_LOWER_BOUNDARY > $intId || parent::ID_ACCOUNT_UPPER_BOUNDARY < $intId)
        {
            $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_ACCOUNT_OUT_OF_BOUND, $intId);
            return;
        }
        $account_raw = [];
        while ( ! empty($strValue))
        {
            $strId = substr($strValue, parent::POS_ZERO, parent::LENGTH_TWO);
            $intLength = intval(substr($strValue, parent::POS_TWO, parent::LENGTH_TWO));
            $thisValue = substr($strValue, parent::POS_FOUR, $intLength);
            $account_raw[$strId] = $thisValue;
            $strValue = substr($strValue, parent::LENGTH_FOUR + $intLength);
        }
        $account_raw['00'] = strtoupper($account_raw['00']);
        switch ($account_raw['00'])
        {
            // SINGAPORE
            case parent::PAYNOW_CHANNEL:
                $this->process_paynow($account_raw, $intId);
                break;
            case parent::FAVE_CHANNEL:
                $this->process_favepay($account_raw, $intId);
                break;
            case parent::SGQR_CHANNEL:
                $this->process_sgqr($account_raw, $intId);
                break;
            // THAILAND
            case parent::PROMPTPAY_CHANNEL:
                $this->process_promptpay($account_raw, $intId);
                break;
            case parent::PROMPTPAY_BILL_CHANNEL:
                $this->process_promptpay_bill($account_raw, $intId); // todo: start working on it
                break;
            default:
                $this->accounts[$account_raw['00']] = array_merge([parent::ID_ORIGINAL_LABEL => $intId], $account_raw);
        }
    }

    /* | --------------------------------------------------------------------------------------------------------
       | SINGAPORE
       | -------------------------------------------------------------------------------------------------------- */

    /**
     * // @todo check
     * Process PayNow account
     * @param string $account_raw
     * @param int $intId
     */
    private function process_paynow($account_raw, $intId)
    {
        $account[parent::ID_ORIGINAL_LABEL] = $intId;
        $account[$this->paynow_keys[parent::PAYNOW_ID_CHANNEL]] = $account_raw[parent::PAYNOW_ID_CHANNEL];
        // PROXY TYPE AND VALUE
        $proxy_type = $account_raw[parent::PAYNOW_ID_PROXY_TYPE];
        $proxy_value = $account_raw[parent::PAYNOW_ID_PROXY_VALUE];
        if (isset($this->paynow_proxy_type[$proxy_type]))
        {
            $account[$this->paynow_keys[parent::PAYNOW_ID_PROXY_TYPE]] = $this->paynow_proxy_type[$proxy_type];
            if (parent::PAYNOW_PROXY_MOBILE == $proxy_type)
            {
                // Accept all country's phone number with country code (E.164 format)
                if (preg_match('/^\+(\d){8,15}$/', $proxy_value))
                {
                    $account[$this->paynow_keys[parent::PAYNOW_ID_PROXY_VALUE]] = $proxy_value;
                } else
                {
                    $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_INVALID_PROXY_VALUE, [$this->paynow_proxy_type[parent::PAYNOW_PROXY_MOBILE], $proxy_value]);
                }
            } else
            {
                // UEN format, src: https://www.uen.gov.sg/ueninternet/faces/pages/admin/aboutUEN.jspx
                /*
                 * Businesses registered with ACRA: nnnnnnnnX
                 * Local companies registered with ACRA: yyyynnnnnX
                 * All other entities which will be issued new UEN: TyyPQnnnnX
                 */
                if (preg_match('/^(\d{8}[A-Z]|(19|20)\d{7}[A-Z]|(S|T)\d{2}[A-Z]{2}\d{4}[A-Z])(\d{2,4}){0,1}$/', $proxy_value))
                {
                    $account[$this->paynow_keys[parent::PAYNOW_ID_PROXY_VALUE]] = $proxy_value;
                } else
                {
                    $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_INVALID_PROXY_VALUE, [$this->paynow_proxy_type[parent::PAYNOW_PROXY_UEN], $proxy_value]);
                }
            }
        } else
        {
            $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_MISSING_PROXY_TYPE);
        }
        // EDITABLE
        if (isset($account_raw[parent::PAYNOW_ID_AMOUNT_EDITABLE]))
        {
            $editable = $account_raw[parent::PAYNOW_ID_AMOUNT_EDITABLE];
            if (isset($this->paynow_amount_editable[$editable]))
            {
                $account[$this->paynow_keys[parent::PAYNOW_ID_AMOUNT_EDITABLE]] = $this->paynow_amount_editable[$editable];
                if ($editable == parent::PAYNOW_AMOUNT_EDITABLE_FALSE && $this->point_of_initiation == parent::POINT_OF_INITIATION_STATIC_VALUE)
                {
                    $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_EDITABLE_FALSE_BUT_STATIC);
                }
            }
        }
        // EXPIRY DATE
        if (isset($account_raw[parent::PAYNOW_ID_EXPIRY_DATE]))
        {
            $expiry_date = $this->parse_date_yyyymmdd($account_raw[parent::PAYNOW_ID_EXPIRY_DATE]);
            if (FALSE != $expiry_date)
            {
                date_default_timezone_set(parent::TIMEZONE_SINGAPORE);
                $now = date(parent::FORMAT_DATE);
                if ($expiry_date < $now)
                {
                    $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_EXPIRED_QR, $expiry_date);
                } else
                {
                    $account[$this->paynow_keys[parent::PAYNOW_ID_EXPIRY_DATE]] = $expiry_date;
                }
            } else
            {
                $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYNOW_EXPIRY_DATE_INVALID, $account_raw[parent::PAYNOW_ID_EXPIRY_DATE]);
            }
        }
        $this->accounts[parent::PAYNOW_CHANNEL] = $account;
    }

    /**
     * // @todo check
     * Process FavePay
     * @param string $account_raw
     * @param int $intId
     */
    private function process_favepay($account_raw, $intId)
    {
        $account[parent::ID_ORIGINAL_LABEL] = $intId;
        $account['channel'] = parent::FAVE_CHANNEL_NAME;
        foreach ($account_raw as $id => $val)
        {
            $account[$this->favepay_keys[$id]] = $val;
        }
        $this->accounts[parent::FAVE_CHANNEL_NAME] = $account;
    }

    /**
     * // @todo check
     * Process SGQR information - not an account but required
     * @param string $account_raw
     * @param int $intId
     */
    private function process_sgqr($account_raw, $intId)
    {
        // FIXED 51
        $account[parent::ID_ORIGINAL_LABEL] = $intId;
        foreach ($account_raw as $id => $val)
        {
            $account[$this->sgqr_keys[$id]] = $val;
        }
        $this->accounts[parent::SGQR_CHANNEL] = $account;
    }

    /* | --------------------------------------------------------------------------------------------------------
       | THAILAND
       | -------------------------------------------------------------------------------------------------------- */

    /**
     * // @todo check
     * Process PromptPay account
     * @param string $account_raw
     * @param int $intId
     */
    private function process_promptpay($account_raw, $intId)
    {
        // MOSTLY 29
        $account[parent::ID_ORIGINAL_LABEL] = $intId;
        $account[$this->promptpay_keys[99]] = parent::PROMPTPAY_CHANNEL_NAME;
        $account[$this->promptpay_keys[parent::PROMPTPAY_ID_APP_ID]] = $account_raw[parent::PROMPTPAY_ID_APP_ID];
        if ( ! empty($account_raw[parent::PROMPTPAY_ID_MOBILE]))
        {
            if (preg_match('/^0066(6|8|9)(\d{8})$/', $account_raw[parent::PROMPTPAY_ID_MOBILE]))
            {
                $account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_MOBILE;
                $account[$this->promptpay_keys[98]] = $account_raw[parent::PROMPTPAY_ID_MOBILE];
                $account[$this->promptpay_keys[96]] = '+66' . substr($account_raw[parent::PROMPTPAY_ID_MOBILE], parent::POS_FOUR);
            } else
            {
                $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PROMPTPAY_INVALID_PROXY, $account_raw[parent::PROMPTPAY_ID_MOBILE]);
            }
        } else if ( ! empty($account_raw[parent::PROMPTPAY_ID_TAX_ID]))
        {
            if (preg_match('/^\d{13}$/', $account_raw[parent::PROMPTPAY_ID_TAX_ID]))
            {
                $account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_TAX_ID;
                $account[$this->promptpay_keys[98]] = $account_raw[parent::PROMPTPAY_ID_TAX_ID];
            } else
            {
                $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PROMPTPAY_INVALID_PROXY, $account_raw[parent::PROMPTPAY_ID_TAX_ID]);
            }
        } else if ( ! empty($account_raw[parent::PROMPTPAY_ID_EWALLET_ID]))
        {
            $account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_EWALLET_ID;
            $account[$this->promptpay_keys[98]] = $account_raw[parent::PROMPTPAY_ID_TAX_ID];
        } else
        {
            $this->add_message($intId, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PROMPTPAY_MISSING_PROXY);
        }
        $this->accounts[parent::PROMPTPAY_CHANNEL_NAME] = $account;
    }

    /**
     * // @todo implement it
     * Process PromptPay Bill Payment account
     * @param string $account_raw
     * @param int $intId
     */
    private function process_promptpay_bill($account_raw, $intId)
    {
        $account = [];
        $this->accounts[parent::PROMPTPAY_BILL_CHANNEL_NAME] = $account;
    }

}
