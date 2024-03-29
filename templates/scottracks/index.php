<!doctype html>

<?php date_default_timezone_set('Europe/London');?>

<?php

function url($url) {
    $url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    $url = trim($url, "-");
    $url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    return $url;
}
?>

<html lang="en">
<head>
    <?php include('includes/head.phtml'); ?>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>

        <div id="content">

            <!-- Navbar -->
            <?php include('includes/navbar.php'); ?>

<!--            <div class="jumbotron jumbotron-fluid">-->
<!--                <div class="container">-->
<!--                    <h2 class="display-4">Welcome to my tracking site.</h2>-->
<!--                    <p>Below may be a list of gliding clubs or sites for which logs exist. If there is a number next to a site name, launches have been detected there today. Click a site name to take a look.</p>-->
<!--                    <small>Disclaimer: data may (will) be incorrect or missing, so logkeepers should practice use of their MK.I eyeballs to look out.</small>-->
<!--                </div>-->
<!--            </div>-->
            <div class="row">
                <div class="col float-right">
                    <!-- Date navigator -->
                    <?php include('includes/date_navigator_index.php'); ?>
                </div>
            </div>
            <!-- Airfields/flights list -->
            <?php include('includes/airfields_flights_list.php'); ?>
        </div>
    </div>
    <?php include('includes/footer.html'); ?>
    <?php include('includes/navbar_toggle.php'); ?>
</body>
</html>
