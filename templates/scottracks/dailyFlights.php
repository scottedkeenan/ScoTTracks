<!doctype html>

<?php
$siteTimezone = new DateTimeZone('Europe/London');
$trackerTimezone = new DateTimeZone('UTC');

// Create a DateTime object with the current time and the tracker timezone
$currentTimeInTrackerTimezone = new DateTime('now', $trackerTimezone);

// Get the offset using the DateTime object in the site timezone
$offset = $siteTimezone->getOffset($currentTimeInTrackerTimezone);
?>


<html lang="en">
<head>
    <?php include('includes/head.phtml'); ?>
    <title></title>
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
                foreach ($data['dates'] as $row) {
                    $dates[] = $row['flight_date'];
                }
                $dates[] = date('Y-m-d');
                $dates[] = $data['show_date'];
                $dates = array_unique($dates);
                sort($dates);
                function date_sort($a, $b) {
                    return strtotime($b) - strtotime($a);
                }
                usort($dates, "date_sort");

                $dateIndex = array_search($data['show_date'], $dates);

                // -24 hours for OGN data policy (no redistribute)
                $distributionLandingCutoff = new DateTime('now -24 Hours');
                ?>

            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-1">
                        <h4><?php echo $data['airfield_name'];?></h4>
                    </div>

                    <div class="col">
                        <!-- Date navigator -->
                        <?php include('includes/date_navigator.php'); ?>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
<!--                <table class="table table-responsive table-condensed table-sm">-->
                <table class="DailyFlights-table table table-sm">
                    <thead>
                    <tr>
                        <th scope="col" class="align-top">
                            <?php if ($data['order_by'] === 'asc'): ?>
                                <a href="<?php echo $data['airfield_id'] . '?date=' . $dates[$dateIndex] . '&order_by=desc'?>" class="align-top">#</a>
                            <?php else: ?>
                                <a href="<?php echo $data['airfield_id'] . '?date=' . $dates[$dateIndex] . '&order_by=asc'?>" class="align-top">#</a>
                            <?php endif; ?>
                        </th>
                        <th scope="col" class="align-top">Reg</th>
                        <th scope="col" class="align-top">Launch</th>
                        <th scope="col" class="align-top">Land</th>
                        <th scope="col" class="align-top">Duration</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    if ($data['order_by'] === 'desc') {
                        $rowCount = count($data['flight_data']);
                    } else {
                        $rowCount = 1;
                    }

                    foreach ($data['flight_data'] as $row) {
                        if (is_null($row['takeoff_timestamp']) && is_null($row['landing_timestamp'])) {
                            continue;
                        }
                        
                        $takeoff_time = $row['takeoff_timestamp'] ? new DateTime($row['takeoff_timestamp'], $trackerTimezone) : null;
                        if ($takeoff_time) {
                            $takeoff_time->setTimeZone($siteTimezone);
                        }

                        $takeoff_airfield = $row['takeoff_airfield'] ?: '--';

                        $landing_time = $row['landing_timestamp'] ? new DateTime($row['landing_timestamp'], $trackerTimezone) : null;
                        if ($landing_time) {
                            $landing_time->setTimeZone($siteTimezone);
                        }
                        
                        
                        $landing_airfield = $row['landing_airfield'] ? $row['landing_airfield']: '--';

                        $registration = $row['registration'] != 'UNKNOWN' ? $row['registration'] : $row['address'];
                        
                        $takeoff_timestamp = $takeoff_time ? $takeoff_time->format('H:i:s') : '--';

                        $landing_timestamp = $landing_time ? $landing_time->format('H:i:s') : $row['status'];
                        $launch_height = round($row['launch_height'] * 3.28084);
                        $chartLeadTime = new DateTime('now -11 Minutes');
                        
                        $flight_path = null;
                        // if ($data['airfield_followed'] && $takeoff_time && $landing_time) {
                        // if ($takeoff_time && $landing_time) {
                        //     if ($chartLeadTime > $landing_time && $landing_time > $distributionLandingCutoff) {
                        //         $takeoff_time_utc = new DateTime($row['takeoff_timestamp'], $trackerTimezone);
                        //         $flight_path = '/flight/' . $row['address'] . '/' . $takeoff_time_utc->format('Y-m-d-H-i-s');
                        //     }
                        // }

                        
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
                                    <?php echo !is_null($flight_path) ? '<a href="' . $flight_path . '">' : ''?><?php echo str_replace('-', '‑', $registration);?><?php echo !is_null($flight_path) ? '</a>' : ''?>
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
                                    <?php if (!is_null($row['takeoff_airfield_name']) && $row['takeoff_airfield_name'] != $data['airfield_name']):
                                        echo sprintf('@ %s', $row['takeoff_airfield_name']);
                                    endif; ?>
                                </div>
                            </td>
                            <td class="daily-flights">
                                <div>
                                    <?php echo $landing_timestamp; ?>
                                </div>
                                    <div class="text-center">
                                    <?php echo !is_null($flight_path) ?
                                        '<a href="' . $flight_path . '">' .
                                        '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16"><path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/></svg>' .
                                        '</a>' : ''?>
                                    </div>
                                <div class="detailed">
                                    <?php if (!is_null($row['landing_airfield_name']) && $row['landing_airfield_name'] != $data['airfield_name']):
                                        echo sprintf('@ %s', $row['landing_airfield_name']);
                                    endif; ?>
                                </div>
                            </td>
                            <td class="daily-flights"><?php echo $duration; ?></td>
                        </tr>

                        <?php
                        if ($data['order_by'] === 'desc') {
                            $rowCount --;
                        } else {
                            $rowCount ++;
                        }

                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include('includes/footer.html'); ?>
    <?php include('includes/navbar_toggle.php'); ?>
</body>
</html>
