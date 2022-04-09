<?php

namespace EMVQR;

class EmvTipFeeIndicator {

    const ID = '55';
    const LENGTH = '02';
    const TIP_INDICATOR = '01';
    const TIP_FEE_FIXED = '02';
    const TIP_FEE_PERCENT = '03';
    public $id;
    public $value;
    public $description;
    public $error;
    private $indicators = [
        '01' => 'Prompt consumer to enter a tip',
        '02' => 'The transaction amount shall include a fixed fee',
        '03' => 'The transaction amount shall include a fee in percentage',
    ];

    /**
     * EmvTipFeeIndicator constructor.
     * @param null|string $code
     */
    public function __construct($code = NULL)
    {
        if ( ! is_null($code))
        {
            $this->id = self::ID;
            if (isset($this->indicators[$code]))
            {
                $this->value = $code;
                $this->description = strtoupper($this->indicators[$code]);
                $this->error = FALSE;
            } else
            {
                $this->error = TRUE;
            }
        }
    }

    /**
     * Generate the string for Tip or Fee Indicator
     * @param float|int $code The code
     * @return string
     */
    public function generate($code)
    {
        if (isset($this->indicators[$code]))
        {
            return self::ID . self::LENGTH . $code;
        }
    }
}