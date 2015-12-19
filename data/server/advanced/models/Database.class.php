<?php
// A singleton class that maintains the database connection
class Database {
    private static $db;
    private static $dbName;
	private static $dsn = 'mysql:host=localhost;dbname=';
	private static $options = 
	   array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
	
	// Returns a reference to the database connection, if successful.
	// Throws an exception otherwise.
	public static function getDB($dbName = 'advanced_db', $configPath = null) {
		
		if (!isset (self::$db) || self::$db == null) {
			try {
				if ($configPath == null) {
			   	    $configPath = dirname(__FILE__).DIRECTORY_SEPARATOR."..". 
				             DIRECTORY_SEPARATOR. ".." . DIRECTORY_SEPARATOR.
					           ".." . DIRECTORY_SEPARATOR . "myConfig.ini";
				}
				
				$passArray = parse_ini_file($configPath);
				$username = $passArray["username"];
				$password = $passArray["password"];
				self::$dbName = $dbName;
				$dbspec = self::$dsn.self::$dbName.";charset=utf8";
				self::$db = new PDO ($dbspec, $username, $password, self::$options);
			} catch ( PDOException $e ) {
				self::$db = null;
				echo "Failed to open connection to ".self::$dbName. $e->getMessage();
			}
		}
		
		return self::$db;
	}
	
	// Clears the reference to the database connection
	public static function clearDB() {
		self::$db = null;
	}
}
?>
