<?php
require_once dirname(__FILE__).'/../../../../advanced/models/Database.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/SensorTypesDB.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/SensorType.class.php';
require_once dirname(__FILE__).'/../../integration/DBMaker.class.php';

class SensorTypesDBTest extends PHPUnit_Framework_TestCase {
	
	public function testGetAllSensorTypes() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$sensorTypes = SensorTypesDB::getSensorTypesBy();
		
		$this->assertEquals(3, count($sensorTypes),
				'It should fetch all of the sensorTypes in the test database');
		
		foreach ($sensorTypes as $sensorType)
			$this->assertTrue(is_a($sensorType, 'SensorType'),
					'It should return valid SensorType objects');
	}
	
	public function testInsertValidSensorType() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$validTest = array('sensor_type_id' => 1,
			'sensor_type_name' => 'ALTITUDE',
			'description' => 'Measures height above mean sea level');
		$validSensorType = new SensorType($validTest);
		
		$beforeCount = count(SensorTypesDB::getSensorTypesBy());
		$newSensorType = SensorTypesDB::addSensorType($validSensorType);
		
		$this->assertEquals(0, $newSensorType->getErrorCount(),
				'The inserted sensor type should be error-free');
		
		$afterCount = count(SensorTypesDB::getSensorTypesBy());
		$this->assertEquals($afterCount, $beforeCount + 1,
				'The database should have one more sensor type after insertaion');		
	}
	
	public function testInsertInvalidSensorTypeEmptyName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$invalidTest = array('sensor_type_id' => 1,
			'sensor_name' => '',
			'description' => 'Blank sensor type name');
		$invalidSensorType = new SensorType($invalidTest);
		
		$beforeCount = count(SensorTypesDB::getSensorTypesBy());
		$newSensorType = SensorTypesDB::addSensorType($invalidSensorType);
		
		$this->assertGreaterThan(0, $newSensorType->getErrorCount(),
				'The attempted insertion should return a sensor type with an error');
		
		$afterCount = count(SensorTypesDB::getSensorTypesBy());
		$this->assertEquals($afterCount, $beforeCount,
				'The database should have no additional sensor types after insertion');
	}
	
 	public function testInsertDuplicateSensorType() {
 		$myDb = DBMaker::create('dataframetest');
 		Database::clearDB();
 		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');

        $duplicateTest = array('sensor_name' => 'DISTANCE',
            'description' => 'Yet another distance sensor type');
        $duplicateSensorType = new SensorType($duplicateTest);

        $beforeCount = count(SensorTypesDB::getSensorTypesBy());
        $duplicate = SensorTypesDB::addSensorType($duplicateSensorType);

        $this->assertGreaterThan(0, $duplicate->getErrorCount(),
            'The attempted duplicate insertion to return a sensor type with an error');

        $afterCount = count(SensorTypesDB::getSensorTypesBy());
        $this->assertEquals($afterCount, $beforeCount,
            'The database should have no additional sensor types after insertion');
 	}
	
	public function testGetSensorTypesBySensorTypeId() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorTypeId = 2;
		$sensorTypes = SensorTypesDB::getSensorTypesBy('sensor_type_id', $testSensorTypeId);
		
		$this->assertEquals(1, count($sensorTypes),
				'The database should return exactly one sensor type with the provided id');
		
		$sensorType = $sensorTypes[0];
		$this->assertEquals($testSensorTypeId, $sensorType->getSensorTypeId(),
				'The database should return the sensor type with the provided id');
	}
	
	public function testGetSensorTypesBySensorTypeName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorTypeName = 'DISTANCE';
		$sensorTypes = SensorTypesDB::getSensorTypesBy('sensor_type_name', $testSensorTypeName);
		
        $this->assertEquals(1, count($sensorTypes),
            'The database should return exactly one sensor type');

        $sensorType = $sensorTypes[0];
	    $this->assertEquals($testSensorTypeName, $sensorType->getSensorTypeName(),
			'The database should return the sensor type that has the specified name');
	}
	
	public function testUpdateSensorTypeName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorTypeId = 3;
		
		$sensorTypes = SensorTypesDB::getSensorTypesBy('sensor_type_id', $testSensorTypeId);
		$sensorType = $sensorTypes[0];
		
		$this->assertEquals($sensorType->getSensorTypeName(), 'IMAGING',
				'Before the update, it should have name IMAGING');
		
		$params = $sensorType->getParameters();
		$params['sensor_type_name'] = 'SUPER-IMAGING';
		$newSensorType = new SensorType($params);
		$newSensorType->setSensorTypeId($testSensorTypeId);
		
		$returnedSensorType = SensorTypesDB::updateSensorType($newSensorType);
		
		$this->assertEquals($returnedSensorType->getSensorTypeName(), $params['sensor_type_name'],
				'After the update it should have the name '.$params['sensor_type_name']);
		$this->assertTrue(empty($returnedSensorType->getErrors()),
				'The updated sensor type should be error-free');
	}
	
	public function testUpdateSensorDescription() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorTypeId = 1;
		
		$sensorTypes = SensorTypesDB::getSensorTypesBy('sensor_type_id', $testSensorTypeId);
		$sensorType = $sensorTypes[0];
		
		$this->assertEquals($sensorType->getDescription(), 
			"A count of how far something has traveled",
			"Before the update, it should have a description about the DISTANCE type");
		
		$params = $sensorType->getParameters();
		$parmas['description'] = 'Here is a new description';
		$newSensorType = new SensorType($params);
		$newSensorType->setSensorTypeId($testSensorTypeId);
		
		$returnedSensorType = SensorTypesDB::updateSensorType($newSensorType);
		
		$this->assertEquals($returnedSensorType->getDescription(), $params['description'],
				'After the update it should have the description '.$params['description']);
		$this->assertTrue(empty($returnedSensorType->getErrors()),
				'The updated sensor should be error-free');
	}
}
?>

