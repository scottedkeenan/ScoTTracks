<!doctype html>

<?php date_default_timezone_set('Europe/London'); ?>

<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>ScoTTracks</title>
</head>
<body>
<div>
    <a href="/" class="btn btn-primary">back</a>
</div>


<div class="d-flex flex-row">
    <?php
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

    if ($dateIndex +1 < count($dates)) {
        ?>
        <div class="p-2">
            <a href="<?php echo isset($data['airfield_name']) ? $data['airfield_name'] . '/' . $dates[$dateIndex+1] : $dates[$dateIndex+1]?>">prev</a>
        </div>
    <?php } else {?>
        <div class="p-2"></div>
    <?php } ?>
    <div class="p-2"><?php echo $data['show_date'] ?></div>
    <?php
    if ($dateIndex +1 > 1 ) {
        ?>
        <div class="p-2">
            <a href="<?php echo isset($data['airfield_name']) ? $data['airfield_name'] . '/' . $dates[$dateIndex-1] : $dates[$dateIndex-1]?>">next</a>
        </div>
    <?php } else {?>
        <div class="col-2"></div>
    <?php } ?>
</div>


<!--<form method="post" action="index.php">-->
<!--    <select id="date" name="dateOption">-->
<!--        --><?php
//        while($row = mysqli_fetch_assoc($select_all_dates_query)) { ?>
<!--            <option value="--><?php //echo $row['cast(reference_timestamp as date)']; ?><!--">--><?php //echo $row['cast(reference_timestamp as date)'];?><!--</option>-->
<!--        --><?php //}?>
<!--    </select>-->
<!--    <input type="submit" value="Filter"/>-->
<!--</form>-->


<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Registration / ID</th>
        <th scope="col">Launched</th>
        <th scope="col">Landed</th>
        <th scope="col">Duration (H:M:S)</th>
        <th scope="col">Launch Height (ft)</th>
        <th scope="col">Launch Method</th>
    </tr>
    </thead>
    <tbody>

    <?php

    $rowCount = 1;

    foreach ($data['flight_data'] as $row) {
        if (is_null($row['takeoff_timestamp']) && is_null($row['landing_timestamp'])) {
            continue;
        }

        $takeoff_time = $row['takeoff_timestamp'] ? new DateTime('@' . strtotime($row['takeoff_timestamp'])) : null;
        $takeoff_airfield = $row['takeoff_airfield'] ? $row['takeoff_airfield']: '--';


        $landing_time = $row['landing_timestamp'] ? new DateTime('@' . strtotime($row['landing_timestamp'])) : null;
        $landing_airfield = $row['landing_airfield'] ? $row['landing_airfield']: '--';

        $registration = $row['registration'] != 'UNKNOWN' ? $row['registration'] : $row['address'];
        $takeoff_timestamp = $takeoff_time ? $takeoff_time->format('H:i:s') : '--';
        $landing_timestamp = $landing_time ? $landing_time->format('H:i:s') : $row['status'];
        $launch_height = round($row['launch_height'] * 3.28084);
        if ($takeoff_time) {
            $graph_path = '/graphs/' . $registration . '-' . $takeoff_time->format('Y-m-d-H-i-s') . '.png'; //2020-12-01-15:02:55.png"
            if (!file_exists('.' . $graph_path)) {
                $graph_path = null;
            }
        } else {
            $graph_path = null;
        }
        if ($takeoff_time && $landing_time) {
            $duration = date_diff($takeoff_time, $landing_time)->format('%h:%I:%S');
        } elseif ($takeoff_time && !$landing_time) {
            $nowTime = new DateTime();
            $duration = date_diff($takeoff_time, $nowTime)->format('%h:%I:%S');
        } elseif (!$takeoff_time && $landing_time) {
            $duration = '--';
        }
        if ($row['aircraft_type'] == '2') {
            $launch_type = 'Tug';
        } elseif ($row['launch_type'] and $row['aircraft_type'] == '1') {
            $launch_type = ucwords($row['launch_type']);
        } else {
            $launch_type = '--';
        }
        ?>

        <tr>
            <th scope="row"><?php echo $rowCount; ?></th>
            <td><?php echo !is_null($graph_path) ? '<a href="' . $graph_path . '">' : ''?><?php echo $registration; ?></a></td>
            <td><?php echo $takeoff_timestamp; ?></td>
            <td><?php echo $landing_timestamp; ?></td>
            <td><?php echo $duration; ?></td>
            <td><?php echo $launch_height; ?></td>
            <td><?php echo $launch_type; ?></td>
        </tr>

        <?php
        $rowCount ++;
    }
    ?>
    </tbody>
</table>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
