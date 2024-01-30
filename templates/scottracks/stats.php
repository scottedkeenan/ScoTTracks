<!doctype html>

<?php date_default_timezone_set('Europe/London'); ?>

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
            <?php
            $topLaunchersData = $data['top_launchers'];
            $differenceData = $data['difference'];
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
                                    <td><a href="<?php echo sprintf('/airfields/%s',$top_launcher['id'])?>"> <?php echo $top_launcher['name'] ?></a></td>
                                    <td><?php echo $top_launcher['flight_count']?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <h5><?php
                $secondWeekDate = new DateTime($data['difference']['week_start_date']);
                $firstWeekDate = clone $secondWeekDate;
                $firstWeekDate->modify('-7 days');
                echo sprintf('Change in launches Week on Week %s / %s',
                    $firstWeekDate->format('d-m-Y'),
                    $secondWeekDate->format('d-m-Y')); ?></h5>
                <div class="row">
                    <div class="col">
                        <h6>Increases</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm w-auto">
                                <thead>
                                <tr>
                                    <th scope="col" class="col-xs-4">Airfield</th>
                                    <th scope="col" class="col-xs-4">Difference</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['difference']['week_on_week_pos'] as $difference) : ?>
                                        <tr>
                                            <td><a href="<?php echo sprintf('/airfields/%s',$difference['id'])?>"> <?php echo $difference['name'] ?></a></td>
                                            <td><?php echo $difference['difference']?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col">
                        <h6>Decreases</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm w-auto">
                                <thead>
                                <tr>
                                    <th scope="col" class="col-xs-4">Airfield</th>
                                    <th scope="col" class="col-xs-4">Difference</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['difference']['week_on_week_neg'] as $difference) : ?>
                                        <tr>
                                            <td><a href="<?php echo sprintf('/airfields/%s',$difference['id'])?>"> <?php echo $difference['name'] ?></a></td>
                                            <td><?php echo $difference['difference']?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
                                        <td><a href="<?php echo sprintf('/airfields/%s',$flight_times['id'])?>"> <?php echo $flight_times['name'] ?></a></td>
                                        <td><?php echo $flight_times['total_flight_time']?></td>
                                        <td><?php echo $flight_times['aircraft_count']?></td>
                                        <td><?php echo round($flight_times['minutes_per_aircaft'])?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
        </div>
    </div>
    <?php include('includes/footer.html'); ?>
    <?php include('includes/navbar_toggle.php'); ?>
</body>
</html>
