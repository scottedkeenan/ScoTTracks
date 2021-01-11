<?php

namespace App\Action;

use App\Domain\Scottracks\Service\Countries;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\PhpRenderer;

final class CountriesAction
{
    private $countries;

    public function __construct(Countries $countries)
    {
        $this->countries = $countries;
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
        //invoke the domain
        $countryData = $this->countries->getAllCountries();
        $data['countries'] = $countryData;
        return $renderer->render($response, "countries.php", $data);
    }

}