<?php
    // If the user clicked the add to cart button on the product page we can check for the form data
    if (isset($_POST['mname'], $_POST['amount']) && $_POST['mname'] && is_numeric($_POST['amount'])) {
        // Set the post variables so we easily identify them, also make sure they are integer
        $mname = $_POST['mname'];
        $amount = (int)$_POST['amount'];
        // Prepare the SQL statement, we basically are checking if the product exists in our databaser
        $stmt = $pdo->prepare('SELECT * FROM medicine WHERE mname like ?');
        $stmt->execute([$_POST['mname']]);
        // Fetch the product from the database and return the result as an Array
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if the product exists (array is not empty)
        if ($product && $amount > 0) {
            // Product exists in database, now we can create/update the session variable for the cart
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                if (array_key_exists($mname, $_SESSION['cart'])) {
                    // Product exists in cart so just update the quanity
                    $_SESSION['cart'][$mname] += $amount;
                    $nwval = $_SESSION['cart'][$mname] + $amount;
                    $_SESSION['cart'][$mname]=min($nwval,$product['amount']);
                } else {
                    // Product is not in cart so add it
                    $_SESSION['cart'][$mname] = $amount;
                }
            } else {
                // There are no products in cart, this will add the first product to cart
                $_SESSION['cart'] = array($mname => $amount);
            }
        }
        // Prevent form resubmission...
        header('location: index.php?page=cart');
        exit;
    }

    // Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
    if (isset($_GET['remove']) && $_GET['remove'] && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
        // Remove the product from the shopping cart
        unset($_SESSION['cart'][$_GET['remove']]);
    }

    // Update product quantities in cart if the user clicks the "Update" button on the shopping cart page
    if (isset($_POST['update']) && isset($_SESSION['cart'])) {
        // Loop through the post data so we can update the quantities for every product in cart
        foreach ($_POST as $k => $v) {
            if (strpos($k, 'quantity') !== false && is_numeric($v)) {
                $mname = str_replace('quantity-', '', $k);
                $amount = (int)$v;
                // Always do checks and validation
                if ($mname && isset($_SESSION['cart'][$mname]) && $amount > 0) {
                    // Update new quantity
                    $_SESSION['cart'][$mname] = $amount;
                }
            }
        }
        // Prevent form resubmission...
        header('location: index.php?page=cart');
        exit;
    }

    // Send the user to the place order page if they click the Place Order button, also the cart should not be empty
    if (isset($_POST['placeorder'])) {
        if(isset($_SESSION['username'])){
            $stmt = "select max(billnum) as lastnum from bill";
            $res = $pdo->query($stmt);
            $row = $res->fetch();

            $billnum = $row['lastnum'] + 1;
            $username=$_SESSION['username'];
            $date=date("Y-m-d");

            $stmt = "insert into bill values ('$billnum' , '$username' ,'$date')";
            $pdo->exec($stmt);

            foreach ($_SESSION['cart'] as $mname => $amount){
                $res = $pdo->query("select * from medicine where mname like '$mname'");
                $row = $res->fetch();
                $price=$row['price'];
                $image=$row['image'];
                $oldamount=$row['amount'];

                $stmt = "insert into billinfo values ('$billnum' , '$mname' , $amount , $price , '$image')";
                $pdo->exec($stmt);

                $stmt = "update medicine set salnum = " . $row['salnum'] + 1 . " where mname like '$mname'";
                $pdo->exec($stmt);

                $stmt = "update medicine set amount  = $oldamount - $amount where mname like '$mname'";
                $pdo->exec($stmt);
            }

            unset($_SESSION['cart']);
            header("Location:index.php?page=thankyou");
            exit();
        }
        else{
            header("Location:index.php?page=login&go=cart");
            exit();
        }

    }

    // Check the session variable for products in cart
    $products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $products = array();
    $subtotal = 0.00;
    // If there are products in cart
    if ($products_in_cart) {
        // There are products in the cart so we need to select those products from the database
        // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
        $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
        $stmt = $pdo->prepare('SELECT * FROM medicine WHERE mname IN (' . $array_to_question_marks . ')');
        // We only need the array keys, not the values, the keys are the id's of the products
        $stmt->execute(array_keys($products_in_cart));
        // Fetch the products from the database and return the result as an Array
        $products = $stmt->fetchAll();
        // Calculate the subtotal
        foreach ($products as $product) {
            $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['mname']];
        }
    }
?>


<?=template_header('Cart')?>

    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0">
                    <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                    <strong class="text-black">Cart</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="site-section">
        <div class="container">
            <form action="#"  method="post">
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="site-blocks-table">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th class="product-thumbnail">Image</th>
                                    <th class="product-name">Product</th>
                                    <th class="product-price">Price</th>
                                    <th class="product-quantity">Quantity</th>
                                    <th class="product-total">Total</th>
                                    <th class="product-remove">Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($products)): ?>
                                        <tr>
                                            <div class="title-section text-center col-12"><h2 >You Have No Products Added In Your Shopping Cart</h2></div>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td class="product-thumbnail">
                                                    <a href="index.php?page=shop-single&mname=<?=$product['mname']?>">
                                                        <img src="images/<?=$product['image']?>" alt="Image" class="img-fluid">
                                                    </a>
                                                </td>
                                                <td class="product-name">
                                                    <a href="index.php?page=shop-single&mname=<?=$product['mname']?>">
                                                        <h2 class="h5 text-black"><?=$product['mname']?></h2>
                                                    </a>
                                                </td>
                                                <td><?=$product['price']?>sp</td>
                                                <td>
                                                    <div class="input-group mb-3" >

                                                        <input type="number" class="form-control text-center" placeholder=""
                                                               aria-label="Example text with button addon" aria-describedby="button-addon1"
                                                                min="1" max="<?=$product['amount']?>" value="<?=$products_in_cart[$product['mname']]?>"
                                                               name="quantity-<?=$product['mname']?>">

                                                    </div>

                                                </td>
                                                <td><?=$product['price'] * $products_in_cart[$product['mname']]?>sp</td>
                                                <td><a href="index.php?page=cart&remove=<?=$product['mname']?>" class="btn btn-primary height-auto btn-sm">X</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="row mb-5">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <input type="submit" value="Update Cart" name="update" class="btn btn-primary btn-md btn-block">
                            </div>
                            <div class="col-md-6">
                                <a href="index.php?page=shop" class="btn btn-outline-primary btn-md btn-block">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pl-5">
                        <div class="row justify-content-end">
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-12 text-right border-bottom mb-5">
                                        <h3 class="text-black h4 text-uppercase">Cart Total :<?=$subtotal?> sp</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="submit" value="Place Order" name="placeorder" class="btn btn-primary btn-lg btn-block">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?=template_footer()?>
