<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <?php require('inc/links.php'); ?>
    <title><?php echo $settings_r['site_title'] ?> - Contact</title>

</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">CONTACT US</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi iste cumque dolorum quod! <br> Debitis deserunt odit sequi, laboriosam similique eveniet quibusdam neque corrupti
        </p>
    </div>

    <?php

    $contact_q = "SELECT * FROM `contact_details` WHERE `sr_no`=?";
    $values = [1];
    $contact_r = mysqli_fetch_assoc(select($contact_q, 'i', $values));



    ?>



    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded" width="600" height="320px" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?php echo $contact_r['iframe']  ?>"></iframe>
                    <h5 class="mt-4">Address</h5>
                    <a href="<?php echo $contact_r['gmap']  ?>" target="_blank" class="d-inline-block text-dark text-decoration-none mb-2">
                        <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address']  ?>
                    </a>
                    <h5 class="mt-4">Call Us</h5>
                    <a href="tel: +<?php echo $contact_r['pn1']  ?>" class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1']  ?>
                    </a>
                    <br>
                    <?php
                    if ($contact_r['pn2'] != '') {
                        echo <<<data
                                
                                    <a href="tel: +$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]
                                </a>
                        data;
                    }


                    ?>


                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $contact_r['email']  ?> " class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-at-fill"></i> <?php echo $contact_r['email']  ?>
                    </a>
                    <h5 class="mt-4">Follow Us</h5>
                    <?php
                    if ($contact_r['tw'] != '') {
                        echo <<<data
                                                
                                    <a href="$contact_r[tw]" class="d-inline-block mb-3 text-dark fs-5 me-2">
                                    <i class="bi bi-twitter me-1"></i>
                                </a>
                        data;
                    }


                    ?>

                    <a href="<?php echo $contact_r['fb']  ?>" class="d-inline-block mb-3 text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="<?php echo $contact_r['insta']  ?>" class="d-inline-block mb-3 text-dark fs-5 me-2">
                        <i class="bi bi-instagram me-1"></i>
                    </a>

                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>
                        <button name="send" type="submit" class="btn text-white custom-bg mt-3">Send</button>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <?php
    if (isset($_POST['send'])) {
        $form_data = filteration($_POST);
        $q = "INSERT INTO `user_queries`(`name`, `email`, `subject`, `message`) VALUES (?,?,?,?)";
        $values = [$form_data['name'], $form_data['email'], $form_data['subject'], $form_data['message']];
        $result = insert($q, 'ssss', $values);
        if ($result == 1) {
            alert('success', 'Mail sent!');
        } else {
            alert('error', 'Server down! Try again later.');
        }
    }





    ?>




    <?php require('inc/footer.php'); ?>




</body>

</html>