<?php

class SensorController {

    public static function run() {
        $action = (array_key_exists('action', $_SESSION)) ? $_SESSION['action'] : "";
        $arguments = (array_key_exists('arguments', $_SESSION)) ? $_SESSION['arguments'] : "";
        
        switch ($action) {
            case "create":
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
                    
                    self::show;
                }
                
                break;
            case "update":
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
        
        if (is_null($sensor) || $sensor->getErrorCount != 0)
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
            $_SESSION['sensors'] = $sensors;
            SensorView::showUpdate();
        }
        // User makes a POST from the update form
        else {
            $sensor = $sensors[0];
            $params = $sensor->getParameters();
            $params['name'] = (array_key_exists('name', $_POST)) ? $_POST['name'] : "";
            $params['description'] = (array_key_exists('description', $_POST))
                ? $_POST['description'] : "";
                
            $editedSensor = new Sensor($params);
            $editedSensor->setSensorId($sensor->getSensorId());
            
            $updatedSensor = SensorsDB::updateSensor($editedSensor);
            
            // If there's an error, have another go at it.
            // Otherwise, show the user the updated sensor.
            if ($updatedSensor->getErrorCount() != 0) {
                $_SESSION['sensors'] = array($updatedSensor);
                SensorView::showUpdate();
            } else {
                $_SESSION['arguments'] = $updatedSensor->getSensorId();
                SensorView::show();
            }
        }
    }
}

?>