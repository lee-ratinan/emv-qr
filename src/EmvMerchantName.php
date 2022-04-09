<?php


namespace EMVQR;


class EmvMerchantName {

    const ID = '59';
    const MAX_LENGTH = 25;
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvMerchantName constructor.
     * @param null|string $name
     */
    public function __construct($name = NULL)
    {
        if ( ! is_null($name))
        {
            $this->id = self::ID;
            $length = strlen($name);
            if (preg_match('/^[\x20-\x7E]+$/', $name) && self::MAX_LENGTH >= $length)
            {
                $this->value = $name;
                $this->description = 'MERCHANT NAME';
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Merchant Name
     * @param string $name The merchant name
     * @return string
     */
    public function generate($name)
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH | FILTER_FLAG_STRIP_LOW);
        $name = trim($name);
        $length = strlen($name);
        if (preg_match('/^[\x20-\x7E]+$/', $name) && self::MAX_LENGTH >= $length)
        {
            return self::ID . sprintf('%02d', $length) . $name;
        }
    }

}