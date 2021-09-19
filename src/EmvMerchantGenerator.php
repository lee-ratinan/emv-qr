<?php

namespace EMVQR;
require_once 'EmvMerchant.php';

/**
 * Class EmvMerchantGenerator
 * @package EMVQR
 */
class EmvMerchantGenerator extends EmvMerchant {

    const DIGIT_OH_TWO = '02';
    const DIGIT_OH_THREE = '03';
    const DIGIT_OH_FOUR = '04';

    const STATUS_COUNTRY_CODE_NOT_SUPPORTED = "Country code is not supported by this library.";
    const STATUS_MISSING_FIELDS = "Required field(s) are missing.";
    const STATUS_INVALID_VALUE = "Required field has invalid character(s) and/or too long.";
    const STATUS_INVALID_FIELD_ID = "Field ID or field name is invalid.";
    const STATUS_IN_WRONG_COUNTRY = "The current account is not supported by the country already set.";
    const STATUS_NO_ACCOUNT_ID = "The system cannot find the right ID for this account.";

    /* | --------------------------------------------------------------------------------------------------------
       | GENERATOR:
       | 00 Payload format indicator (M)            - __construct()
       | 01 Point of initiation (O)                 - set_point_of_initiation_static() or set_price()
       | 02-51 Accounts (M)                         - based on each network
       | 52 Merchant category code (M)              - set_merchant_info()
       | 53 Transaction currency (M)                - set_country()
       | 54 Transaction amount (C)                  - set_point_of_initiation_static() or set_price()
       | 55 Tip or Convenience Indicator (O)        - set_tip_or_fees()
       | 56 Value of Convenience Fee Fixed (C)      - set_tip_or_fees()
       | 57 Value of Convenience Fee Percentage (C) - set_tip_or_fees()
       | 58 Country code (M)                        - set_country()
       | 59 Merchant name (M)                       - set_merchant_info()
       | 60 Merchant city (M)                       - set_merchant_info()
       | 61 Merchant postal code (O)                - set_merchant_info()
       | 62 Additional data field template (O)      - set_additional_info()
       | 63 CRC (O)
       | 64 Merchant information template language (O) - Not yet supported
       | -------------------------------------------------------------------------------------------------------- */

    /**
     * EmvMerchantDecoder constructor.
     * Add mode and payload format indicator (01)
     */
    public function __construct()
    {
        parent::__construct();
        $this->mode = parent::MODE_GENERATE;
        $this->payload_format_indicator = parent::PAYLOAD_FORMAT_INDICATOR_VALUE;
    }

    /**
     * Return status
     * @param bool $status Return TRUE if no error, FALSE otherwise
     * @param null|string $message
     * @param string|array $field_id
     * @return array Status
     */
    private function return_status($status, $message = NULL, $field_id = NULL)
    {
        return [
            'status' => $status,
            'message' => $message,
            'field_id' => $field_id
        ];
    }

    /**
     * Add country code (58) and currency code (53)
     * @param string $country_code
     * @return array Status
     */
    public function set_country($country_code)
    {
        if (in_array($country_code, $this->country_codes))
        {
            switch ($country_code)
            {
                case parent::COUNTRY_SG:
                    $this->country_code = parent::COUNTRY_SG;
                    $this->transaction_currency = parent::CURRENCY_SGD_NUMERIC;
                    break;
                case parent::COUNTRY_TH:
                    $this->country_code = parent::COUNTRY_TH;
                    $this->transaction_currency = parent::CURRENCY_THB_NUMERIC;
                    break;
                case parent::COUNTRY_ID:
                    $this->country_code = parent::COUNTRY_ID;
                    $this->transaction_currency = parent::CURRENCY_IDR_NUMERIC;
                    break;
                case parent::COUNTRY_IN:
                    $this->country_code = parent::COUNTRY_IN;
                    $this->transaction_currency = parent::CURRENCY_INR_NUMERIC;
                    break;
                case parent::COUNTRY_MY:
                    $this->country_code = parent::COUNTRY_MY;
                    $this->transaction_currency = parent::CURRENCY_MYR_NUMERIC;
                    break;
                case parent::COUNTRY_HK:
                    $this->country_code = parent::COUNTRY_HK;
                    $this->transaction_currency = parent::CURRENCY_HKD_NUMERIC;
                    break;
            }
            return $this->return_status(TRUE);
        } else
        {
            return $this->return_status(FALSE, self::STATUS_COUNTRY_CODE_NOT_SUPPORTED, [parent::ID_TRANSACTION_CURRENCY, parent::ID_COUNTRY_CODE]);
        }
    }

