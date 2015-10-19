<?php
require_once dirname(__FILE__).'/../DBMaker.class.php';

class DBMakerTest extends PHPUnit_Framework_TestCase {
    
    public function testDatabaseCreate() {
        $myDB = DBMaker::create('dataframetest');
        
        $this->assertTrue(isset($myDB),
            'It should create a database');
    }
}
?>