<?php
use \EMVQR\EmvMerchant;
require_once '../src/EmvMerchant.php';
$emv = new EmvMerchant();
$emv->write(\EMVQR\EmvPointOfInitiation::TYPE_DYNAMIC, 'SGD', '', 123.45, '02', 140.0);
$json = json_encode($emv, JSON_PRETTY_PRINT);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="emvco qr code">
        <meta name="author" content="Ratinan Lee">
        <title>TEST DECODER</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    </head>
    <body>
        <?php include "_header.php"; ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col col-lg-8">
                    <h1>Test QR Code Generator</h1>
                    <hr>
                    <?= (isset($json) ? '<h2>Result</h2><pre>' . $json . '</pre>' : '') ?>
                    <hr>
                    <p>&copy; Ratinan Lee - 2021</p>
                </div>
            </div>
        </div>
    </body>
</html>
<?php
//require_once '../src/EmvMerchantGenerator.php';
//$submit = $_POST['submit'];
//// set_country($country_code)
//$country_code = $_POST['country_code'];
//// set_merchant_info($merchant_name, $merchant_city, $merchant_category_code = '', $postal_code = '')
//$merchant_name = $_POST['merchant_name'];
//$merchant_city = $_POST['merchant_city'];
//$merchant_category_code = $_POST['merchant_category_code']; // optional
//$postal_code = $_POST['postal_code']; // optional
//// set_point_of_initiation_static() or set_price($price)
//$price = $_POST['price']; // optional
//// set_tip_or_fees($code, $amount = 0)
//$tip_or_fees = $_POST['tip_or_fees'];
//$fees_amount = $_POST['fees_amount']; // either percentage or int/float
//// set_additional_info($field_name, $field_value)
//$bill_number = $_POST['bill_number'];
//$mobile_number = $_POST['mobile_number'];
//$store_label = $_POST['store_label'];
//$loyalty_number = $_POST['loyalty_number'];
//$reference_label = $_POST['reference_label'];
//$customer_label = $_POST['customer_label'];
//$terminal_label = $_POST['terminal_label'];
//$purpose_of_transaction = $_POST['purpose_of_transaction'];
//$additional_customer_data_request = $_POST['additional_customer_data_request'];
//$merchant_tax_id = $_POST['merchant_tax_id'];
//$merchant_channel = $_POST['merchant_channel'];
//// set accounts
//$paynow_proxy_type = $_POST['paynow_proxy_type'];
//$paynow_proxy_value = $_POST['paynow_proxy_value'];
//$paynow_amount_editable = $_POST['paynow_amount_editable'];
//$paynow_expiry = $_POST['paynow_expiry'];
//$favepay_id = $_POST['favepay_id'];
//$sgqr_id = $_POST['sgqr_id'];
//$sgqr_version = $_POST['sgqr_version'];
//$sgqr_postal_code = $_POST['sgqr_postal_code'];
//$sgqr_level = $_POST['sgqr_level'];
//$sgqr_unit = $_POST['sgqr_unit'];
//$sgqr_misc = $_POST['sgqr_misc'];
//$sgqr_version_date = $_POST['sgqr_version_date'];
//$promptpay_proxy_type = $_POST['promptpay_proxy_type'];
//$promptpay_proxy_value = $_POST['promptpay_proxy_value'];
//function print_text_input($field_name, $label, $value, $note = '', $type = 'text')
//{
//    echo '<div class="row g-3 align-items-center mb-3">
//              <div class="col-6 col-md-4">
//                <label for="' . $field_name . '" class="col-form-label">' . $label . '</label>
//              </div>
//              <div class="col-6 col-md-4">
//                <input class="form-control" type="' . $type . '" name="' . $field_name . '" id="' . $field_name . '" value="' . $value . '" />
//              </div>
//              <div class="col-6 col-md-4"><span class="form-text">' . $note . '</span></div>
//            </div>';
//}
//
//function print_select_input($field_name, $label, $value, $options = [], $note = '')
//{
//    echo '<div class="row g-3 align-items-center mb-3">
//              <div class="col-6 col-md-4">
//                <label for="' . $field_name . '" class="col-form-label">' . $label . '</label>
//              </div>
//              <div class="col-6 col-md-4">
//                <select class="form-control" id="' . $field_name . '" name="' . $field_name . '">';
//    foreach ($options as $key => $val)
//    {
//        echo '<option value="' . $key . '"' . ($key == $value ? ' selected="selected"' : '') . '>' . $val . '</option>';
//    }
//    echo '</select>
//              </div>
//              <div class="col-6 col-md-4"><span class="form-text">' . $note . '</span></div>
//            </div>';
//}
//
//function print_header($head)
//{
//    echo '<div class="row mb-3"><div class="col"><b>' . $head . '</b><hr></div></div>';
//}
//
//function print_status($status)
//{
//    if ($status['status'])
//    {
//        echo "-- OK!\n";
//    } else
//    {
//        echo "-- " . $status['message'] . " (" . json_encode($status['field_id']) . ")\n";
//    }
//}
//
//?>
<!--<html>-->
<!--  <head>-->
<!--    <meta charset="utf-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <meta name="description" content="emvco qr code">-->
<!--    <meta name="author" content="Ratinan Lee">-->
<!--    <title>TEST GENERATOR</title>-->
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"-->
<!--          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">-->
<!--    <style>-->
<!--        .col-form-label {-->
<!--            text-align: right !important;-->
<!--            width: 100%-->
<!--        }-->
<!--    </style>-->
<!--  </head>-->
<!--  <body>-->
<!--      --><?php
//      include "_header.php";
//      ?>
<!--    <div class="container mt-5">-->
<!--      <div class="row">-->
<!--        <div class="col col-lg-8">-->
<!--          <h1>Test QR Code Generator</h1>-->
<!--          <hr>-->
<!--            --><?php
//            if ( ! empty($submit))
//            {
//                echo '<h2>Result</h2>';
//                echo '<pre>';
//                $emv = new \EMVQR\EmvMerchantGenerator();
//                $status = $emv->set_country($country_code);
//                echo "From set_country()\n";
//                print_status($status);
//                $status = $emv->set_merchant_info($merchant_name, $merchant_city, $merchant_category_code, $postal_code);
//                echo "\nFrom set_merchant_info()\n";
//                print_status($status);
//                if (empty($price))
//                {
//                    $status = $emv->set_point_of_initiation_static();
//                    echo "\nFrom set_point_of_initiation_static()\n";
//                    print_status($status);
//                } else
//                {
//                    $status = $emv->set_price($price);
//                    echo "\nFrom set_price()\n";
//                    print_status($status);
//                }
//                if ( ! empty($tip_or_fees))
//                {
//                    $status = $emv->set_tip_or_fees($tip_or_fees, $fees_amount);
//                    echo "\nFrom set_tip_or_fees()\n";
//                    print_status($status);
//                }
//                echo "\nFrom set_additional_info() - if any:\n";
//                if ( ! empty($bill_number))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_BILL_NUMBER_KEY, $bill_number);
//                    print_status($status);
//                }
//                if ( ! empty($mobile_number))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY, $mobile_number);
//                    print_status($status);
//                }
//                if ( ! empty($store_label))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_STORE_LABEL_KEY, $store_label);
//                    print_status($status);
//                }
//                if ( ! empty($loyalty_number))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY, $loyalty_number);
//                    print_status($status);
//                }
//                if ( ! empty($reference_label))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY, $reference_label);
//                    print_status($status);
//                }
//                if ( ! empty($customer_label))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY, $customer_label);
//                    print_status($status);
//                }
//                if ( ! empty($terminal_label))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY, $terminal_label);
//                    print_status($status);
//                }
//                if ( ! empty($purpose_of_transaction))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY, $purpose_of_transaction);
//                    print_status($status);
//                }
//                if ( ! empty($additional_customer_data_request))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY, $additional_customer_data_request);
//                    print_status($status);
//                }
//                if ( ! empty($merchant_tax_id))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY, $merchant_tax_id);
//                    print_status($status);
//                }
//                if ( ! empty($merchant_channel))
//                {
//                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY, $merchant_channel);
//                    print_status($status);
//                }
//                echo "\nFrom accounts:\n";
//                if ( ! empty($paynow_proxy_type))
//                {
//                    $status = $emv->set_account_paynow($paynow_proxy_type, $paynow_proxy_value, $paynow_amount_editable, $paynow_expiry);
//                    echo "PayNow\n";
//                    print_status($status);
//                }
//                if ( ! empty($favepay_id))
//                {
//                    $status = $emv->set_account_sg_favepay($favepay_id);
//                    echo "Fave\n";
//                    print_status($status);
//                }
//                if ( ! empty($sgqr_id))
//                {
//                    $status = $emv->set_account_sgqr($sgqr_id, $sgqr_version, $sgqr_postal_code, $sgqr_level, $sgqr_unit, $sgqr_misc, $sgqr_version_date);
//                    echo "SGQR\n";
//                    print_status($status);
//                }
//                if ( ! empty($promptpay_proxy_type))
//                {
//                    $status = $emv->set_account_promptpay($promptpay_proxy_type, $promptpay_proxy_value);
//                    echo "PromptPay\n";
//                    print_status($status);
//                }
//                // Output:
//                $string = $emv->generate_qr_string();
//                echo "\nQR Code String:\n";
//                echo $string . "\n\n";
//                echo "emvQr Object:\n";
//                echo json_encode($emv, JSON_PRETTY_PRINT);
//                echo "\n\nQR Code:\n";
//                echo '</pre>';
//                echo '<script src="qrious.min.js"></script><div class="qrcode"><canvas id="qr" style="width: 80vw; max-width: 400px;"></canvas></div>';
//                echo '<script>let qr = new QRious({element: document.getElementById("qr"),value: "' . $string . '",level: "H",mime: "image/png",size: 1000});</script>';
//                echo '<br><br>';
//            }
//            ?>
<!--          <form class="form" method="POST">-->
<!--              --><?php
//              print_header('Country');
//              print_select_input('country_code', 'Country', $country_code, [
//                  '' => '-',
//                  'SG' => 'Singapore',
//                  'TH' => 'Thailand',
//                  'ID' => 'Indonesia (not available at the moment, will trigger error)',
//                  'MY' => 'Malaysia (not available at the moment, will trigger error)',
//                  'HK' => 'Hong Kong (not available at the moment, will trigger error)',
//                  'IN' => 'India (not available at the moment, will trigger error)'
//              ], 'ID 53, 58 (country code and currency code)');
//              print_header('Merchant Information');
//              print_text_input('merchant_name', 'Merchant Name', $merchant_name, 'ID 59');
//              print_text_input('merchant_city', 'Merchant City', $merchant_city, 'ID 60');
//              print_text_input('merchant_category_code', 'Merchant Category Code', $merchant_category_code, 'ID 52');
//              print_text_input('postal_code', 'Postal Code', $postal_code, 'ID 61');
//              print_header('Price, Tip, Fees');
//              print_text_input('price', 'Price', $price, 'ID 01, 54');
//              print_select_input('tip_or_fees', 'Tip or Convenience Fees', $tip_or_fees, [
//                  '' => '-',
//                  '01' => 'Tip',
//                  '02' => 'Convenience Fees (fixed)',
//                  '03' => 'Convenience Fees (percentage)',
//                  '04' => 'Invalid Code (will trigger error)'
//              ], 'ID 55');
//              print_text_input('fees_amount', 'Fees Amount', $fees_amount, 'ID 56 or 57');
//              print_header('Additional Information');
//              print_text_input('bill_number', 'Bill Number', $bill_number, 'ID 62 - 01');
//              print_text_input('mobile_number', 'Mobile Number', $mobile_number, 'ID 62 - 02');
//              print_text_input('store_label', 'Store Label', $store_label, 'ID 62 - 03');
//              print_text_input('loyalty_number', 'Loyalty Number', $loyalty_number, 'ID 62 - 04');
//              print_text_input('reference_label', 'Reference Label', $reference_label, 'ID 62 - 05');
//              print_text_input('customer_label', 'Customer Label', $customer_label, 'ID 62 - 06');
//              print_text_input('terminal_label', 'Terminal Label', $terminal_label, 'ID 62 - 07');
//              print_text_input('purpose_of_transaction', 'Purpose of Transaction', $purpose_of_transaction, 'ID 62 - 08');
//              print_text_input('additional_customer_data_request', 'Additional Customer Data Request', $additional_customer_data_request, 'ID 62 - 09');
//              print_text_input('merchant_tax_id', 'Merchant Tax ID', $merchant_tax_id, 'ID 62 - 10');
//              print_text_input('merchant_channel', 'Merchant Channel', $merchant_channel, 'ID 62 - 11');
//              print_header('Singapore');
//              print_select_input('paynow_proxy_type', 'PayNow - Proxy Type', $paynow_proxy_type, [
//                  '' => '-',
//                  '0' => 'Mobile',
//                  '2' => 'UEN',
//                  '1' => 'NRIC (will trigger error)'
//              ], 'PayNow');
//              print_text_input('paynow_proxy_value', 'PayNow - Proxy Value', $paynow_proxy_value, 'PayNow');
//              print_select_input('paynow_amount_editable', 'PayNow - Editable?', $paynow_amount_editable, [
//                  '' => '-',
//                  '1' => 'True',
//                  '0' => 'False'
//              ], 'PayNow');
//              print_text_input('paynow_expiry', 'PayNow - Expiry Date', $paynow_expiry, 'PayNow', 'date');
//              print_text_input('favepay_id', 'FavePay - https://myfave.com/qr/', $favepay_id, 'FavePay');
//              print_text_input('sgqr_id', 'SGQR - ID', $sgqr_id, 'SGQR');
//              print_text_input('sgqr_version', 'SGQR - version', $sgqr_version, 'SGQR, value = 01.0001');
//              print_text_input('sgqr_postal_code', 'SGQR - Postal Code', $sgqr_postal_code, 'SGQR');
//              print_text_input('sgqr_level', 'SGQR - Level', $sgqr_level, 'SGQR');
//              print_text_input('sgqr_unit', 'SGQR - Unit Number', $sgqr_unit, 'SGQR');
//              print_text_input('sgqr_misc', 'SGQR - Miscellaneous', $sgqr_misc, 'SGQR, value = 0000');
//              print_text_input('sgqr_version_date', 'SGQR - Version Date', $sgqr_version_date, 'SGQR, value = 2019-12-31', 'date');
//              print_header('Thailand');
//              print_select_input('promptpay_proxy_type', 'PromptPay - Proxy Type', $promptpay_proxy_type, [
//                  '' => '-',
//                  'MOBILE' => 'Mobile',
//                  'TAX_ID' => 'Tax ID',
//                  'EWALLET_ID' => 'eWallet ID',
//                  'XXX' => 'Invalid (will trigger error)'
//              ], 'PromptPay');
//              print_text_input('promptpay_proxy_value', 'PromptPay - Proxy Value', $promptpay_proxy_value, 'PromptPay');
//              ?>
<!--            <input type="hidden" name="submit" value="1"/>-->
<!--            <div class="text-end">-->
<!--              <input class="btn btn-success mb-3" type="submit" value="Generate"/>-->
<!--            </div>-->
<!--          </form>-->
<!--          <hr>-->
<!--          <p>&copy; Ratinan Lee - 2021 - QR Generator from <a href="https://github.com/TamerSali/QR-Code-Generator"-->
<!--                                                              target="_blank">TamerSali/QR-Code-Generator</a>-->
<!--            repository.</p>-->
<!--        </div>-->
<!--      </div>-->
<!--    </div>-->
<!--  </body>-->
<!--</html>-->