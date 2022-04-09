<?php

namespace EMVQR;

class EmvPostalCode {

    const ID = '61';
    const MAX_LENGTH = 10;
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvPostalCode constructor.
     * @param null|string $postal_code
     */
    public function __construct($postal_code = NULL)
    {
        if ( ! is_null($postal_code))
        {
            $this->id = self::ID;
            $length = strlen($postal_code);
            if (preg_match('/^[\x20-\x7E]+$/', $postal_code) && self::MAX_LENGTH >= $length)
            {
                $this->value = $postal_code;
                $this->description = 'POSTAL CODE';
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Postal Code
     * @param string $postal_code The postal code
     * @return string
     */
    public function generate($postal_code)
    {
        $postal_code = filter_var($postal_code, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        $postal_code = trim($postal_code);
        $length = strlen($postal_code);
        if (preg_match('/^[\x20-\x7E]+$/', $postal_code) && self::MAX_LENGTH >= $length)
        {
            return self::ID . sprintf('%02d', $length) . $postal_code;
        }
    }

}