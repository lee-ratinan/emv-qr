<?php

namespace EMVQR;
require_once 'EmvMerchant.php';

/**
 * Class EmvMerchantDecoder
 * @package EMVQR
 */
class EmvMerchantDecoder extends EmvMerchant {

	/**
	 * EmvMerchantDecoder constructor.
	 * @param $string string Input string read from the QR Code
	 */
	public function __construct($string)
	{
		parent::__construct();
		$this->mode = parent::MODE_DECODE;
		$this->decode($string);
	}

	/**
	 * Read and decode the EMV QR string
	 * @param $string string Input string read from the QR Code
	 * @return EmvMerchantDecoder
	 */
	protected function decode($string)
	{
		$string = str_replace(chr(194) . chr(160), ' ', $string);
		$this->qr_string = $string;
		while ( ! empty($string))
		{
			$strId = substr($string, parent::POS_ZERO, parent::LENGTH_TWO);
			$intId = intval($strId);
			$intLength = intval(substr($string, parent::POS_TWO, parent::LENGTH_TWO));
			$strValue = substr($string, parent::POS_FOUR, $intLength);
			switch ($strId)
			{
				case parent::ID_PAYLOAD_FORMAT_INDICATOR:
					$this->process_payload_format_indicator($strValue);
					break;
				case parent::ID_POINT_OF_INITIATION:
					$this->process_point_of_initiation($strValue);
					break;
				case parent::ID_MERCHANT_CATEGORY_CODE:
					$this->process_merchant_category_code($strValue);
					break;
				case parent::ID_TRANSACTION_CURRENCY:
					$this->process_currency($strValue);
					break;
				case parent::ID_TRANSACTION_AMOUNT:
					$this->process_amount($strValue); // @todo check
					break;
				case parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR:
					$this->process_fee_indicator($strValue); // @todo check
					break;
				case parent::ID_VALUE_OF_FEE_FIXED:
					$this->process_fee_value_fixed($strValue); // @todo check
					break;
				case parent::ID_VALUE_OF_FEE_PERCENTAGE:
					$this->process_fee_value_percentage($strValue); // @todo check
					break;
				case parent::ID_COUNTRY_CODE:
					$this->process_country_code($strValue); // @todo check
					break;
				case parent::ID_MERCHANT_NAME:
					$this->merchant_name = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
					break;
				case parent::ID_MERCHANT_CITY:
					$this->merchant_city = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);
					break;
				case parent::ID_MERCHANT_POSTAL_CODE:
					$this->merchant_postal_code = $this->validate_ans_charset($strValue, parent::MODE_SANITIZER);;
					break;
				case parent::ID_ADDITIONAL_DATA_FIELDS:
					$this->process_additional_data($strValue); // @todo check
					break;
				case parent::ID_CRC:
					$this->process_crc($strValue);
					break;
				case parent::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE:
					$this->process_warning(parent::ID_MERCHANT_INFORMATION_LANGUAGE_TEMPLATE, "Merchant information - language template is not supported by this library. Value read from the input: {$strValue}.");
				default:
					$this->process_accounts($intId, $strValue);
			}
			$string = substr($string, parent::LENGTH_FOUR + $intLength);
		}
		return $this;
	}

	/**
	 * Validate and assign payload format indicator to the class
	 * @param $strValue
	 */
	private function process_payload_format_indicator($strValue)
	{
		if (parent::PAYLOAD_FORMAT_INDICATOR_VALUE == $strValue)
		{
			$this->payload_format_indicator = $strValue;
		} else
		{
			$this->add_message(parent::ID_PAYLOAD_FORMAT_INDICATOR, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_PAYLOAD_FORMAT_INDICATOR_INVALID, $strValue);
		}
	}

	/**
	 * Validate and assign point of initiation to the class
	 * @param $strValue
	 */
	private function process_point_of_initiation($strValue)
	{
		switch ($strValue)
		{
			case parent::POINT_OF_INITIATION_STATIC:
				$this->point_of_initiation = parent::POINT_OF_INITIATION_STATIC_VALUE;
				break;
			case parent::POINT_OF_INITIATION_DYNAMIC:
				$this->point_of_initiation = parent::POINT_OF_INITIATION_DYNAMIC_VALUE;
				break;
			default:
				$this->add_message(parent::ID_POINT_OF_INITIATION, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_TYPE_OF_INITIATION_INVALID, $strValue);
		}
	}

	/**
	 * Assign merchant category code and its value (according to ISO 18245; if any) to the class
	 * @param $strValue
	 */
	private function process_merchant_category_code($strValue)
	{
		$this->merchant_category_code['code'] = $strValue;
		if (isset($this->merchant_category_codes[$strValue]))
		{
			$this->merchant_category_code['value'] = $this->merchant_category_codes[$strValue];
		} else
		{
			$this->merchant_category_code['value'] = parent::MERCHANT_CATEGORY_UNKNOWN;
			if (preg_match('/\d{4}/', $strValue))
			{
				$this->add_message(parent::ID_MERCHANT_CATEGORY_CODE, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_MCC_INVALID, $strValue);
			} else
			{
				$this->add_message(parent::ID_MERCHANT_CATEGORY_CODE, parent::MESSAGE_TYPE_WARNING, parent::WARNING_ID_MCC_UNKNOWN, $strValue);
			}
		}
	}

	/**
	 * Validate and assign currency to the class
	 * @param $strValue
	 */
	private function process_currency($strValue)
	{
		if (isset($this->currency_codes[$strValue]))
		{
			$this->transaction_currency = $this->currency_codes[$strValue];
		} else
		{
			$this->add_message(parent::ID_TRANSACTION_CURRENCY, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_CURRENCY_NOT_SUPPORTED, $strValue);
		}
	}

	/**
	 * Validate and assign amount to the class
	 * Matching:
	 * - \d+ An integer
	 * or
	 * - \d+\.\d+ A floating point number
	 * @param $strValue
	 */
	private function process_amount($strValue)
	{
		if ($this->validate_number($strValue, parent::MODE_VALIDATOR))
		{
			// validated
			$value = $this->validate_number($strValue, parent::MODE_PARSE_VALUE);
			if (0.0 < $value)
			{
				$this->transaction_amount = $value;
			}
		} else
		{
			// failed validation
			$this->add_message(parent::ID_TRANSACTION_AMOUNT, parent::MESSAGE_TYPE_ERROR, parent::ERROR_ID_AMOUNT_INVALID, $strValue);
		}


		if (preg_match('/^(\d+|\d+\.\d+)$/', $strValue))
		{
			$val = floatval($strValue);
			if (0 < $val)
			{
				$this->transaction_amount = (float)number_format($val, 2, '.', '');
				if (parent::POINT_OF_INITIATION_STATIC == $this->point_of_initiation)
				{
					$this->process_warning(parent::ID_TRANSACTION_AMOUNT, "Point of initiation is static ('{$this->point_of_initiation}'), but the transaction amount is set ('{$strValue}').");
				}
			} else
			{
				$this->point_of_initiation = parent::POINT_OF_INITIATION_STATIC;
				$this->process_warning(parent::ID_TRANSACTION_AMOUNT, "Transaction amount is omitted. Expected a positive number, found '{$strValue}'. Point of initiation is forced to be static.");
			}
		} else
		{
			$this->process_error(parent::ID_TRANSACTION_AMOUNT, "Transaction amount is not a number. Expected a positive number, found '{$strValue}'.");
		}
	}

	/**
	 * @param $strValue
	 */
	private function process_fee_indicator($strValue)
	{
		if (isset($this->tip_or_convenience_fee_indicators[$strValue]))
		{
			$this->tip_or_convenience_fee_indicator = $this->tip_or_convenience_fee_indicators[$strValue];
		} else
		{
			$this->process_error(parent::ID_TIP_OR_CONVENIENCE_FEE_INDICATOR, "Tip or convenience fee indicator is invalid. Expected '01', '02', or '03', found '{$strValue}'.");
		}
	}

	private function process_fee_value_fixed($strValue)
	{
		if (self::FEE_INDICATOR_CONVENIENCE_FEE_FIXED == $this->tip_or_convenience_fee_indicator)
		{
			$this->convenience_fee_fixed = $strValue;
		} else
		{
			$this->process_error(parent::ID_VALUE_OF_FEE_FIXED, "Tip or convenience fee indicator is invalid. Expected '01', '02', or '03', found '{$strValue}'.");
		}
	}

	private function process_fee_value_percentage($strValue)
	{
		if (self::FEE_INDICATOR_CONVENIENCE_FEE_PERCENTAGE == $this->tip_or_convenience_fee_indicator)
		{
			$this->convenience_fee_fixed = $strValue;
		} else
		{
			$this->process_error(parent::ID_VALUE_OF_FEE_PERCENTAGE, "Tip or convenience fee indicator is invalid. Expected '01', '02', or '03', found '{$strValue}'.");
		}
	}

	/**
	 * Validate and assign country code to the class
	 * @param $strValue
	 */
	private function process_country_code($strValue)
	{
		if (in_array($strValue, $this->country_codes))
		{
			$this->country_code = $strValue;
		} else
		{
			$this->process_error(parent::ID_COUNTRY_CODE, "Country code is not supported. Currently, this class only supports SG, TH, MY, ID, found '{$strValue}'.");
		}
	}

	/**
	 * Process additional data fields
	 * @param $string
	 */
	private function process_additional_data($string)
	{
		while ( ! empty($string))
		{
			$strId = substr($string, parent::POS_ZERO, parent::LENGTH_TWO);
			$intLength = intval(substr($string, parent::POS_TWO, parent::LENGTH_TWO));
			$strValue = substr($string, parent::POS_FOUR, $intLength);
			switch ($strId)
			{
				case parent::ID_ADDITIONAL_DATA_BILL_NUMBER:
					$this->additional_fields['bill_number'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_MOBILE_NUMBER:
					$this->additional_fields['mobile_number'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_STORE_LABEL:
					$this->additional_fields['store_label'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_LOYALTY_NUMBER:
					$this->additional_fields['loyalty_number'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_REFERENCE_LABEL:
					$this->additional_fields['reference_label'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_CUSTOMER_LABEL:
					$this->additional_fields['customer_label'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_TERMINAL_LABEL:
					$this->additional_fields['terminal_label'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION:
					$this->additional_fields['purpose_of_transaction'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST:
					$this->additional_fields['customer_data_request'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID:
					$this->additional_fields['merchant_tax_id'] = $strValue;
					break;
				case parent::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL:
					$this->additional_fields['merchant_channel'] = $strValue;
					break;
				default:
					$this->additional_fields['ID-' . $strId] = $strValue;
			}
			$string = substr($string, parent::LENGTH_FOUR + $intLength);
		}
	}

	/**
	 * Process and verify the CRC field
	 * @param $strValue
	 */
	private function process_crc($strValue)
	{
		$this->crc = $strValue;
		$checkData = substr($this->qr_string, parent::POS_ZERO, parent::POS_MINUS_FOUR);
		$newCrc = $this->CRC16HexDigest($checkData);
		if ($strValue != $newCrc)
		{
			$this->process_error(parent::ID_CRC, "Failed CRC verification. Expected '{$newCrc}', found '{$strValue}'.");
		}
	}

	/**
	 * Process account
	 * @param $intId
	 * @param $strValue
	 */
	private function process_accounts($intId, $strValue)
	{
		if (parent::ID_ACCOUNT_LOWER_BOUNDARY > $intId || parent::ID_ACCOUNT_UPPER_BOUNDARY < $intId)
		{
			$this->process_error($intId, "Account ID is out of bound. Expected between '02' and '51' inclusive, found '{$intId}'.");
			return;
		}
		$account_raw = [];
		while ( ! empty($strValue))
		{
			$strId = substr($strValue, parent::POS_ZERO, parent::LENGTH_TWO);
			$intLength = intval(substr($strValue, parent::POS_TWO, parent::LENGTH_TWO));
			$thisValue = substr($strValue, parent::POS_FOUR, $intLength);
			$account_raw[$strId] = $thisValue;
			$strValue = substr($strValue, parent::LENGTH_FOUR + $intLength);
		}
		$account_raw['00'] = strtoupper($account_raw['00']);
		switch ($account_raw['00'])
		{
			case parent::PAYNOW_CHANNEL:
				$this->accounts[] = $this->process_paynow($account_raw, $intId);
				break;
			case parent::PROMPTPAY_CHANNEL:
				$this->accounts[] = $this->process_promptpay($account_raw, $intId); // todo: check
				break;
			case parent::SGQR_CHANNEL:
				$this->accounts[] = $this->process_sgqr($account_raw, $intId); // todo: check
				break;
			case parent::FAVE_CHANNEL:
				$this->accounts[] = $this->process_favepay($account_raw, $intId); // todo: check
				break;
			case parent::DASH_CHANNEL:
				$this->accounts[] = $this->process_dash($account_raw, $intId); // todo: check
				break;
			case parent::LIQUIDPAY_CHANNEL:
				$this->accounts[] = $this->process_liquidpay($account_raw, $intId); // todo: check
				break;
			case parent::EZLINK_CHANNEL:
				$this->accounts[] = $this->process_ezlink($account_raw, $intId); // todo: check
				break;
			case parent::GRAB_CHANNEL:
				$this->accounts[] = $this->process_grab($account_raw, $intId); // todo: check
				break;
			case parent::PAYLAH_CHANNEL:
				$this->accounts[] = $this->process_paylah($account_raw, $intId); // todo: check
				break;
			case parent::WECHAT_CHANNEL:
				$this->accounts[] = $this->process_wechat($account_raw, $intId); // todo: check
				break;
			case parent::UOB_CHANNEL:
				$this->accounts[] = $this->process_uob($account_raw, $intId); // todo: check
				break;
			case parent::AIRPAY_CHANNEL:
				$this->accounts[] = $this->process_airpay($account_raw, $intId); // todo: check
				break;
			default:
				$this->accounts[] = array_merge(['original_id' => $intId], $account_raw);
		}
	}

	/**
	 * Process PayNow account
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_paynow($account_raw, $intId)
	{
		// MOSTLY ID = 26
		$account['original_id'] = $intId;
		foreach ($account_raw as $id => $val)
		{
			switch ($id)
			{
				case parent::PAYNOW_ID_PROXY_TYPE:
					$account[$this->paynow_keys[$id]] = $this->paynow_proxy_type[$val];
					break;
				case parent::PAYNOW_ID_AMOUNT_EDITABLE:
					$account[$this->paynow_keys[$id]] = $this->paynow_amount_editable[$val];
					break;
				default:
					$account[$this->paynow_keys[$id]] = $val;
			}
		}
		if (empty($account['proxy_type']))
		{
			$this->process_error($intId, "[PayNow] Missing proxy type.");
		} else if (empty($account['proxy_value']))
		{
			$this->process_error($intId, "[PayNow] Missing proxy value.");
		} else if ($account['proxy_type'] == $this->paynow_proxy_type['2'] && ! preg_match('/[A-Z0-9]{9,13}/', $account['proxy_value']))
		{
			$this->process_error($intId, "[PayNow] UEN, as a proxy value, is invalid. Found '{$account['proxy_value']}'.");
		} else if ($account['proxy_type'] == $this->paynow_proxy_type['0'] && ! preg_match('/\+\d{8,16}/', $account['proxy_value']))
		{
			$this->process_error($intId, "[PayNow] Mobile number, as a proxy value, is invalid. Found '{$account['proxy_value']}'.");
		} else if (isset($account['amount_editable']))
		{
			if (FALSE == $account['amount_editable'] && empty($this->transaction_amount))
			{
				$account['amount_editable'] = TRUE;
				$this->process_warning($intId, "[PayNow] Amount editable flag was false while the transaction amount is not set. The field amount editable is now changed to true.");
			}
		} else if (isset($account['expiry_date']))
		{
			date_default_timezone_set(parent::TIMEZONE_SINGAPORE);
			$year = substr($account['expiry_date'], parent::POS_ZERO, parent::LENGTH_FOUR);
			$month = substr($account['expiry_date'], parent::POS_FOUR, parent::LENGTH_TWO);
			$date = substr($account['expiry_date'], parent::POS_SIX, parent::LENGTH_TWO);
			$date_string = "$year-$month-$date";
			if ( ! preg_match('/20\d{2}\-(0[1-9]|1[0-2])\-(0[1-9]|[1-2]\d|3[0-1])/', $date_string))
			{
				$this->process_warning($intId, "[PayNow] Expiry date is invalid. Expected a date, found '{$account['expiry_date']}'. Expiry date is removed.");
				unset($account['expiry_date']);
			} else
			{
				$expiry_date = strtotime("{$date_string} 23:59:59");
				$timestamp = strtotime("now");
				if ($expiry_date < $timestamp)
				{
					$this->process_error($intId, "[PayNow] This QR code is expired. PayNow expiry date is '{$date_string}'.");
				}
			}
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process PromptPay account
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_promptpay($account_raw, $intId)
	{
		// MOSTLY 29
		$account['original_id'] = $intId;
		$account[$this->promptpay_keys[99]] = parent::PROMPTPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			switch ($id)
			{
				case parent::PROMPTPAY_ID_APP_ID:
					$account[$this->promptpay_keys[$id]] = $val;
					break;
				case parent::PROMPTPAY_ID_MOBILE:
					$account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_MOBILE;
					$account[$this->promptpay_keys[98]] = $val;
					break;
				case parent::PROMPTPAY_ID_TAX_ID:
					$account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_TAX_ID;
					$account[$this->promptpay_keys[98]] = $val;
					break;
				case parent::PROMPTPAY_ID_EWALLET_ID:
					$account[$this->promptpay_keys[97]] = parent::PROMPTPAY_PROXY_EWALLET_ID;
					$account[$this->promptpay_keys[98]] = $val;
					break;
			}
		}
		return $account;
	}

	/**
	 * Process SGQR information - not an account but required
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_sgqr($account_raw, $intId)
	{
		// FIXED 51
		$account['original_id'] = $intId;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->sgqr_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process FavePay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_favepay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::FAVE_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->favepay_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process Dash
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_dash($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::DASH_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->dash_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process LiquidPay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_liquidpay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::LIQUIDPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->liquidpay_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process EZ-Link
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_ezlink($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::EZLINK_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->ezlink_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process GrabPay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_grab($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::GRAB_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->grab_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process DBS PayLah!
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_paylah($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::PAYLAH_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->paylah_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process WeChat Pay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_wechat($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::WECHAT_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->wechat_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process UOB
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_uob($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::UOB_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->uob_keys[$id]] = $val;
		}
		return $account;
	}

	/**
	 * todo: verify
	 * Process AirPay / ShopeePay
	 * @param $account_raw
	 * @param $intId
	 * @return array
	 */
	private function process_airpay($account_raw, $intId)
	{
		$account['original_id'] = $intId;
		$account['channel_name'] = parent::AIRPAY_CHANNEL_NAME;
		foreach ($account_raw as $id => $val)
		{
			$account[$this->airpay_keys[$id]] = $val;
		}
		return $account;
	}

}