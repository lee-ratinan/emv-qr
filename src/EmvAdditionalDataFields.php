<?php

namespace EMVQR;

class EmvAdditionalDataFields {

    const ID_ADDITIONAL_DATA = '62';
    const KEY_BILL_NUMBER = 'bill_number';
    const ID_BILL_NUMBER = '01';
    const KEY_MOBILE_NUMBER = 'mobile_number';
    const ID_MOBILE_NUMBER = '02';
    const KEY_STORE_LABEL = 'store_label';
    const ID_STORE_LABEL = '03';
    const KEY_LOYALTY_NUMBER = 'loyalty_number';
    const ID_LOYALTY_NUMBER = '04';
    const KEY_REFERENCE_LABEL = 'reference_label';
    const ID_REFERENCE_LABEL = '05';
    const KEY_CUSTOMER_LABEL = 'customer_label';
    const ID_CUSTOMER_LABEL = '06';
    const KEY_TERMINAL_LABEL = 'terminal_label';
    const ID_TERMINAL_LABEL = '07';
    const KEY_PURPOSE_OF_TRANSACTION = 'purpose_of_transaction';
    const ID_PURPOSE_OF_TRANSACTION = '08';
    const KEY_ADDITIONAL_DATA_REQUEST = 'additional_data_request';
    const ID_ADDITIONAL_DATA_REQUEST = '09';
    const KEY_MERCHANT_TAX_ID = 'merchant_tax_id';
    const ID_MERCHANT_TAX_ID = '10';
    const KEY_MERCHANT_CHANNEL = 'merchant_channel';
    const ID_MERCHANT_CHANNEL = '11';

    const ADDITIONAL_DATA_REQUEST_MOBILE = 'MOBILE';
    const ADDITIONAL_DATA_REQUEST_EMAIL = 'EMAIL';
    const ADDITIONAL_DATA_REQUEST_ADDRESS = 'ADDRESS';

    const MERCHANT_CHANNEL_KEY_MEDIA = 'media';
    const MERCHANT_CHANNEL_KEY_MEDIA_PRINT_STICKER = 0;
    const MERCHANT_CHANNEL_KEY_MEDIA_PRINT_BILL = 1;
    const MERCHANT_CHANNEL_KEY_MEDIA_PRINT_POSTER = 2;
    const MERCHANT_CHANNEL_KEY_MEDIA_PRINT_OTHER = 3;
    const MERCHANT_CHANNEL_KEY_MEDIA_SCREEN_POS = 4;
    const MERCHANT_CHANNEL_KEY_MEDIA_SCREEN_WEBSITE = 5;
    const MERCHANT_CHANNEL_KEY_MEDIA_SCREEN_APP = 6;
    const MERCHANT_CHANNEL_KEY_MEDIA_SCREEN_OTHER = 7;

    const MERCHANT_CHANNEL_KEY_LOCATION = 'location';
    const MERCHANT_CHANNEL_KEY_LOCATION_AT_PREMISE = 0;
    const MERCHANT_CHANNEL_KEY_LOCATION_NOT_AT_PREMISE = 1;
    const MERCHANT_CHANNEL_KEY_LOCATION_REMOTE_COMMERCE = 2;
    const MERCHANT_CHANNEL_KEY_LOCATION_OTHER = 3;

    const MERCHANT_CHANNEL_KEY_PRESENCE = 'presence';
    const MERCHANT_CHANNEL_KEY_PRESENCE_ATTENDED_POI = 0;
    const MERCHANT_CHANNEL_KEY_PRESENCE_UNATTENDED = 1;
    const MERCHANT_CHANNEL_KEY_PRESENCE_SEMI_ATTENDED = 2;
    const MERCHANT_CHANNEL_KEY_PRESENCE_OTHER = 3;

