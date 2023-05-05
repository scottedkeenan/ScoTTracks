<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class FlightDetailAction
{
    public function __construct(DailyFlightsService $dailyFlights)
    {
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        //invoke the domain
        $data['flight_map_url'] = 'https://scotttracks-graphs.s3.eu-west-1.amazonaws.com/maps/map.html';
        $data['flight_chart_url'] = 'https://scotttracks-graphs.s3-eu-west-1.amazonaws.com/graphs/407A1A-2022-09-09-14-21-17.png';

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "flightDetail.php", $data);
    }
}
