<!doctype html>

<?php date_default_timezone_set('Europe/London'); ?>

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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Sidebar meta -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/css/sidebar.css" />

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>

    <title>ScoTTracks</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>

        <div id="content">

            <!-- Navbar -->
            <?php include('includes/navbar.php'); ?>

            <div>
                <a href=<?php echo sprintf("/airfields/%s?date=%s",$data['flight']['takeoff_airfield'], $data['flight']['launch_date']) ?> >< Back</a>
            </div>

            <?php
            $flight_data = $data['flight'];
            ?>
            <div>
                <table class="table table-sm small">
                    <tbody>
                    <tr>
                        <th scope="row">Reg</th>
                        <td><?php echo $flight_data['registration']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Model</th>
                        <td><?php echo $flight_data['aircraft_model']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Takeoff</th>
                        <td><?php echo sprintf(
                                '%s @ %s',
                                $flight_data['takeoff_timestamp'],
                                $flight_data['takeoff_airfield_name']
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Landing</th>
                        <td><?php echo sprintf(
                                '%s @ %s',
                                $flight_data['landing_timestamp'],
                                $flight_data['landing_airfield_name']
                            ) ?>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Duration</th>
                        <td><?php echo $flight_data['duration'];?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <div class="col-sm-8">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe src=<?php echo $data['flight_map_url']?> title="Map of flight"></iframe>
                    </div>
                </div>
                <div class="col-sm-8">
                    <img class="img-fluid" src=<?php echo $data['flight_graph_url']?> title="Speed and Height of flight"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    <?php include('includes/navbar_toggle.php'); ?>

</body>
</html>
