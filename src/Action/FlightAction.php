<?php

namespace App\Action;

use App\Domain\Scottracks\Service\AirfieldsService;
use App\Domain\Scottracks\Service\DailyFlightsService;
use DateTime;
use DateTimeZone;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class FlightAction
{
    private $dailyFlights;
    private $airfields;

    public function __construct(DailyFlightsService $dailyFlights, AirfieldsService $airfields)
    {
        $this->dailyFlights = $dailyFlights;
        $this->airfields = $airfields;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

        //invoke the domain
        $data['flight_graph_url'] = sprintf(
            'https://scotttracks-graphs.s3.eu-west-1.amazonaws.com/graphs/%s-%s.png',
            $args['address'],
            $args['takeoff_time']
        );
        $data['flight_map_url'] = sprintf(
            'https://scotttracks-graphs.s3-eu-west-1.amazonaws.com/maps/%s-%s.html',
            $args['address'],
            $args['takeoff_time']
        );

        //invoke the domain
        $data['flight'] = $this->dailyFlights->getFlight($args['address'], $args['takeoff_time'])[0];

        $trackerTimezone = new DateTimeZone('UTC');

        //todo: Decide what to do with this, also repeated in daily flights view. Move to domain?
        $takeoff_time = $data['flight']['takeoff_timestamp'] ? new DateTime($data['flight']['takeoff_timestamp'], $trackerTimezone) : null;
//        if ($takeoff_time) {
//            $takeoff_time->setTimeZone($siteTimezone);
//        }

        $landing_time = $data['flight']['landing_timestamp'] ? new DateTime($data['flight']['landing_timestamp'], $trackerTimezone) : null;
//        if ($landing_time) {
//            $landing_time->setTimeZone($siteTimezone);
//        }

        $data['flight']['duration'] = date_diff($takeoff_time, $landing_time)->format('%h:%I:%S');

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "flight.php", $data);
    }
}
