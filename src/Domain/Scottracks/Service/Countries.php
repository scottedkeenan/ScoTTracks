<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\CountriesRepository;

/**
 * Service.
 */
final class Countries
{
    /**
     * @var CountriesRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param CountriesRepository $repository The repository
     */
    public function __construct(CountriesRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllCountries(): array
    {
        // Get daily flights
        $allCountries = $this->repository->getAllCountries();

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $allCountries;
    }
}