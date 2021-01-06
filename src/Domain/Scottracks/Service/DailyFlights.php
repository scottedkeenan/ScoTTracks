<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\DailyFlightsRepository;

/**
 * Service.
 */
final class DailyFlights
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

    public function getDailyFlights(string $airfieldName, $showDate): array
    {
        // Get daily flights
        $dailyFlights = $this->repository->getDailyFlights($airfieldName, $showDate);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $dailyFlights;
    }

    public function getDailyFlightDatesForAirfield(string $airfieldName): array
    {
        //get daily flight dates
        $dailyFLightDates = $this->repository->getDailyFlightDates($airfieldName);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $dailyFLightDates;
    }

    public function getDistinctAirfieldNames(): array
    {
        //get daily flight dates
        $airfieldNames = $this->repository->getDistinctAirfieldNames();

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
}