<?php

namespace EMVQR;

class EmvLanguageTemplate {

    const ID = '64';
    const ID_LANGUAGE_CODE = '00';
    const KEY_LANGUAGE_CODE = 'language_code';
    const ID_MERCHANT_NAME = '01';
    const KEY_MERCHANT_NAME = 'merchant_name';
    const ID_MERCHANT_CITY = '02';
    const KEY_MERCHANT_CITY = 'merchant_city';
    private $language_codes = ['TH', 'ZH', 'ID', 'MS'];
    /**
     * Generate the string for Language Template
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
                case self::KEY_LANGUAGE_CODE:
                    $final_array[self::ID_LANGUAGE_CODE] = $this->check_language($value);
                    break;
                case self::KEY_MERCHANT_NAME:
                    $final_array[self::ID_MERCHANT_NAME] = $this->check_length($value, 25);
                    break;
                case self::KEY_MERCHANT_CITY:
                    $final_array[self::ID_MERCHANT_CITY] = $this->check_length($value, 15);
                    break;
            }
        }
        if ( ! empty($final_array[self::ID_LANGUAGE_CODE]) && ! empty($final_array[self::ID_MERCHANT_NAME]))
        {
            $final_string = '';
            foreach ($final_array as $key => $item)
            {
                $length = mb_strlen($item);
                $final_string .= $key . sprintf('%02d', $length) . $item;
            }
            $final_length = mb_strlen($final_string);
            if (99 >= $final_length)
            {
                return self::ID . sprintf('%02d', $final_length) . $final_string;
            }
        }
    }

    /**
     * Verify whether the language code is valid for this QR code or not
     * @param $value
     * @return string|false
     */
    private function check_language($value)
    {
        if (in_array($value, $this->language_codes))
        {
            return $value;
        }
        return FALSE;
    }

    /**
     * Check the length of the input string
     * @param string $value
     * @param int $max_length
     * @return string|false
     */
    private function check_length($value, $max_length)
    {
        $length = mb_strlen($value);
        if ($max_length >= $length)
        {
            return $value;
        }
        return FALSE;
    }
}