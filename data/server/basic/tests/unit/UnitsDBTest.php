<?php
// Tests the UnitsDB class
// - CRUD operations for Unit objects and the Units table
require_once dirname(__FILE__).'/../DBMaker.class.php';
require_once dirname(__FILE__).'/../../models/Database.class.php';
require_once dirname(__FILE__).'/../../models/Messages.class.php';
require_once dirname(__FILE__).'/../../models/Unit.class.php';
require_once dirname(__FILE__).'/../../models/UnitsDB.class.php';

class UnitsDBTest extends PHPUnit_Framework_TestCase {

    public function testGetAllUnits() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $units = UnitsDB::getUnitsBy();
        
        $this->assertEquals(4, count($units),
            'It should fetch all of the units in the test database');
            
        foreach ($units as $unit)
            $this->assertTrue(is_a($unit, 'Unit'),
                'It should return valid Unit objects');
    }
}

?>