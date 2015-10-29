<?php
class SensorsDB {

    public static function addSensor($sensor) {
        $query = "INSERT INTO Sensors (name, description)
                    VALUES (:name, :description)";

        try {
            if (is_null($sensor) || $sensor->getErrorCount() > 0)
                throw new PDOException("Invalid Sensor object cannot be inserted");

            $units = $sensor->getUnits();

            if (is_null($units) || !is_array($units) || count($units) < 1)
                throw new PDOException("Invalid Sensor object cannot be inserted");

            $db = Database::getDB();
            $statement = $db->prepare($query);
            $statement->bindValue(":name", $sensor->getName());
            $statement->bindValue(":description", $sensor->getDescription());
            $statement->execute();
            $statement->closeCursor();

            $newSensorId = $db->lastInsertId("sensorId");

            $returnUnitAssocId = SensorsDB::addSensorUnitAssoc($newSensorId, $units);

            if ($returnUnitAssocId == 0) {
                // Delete the new Sensor from Sensors
                throw new PDOException("Invalid Sensor units");
            }

            $sensor->setSensorId($newSensorId);
        } catch (Exception $e) {
            $sensor->setError('sensorId', 'SENSOR_INVALID');
        }

        return $sensor;
    }

    public static function addSensorUnitAssoc($sensorId, $units) {
        $query = "INSERT INTO SensorUnitAssocs (sensorId, unitId)
                    VALUES (:sensorId, :unitId)";
        $returnId = 0;

        try {
            
 
            foreach ($units as $unit) {
                // Translate the unit to a unitId
                // The getUnitBy() function returns an array of Unit objects.
                // However, there can be only one Unit in this case since unitNames
                // are unique.
                $unitObjects = UnitsDB::getUnitsBy("unitName", $unit);
                $unitObject = $unitObjects[0];
                $unitId = $unitObject->getUnitId();

                // TODO: Not sure if we need to open and close the db connection
                // each time we do a unitId translation; or just open and close once
                $db = Database::getDB();
                
                // Perform the INSERT using the unitId
                $statement = $db->prepare($query);
                $statement->bindValue(":sensorId", $sensorId);
                $statement->bindValue(":unitId", $unitId);
                $statement->execute();
                $statement->closeCursor();
                
                // TODO: add error trap in case INSERT goes wrong
                $returnId = $db->lastInsertId("unitAssocId");
            }
            
        } catch (PDOException $e) {
            echo "<p>Error adding sensor unit association to SensorUnitAssocs ".
                $e->getMessage()."</p>";
        }

        return $returnId;
    }

    public static function getSensorsBy($type = null, $value = null) {
        $sensorRows = SensorsDB::getSensorRowsBy($type, $value);
        
        return SensorsDB::getSensorsArray($sensorRows);
    }

    public static function getSensorRowsBy($type = null, $value = null) {
        $allowedTypes = ["sensorId", "name", "description"];
        $sensorRows = array();
        
        try {
            $db = Database::getDB();
            $query = "SELECT sensorId, name, description, dateAdded FROM Sensors";
            
            if (!is_null($type)) {
                if (!in_array($type, $allowedTypes))
                    throw new PDOException("$type not an allowed search criterion for Sensors");
                    
                $query = $query . " WHERE ($type = :$type)";
                $statement = $db->prepare($query);
                $statement->bindParam(":$type", $value);
            } else
                $statement = $db->prepare($query);
                
            $statement->execute();
            $sensorRows = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
        } catch (Exception $e) {
            echo "<p>Error getting sensor rows by $type: " . $e->getMessage()."</p>";
        }
        
        return $sensorRows;
    }

    public static function getSensorsArray($rows) {
        $sensors = array();
        
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $sensor = new Sensor($row);
                $sensor->setSensorId($row['sensorId']);
                
                // TODO: Also fetch the associated units for the sensor,
                // then set the units (setSensorUnits()) before array_push
                
                array_push($sensors, $sensor);
            }
        }
        
        return $sensors;
    }
}

?>
