<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();


if (isset($_POST['add_room'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $form_data = filteration($_POST);
    $flag = 0;

    $q1 =  "INSERT INTO `rooms`(`name`, `area`, `price`, `quantity`, `adult`, `children`,   `description`) VALUES (?,?,?,?,?,?,?)";
    $values = [$form_data['name'], $form_data['area'], $form_data['price'], $form_data['quantity'], $form_data['adult'], $form_data['children'], $form_data['desc']];

    if (insert($q1, 'siiiiis', $values)) {
        $flag = 1;
    }
    $room_id = mysqli_insert_id($conn);

    $q2 = "INSERT INTO `room_facilities`(`room_id`,`facilities_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q2)) {
        foreach ($facilities as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('query cannot be prepared - insert');
    }

    $q3 = "INSERT INTO `room_features`(`room_id`,`features_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q3)) {
        foreach ($features as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('query cannot be prepared - insert');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}

if (isset($_POST['get_all_rooms'])) {
    $result = select("SELECT * FROM `rooms` WHERE `removed`=?", 'i', [0]);
    $i = 1;
    $data = "";
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status($row[id],0)' class= 'btn btn-dark btn-sm shadow-none'>active</button>";
        } else {
            $status = "<button onclick='toggle_status($row[id],1)' class= 'btn btn-warning btn-sm shadow-none'>inactive</button>";
        }




        $data .= "
            <tr class= 'align-middle'>
                <td>$i</td>
                <td>$row[name]</td>
                <td>$row[area] sq. ft.</td>
                <td>
                <span class= 'badge rounded-pill bg-light text-dark'>
                Adult: $row[adult]
                </span><br>
                <span class= 'badge rounded-pill bg-light text-dark'>
                Children: $row[children]
                </span>
                </td>
                <td>Rs $row[price]</td>
                <td>$row[quantity]</td>
                <td>$status</td>
                <td>
                    <button type='button' onclick='edit_details($row[id])' class='btn btn-primary btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#edit-room'><i class='bi bi-pencil-square'></i>
                    </button>
                    <button type='button' onclick=\"room_images($row[id],'$row[name]')\" class='btn btn-info btn-sm shadow-none' data-bs-toggle='modal' data-bs-target='#room-images'><i class='bi bi-images'></i>
                    </button>
                    <button type='button' onclick='remove_room($row[id])' class='btn btn-danger btn-sm shadow-none'><i class='bi bi-trash'></i>
                    </button>
                </td>
            </tr>
        ";
        $i++;
    }
    echo $data;
}

if (isset($_POST['get_room'])) {
    $form_data = filteration($_POST);
    $result1 = select("SELECT * FROM `rooms` WHERE `id`=?", 'i', [$form_data['get_room']]);
    $result2 = select("SELECT * FROM `room_features` WHERE `room_id`=?", 'i', [$form_data['get_room']]);
    $result3 = select("SELECT * FROM `room_facilities` WHERE `room_id`=?", 'i', [$form_data['get_room']]);

    $roomdata = mysqli_fetch_assoc($result1);
    $features = [];
    $facilities = [];

    if (mysqli_num_rows($result2) > 0) {
        while ($row = mysqli_fetch_assoc($result2)) {
            array_push($features, $row['features_id']);
        }
    }

    if (mysqli_num_rows($result3) > 0) {
        while ($row = mysqli_fetch_assoc($result3)) {
            array_push($facilities, $row['facilities_id']);
        }
    }

    $data = ["roomdata" => $roomdata, "features" => $features, "facilities" => $facilities];
    $data = json_encode($data);
    echo $data;
}

if (isset($_POST['edit_room'])) {
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    $form_data = filteration($_POST);
    $flag = 0;

    $q1 = "UPDATE `rooms` SET `name`=?, `area`=?, `price`=?,`quantity`=?,`adult`=?,`children`=?,`description`=? WHERE `id`=?";
    $values = [$form_data['name'], $form_data['area'], $form_data['price'], $form_data['quantity'], $form_data['adult'], $form_data['children'], $form_data['desc'], $form_data['room_id']];

    if (update($q1, 'siiiiisi', $values)) {
        $flag = 1;
    }

    $del_features = delete("DELETE FROM `room_features` WHERE `room_id`=?", 'i', [$form_data['room_id']]);

    $del_facilities = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", 'i', [$form_data['room_id']]);

    if (!($del_facilities && $del_features)) {
        $flag = 0;
    }

    $q2 = "INSERT INTO `room_facilities`(`room_id`,`facilities_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q2)) {
        foreach ($facilities as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $form_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('query cannot be prepared - insert');
    }

    $q3 = "INSERT INTO `room_features`(`room_id`,`features_id`) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q3)) {
        foreach ($features as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $form_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('query cannot be prepared - insert');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['toggle_status'])) {
    $form_data = filteration($_POST);
    $q = "UPDATE `rooms` SET `status`=? WHERE `id`=?";
    $v = [$form_data['value'], $form_data['toggle_status']];
    if (update($q, 'ii', $v)) {
        echo 1;
    } else {
        echo 0;
    }
}


if (isset($_POST['add_image'])) {
    $form_data = filteration($_POST);
    $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER);
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = " INSERT INTO `room_images`(`room_id`, `image`) VALUES (?,?)";
        $values = [$form_data['room_id'], $img_r];
        $result = insert($q, 'is', $values);
        echo $result;
    }
}

