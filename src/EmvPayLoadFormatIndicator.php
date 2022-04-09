<?php

namespace EMVQR;

class EmvPayLoadFormatIndicator {

    const ID = '00';
    const LENGTH = '02';
    const VALUE = '01';
    const DESCRIPTION = 'PAYLOAD FORMAT INDICATOR, IN THIS VERSION, THE VALUE IS 01.';
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvPayLoadFormatIndicator constructor.
     * @param null $string
     */
    public function __construct($string = null)
    {
        if (! is_null($string))
        {
            $this->id = self::ID;
            if (self::VALUE == $string)
            {
                $this->value = self::VALUE;
                $this->description = self::DESCRIPTION;
                $this->error = FALSE;
            } else {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Payload Format Indicator
     * @return string
     */
    public function generate()
    {
        return self::ID.self::LENGTH.self::VALUE;
    }

}