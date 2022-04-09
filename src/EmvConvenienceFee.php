<?php

namespace EMVQR;

class EmvConvenienceFee {

    const ID_FIXED = '56';
    const ID_PERCENTAGE = '57';
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvConvenienceFee constructor.
     * @param null|string $value
     * @param null|string $id
     */
    public function __construct($value = NULL, $id = NULL)
    {
        if ( ! is_null($value) && in_array($id, [self::ID_FIXED, self::ID_PERCENTAGE]))
        {
            $this->id = $id;
            if (self::ID_FIXED == $id && 13 >= strlen($value) && preg_match('/^(\d+|\d+\.|\d+\.\d+)$/', $value))
            {
                $floatValue = floatval($value);
                $this->value = $value;
                $this->description = number_format($floatValue, 2, '.', ',');
                $this->error = FALSE;
            } else if (self::ID_PERCENTAGE == $id && preg_match('/^(\d+|\d+\.|\d+\.\d+)$/', $value))
            {
                $floatValue = floatval($value);
                if (0.01 > $floatValue || 99.99 < $floatValue)
                {
                    $this->error = TRUE;
                } else
                {
                    $this->value = $value;
                    $this->description = number_format($floatValue, 2, '.', ',');
                    $this->error = FALSE;
                }
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Fee Amount
     * @param string $id The ID, either ID_FIXED or ID_PERCENTAGE
     * @param float|int $amount The amount to be charged on top of the transaction amount
     * @return string
     */
    public function generate($id, $amount)
    {
        if (in_array($id, [self::ID_FIXED, self::ID_PERCENTAGE]))
        {
            $strAmount = number_format($amount, 2, '.', '');
            $strLength = strlen($strAmount);
            $maxLength = (self::ID_FIXED == $id ? 13 : 5);
            if ($maxLength >= $strLength)
            {
                return $id . sprintf('%02d', $strLength) . $strAmount;
            }
        }
    }

}