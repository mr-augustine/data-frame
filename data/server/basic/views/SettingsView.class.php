<?php

class SettingsView {

    public static function show() {
        $_SESSION['headertitle'] = "DataFrame | Settings";
        MasterView::showHeader();
        SettingsView::showDetails();
        MasterView::showFooter();
    }

    public static function showDetails() {
        $base = (array_key_exists('base', $_SESSION)) ? $_SESSION['base'] : "";
        
        echo '<h1>This is the DataFrame Settings Page!</h1>';
        echo '<h2>Sensors</h2>';
        echo '<form style="display: inline" action="settings_add_sensor" method="get">';
        echo '<button>Add a Sensor</button>';
        echo '<br><br>';
        echo '</form>';
        echo '<br>';
        
        // Display a list of all of the sensors
        $sensors = SensorsDB::getSensorsBy();
        
        foreach ($sensors as $sensor) {
            echo '<a href="settings_edit_'.$sensor->getName().'">'.$sensor->getName().'</a><br>';
        }
    }
}
?>
