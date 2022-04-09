<?php

namespace EMVQR;

class EmvCountryCode {

    const ID = '58';
    const LENGTH = '02';
    public $id;
    public $value;
    public $description;
    public $error;
    private $countries = [
        'ID' => 'INDONESIA',
        'MY' => 'MALAYSIA',
        'SG' => 'SINGAPORE',
        'TH' => 'THAILAND',
    ];
    private $currencies = [
        '360' => 'ID',
        '458' => 'MY',
        '702' => 'SG',
        '764' => 'TH',
    ];

    /**
     * EmvCountryCode constructor.
     * @param null|string $code
     */
    public function __construct($code = NULL)
    {
        if ( ! is_null($code))
        {
            $this->id = self::ID;
            if (isset($this->countries[$code]))
            {
                $this->value = $code;
                $this->description = 'MERCHANT COUNTRY: ' . $this->countries[$code];
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Country Code
     * @param string $input The country code in ISO3166 format - or hopefully, the name matches the value in the array $countries
     * @param string $currency_string The transaction currency string
     * @return string
     */
    public function generate($input, $currency_string)
    {
        $input = strtoupper($input);
        $country_code = '';
        $currency_code = substr($currency_string, -3);
        foreach ($this->countries as $code => $name)
        {
            if ($code == $input || $name == $input)
            {
                $country_code = $code;
                break;
            }
        }
        if ($this->currencies[$currency_code] == $country_code)
        {
            return self::ID . self::LENGTH . $country_code;;
        }
    }

}