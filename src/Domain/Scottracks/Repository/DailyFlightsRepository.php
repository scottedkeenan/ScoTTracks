<?php


namespace App\Domain\Scottracks\Repository;

use PDO;

/**
 * Repository.
 */
class DailyFlightsRepository
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

    public function getDistinctAirfieldNames(): array
    {
        $query = "
                    SELECT DISTINCT takeoff_airfield, nice_name
                    FROM daily_flights
                    INNER JOIN airfields ON daily_flights.takeoff_airfield = airfields.name
                    WHERE takeoff_airfield IS NOT NULL
                    AND takeoff_airfield != 'unknown'
                ";
        $data = $this->connection->query($query)->fetchAll();

        $result = [];

        foreach ($data as $row) {
            $result[$row['takeoff_airfield']] = $row['nice_name'];
        }
        return $result;

    }

    public function getDistinctAirfieldNamesFlownToday(): array
    {
        $query = "
                    SELECT takeoff_airfield, COUNT(takeoff_airfield) as `num`
                    FROM daily_flights
                    WHERE takeoff_airfield IS NOT NULL
                    AND takeoff_airfield != 'unknown'
                    AND daily_flights.takeoff_timestamp > current_date
                    GROUP BY takeoff_airfield

                ";
        $result = [];
        $data = $this->connection->query($query)->fetchAll();
        foreach ($data as $row) {
            $result[$row['takeoff_airfield']] = $row['num'];
        }
        return $result;
    }

    public function getDistinctAirfieldNamesByCountry($countryCode): array
    {
        $query = "
                    SELECT DISTINCT takeoff_airfield, nice_name
                    FROM daily_flights
                    INNER JOIN airfields ON daily_flights.takeoff_airfield = airfields.name
                    WHERE takeoff_airfield IS NOT NULL
                    AND takeoff_airfield != 'unknown'
                    AND country_code = '$countryCode'
                ";
        $data = $this->connection->query($query)->fetchAll();

        $result = [];

        foreach ($data as $row) {
            $result[$row['takeoff_airfield']] = $row['nice_name'];
        }
        return $result;

    }

    public function getDistinctAirfieldNamesFlownTodayByCountry($countryCode): array
    {
        $query = "
                    SELECT takeoff_airfield, COUNT(takeoff_airfield) as `num`
                    FROM daily_flights
                    INNER JOIN airfields ON daily_flights.takeoff_airfield = airfields.name
                    WHERE takeoff_airfield IS NOT NULL
                    AND takeoff_airfield != 'unknown'
                    AND daily_flights.takeoff_timestamp > current_date
                    AND country_code = '$countryCode'
                    GROUP BY takeoff_airfield

                ";
        $result = [];
        $data = $this->connection->query($query)->fetchAll();
        foreach ($data as $row) {
            $result[$row['takeoff_airfield']] = $row['num'];
        }
        return $result;
    }
}

