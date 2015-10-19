<?php
include_once ("Messages.class.php");

class Unit {
    public static $UNITS = array("temperature.deg_C",
                                "temperature.deg_F",
                                "pressure.mm_Hg",
                                "pressure.in_Hg");
    private static $MAX_UNITNAME_LENGTH = 255;
    private $errorCount;
    private $errors;
    private $formInput;
    
    private $unitId
    private $unitName
    
    public function __construct($formInput = null) {
        $this->formInput = $formInput;
        Messages::reset();
        $this->initialize();
    }
    
    public function getError($errorName) {
        if (isset($this->errors[$errorName]))
            return $this->errors[$errorName];
        else
            return "";
    }
    
    private function initialize() {
        $this->errorCount = 0;
        $errors = array();
        
        if (is_null($this->formInput))
            $this->initializeEmpty();
        else {
            $this->validateUnitName();
        }
    }
    
    private function initializeEmpty() {
        $this->errorCount = 0;
        $errors = array();
        $this->unitName = "";
    }
    
    private function extractForm($valueName) {
        $value = ""
        
        // Expecting array("unitName" => "temperature.deg_C")
        // or similar, with optional "unitId" => 1
        if (isset($this->formInput[$valueName])) {
            $value = trim($this->formInput[$valueName]);
            $value = stripslashes($value);
            $value = htmlspecialchars($value);
            
            return $value;
        }
    }
    
    public function setUnitId($id) {
        $this->unitId = $id;
    }
    
    public function setError($errorName, $errorValue) {
        $this->errors[$errorName] = Messages::getError($errorValue);
        $this->errorCount++;
    }
    
    public function getUnitName() {
        return $this->unitName;
    }
    
    public function getUnitId() {
        return $this->unitId;
    }
    
    public function validateUnitName() {
        if (empty($this->unitName)) {
            $this->setError('unitName', 'UNIT_NAME_EMPTY');
        } elseif (strlen($this->unitName) > self::$MAX_UNITNAME_LENGTH) {
            $this->setError('unitName', 'UNIT_NAME_TOO_LONG');
        } elseif (!filter_var($this->unitName, FILTER_VALIDATE_REGEXP,
            array("options"=>array("regexp" => "/^([a-zA-Z0-9\-\_.])+$/i")) )) {
            $this->setError('unitName', 'UNIT_NAME_HAS_INVALID_CHARS');
        }
    }
    
}
?>