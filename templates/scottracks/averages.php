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
<div class="container">
    <?php
    $table_count = 1;

    $averages = $data['averages'];

    function cmp($a, $b) {
        $a = count($a);
        $b = count($b);
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : 1;
    }
    uasort($averages, 'cmp');

    foreach($averages as $airfieldName => $averageData):
        if ($averageData) :
    if ($table_count == 1): ?>
        <div class="row">
        <?php endif; ?>
            <div class="col-sm">
                    <h5><?php echo ucwords($airfieldName) ?></h5>
                    <table class="table table-bordered table-sm">
                        <thead>
                        <tr>
                            <th scope="col" class="col-xs-4">Date</th>
                            <th scope="col" class="col-xs-4">Average</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($averageData as $row) : ?>
                        <tr>
                            <td><?php echo $row['theDate'] ?></td>
                            <td><?php echo $row['avg_launch_climb_rate']?></td>
                        </tr>
                        </tbody>

                        <?php endforeach; ?>
                    </table>
                </div>
            <?php
            if ($table_count == 3):?>
                </div>
            <?php endif;
            $table_count = $table_count + 1;
            if ($table_count == 4) {
                $table_count = 1;
            }
            endif;
            endforeach;
            ?>
</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