    /**
     * Generate the string for Additional Info
     * @param array $array
     * @return string
     */
    public function generate($array)
    {
        $final_array = [];
        foreach ($array as $key => $value)
        {
            switch ($key)
            {
                case self::KEY_BILL_NUMBER:
                    $final_array[self::ID_BILL_NUMBER] = $this->validate_input($value, self::ID_BILL_NUMBER, 25);
                    break;
                case self::KEY_MOBILE_NUMBER:
                    $final_array[self::ID_MOBILE_NUMBER] = $this->validate_input($value, self::ID_MOBILE_NUMBER, 25);
                    break;
                case self::KEY_STORE_LABEL:
                    $final_array[self::ID_STORE_LABEL] = $this->validate_input($value, self::ID_STORE_LABEL, 25);
                    break;
                case self::KEY_LOYALTY_NUMBER:
                    $final_array[self::ID_LOYALTY_NUMBER] = $this->validate_input($value, self::ID_LOYALTY_NUMBER, 25);
                    break;
                case self::KEY_REFERENCE_LABEL:
                    $final_array[self::ID_REFERENCE_LABEL] = $this->validate_input($value, self::ID_REFERENCE_LABEL, 25);
                    break;
                case self::KEY_CUSTOMER_LABEL:
                    $final_array[self::ID_CUSTOMER_LABEL] = $this->validate_input($value, self::ID_CUSTOMER_LABEL, 25);
                    break;
                case self::KEY_TERMINAL_LABEL:
                    $final_array[self::ID_TERMINAL_LABEL] = $this->validate_input($value, self::ID_TERMINAL_LABEL, 25);
                    break;
                case self::KEY_PURPOSE_OF_TRANSACTION:
                    $final_array[self::ID_PURPOSE_OF_TRANSACTION] = $this->validate_input($value, self::ID_PURPOSE_OF_TRANSACTION, 25);
                    break;
                case self::KEY_ADDITIONAL_DATA_REQUEST:
                    $final_array[self::ID_ADDITIONAL_DATA_REQUEST] = $this->generate_additional_data_request($value);
                    break;
                case self::KEY_MERCHANT_TAX_ID:
                    $final_array[self::ID_MERCHANT_TAX_ID] = $this->validate_input($value, self::ID_MERCHANT_TAX_ID, 20);
                    break;
                case self::KEY_MERCHANT_CHANNEL:
                    $final_array[self::ID_MERCHANT_CHANNEL] = $this->generate_merchant_channel($value);
                    break;
            }
        }
        ksort($final_array);
        $final_string = implode('', $final_array);
        $final_length = strlen($final_string);
        if (99 >= $final_length)
        {
            return self::ID_ADDITIONAL_DATA . sprintf('%02d', $final_length) . $final_string;
        }
    }

    /**
     * Validate the input
     * @param $value
     * @param $id
     * @param $max_length
     * @return string
     */
    private function validate_input($value, $id, $max_length)
    {
        $value = filter_var($value, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        $value = trim($value);
        $length = strlen($value);
        if (preg_match('/^[\x20-\x7E]+$/', $value) && $max_length >= $length)
        {
            return $id . sprintf('%02d', $length) . $value;
        }
    }

    /**
     * Generate Additional Data Request field
     * @param array $values
     * @return string
     */
    private function generate_additional_data_request($values)
    {
        $field_array = [];
        if (is_array($values))
        {
            foreach ($values as $field)
            {
                $field = strtoupper($field);
                if ($field == 'ADDRESS')
                {
                    $field_array[] = 'A';
                } else if ($field == 'MOBILE')
                {
                    $field_array[] = 'M';
                } else if ($field == 'EMAIL')
                {
                    $field_array[] = 'E';
                }
            }
        }
        if ( ! empty($field_array))
        {
            sort($field_array);
            $final_string = implode('', $field_array);
            $length = strlen($final_string);
            return self::ID_ADDITIONAL_DATA_REQUEST . sprintf('%02d', $length) . $final_string;
        }
        return '';
    }

    /**
     * Generate Merchant Channel field
     * @param array $value Array of integer
     * @return string
     */
    private function generate_merchant_channel($value)
    {
        $final_string = '';
        if (in_array($value['media'], [0, 1, 2, 3, 4, 5, 6, 7]))
        {
            $final_string .= $value['media'];
            if (in_array($value['location'], [0, 1, 2, 3]))
            {
                $final_string .= $value['location'];
                if (in_array($value['presence'], [0, 1, 2, 3]))
                {
                    $final_string .= $value['presence'];
                    return self::ID_MERCHANT_CHANNEL . '03' . $final_string;
                }
            }
        }
        return '';
    }
}