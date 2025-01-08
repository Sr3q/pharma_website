<?php
    // Get the 4 most recently added products
    $stmt = $pdo->prepare('SELECT * FROM medicine ORDER BY salnum DESC LIMIT 6');
    $stmt->execute();
    $popular_products = $stmt->fetchAll();

    $stmt = $pdo->prepare('SELECT * FROM medicine ORDER BY indate DESC LIMIT 4');
    $stmt->execute();
    $recently_added_products = $stmt->fetchAll();
?>

<?=template_header('Home')?>

<div class="site-blocks-cover" style="background-image: url('images/hero_1.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 mx-auto order-lg-2 align-self-center">
                <div class="site-block-cover-content text-center">
                    <h2 class="sub-title">Effective Medicine, New Medicine Everyday</h2>
                    <h1>Welcome To Pharma</h1>
                    <p>
                        <a href="index.php?page=shop" class="btn btn-primary px-5 py-3">Shop Now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row align-items-stretch section-overlap">
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap bg-primary h-100">
                    <a href="#" class="h-100">
                        <h5>All medicines <br> are available</h5>
                        <p>
                            you can order any medicine you want
                            <strong>what are you waiting for!</strong>
                        </p>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap h-100">
                    <a href="#" class="h-100">
                        <h5>Season <br> Sale 50% Off</h5>
                        <p>
                            more bills you have more sale you get
                            <strong> buy one and get one free!</strong>
                        </p>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
                <div class="banner-wrap bg-warning h-100">
                    <a href="#" class="h-100">
                        <h5>shipping your medicines </h5>
                        <p>
                            we can offer a big amount of the medicine you want
                            <strong>buy now</strong>
                        </p>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">Popular Products</h2>
            </div>
        </div>

        <div class="row">
            <?php foreach ($popular_products as $prod): ?>
            <div class="col-sm-6 col-lg-4 text-center item mb-4">
                <?php if(!$prod['amount']) echo '<span class="tag">Sold</span>'; ?>
                <a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"> <img src="images/<?=$prod['image']?>" alt="Image" height="370" width="270"></a>
                <h3 class="text-dark"><a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"><?=$prod['mname']?></a></h3>
                <p class="price"><?=$prod['price']?>sp</p>
            </div>
            <?php endforeach;?>
        </div>
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="index.php?page=shop" class="btn btn-primary px-4 py-3">View All Products</a>
            </div>
        </div>
    </div>
</div>

<div class="site-section bg-light">
    <div class="container">
        <div class="row">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">New Products</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 block-3 products-wrap">
                <div class="nonloop-block-3 owl-carousel">

                    <?php foreach ($recently_added_products as $prod):?>
                    <div class="text-center item mb-4">
                        <?php if(!$prod['amount']) echo '<span class="tag">Sold</span>'; ?>
                        <a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"> <img src="images/<?=$prod['image']?>" alt="Image" height="370" width="270"></a>
                        <h3 class="text-dark"><a href="index.php?page=shop-single&mname=<?=$prod['mname']?>"><?=$prod['mname']?></a></h3>
                        <p class="price"><?=$prod['price']?>sp</p>
                    </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">Testimonials</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 block-3 products-wrap">
                <div class="nonloop-block-3 no-direction owl-carousel">

                    <div class="testimony">
                        <blockquote>
                            <img src="images/person.jpg" alt="Image" class="img-fluid w-25 mb-4 rounded-circle" height="80px">
                            <p>&ldquo;This website is amazing!, it helps a lot especially if you don't have time to go to a pharmacy ,since i started using this website buying medicines become an amusement to me.&rdquo;</p>
                        </blockquote>

                        <p>&mdash; Joudy Sarraj</p>
                    </div>

                    <div class="testimony">
                        <blockquote>
                            <img src="images/person1.jpg" alt="Image" class="img-fluid w-25 mb-4 rounded-circle" height="80px">
                            <p>&ldquo;I really like this website ,you can find missing medicines here, and it's easy to use are very affordable.&rdquo;</p>
                        </blockquote>

                        <p>&mdash; Lama Asaya</p>
                    </div>

                    <div class="testimony">
                        <blockquote>
                            <img src="images/person2.jpg" alt="Image" class="img-fluid w-25 mb-4 rounded-circle" height="80px">
                            <p>&ldquo;I find it really helpfull to use this website ,their offers are quite good &rdquo;</p>
                        </blockquote>

                        <p>&mdash; Mohammed Nour Alkhatib</p>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>

<?=template_footer()?>
