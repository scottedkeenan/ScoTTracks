<!doctype html>

<?php date_default_timezone_set('Europe/London'); ?>

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

        <div class="container" id="content">

            <!-- Navbar -->
            <?php include('includes/navbar.php'); ?>
            <?php
            $table_count = 1;

            $differenceData = $data['difference'];
            $topLaunchersData = $data['top_launchers']
            ?>

                <h5>Top airfields this week</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm w-auto">
                        <thead>
                        <tr>
                            <th scope="col" class="col-xs-4">Airfield</th>
                            <th scope="col" class="col-xs-4">Launches this week</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($topLaunchersData as $top_launcher) : ?>
                        <tr>
                            <td><?php echo $top_launcher['name'] ?></td>
                            <td><?php echo $top_launcher['flight_count']?></td>
                        </tr>
                        </tbody>

                        <?php endforeach; ?>
                    </table>
                </div>

                    <h5><?php
                        $secondWeekDate = new DateTime($data['difference']['week_start_date']);
                        $firstWeekDate = clone $secondWeekDate;
                        $firstWeekDate->modify('-7 days');
                        echo sprintf('Change in launches Week on Week %s / %s',
                            $firstWeekDate->format('d-m-Y'),
                            $secondWeekDate->format('d-m-Y')); ?></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm w-auto">
                            <thead>
                            <tr>
                                <th scope="col" class="col-xs-4">Airfield</th>
                                <th scope="col" class="col-xs-4">% Change</th>
                                <th scope="col" class="col-xs-4">Difference</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($data['difference']['week_on_week_data'] as $difference) : ?>
                            <tr>
                                <td><?php echo $difference['name'] ?></td>
                                <td><?php echo round($difference['percentage_change'], 2)?></td>
                                <td><?php echo $difference['difference']?></td>
                            </tr>
                            </tbody>

                            <?php endforeach; ?>
                        </table>
                    </div>

                    <h5><?php
                        $startDate = new DateTime($data['difference']['week_start_date']);
                        echo sprintf('Flight times for week starting %s',
                            $startDate->format('d-m-Y'))?></h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm w-auto">
                            <thead>
                            <tr>
                                <th scope="col" class="col-xs-4">Airfield</th>
                                <th scope="col" class="col-xs-4">Total Flight Time</th>
                                <th scope="col" class="col-xs-4">Number of Aircraft</th>
                                <th scope="col" class="col-xs-4">Minutes per Aircraft</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($data['flight_times'] as $flight_times) : ?>
                            <tr>
                                <td><?php echo $flight_times['name'] ?></td>
                                <td><?php echo $flight_times['total_flight_time']?></td>
                                <td><?php echo $flight_times['aircraft_count']?></td>
                                <td><?php echo round($flight_times['minutes_per_aircaft'])?></td>
                            </tr>
                            </tbody>

                            <?php endforeach; ?>
                        </table>
                    </div>
            </div>
        </div>
    </div>

    <?php include('includes/footer.html'); ?>
    <?php include('includes/navbar_toggle.php'); ?>

</body>
</html>
