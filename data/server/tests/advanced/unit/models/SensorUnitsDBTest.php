<?php
require_once dirname(__FILE__).'/../../../../advanced/models/Database.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/SensorUnitsDB.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/SensorUnit.class.php';
require_once dirname(__FILE__).'/../../integration/DBMaker.class.php';

class SensorUnitsDBTest extends PHPUnit_Framework_TestCase {
	
	public function testGetAllSensorUnits() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$sensorUnits = SensorUnitsDB::getSensorUnitsBy();
		
		$this->assertEquals(2, count($sensorUnits),
				'It should fetch all of the sensorUnits in the test database');
		
		foreach ($sensorUnits as $sensorUnit)
			$this->assertTrue(is_a($sensorUnit, 'SensorUnit'),
					'It should return valid SensorUnit objects');
	}
	
	public function testInsertValidSensorUnit() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$validTest = array('sensor_unit_name' => 'FEET',
			'description' => 'Also known as 12 inches');
		$validSensorUnit = new SensorUnit($validTest);
		
		$beforeCount = count(SensorUnitsDB::getSensorUnitsBy());
		$newSensorUnit = SensorUnitsDB::addSensorUnit($validSensorUnit);
		
		$this->assertEquals(0, $newSensorUnit->getErrorCount(),
				'The inserted sensor unit should be error-free');
		
		$afterCount = count(SensorUnitsDB::getSensorUnitsBy());
		$this->assertEquals($afterCount, $beforeCount + 1,
				'The database should have one more sensor unit after insertaion');		
	}
	
	public function testInsertInvalidSensorUnitEmptyName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		
		$invalidTest = array('sensor_unit_name' => '',
			'description' => 'Blank sensor unit name');
		$invalidSensorUnit = new SensorUnit($invalidTest);
		
		$beforeCount = count(SensorUnitsDB::getSensorUnitsBy());
		$newSensorUnit = SensorUnitsDB::addSensorUnit($invalidSensorUnit);
		
		$this->assertGreaterThan(0, $newSensorUnit->getErrorCount(),
				'The attempted insertion should return a sensor unit with an error');
		
		$afterCount = count(SensorUnitsDB::getSensorUnitsBy());
		$this->assertEquals($afterCount, $beforeCount,
				'The database should have no additional sensor unit after insertion');
	}
	
 	public function testInsertDuplicateSensorUnit() {
 		$myDb = DBMaker::create('dataframetest');
 		Database::clearDB();
 		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');

        $duplicateTest = array('sensor_name' => 'CENTIMETERS',
            'description' => 'Yet another centimeter sensor unit');
        $duplicateSensorUnit = new SensorUnit($duplicateTest);

        $beforeCount = count(SensorUnitsDB::getSensorUnitsBy());
        $duplicate = SensorUnitsDB::addSensorUnit($duplicateSensorUnit);

        $this->assertGreaterThan(0, $duplicate->getErrorCount(),
            'The attempted duplicate insertion to return a sensor unit with an error');

        $afterCount = count(SensorUnitsDB::getSensorUnitsBy());
        $this->assertEquals($afterCount, $beforeCount,
            'The database should have no additional sensor units after insertion');
 	}
	
	public function testGetSensorUnitsBySensorUnitId() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorUnitId = 2;
		$sensorUnits = SensorUnitsDB::getSensorUnitsBy('sensor_unit_id', $testSensorUnitId);
		
		$this->assertEquals(1, count($sensorUnits),
				'The database should return exactly one sensor unit with the provided id');
		
		$sensorUnit = $sensorUnits[0];
		$this->assertEquals($testSensorUnitId, $sensorUnit->getSensorUnitId(),
				'The database should return the sensor unit with the provided id');
	}
	
	public function testGetSensorUnitsBySensorUnitName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorUnitName = 'COLOR';
		$sensorUnits = SensorUnitsDB::getSensorUnitsBy('sensor_unit_name', $testSensorUnitName);
		
        $this->assertEquals(1, count($sensorUnits),
            'The database should return exactly one sensor unit');

        $sensorUnit = $sensorUnits[0];
	    $this->assertEquals($testSensorUnitName, $sensorUnit->getSensorUnitName(),
			'The database should return the sensor unit that has the specified name');
	}
	
	public function testUpdateSensorUnitName() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorUnitId = 2;
		
		$sensorUnits = SensorUnitsDB::getSensorUnitsBy('sensor_unit_id', $testSensorUnitId);
		$sensorUnit = $sensorUnits[0];
		
		$this->assertEquals($sensorUnit->getSensorUnitName(), 'COLOR',
				'Before the update, it should have name COLOR');
		
		$params = $sensorUnit->getParameters();
		$params['sensor_unit_name'] = 'COLOR-16-BIT';
		$newSensorUnit = new SensorUnit($params);
		$newSensorUnit->setSensorUnitId($testSensorUnitId);
		
		$returnedSensorUnit = SensorUnitsDB::updateSensorUnit($newSensorUnit);
		
		$this->assertEquals($returnedSensorUnit->getSensorUnitName(), $params['sensor_unit_name'],
				'After the update it should have the name '.$params['sensor_unit_name']);
		$this->assertTrue(empty($returnedSensorUnit->getErrors()),
				'The updated sensor type should be error-free');
	}
	
    public function testUpdateSensorDescription() {
		$myDb = DBMaker::create('dataframetest');
		Database::clearDB();
		$db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
		$testSensorUnitId = 2;
		
		$sensorUnits = SensorUnitsDB::getSensorUnitsBy('sensor_unit_id', $testSensorUnitId);
		$sensorUnit = $sensorUnits[0];
		
		$this->assertEquals($sensorUnit->getDescription(), 
			"Images that display things in the visible light spectrum",
			"Before the update, it should have a description about the COLOR type");
		
		$params = $sensorUnit->getParameters();
		$parmas['description'] = 'Here is a new description';
		$newSensorUnit = new SensorUnit($params);
		$newSensorUnit->setSensorUnitId($testSensorUnitId);
		
		$returnedSensorUnit = SensorUnitsDB::updateSensorUnit($newSensorUnit);
		
		$this->assertEquals($returnedSensorUnit->getDescription(), $params['description'],
				'After the update it should have the description '.$params['description']);
		$this->assertTrue(empty($returnedSensorUnit->getErrors()),
				'The updated sensor should be error-free');
	}
}
?>


