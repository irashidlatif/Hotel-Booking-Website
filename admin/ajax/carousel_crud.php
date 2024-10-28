<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if (isset($_POST['get_general'])) {
    $query = "SELECT * FROM `settings` WHERE `sr_no`=?";
    $values = [1];
    $result = select($query, 'i', $values);
    $data = mysqli_fetch_assoc($result);
    $json_data = json_encode($data);
    echo $json_data;
}

if (isset($_POST['upd_general'])) {
    $form_data = filteration($_POST);
    $q = "UPDATE `settings` SET `site_title`= ? , `site_about`= ? WHERE `sr_no`= ?";
    $values = [$form_data['site_title'], $form_data['site_about'], 1];
    $result = update($q, "ssi", $values);
    echo $result;
}

if (isset($_POST['upd_shutdown'])) {
    $form_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0;
    $q = "UPDATE `settings` SET `shutdown`= ? WHERE `sr_no`= ?";
    $values = [$form_data, 1];
    $result = update($q, "ii", $values);
    echo $result;
}

if (isset($_POST['get_contacts'])) {
    $query = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $values = [1];
    $result = select($query, 'i', $values);
    $data = mysqli_fetch_assoc($result);
    $json_data = json_encode($data);
    echo $json_data;
}


if (isset($_POST['upd_contacts'])) {
    $form_data = filteration($_POST);
    $q = "UPDATE `contact_details` SET `address`=?,`gmap`=?,`pn1`=?,`pn2`=?,`email`=?,`fb`=?,`insta`=?,`tw`=?,`iframe`= ? WHERE `sr_no`= ?";
    $values = [$form_data['address'], $form_data['gmap'], $form_data['pn1'], $form_data['pn2'], $form_data['email'], $form_data['fb'], $form_data['insta'], $form_data['tw'], $form_data['iframe'], 1];
    $result = update($q, "sssssssssi", $values);
    echo $result;
}

if (isset($_POST['add_image'])) {
    $form_data = filteration($_POST);
    $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER);
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO `carousel`(`image`) VALUES (?)";
        $values = [$img_r];
        $result = insert($q, 's', $values);
        echo $result;
    }
}

if (isset($_POST['get_carousel'])) {
    $result = selectAll('carousel');
    while ($row = mysqli_fetch_assoc($result)) {
        $path = CAROUSEL_IMG_PATH;
        echo <<<data
            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white">
                    <img src="$path$row[image]" class="card-img">
                    <div class="card-img-overlay text-end">
                        <button type="button" onclick="rem_image($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i>Delete
                        </button>
                    </div>
                </div>
            </div>

        data;
    }
}


if (isset($_POST['rem_image'])) {
    $form_data = filteration($_POST);
    $values = [$form_data['rem_image']];
    $pre_q = "SELECT * FROM `carousel` WHERE `sr_no`=? ";
    $result = select($pre_q, 'i', $values);
    $img = mysqli_fetch_assoc($result);

    if (deleteImage($img['image'], CAROUSEL_FOLDER)) {
        $q = "DELETE FROM `carousel` WHERE `sr_no`=? ";
        $result = delete($q, 'i', $values);
        echo $result;
    } else {
        echo 0;
    }
}
