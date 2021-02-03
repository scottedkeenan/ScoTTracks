<?php

namespace App\Domain\Scottracks\Service;

use App\Domain\Scottracks\Repository\AirfieldsRepository;

/**
 * Service.
 */
final class AirfieldsService
{
    /**
     * @var AirfieldsRepository
     */
    private $repository;

    /**
     * The constructor.
     *
     * @param AirfieldsRepository $repository The repository
     */
    public function __construct(AirfieldsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getNiceName($airfieldName): string
    {
        // Get daily flights
        $niceName = $this->repository->getNiceName($airfieldName);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

        return $niceName[0]['nice_name'];
    }
}