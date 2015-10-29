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
        echo '<form style="display: inline" action="/'.$base.'/sensor/create" method="GET">';
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
        $sensor = (array_key_exists('sensor', $_SESSION)) ? $_SESSION['sensor'] : null;
        $base = (array_key_exists('base', $_SESSION)) ? $_SESSION['base'] : "";
        MasterView::showHeader();
        
        echo '<h1>Add a Sensor</h1>';
        
        echo '<form action="'.$base.'/sensor/create" method="POST">'."\n";
        echo 'Name: <input type="text" name="name"';
            if (!is_null($sensor)) { echo 'value="'.$edit->getName().'" '; }
        echo 'tabindex="1" required>'."\n";
        echo '<span class="error">';
            if (!is_null($sensor)) { echo $edit->getError('name'); }
        echo '</span><br><br>'."\n";
        
        echo 'Units:<br>';
        // Display the dropdown lists for the sensor's units
        if (!is_null($sensor) && count($sensor->getUnits()) > 0) {
            // Display all of the dropdown lists
            $units = $sensor->getUnits();
            
            foreach ($units as $unit) {
                displaySelectedUnitDropdown($unit);
            }
        } else {
            // Display just one unselected dropdown
            self::displaySelectedUnitDropdown(null);
        }
        
        echo '<br><br>';
        // Description text area
        echo 'Description:';
        echo '<br>';
        echo '<textarea name="description" maxlength="255" rows="2" cols="80">';
        echo '</textarea>';
        
        // Submit button and cancel link
        echo '<p><input type="submit" name="submit" value="Submit">';
        echo '&nbsp&nbsp';
        echo '<a href="/'.$base.'/sensor/show/all">Cancel</a><br>';
        echo '</form>';
    }
    
    private function displaySelectedUnitDropdown($unit) {
        $validUnits = Unit::$UNITS;        
        
        echo '<select name="units[]">'."\n";
        
        if (!is_null($unit)) {
            echo '<option value=""> </option>'."\n";
            
            foreach ($validUnits as $validUnit) {
                echo '<option ';
                
                if ($unit == $validUnit) { echo 'selected="selected" '; }
                
                echo 'value="'.$validUnit.'">'.$validUnit.'</option>'."\n";
            }
        } else {
            echo '<option selected="selected" value=""> </option>'."\n";
            
            foreach ($validUnits as $validUnit) {
                echo '<option value="'.$validUnit.'">'.$validUnit.'</option>'."\n";
            }
        }
        
        echo '</select>';
    }
    
    
    public static function showUpdate() {
    
    }
}

?>