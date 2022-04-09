<?php


namespace EMVQR;


class EmvMerchantCity {

    const ID = '60';
    const MAX_LENGTH = 15;
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvMerchantCity constructor.
     * @param null|string $city
     */
    public function __construct($city = NULL)
    {
        if ( ! is_null($city))
        {
            $this->id = self::ID;
            $length = strlen($city);
            if (preg_match('/^[\x20-\x7E]+$/', $city) && self::MAX_LENGTH >= $length)
            {
                $this->value = $city;
                $this->description = 'MERCHANT CITY';
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Merchant City
     * @param string $city The merchant city
     * @return string
     */
    public function generate($city)
    {
        $city = filter_var($city, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        $city = trim($city);
        $length = strlen($city);
        if (preg_match('/^[\x20-\x7E]+$/', $city) && self::MAX_LENGTH >= $length)
        {
            return self::ID . sprintf('%02d', $length) . $city;
        }
    }

}