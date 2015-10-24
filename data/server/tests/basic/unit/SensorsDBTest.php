<?php
// Tests the SensorsDB class
// - CRUD operations for Sensor objects and the Sensors table
require_once dirname(__FILE__).'/../integration/DBMaker.class.php';
require_once dirname(__FILE__).'/../../../basic/models/Database.class.php';
require_once dirname(__FILE__).'/../../../basic/models/Messages.class.php';
require_once dirname(__FILE__).'/../../../basic/models/Sensor.class.php';
require_once dirname(__FILE__).'/../../../basic/models/Unit.class.php';
require_once dirname(__FILE__).'/../../../basic/models/UnitsDB.class.php';
require_once dirname(__FILE__).'/../../../basic/models/SensorsDB.class.php';

class SensorsDBTest extends PHPUnit_Framework_TestCase {

    public function testGetAllSensors() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $sensors = SensorsDB::getSensorsBy();
        $this->assertEquals(1, count($sensors),
            'It should fetch all of the sensors in the test database');
            
        foreach ($sensors as $sensor)
            $this->assertTrue(is_a($sensor, 'Sensor'),
                'It should return valid Sensor objects');
    }
    
    public function testInsertValidSensor() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(SensorsDB::getSensorsBy());
        $validTest = array("name" => "test-sensor-1", 
            "description" => "Living Room",
            "units" => array("temperature.deg_C", "pressure.mm_Hg"));
        $s1 = new Sensor($validTest);
        $newSensor = SensorsDB::addSensor($s1);
        $afterCount = count(SensorsDB::getSensorsBy());
        
        $this->assertEquals($afterCount, $beforeCount + 1,
            'The database should have one more sensor after insertion');
    }
    
    public function testInsertDuplicateSensor() {
        ob_start();
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(SensorsDB::getSensorsBy());
        $duplicateTest = array("name" => "example_temp_sensor", "description" => "none");
        $s1 = new Sensor($duplicateTest);
        $duplicateSensor = SensorsDB::addSensor($s1);
        $afterCount = count(SensorsDB::getSensorsBy());
        
        $this->assertGreaterThan(0, $duplicateSensor->getErrorCount() ,
            'The returned sensor should have a sensorId error');
        
        $this->assertEquals($afterCount, $beforeCount,
            'The database should not have any additional sensors after the insertion attempt');
        ob_get_clean();
    }
    
    public function testInsertInvalidSensor() {
    
    }
    
    public function testInsertEmptySensor() {
        
    }
}
?>
