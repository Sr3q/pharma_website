<?php
// Check to make sure the id parameter is specified in the URL
    if (isset($_GET['mname'])) {
        // Prepare statement and execute, prevents SQL injection
        $stmt = $pdo->prepare('SELECT * FROM medicine WHERE mname like ?');
        $stmt->execute([$_GET['mname']]);
        // Fetch the product from the database and return the result as an Array
        $product = $stmt->fetch();
        // Check if the product exists (array is not empty)
        if (!$product) {
            // Simple error to display if the id for the product doesn't exists (array is empty)
            exit('Product does not exist!');
        }
    } else {
        // Simple error to display if the id wasn't specified
        exit('Product does not exist!');
    }
?>


<?=template_header('shop-single')?>

    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <a
                            href="index.php?page=shop">Store</a> <span class="mx-2 mb-0">/</span> <strong class="text-black"><?=$_GET['mname']?></strong></div>
            </div>
        </div>
    </div>
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mr-auto">
                    <div class="border text-center">
                        <img src="images/<?=$product['image']?>" alt="Image" class="img-fluid p-5">
                    </div>
                </div>
                <div class="col-md-6">
                    <h2 class="text-black"><?=$product['mname']?></h2>
                    <p><?=$product['info']?>.</p>
                    Age Group : <?=$product['agegroup']?>
                    <p><strong class="text-primary h4"><?=$product['price']?>sp</strong></p>


                    <form action="index.php?page=cart" method="post">
                        <div class="mb-5">
                            <div class="input-group mb-3" style="max-width: 220px;">
                                <input type="number" class="form-control text-center" value="1" placeholder=""
                                       aria-label="Example text with button addon" aria-describedby="button-addon1"
                                        name="amount" min="1" max="<?=$product['amount']?>" required>
                            </div>

                        </div>
                        <input type="hidden" name="mname" value="<?=$product['mname']?>">
                        <p><input type="submit" value="Add To Cart" class="buy-now btn btn-sm height-auto px-4 py-3 btn-primary"></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?=template_footer()?>