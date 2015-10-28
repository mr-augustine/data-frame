<?php

class SensorView {

    public static function show() {
        $_SESSION['headertitle'] = "DataFrame | Sensors";
        MasterView::showHeader();
        SensorView::showDetails();
        MasterView::showFooter();
    }
    
    public static function showAll() {
        if (array_key_exists('headertitle', $_SESSION))
            MasterView::showHeader();
            
        $sensors = (array_key_exists('sensors', $_SESSION)) ? $_SESSION['sensors'] : array();
        $base = (array_key_exists('base', $_SESSION)) ? $_SESSION['base'] : "";
        
        echo '<h2>Sensors Summary</h2>';
        echo '<p>Summary text goes here</p>';
        echo '<form style="display: inline" action="/'.$base.'/sensor/add/" method="GET">';
        echo '<button>Add a Sensor</button>';
        echo '<br><br>';
        echo '</form>';
        
        // Display a list of all sensors
        $sensors = SensorsDB::getSensorsBy();
        
        foreach ($sensors as $sensor)
            echo '<a href="/'.$base.'/sensor/show/'.$sensor->getSensorId().'">'.$sensor->getName().'</a><br>';
    }
    
    public static function showDetails() {
    
    }
    
    public static function showNew() {
    
    }
    
    public static function showUpdate() {
    
    }
}

?>