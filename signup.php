<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $username=validate($_POST['username']);$password=$_POST['password'];
        $fname=$_POST['fname'];$lname=$_POST['lname'];
        $address=$_POST['address'];$email=$_POST['email'];
        $phnum=$_POST['phnum'];$gender=(isset($_POST['genderm']))?1:0;
        $allergy=$_POST['allergy'];$bdate=$_POST['bdate'];
        $admin=0;

        if(isset($_POST['adminpassword']) && $_POST['adminpassword']) {
            if($_POST['adminpassword'] == "والله"){
                $admin = 1;
            }
            else{
                header("Location:index.php?page=signup&error=wrong Admin Password");
                exit();
            }
        }
        if (empty($username)) {
            header("Location:index.php?page=signup&error=User Name is required");
            exit();
        }
        else {
            $stmt = "select * from apuser where username like '$username'";
            $res=$pdo->query($stmt);

            if($res->rowcount()){
                header("Location:index.php?page=signup&error=username already exists");
                exit();
            }

            $stmt = "insert into apuser values ('$username' , '$password' , '$fname' , '$lname' , '$bdate' , $gender , '$email' , '$phnum' , '$address' , '$allergy' , $admin)";
            $pdo->exec($stmt);

            $_SESSION['username']=$username;
            header("Location:index.php?page=profile");
            exit();
        }
    }
?>

<?=template_header('Sign Up')?>

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                <strong class="text-black">Sign up</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h3 mb-5 text-black">Sign up</h2>
            </div>
            <div class="col-md-12">
                <form action="#" method="post">

                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_username" class="text-black">User Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_username" name="username" required>
                            </div>
                            <div class="col-md-6">
                                <label for="c_passw" class="text-black">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="c_passw" name="password" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_fname" class="text-black">First Name</label>
                                <input type="text" class="form-control" id="c_fname" name="fname">
                            </div>
                            <div class="col-md-6">
                                <label for="c_lname" class="text-black">Last Name</label>
                                <input type="text" class="form-control" id="c_lname" name="lname">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address</label>
                                <input type="text" class="form-control" id="c_address" name="address" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_email" class="text-black">Email</label>
                                <input type="email" class="form-control" id="c_email" name="email">
                            </div>
                            <div class="col-md-6">
                                <label for="c_phone" class="text-black">Phone</label>
                                <input type="text" class="form-control" id="c_phone" name="phnum">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="text-black">Gender</label><br>
                                <input type="radio" class="text-black" id="c_genderm" name="genderm" value="m">&nbsp;<label for="c_genderm">male</label>&nbsp;
                                <input type="radio" class="text-black" id="c_genderf" name="genderf" value="f">&nbsp;<label for="c_genderf">female</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Allergy</label>
                                <input type="text" class="form-control" id="c_address" name="allergy" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_bdate" class="text-black">Birth Date</label><br>
                                <input type="date" class="form-control" id="c_bdate" name="bdate">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="c_create_account" class="text-black" data-toggle="collapse" href="#create_an_account"
                                   role="button" aria-expanded="false" aria-controls="create_an_account"><input type="checkbox" value="1"
                                                                                                                id="c_create_account"> Admin?</label>
                            <div class="collapse" id="create_an_account">
                                <div class="py-2">
                                    <p class="mb-3">if you are a Admin , please inter Admin Password , if you don't have it please contact us.</p>
                                    <div class="form-group">
                                        <label for="c_gnum" class="text-black">Admin Password</label>
                                        <input type="password" class="form-control" id="c_gnum" name="adminpassword"
                                               placeholder="قول والله">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="sign up" name="signup">
                            </div>
                        </div>
                        <?php
                            if(isset($_GET['error']))
                                echo $_GET['error'];
                        ?>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?=template_footer()?>
