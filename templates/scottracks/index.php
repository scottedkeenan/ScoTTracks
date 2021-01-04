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

<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h2 class="display-4">Welcome to my tracking site.</h2>
        <p>Below should be a list of gliding clubs or sites for which logs exist. If there is a number next to a site name, launches have been detected there today. Click a site name to take a look.</p>
        <small>Disclaimer: data may (will) be incorrect or missing.</small>
    </div>
</div>

<div class="container col-sm-">
    <div class="row">
        <div class="col-sm">
            <ul class="list-group">
                <?php
                foreach ($data['flown_today'] as $airfieldName=>$flights):
                    $url = sprintf('/%s', $airfieldName);
                    ?>
                    <a href="<?php echo $url;?>" class="list-group-item list-group-item-action">
                        <?php echo ucwords(str_replace('_', ' ', $airfieldName)) ?>
                        <span class="badge badge-primary badge-pill"><?php echo $flights; ?></span>
                    </a>
                <? endforeach; ?>
                <?php
                sort($data['airfield_names']);
                foreach ($data['airfield_names'] as $airfieldName):
                    if (!in_array($airfieldName, array_keys($data['flown_today']))):
                        $url = sprintf('/%s', $airfieldName); ?>
                        <a href="<?php echo $url;?>" class="list-group-item list-group-item-action">
                            <?php echo ucwords(str_replace('_', ' ', $airfieldName)) ?>
                        </a>
                    <?php endif; ?>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
