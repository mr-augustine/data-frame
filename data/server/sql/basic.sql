DROP DATABASE if EXISTS basic;
CREATE DATABASE basic;
USE basic;

DROP TABLE if EXISTS Sensors;
CREATE TABLE Sensors (
    sensorId        int(11) NOT NULL AUTO_INCREMENT,
    sensorName      varchar(255) UNIQUE NOT NULL COLLATE utf8_unicode_ci,
    dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (sensorId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE if EXISTS Units;
CREATE TABLE Units (
    unitId          int(11) NOT NULL AUTO_INCREMENT,
    unitName        varchar(255) UNIQUE NOT NULL COLLATE utf8_unicode_ci,
    PRIMARY KEY (unitId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE if EXISTS SensorUnitAssocs;
CREATE TABLE SensorUnitAssocs (
    unitAssocId     int(11) NOT NULL AUTO_INCREMENT,
    sensorId        int(11) NOT NULL,
    unitId          int(11) NOT NULL,
    PRIMARY KEY (unitAssocId),
    FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId),
    FOREIGN KEY (unitId) REFERENCES Units(unitId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE if EXISTS TemperatureData;
CREATE TABLE TemperatureData (
    recordId        int(11) NOT NULL AUTO_INCREMENT,
    sensorId        int(11) NOT NULL,
    measurement     int(11) NOT NULL,
    dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (recordId),
    FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE if EXISTS PressureData;
CREATE TABLE PressureData (
    recordId        int(11) NOT NULL AUTO_INCREMENT,
    sensorId        int(11) NOT NULL,
    measurement     int(11) NOT NULL,
    dateAdded       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (recordId),
    FOREIGN KEY (sensorId) REFERENCES Sensors(sensorId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO Units (unitId, unitName) VALUES (1, "temperature.deg_C");
INSERT INTO Units (unitId, unitName) VALUES (2, "temperature.deg_F");
INSERT INTO Units (unitId, unitName) VALUES (3, "pressure.mm_Hg");
INSERT INTO Units (unitId, unitName) VALUES (4, "pressure.in_Hg");

INSERT INTO Sensors (sensorId, sensorName) VALUES (1, "example_temp_sensor");

INSERT INTO SensorUnitAssocs (unitAssocId, sensorId, unitId) VALUES (1, 1, 2);
INSERT INTO SensorUnitAssocs (unitAssocId, sensorId, unitId) VALUES (2, 1, 4);
