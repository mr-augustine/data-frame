<?php
class UnitsDB {

    public static function addUnit($unit) {

    }

    // Returns an array of Unit objects extracted from $rows
    public static function getUnitsArray($rows) {
        $units = array();

        if (!empty($rows)) {
            foreach ($rows as $row) {
                $unit = new Unit($row);
                $unit->setUnitId($row['unitId']);
                array_push($units, $unit);
            }
        }

        return $units;
    }

    public static function getUnitsRowsBy($type = null, $value = null) {
        $allowedTypes = ["unitId", "unitName"];
        $unitRows = array();

        try {
            $db = Database::getDB();
            $query = "SELECT unitId, unitName FROM Units";

            if (!is_null($type)) {
                if (!in_array($type, $allowedTypes))
                    throw new PDOException("$type is not an allowed search criterion for Units");

                $query = $query . " WHERE ($type = :$type)";
                $statement = $db->prepare($query);
                $statement->bindParam(":$type", $value);
            } else
                $statement = $db->prepare($query);

            $statement->execute();
            $unitRows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
        } catch (Exception $e) {
            echo "<p>Error getting unit rows by $type: " . $e->getMessage() . "</p>";
        }

        return $unitRows;
    }

    public static function getUnitsBy($type = null, $value = null) {
        $unitRows = UnitsDB::getUnitRowsBy($type, $value);

        return UsersDB::getUnitsArray($unitRows);
    }
}
?>
