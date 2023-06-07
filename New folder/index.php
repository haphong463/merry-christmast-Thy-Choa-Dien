<?php
require_once('db/dbhelper.php');
$sql = "SELECT * FROM product_category";
$p_category = executeResult($sql);

$sql_category = "SELECT * FROM category";
$category = executeResult($sql_category);
?>

<!-- Header Section Begin -->
<?php include('layout/header.php') ?>
<!-- Header Section End -->

<!-- Hero Slider Begin -->
<section class="hero-slider">
    <div class="hero-items owl-carousel">
        <?php
        $sql = "SELECT * FROM slider";
        $slider = executeResult($sql);

        if ($slider != null) {
            foreach ($slider as $s) {
                $year = $s['year'];
                $image = $s['image'];
                $heading = $s['heading'];
                echo '
    
                <div class="single-slider-item set-bg" data-setbg="' . $image . '">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <h1>' . $year . '</h1>
                                <h2>' . $heading . '</h2>
                            </div>
                        </div>
                    </div>
                </div>
    
                ';
            }
        }
        ?>
    </div>
</section>
<!-- Hero Slider End -->


<section class="features-section spad">
    <div class="features-ads">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="single-features-ads first">
                        <img src="assets/img/icons/f-delivery.png" alt="">
                        <h4>Free shipping</h4>
                        <p>Fusce urna quam, euismod sit amet mollis quis, vestibulum quis velit. Vesti bulum mal
                            esuada aliquet libero viverra cursus. </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-features-ads second">
                        <img src="assets/img/icons/coin.png" alt="">
                        <h4>100% Money back </h4>
                        <p>Urna quam, euismod sit amet mollis quis, vestibulum quis velit. Vesti bulum mal esuada
                            aliquet libero viverra cursus. </p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single-features-ads">
                        <img src="assets/img/icons/chat.png" alt="">
                        <h4>Online support 24/7</h4>
                        <p>Urna quam, euismod sit amet mollis quis, vestibulum quis velit. Vesti bulum mal esuada
                            aliquet libero viverra cursus. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- Latest Section Begin -->
<!-- Latest Product Begin -->
<section class="latest-products spad">
    <div class="container">
        <div class="product-filter">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="section-title">
                        <h2>Latest Products</h2>
                    </div>
                    <ul class="product-controls">
                        <li data-filter="*">All</li>
                        <?php
                        foreach ($p_category as $c) {

                        ?>
                            <li data-filter=".<?php echo strtolower($c['p_cat_name']) ?>"><?php echo $c['p_cat_name'] ?></li>
                        <?php
                        }

                        ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row" id="product-list">
            <?php
            $product_info = "SELECT product.*, product_thumbnail.thumbnail
                    FROM product
                    LEFT JOIN (
                        SELECT pid, MIN(id) AS min_id
                        FROM product_thumbnail
                        GROUP BY pid
                    ) AS sub ON product.pid = sub.pid
                    LEFT JOIN product_thumbnail ON sub.min_id = product_thumbnail.id LIMIT 8";
            $resultInfo = executeResult($product_info);

            foreach ($resultInfo as $info) {
                $pid = $info['pid'];
                $name = $info['name'];
                $price = $info['price'];
                $imagePath = $info['thumbnail'];

                $p_cat_name = '';
                $cat_name = '';
                foreach ($p_category as $c) {
                    if ($info['p_cat_id'] == $c['p_cat_id']) {
                        $p_cat_name = strtolower($c['p_cat_name']);
                        break;
                    }
                }
            ?>
                <div class="col-lg-3 col-sm-12 mix all <?php echo $p_cat_name ?>">
                    <div class="single-product-item">
                        <figure>
                            <a href="product-page.php?pid=<?php echo $pid ?>"><img src="<?php echo $imagePath ?>" alt=""></a>
                            <div class="p-status">
                                <?php
                                $createdDate = $info['created_at'];
                                $currentDate = date('Y-m-d');
                                $dateDiff = floor((strtotime($currentDate) - strtotime($createdDate)) / (60 * 60 * 24));

                                if ($dateDiff < 7) {
                                    echo 'NEW';
                                }
                                ?>
                            </div>
                        </figure>
                        <div class="product-text">
                            <h6><?php echo $name ?></h6>
                            <p>$<?php echo $price ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>
<!-- Latest Product End -->
<!-- Latest Section End -->




<!-- Lookbok Section Begin -->
<section class="lookbok-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4 offset-lg-1">
                <div class="lookbok-left">
                    <div class="section-title">
                        <h2>2019 <br />#lookbook</h2>
                    </div>
                    <p>Fusce urna quam, euismod sit amet mollis quis, vestibulum quis velit. Vestibulum malesuada
                        aliquet libero viverra cursus. Aliquam erat volutpat. Morbi id dictum quam, ut commodo
                        lorem. In at nisi nec arcu porttitor aliquet vitae at dui. Sed sollicitudin nulla non leo
                        viverra scelerisque. Phasellus facilisis lobortis metus, sit amet viverra lectus finibus ac.
                        Aenean non felis dapibus, placerat libero auctor, ornare ante. Morbi quis ex eleifend,
                        sodales nulla vitae, scelerisque ante. Nunc id vulputate dui. Suspendisse consectetur rutrum
                        metus nec scelerisque. s</p>
                    <a href="#" class="primary-btn look-btn">See More</a>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="lookbok-pic">
                    <img src="assets/img/lookbok.jpg" alt="">
                    <div class="pic-text">fashion</div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- Lookbok Section End -->


<?php include('layout/footer.php') ?>