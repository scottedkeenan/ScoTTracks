<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;
use DateTime;

final class HomeAction
{
    private $dailyFlights;

    public function __construct(DailyFlightsService $dailyFlights)
    {
        $this->dailyFlights = $dailyFlights;
        date_default_timezone_set('Europe/London');
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        //invoke the domain

        if (isset($request->getQueryParams()['date'])) {
            $d = DateTime::createFromFormat('Y-m-d', $request->getQueryParams()['date']);
            if ($d && $d->format('Y-m-d') === $request->getQueryParams()['date'])  {
                $data['date'] = $request->getQueryParams()['date'];
                $data['flown_today'] = $this->dailyFlights->getDistinctFlownAirfieldNamesByCountryDate('GB', $request->getQueryParams()['date']);

                $today = new DateTime("today");
                $diff = $today->diff($d->setTime( 0, 0, 0 )); // set time part to midnight, in order to prevent partial comparison);
                $diffDays = (integer)$diff->format( "%R%a" ); // Extract days count in interval
                if ($diffDays === 0) {
                    $data['next_day'] = null;
                } else {
                    $data['next_day'] = (clone $d)->modify('+1 days')->format('Y-m-d');
                }
                $data['previous_day'] = (clone $d)->modify('-1 days')->format('Y-m-d');
            } else {
                return $response->withHeader('Location', '/')->withStatus(400);
            }
        } else {
            $data['date'] = date('Y-m-d', strtotime('today'));
            $data['flown_today'] = $this->dailyFlights->getDistinctFlownAirfieldNamesByCountryDate('GB');
            $data['next_day'] = null;
            $data['previous_day'] = date('Y-m-d', strtotime('yesterday'));
        }

        $data['airfield_names'] = $this->dailyFlights->getDistinctFlownAirfieldNamesByCountry('GB');

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "index.php", $data);
    }
}
