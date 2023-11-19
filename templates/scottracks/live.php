<!doctype html>

<?php date_default_timezone_set('Europe/London');

//die(print_r($data));

?>


<html lang="en">
<head>
    <?php include('includes/head.phtml'); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=1024, user-scalable=no">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        #content {
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        #map {
            flex: 1;
            height: 100%;
        }
    </style>
    <link rel="stylesheet" href="css/leaflet.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.6.4/leaflet.ie.css" />
    <![endif]-->
    <script src="js/leaflet/leaflet-src.js"></script>
    <script type="text/javascript" src="js/leaflet-ajax/dist/leaflet.ajax.js"></script>
    <!--<script src="spin.js"></script>-->
    <!--<script src="leaflet.spin.js"></script>-->

    <title>Leaflet AJAX</title>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include('includes/sidebar.php'); ?>

        <div id="content">

            <!-- Navbar -->
            <?php include('includes/navbar.php'); ?>

            <div id="map"></div>
            <script type="text/javascript">

                // Access the data passed from PHP
                var data = <?= json_encode($data) ?>;
                var m = L.map('map').setView([52.561911, -1.464858], 7.5);
                var mopt = {
                    url: 'https://api.mapbox.com/styles/v1/mapbox/streets-v9/tiles/256/{z}/{x}/{y}?access_token=' + data['mapbox_token'],
                    options: {attribution:'© <a href="https://www.mapbox.com/map-feedback/">Mapbox</a> © <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'}
                };
                var mq=L.tileLayer(mopt.url,mopt.options);

                mq.addTo(m);

                var mopt_airspace = {
                    url: 'https://api.tiles.openaip.net/api/data/openaip/{z}/{x}/{y}.png?apiKey=' + data['open_aip_token'],
                    options: {attribution:'© <a href="">Open AIP</a> ©'}
                };
                var mq_airspace=L.tileLayer(mopt_airspace.url,mopt_airspace.options);

                mq_airspace.addTo(m);



                function popUp(f,l){
                    var out = [];
                    if (f.properties){
                        for(key in f.properties){
                            out.push(key+": "+f.properties[key]);
                        }
                        l.bindPopup(out.join("<br />"));
                    }
                }
                // https://2pokujcbrs2vli72irapldqlwy0kkqbv.lambda-url.eu-west-2.on.aws/
                // Create a GeoJSON layer using Leaflet's AJAX functionality
                var aircraftLayer = new L.GeoJSON.AJAX(data['aircraft_geojson_url'], {
                    onEachFeature: popUp  // Custom function to handle popups for each feature
                }).addTo(m);

                // Function to fetch and update GeoJSON data
                function updateGeoJSONData() {
                    // Clear existing layers in the GeoJSON layer
                    aircraftLayer.clearLayers();
                    // Fetch new GeoJSON data and add it to the existing layer
                    aircraftLayer.refresh([data['aircraft_geojson_url']]);
                }

                // Update GeoJSON data every 10 seconds
                setInterval(updateGeoJSONData, 10000);  // 10000 milliseconds = 10 seconds

                // Redraw map on Toggle sidebar event
                var sidebarToggle = document.getElementById('sidebarCollapse');
                if (sidebarToggle) {
                    sidebarToggle.addEventListener('click', function () {
                        // Use requestAnimationFrame to ensure the transition has started
                        requestAnimationFrame(function () {
                            setTimeout(function () {
                                m.invalidateSize();
                            }, 250); // Sidebar transition duration
                        });
                    });
                }
            </script>
        </div>
    </div>

    <?php include('includes/navbar_toggle.php'); ?>

</body>
</html>
