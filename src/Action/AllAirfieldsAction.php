<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class AllAirfieldsAction
{
    private $dailyFlights;

    public function __construct(DailyFlightsService $dailyFlights)
    {
        $this->dailyFlights = $dailyFlights;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        //invoke the domain
        $data['airfield_names'] = $this->dailyFlights->getDistinctAirfieldNames();
        $data['flown_today'] = $this->dailyFlights->getDistinctAirfieldNamesFlownToday();

        $renderer = new PhpRenderer('../templates/scottracks');
        return $renderer->render($response, "airfields.php", $data);
    }
}
