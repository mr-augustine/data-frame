<?php
// Tests the SensorsDB class
// - CRUD operations for Sensor objects and the Sensors table
require_once dirname(__FILE__).'/../DBMaker.class.php';
require_once dirname(__FILE__).'/../../models/Database.class.php';
require_once dirname(__FILE__).'/../../models/Messages.class.php';
require_once dirname(__FILE__).'/../../models/Sensor.class.php';
require_once dirname(__FILE__).'/../../models/SensorsDB.class.php';

class SensorsDBTest extends PHPUnit_Framework_TestCase {

    public function testGetAllSensors() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('databasetest', '/home/mr-augustine/myConfig.ini');
        $sensors = SensorsDB::getSensorsBy();
        $this->assertEquals(1, count($sensors),
            'It should fetch all of the sensors in the test database');
            
        foreach ($sensors as $sensor)
            $this->assertTrue(is_a($sensor, 'Sensor'),
                'It should return valid Sensor objects');
    }
}
?>
