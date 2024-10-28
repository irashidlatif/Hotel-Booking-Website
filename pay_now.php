<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>Processing</title>
    <style>
        body {
            background: #fff;
        }

        form {
            margin: 0;
            padding: 0;
        }

        .jsformWrapper {
            border: 1px solid rgba(196, 21, 28, 0.50);
            padding: 2rem;
            width: 600px;
            height: 1400px;
            margin: 0 auto;
            border-radius: 2px;
            margin-top: 2rem;
            box-shadow: 0 7px 5px #eee;
            padding-bottom: 4rem;
        }

        .jsformWrapper .formFielWrapper label {
            width: 300px;
            float: left;
        }

        .jsformWrapper .formFielWrapper input {
            width: 300px;
            padding: 0.5rem;
            border: 1px solid #ccc;
            float: left;
            font-family: sans-serif;
        }

        .jsformWrapper .formFielWrapper select {
            width: 300px;
            padding: 0.5rem;
            border: 1px solid #ccc;
            float: left;
            font-family: sans-serif;
        }

        .jsformWrapper .formFielWrapper {
            float: left;
            margin-bottom: 1rem;
        }

        .jsformWrapper button {
            background: rgba(196, 21, 28, 1);
            border: none;
            color: #fff;
            width: 120px;
            height: 40px;
            line-height: 25px;
            font-size: 16px;
            font-family: sans-serif;
            text-transform: uppercase;
            border-radius: 2px;
            cursor: pointer;
        }

        h3 {
            text-align: center;
            margin-top: 3rem;
            color: rgba(196, 21, 28, 1);
        }
    </style>
    <script>
        function submitForm() {

            CalculateHash();
        }
    </script>
    <script src="https://sandbox.jazzcash.com.pk/Sandbox/Scripts/hmac-sha256.js"></script>


</head>


