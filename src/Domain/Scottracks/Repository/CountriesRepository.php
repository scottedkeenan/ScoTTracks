<?php

namespace App\Domain\Scottracks\Repository;

use PDO;

/**
 * Repository.
 */
class CountriesRepository
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

    public function getAllCountries(): array
    {
        $sql = "
                    SELECT * FROM countries;
                ";

        return $this->connection->query($sql)->fetchAll();

    }

}

