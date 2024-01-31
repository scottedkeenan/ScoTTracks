<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use App\Domain\Scottracks\Service\AirfieldsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Views\PhpRenderer;
use App\Helpers\dateValidators;
use Throwable;

final class DailyFlightsAction
{
    private $dailyFlights;
    private $airfields;

    public function __construct(DailyFlightsService $dailyFlights, AirfieldsService $airfields)
    {
        $this->dailyFlights = $dailyFlights;
        $this->airfields = $airfields;
    }

    /**
     * @throws HttpBadRequestException
     * @throws Throwable
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface
    {
        // Validate airfield ID
        $airfieldId = $args['airfield_id'];
        if (is_numeric($airfieldId)) {
            $data['airfield_id'] = $airfieldId;
        } else {
            return $response->withHeader('Location', '/')->withStatus(400);
        }

        // Validate date
        if (isset($request->getQueryParams()['date'])) {
            if (!dateValidators::validateDate($request->getQueryParams()['date'])) {
                return $response->withHeader('Location', '/')->withStatus(400);
            }
            $showDate = $request->getQueryParams()['date'];
        } else {
            date_default_timezone_set('Europe/London');
            $showDate = date('Y-m-d');
        }

        // Validate sort order direction
        if (isset($request->getQueryParams()['order_by'])) {
            if (!in_array($request->getQueryParams()['order_by'],['asc', 'desc'])) {
                return $response->withHeader('Location', '/')->withStatus(400);
            }
        }

        //invoke the domain
        $airfieldName = $this->airfields->getAirfieldNameByID($args['airfield_id']);
        if ($airfieldName) {
            $data['airfield_name'] = $this->airfields->getAirfieldNameByID($args['airfield_id']);
        } else {
            return $response->withStatus(404);
        }
        // Mapping currently disabled.
        // $data['airfield_followed'] = $this->airfields->getAirfieldTrackedByID($args['airfield_id']);
        $data['flight_data'] = $this->dailyFlights->getDailyFlights($args['airfield_id'], $showDate, $request->getQueryParams()['order_by'] ?? 'asc');
        $data['dates'] = $this->dailyFlights->getDailyFlightDatesForAirfield($args['airfield_id']);
        $data['show_date'] = $showDate;
        # For reversing sort order
        $data['order_by'] = $request->getQueryParams()['order_by'] ?? 'asc';


        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "dailyFlights.php", $data);
    }
}
