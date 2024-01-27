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

        if (isset($request->getQueryParams()['date'])) {
            // todo: redirect if not a date
//            return $response->withHeader('Location', '/')->withStatus(404);
            $data['airfield_id'] = $args['airfield_id'];
            $showDate = $request->getQueryParams()['date'];
        } else {
            date_default_timezone_set('Europe/London');
            $showDate = date('Y-m-d');
            $data['airfield_id'] = $args['airfield_id'];
        }

        if (isset($request->getQueryParams()['order_by'])) {
            if (!in_array($request->getQueryParams()['order_by'],['asc', 'desc'])) {
                return $response->withHeader('Location', '/')->withStatus(400);
            }
        }

        //invoke the domain
        $data['airfield_name'] = $this->airfields->getAirfieldNameByID($args['airfield_id']);
        $data['airfield_followed'] = $this->airfields->getAirfieldTrackedByID($args['airfield_id']);
        $data['flight_data'] = $this->dailyFlights->getDailyFlights($args['airfield_id'], $showDate, $request->getQueryParams()['order_by'] ?? 'asc');
        $data['dates'] = $this->dailyFlights->getDailyFlightDatesForAirfield($args['airfield_id']);
        $data['show_date'] = $showDate;
        $data['order_by'] = $request->getQueryParams()['order_by'] ?? 'asc';


        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "dailyFlights.php", $data);
    }

}
