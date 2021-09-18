<?php
require_once '../src/EmvMerchantGenerator.php';
$submit = $_POST['submit'];
// set_country($country_code)
$country_code = $_POST['country_code'];
// set_merchant_info($merchant_name, $merchant_city, $merchant_category_code = '', $postal_code = '')
$merchant_name = $_POST['merchant_name'];
$merchant_city = $_POST['merchant_city'];
$merchant_category_code = $_POST['merchant_category_code']; // optional
$postal_code = $_POST['postal_code']; // optional
// set_point_of_initiation_static() or set_price($price)
$price = $_POST['price']; // optional
// set_tip_or_fees($code, $amount = 0)
$tip_or_fees = $_POST['tip_or_fees'];
$fees_amount = $_POST['fees_amount']; // either percentage or int/float
// set_additional_info($field_name, $field_value)
$bill_number = $_POST['bill_number'];
$mobile_number = $_POST['mobile_number'];
$store_label = $_POST['store_label'];
$loyalty_number = $_POST['loyalty_number'];
$reference_label = $_POST['reference_label'];
$customer_label = $_POST['customer_label'];
$terminal_label = $_POST['terminal_label'];
$purpose_of_transaction = $_POST['purpose_of_transaction'];
$additional_customer_data_request = $_POST['additional_customer_data_request'];
$merchant_tax_id = $_POST['merchant_tax_id'];
$merchant_channel = $_POST['merchant_channel'];
function print_text_input($field_name, $label, $value, $note = '')
{
    echo '<div class="row g-3 align-items-center mb-3">
              <div class="col-6 col-md-4">
                <label for="' . $field_name . '" class="col-form-label">' . $label . '</label>
              </div>
              <div class="col-6 col-md-4">
                <input class="form-control" name="' . $field_name . '" id="' . $field_name . '" value="' . $value . '" />
              </div>
              <div class="col-6 col-md-4"><span class="form-text">' . $note . '</span></div>
            </div>';
}

function print_select_input($field_name, $label, $value, $options = [], $note = '')
{
    echo '<div class="row g-3 align-items-center mb-3">
              <div class="col-6 col-md-4">
                <label for="' . $field_name . '" class="col-form-label">' . $label . '</label>
              </div>
              <div class="col-6 col-md-4">
                <select class="form-control" id="' . $field_name . '" name="' . $field_name . '">';
    foreach ($options as $key => $val)
    {
        echo '<option value="' . $key . '"' . ($key == $value ? ' selected="selected"' : '') . '>' . $val . '</option>';
    }
    echo '</select>
              </div>
              <div class="col-6 col-md-4"><span class="form-text">' . $note . '</span></div>
            </div>';
}

