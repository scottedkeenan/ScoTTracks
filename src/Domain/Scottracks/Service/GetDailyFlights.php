<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\GetDailyFlightsRepository;

/**
 * Service.
 */
final class GetDailyFlights
{
    /**
     * @var GetDailyFlightsRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param GetDailyFlightsRepository $repository The repository
     */
    public function __construct(GetDailyFlightsRepository $repository)
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
}