<?php
require_once dirname(__FILE__).'/../../../../advanced/models/SensorUnit.class.php';
require_once dirname(__FILE__).'/../../../../advanced/models/Messages.class.php';

class SensorUnitTest extends PHPUnit_Framework_TestCase {
	
	public function testValidSensorUnitCreate() {
		$validTest = array('sensor_unit_id' => 1,
			'sensor_unit_name' => 'FEET',
			'description' => 'Otherwise known as 12 inches');
		$validSensorUnit = new SensorUnit($validTest);
		
		$this->assertTrue(is_a($validSensorUnit, 'SensorUnit'),
				'It should create a SensorUnit object when valid input is provided');
		$this->assertEquals(0, $validSensorUnit->getErrorCount(),
				'The SensorUnit object should be error-free');
	}
	
	public function testInvalidSensorUnitNameBadChar() {
		$invalidTest = array('sensor_unit_id' => 1,
			'sensor_unit_name' => 'FEET@',
			'description' => 'Otherwise known as 12 inches');
		$invalidSensorUnit = new SensorUnit($invalidTest);

		$this->assertEquals(1, $invalidSensorUnit->getErrorCount(),
				'The SensorUnit object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorUnit->getError('sensor_unit_name')),
				'The SensorUnit should have a sensor_unit_name error');
	}
	
	public function testInvalidSensorUnitNameTooShort() {
		$invalidTest = array('sensor_unit_id' => 1,
			'sensor_unit_name' => '0',
			'description' => 'The name is too short');
		$invalidSensorUnit = new SensorUnit($invalidTest);

		$this->assertEquals(1, $invalidSensorUnit->getErrorCount(),
				'The SensorUnit object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorUnit->getError('sensor_unit_name')),
				'The SensorUnit should have a sensor_unit_name error');
	}
	
	public function testInvalidSensorUnitNameTooLong() {
		$invalidTest = array('sensor_unit_id' => 1,
			'sensor_unit_name' => '012345678901234567890123456789012',
			'description' => 'The name is too long');
		$invalidSensorUnit = new SensorUnit($invalidTest);

		$this->assertEquals(1, $invalidSensorUnit->getErrorCount(),
				'The SensorUnit object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorUnit->getError('sensor_unit_name')),
				'The SensorUnit should have a sensor_unit_name error');
	}
	
	public function testInvalidDescriptionTooLong() {
		$invalidTest = array('sensor_unit_id' => 1,
			'sensor_unit_name' => '01234',
			'description' => 'This description contains more than 128 chars'.
                '012345678901234567890123456789012345678901234567890123456789'.
                '012345678901234567890123456789012345678901234567890123456789');
		$invalidSensorUnit = new SensorUnit($invalidTest);

		$this->assertEquals(1, $invalidSensorUnit->getErrorCount(),
				'The SensorUnit object should have exactly 1 error');
		$this->assertTrue(!empty($invalidSensorUnit->getError('description')),
				'The SensorUnit should have a description error');
	}
}
?>


