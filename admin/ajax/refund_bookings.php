<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();




if (isset($_POST['get_bookings'])) {
    $form_data = filteration($_POST);

    $query = "SELECT bo.*, bd.* FROM `booking_order` bo INNER JOIN `booking_details` bd ON bo.booking_id = bd.booking_id WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?) AND (bo.booking_status = ? AND bo.refund = ?) ORDER BY bo.booking_id ASC";
    $res = select($query, 'sssss', ["%$form_data[search]%", "%$form_data[search]%", "%$form_data[search]%", "cancelled", 0]);
    $i = 1;
    $table_data = "";

    if (mysqli_num_rows($res) == 0) {
        echo "<b>No such data!</b>";
        exit;
    }


    while ($data = mysqli_fetch_assoc($res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin =  date("d-m-Y", strtotime($data['check_in']));
        $checkout =  date("d-m-Y", strtotime($data['check_out']));

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>
                <span class='badge bg-primary'>
                    Order ID: $data[order_id]
                </span>
                <br>
                <b>Name:</b> $data[user_name]
                <br>
                <b>Phone No:</b> $data[phonenum]
                </td>
                
                <td>
                    <b>Room:</b> $data[room_name]
                    <br>
                    <b>Check in:</b> $checkin
                    <br>
                    <b>Check out:</b> $checkout
                    <br>
                    <b>Date:</b> $date
                </td>
                <td>
                    <b>₨  $data[trans_amt] in cents</b> 
                </td>
                <td>
                    <button type='button' onclick='refund_booking($data[booking_id])' class='btn btn-success  btn-sm fw-bold shadow-none'>
                    <i class='bi bi-cash-stack'></i>  Refund Booking
                    </button>
                </td>
            </tr>        
        
        
        ";
        $i++;
    }

    echo $table_data;
}

if (isset($_POST['refund_booking'])) {
    $form_data = filteration($_POST);
    $query = "UPDATE `booking_order` SET `refund`= ? WHERE `booking_id`= ?";
    $values = [1, $form_data['booking_id']];
    $res = update($query, 'ii', $values);
    echo $res;
}
