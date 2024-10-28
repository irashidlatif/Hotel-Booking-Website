<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/common.css">

<?php
require('admin/inc/db_config.php');
require('admin/inc/essentials.php');
session_start();

date_default_timezone_set("Asia/Karachi");


$contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
$values = [1];
$contact_r = mysqli_fetch_assoc(select($contact_q, 'i', $values));

$settings_q = "SELECT * FROM `settings` WHERE `sr_no`=?";
$values = [1];
$settings_r = mysqli_fetch_assoc(select($settings_q, 'i', $values));

if ($settings_r['shutdown']) {
    echo <<<alertbar
        <div class="bg-danger text-center p-2 fw-bold">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Bookings are temporarily closed!
        </div>


    alertbar;
}
?>