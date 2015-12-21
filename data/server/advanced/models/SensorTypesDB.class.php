<?php
class SensorTypesDB {
	
	// Inserts a SensorType object into the SensorTypes table and returns
	// the SensorType with the sensor_type_id property set, if successful;
	// otherwise, returns the SensorType unchanged. Sets a sensor_type_id error
	// if there is a db issue.
	public static function addSensorType($sensorType) {
		$query = "INSERT INTO SensorTypes (sensor_type_name, description)
				VALUES (:sensor_type_name, :description)";
		
		try {
			if (is_null($sensorType) || $sensorType->getErrorCount() > 0)
				return $sensorType;
			
			$db = Database::getDB();
			$statement = $db->prepare($query);
			$statement->bindValue(':sensor_type_name', $sensorType->getSensorTypeName());
			$statement->bindValue(':description', $sensorType->getDescription());
			$statement->execute();
			$statement->closeCursor();
			
			$newSensorTypeId = $db->lastInsertId('sensor_type_id');
			$sensorType->setSensorId($newSensorTypeId);
		} catch (Exception $e) {
			$sensorType->setError('sensor_type_id', 'SENSOR_TYPE_INVALID');
		}
		
		return $sensorType;
	}
	
	// Returns an array of SensorTypes that meet the criteria specified.
	// If unsuccessful, this function returns an empty array.
	public static function getSensorTypesBy($type = null, $value = null) {
		$sensorTypeRows = SensorTypesDB::getSensorTypeRowsBy($type, $value);
		
		return SensorTypesDB::getSensorTypesArray($sensorTypeRows);
	}
	
	// Returns an array of the rows from the SensorTypes table whose $type
	// field has value $value. Throws an exception if unsuccessful.
	public static function getSensorTypeRowsBy($type = null, $value = null) {
		$allowedTypes = ['sensor_type_id', 'sensor_type_name'];
		$sensorTypeRows = array();
		
		try {
			$db = Database::getDB();
			$query = "SELECT sensor_type_id, sensor_type_name, description FROM SensorTypes";
			
			if (!is_null($type)) {
				if (!in_array($type, $allowedTypes))
					throw new PDOException("$type not an allowed search criterion for SensorTypes");
				
				$query = $query . " WHERE ($type = :$type)";
				$statement = $db->prepare($query);
				$statement->bindParam(":$type", $value);
			} else {
				$query = $query . " ORDER BY sensor_type_name ASC";
				$statement = $db->prepare($query);
			}
			
			$statement->execute();
			$sensorTypeRows = $statement->fetchAll(PDO::FETCH_ASSOC);
			$statement->closeCursor();
		} catch (Exception $e) {
			echo "<p>Error getting sensor type rows by $type: ".$e->getMessage()."</p>";
		}
		
		return $sensorTypeRows;
	}
	
	// Returns an array of SensorType objects extracted from $rows
	public static function getSensorTypesArray($rows) {
		$sensorTypes = array();
		
		if (!empty($rows)) {
			// Convert the array of arrays into an array of SensorTypes
			// and set the id field
			foreach ($rows as $sensorTypeRow) {
				$sensorType = new SensorType($sensorTypeRow);
				
				$sensorTypeId = $sensorTypeRow['sensor_type_id'];
				$sensorType->setSensorTypeId($sensorTypeId);
				
				array_push($sensorTypes, $sensorType);
			}
		}
		
		return $sensorTypes;
	}
	
	// Returns the $column of SensorTypes whose $type maches $value
	public static function getSensorTypeValuesBy($type = null, $value = null, $column) {
		$sensorTypeRows = SensorTypesDB::getSensorTypeRowsBy($type, $value);
		
		return SensorTypesDB::getSensorTypeValues($sensorTypeRows, $column);
	}
	
	// Returns an array of values from the $column extracted from $rows
	public static function getSensorTypeValues($rows, $column) {
		$sensorTypeValues = array();
		
		foreach ($row as $sensorTypeRow) {
			$sensorTypeValue = $sensorTypeRow[$column];
			array_push($sensorTypeValues, $sensorTypeValue);
		}
		
		return $sensorTypeValues;
	}
	
	// Updates a SensorType entry in the SensorTypes table
	public static function updateSensorType($sensorType) {
		try {
			$db = Database::getDB();
			
			if (is_null($sensorType) || $sensorType->getErrorCount() > 0)
				return $sensorType;
			
			$checkSensorType = SensorTypesDB::getSensorTypesBy('sensor_type_id', $sensorType->getSensorTypeId());
			
			if (empty($checkSensorType)) {
				$sensorType->setError('sensor_type_id', 'SENSOR_TYPE_DOES_NOT_EXIST');
				return $sensorType;
			}
			if ($checkSensorType->getErrorCount() > 0)
				return $sensorType;
			
			$query = "UPDATE SensorTypes SET sensor_type_name = :sensor_type_name,
					description = :description WHERE sensor_type_id = :sensor_type_id";
			
			$statement = $db->prepare($query);
			$statement->bindValue(':sensor_type_name', $sensorType->getSensorTypeName());
			$statement->bindValue(':description', $sensorType->getDescription());
			$statement->bindValue(':sensor_type_id', $sensorType->getSensorTypeId());
			$statement->execute();
			$statement->closeCursor();
		} catch (Exception $e) {
			$sensorType->setError('sensor_type_id', 'SENSOR_TYPE_COULD_NOT_BE_UPDATED');
		}
		
		return $sensorType;
	}
}
?>

