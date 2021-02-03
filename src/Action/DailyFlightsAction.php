<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use App\Domain\Scottracks\Service\AirfieldsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class DailyFlightsAction
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
        $data['nice_airfield_name'] = $this->airfields->getNiceName($args['airfield_name']);
        $data['flight_data'] = $this->dailyFlights->getDailyFlights($args['airfield_name'], $showDate);
        $data['dates'] = $this->dailyFlights->getDailyFlightDatesForAirfield($args['airfield_name']);
        $data['show_date'] = $showDate;

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "dailyFlights.php", $data);
    }

}
