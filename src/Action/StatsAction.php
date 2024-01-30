<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class StatsAction
{
    private $dailyFlights;

    public function __construct(DailyFlightsService $dailyFlights)
    {
        $this->dailyFlights = $dailyFlights;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface
    {
        $renderer = new PhpRenderer('../templates/scottracks');

        //invoke the domain

        $data['difference'] = $this->dailyFlights->getWeekOnWeekDifference();
        $data['top_launchers'] = $this->dailyFlights->getTopAirfieldsByLaunchesForWeek();
        $data['flight_times'] = $this->dailyFlights->getWeeklyFlightTimes();

        return $renderer->render($response, "stats.php", $data);

    }

}
