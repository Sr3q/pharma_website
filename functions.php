<?php
    function pdo_connect_mysql() {
        // Update the details below with your MySQL details
        $DATABASE_HOST = 'localhost';
        $DATABASE_USER = 'webapp';
        $DATABASE_PASS = 'fourthyear';
        $DATABASE_NAME = 'pharma';
        $opts =[
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
            return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS , $opts);
        } catch (PDOException $e) {
            // If there is an error with the connection, stop the script and display the error.
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    function image_upload(){
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = (isset($_FILES["fileToUpload"]["tmp_name"]) && $_FILES["fileToUpload"]["tmp_name"] ) ? getimagesize($_FILES["fileToUpload"]["tmp_name"]) : 0;
        if ($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            //echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            //echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "webp") {
            //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            //echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                //echo "Sorry, there was an error uploading your file.";
            }
        }

        $imgname = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
        return $imgname;
    }

    function template_header($title) {
        $num_items_in_cart = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;

        $page = (isset($_GET['page'])) ? $_GET['page'] : 'home';
        $home = "";$store = "";$about = "";$prof = "";

        if($page == "home")
            $home = "class = 'active'";
        else if($page == "shop" || $page == "shop-single" || $page == "cart" || $page == "thankyou")
            $store = "class = 'active'";
        else if($page == "about")
            $about = "class = 'active'";
        else
            $prof = "class = 'active'";

        $loginstat="<li $prof><a href='index.php?page=login&go=profile'>Log In</a></li>";
        if(isset($_SESSION['username'])&&$_SESSION['username']){
            $loginstat="<li $prof><a href='index.php?page=profile'>My Profile</a></li>";
        }

        echo <<<EOT
    <!DOCTYPE html>
    <html lang="en">

        <head>
            <title>$title</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <link href="https://fonts.googleapis.com/css?family=Rubik:400,700|Crimson+Text:400,400i" rel="stylesheet">
            <link rel="stylesheet" href="fonts/icomoon/style.css">

            <link rel="stylesheet" href="css/bootstrap.min.css">
            <link rel="stylesheet" href="css/magnific-popup.css">
            <link rel="stylesheet" href="css/jquery-ui.css">
            <link rel="stylesheet" href="css/owl.carousel.min.css">
            <link rel="stylesheet" href="css/owl.theme.default.min.css">


            <link rel="stylesheet" href="css/aos.css">

            <link rel="stylesheet" href="css/style.css">

        </head>
        <body>
            <div class="site-wrap">
                <div class="site-navbar py-2">

                     <div class="search-wrap">
                        <div class="container">
                            <a href="#" class="search-close js-search-close"><span class="icon-close2"></span></a>
                            <form action="index.php?page=shop" method="post">
                                 <input type="text" name = "search" class="form-control" placeholder="Search keyword and hit enter...">
                            </form>
                        </div>
                    </div>

                    <div class="container">
                        <div class="d-flex align-items-center justify-content-between">
                                <div class="logo">
                                <div class="site-logo">
                                    <a href="index.php" class="js-logo-clone">Pharma</a>
                                </div>
                            </div>
                            <div class="main-nav d-none d-lg-block">
                            <nav class="site-navigation text-right text-md-center" role="navigation">
                            <ul class="site-menu js-clone-nav d-none d-lg-block">
                                <li $home><a href="index.php">Home</a></li>
                                <li $store><a href="index.php?page=shop">Store</a></li>
                                <li $about><a href="index.php?page=about">About</a></li>
                                $loginstat
                            </ul>
                            </nav>
                        </div>
                            <div class="icons">
                            <a href="#" class="icons-btn d-inline-block js-search-open"><span class="icon-search"></span></a>
                            <a href="index.php?page=cart" class="icons-btn d-inline-block bag">
                                <span class="icon-shopping-bag"></span>
                                <span class="number">$num_items_in_cart</span>
                            </a>
                            <a href="#" class="site-menu-toggle js-menu-toggle ml-3 d-inline-block d-lg-none"><span
                            class="icon-menu"></span></a>
                        </div>
                        </div>
                    </div>
                </div>
EOT;
    }

    function template_footer(){
        echo <<<EOT
         <div class="site-section bg-secondary bg-image" style="background-image: url('images/bg_2.jpg');">
            <div class="container">
                <div class="row align-items-stretch">
          
                </div>
            </div>
        </div>


        <footer class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-4 mb-lg-0">

                        <div class="block-7">
                            <h3 class="footer-heading mb-4">About Us</h3>
                            <p>This website is capable of offering you the medicines you want at reasonable prices, unlimited amounts, fast shipping

.</p>
                        </div>

                    </div>
                    <div class="col-lg-3 mx-auto mb-5 mb-lg-0">
                        <h3 class="footer-heading mb-4">Quick Links</h3>
                        <ul class="list-unstyled">
                            <li><a href="#">Home</a></li>
                            <li><a href="index.php?page=shop">Store</a></li>
                            <li><a href="index.php?page=about">About</a></li>
                        </ul>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="block-5 mb-5">
                            <h3 class="footer-heading mb-4">Contact Info</h3>
                            <ul class="list-unstyled">
                                <li class="email"><a href="mailto:joudysarraj666@gmail.com">joudysarraj666@gmail.com</a></li>
                                <li class="phone"><a href="tell://0991381658">0991381658</a></li>
                                <li class="email"><a href="mailto:lamaasaya43@gmail.com">lamaasaya43@gmail.com</a></li>
                                <li class="phone"><a href="tell://0940331862">0940331862</a></li>
                                <li class="email"><a href="mailto:mohammedradwaan2002.mk@gmail.com">mohammedradwaan2002.mk@gmail.com</a></li>
                                <li class="phone"><a href="tell://0969165756">0969165756</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row pt-5 mt-5 text-center">
                    <div class="col-md-12">
                        <p>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made
                            with <i class="icon-heart" aria-hidden="true"></i> by <a href="index.php?page=about"
                            class="text-primary">J.S, M.K ,L.A</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </p>
                    </div>

                </div>
            </div>
        </footer>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/aos.js"></script>

    <script src="js/main.js"></script>

    </body>

    </html>
EOT;

    }

    function validate($data){

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;

    }