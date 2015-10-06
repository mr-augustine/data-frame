<?php
class Sensor {
    public static $SENSOR_TYPES = array("temperature");
    public static $UNITS = array("degrees_C" => "temperature",
                                "degrees_F" => "temperature");

    private static $DESCRIP_MAX_LENGTH = 255;
    private static $NAME_MAX_LENGTH = 255;

    private $errorCount;
    private $errors;
    private $formInput;

    private $description;
    private $name;
    private $type;
    private $units;

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

    public function getType() {
        return $this->type;
    }

    public function getUnits() {
        return $this->units;
    }

    public function __toString() {
        $str = "[" . get_class($this) . ": name=" . $this->getName() . ", type=" .
                $this->getType() . ", units= " . $this->getUnits() . ", description=" .
                $this->getDescription() . "]";

        return $str;
    }

    private function extractForm($valueName) {
        $value = "";

        if (isset($this->formInput[$valueName])) {
            $value = trim($this->formInput[$valueName]);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);

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

            // Type validation must occur before Units validation
            $this->validateType();
            $this->validateUnits();

            $this->validateDescription();
        }
    }

    private function initializeEmpty() {
        $this->errorCount = 0;
        $this->errors = array();
        $this->description = "";
        $this->name = "";
        $this->type = "";
        $this->units = "";
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

    private function validateType() {
        // Type is a mandatory field
        // It may only be one of the predefined types
        $this->type = $this->extractForm('type');
                
        if (empty($this->type))
            $this->setError('type', 'SENSOR_TYPE_EMPTY');
        elseif (!in_array($this->type, self::$SENSOR_TYPES))
            $this->setError('type', 'SENSOR_TYPE_INVALID');
    }

    private function validateUnits() {
        // Units is a mandatory field
        // It/They must mach with the predefined type
        $this->units = $this->extractForm('units');

        if (empty($this->units))
            $this->setError('units', 'SENSOR_UNITS_EMPTY');
        elseif (strcmp(self::$UNITS[$this->units], $this->type) != 0)
            $this->setError('units', 'SENSOR_UNITS_INVALID');
    }
}

?>
