<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\DailyFlightsRepository;

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

    public function getDailyFlights(string $airfieldID, $showDate, $order): array
    {
        // Get daily flights
        $dailyFlights = $this->repository->getDailyFlights($airfieldID, $showDate, $order);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $dailyFlights;
    }

    public function getDailyFlightDatesForAirfield(string $airfieldID): array
    {
        //get daily flight dates
        $dailyFLightDates = $this->repository->getDailyFlightDates($airfieldID);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $dailyFLightDates;
    }

    public function getDistinctFlownAirfieldNames(): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctFlownAirfieldNames();

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $airfieldNames;
    }

    public function getDistinctAirfieldNamesFlownToday(): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNamesFlownToday();

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $airfieldNames;
    }

    public function getDistinctFlownAirfieldNamesByCountry($countryCode): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNamesByCountry($countryCode);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $airfieldNames;
    }

    public function getDistinctAirfieldNamesFlownTodayByCountry($countryCode): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNamesFlownTodayByCountry($countryCode);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $airfieldNames;
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
}