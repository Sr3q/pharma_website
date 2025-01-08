<?php

    $num_products_on_each_page = 9;

    $current_page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;

    if(isset($_POST['search']) && $_POST['search']) {
        $stmt = $pdo->prepare('SELECT * FROM medicine where mname like ? ORDER BY indate DESC LIMIT ?,?');

        $sr ="%" . $_POST['search'] . "%";
        $stmt->bindValue(1, $sr,PDO::PARAM_STR);
        $stmt->bindValue(2, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
        $stmt->bindValue(3, $num_products_on_each_page, PDO::PARAM_INT);
        $stmt->execute();

        $total_products = $pdo->query("SELECT * FROM medicine where mname like '$sr' ")->rowCount();
    }
    else {
        $stmt = $pdo->prepare('SELECT * FROM medicine ORDER BY indate DESC LIMIT ?,?');

        $stmt->bindValue(1, ($current_page - 1) * $num_products_on_each_page, PDO::PARAM_INT);
        $stmt->bindValue(2, $num_products_on_each_page, PDO::PARAM_INT);
        $stmt->execute();

        $total_products = $pdo->query('SELECT * FROM medicine')->rowCount();
    }

    $products = $stmt->fetchAll();
?>

<?=template_header('shop')?>

    <div class="bg-light py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-0"><a href="index.php">Home</a> <span class="mx-2 mb-0">/</span> <strong class="text-black">Store</strong></div>
            </div>
        </div>
    </div>
    <div class="site-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 class="mb-3 h6 text-uppercase text-black d-block"><?=$total_products?> Products</h3>
                </div>
            </div>
            <div class="row">
        <?php foreach ($products as $prod): ?>
        <div class="col-sm-6 col-lg-4 text-center item mb-4">
            <?php if(!$prod['amount']) echo '<span class="tag">Sold</span>'; ?>
            <a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"> <img src="images/<?=$prod['image']?>" alt="Image" height="370" width="270"></a>
            <h3 class="text-dark"><a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"><?=$prod['mname']?></a></h3>
            <p class="price"><?=$prod['price']?>sp</p>
        </div>
        <?php endforeach;?>
    </div>

            <div class="row mt-5">
        <div class="col-md-12 text-center">
            <div class="site-block-27">
                <ul>
                    <?php if ($current_page > 1): ?>
                    <li><a href="index.php?page=shop&p=<?=$current_page-1?>">prev</a></li>
                    <?php endif;?>

                    <?php if ($total_products > ($current_page * $num_products_on_each_page) - $num_products_on_each_page + count($products)): ?>
                    <li><a href="index.php?page=shop&p=<?=$current_page+1?>">next</a></li>
                    <?php endif?>
                </ul>
            </div>
        </div>
    </div>
        </div>
    </div>

<?=template_footer()?>