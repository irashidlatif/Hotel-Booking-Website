<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if (isset($_POST['add_feature'])) {
    $form_data = filteration($_POST);
    $q = "INSERT INTO `features`(`name`) VALUES (?)";
    $values = [$form_data['name']];
    $result = insert($q, 's', $values);
    echo $result;
}

if (isset($_POST['get_features'])) {
    $result = selectAll('features');
    $i = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        echo <<<data
                <tr>
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td>
                        <button type="button" onclick="rem_feature($row[id])" class="btn     btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i>Delete
                        </button>
                    </td>
                </tr>
        data;
        $i++;
    }
}

if (isset($_POST['rem_feature'])) {
    $form_data = filteration($_POST);
    $values = [$form_data['rem_feature']];

    $check_q = select("SELECT * FROM `room_features` WHERE `features_id`=?", 'i', [$form_data['rem_feature']]);
    if (mysqli_num_rows($check_q) == 0) {
        $q = "DELETE FROM `features` WHERE `id`=? ";
        $result = delete($q, 'i', $values);
        echo $result;
    } else {
        echo 'room_added';
    }
}

if (isset($_POST['add_facility'])) {
    $form_data = filteration($_POST);
    $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER);
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = " INSERT INTO `facilities`(`name`, `icon`,`description`) VALUES (?,?,?)";
        $values = [$form_data['name'], $img_r, $form_data['desc']];
        $result = insert($q, 'sss', $values);
        echo $result;
    }
}

if (isset($_POST['get_facilities'])) {
    $result = selectAll('facilities');
    $i = 1;
    $path = FACILITIES_IMG_PATH;
    while ($row = mysqli_fetch_assoc($result)) {
        echo <<<data
                <tr class= "align-middle">
                    <td>$i</td>
                    <td>$row[name]</td>
                    <td> <img src="$path$row[icon]" width="30px"></td>
                    <td>$row[description]</td>
                    <td>
                        <button type="button" onclick="rem_facility($row[id])" class="btn     btn-danger btn-sm shadow-none">
                            <i class="bi bi-trash"></i>Delete
                        </button>
                    </td>
                </tr>
        data;
        $i++;
    }
}


if (isset($_POST['rem_facility'])) {
    $form_data = filteration($_POST);
    $values = [$form_data['rem_facility']];

    $check_q = select("SELECT * FROM `room_facilities` WHERE `facilities_id`=?", 'i', [$form_data['rem_facility']]);

    if (mysqli_num_rows($check_q) == 0) {
        $pre_q = "SELECT * FROM `facilities` WHERE `id`=? ";
        $result = select($pre_q, 'i', $values);
        $img = mysqli_fetch_assoc($result);

        if (deleteImage($img['icon'], FACILITIES_FOLDER)) {
            $q = "DELETE FROM `facilities` WHERE `id`=? ";
            $result = delete($q, 'i', $values);
            echo $result;
        } else {
            echo 0;
        }
    } else {
        echo 'room_added';
    }
}
