<?php

namespace App\Action;

use App\Domain\Scottracks\Service\Countries;
use App\Domain\Scottracks\Service\DailyFlightsService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class AirfieldsAction
{
    private $countries;
    private $dailyFlights;

    public function __construct(Countries $countries, DailyFlightsService $dailyFlights)
    {
        $this->countries = $countries;
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
        $countryData = $this->countries->getAllCountries();
//        $data['countries'] = $countryData;

        if ($args['country']) {
            //invoke the domain
            $data['airfield_names'] = $this->dailyFlights->getDistinctFlownAirfieldNamesByCountry($args['country']);
            $data['flown_today'] = $this->dailyFlights->getDistinctAirfieldNamesFlownTodayByCountry($args['country']);

            if (in_array($args['country'], array_column($countryData, 'country_code'))) {
                return $renderer->render($response, "airfields.php", $data);
            } else {
                // todo: redirect if not a country
                return $response->withHeader('Location', '/')->withStatus(404);
            }
        } else {
            //invoke the domain
            $countryData = $this->countries->getAllCountries();
            $data['countries'] = $countryData;
            return $renderer->render($response, "airfields.php", $data);
        }
    }

}
