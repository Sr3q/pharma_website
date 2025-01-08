<?php
    if(isset($_GET['billnum'])){
        $billnum=$_GET['billnum'];

        $stmt = $pdo->prepare("select * from billinfo where billnum like ?");
        $stmt->execute([$billnum]);
        $billinfo=$stmt->fetchall();
    }
    else {
        header("Location:index.php");
        exit();
    }
?>

<?=template_header('Bill')?>

<div class="bg-light py-3">
    <div class="container">
        <div class="row">
            <div class="col-md-12 mb-0">
                <a href="index.php">Home</a> <span class="mx-2 mb-0">/</span>
                <strong class="text-black">Bill</strong>
            </div>
        </div>
    </div>
</div>

<div class="site-section">
    <div class="container">
        <div class="row mb-5">
            <div class="title-section text-center col-12">
                <h2 class="text-uppercase">bill number <?= $billnum ?></h2>
            </div>
            <form class="col-md-12" method="post">
                <div class="site-blocks-table">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="product-thumbnail">Image</th>
                            <th class="product-name">Product</th>
                            <th class="product-price">Price</th>
                            <th class="product-quantity" style="padding: 15px">Quantity</th>
                            <th class="product-total">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $totbiilpri = 0;
                        foreach($billinfo as $row){
                            echo '<tr>';
                                $totbiilpri += $row['price'] * $row['amount'];
                                ?>
                                <td class="product-thumbnail">
                                    <a href="index.php?page=shop-single&mname=<?=$row['mname']?>">
                                        <img src="images/<?=$row['image']?>" alt="Image" class="img-fluid">
                                    </a>
                                </td>
                                <td class="product-name">
                                    <a href="index.php?page=shop-single&mname=<?=$row['mname']?>"
                                        <h2 class="h5 text-black"><?=$row['mname']?></h2>
                                    </a>
                                </td>
                                <td><?=$row['price']?></td>
                                <td><?=$row['amount']?></td>
                                <td><?=$row['price'] * $row['amount']?></td>
                        <?php echo '</tr>'; }
                        ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4">
                                <h2 class="h5 text-black">total bill price </h2>
                            </td>
                            <td>
                                <?=$totbiilpri?>
                            </td>

                        </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>


    </div>
</div>

<?=template_footer()?>
