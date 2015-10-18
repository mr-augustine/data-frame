<?php
class Messages {
    public static $errors = array();
    public static $locale = "English";

    public static function setErrors($filename) {
        $errorsArray = array();

        // TODO verify that the file exists first!
        foreach (file($filename) as $line) {
            list($key, $value) = explode(' ', $line, 2) + array(NULL, NULL);

            if ($value !== NULL)
                $errorsArray[$key] = $value;
        }

        self::$errors = $errorsArray;
    }

    public static function getError($errorName) {
        if (array_key_exists($errorName, self::$errors))
            return self::$errors[$errorName];
        else
            return "";
    }

    public static function reset() {
        $pathDir = dirname(__FILE__);
        $fileName = $pathDir . DIRECTORY_SEPARATOR . "../resources/errors_" . self::$locale . ".txt";
    }
}
?>
