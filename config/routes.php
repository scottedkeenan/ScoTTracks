<?php

use App\Action\AirfieldsAction;
use App\Action\AllAirfieldsAction;
use App\Action\CountriesAction;
use App\Action\DailyFlightsAction;
use App\Action\FlightAction;
use App\Action\HomeAction;
use App\Action\StatsAction;
use App\Action\StatsAveragesAction;
use Slim\App;


return function (App $app) {

    $app->get('/', HomeAction::class)->setName('home');
    $app->get('/airfields/all', AllAirfieldsAction::class)->setName('allairfields');
    $app->get('/airfields/{airfield_id}', DailyFlightsAction::class)->setName('airfield');
    $app->get('/airfields/{airfield_id}/{date}', DailyFlightsAction::class)->setName('airfielddate');

    $app->get('/countries', CountriesAction::class)->setName('countries');
    $app->get('/countries/{country}', AirfieldsAction::class)->setName('country');

    $app->get('/stats', StatsAction::class)->setName('stats');
    $app->get('/stats/averages', StatsAveragesAction::class)->setName('averages');

    $app->get('/flight/{address}/{takeoff_time}', FlightAction::class)->setName('flight');
};
