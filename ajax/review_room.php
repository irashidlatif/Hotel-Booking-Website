<?php
session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}



if (isset($_POST['review_form'])) {
    $form_data = filteration($_POST);

    $upd_query = "UPDATE `booking_order` SET `rate_review`= ? WHERE `booking_id`= ? AND `user_id`= ? ";
    $upd_values = [1, $form_data['booking_id'], $_SESSION['uId']];
    $upd_result = update($upd_query, 'iii', $upd_values);

    $ins_query = "INSERT INTO `rate_review`(`booking_id`,`room_id`,`user_id`,`rating`,`review`)
    VALUES (?,?,?,?,?)";
    $ins_values = [$form_data['booking_id'], $form_data['room_id'], $_SESSION['uId'], $form_data['rating'], $form_data['review']];
    $ins_result = insert($ins_query, 'iiiis', $ins_values);



    echo $ins_result;
}
