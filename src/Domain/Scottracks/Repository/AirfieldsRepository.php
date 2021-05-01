<?php

namespace App\Domain\Scottracks\Repository;

use PDO;

/**
 * Repository.
 */
class AirfieldsRepository
{
    /**
     * @var PDO The database connection
     */
    private $connection;

    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getNiceName($airfieldName): array
    {
        $sql = "
                    SELECT name
                    FROM airfields
                    WHERE name = '$airfieldName';
                ";

        return $this->connection->query($sql)->fetchAll();

    }


    public function getName($airfieldID): array
    {
        $sql = "
                    SELECT name
                    FROM airfields
                    WHERE id = '$airfieldID';
                ";

        return $this->connection->query($sql)->fetchAll();

    }

}

