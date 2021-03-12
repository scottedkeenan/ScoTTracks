<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;


return function (App $app) {

    $app->get('/', \App\Action\HomeAction::class)->setName('home');
//    $app->get('sites/stats', \App\Action\AllSitesStatsAction::class)->setName('allsitesstats');
//    $app->get('sites/{airfield_name}/stats', \App\Action\StatsForSiteAction::class)->setName('statsforsite');
    $app->get('/airfields/all', \App\Action\AllAirfieldsAction::class)->setName('allairfields');
    $app->get('/airfields/{airfield_name}', \App\Action\DailyFlightsAction::class)->setName('airfield');
    $app->get('/airfields/{airfield_name}/{date}', \App\Action\DailyFlightsAction::class)->setName('airfielddate');

    $app->get('/countries', \App\Action\CountriesAction::class)->setName('countries');
    $app->get('/countries/{country}', \App\Action\AirfieldsAction::class)->setName('country');
//    $app->get('countries/{country}/stats', \App\Action\CountryStatsAction::class)->setName('countrystats');
    $app->get('/stats/averages', \App\Action\StatsAveragesAction::class)->setName('averages');

};