?>
<html>
  <head>
    <title>TEST DECODER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <style>
        .col-form-label {
            text-align: right !important;
            width: 100%
        }
    </style>
  </head>
  <body>
    <header class="p-3 bg-dark text-white">
      <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
          <b style="font-size:1.5em">emvQr</b>
          <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
            <li><a href="#" class="nav-link px-2 text-secondary"></a></li>
          </ul>
          <div class="text-end">
            <a class="text-decoration-none text-white" href="decoder.php">switch to decoder</a>
            &nbsp;
            <a href="https://github.com/lee-ratinan/emvQr" class="btn btn-warning" target="_blank">
              <svg role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" style="height:1em;width:1em;">
                <path fill="currentColor"
                      d="M165.9 397.4c0 2-2.3 3.6-5.2 3.6-3.3.3-5.6-1.3-5.6-3.6 0-2 2.3-3.6 5.2-3.6 3-.3 5.6 1.3 5.6 3.6zm-31.1-4.5c-.7 2 1.3 4.3 4.3 4.9 2.6 1 5.6 0 6.2-2s-1.3-4.3-4.3-5.2c-2.6-.7-5.5.3-6.2 2.3zm44.2-1.7c-2.9.7-4.9 2.6-4.6 4.9.3 2 2.9 3.3 5.9 2.6 2.9-.7 4.9-2.6 4.6-4.6-.3-1.9-3-3.2-5.9-2.9zM244.8 8C106.1 8 0 113.3 0 252c0 110.9 69.8 205.8 169.5 239.2 12.8 2.3 17.3-5.6 17.3-12.1 0-6.2-.3-40.4-.3-61.4 0 0-70 15-84.7-29.8 0 0-11.4-29.1-27.8-36.6 0 0-22.9-15.7 1.6-15.4 0 0 24.9 2 38.6 25.8 21.9 38.6 58.6 27.5 72.9 20.9 2.3-16 8.8-27.1 16-33.7-55.9-6.2-112.3-14.3-112.3-110.5 0-27.5 7.6-41.3 23.6-58.9-2.6-6.5-11.1-33.3 2.6-67.9 20.9-6.5 69 27 69 27 20-5.6 41.5-8.5 62.8-8.5s42.8 2.9 62.8 8.5c0 0 48.1-33.6 69-27 13.7 34.7 5.2 61.4 2.6 67.9 16 17.7 25.8 31.5 25.8 58.9 0 96.5-58.9 104.2-114.8 110.5 9.2 7.9 17 22.9 17 46.4 0 33.7-.3 75.4-.3 83.6 0 6.5 4.6 14.4 17.3 12.1C428.2 457.8 496 362.9 496 252 496 113.3 383.5 8 244.8 8zM97.2 352.9c-1.3 1-1 3.3.7 5.2 1.6 1.6 3.9 2.3 5.2 1 1.3-1 1-3.3-.7-5.2-1.6-1.6-3.9-2.3-5.2-1zm-10.8-8.1c-.7 1.3.3 2.9 2.3 3.9 1.6 1 3.6.7 4.3-.7.7-1.3-.3-2.9-2.3-3.9-2-.6-3.6-.3-4.3.7zm32.4 35.6c-1.6 1.3-1 4.3 1.3 6.2 2.3 2.3 5.2 2.6 6.5 1 1.3-1.3.7-4.3-1.3-6.2-2.2-2.3-5.2-2.6-6.5-1zm-11.4-14.7c-1.6 1-1.6 3.6 0 5.9 1.6 2.3 4.3 3.3 5.6 2.3 1.6-1.3 1.6-3.9 0-6.2-1.4-2.3-4-3.3-5.6-2z"
                      class=""></path>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </header>
    <div class="container mt-5">
      <div class="row">
        <div class="col col-lg-8">
          <h1>Test QR Code Generator</h1>
          <hr>
            <?php
            if ( ! empty($submit))
            {
                echo '<h2>Result</h2>';
                echo '<pre>';
                $emv = new \EMVQR\EmvMerchantGenerator();
                $status = $emv->set_country($country_code);
                echo "From set_country()\n";
                echo json_encode($status, JSON_PRETTY_PRINT);
                $status = $emv->set_merchant_info($merchant_name, $merchant_city, $merchant_category_code, $postal_code);
                echo "\nFrom set_merchant_info()\n";
                echo json_encode($status, JSON_PRETTY_PRINT);
                if (empty($price))
                {
                    $status = $emv->set_point_of_initiation_static();
                    echo "\nFrom set_point_of_initiation_static()\n";
                    echo json_encode($status, JSON_PRETTY_PRINT);
                } else
                {
                    $status = $emv->set_price($price);
                    echo "\nFrom set_price()\n";
                    echo json_encode($status, JSON_PRETTY_PRINT);
                }
                if ( ! empty($tip_or_fees))
                {
                    $status = $emv->set_tip_or_fees($tip_or_fees, $fees_amount);
                    echo "\nFrom set_tip_or_fees()\n";
                    echo json_encode($status, JSON_PRETTY_PRINT);
                }
                echo "\nFrom set_additional_info() - if any:\n";
                if ( ! empty($bill_number))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_BILL_NUMBER_KEY, $bill_number);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($mobile_number))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MOBILE_NUMBER_KEY, $mobile_number);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($store_label))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_STORE_LABEL_KEY, $store_label);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($loyalty_number))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_LOYALTY_NUMBER_KEY, $loyalty_number);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($reference_label))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_REFERENCE_LABEL_KEY, $reference_label);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($customer_label))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_CUSTOMER_LABEL_KEY, $customer_label);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($terminal_label))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_TERMINAL_LABEL_KEY, $terminal_label);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($purpose_of_transaction))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_PURPOSE_OF_TRANSACTION_KEY, $purpose_of_transaction);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($additional_customer_data_request))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_ADDITIONAL_CUSTOMER_DATA_REQUEST_KEY, $additional_customer_data_request);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($merchant_tax_id))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MERCHANT_TAX_ID_KEY, $merchant_tax_id);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                if ( ! empty($merchant_channel))
                {
                    $status = $emv->set_additional_info($emv::ID_ADDITIONAL_DATA_MERCHANT_CHANNEL_KEY, $merchant_channel);
                    echo json_encode($status, JSON_PRETTY_PRINT)."\n";
                }
                $string = $emv->generate_qr_string();
                echo "QR Code String:\n";
                echo $string."\n";
                /*

                
                // Accounts
                // @todo: loop through accounts */
                echo '</pre>';
            }


            if (isset($emv))
            {
                $qr_string = $emv->generate_qr_string();
                //echo $qr_string;
                $object = $emv->get_object();
                //echo '<pre>' . json_encode($object, JSON_PRETTY_PRINT) . '</pre>';
                //echo '<pre>' . json_encode($status, JSON_PRETTY_PRINT) . '</pre>';
            }
            ?>
          <form class="form" method="POST">
              <?php
              print_select_input('country_code', 'Country', @$country_code, [
                  '' => '-',
                  'SG' => 'Singapore',
                  'TH' => 'Thailand',
                  'ID' => 'Indonesia',
                  'MY' => 'Malaysia (not available at the moment, will trigger error)',
                  'HK' => 'Hong Kong (not available at the moment, will trigger error)',
                  'IN' => 'India (not available at the moment, will trigger error)'
              ], 'ID 53, 58 (country code and currency code)');
              print_text_input('merchant_name', 'Merchant Name', @$merchant_name, 'ID 59');
              print_text_input('merchant_city', 'Merchant City', @$merchant_city, 'ID 60');
              print_text_input('merchant_category_code', 'Merchant Category Code', @$merchant_category_code, 'ID 52');
              print_text_input('postal_code', 'Postal Code', @$postal_code, 'ID 61');
              print_text_input('price', 'Price', @$price, 'ID 01, 54');
              print_select_input('tip_or_fees', 'Tip or Convenience Fees', @$tip_or_fees, [
                  '' => '-',
                  '01' => 'Tip',
                  '02' => 'Convenience Fees (fixed)',
                  '03' => 'Convenience Fees (percentage)',
                  '04' => 'Invalid Code (will trigger error)'
              ], 'ID 55');
              print_text_input('fees_amount', 'Fees Amount', @$fees_amount, 'ID 56 or 57');
              print_text_input('bill_number', 'Bill Number', @$bill_number, 'ID 62 - 01');
              print_text_input('mobile_number', 'Mobile Number', @$mobile_number, 'ID 62 - 02');
              print_text_input('store_label', 'Store Label', @$store_label, 'ID 62 - 03');
              print_text_input('loyalty_number', 'Loyalty Number', @$loyalty_number, 'ID 62 - 04');
              print_text_input('reference_label', 'Reference Label', @$reference_label, 'ID 62 - 05');
              print_text_input('customer_label', 'Customer Label', @$customer_label, 'ID 62 - 06');
              print_text_input('terminal_label', 'Terminal Label', @$terminal_label, 'ID 62 - 07');
              print_text_input('purpose_of_transaction', 'Purpose of Transaction', @$purpose_of_transaction, 'ID 62 - 08');
              print_text_input('additional_customer_data_request', 'Additional Customer Data Request', @$additional_customer_data_request, 'ID 62 - 09');
              print_text_input('merchant_tax_id', 'Merchant Tax ID', @$merchant_tax_id, 'ID 62 - 10');
              print_text_input('merchant_channel', 'Merchant Channel', @$merchant_channel, 'ID 62 - 11');
              ?>
            <input type="text" name="submit" value="1"/>
            <div class="text-end">
              <input class="btn btn-success mb-3" type="submit" value="Generate"/>
            </div>
          </form>
          <hr>
          <p>&copy; Ratinan Lee - 2021</p>
        </div>
      </div>
    </div>
  </body>
</html>