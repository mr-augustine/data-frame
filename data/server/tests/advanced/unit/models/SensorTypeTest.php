<?php
require_once dirname(__FILE__).'/../../../../advanced/models/SensorType.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';

class SensorTypeTest extends PHPUnit_Framework_TestCase {
	
	public function testValidSensorTypeCreate() {
		$validTest = array('sensor_type_id' => 1,
			'sensor_type_name' => 'DISTANCE',
			'description' => 'Measures a distance');
		$validSensorType = new SensorType($validTest);
		
		$this->assertTrue(is_a($validSensorType, 'SensorType'),
				'It should create a SensorType object when valid input is provided');
		$this->assertEquals(0, $validSensorType->getErrorCount(),
				'The SensorType object should be error-free');
	}
	
	public function testInvalidSensorTypeNameBadChar() {
		$invalidTest = array('sensor_type_id' => 1,
			'sensor_type_name' => 'invalid@sensortypename',
			'description' => 'Measures a distance');
		$invalidSensorType = new SensorType($invalidTest);

		$this->assertEquals(1, $invalidSensorType->getErrorCount(),
				'The SensorType object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorType->getError('sensor_type_name')),
				'The SensorType should have a sensor_type_name error');
	}
	
	public function testInvalidSensorTypeNameTooShort() {
		$invalidTest = array('sensor_type_id' => 1,
			'sensor_type_name' => '0',
			'description' => 'The name is too short');
		$invalidSensorType = new SensorType($invalidTest);

		$this->assertEquals(1, $invalidSensorType->getErrorCount(),
				'The SensorType object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorType->getError('sensor_type_name')),
				'The SensorType should have a sensor_type_name error');
	}
	
	public function testInvalidSensorTypeNameTooLong() {
		$invalidTest = array('sensor_type_id' => 1,
			'sensor_type_name' => '012345678901234567890123456789012',
			'description' => 'The name is too long');
		$invalidSensorType = new SensorType($invalidTest);

		$this->assertEquals(1, $invalidSensorType->getErrorCount(),
				'The SensorType object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorType->getError('sensor_type_name')),
				'The SensorType should have a sensor_type_name error');
	}
	
	public function testInvalidDescriptionTooLong() {
		$invalidTest = array('sensor_type_id' => 1,
			'sensor_type_name' => '01234',
			'description' => 'This description contains more than 128 chars'.
                '012345678901234567890123456789012345678901234567890123456789'.
                '012345678901234567890123456789012345678901234567890123456789');
		$invalidSensorType = new SensorType($invalidTest);

		$this->assertEquals(1, $invalidSensorType->getErrorCount(),
				'The SensorType object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorType->getError('description')),
				'The SensorType should have a description error');
	}
}
?>

