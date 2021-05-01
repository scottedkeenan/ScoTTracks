<!doctype html>

<?php
$siteTimezone = new DateTimeZone('Europe/London');
$trackerTimezone = new DateTimeZone('UTC');
$offset = $siteTimezone->getOffset($trackerTimezone);
?>


<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

            <?php
                // build dates array
                $dates = [];
                sort($dates);
                foreach ($data['dates'] as $row) {
                    array_push($dates, $row['cast(reference_timestamp AS date)']);
                }
                array_push($dates, date('Y-m-d'));
                $dates = array_unique($dates);

                function date_sort($a, $b) {
                    return strtotime($b) - strtotime($a);
                }
                usort($dates, "date_sort");

                $dateIndex = array_search($data['show_date'] ,$dates);
            ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <h4><?php echo ucwords($data['airfield_name']);?></h4>
                    </div>

                    <div class="col">

                        <div class="col">
                            <?php if ($dateIndex +1 > 1 ): ?>
                                <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '/' . $dates[$dateIndex-1] : $dates[$dateIndex-1]?>" class="btn btn-primary float-right">next</a>
                            <?php else: ?>
                                <a class="btn btn-primary float-right disabled">next</a>
                            <?php endif; ?>
                        </div>


                        <div class="col">
                            <?php if ($dateIndex +1 < count($dates)): ?>
                                <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '/' . $dates[$dateIndex+1] : $dates[$dateIndex+1]?>" class="btn btn-primary float-right">prev</a>
                            <?php else: ?>
                                <a class="btn btn-primary float-right disabled">prev</a>
                            <?php endif; ?>
                        </div>

                        <div class="col">
                            <h6 class="btn float-right"><?php echo str_replace('-', '‑', $data['show_date']); ?></h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
            <!--    <table class="table table-responsive table-condensed table-sm">-->
                <table class="DailyFlights-table table table-sm">
                    <thead>
                    <tr>
                        <th scope="col" class="align-top">#</th>
                        <th scope="col" class="align-top">Reg</th>
                        <th scope="col" class="align-top">Launch</th>
                        <th scope="col" class="align-top">Land</th>
                        <th scope="col" class="align-top">Duration</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $rowCount = 1;

                    foreach ($data['flight_data'] as $row) {
                        if (is_null($row['takeoff_timestamp']) && is_null($row['landing_timestamp'])) {
                            continue;
                        }
                        
                        $takeoff_time = $row['takeoff_timestamp'] ? new DateTime($row['takeoff_timestamp'], $trackerTimezone) : null;
                        if ($takeoff_time) {
                            $takeoff_time->setTimeZone($siteTimezone);
                        }

                        $takeoff_airfield = $row['takeoff_airfield'] ? $row['takeoff_airfield']: '--';

                        $landing_time = $row['landing_timestamp'] ? new DateTime($row['landing_timestamp'], $trackerTimezone) : null;
                        if ($landing_time) {
                            $landing_time->setTimeZone($siteTimezone);
                        }
                        
                        
                        $landing_airfield = $row['landing_airfield'] ? $row['landing_airfield']: '--';

                        $registration = $row['registration'] != 'UNKNOWN' ? $row['registration'] : $row['address'];
                        
                        $takeoff_timestamp = $takeoff_time ? $takeoff_time->format('H:i:s') : '--';

                        $landing_timestamp = $landing_time ? $landing_time->format('H:i:s') : $row['status'];
                        $launch_height = round($row['launch_height'] * 3.28084);

                        if ($takeoff_time && $landing_time) {
                            $graph_path = '/graphs/' . $registration . '-' . $takeoff_time->format('Y-m-d-H-i-s') . '.png'; //2020-12-01-15:02:55.png"
                            if (!(file_exists('.' . $graph_path))) {
                                $graph_path = 'https://scotttracks-graphs.s3-eu-west-1.amazonaws.com/graphs/' . $row['address'] . '-' .  (new DateTime($row['takeoff_timestamp']))->format('Y-m-d-H-i-s') . '.png'; //2020-12-01-15:02:55.png"
                            }

                        } else {
                            $graph_path = null;
                        }
                        
                        if ($takeoff_time && $landing_time) {
                            $duration = date_diff($takeoff_time, $landing_time)->format('%h:%I:%S');
                        } elseif ($takeoff_time && !$landing_time) {
                            $nowTime = new DateTime('now', $siteTimezone);
                            $duration = date_diff($takeoff_time, $nowTime)->format('%h:%I:%S');

                        } elseif (!$takeoff_time && $landing_time) {
                            $duration = '--';
                        }
                        if ($row['aircraft_type'] == '2') {
                            if (isset($row['tug_registration'])) {
                                $launch_type = 'Tug for ' . $row['tug_registration'];
                            } else {
                                $launch_type = 'Tug';
                            }
                        } elseif ($row['launch_type'] and $row['aircraft_type'] == '1') {
                            if ($row['launch_type'] == 'aerotow_glider') {
                                if (isset($row['tug_registration'])) {
                                    $launch_type = 'Aerotowed by ' . $row['tug_registration'];
                                }
                            } elseif ($row['launch_type'] == 'aerotow_pair') {
                                $launch_type = 'Aerotow with ' . $row['tug_registration'];
                            } elseif ($row['launch_type'] == 'aerotow_sl') {
                                // $launch_type = 'Aerotow (unknown tug) or Self-Launch';
                                $launch_type = '';
                            } else {
                                $launch_type = ucwords($row['launch_type']);
                            }
                        } else {
                            $launch_type = '--';
                        }
                        ?>

                        <tr>
                            <td class="daily-flights-ln-num"><?php echo $rowCount; ?></td>
                            <td class="daily-flights">
                                <div>
                                    <?php echo !is_null($graph_path) ? '<a href="' . $graph_path . '">' : ''?><?php echo str_replace('-', '‑', $registration);?><?php echo !is_null($graph_path) ? '</a>' : ''?>
                                </div>
                                <div class="detailed">
                                    <?php if ($row['aircraft_model']):
                                        echo $row['aircraft_model'];
                                    endif; ?>
                                    <?php if ($row['competition_number']):
                                        echo ' | ' . $row['competition_number'];
                                    endif; ?>
                                </div>
                            </td>
                            <td class="daily-flights">
                                <div>
                                    <?php echo $takeoff_timestamp; ?>
                                </div>
                                <div class="detailed">
                                    <?php if ($launch_type !== '--'): ?>
                                        <?php if (is_null($launch_height) || !((int)$launch_height <= 0)): ?>
                                            <?php echo $launch_height . ' ft | '; ?>
                                        <?php endif; ?>
                                            <?php echo str_replace('-', '‑', $launch_type); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="daily-flights"><?php echo $landing_timestamp; ?></td>
                            <td class="daily-flights"><?php echo $duration; ?></td>
                        </tr>

                        <?php
                        $rowCount ++;
                    }
                    ?>
                    </tbody>
                </table>
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
