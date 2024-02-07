<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\DailyFlightsRepository;
use Slim\Exception\HttpBadRequestException;

/**
 * Service.
 */
final class DailyFlightsService
{
    /**
     * @var DailyFlightsRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param DailyFlightsRepository $repository The repository
     */
    public function __construct(DailyFlightsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getFlight(string $address, $takeoffTime): array
    {
        // Get flight
        return $this->repository->getFlight($address, $takeoffTime);
    }

    /**
     * @throws HttpBadRequestException
     */
    public function getDailyFlights(string $airfieldID, $showDate, $sortOrder): array
    {
        // Get daily flights
        $dailyFlights = $this->repository->getDailyFlights($airfieldID, $showDate, $sortOrder);

        // Logging here: Got daily flights data
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $dailyFlights;
    }

    public function getDailyFlightDatesForAirfield(string $airfieldID): array
    {
        //get daily flight dates
        // Logging here
        //$this->logger->info('Placeholder Message');

        return $this->repository->getDailyFlightDates($airfieldID);
    }

    public function getDistinctFlownAirfieldNames(): array
    {
        $airfieldNames = $this->repository->getDistinctFlownAirfieldNames();
        $airfieldsByName = [];
        foreach ($airfieldNames as $airfield) {
            $airfieldsByName[$airfield['takeoff_airfield']] = [
                'name' => $airfield['name'],
                'icao' => $airfield['icao']];
        }

        // Logging here
        //$this->logger->info('Placeholder Message');

        return $airfieldsByName;
    }

    public function getDistinctAirfieldNamesFlownToday(): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNamesFlownToday();
        $airfieldsByName = [];
        foreach ($airfieldNames as $airfield) {
            $airfieldsByName[$airfield['takeoff_airfield']] = [
                'name' => $airfield['name'],
                'icao' => $airfield['icao'],
                'flights' => $airfield['flights']
            ];
        }

        // Logging here
        //$this->logger->info('Placeholder Message');

        return $airfieldsByName;
    }

    public function getDistinctFlownAirfieldNamesByCountry($countryCode): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNamesByCountry($countryCode);
        $airfieldsByName = [];
        foreach ($airfieldNames as $airfield) {
            $airfieldsByName[$airfield['takeoff_airfield']] = [
                'name' => $airfield['name'],
                'icao' => $airfield['icao']
            ];
        }

        // Logging here
        //$this->logger->info('Placeholder Message');

        return $airfieldsByName;
    }

    public function getDistinctFlownAirfieldNamesByCountryDate($countryCode, $date=null): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctFlownAirfieldNamesByCountryDate($countryCode, $date);

        $airfieldsByName = [];
        foreach ($airfieldNames as $airfield) {
            $airfieldsByName[$airfield['takeoff_airfield']] = [
                'name' => $airfield['name'],
                'icao' => $airfield['icao'],
                'flights' => $airfield['flights']
            ];
        }

        // Logging here
        //$this->logger->info('Placeholder Message');

        return $airfieldsByName;
    }

    public function getAverageLaunchClimbRates(): array
    {
        $airfieldNames = $this->repository->getDistinctAirfieldNames();
        $averages = [];

        foreach ($airfieldNames as $airfieldName) {
            $field_averages = $this->repository->getAverageLaunchClimbRatesByAirfield($airfieldName['takeoff_airfield']);
            $averages[$airfieldName['takeoff_airfield']] = $field_averages;
        }

        return $averages;
    }

    public function getTopAirfieldsByLaunchesForWeek(): array
    {
        $weekStartDate = date('Y-m-d', strtotime('last monday'));
        $topFiveAirfields = $this->repository->getTopAirfieldsByLaunchesForWeekStarting($weekStartDate, 10);

        return $topFiveAirfields;
    }

    public function getWeekOnWeekDifference(): array
    {

        $weekStartDate = date('Y-m-d', strtotime('monday last week'));
        $data = $this->repository->getWeekOnWeekLaunchDifferenceByAirfieldForWeekStarting($weekStartDate);
        $weekOnWeekDifference['week_start_date'] = $weekStartDate;
        $weekOnWeekDifference['week_on_week_pos'] = array_slice($data, 0, 10, true);
        $weekOnWeekDifference['week_on_week_neg'] = array_slice($data, -10, 10, true);
        return $weekOnWeekDifference;
    }

    public function getWeeklyFlightTimes() :array
    {
        $weekStartDate = date('Y-m-d', strtotime('monday last week'));
        return $this->repository->getTotalFlightTimesForWeek($weekStartDate);
    }
}
