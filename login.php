<?php
    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $username=validate($_POST['username']);
        $password=validate($_POST['password']);

        if (empty($username)) {
            header("Location:index.php?page=login&error=User Name is required");
            exit();
        }
        else if(empty($password)){
            header("Location:index.php?page=login&error=Password is required");
            exit();
        }
        else
        {
            $stmt="select * from apuser where username like '$username' and password like '$password'";
            $res=$pdo->query($stmt);

            if($res->rowcount()) {
                $_SESSION['username']=$username;
                $go = $_GET['go'];
                header("Location:index.php?page=$go");
                exit();
            }
            else{
                header("Location:index.php?page=login&error=Incorect User name or password");
                exit();
            }
        }
    }
?>

<?=template_header('Log In')?>

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                <strong class="text-black">Log In</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="h3 mb-5 text-black">Log in</h2>
            </div>
            <div class="col-md-12">
                <form action="#" method="post">

                    <div class="p-3 p-lg-5 border">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_uname" class="text-black">User Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="c_uname" name="username" required>
                            </div>
                            <div class="col-md-6">
                                <label for="c_passw" class="text-black">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="c_passw" name="password" required>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <input type="submit" class="btn btn-primary btn-lg btn-block" value="Send" name="login">
                        </div>
                        <?php
                            if(isset($_GET['error']))
                                echo $_GET['error'];
                        ?>
                    </div>
                </form>
            </div>
        </div>
        <br>
        <h5 class="text-black">Don't you have an account yet? <a href="index.php?page=signup&go=profile">let's</a> sign up</h5>

    </div>
</div>

<?=template_footer()?>
