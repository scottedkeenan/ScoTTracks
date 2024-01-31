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

    public function getFlight($address, $takeoffTime): array
    {
        $query = "
                    SELECT daily_flights.*, toff.name AS takeoff_airfield_name, lndg.name AS landing_airfield_name
                    FROM daily_flights 
                    LEFT JOIN airfields toff ON daily_flights.takeoff_airfield = `toff`.`id`
                    LEFT JOIN airfields lndg ON daily_flights.landing_airfield = `lndg`.`id`
                    WHERE takeoff_timestamp = :takeoffTime
                    AND address = :address;
                ";
        $statement = $this->connection->prepare($query);
        $statement->bindParam('address', $address);
        $statement->bindParam('takeoffTime', $takeoffTime);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDailyFlights($airfieldId, $showDate, $sortOrder = 'ASC'): array
    {
        $query = "
                    SELECT daily_flights.*, toff.name AS takeoff_airfield_name, lndg.name AS landing_airfield_name
                    FROM daily_flights 
                    LEFT JOIN airfields toff ON daily_flights.takeoff_airfield = `toff`.`id`
                    LEFT JOIN airfields lndg ON daily_flights.landing_airfield = `lndg`.`id`
                    WHERE (DATE_FORMAT(takeoff_timestamp, '%Y-%m-%d') = :showDate OR DATE_FORMAT(landing_timestamp, '%Y-%m-%d') = :showDate)
                    AND (takeoff_airfield = :airfieldId OR landing_airfield = :airfieldId)
                    ORDER BY `daily_flights`.id $sortOrder;
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam('airfieldId', $airfieldId, PDO::PARAM_INT);
        $statement->bindParam('showDate', $showDate);
        $statement->bindParam('sortOrder', $sortOrder);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getDailyFlightDates($airfieldId): array
    {
        $query = "
                    SELECT DISTINCT cast(reference_timestamp AS date) `flight_date`
                    FROM daily_flights
                    WHERE takeoff_airfield = :airfieldId
                    OR landing_airfield = :airfieldId;
                ";
        $statement = $this->connection->prepare($query);
        $statement->bindParam('airfieldId', $airfieldId, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctFlownAirfieldNames(): array
    {
        $query = "
                    SELECT DISTINCT takeoff_airfield, name, icao
                    FROM daily_flights
                    INNER JOIN airfields a ON daily_flights.takeoff_airfield = a.id
                    WHERE takeoff_airfield IS NOT NULL
                ";
        $statement = $this->connection->prepare($query);
        $statement ->execute();
        return $statement->fetchAll(PDO::FETCH_BOTH);
    }

    public function getDistinctAirfieldNames(): array
    {
        $query = "
                    SELECT DISTINCT takeoff_airfield, icao
                    FROM daily_flights
                    INNER JOIN airfields a ON daily_flights.takeoff_airfield = a.id
                    WHERE takeoff_airfield IS NOT NULL
                ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctAirfieldNamesFlownToday(): array
    {
        $query = "
                    SELECT takeoff_airfield, name, icao, COUNT(takeoff_airfield) as `flights`
                    FROM daily_flights
                    INNER JOIN airfields a ON daily_flights.takeoff_airfield = a.id
                    WHERE takeoff_airfield IS NOT NULL
                    AND daily_flights.takeoff_timestamp > current_date
                    GROUP BY takeoff_airfield
                    ORDER BY name;
                ";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctAirfieldNamesByCountry($countryCode): array
    {
        $query = "
                    SELECT DISTINCT takeoff_airfield, name, icao
                    FROM daily_flights
                    INNER JOIN airfields a ON daily_flights.takeoff_airfield = a.id
                    WHERE takeoff_airfield IS NOT NULL
                    AND daily_flights.takeoff_timestamp < current_date
                    AND country_code = :countryCode
                    ORDER BY name;
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam('countryCode', $countryCode);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDistinctFlownAirfieldNamesByCountryDate($countryCode, $date = null): array
    {
        $insertDate = $date ?: date('Y-m-d');
        $query = "
                    SELECT takeoff_airfield, name, icao, COUNT(takeoff_airfield) as `flights`
                    FROM daily_flights
                    INNER JOIN airfields ON daily_flights.takeoff_airfield = airfields.id
                    WHERE takeoff_airfield IS NOT NULL
                    AND daily_flights.takeoff_timestamp BETWEEN :insertDate AND :insertDate + INTERVAL 1 DAY
                    AND country_code = :countryCode
                    GROUP BY takeoff_airfield
                    ORDER BY name;
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam('insertDate', $insertDate);
        $statement->bindParam('countryCode', $countryCode);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAverageLaunchClimbRatesByAirfield($airfield): array
    {
        $query = "
                    SELECT CAST(takeoff_timestamp AS DATE) theDate,
                    AVG(average_launch_climb_rate) as avg_launch_climb_rate
                    FROM daily_flights
                    WHERE takeoff_timestamp > '2021-01-01'
                    AND takeoff_airfield = :airfield
                    GROUP BY theDate
        
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam('airfield', $airfield);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopAirfieldsByLaunchesForWeekStarting($startDate, $limit): array
    {
        // Prepare the SQL statement with placeholders
        $query = "
                SELECT af.id, af.name, count(*) as flight_count FROM daily_flights df
                JOIN airfields af
                ON takeoff_airfield = af.id
                WHERE takeoff_timestamp > :startDate
                  AND takeoff_timestamp <= (:startDate + INTERVAL 1 WEEK)
                AND af.country_code = 'GB'
                GROUP BY takeoff_airfield
                ORDER BY flight_count DESC
                LIMIT :limit;
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $statement->bindParam(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeekOnWeekLaunchDifferenceByAirfieldForWeekStarting($startDate): array
    {
        $query = "
        SELECT * FROM 
        (
                SELECT
                    id,
                    name,
                    total_flight_count,
                    later_week_flight_count,
                    earlier_week_flight_count,
                    later_week_flight_count - earlier_week_flight_count as difference,
                    CASE WHEN earlier_week_flight_count > 0
                             THEN (later_week_flight_count - earlier_week_flight_count) / CAST(earlier_week_flight_count AS DECIMAL(10,2)) * 100
                         ELSE NULL
                        END as percentage_change
                FROM (
                         SELECT
                             af.id,
                             af.name,
                             COUNT(*) as total_flight_count,
                             SUM(CASE WHEN takeoff_timestamp >= :startDate AND takeoff_timestamp < (:startDate + INTERVAL 1 WEEK) THEN 1 ELSE 0 END) as later_week_flight_count,
                             SUM(CASE WHEN takeoff_timestamp >= (:startDate - INTERVAL 1 WEEK) AND takeoff_timestamp < :startDate THEN 1 ELSE 0 END) as earlier_week_flight_count
                         FROM
                             daily_flights
                                 JOIN airfields af ON takeoff_airfield = af.id
                         WHERE
                                 takeoff_timestamp >= :startDate - INTERVAL 1 WEEK AND
                                 takeoff_timestamp < :startDate + INTERVAL 1 WEEK
                           AND af.country_code = 'GB'
                         GROUP BY
                             takeoff_airfield
                     ) AS FlightCounts
            ) AS FinalQuery
            ORDER BY difference DESC;
              
        ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':startDate', $startDate, PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);


    }

    public function getTotalFlightTimesForWeek($startDate) {
        $query = "
                SELECT
                name,
                id,
                SUM(flight_time)as total_flight_time,
                COUNT(DISTINCT registration) as aircraft_count,
                (SUM(flight_time)/ COUNT(DISTINCT registration)) as minutes_per_aircaft
                    FROM (SELECT af.id,
                          af.name,
                          registration,
                          takeoff_timestamp,
                          landing_timestamp,
                          TIMESTAMPDIFF(MINUTE, takeoff_timestamp, landing_timestamp) as flight_time
                           FROM daily_flights df
                                    JOIN airfields af ON takeoff_airfield = af.id
                           WHERE takeoff_timestamp > :startDate
                             AND takeoff_timestamp <= (:startDate + INTERVAL 1 WEEK)
                             AND takeoff_timestamp IS NOT NULL
                             AND landing_timestamp IS NOT NULL
                             AND aircraft_type = 1
                             AND af.country_code = 'GB'
                ) AS flight_times
                GROUP BY id
                ORDER BY total_flight_time DESC;
                ";

        $statement = $this->connection->prepare($query);
        $statement->bindParam('startDate', $startDate);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
