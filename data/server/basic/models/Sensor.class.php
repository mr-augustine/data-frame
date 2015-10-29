<?php
class Sensor {
    private static $DESCRIP_MAX_LENGTH = 255;
    private static $NAME_MAX_LENGTH = 255;

    private $errorCount;
    private $errors;
    private $formInput;

    // Fields from the form
    private $description;
    private $name;
    private $units;

    // Fields from the database
    private $dateAdded;
    private $sensorId;

    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }

    public function getDescription() {
        return $this->description;
    }

    public function getError($errorName) {
        if (isset($this->errors[$errorName]))
            return $this->errors[$errorName];
        else
            return "";
    }

    public function setError($errorName, $errorValue) {
        $this->errors[$errorName] = Messages::getError($errorValue);
        $this->errorCount++;
    }

    public function getErrorCount() {
        return $this->errorCount;
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getName() {
        return $this->name;
    }

    public function getUnits() {
        return $this->units;
    }

    public function getSensorId() {
        return $this->sensorId;
    }
    
    public function setDateAdded($date) {
        $this->dateAdded = $date;
    }

    public function setSensorId($id) {
        $this->sensorId = $id;
    }

    public function setUnits($units) {
        $this->units = $units;
    }
    
    public function __toString() {
        $str = "[" . get_class($this) . ": name=" . $this->getName() .
                ", units= " . print_r($this->getUnits(), true) .
                ", description=" . $this->getDescription() . "]";

        return $str;
    }

    private function extractForm($valueName) {
        $value = "";

        if (isset($this->formInput[$valueName])) {

            // Handle leaf-values and array-values differently
            if (!is_array($this->formInput[$valueName])) {
                $value = trim($this->formInput[$valueName]);
                $value = stripslashes($value);
                $value = htmlspecialchars($value);
            } else {
                $value = array();

                foreach ($this->formInput[$valueName] as $arrayValue) {
                    $tempValue = trim($arrayValue);
                    $tempValue = stripslashes($arrayValue);
                    $tempValue = htmlspecialchars($arrayValue);

                    array_push($value, $tempValue);
                }
            }

            return $value;
        }
    }

    private function initialize() {
        $this->errorCount = 0;
        $this->errors = array();

        if (is_null($this->formInput))
            $this->initializeEmpty();
        else {
            $this->validateName();
            $this->validateUnits();
            $this->validateDescription();
        }
    }

    private function initializeEmpty() {
        $this->errorCount = 0;
        $this->errors = array();
        $this->description = "";
        $this->name = "";
        $this->units = array();
    }

    private function validateDescription() {
        // Description is an optional field
        // If set, length cannot exceed DESCRIP_MAX_LENGTH
        $this->description = $this->extractForm('description');

        if (!empty($this->description)) {
            if (count($this->description) > self::$DESCRIP_MAX_LENGTH)
                $this->setError('description', 'SENSOR_DESCRIP_TOO_LONG');
        }
    }

    private function validateName() {
        // Name is a mandatory field
        // It may only contain letters, numbers, dashes, and underscores
        $this->name = $this->extractForm('name');

        if (empty($this->name))
            $this->setError('name', 'SENSOR_NAME_EMPTY');
        elseif (count($this->name) > self::$NAME_MAX_LENGTH)
            $this->setError('name', 'SENSOR_NAME_TOO_LONG');
        elseif (!filter_var($this->name, FILTER_VALIDATE_REGEXP,
            array("options"=>array("regexp" => "/^[a-zA-z0-9-_]+$/")) )) {
            $this->setError('name', 'SENSOR_NAME_INVALID');
        }
    }

    private function validateUnits() {
        // Units is a mandatory field
        // It/They must mach with the predefined type
        $this->units = $this->extractForm('units');

        if (empty($this->units))
            $this->setError('units', 'SENSOR_UNITS_EMPTY');
        else {
            foreach ($this->units as $unit) {
                if (!in_array($unit, Unit::$UNITS)) {
                    $this->setError('units', 'SENSOR_UNITS_INVALID');
                    // Set an error after the first occurence
                    break;
                }
            }
        }
    }
}

?>
