<?php
// Unit test for Sensor class using phpunit
require_once dirname(__FILE__).'/../../models/Sensor.class.php';
require_once dirname(__FILE__).'/../../models/Messages.class.php';

class SensorTest extends PHPUnit_Framework_TestCase {

    public function testValidSensorCreate() {
        $validSensorFields = array("name" => "_sensor-1",
                                "units" => array("temperature.deg_C", "pressure.mm_Hg"),
                                "description" => "This is a test sensor");
        $s1 = new Sensor($validSensorFields);

        $this->assertTrue(is_a($s1, 'Sensor') && $s1->getErrorCount() == 0,
            'It should create a valid Sensor object when valid input is provided');
    }

    public function testInvalidCharInName() {
        $invalidSensorFields = array("name" => '$ensor1',
                                "units" => array("temperature.deg_C", "pressure.mm_Hg"),
                                "description" => "This is a test sensor");
        $s1 = new Sensor($invalidSensorFields);

        $this->assertTrue(!empty($s1->getError('name')),
            'It should have a name error if the sensor name is invalid');
    }

    public function testInvalidUnits() {
        $invalidSensorFields = array("name" => 'sensor1',
                                "units" => array("invalidUnit.deg_C", "pressure.mm_Hg"),
                                "description" => "This is a test sensor");
        $s1 = new Sensor($invalidSensorFields);

        $this->assertTrue(!empty($s1->getError('units')),
            'It should have a units error if any of the sensor units are invalid');
    }
}
?>
