<?php
class SensorsDB {

    public static function addSensor($sensor) {
        $query = "INSERT INTO Sensors (sensorName, description)
                    VALUES (:sensorName, :description)";
        $returnId = 0;

        try {
            if (is_null($sensor) || $sensor->getErrorCount() > 0)
                throw new PDOException("Invalid Sensor object cannot be inserted");

            $units = $sensor->getUnits();

            if (is_null($units) || !is_array($units) || count($units) < 1)
                throw new PDOException("Invalid Sensor object cannot be inserted");

            $db = Database::getDB();
            $statement = $db->prepare($query);
            $statement->bindValue(":sensorName", $sensor->getName());
            $statement->bindValue(":description", $sensor->getDescription());
            $statement->execute();
            $statement->closeCursor();

            $returnId = $db->lastInsertId("sensorId");

            $returnUnitAssocId = SensorsDB::addSensorUnitAssoc($returnId, $units);

            if ($returnUnitAssocId == 0) {
                // Delete the new Sensor from Sensors
                // Restore $returnId = 0
                // Throw and exception
            }

        } catch (PDOException $e) {
            echo "<p>Error adding sensor to Sensors ".$e->getMessage()."</p>";
        }

        return $returnId;
    }

    public static function addSensorUnitAssoc($sensorId, $units) {
        $query = "INSERT INTO SensorUnitAssocs (sensorId, unitId)
                    VALUES (:sensorId, :unitId)";
        $returnId = 0;

        try {
            $db = Database::getDB();
 
            foreach ($units as $unit) {
                // Translate the unit to a unitId
                // The getUnitBy() function returns an array
                $unitId = (UnitsDB::getUnitBy("unitName", $unit))[0];

                // Perform the INSERT using the unitId
                $statement = $db->prepare($query);
                $statement->bindValue(":sensorId", $sensorId);
                $statement->bindValue(":unitId", $unitId);
                $statement->execute();
                $statement->closeCursor();

                $returnId = $db->lastInsertId("unitAssocId");
            }
        } catch (PDOException $e) {
            echo "<p>Error adding sensor unit association to SensorUnitAssocs ".
                $e->getMessage()."</p>";
        }

        return $returnId;
    }

    public static function getSensorsBy($type, $value) {

    }

    public static function getSensorRowsBy($type, $value, $column) {

    }

    public static function getSensorsArray($rows) {

    }
}

?>
