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
    
    public function testInsertValidUnit() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(UnitsDB::getUnitsBy());
        $validTest = array("unitName" => "electricity.V");
        $s1 = new Unit($validTest);
        $newUnit = UnitsDB::addUnit($s1);
        
        $this->assertEquals(0, $newUnit->getErrorCount(),
            'The created unit should not have errors');
        
        $afterCount = count(UnitsDB::getUnitsBy());
        
        $this->assertEquals($afterCount, $beforeCount + 1,
            'The database should have one more unit after insertion');
    }
    
    public function testInsertDuplicateUnit() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(UnitsDB::getUnitsBy());
        $duplicateTest = array("unitName" => "temperature.deg_C");
        $s1 = new Unit($duplicateTest);
        $newUnit = UnitsDB::addUnit($s1);
        $afterCount = count(UnitsDB::getUnitsBy());
        
        $this->assertEquals($afterCount, $beforeCount,
            'The database should have the same number of elements after trying to insert the duplicate');
    }
    
    public function testInsertEmptyUnit() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(UnitsDB::getUnitsBy());
        $invalidTest = array("unitName" => "");
        $s1 = new Unit($invalidTest);
        $newUnit = UnitsDB::addUnit($s1);
        $afterCount = count(UnitsDB::getUnitsBy());
        
        $this->assertEquals($afterCount, $beforeCount,
            'The database should have the same number of elements after trying to insert the empty Unit');
    }
    
    public function testInsertInvalidUnit() {
        $myDB = DBMaker::create('dataframetest');
        Database::clearDB();
        $db = Database::getDB('dataframetest', '/home/mr-augustine/myConfig.ini');
        $beforeCount = count(UnitsDB::getUnitsBy());
        $invalidTest = array("unitName" => "@temperature.deg_C");
        $s1 = new Unit($invalidTest);
        $newUnit = UnitsDB::addUnit($s1);
        $afterCount = count(UnitsDB::getUnitsBy());
        
        $this->assertEquals($afterCount, $beforeCount,
            'The database should have the same number of elements after trying to insert the invalid Unit');
    }
}

?>