    /**
     * Add merchant name (59), city (60), merchant category code (52), and postal code (61)
     * @param string $merchant_name
     * @param string $merchant_city
     * @param string $merchant_category_code (optional)
     * @param string $postal_code (optional)
     * @return array Status
     */
    public function set_merchant_info($merchant_name, $merchant_city, $merchant_category_code = '', $postal_code = '')
    {
        // check empty
        if (empty($merchant_name) || empty($merchant_city))
        {
            return $this->return_status(FALSE, self::STATUS_MISSING_FIELDS, [parent::ID_MERCHANT_NAME, parent::ID_MERCHANT_CITY]);
        }
        // merchant name
        if ($this->validate_ans_charset_len($merchant_name, parent::LENGTH_TWENTY_FIVE))
        {
            $this->merchant_name = strtoupper($merchant_name);
        } else
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_MERCHANT_NAME);
        }
        // merchant city
        if ($this->validate_ans_charset_len($merchant_city, parent::LENGTH_TWENTY_FIVE))
        {
            $this->merchant_city = strtoupper($merchant_city);
        } else
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_MERCHANT_CITY);
        }
        // merchant category code
        if ( ! empty($merchant_category_code))
        {
            if (isset($this->merchant_category_codes[$merchant_category_code]))
            {
                $this->merchant_category_code = $merchant_category_code;
            } else
            {
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_MERCHANT_CATEGORY_CODE);
            }
        } else
        {
            $this->merchant_category_code = parent::MERCHANT_CATEGORY_CODE_GENERIC;
        }
        // postal code
        if ($this->validate_ans_charset_len($postal_code, parent::LENGTH_TEN))
        {
            $this->merchant_postal_code = strtoupper($postal_code);
        } else if ( ! empty($postal_code))
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_MERCHANT_POSTAL_CODE);
        }
        return $this->return_status(TRUE);
    }

    /**
     * Set point of initiation (01) to STATIC, used when the price does not need to be set
     * @return array Status
     */
    public function set_point_of_initiation_static()
    {
        $this->point_of_initiation = parent::POINT_OF_INITIATION_STATIC;
        $this->transaction_amount = NULL;
        return $this->return_status(TRUE);
    }

    /**
     * Add transaction amount (54) and set point of initiation (01)
     * Automatically set the point of initiation (01) to DYNAMIC when the price is not null, STATIC otherwise
     * If the price is null, set_point_of_initiation_static() can be used instead
     * @param float|int|null $price
     * @return array Status
     */
    public function set_price($price = NULL)
    {
        if (is_null($price) || empty($price))
        {
            return $this->set_point_of_initiation_static();
        } else
        {
            $this->point_of_initiation = parent::POINT_OF_INITIATION_DYNAMIC;
            if (is_numeric($price))
            {
                $this->transaction_amount = number_format($price, 2, '.', '');
            } else
            {
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_TRANSACTION_AMOUNT);
            }
        }
        return $this->return_status(TRUE);
    }

    /**
     * Add tip or fees flag, if fee, $amount is required (55-57)
     * @param string $code
     * @param int $amount (optional)
     * @return array Status
     */
    public function set_tip_or_fees($code, $amount = 0)
    {
        $amount = floatval($amount);
        if ($code == parent::FEE_INDICATOR_TIP)
        {
            $this->tip_or_convenience_fee_indicator = $code;
        } else if ($code == parent::FEE_INDICATOR_CONVENIENCE_FEE_FIXED)
        {
            $this->tip_or_convenience_fee_indicator = $code;
            if (0.00 < $amount && $amount <= 9999999999.99)
            {
                $this->convenience_fee_fixed = number_format($amount, 2, '.', '');
            } else
            {
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_VALUE_OF_FEE_FIXED);
            }
        } else if ($code == parent::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE)
        {
            $this->tip_or_convenience_fee_indicator = $code;
            if (0.00 < $amount && $amount < 100.00)
            {
                $this->convenience_fee_percentage = number_format($amount, 2, '.', '');
            } else
            {
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_VALUE_OF_FEE_PERCENTAGE);
            }
        } else
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR);
        }
        return $this->return_status(TRUE);
    }

    /**
     * Set additional info (62)
     * @param string $field_name
     * @param string $field_value
     * @return array Status
     */
    public function set_additional_info($field_name, $field_value)
    {
        $max_length = self::LENGTH_TWENTY_FIVE;
        $field_id = '';
        $function = NULL;
        switch ($field_name)
        {
            case parent::ID_ADDITIONAL_DATA_BILL_NUMBER:
            case parent::ID_ADDITIONAL_DATA_BILL_NUMBER_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_BILL_NUMBER;
                break;
            case parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER:
            case parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER;
                break;
            case parent::ID_ADDITIONAL_DATA_STORE_LABEL:
            case parent::ID_ADDITIONAL_DATA_STORE_LABEL_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_STORE_LABEL;
                break;
            case parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER:
            case parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER;
                break;
            case parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL:
            case parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL;
                break;
            case parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL:
            case parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL;
                break;
            case parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL:
            case parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL;
                break;
            case parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION:
            case parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION;
                break;
            case parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST:
            case parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST;
                $function = parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY;
                break;
            case parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID:
            case parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID;
                $max_length = parent::LENGTH_TWENTY;
                break;
            case parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL:
            case parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY:
                $field_id = parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL;
                $function = parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY;
                break;
            default:
                return $this->return_status(FALSE, self::STATUS_INVALID_FIELD_ID, parent::ID_ADDITIONAL_DATA_FIELDS);
        }
        if (is_null($function))
        {
            if ($this->validate_ans_charset_len($field_value, $max_length))
            {
                $this->additional_fields[$field_id] = $field_id . sprintf('%02d', strlen($field_value)) . $field_value;
                return $this->return_status(TRUE);
            }
        } else if ($function == parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY)
        {
            if (preg_match('/^[A|M|E]{1,3}$/', $field_value))
            {
                $this->additional_fields[$field_id] = $field_id . sprintf('%02d', strlen($field_value)) . $field_value;
                return $this->return_status(TRUE);
            }
        } else if ($function == parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY)
        {
            if (preg_match('/^[0-7][0-3][0-3]$/', $field_value))
            {
                $this->additional_fields[$field_id] = $field_id . sprintf('%02d', strlen($field_value)) . $field_value;
                return $this->return_status(TRUE);
            }
        }
        return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::ID_ADDITIONAL_DATA_FIELDS . '.' . $field_name);
    }

    /* | --------------------------------------------------------------------------------------------------------
       | ACCOUNTS
       | -------------------------------------------------------------------------------------------------------- */

    /**
     * Find account ID
     * @param int $preferred
     * @return false|string
     */
    private function assign_account_id($preferred = parent::ID_ACCOUNT_START_INDEX)
    {
        if ($preferred < parent::ID_ACCOUNT_START_INDEX || $preferred > parent::ID_ACCOUNT_UPPER_BOUNDARY)
        {
            $preferred = parent::ID_ACCOUNT_START_INDEX;
        }
        for (; $preferred <= parent::ID_ACCOUNT_UPPER_BOUNDARY; $preferred++)
        {
            if ( ! isset($this->accounts[$preferred]))
            {
                return (string)$preferred;
            }
        }
        return FALSE;
    }

    /**
     * Setup PayNow Account
     * @param string $proxy_type
     * @param string $proxy_value
     * @param string $editable (optional)
     * @param string $expiry (optional)
     * @return array Status
     */
    public function set_account_paynow($proxy_type, $proxy_value, $editable = parent::PAYNOW_AMOUNT_EDITABLE_TRUE, $expiry = parent::PAYNOW_DEFAULT_EXPIRY_DATE)
    {
        if (empty($this->country_code))
        {
            $this->set_country(parent::COUNTRY_SG);
        } else if (parent::COUNTRY_SG != $this->country_code)
        {
            return $this->return_status(FALSE, self::STATUS_IN_WRONG_COUNTRY, parent::PAYNOW_CHANNEL);
        }
        $id = $this->assign_account_id(parent::ID_ACCOUNT_START_INDEX);
        if (FALSE == $id)
        {
            return $this->return_status(FALSE, self::STATUS_NO_ACCOUNT_ID, parent::PAYNOW_CHANNEL);
        }
        // PROXY
        if (isset($this->paynow_proxy_type[$proxy_type]))
        {
            $account[parent::PAYNOW_ID_PROXY_TYPE] = $proxy_type;
            if (parent::PAYNOW_PROXY_MOBILE == $proxy_type)
            {
                // Accept all country's phone number with country code (E.164 format)
                if (preg_match('/^\+(\d){8,15}$/', $proxy_value))
                {
                    $account[parent::PAYNOW_ID_PROXY_VALUE] = $proxy_value;
                } else
                {
                    return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PAYNOW_CHANNEL . '.' . parent::PAYNOW_ID_PROXY_VALUE);
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
                    $account[parent::PAYNOW_ID_PROXY_VALUE] = $proxy_value;
                } else
                {
                    return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PAYNOW_CHANNEL . '.' . parent::PAYNOW_ID_PROXY_VALUE);
                }
            }
        } else
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PAYNOW_CHANNEL . '.' . parent::PAYNOW_ID_PROXY_TYPE);
        }
        // EDITABLE
        if ($editable == parent::PAYNOW_AMOUNT_EDITABLE_TRUE || $editable == parent::PAYNOW_AMOUNT_EDITABLE_FALSE)
        {
            $account[parent::PAYNOW_ID_AMOUNT_EDITABLE] = $editable;
        } else
        {
            return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PAYNOW_CHANNEL . '.' . parent::PAYNOW_ID_AMOUNT_EDITABLE);
        }
        // EXPIRY
        if (!empty($expiry))
        {
            $expiry_formatted = $this->format_date_with_dash($expiry, TRUE);
            if (FALSE == $expiry_formatted)
            {
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PAYNOW_CHANNEL . '.' . parent::PAYNOW_ID_EXPIRY_DATE);
            } else
            {
                $account[parent::PAYNOW_ID_EXPIRY_DATE] = $expiry_formatted;
            }
        }
        $this->accounts[$id] = $account;
        return $this->return_status(TRUE);
    }

    /**
     * Setup SGQR account
     * @param $id_no
     * @param $version
     * @param $postal_code
     * @param $level
     * @param $unit
     * @param $misc
     * @param $version_date
     * @return array Status
     */
    public function set_account_sgqr($id_no, $version, $postal_code, $level, $unit, $misc, $version_date)
    {
        if (empty($this->country_code))
        {
            $this->set_country(parent::COUNTRY_SG);
        } else if (parent::COUNTRY_SG != $this->country_code)
        {
            return $this->return_status(FALSE, self::STATUS_IN_WRONG_COUNTRY, parent::SGQR_CHANNEL);
        }
        $id = $this->assign_account_id(parent::ID_ACCOUNT_UPPER_BOUNDARY);
        if (FALSE == $id)
        {
            return $this->return_status(FALSE, self::STATUS_NO_ACCOUNT_ID, parent::SGQR_CHANNEL);
        }
        $this->accounts[$id] = [
            parent::SGQR_ID_REVERSE_DOMAIN => parent::SGQR_CHANNEL,
            parent::SGQR_ID_IDENTIFICATION_NUMBER => $id_no,
            parent::SGQR_ID_VERSION => $version,
            parent::SGQR_ID_POSTAL_CODE => $postal_code,
            parent::SGQR_ID_LEVEL => $level,
            parent::SGQR_ID_UNIT_NUMBER => $unit,
            parent::SGQR_ID_MISC => $misc,
            parent::SGQR_ID_VERSION_DATE => date('Ymd', strtotime($version_date))
        ];
        return $this->return_status(TRUE);
    }

    /**
     * Setup FavePay account
     * @param $fave_id
     * @return array Status
     */
    public function set_account_sg_favepay($fave_id)
    {
        if (empty($this->country_code))
        {
            $this->set_country(parent::COUNTRY_SG);
        } else if (parent::COUNTRY_SG != $this->country_code)
        {
            return $this->return_status(FALSE, self::STATUS_IN_WRONG_COUNTRY, parent::FAVE_CHANNEL_NAME);
        }
        $id = $this->assign_account_id(parent::ID_ACCOUNT_START_INDEX);
        if (FALSE == $id)
        {
            return $this->return_status(FALSE, self::STATUS_NO_ACCOUNT_ID, parent::FAVE_CHANNEL_NAME);
        }
        // @todo: verify Fave ID
        $this->accounts[$id] = [
            parent::FAVE_ID_REVERSE_DOMAIN => parent::FAVE_CHANNEL,
            parent::FAVE_ID_URL => parent::FAVE_URL . $fave_id
        ];
        return $this->return_status(TRUE);
    }

    /**
     * Setup PromptPay account
     * @param string $proxy_type
     * @param string $proxy_value
     * @return array Status
     */
    public function set_account_promptpay($proxy_type, $proxy_value)
    {
        if (empty($this->country_code))
        {
            $this->set_country(parent::COUNTRY_TH);
        } else if (parent::COUNTRY_TH != $this->country_code)
        {
            return $this->return_status(FALSE, self::STATUS_IN_WRONG_COUNTRY, parent::PROMPTPAY_CHANNEL_NAME);
        }
        $id = $this->assign_account_id(parent::PROMPTPAY_PREFER_ID);
        if (FALSE == $id)
        {
            return $this->return_status(FALSE, self::STATUS_NO_ACCOUNT_ID, parent::PROMPTPAY_CHANNEL_NAME);
        }
        $account[parent::PROMPTPAY_ID_APP_ID] = parent::PROMPTPAY_CHANNEL;
        switch ($proxy_type)
        {
            case parent::PROMPTPAY_PROXY_MOBILE:
                if (preg_match('/^0066(6|8|9)(\d{8})$/', $proxy_value))
                {
                    $account[parent::PROMPTPAY_ID_MOBILE] = $proxy_value;
                } else if (preg_match('/^\+66(6|8|9)(\d{8})$/', $proxy_value))
                {
                    $account[parent::PROMPTPAY_ID_MOBILE] = str_replace('+66', '0066', $proxy_value);
                } else
                {
                    return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PROMPTPAY_CHANNEL_NAME . '.' . parent::PROMPTPAY_ID_MOBILE);
                }
                break;
            case parent::PROMPTPAY_PROXY_TAX_ID:
                if (preg_match('/^\d{13}$/', $proxy_value))
                {
                    $account[parent::PROMPTPAY_ID_TAX_ID] = $proxy_value;
                } else if (preg_match('/^\d\-\d{4}\-\d{5}\-\d{2}\-\d$/', $proxy_value))
                {
                    $account[parent::PROMPTPAY_ID_TAX_ID] = str_replace('-', '', $proxy_value);
                } else
                {
                    return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PROMPTPAY_CHANNEL_NAME . '.' . parent::PROMPTPAY_ID_TAX_ID);
                }
                break;
            case parent::PROMPTPAY_PROXY_EWALLET_ID:
                if ($this->validate_ans_charset_len($proxy_value, parent::LENGTH_TWENTY_FIVE))
                {
                    $account[parent::PROMPTPAY_ID_EWALLET_ID] = $proxy_value;
                } else
                {
                    return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PROMPTPAY_CHANNEL_NAME . '.' . parent::PROMPTPAY_ID_EWALLET_ID);
                }
                break;
            default:
                return $this->return_status(FALSE, self::STATUS_INVALID_VALUE, parent::PROMPTPAY_CHANNEL_NAME . '.' . parent::PROMPTPAY_CHANNEL_NAME);
        }
        $this->accounts[$id] = $account;
        return $this->return_status(TRUE);
    }

    /* | --------------------------------------------------------------------------------------------------------
       | GENERATE QR
       | -------------------------------------------------------------------------------------------------------- */
    /**
     * Generate the QR code string
     * @return array|string Return array if error, otherwise, the string for generating QR code
     */
    public function generate_qr_string()
    {
        // 00 payload format indicator - mandatory - set in __construct()
        $string = parent::ID_PAYLOAD_FORMAT_INDICATOR . self::DIGIT_OH_TWO . $this->payload_format_indicator;
        // 01 point of initiation - optional - set in set_price() or set_point_of_initiation_static()
        if ( ! empty($this->point_of_initiation))
        {
            $string .= parent::ID_POINT_OF_INITIATION . self::DIGIT_OH_TWO . $this->point_of_initiation;
        }
        // 02-51 accounts
        // todo: loop through accounts
        // 52 merchant category code - mandatory - set in set_merchant_info()
        if ( ! empty($this->merchant_category_code))
        {
            $string .= parent::ID_MERCHANT_CATEGORY_CODE . self::DIGIT_OH_FOUR . $this->merchant_category_code;
        } else
        {
            $this->add_message(parent::ID_MERCHANT_CATEGORY_CODE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_MERCHANT_CATEGORY_CODE);
        }
        // 53 transaction currency - mandatory - set in set_country()
        if ( ! empty($this->transaction_currency))
        {
            $string .= parent::ID_TRANSACTION_CURRENCY . self::DIGIT_OH_THREE . $this->transaction_currency;
        } else
        {
            $this->add_message(parent::ID_TRANSACTION_CURRENCY, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_TRANSACTION_CURRENCY);
        }
        // 54 transaction amount - conditional - set in set_price()
        if ($this->point_of_initiation == parent::POINT_OF_INITIATION_DYNAMIC)
        {
            if ( ! empty($this->transaction_amount))
            {
                $string .= parent::ID_TRANSACTION_AMOUNT . sprintf('%02d', strlen($this->transaction_amount)) . $this->transaction_amount;
            } else
            {
                $this->add_message(parent::ID_TRANSACTION_AMOUNT, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_TRANSACTION_AMOUNT);
            }
        }
        // 55-57 tip or convenient fee
        if ( ! empty($this->tip_or_convenience_fee_indicator))
        {
            $string .= parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR . self::DIGIT_OH_TWO . $this->tip_or_convenience_fee_indicator;
            if (parent::FEE_INDICATOR_CONVENIENCE_FEE_FIXED == $this->tip_or_convenience_fee_indicator)
            {
                if ( ! empty($this->convenience_fee_fixed))
                {
                    $string .= parent::ID_VALUE_OF_FEE_FIXED . sprintf('%02d', strlen($this->convenience_fee_fixed)) . $this->convenience_fee_fixed;
                } else
                {
                    $this->add_message(parent::ID_VALUE_OF_FEE_FIXED, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_VALUE_OF_FEE_FIXED);
                }
            } else if (parent::FEE_INDICATOR_CONVENIENCE_FEE_FIXED == $this->tip_or_convenience_fee_indicator)
            {
                if ( ! empty($this->convenience_fee_percentage))
                {
                    $string .= parent::ID_VALUE_OF_FEE_PERCENTAGE . sprintf('%02d', strlen($this->convenience_fee_percentage)) . $this->convenience_fee_percentage;
                } else
                {
                    $this->add_message(parent::ID_VALUE_OF_FEE_PERCENTAGE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_VALUE_OF_FEE_PERCENTAGE);
                }
            }
        }
        // 58 country code - mandatory - set in set_country()
        if ( ! empty($this->country_code))
        {
            $string .= parent::ID_COUNTRY_CODE . self::DIGIT_OH_TWO . $this->country_code;
        } else
        {
            $this->add_message(parent::ID_COUNTRY_CODE, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_COUNTRY_CODE);
        }
        // 59 merchant name - mandatory - set in set_merchant_info()
        if ( ! empty($this->merchant_name))
        {
            $string .= parent::ID_MERCHANT_NAME . sprintf('%02d', strlen($this->merchant_name)) . $this->merchant_name;
        } else
        {
            $this->add_message(parent::ID_MERCHANT_NAME, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_MERCHANT_NAME);
        }
        // 60 merchant city - mandatory - set in set_merchant_info()
        if ( ! empty($this->merchant_city))
        {
            $string .= parent::ID_MERCHANT_CITY . sprintf('%02d', strlen($this->merchant_city)) . $this->merchant_city;
        } else
        {
            $this->add_message(parent::ID_MERCHANT_CITY, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_MISSING_FIELD, parent::ID_MERCHANT_CITY);
        }
        // 61 postal code - optional - set in set_merchant_info()
        if ( ! empty($this->merchant_postal_code))
        {
            $string .= parent::ID_MERCHANT_POSTAL_CODE . sprintf('%02d', strlen($this->merchant_postal_code)) . $this->merchant_postal_code;
        }
        // 62 - additional data - optional
        if ( ! empty($this->additional_fields))
        {
            $additional_fields = implode('', $this->additional_fields);
            if (99 < strlen($additional_fields))
            {
                // skip
            } else
            {
                $string .= parent::ID_ADDITIONAL_DATA_FIELDS . sprintf('%02d', strlen($additional_fields)) . $additional_fields;
            }
        }
        // 64 - not supports - skip
        // 63 CRC
        $string .= parent::ID_CRC . self::DIGIT_OH_FOUR;
        $string .= $this->CRC16HexDigest($string);
        $this->qr_string = $string;
        return $this->qr_string;
    }

}