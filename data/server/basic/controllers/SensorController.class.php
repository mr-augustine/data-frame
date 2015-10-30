<?php

class SensorController {

    public static function run() {
        $action = (array_key_exists('action', $_SESSION)) ? $_SESSION['action'] : "";
        $arguments = (array_key_exists('arguments', $_SESSION)) ? $_SESSION['arguments'] : "";
        
        switch ($action) {
            case "create":
                $_SESSION['headertitle'] = "DataFrame | Add Sensor";
                self::newSensor();
                
                break;
            case "show":
                if ($arguments == 'all') {
                    $_SESSION['sensors'] = SensorsDB::getSensorsBy();
                    $_SESSION['headertitle'] = "DataFrame | Sensors";
                    
                    SensorView::showAll();
                } else {
                    $sensors = SensorsDB::getSensorsBy('sensorId', $arguments);
                    $_SESSION['sensor'] = $sensors[0];
                    
                    self::show();
                }
                
                break;
            case "update":
                $_SESSION['headertitle'] = "DataFrame | Update Sensor";
                echo "Update A Sensor";
                self::updateSensor();
                
                break;
            default:
        }
    }
    
    public static function show() {
        $arguments = (array_key_exists('arguments', $_SESSION)) ? $_SESSION['arguments'] : 0;
        $sensor = $_SESSION['sensor'];
        
        if (!is_null($sensor))
            SensorView::show();
        else
            HomeView::show();
    }
    
    public static function newSensor() {
        $sensor = null;
        
        if ($_SERVER["REQUEST_METHOD"] == "POST")
            $sensor = new Sensor($_POST);
        
        $_SESSION['sensor'] = $sensor;
        
        if (is_null($sensor) || $sensor->getErrorCount() != 0)
            SensorView::showNew();
        else {
            $newSensor = SensorsDB::addSensor($sensor);
            
            if ($newSensor->getErrorCount() == 0)
                $_SESSION['sensor'] = $newSensor;
                
            SensorView::showAll();
        }
    }
    
    public static function updateSensor() {
        $sensors = SensorsDB::getSensorsBy('sensorId', $_SESSION['arguments']);
        
        if (empty($sensors)) {
            SensorView::show();
        } 
        // User selects a link to update an existing sensor
        elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
            $_SESSION['sensor'] = $sensors[0];
            SensorView::showUpdate();
        }
        // User makes a POST from the update form
        else {
            $sensor = $sensors[0];
            $params = $sensor->getParameters();
            $params['name'] = (array_key_exists('name', $_POST)) ? $_POST['name'] : "";
            $params['description'] = (array_key_exists('description', $_POST))
                ? $_POST['description'] : "";

            // TODO: Include any changes to the units, iff we decide to allow this
            // For now, just carry over the units as defined in $sensor already
            // NOTE: The following line produces an empty array
            //$params['units'] = (array_key_exists('units', $_POST)) ? $_POST['units'] : array();

            
            $editedSensor = new Sensor($params);
            $editedSensor->setSensorId($sensor->getSensorId());
            
            $updatedSensor = SensorsDB::updateSensor($editedSensor);
            
            // If there's an error, have another go at it.
            // Otherwise, show the user the updated sensor.
            if ($updatedSensor->getErrorCount() != 0) {
                $_SESSION['sensor'] = $updatedSensor;
                SensorView::showUpdate();
            } else {
                // TODO: Doesn't seem quite right; just redirect to the showall with no args
                $_SESSION['arguments'] = $updatedSensor->getSensorId();
                SensorView::showAll();
            }
        }
    }
}

?>