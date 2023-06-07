<?php
$sqlCount = "SELECT COUNT(*) AS total_records FROM discount";
$resultCount = executeSingleResult($sqlCount);
$totalRecords = $resultCount['total_records'];
?>

<div class="col-lg-8">
    <div id="carouselExample" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php for ($i = 0; $i < $totalRecords; $i++) { ?>
                <li data-target="#carouselExample" data-slide-to="<?php echo $i; ?>" <?php echo ($i == 0) ? 'class="active"' : ''; ?>></li>
            <?php } ?>
        </ol>

        <!-- Slides -->
        <div class="carousel-inner">
            <?php
            $discountiscounts = executeResult("SELECT * FROM discount");
            $count = 0;
            foreach ($discountiscounts as $discount) {
                $imagePath = $discount['banner'];
                $startDate = $discount['startDate'];
                $endDate = $discount['expiration_date'];
                $activeClass = ($count == 0) ? 'active' : '';

                // Tính thời gian còn lại
                $startTime = strtotime($startDate) * 1000;
                $endTime = strtotime($endDate) * 1000;
                $remainingTime = max(($endTime - time()), 0);
            ?>
                <div class="carousel-item <?php echo $activeClass; ?>">
                    <img style="width: 802px; height:153px;" src="<?php echo $imagePath; ?>" alt="Slide <?php echo ($count + 1); ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <p style="color:white; font-weight:bold; font-size:1.5rem;" id="countdown-<?php echo $discount['id']; ?>"></p>
                    </div>
                    <script>
                        // Đếm ngược thời gian
                        var countdownDate<?php echo $discount['id']; ?> = new Date("<?php echo  $discount['expiration_date']; ?>").getTime();
                        var countdownElement<?php echo $discount['id']; ?> = document.getElementById("countdown-<?php echo $discount['id']; ?>");

                        var countdownTimer<?php echo $discount['id']; ?> = setInterval(function() {
                            var now = new Date().getTime();
                            var distance = countdownDate<?php echo $discount['id']; ?> - now;

                            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                            countdownElement<?php echo $discount['id']; ?>.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";

                            if (distance < 0) {
                                clearInterval(countdownTimer<?php echo $discount['id']; ?>);
                                countdownElement<?php echo $discount['id']; ?>.innerHTML = "Expired";
                            }
                        }, 1000);
                    </script>
                </div>
            <?php
                $count++;
            }
            ?>
        </div>
    </div>
</div>