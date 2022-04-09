<?php

namespace EMVQR;

class EmvPointOfInitiation {

    const ID = '01';
    const LENGTH = '02';
    const TYPE_STATIC = '11';
    const TYPE_DYNAMIC = '12';
    const STATIC_DESCRIPTION = 'STATIC: THE QR CODE CAN BE USED FOR MULTIPLE TRANSACTION';
    const DYNAMIC_DESCRIPTION = 'DYNAMIC: THE QR CODE CAN ONLY BE USED FOR ONE TRANSACTION';
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvPayLoadFormatIndicator constructor.
     * @param null|string $string
     */
    public function __construct($string = NULL)
    {
        if ( ! is_null($string))
        {
            $this->id = self::ID;
            if (self::TYPE_STATIC == $string)
            {
                $this->value = self::TYPE_STATIC;
                $this->description = self::STATIC_DESCRIPTION;
            } else
            {
                if (self::TYPE_DYNAMIC == $string)
                {
                    $this->value = self::TYPE_DYNAMIC;
                    $this->description = self::DYNAMIC_DESCRIPTION;
                } else
                {
                    $this->error = TRUE;
                }
            }
        }
    }

    /**
     * Generate the string for Point of Initiation Method
     * @param string $type The QR code type must be one of the constants of this class: TYPE_STATIC or TYPE_DYNAMIC
     * @return string
     */
    public function generate($type)
    {
        if (in_array($type, [self::TYPE_STATIC, self::TYPE_DYNAMIC]))
        {
            return self::ID . self::LENGTH . $type;
        }
    }

}