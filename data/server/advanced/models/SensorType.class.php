<?php
include_once("Messages.class.php");

class SensorType {
    public static $MAX_DESCRIPTION_LENGTH = 128;
	public static $MAX_SENSOR_TYPE_NAME_LENGTH = 32;
	public static $MIN_SENSOR_TYPE_NAME_LENGTH = 3;
	
	private $errorCount;
	private $errors;
	private $formInput;
	
	private $sensor_type_id;
	private $sensor_type_name;
	private $description;

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
	
	public function getSensorTypeId() {
		return $this->sensor_type_id;
	}
	
	public function setSensorTypeId($id) {
		$this->sensor_type_id = $id;
	}
	
	public function getSensorTypeName() {
		return $this->sensor_type_name;
	}
	
	public function getDescription() {
		return $this->description;
	}
	
	public function getParameters() {
		$paramArray = array('sensor_type_id' => $this->sensor_type_id,
							'sensor_type_name' => $this->sensor_type_name,
							'description' => $this->description);
		
		return $paramArray;
	}
	
	public function __toString() {
		$objectString = "[SensorType] {id: ".$this->sensor_type_id.
		    ", name: ".$this->sensor_type_name.
            ", description: ".$this->description."}";
		
		return $objectString;
	}
	
	private function extractForm($valueName) {
		$value = "";
		
		if (isset($this->formInput[$valueName])) {
			$value = trim($this->formInput[$valueName]);
		    $value = stripslashes($value);
		    $value = htmlspecialchars($value);
		}
		
		return $value;
	}
	
	private function initialize() {
		$this->errorCount = 0;
		$this->errors = array();
		
		if (!is_null($this->formInput)) {
			$this->sensor_id = "";
			
			$this->validateSensorTypeName();
			$this->validateDescription();
		} else {
			$this->initializeEmpty();
		}
	}
	
	private function initializeEmpty() {
		$this->errorCount = 0;
		$this->errors = array();
		$this->sensor_type_id = "";
		$this->sensor_type_name = "";
		$this->description = "";
	}

    private function validateSensorTypeName() {
		$this->sensor_type_name = $this->extractForm('sensor_type_name');
		
		// Not empty
		if (empty($this->sensor_type_name))
			$this->setError('sensor_type_name', 'SENSOR_TYPE_NAME_EMPTY');
		// Meets length constraints (min & max)
		else if (strlen($this->sensor_type_name) < self::$MIN_SENSOR_TYPE_NAME_LENGTH)
			$this->setError('sensor_type_name', 'SENSOR_TYPE_NAME_TOO_SHORT');
		else if (strlen($this->sensor_type_name) > self::$MAX_SENSOR_TYPE_NAME_LENGTH)
			$this->setError('sensor_type_name', 'SENSOR_TYPE_NAME_TOO_LONG');
		// Only valid chars
		else if (!filter_var($this->sensor_type_name, FILTER_VALIDATE_REGEXP,
				array('options' => array('regexp' => "/^([a-zA-Z0-9\-\_.])+$/i")) )) {
			$this->setError('sensor_type_name', 'SENSOR_TYPE_NAME_INVALID_CHARS');
		}
	}
	
	private function validateDescription() {
		$this->description = $this->extractForm('description');
		
		// Within the length constraint
		if (!empty($this->description)) {
			if (strlen($this->description) > self::$MAX_DESCRIPTION_LENGTH)
				$this->setError('description', 'SENSOR_TYPE_DESCRIPTION_TOO_LONG');
		}
	}
}
?>
