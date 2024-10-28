<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_POST['info_form'])) {
    $form_data = filteration($_POST);
    session_start();

    $u_exist = select("SELECT * FROM `user_cred` WHERE `phonenum` =? AND `id` !=? LIMIT 1", 'ss', [$form_data['phonenum'], $_SESSION['uId']]);

    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    $query = "UPDATE `user_cred` SET `name`=?, `address`=?, `phonenum`=?, `pincode`=?, `dob`=? WHERE `id`=?";
    $values = [$form_data['name'], $form_data['address'], $form_data['phonenum'], $form_data['pincode'], $form_data['dob'], $_SESSION['uId']];

    if (update($query, 'sssssi', $values)) {
        $_SESSION['uName'] = $form_data['name'];
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['profile_form'])) {
    session_start();

    $img = uploadUserImage($_FILES['profile']);
    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }




    // fetching old image & deleting it 

    $u_exist = select("SELECT * FROM `user_cred` WHERE `id` =? LIMIT 1", 's', [$_SESSION['uId']]);
    $u_fetch = mysqli_fetch_assoc($u_exist);

    deleteImage($u_fetch['profile'], USERS_FOLDER);

    $query = "UPDATE `user_cred` SET `profile`=? WHERE `id`=?";

    $values = [$img, $_SESSION['uId']];

    if (update($query, 'ss', $values)) {
        $_SESSION['uPic'] = $img;
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['pass_form'])) {

    $form_data = filteration($_POST);

    if ($form_data['new_pass'] != $form_data['confirm_pass']) {
        echo 'mismatch';
        exit;
    }

    $enc_pass = password_hash($form_data['new_pass'], PASSWORD_BCRYPT);

    $query = "UPDATE `user_cred` SET `password`=? WHERE `id`=? LIMIT 1";

    $values = [$enc_pass, $_SESSION['uId']];

    if (update($query, 'ss', $values)) {
        echo 1;
    } else {
        echo 0;
    }
}