<body class="bg-light">

    <?php

    require('admin/inc/db_config.php');
    require('admin/inc/essentials.php');
    date_default_timezone_set("Asia/Karachi");

    session_start();
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    if (isset($_POST['pay_now'])) {
        $ORDER_ID = 'ORD' . $_SESSION['uId'] . random_int(11111, 9999999);
        $_SESSION['order_id'] = $ORDER_ID;
        // $CUST_ID = $_SESSION['uId'];
        $TXN_AMOUNT = $_SESSION['room']['payment'];

        // Convert the amount to the smallest unit (e.g., cents)
        $amountInCents = $TXN_AMOUNT * 100;

        $pp_TxnRefNo = 'T' . date('YmdHis');
        // Generate pp_TxnDateTime (current date and time)
        $pp_TxnDateTime = date('YmdHis');

        // Generate pp_TxnExpiryDateTime (current date and time + 24 hours)
        $expiryTimestamp = strtotime('+24 hours');
        $pp_TxnExpiryDateTime = date('YmdHis', $expiryTimestamp);
    }


    // insert payment data into database 

    $form_data = $_POST;
    $query1 = "INSERT INTO `booking_order`( `user_id`, `room_id`, `check_in`, `check_out`, `order_id`) VALUES (?,?,?,?,?)";
    insert($query1, 'issss', [$_SESSION['uId'], $_SESSION['room']['id'], $form_data['checkin'], $form_data['checkout'], $ORDER_ID]);

    $booking_id = mysqli_insert_id($conn);

    $query2 = "INSERT INTO `booking_details`( `booking_id`, `room_name`, `price`, `total_pay`, `user_name`, `phonenum`, `address`) VALUES (?,?,?,?,?,?,?)";
    insert($query2, 'issssss', [$booking_id, $_SESSION['room']['name'], $_SESSION['room']['price'], $TXN_AMOUNT, $form_data['name'], $form_data['phonenum'], $form_data['address']]);


    // header("Pragma: no-cache");
    // header("Cache-Control: no-cache");
    // header("Expires: 0");

    ?>




    <div class="jsformWrapper">
        <h3>Your payment is being processed. Please wait...</h3>
        <form id="paymentForm" name="jsform" method="post" action="https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/" style="display: none;">

            <!-- For Card Tokenization Version should be 2.0 -->
            <div class="formFielWrapper">
                <label class="active">pp_Version: </label>
                <input type="text" name="pp_Version" value="1.1" readonly="true">
            </div>

            <div class="formFielWrapper">
                <label class="">pp_TxnType: </label>
                <input type="text" name="pp_TxnType" value="">
            </div>


            <!-- For Card Tokenization 2.0. Uncomment below 5 fields pp_IsRegisteredCustomer, pp_TokenizedCardNumber, pp_CustomerID, pp_CustomerEmail, pp_CustomerMobile -->
            <!--
                <div class="formFielWrapper">
                    <label class="active">pp_IsRegisteredCustomer: </label>
                    <input type="text" name="pp_IsRegisteredCustomer" value="No">
                </div>

                <div class="formFielWrapper">
                    <label class="">pp_TokenizedCardNumber: </label>
                    <input type="text" name="pp_TokenizedCardNumber" value="">
                </div>

                <div class="formFielWrapper">
                    <label class="">pp_CustomerID: </label>
                    <input type="text" name="pp_CustomerID" value="">
                </div>

                <div class="formFielWrapper">
                    <label class="">pp_CustomerEmail: </label>
                    <input type="text" name="pp_CustomerEmail" value="">
                </div>

                <div class="formFielWrapper">
                    <label class="">pp_CustomerMobile: </label>
                    <input type="text" name="pp_CustomerMobile" value="">
                </div>
                -->



            <div class="formFielWrapper">
                <label class="active">pp_MerchantID: </label>
                <input type="text" name="pp_MerchantID" value="MC71546">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_Language: </label>
                <input type="text" name="pp_Language" value="EN">
            </div>

            <div class="formFielWrapper">
                <label class="">pp_SubMerchantID: </label>
                <input type="text" name="pp_SubMerchantID" value="">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_Password: </label>
                <input type="text" name="pp_Password" value="0s4f6s4zz0">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_TxnRefNo: </label>
                <input type="text" name="pp_TxnRefNo" id="pp_TxnRefNo" value="<?php echo $pp_TxnRefNo ?>">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_Amount: </label>
                <input type="text" name="pp_Amount" value="<?php echo $amountInCents; ?>">
            </div>

            <div class="formFielWrapper">
                <label class="">pp_DiscountedAmount: </label>
                <input type="text" name="pp_DiscountedAmount" value="">
            </div>

            <div class="formFielWrapper">
                <label class="">pp_DiscountBank: </label>
                <input type="text" name="pp_DiscountBank" value="">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_TxnCurrency: </label>
                <input type="text" name="pp_TxnCurrency" value="PKR">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_TxnDateTime: </label>
                <input type="text" name="pp_TxnDateTime" id="pp_TxnDateTime" value="<?php echo $pp_TxnDateTime ?>">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_TxnExpiryDateTime: </label>
                <input type="text" name="pp_TxnExpiryDateTime" id="pp_TxnExpiryDateTime" value="<?php echo $pp_TxnExpiryDateTime ?>">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_BillReference: </label>
                <input type="text" name="pp_BillReference" value="<?php echo $ORDER_ID ?>">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_Description: </label>
                <input type="text" name="pp_Description" value="Hotel Booking - Confirmation">
            </div>

            <div class="formFielWrapper">
                <label class="active">pp_ReturnURL: </label>
                <input type="text" name="pp_ReturnURL" value="http://localhost/hotel_booking/response.php">
            </div>


            <div class="formFielWrapper">
                <label class="active">pp_SecureHash: </label>
                <input type="text" name="pp_SecureHash" value="">
            </div>

            <div class="formFielWrapper">
                <label class="active">ppmpf 1: </label>
                <input type="text" name="ppmpf_1" value="1">
            </div>

            <div class="formFielWrapper">
                <label class="active">ppmpf 2: </label>
                <input type="text" name="ppmpf_2" value="2">
            </div>

            <div class="formFielWrapper">
                <label class="active">ppmpf 3: </label>
                <input type="text" name="ppmpf_3" value="3">
            </div>

            <div class="formFielWrapper">
                <label class="active">ppmpf 4: </label>
                <input type="text" name="ppmpf_4" value="4">
            </div>

            <div class="formFielWrapper">
                <label class="active">ppmpf 5: </label>
                <input type="text" name="ppmpf_5" value="5">
            </div>
            <input type="hidden" name="salt" value="5bzz2120zy">
            <br><br>
            <div class="formFielWrapper" style="margin-bottom: 2rem;">
                <label class="">Hash values string: </label>
                <input type="text" id="hashValuesString" value="">
                <br><br>
            </div>

        </form>


    </div>

    <script>
        // JavaScript code to automatically submit the form
        document.addEventListener("DOMContentLoaded", function() {
            // Call the function to calculate hash when the page loads
            CalculateHash();

            var IntegritySalt = document.getElementsByName("salt")[0].value;;
            var hash = CryptoJS.HmacSHA256(document.getElementById("hashValuesString").value, IntegritySalt);
            document.getElementsByName("pp_SecureHash")[0].value = hash + '';

            alert('string: ' + hashString);
            alert('hash: ' + document.getElementsByName("pp_SecureHash")[0].value);


            // Automatically submit the form
            document.getElementById("paymentForm").submit();
        });

        // function for calculating hash 
        function CalculateHash() {
            var IntegritySalt = document.getElementsByName("salt")[0].value;
            hashString = '';

            hashString += IntegritySalt + '&';

            if (document.getElementsByName("pp_Amount")[0].value != '') {
                hashString += document.getElementsByName("pp_Amount")[0].value + '&';
            }

            if (document.getElementsByName("pp_BillReference")[0].value != '') {
                hashString += document.getElementsByName("pp_BillReference")[0].value + '&';
            }


            //    For Card Tokenization 2.0.Uncomment below 5 commented fields pp_IsRegisteredCustomer, pp_TokenizedCardNumber, pp_CustomerID, pp_CustomerEmail, pp_CustomerMobile


            //if (document.getElementsByName("pp_CustomerEmail")[0].value != '') {
            //    hashString += document.getElementsByName("pp_CustomerEmail")[0].value + '&';
            //}
            //if (document.getElementsByName("pp_CustomerID")[0].value != '') {
            //    hashString += document.getElementsByName("pp_CustomerID")[0].value + '&';
            //}
            //if (document.getElementsByName("pp_CustomerMobile")[0].value != '') {
            //    hashString += document.getElementsByName("pp_CustomerMobile")[0].value + '&';
            //}



            if (document.getElementsByName("pp_Description")[0].value != '') {
                hashString += document.getElementsByName("pp_Description")[0].value + '&';
            }


            //                    if (document.getElementsByName("pp_IsRegisteredCustomer")[0].value != '') {
            //    hashString += document.getElementsByName("pp_IsRegisteredCustomer")[0].value + '&';
            //}


            if (document.getElementsByName("pp_Language")[0].value != '') {
                hashString += document.getElementsByName("pp_Language")[0].value + '&';
            }
            if (document.getElementsByName("pp_MerchantID")[0].value != '') {
                hashString += document.getElementsByName("pp_MerchantID")[0].value + '&';
            }
            if (document.getElementsByName("pp_Password")[0].value != '') {
                hashString += document.getElementsByName("pp_Password")[0].value + '&';
            }
            if (document.getElementsByName("pp_ReturnURL")[0].value != '') {
                hashString += document.getElementsByName("pp_ReturnURL")[0].value + '&';
            }
            if (document.getElementsByName("pp_SubMerchantID")[0].value != '') {
                hashString += document.getElementsByName("pp_SubMerchantID")[0].value + '&';
            }


            //                    if (document.getElementsByName("pp_TokenizedCardNumber")[0].value != '') {
            //    hashString += document.getElementsByName("pp_TokenizedCardNumber")[0].value + '&';
            //}



            if (document.getElementsByName("pp_TxnCurrency")[0].value != '') {
                hashString += document.getElementsByName("pp_TxnCurrency")[0].value + '&';
            }
            if (document.getElementsByName("pp_TxnDateTime")[0].value != '') {
                hashString += document.getElementsByName("pp_TxnDateTime")[0].value + '&';
            }
            if (document.getElementsByName("pp_TxnExpiryDateTime")[0].value != '') {
                hashString += document.getElementsByName("pp_TxnExpiryDateTime")[0].value + '&';
            }
            if (document.getElementsByName("pp_TxnRefNo")[0].value != '') {
                hashString += document.getElementsByName("pp_TxnRefNo")[0].value + '&';
            }

            if (document.getElementsByName("pp_TxnType")[0].value != '') {
                hashString += document.getElementsByName("pp_TxnType")[0].value + '&';
            }

            if (document.getElementsByName("pp_Version")[0].value != '') {
                hashString += document.getElementsByName("pp_Version")[0].value + '&';
            }
            if (document.getElementsByName("ppmpf_1")[0].value != '') {
                hashString += document.getElementsByName("ppmpf_1")[0].value + '&';
            }
            if (document.getElementsByName("ppmpf_2")[0].value != '') {
                hashString += document.getElementsByName("ppmpf_2")[0].value + '&';
            }
            if (document.getElementsByName("ppmpf_3")[0].value != '') {
                hashString += document.getElementsByName("ppmpf_3")[0].value + '&';
            }
            if (document.getElementsByName("ppmpf_4")[0].value != '') {
                hashString += document.getElementsByName("ppmpf_4")[0].value + '&';
            }
            if (document.getElementsByName("ppmpf_5")[0].value != '') {
                hashString += document.getElementsByName("ppmpf_5")[0].value + '&';
            }

            hashString = hashString.slice(0, -1);
            document.getElementById("hashValuesString").value = hashString;
        }
    </script>












    <?php require('inc/footer.php'); ?>

</body>





</html>