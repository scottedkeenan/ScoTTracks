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

//        if ($niceName[0]['nice_name']) {
//            return $niceName[0]['nice_name'];
//        } else {
//            return 'no name';
//        }
        return $niceName[0]['name'];
    }


    public function getAirfieldNameByID($airfieldID): string
    {
        // Get daily flights
        $name = $this->repository->getName($airfieldID);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

//        if ($niceName[0]['nice_name']) {
//            return $niceName[0]['nice_name'];
//        } else {
//            return 'no name';
//        }
        return $name[0]['name'];
    }

    public function getAirfieldTrackedByID($airfieldID): bool
    {
        // Get daily flights
        $tracked = $this->repository->getTracked($airfieldID);

        // Logging here: User created successfully
        //$this->logger->info(sprintf('User created successfully: %s', $userId));

//        if ($niceName[0]['nice_name']) {
//            return $niceName[0]['nice_name'];
//        } else {
//            return 'no name';
//        }
        return $tracked;
    }
}