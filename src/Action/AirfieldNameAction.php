<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlights;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class AirfieldNameAction
{
    private $dailyFlights;

    public function __construct(DailyFlights $dailyFlights)
    {
        $this->dailyFlights = $dailyFlights;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface
    {

        if ($args['date']) {
            // todo: redirect if not a date
//            return $response->withHeader('Location', '/')->withStatus(404);
            $showDate = $args['date'];
        } else {
            date_default_timezone_set('Europe/London');
            $showDate = date('Y-m-d');
            $data['airfield_name'] = $args['airfield_name'];
        }

        //invoke the domain
        $data['flight_data'] = $this->dailyFlights->getDailyFlights($args['airfield_name'], $showDate);
        $data['dates'] = $this->dailyFlights->getDailyFlightDatesForAirfield($args['airfield_name']);
        $data['show_date'] = $showDate;

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "dailyFlights.php", $data);
    }

}
