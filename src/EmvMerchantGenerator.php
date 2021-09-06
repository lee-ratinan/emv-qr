<?php
namespace EMVQR;
require_once 'EmvMerchant.php';

/**
 * Class EmvMerchantGenerator
 * @package EMVQR
 */
class EmvMerchantGenerator extends EmvMerchant {

	/**
	 * EmvMerchantDecoder constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}


	private function generate()
	{
		$string = self::ID_PAYLOAD_FORMAT_INDICATOR . sprintf('%02d', strlen($this->payload_format_indicator)) . $this->payload_format_indicator;
		$string .= self::ID_POINT_OF_INITIATION . sprintf('%02d', strlen($this->point_of_initiation)) . $this->point_of_initiation;
		// todo: accounts

		if (!empty($this->merchant_category_code))
		{
			$string .= self::ID_MERCHANT_CATEGORY_CODE . sprintf('%02d', strlen($this->merchant_category_code)) . $this->merchant_category_code;
		}
		if (!empty($this->transaction_currency))
		{
			$string .= self::ID_TRANSACTION_CURRENCY . sprintf('%02d', strlen($this->transaction_currency)) . $this->transaction_currency;
		}
		if (!empty($this->transaction_amount))
		{
			$string .= self::ID_TRANSACTION_AMOUNT . sprintf('%02d', strlen($this->transaction_amount)) . $this->transaction_amount;
		}
		if (!empty($this->country_code))
		{
			$string .= self::ID_COUNTRY_CODE . sprintf('%02d', strlen($this->country_code)) . $this->country_code;
		}
		if (!empty($this->merchant_name))
		{
			$string .= self::ID_MERCHANT_NAME . sprintf('%02d', strlen($this->merchant_name)) . $this->merchant_name;
		}
		if (!empty($this->merchant_city))
		{
			$string .= self::ID_MERCHANT_CITY . sprintf('%02d', strlen($this->merchant_city)) . $this->merchant_city;
		}
		if (!empty($this->merchant_postal_code))
		{
			$string .= self::ID_MERCHANT_POSTAL_CODE . sprintf('%02d', strlen($this->merchant_postal_code)) . $this->merchant_postal_code;
		}
		if (!empty($this->additional_fields))
		{
			$additional_fields = '';
			if (!empty($this->additional_fields['bill_number']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_BILL_NUMBER . sprintf('%02d', strlen($this->additional_fields['bill_number'])) . $this->additional_fields['bill_number'];
			}
			if (!empty($this->additional_fields['mobile_number']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_MOBILE_NUMBER . sprintf('%02d', strlen($this->additional_fields['mobile_number'])) . $this->additional_fields['mobile_number'];
			}
			if (!empty($this->additional_fields['store_label']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_STORE_LABEL . sprintf('%02d', strlen($this->additional_fields['store_label'])) . $this->additional_fields['store_label'];
			}
			if (!empty($this->additional_fields['loyalty_number']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_LOYALTY_NUMBER . sprintf('%02d', strlen($this->additional_fields['loyalty_number'])) . $this->additional_fields['loyalty_number'];
			}
			if (!empty($this->additional_fields['reference_label']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_REFERENCE_LABEL . sprintf('%02d', strlen($this->additional_fields['reference_label'])) . $this->additional_fields['reference_label'];
			}
			if (!empty($this->additional_fields['customer_label']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_CUSTOMER_LABEL . sprintf('%02d', strlen($this->additional_fields['customer_label'])) . $this->additional_fields['customer_label'];
			}
			if (!empty($this->additional_fields['terminal_label']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_TERMINAL_LABEL . sprintf('%02d', strlen($this->additional_fields['terminal_label'])) . $this->additional_fields['terminal_label'];
			}
			if (!empty($this->additional_fields['purpose_of_transaction']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION . sprintf('%02d', strlen($this->additional_fields['purpose_of_transaction'])) . $this->additional_fields['purpose_of_transaction'];
			}
			if (!empty($this->additional_fields['customer_data_request']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST . sprintf('%02d', strlen($this->additional_fields['customer_data_request'])) . $this->additional_fields['customer_data_request'];
			}
			if (!empty($this->additional_fields['merchant_tax_id']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID . sprintf('%02d', strlen($this->additional_fields['merchant_tax_id'])) . $this->additional_fields['merchant_tax_id'];
			}
			if (!empty($this->additional_fields['merchant_channel']))
			{
				$additional_fields .= self::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL . sprintf('%02d', strlen($this->additional_fields['merchant_channel'])) . $this->additional_fields['merchant_channel'];
			}
			if (!empty($additional_fields))
			{
				$string .= self::ID_ADDITIONAL_DATA_FIELDS . sprintf('%02d', strlen($additional_fields)) . $additional_fields;
			}
		}
		$string .= self::ID_CRC . self::CRC_LENGTH;
		$string .= $this->CRC16HexDigest($string);
		$this->qr_string = $string;
		return $string;
	}

	/**
	 * @param array $accounts
	 * @param string $merchant_category_code (validated)
	 * @param string $merchant_name (check length)
	 * @param string $postal_code (validated)
	 * @param float $transaction_amount (validated)
	 * @return false|string
	 */
	public function create_code_sg($accounts = [], $merchant_category_code = self::MERCHANT_CATEGORY_CODE_GENERIC, $merchant_name = '', $postal_code = '', $transaction_amount = 0.0)
	{
		$this->mode = self::MODE_GENERATE;
		$this->payload_format_indicator = self::PAYLOAD_FORMAT_INDICATOR_VALUE;
		$float_transaction_amount = floatval($transaction_amount);
		if (0.0 < $float_transaction_amount)
		{
			$this->point_of_initiation = self::POINT_OF_INITIATION_DYNAMIC;
			$this->transaction_amount = number_format($float_transaction_amount, 2, '.', '');
		} else
		{
			$this->point_of_initiation = self::POINT_OF_INITIATION_STATIC;
		}
		if (empty($accounts))
		{
			return false;
		}
		$this->accounts = $accounts;
		if (4 != strlen($merchant_category_code))
		{
			return false;
		}
		$this->merchant_category_code = $merchant_category_code;
		$this->transaction_currency = self::CURRENCY_SGD;
		$transaction_amount = number_format($transaction_amount, 2, '.', '');
		if (13 < strlen($transaction_amount))
		{
			return false;
		}
		$this->transaction_amount = $transaction_amount;
		$this->country_code = self::COUNTRY_SG;
		$merchant_name = filter_var($merchant_name, FILTER_SANITIZE_STRING); // todo: must be only 'ans', check valid character set
		if (25 < strlen($merchant_name))
		{
			return false;
		}
		$this->merchant_name = $merchant_name;
		$this->merchant_city = self::MERCHANT_CITY_SG;
		if (!preg_match('/\d{6}/', $postal_code))
		{
			$postal_code = null;
		}
		$this->merchant_postal_code = $postal_code;
		//$this->additional_fields = [];
		$this->qr_string = '';
		return $this->generate();
	}

	public function create_code_th($accounts = [], $merchant_category_code = self::MERCHANT_CATEGORY_CODE_GENERIC, $merchant_name = null, $postal_code = null, $transaction_amount = null)
	{

	}

	public function create_code_my($accounts = [], $merchant_category_code = self::MERCHANT_CATEGORY_CODE_GENERIC, $merchant_name = null, $postal_code = null, $transaction_amount = null)
	{

	}

	public function create_code_id($accounts = [], $merchant_category_code = self::MERCHANT_CATEGORY_CODE_GENERIC, $merchant_name = null, $postal_code = null, $transaction_amount = null)
	{

	}
}