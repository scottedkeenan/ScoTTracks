<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class StatsAveragesAction
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

        $data['averages'] = $this->dailyFlights->getAverageLaunchClimbRates();

        return $renderer->render($response, "averages.php", $data);

    }

}
