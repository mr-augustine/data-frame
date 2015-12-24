<?php
require_once dirname(__FILE__).'/../../../../advanced/models/Database.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/SensorsDB.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Sensor.class.php';
require_once dirname(__FILE__).'/../../integration/DBMaker.class.php';

class SensorsDBTest extends PHPUnit_Framework_TestCase {
	
	public function testGetAllSensors() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$sensors = SensorsDB::getSensorsBy();
		
		$this->assertEquals(4, count($sensors),
				'It should fetch all of the sensors in the test database');
		
		foreach ($sensors as $sensor)
			$this->assertTrue(is_a($sensor, 'Sensor'),
					'It should return valid Sensor objects');
	}
	
	public function testInsertValidSensor() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$validTest = array('dataset_id' => 1,
			'sensor_name' => 'front_camera',
			'sensor_type' => 'IMAGING',
			'sensor_units' => 'COLOR',
			'sequence_type' => 'TIME-CODED',
			'description' => 'Camera at the front');
		$validSensor = new Sensor($validTest);
		
		$beforeCount = count(SensorsDB::getSensorsBy());
		$newSensor = SensorsDB::addSensor($validSensor);
		
		$this->assertEquals(0, $newSensor->getErrorCount(),
				'The inserted sensor should be error-free');
		
		$afterCount = count(SensorsDB::getSensorsBy());
		$this->assertEquals($afterCount, $beforeCount + 1,
				'The database should have one more sensor after insertaion');		
	}
	
	public function testInsertInvalidSensor() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$invalidTest = array('dataset_id' => 1,
			'sensor_name' => 'front_camera',
			'sensor_type' => 'INVALID-TYPE',
			'sensor_units' => 'COLOR',
			'sequence_type' => 'TIME-CODED',
			'description' => 'Camera at the front');
		$invalidSensor = new Sensor($invalidTest);
		
		$beforeCount = count(SensorsDB::getSensorsBy());
		$newSensor = SensorsDB::addSensor($invalidSensor);
		
		$this->assertGreaterThan(0, $newSensor->getErrorCount(),
				'The attempted insertion should return a sensor with an error');
		
		$afterCount = count(SensorsDB::getSensorsBy());
		$this->assertEquals($afterCount, $beforeCount,
				'The database should have no additional sensors after insertion');
	}
	
	// Duplicate sensor names are allowed
