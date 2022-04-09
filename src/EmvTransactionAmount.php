<?php


namespace EMVQR;


class EmvTransactionAmount {

    const ID = '54';
    public $id;
    public $value;
    public $description;
    public $error;

    /**
     * EmvTransactionAmount constructor.
     * @param null|string $string
     */
    public function __construct($string = NULL)
    {
        if ( ! is_null($string))
        {
            $this->id = self::ID;
            if (13 >= strlen($string) && preg_match('/^(\d+|\d+\.|\d+\.\d+)$/', $string))
            {
                $this->value = $string;
                $stringFloat = floatval($string);
                $this->description = 'TRANSACTION AMOUNT: ' . number_format($stringFloat, 2, '.', ',');
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Transaction Amount
     * @param float|int $amount The amount to be charged
     * @return string
     */
    public function generate($amount)
    {
        $strAmount = '';
        if (is_int($amount))
        {
            $strAmount = number_format($amount, 0, '.', '');
        } else if (is_float($amount))
        {
            $strAmount = number_format($amount, 2, '.', '');
        }
        $strLength = strlen($strAmount);
        if (13 >= $strLength && ! empty($strAmount))
        {
            return self::ID . sprintf('%02d', $strLength) . $strAmount;
        }
    }

}