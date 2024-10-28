<?php
require('inc/db_config.php');
require('inc/essentials.php');

session_start();
if (isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true) {
    header("location: dashboard.php");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <style>
        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>
<?php
include('inc/links.php');
?>


<body class="bg-light">

    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">ADMIN LOGIN PANEL</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input type="text" name="admin_name" class="form-control shadow-none text-center" placeholder="Admin Name" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="admin_pass" class="form-control shadow-none text-center" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn text-white custom-bg shadow-none">Login</button>
            </div>
        </form>
    </div>

    <?php
    if (isset($_POST['login'])) {
        $form_data = filteration($_POST);

        $query = "SELECT * FROM `admin_cred` WHERE `admin_name`=? AND `admin_pass`=?";
        $values = [$form_data['admin_name'], $form_data['admin_pass']];
        $data_types = 'ss';
        $result = select($query, $data_types, $values);
        if ($result->num_rows == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminId'] = $row['sr_no'];
            redirect('dashboard.php');
        } else {
            alert('error', 'login failed - invalid credentials');
        }
    }


    ?>



    <?php require('inc/scripts.php') ?>
</body>

</html>