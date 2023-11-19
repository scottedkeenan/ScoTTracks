<?php

namespace App\Action;

use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface;
use Slim\Views\PhpRenderer;
use DateTime;

final class LiveAction
{
    private $dailyFlights;
    private $container;

    public function __construct(DailyFlightsService $dailyFlights, ContainerInterface $container)
    {
        $this->dailyFlights = $dailyFlights;
        $this->container = $container;
        date_default_timezone_set('Europe/London');
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        //invoke the domain

        $data = $this->container->get('settings')['maps'];

        $renderer = new PhpRenderer('../templates/scottracks');

        return $renderer->render($response, "live.php", $data);
    }
}