// 	public function testInsertDuplicateSensor() {
// 		$myDb = DBMaker::create('dataframetest');
// 		Database::clearDB();
// 		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
// 	}
	
	public function testGetSensorsBySensorId() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 2;
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		
		$this->assertEquals(1, count($sensors),
				'The database should return exactly one sensor with the provided id');
		
		$sensor = $sensors[0];
		$this->assertEquals($testSensorId, $sensor->getSensorId(),
				'The database should return the sensor with the provided id');
	}
	
	public function testGetSensorsBySensorName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorName = 'compass0';
		$sensors = SensorsDB::getSensorsBy('sensor_name', $testSensorName);
		
		foreach ($sensors as $sensor)
			$this->assertEquals($testSensorName, $sensor->getSensorName(),
				'The database should return sensors that have the specified name');
	}
	
	public function testGetSensorsByDatasetId() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testDatasetId = 1;
		$sensors = SensorsDB::getSensorsBy('dataset_id', $testDatasetId);
		
		foreach ($sensors as $sensor)
			$this->assertEquals($testDatasetId, $sensor->getDatasetId(),
				'The database should return sensors that have the specified dataset_id');
	}
	
	public function testUpdateSensorName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 3;
		
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		$sensor = $sensors[0];
		
		$this->assertEquals($sensor->getSensorName(), 'bump0',
				'Before the update, it should have name bump0');
		
		$params = $sensor->getParameters();
		$params['sensor_name'] = 'bump_switch_0';
		$newSensor = new Sensor($params);
		$newSensor->setSensorId($testSensorId);
		
		$returnedSensor = SensorsDB::updateSensor($newSensor);
		
		$this->assertEquals($returnedSensor->getSensorName(), $params['sensor_name'],
				'After the update it should have the name '.$params['sensor_name']);
		$this->assertTrue(empty($returnedSensor->getErrors()),
				'The updated sensor should be error-free');
	}
	
	public function testUpdateSensorDescription() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 1;
		
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		$sensor = $sensors[0];
		
		$this->assertEquals($sensor->getDescription(), 
				"The robot's only compass. Placed ontop a mast.",
				"Before the update, it should have a description about the robot's compass");
		
		$params = $sensor->getParameters();
		$parmas['description'] = 'The bump switch is twitchy';
		$newSensor = new Sensor($params);
		$newSensor->setSensorId($testSensorId);
		
		$returnedSensor = SensorsDB::updateSensor($newSensor);
		
		$this->assertEquals($returnedSensor->getDescription(), $params['description'],
				'After the update it should have the description '.$params['description']);
		$this->assertTrue(empty($returnedSensor->getErrors()),
				'The updated sensor should be error-free');
	}
	
	public function testUpdateSensorType() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 2;
		
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		$sensor = $sensors[0];
		
		$this->assertEquals($sensor->getSensorType(), 'RANGE',
				'Before the update, it should have a sensor type of RANGE');
		
		$params = $sensor->getParameters();
		$params['sensor_type'] = 'DISTANCE';
		$newSensor = new Sensor($params);
		$newSensor->setSensorId($testSensorId);
		
		$returnedSensor = SensorsDB::updateSensor($newSensor);
		
		$this->assertEquals($returnedSensor->getSensorType(), $params['sensor_type'],
				'After the update it should have a sensor type of '.$params['sensor_type']);
		$this->assertTrue(empty($returnedSensor->getErrors()),
				'The updated sensor should be error-free');
	}
	
	public function testUpdateSensorUnits() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 2;
		
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		$sensor = $sensors[0];
		
		$this->assertEquals($sensor->getSensorUnits(), 'CENTIMETERS',
				'Before the update, it should have units of CENTIMETERS');
		
		$params = $sensor->getParameters();
		$params['sensor_units'] = 'METERS';
		$newSensor = new Sensor($params);
		$newSensor->setSensorId($testSensorId);
		
		$returnedSensor = SensorsDB::updateSensor($newSensor);
		
		$this->assertEquals($returnedSensor->getSensorUnits(), $params['sensor_units'],
				'After the update it should have units of '.$params['sensor_units']);
		$this->assertTrue(empty($returnedSensor->getErrors()),
				'The updated sensor should be error-free');
	}
	
	public function testUpdateSequenceType() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorId = 2;
		
		$sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
		$sensor = $sensors[0];
		
		$this->assertEquals($sensor->getSequenceType(), 'SEQUENTIAL',
				'Before the update, it should have sequence type SEQUENTIAL');
		
		$params = $sensor->getParameters();
		$params['sequence_type'] = 'TIME-CODED';
		$newSensor = new Sensor($params);
		$newSensor->setSensorId($testSensorId);
		
		$returnedSensor = SensorsDB::updateSensor($newSensor);
		
		$this->assertEquals($returnedSensor->getSequenceType(), $params['sequence_type'],
				'After the update it should have sequence_type '.$params['sequence_type']);
		$this->assertTrue(empty($returnedSensor->getErrors()),
				'The updated sensor should be error-free');
	}

    public function testDeleteExistingSensor() {
        $myDb = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');

        $testSensorId = 4;
        $sensors = SensorsDB::getSensorsBy('sensor_id', $testSensorId);
        $existingSensor = $sensors[0];

        $beforeCount = count(SensorsDB::getSensorsBy());
        $deletedSensor = SensorsDB::deleteSensor($existingSensor);
        $afterCount = count(SensorsDB::getSensorsBy());
        $this->assertEquals(0, $deletedSensor->getErrorCount(),
            'The deleted sensor should be error-free');
        $this->assertEquals($beforeCount - 1, $afterCount,
            'The database should have one less sensor in the Sensors table');
    }

    public function testDeleteNonexistentSensor() {
        $myDb = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');

        $invalidSensorId = 99;

        $this->assertEquals(0, count(SensorsDB::getSensorsBy('dataset_id', $invalidSensorId)),
            'The specified sensor should not exist in the database');

        $validParams = array('sensor_id' => $invalidSensorId, 'dataset_id' => 1,
            'sensor_name' => 'valid_sensor_name', 'sensor_type' => 'DISTANCE',
            'sensor_units' => 'FEET', 'sequence_type' => 'SEQUENTIAL',
            'description' => 'This sensor does not exist');
        $nonexistentSensor = new Sensor($validParams);

        $beforeCount = count(SensorsDB::getSensorsBy());
        $deletedSensor = SensorsDB::deleteSensor($nonexistentSensor);
        $afterCount = count(SensorsDB::getSensorsBy());

        $this->assertEquals($afterCount, $beforeCount,
            'The database should maintain the same number of sensors');
    }
}
?>
