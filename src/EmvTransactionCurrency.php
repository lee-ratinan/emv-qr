<?php


namespace EMVQR;


class EmvTransactionCurrency {

    const ID = '53';
    const LENGTH = '03';
    public $id;
    public $value;
    public $description;
    public $error;
    private $currencies = [
        '360' => 'IDR',
        '458' => 'MYR',
        '702' => 'SGD',
        '764' => 'THB',
    ];

    /**
     * EmvTransactionCurrency constructor.
     * @param null|string $code
     */
    public function __construct($code = NULL)
    {
        if ( ! is_null($code))
        {
            $this->id = self::ID;
            if (isset($this->currencies[$code]))
            {
                $this->value = $code;
                $this->description = 'TRANSACTION CURRENCY: ' . $this->currencies[$code];
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Transaction Currency
     * @param string $code The currency of the transaction in ISO4217 format, it could be either the 3-digit or the 3-character representation
     * @return string
     */
    public function generate($code)
    {
        foreach ($this->currencies as $numeric => $string)
        {
            if ($code == $numeric || $code == $string)
            {
                return self::ID.self::LENGTH.$numeric;
            }
        }
    }

}