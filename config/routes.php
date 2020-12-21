<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;


return function (App $app) {

    $app->get('/', \App\Action\HomeAction::class)->setName('home');
    $app->get('/{airfield_name}', \App\Action\AirfieldNameAction::class)->setName('airfield');
    $app->get('/{airfield_name}/{date}', \App\Action\AirfieldNameAction::class)->setName('airfield');

};