if (isset($_POST['get_room_images'])) {
    $form_data = filteration($_POST);
    $result = select("SELECT * FROM `room_images` WHERE `room_id`= ?", 'i', [$form_data['get_room_images']]);

    $path = ROOMS_IMG_PATH;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['thumb'] == 1) {
            $thumb_btn = "<i class= 'bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
        } else {
            $thumb_btn = "<button onclick='thumb_image($row[sr_no],$row[room_id])' class= 'btn btn-secondary btn-sm shadow-none'><i class='bi bi-check-lg'></i></button>";
        }


        echo <<<data
                <tr class='align-middle'>
                    <td><img src='$path$row[image]' class='img-fluid'></td>
                    <td>$thumb_btn</td>
                    <td>
                       <button onclick='rem_image($row[sr_no],$row[room_id])' class= 'btn btn-danger btn-sm shadow-none'><i class='bi bi-trash'></i></button>
                    </td>
                </tr>


        data;
    }
}


if (isset($_POST['rem_image'])) {
    $form_data = filteration($_POST);
    $values = [$form_data['image_id'], $form_data['room_id']];

    $pre_q = "SELECT * FROM `room_images` WHERE `sr_no`=? AND `room_id`=? ";
    $result = select($pre_q, 'ii', $values);
    $img = mysqli_fetch_assoc($result);

    if (deleteImage($img['image'], ROOMS_FOLDER)) {
        $q = "DELETE FROM `room_images` WHERE `sr_no`=? AND `room_id`=? ";
        $result = delete($q, 'ii', $values);
        echo $result;
    } else {
        echo 0;
    }
}

if (isset($_POST['thumb_image'])) {

    $form_data = filteration($_POST);

    $pre_q = "UPDATE `room_images` SET `thumb`=? WHERE `room_id`= ?";
    $pre_v = [0, $form_data['room_id']];
    $pre_result = update($pre_q, 'ii', $pre_v);

    $q = "UPDATE `room_images` SET `thumb`= ? WHERE `sr_no`=? AND `room_id`= ?";
    $v = [1, $form_data['image_id'], $form_data['room_id']];
    $result = update($q, 'iii', $v);

    echo $result;
}

if (isset($_POST['remove_room'])) {

    $form_data = filteration($_POST);

    $result1 = select("SELECT * FROM `room_images` WHERE `room_id`=? ", 'i', [$form_data['room_id']]);



    while ($row = mysqli_fetch_assoc($result1)) {
        deleteImage($row['image'], ROOMS_FOLDER);
    }


    $result2 = delete("DELETE FROM `room_images` WHERE `room_id`=?", 'i', [$form_data['room_id']]);
    $result3 = delete("DELETE FROM `room_features` WHERE `room_id`=?", 'i', [$form_data['room_id']]);
    $result4 = delete("DELETE FROM `room_facilities` WHERE `room_id`=?", 'i', [$form_data['room_id']]);
    $result5 = update("UPDATE `rooms` SET `removed`=? WHERE `id`=?", 'ii', [1, $form_data['room_id']]);

    if ($result2 || $result3 || $result4 || $result5) {
        echo 1;
    } else {
        echo 0;
    }
}
