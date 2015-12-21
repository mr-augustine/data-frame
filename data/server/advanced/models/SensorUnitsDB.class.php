<?php
class SensorUnitsDB {
	
	// Inserts a SensorUnit object into the SensorUnits table and returns
	// the SensorUnit with the sensor_unit_id property set, if successful;
	// otherwise, returns the SensorUnit unchanged. Sets a sensor_unit_id error
	// if there is a db issue.
	public static function addSensorUnit($sensorUnit) {
		$query = "INSERT INTO SensorUnits (sensor_unit_name, description)
				VALUES (:sensor_unit_name, :description)";
		
		try {
			if (is_null($sensorUnit) || $sensorUnit->getErrorCount() > 0)
				return $sensorUnit;
			
			$db = Database::getDB();
			$statement = $db->prepare($query);
			$statement->bindValue(':sensor_unit_name', $sensorUnit->getSensorUnitName());
			$statement->bindValue(':description', $sensorUnit->getDescription());
			$statement->execute();
			$statement->closeCursor();
			
			$newSensorUnitId = $db->lastInsertId('sensor_unit_id');
			$sensorUnit->setSensorUnitId($newSensorUnitId);
		} catch (Exception $e) {
			$sensorUnit->setError('sensor_unit_id', 'SENSOR_UNIT_INVALID');
		}
		
		return $sensorUnit;
	}
	
	// Returns an array of SensorUnits that meet the criteria specified.
	// If unsuccessful, this function returns an empty array.
	public static function getSensorUnitsBy($type = null, $value = null) {
		$sensorUnitRows = SensorUnitsDB::getSensorUnitRowsBy($type, $value);
		
		return SensorUnitsDB::getSensorUnitsArray($sensorUnitRows);
	}
	
	// Returns an array of the rows from the SensorUnits table whose $type
	// field has value $value. Throws an exception if unsuccessful.
	public static function getSensorUnitRowsBy($type = null, $value = null) {
		$allowedTypes = ['sensor_unit_id', 'sensor_unit_name'];
		$sensorUnitRows = array();
		
		try {
			$db = Database::getDB();
			$query = "SELECT sensor_unit_id, sensor_unit_name, description FROM SensorUnits";
			
			if (!is_null($type)) {
				if (!in_array($type, $allowedTypes))
					throw new PDOException("$type not an allowed search criterion for SensorUnits");
				
				$query = $query . " WHERE ($type = :$type)";
				$statement = $db->prepare($query);
				$statement->bindParam(":$type", $value);
			} else {
				$query = $query . " ORDER BY sensor_unit_name ASC";
				$statement = $db->prepare($query);
			}
			
			$statement->execute();
			$sensorUnitRows = $statement->fetchAll(PDO::FETCH_ASSOC);
			$statement->closeCursor();
		} catch (Exception $e) {
			echo "<p>Error getting sensor unit rows by $type: ".$e->getMessage()."</p>";
		}
		
		return $sensorUnitRows;
	}
	
	// Returns an array of SensorUnit objects extracted from $rows
	public static function getSensorUnitsArray($rows) {
		$sensorUnits = array();
		
		if (!empty($rows)) {
			// Convert the array of arrays into an array of SensorUnits
			// and set the id field
			foreach ($rows as $sensorUnitRow) {
				$sensorUnit = new SensorUnit($sensorUnitRow);
				
				$sensorUnitId = $sensorUnitRow['sensor_unit_id'];
				$sensorUnit->setSensorUnitId($sensorUnitId);
				
				array_push($sensorUnits, $sensorUnit);
			}
		}
		
		return $sensorUnits;
	}
	
	// Returns the $column of SensorUnits whose $type maches $value
	public static function getSensorUnitValuesBy($type = null, $value = null, $column) {
		$sensorUnitRows = SensorUnitsDB::getSensorUnitRowsBy($type, $value);
		
		return SensorUnitsDB::getSensorUnitValues($sensorUnitRows, $column);
	}
	
	// Returns an array of values from the $column extracted from $rows
	public static function getSensorUnitValues($rows, $column) {
		$sensorUnitValues = array();
		
		foreach ($row as $sensorUnitRow) {
			$sensorUnitValue = $sensorUnitRow[$column];
			array_push($sensorUnitValues, $sensorUnitValue);
		}
		
		return $sensorUnitValues;
	}
	
	// Updates a SensorUnit entry in the SensorUnits table
	public static function updateSensorUnit($sensorUnit) {
		try {
			$db = Database::getDB();
			
			if (is_null($sensorUnit) || $sensorUnit->getErrorCount() > 0)
				return $sensorUnit;
			
			$checkSensorUnitArray = SensorUnitsDB::getSensorUnitsBy('sensor_unit_id', $sensorUnit->getSensorUnitId());
			
			if (empty($checkSensorUnitArray)) {
				$sensorUnit->setError('sensor_unit_id', 'SENSOR_UNIT_DOES_NOT_EXIST');
				return $sensorUnit;
			}
            $checkSensorUnit = $checkSensorUnitArray[0];
			if ($checkSensorUnit->getErrorCount() > 0)
				return $sensorUnit;
			
			$query = "UPDATE SensorUnits SET sensor_unit_name = :sensor_unit_name,
					description = :description WHERE sensor_unit_id = :sensor_unit_id";
			
			$statement = $db->prepare($query);
			$statement->bindValue(':sensor_unit_name', $sensorUnit->getSensorUnitName());
			$statement->bindValue(':description', $sensorUnit->getDescription());
			$statement->bindValue(':sensor_unit_id', $sensorUnit->getSensorUnitId());
			$statement->execute();
			$statement->closeCursor();
		} catch (Exception $e) {
			$sensorUnit->setError('sensor_unit_id', 'SENSOR_UNIT_COULD_NOT_BE_UPDATED');
		}
		
		return $sensorUnit;
	}
}
?>


