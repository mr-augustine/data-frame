<?php
class DBMaker {
    public static function create($dbName) {
        $db = null;
        
        try {
            $dbspec = 'mysql:host=localhost;dbname='."".";charset=utf8";
            $username = 'dataframe';
            $password = 'dataframe';
            $options = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $db = new PDO($dbspec, $username, $password, $options);
            
            $st = $db->prepare("DROP DATABASE if EXISTS $dbName");
            $st->execute();
            
            $st = $db->prepare("CREATE DATABASE $dbName");
            $st->execute();
            
            $st = $db->prepare("USE $dbName");
            $st->execute();
            
            $st = $db->prepare("DROP TABLE if EXISTS Sensors");
            $st->execute();
            $st = $db->prepare("CREATE TABLE Sensors (
                sensorId        int(11) NOT NULL AUTO_INCREMENT,
                sensorName      varchar(255) UNIQUE NOT NULL COLLATE utf8_unicode_ci,
                description     varchar(255) COLLATE utf8_unicode_ci,
                dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (sensorId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            $st->execute();
            
            $st = $db->prepare("DROP TABLE if EXISTS Units");
            $st->execute();            
            $st = $db->prepare ("CREATE TABLE Units (
                unitId          int(11) NOT NULL AUTO_INCREMENT,
                unitName        varchar(255) UNIQUE NOT NULL COLLATE utf8_unicode_ci,
                PRIMARY KEY (unitId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            $st->execute();
            
            $st = $db->prepare("DROP TABLE if EXISTS SensorUnitAssocs");
            $st->execute();
            $st = $db->prepare("CREATE TABLE SensorUnitAssocs (
                unitAssocId     int(11) NOT NULL AUTO_INCREMENT,
                sensorId        int(11) NOT NULL,
                unitId          int(11) NOT NULL,
                PRIMARY KEY (unitAssocId),
                FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId),
                FOREIGN KEY (unitId) REFERENCES Units(unitId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            $st->execute();
            
            $st = $db->prepare("DROP TABLE if EXISTS TemperatureData");
            $st->execute();
            $st = $db->prepare("CREATE TABLE TemperatureData (
                recordId        int(11) NOT NULL AUTO_INCREMENT,
                sensorId        int(11) NOT NULL,
                measurement     int(11) NOT NULL,
                dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (recordId),
                FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            $st->execute();
        
            $st = $db->prepare("DROP TABLE if EXISTS PressureData");
            $st->execute();
            $st = $db->prepare("CREATE TABLE PressureData (
                recordId        int(11) NOT NULL AUTO_INCREMENT,
                sensorId        int(11) NOT NULL,
                measurement     int(11) NOT NULL,
                dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (recordId),
                FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
            $st->execute();
            
            $sql = "INSERT INTO Units (unitId, unitName) 
                    VALUES (:unitId, :unitName)";
            $st = $db->prepare($sql);
            $st->execute(array(':unitId' => 1, ':unitName' => 'temperature.deg_C'));
            $st->execute(array(':unitId' => 2, ':unitName' => 'temperature.deg_F'));
            $st->execute(array(':unitId' => 3, ':unitName' => 'pressure.mm_Hg'));
            $st->execute(array(':unitId' => 4, ':unitName' => 'pressure.in_Hg'));
            
            $sql = "INSERT INTO Sensors (sensorId, sensorName, description)
                    VALUES (:sensorId, :sensorName, :description)";
            $st = $db->prepare($sql);
            $st->execute(array(':sensorId' => 1, ':sensorName' => "example_temp_sensor",
                ':description' => "This sensor does not exist."));
                
            $sql = "INSERT INTO SensorUnitAssocs (unitAssocId, sensorId, unitId)
                    VALUES (:unitAssocId, :sensorId, :unitId)";
            $st = $db->prepare($sql);
            $st->execute(array(':unitAssocId' => 1, ':sensorId' => 1, ':unitId' => 2));
            $st->execute(array(':unitAssocId' => 2, ':sensorId' => 1, ':unitId' => 4));
        
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        return $db;
    }

    public static function delete($dbName) {
        try {
            $dbspec = 'mysql:host=localhost;dbname='.$dbName.";charset=utf8";
            $username = 'dataframe';
            $password = 'dataframe';
            $options = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            $db = new PDO($dbspec, $username, $password, $options);
            $st = $db->prepare("DROP DATABASE if EXISTS $dbName");
            $st->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

?>