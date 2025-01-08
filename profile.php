<?php
    if(!isset($_SESSION['username'])){
        header("Location:index.php");
        exit();
    }

    $username=$_SESSION['username'];

    $stmt="select * from apuser where username like '$username'";
    $res=$pdo->query($stmt);
    $userinfo=$res->fetch();

    $stmt = "select * from bill where username like '$username' order by bdate DESC";
    $res=$pdo->query($stmt);
    $billinfo=$res->fetchall();

    if(isset($_POST['edite'])){
        if($_POST['username'] != $username){
            $stmt =$pdo->prepare("select * from apuser where username like ?");
            $stmt->execute([$_POST['username']]);

            if($stmt->rowcount()){
                header("Location:index.php?page=profile&error=Username already exists");
                exit();
            }

            $stmt = $pdo->prepare("update apuser set username = ? where username like '$username'");
            $stmt->execute([$_POST['username']]);

            $_SESSION['username']=$_POST['username'];
            $username=$_POST['username'];
        }
        if($_POST['oldpassword']) {
            if ($_POST['oldpassword'] == $userinfo['password']) {
                $stmt = $pdo->prepare("update apuser set password = ? where username like '$username'");
                $stmt->execute([$_POST['newpassword']]);
            }
            else{
                header("Location:index.php?page=profile&error=wrong old password try agine");
                exit();
            }
        }

        $fname=$_POST['fname'];$lname=$_POST['lname'];
        $address=$_POST['address'];$email=$_POST['email'];
        $phnum=$_POST['phnum'];$gender=(isset($_POST['genderm']))?1:0;
        $allergy=$_POST['allergy'];$bdate=$_POST['bdate'];

        $stmt = "update apuser set fname = '$fname' , lname = '$lname' , bdate = '$bdate' , gender = $gender , email = '$email' , phnum = '$phnum' , address = '$address' , allergy = '$allergy' where username like '$username'";
        $pdo->exec($stmt);
    }

    if(isset($_POST['addmed'])){

        $mname=$_POST['mname'];$imgname=image_upload();
        $amount=$_POST['amount'];$price=$_POST['price'];
        $agegroup=$_POST['agegroup'];$info=$_POST['info'];
        $indate=date('Y-m-d');

        $stmt = $pdo->prepare("select * from medicine where mname like ?");
        $stmt->execute([$mname]);

        if($stmt->rowcount()){
            header("Location:index.php>page=profile&error=medicine already exists please update it");
            exit();
        }

        $stmt = "insert into medicine values ('$mname' , $price , '$agegroup' ,'$info' , '$imgname' , '$indate' , 0 , $amount)";
        $pdo->exec($stmt);

        header("Location:index.php?page=profile&error=The medicine has been added successfully");
        exit();
    }

    if(isset($_POST['rmmed'])){
        $mname=$_POST['mname'];

        $stmt = $pdo->prepare("select * from medicine where mname like ?");
        $stmt->execute([$mname]);

        if(!$stmt->rowcount()){
            header("Location:index.php?page=profile&error=The medicine is not found");
            exit();
        }

        $stmt =$pdo->prepare("delete from medicine where mname like ?");
        $stmt->execute([$mname]);

        header("Location:index.php?page=profile&error=The medicine has been successfully removed");
        exit();
    }

    if(isset($_POST['editemed'])) {
        $mname=$_POST['mname'];
        $stmt = $pdo->prepare("select * from medicine where mname like ?");
        $stmt->execute([$mname]);

        if(!$stmt->rowcount()){
            header("Location:index.php?page=profile&error=The medicine is not found");
            exit();
        }

        if($_POST['amount']){
            $stmt = $pdo->prepare("update medicine set amount = ? where mname like ?");
            $stmt->bindValue(1,$_POST['amount'], PDO::PARAM_INT);
            $stmt->bindValue(2,$_POST['mname'], PDO::PARAM_STR);
            $stmt->execute();
        }

        if($_POST['price']){
            $stmt = $pdo->prepare("update medicine set price = ? where mname like ?");
            $stmt->bindValue(1,$_POST['price'], PDO::PARAM_INT);
            $stmt->bindValue(2,$_POST['mname'], PDO::PARAM_STR);
            $stmt->execute();
        }

        if($_POST['agegroup']){
            $stmt = $pdo->prepare("update medicine set agegroup = ? where mname like ?");
            $stmt->bindValue(1,$_POST['agegroup'], PDO::PARAM_STR);
            $stmt->bindValue(2,$_POST['mname'], PDO::PARAM_STR);
            $stmt->execute();
        }

        if($_POST['info']){
            $stmt = $pdo->prepare("update medicine set info = ? where mname like ?");
            $stmt->bindValue(1,$_POST['info'], PDO::PARAM_STR);
            $stmt->bindValue(2,$_POST['mname'], PDO::PARAM_STR);
            $stmt->execute();
        }

        $imgname=image_upload();
        if($imgname){
            $stmt = $pdo->prepare("update medicine set image = ? where mname like ?");
            $stmt->bindValue(1,$imgname, PDO::PARAM_STR);
            $stmt->bindValue(2,$_POST['mname'], PDO::PARAM_STR);
            $stmt->execute();
        }

        header("Location:index.php?page=profile&error=The medicine has been updated successfully");
        exit();
    }

    if(isset($_POST['logout'])){
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

?>

<?=template_header('Profile')?>

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                <strong class="text-black">My Profile</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-12">
                <h1 class="text-black">Hello <?=$username?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-5 mb-md-0">
                <h2 class="h3 mb-3 text-black">Your Details</h2>
                <div class="p-3 p-lg-5 border">
                    <form action="#" method="post">
                        <div class="form-group">
                            <label for="c_uname" class="text-black">User Name</label>
                            <input type="text" id="c_uname" class="form-control" name="username" value="<?=$userinfo['username']?>" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="c_passw" class="text-black">Password</label>
                            <input type="password" id="c_passw" class="form-control" name="password" value="*******">
                        </div>

                        <div class="form-group">
                            <label for="c_create_account" class="text-black" data-toggle="collapse" href="#create_an_account"
                                   role="button" aria-expanded="false" aria-controls="create_an_account"><input type="checkbox" value="1"
                                                                                                                id="c_create_account"> Edite Password?</label>
                            <div class="collapse" id="create_an_account">
                                <div class="py-2">
                                    <p class="mb-3">please enter your old password and the new one and prees edite button.</p>
                                    <div class="form-group">
                                        <label for="c_opassw" class="text-black">Old Password</label>
                                        <input type="password" class="form-control" id="c_opassw" name="oldpassword">
                                    </div>
                                    <div class="form-group">
                                        <label for="c_npassw" class="text-black">New Password</label>
                                        <input type="password" class="form-control" id="c_npassw" name="newpassword">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_fname" class="text-black">First Name</label>
                                <input type="text" class="form-control" id="c_fname" name="fname" value="<?=$userinfo['fname']?>">
                            </div>
                            <div class="col-md-6">
                                <label for="c_lname" class="text-black">Last Name</label>
                                <input type="text" class="form-control" id="c_lname" name="lname" value="<?=$userinfo['lname']?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label  class="text-black"> Gender </label><br>
                                <input type="radio" class="text-black" id="c_genderm" name="genderm" <?= ($userinfo['gender']==1)?'checked':''; ?>>&nbsp;male&nbsp;
                                <input type="radio" class="text-black" id="c_genderf" name="genderf" <?= ($userinfo['gender']==0)?'checked':''; ?>>&nbsp;female
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="c_address" class="text-black">Address</label>
                                <input type="text" class="form-control" id="c_address" name="address" value="<?=$userinfo['address']?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="c_email_address" class="text-black">Email Address</label>
                                <input type="text" class="form-control" id="c_email_address" name="email" value="<?=$userinfo['email']?>">
                            </div>
                            <div class="col-md-6">
                                <label for="c_phone" class="text-black">Phone</label>
                                <input type="text" class="form-control" id="c_phone" name="phnum" placeholder="Phone Number" value="<?=$userinfo['phnum']?>">
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-6">
                                <label for="c_bdate" class="text-black">Birth Date</label>
                                <input type="date" class="form-control" id="c_bdate" name="bdate" value="<?=$userinfo['bdate']?>">
                            </div>
                        </div>

                        <div class="form-group row mb-5">
                            <div class="col-md-12">
                                <label for="c_allergy" class="text-black">Your Allergy</label>
                                <input type="text" class="form-control" id="c_allergy" name="allergy"  value="<?=$userinfo['allergy']?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Edite" name="edite">
                            </div>
                            <div class="col-lg-6">
                                <input type="submit" class="btn btn-primary btn-lg btn-block" value="Log Out" name="logout">
                            </div>
                        </div>
                        <?php
                        if(isset($_GET['error'])){
                            echo $_GET['error'];
                        }
                        ?>
                    </form>
                </div>
            </div>

            <!-- admin -->
            <div class="col-md-6">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <?php if($userinfo['admin']): ?>
                        <h2 class="h3 mb-3 text-black">Do you want to do something?</h2>
                        <div class="p-3 p-lg-5 border mb-5">

                            <div class="form-group mb-5">
                                <label for="c_add_mid" class="text-black" data-toggle="collapse" href="#add_mid"
                                       role="button" aria-expanded="false" aria-controls="create_an_account"><button class="btn btn-primary btn-lg btn-block" id="c_add_mid" >Add Medicine</button></label>
                                <div class="collapse" id="add_mid">
                                    <div class="py-2">
                                        <div class="form-group">
                                            <form action="#" method="post" enctype="multipart/form-data">
                                                <label for="c_mid_nm" class="text-black">Name</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_nm" name="mname"
                                                       placeholder="" required>

                                                <label for="c_mid_amount" class="text-black">Amount</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_amount" name="amount"
                                                       placeholder="" required>

                                                <label for="c_mid_pri" class="text-black">Price</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_pri" name="price"
                                                       placeholder="" required>

                                                <label for="c_mid_ag" class="text-black">Age Group</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_ag" name="agegroup"
                                                       placeholder=""required>

                                                <label for="c_mid_info" class="text-black">Info About Medicine</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_info" name="info"
                                                       placeholder="" required>

                                                <label for="c_mid_img" class="text-black">Image</label>
                                                <input type="file" class="form-control mb-3" id="c_mid_img" name="fileToUpload"
                                                       placeholder="" accept="image/*" required>

                                                <div class="form-group row">
                                                    <div class="col-lg-3">
                                                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="Add" name="addmed">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-5">
                                <label for="c_rem_mid" class="text-black" data-toggle="collapse" href="#rem_mid"
                                       role="button" aria-expanded="false" aria-controls="create_an_account"><button class="btn btn-primary btn-lg btn-block" id="c_rem_mid" >Remove Medicine</button></label>
                                <div class="collapse" id="rem_mid">
                                    <div class="py-2">
                                        <div class="form-group">
                                            <form action="#" method="post">
                                                <label for="c_mid_nm" class="text-black">Medicine's Name To Remove</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_nm" name="mname"
                                                       placeholder="">
                                                <div class="form-group row">
                                                    <div class="col-lg-4">
                                                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="remove" name="rmmed">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c_edt_mid" class="text-black" data-toggle="collapse" href="#edt_mid"
                                       role="button" aria-expanded="false" aria-controls="create_an_account"><button class="btn btn-primary btn-lg btn-block" id="c_edt_mid" >Edite Medicine</button></label>
                                <div class="collapse" id="edt_mid">
                                    <div class="py-2">
                                        <div class="form-group">
                                            <form action="#" method="post" enctype="multipart/form-data">
                                                <label for="c_mid_nm" class="text-black">Name</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_nm" name="mname"
                                                       placeholder="" required>

                                                <label for="c_mid_amount" class="text-black">Amount</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_amount" name="amount"
                                                       placeholder="" >

                                                <label for="c_mid_pri" class="text-black">Price</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_pri" name="price"
                                                       placeholder="" >

                                                <label for="c_mid_ag" class="text-black">Age Group</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_ag" name="agegroup"
                                                       placeholder="">

                                                <label for="c_mid_info" class="text-black">Info About Medicine</label>
                                                <input type="text" class="form-control mb-3" id="c_mid_info" name="info"
                                                       placeholder="" >

                                                <label for="c_mid_img" class="text-black">Image</label>
                                                <input type="file" class="form-control mb-3" id="c_mid_img" name="fileToUpload"
                                                       placeholder="" accept="image/*" >

                                                <div class="form-group row">
                                                    <div class="col-lg-3">
                                                        <input type="submit" class="btn btn-primary btn-lg btn-block" value="edite" name="editemed">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                if(isset($_GET['error'])){
                                    echo $_GET['error'];
                                }
                            ?>

                        </div>
                        <?php endif; ?>
                        <h2 class="h3 mb-3 text-black">Your purchase history</h2>
                        <div class="p-3 p-lg-5 border">
                            <table class="table site-block-order-table mb-1">
                                <thead>
                                <th>Bill num</th>
                                <th>Date</th>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($billinfo as $row) {
                                    echo '<tr>';
                                    foreach ($row as $key => $val) {
                                        if($key == 'billnum') {
                                            echo "<td><a href='index.php.?page=bill&billnum=$val' style='padding: 0px'>$val</a></td>";
                                        }
                                        else if($key == "bdate") {
                                            echo "<td>$val</td>";
                                        }
                                    }
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- </form> -->
    </div>
</div>

<?=template_footer()?>
