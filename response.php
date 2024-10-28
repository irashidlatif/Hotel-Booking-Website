<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Response page</title>
</head>

<body>
    <h1>Jazzcash Response page</h1>


    <?php
    require('admin/inc/essentials.php');
    require('admin/inc/db_config.php');
    date_default_timezone_set("Asia/Karachi");

    session_start();
    unset($_SESSION['room']);
    // regenerate session function
    function regenerate_session($uid)
    {
        $user_q = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1", 'i', [$uid]);
        $user_fetch = mysqli_fetch_assoc($user_q);
        $_SESSION['login'] = true;
        $_SESSION['uId'] = $user_fetch['id'];
        $_SESSION['uName'] = $user_fetch['name'];
        $_SESSION['uPic'] = $user_fetch['profile'];
        $_SESSION['uPhone'] = $user_fetch['phonenum'];
    }

    $expectedMerchantID = 'MC71546';

    if (isset($_POST['pp_MerchantID'])) {
        $receivedMerchantID = $_POST['pp_MerchantID'];

        if ($receivedMerchantID === $expectedMerchantID) {
            // it means transaciton is secure.
            $select_query = "SELECT `booking_id`,`user_id` FROM `booking_order`
            WHERE `order_id`='$_POST[pp_BillReference]'";
            $select_res = mysqli_query($conn, $select_query);
            if (mysqli_num_rows($select_res) == 0) {
                redirect('index.php');
            }
            $select_fetch = mysqli_fetch_assoc($select_res);
            if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                // regenerate session
                regenerate_session($select_fetch['user_id']);
            }
            if ($_POST['pp_ResponseCode'] == 000) {
                $upd_query = "UPDATE `booking_order` SET `booking_status`='booked',`trans_id`='$_POST[pp_TxnRefNo]',`trans_amt`='$_POST[pp_Amount]',`trans_resp_code`='$_POST[pp_ResponseCode]',`trans_resp_msg`='$_POST[pp_ResponseMessage]' WHERE `booking_id`='$select_fetch[booking_id]'";

                mysqli_query($conn, $upd_query);
            } else {
                $upd_query = "UPDATE `booking_order` SET `booking_status`='payment failed',`trans_id`='$_POST[pp_TxnRefNo]',`trans_amt`='$_POST[pp_Amount]',`trans_resp_code`='$_POST[pp_ResponseCode]',`trans_resp_msg`='$_POST[pp_ResponseMessage]' WHERE `booking_id`='$select_fetch[booking_id]'";

                mysqli_query($conn, $upd_query);
            }
            redirect('pay_status.php?order=' . $_POST['pp_BillReference']);
        } else {
            redirect('index.php');
        }
    } else {
        redirect('index.php');
    }

    ?>



























</body>

</html>