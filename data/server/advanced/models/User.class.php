<?php
include_once("Messages.class.php");

class User {
	private static $MIN_PASSWORD_LENGTH = 8;
    private static $MAX_PASSWORD_LENGTH = 32;
    private static $MIN_USERNAME_LENGTH = 6;
    private static $MAX_USERNAME_LENGTH = 16;
	
	private $errorCount;
	private $errors;
	private $formInput;
	
	private $user_id;
	private $username;
	private $password;
	
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
	
	public function getUserId() {
		return $this->user_id;
	}
	
	public function setUserId($id) {
		$this->user_id = $id;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function setPassword($password) {
		$this->password = $password;
	}
	
	public function getParameters() {
		$paramArray = array('user_id' => $this->user_id,
							'username' => $this->username,
							'password' => $this->password);
		
		return $paramArray;
	}
	
	public function __toString() {
		$objectString = "[User] {id: ".$this->user_id.", username: ".
				$this->username.", password: ".$this->password."}";
		
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
			$this->user_id = "";

			$this->validateUsername();
			$this->validatePassword();
		} else {
			$this->initializeEmpty();
		}
	}
	
	private function initializeEmpty() {
		$this->errorCount = 0;
		$this->errors = array();
		$this->user_id = "";
		$this->username = "";
		$this->password = "";
	}
	
	private function validateUsername() {
		$this->username = $this->extractForm('username');
		
		// Not empty
		if (empty($this->username))
			$this->setError('username', 'USERNAME_EMPTY');
		// Meets minimum length
		else if (strlen($this->username) < self::$MIN_USERNAME_LENGTH)
			$this->setError('username', 'USERNAME_TOO_SHORT');
        // Does not exceed maximum length
        else if (strlen($this->username) > self::$MAX_USERNAME_LENGTH)
            $this->setError('username', 'USERNAME_TOO_LONG');
		// Only valid chars {letters, numbers, dashes, underscores, periods}
		else if (!filter_var($this->username, FILTER_VALIDATE_REGEXP,
				array('options' => array('regexp' => "/^([a-zA-Z0-9\-\_.])+$/i")) )) {
			$this->setError('username', 'USERNAME_INVALID_CHARS');	
		}
	}
	
	private function validatePassword() {
		$this->password = $this->extractForm('password');
		
		// Not empty
		if (empty($this->password))
			$this->setError('password', 'PASSWORD_EMPTY');
		
		// Don't continue validation if it's a hashed password (i.e., retrieved from database)
		else if (self::passwordIsHashed($this->password))
			return;
		// Meets minimum length
		else if (strlen($this->password) < self::$MIN_PASSWORD_LENGTH)
			$this->setError('password', 'PASSWORD_TOO_SHORT');
		// Does not exceed maximum length
		else if (strlen($this->password) > self::$MAX_PASSWORD_LENGTH)
			$this->setError('password', 'PASSWORD_TOO_LONG');
		// Only valid chars {letters, numbers, dashes, underscores, periods}
		else if (!filter_var($this->password, FILTER_VALIDATE_REGEXP,
				array('options' => array('regexp' => "/^([a-zA-Z0-9\-\_.!@#$%^&*<>?:;|+=])+$/i")))) {
			$this->setError('password', 'PASSWORD_INVALID_CHARS');
		}
	}
	
	private function passwordIsHashed($password) {
		$hashPrefix = '$2y$10$';
		
        // Use '===' since we're verifying a match at index '0'
		return (strpos($password, $hashPrefix) === 0);
	}
}
?>
