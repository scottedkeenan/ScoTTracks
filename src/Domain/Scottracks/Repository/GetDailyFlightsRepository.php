<?php


namespace App\Domain\Scottracks\Repository;

use PDO;

/**
 * Repository.
 */
class GetDailyFlightsRepository
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

    public function getDailyFlights($airfieldName, $showDate): array
    {
        $sql = "
                    SELECT * FROM daily_flights
                    WHERE (DATE_FORMAT(takeoff_timestamp, '%Y-%m-%d') = '$showDate' OR DATE_FORMAT(landing_timestamp, '%Y-%m-%d') = '$showDate')
                    AND (takeoff_airfield = '$airfieldName' OR landing_airfield = '$airfieldName')
                    ORDER BY id;
                ";

        return $this->connection->query($sql)->fetchAll();

    }

    public function getDailyFlightDates($airfieldName): array
    {
        $query = "
                    SELECT DISTINCT cast(reference_timestamp AS date)
                    FROM daily_flights
                    WHERE takeoff_airfield = '$airfieldName'
                    OR landing_airfield = '$airfieldName';
                ";
        return $this->connection->query($query)->fetchAll();
    }
}

