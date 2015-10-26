<?php

class SensorAddEditView {

    public static function show() {
        $_SESSION['headertitle'] = "DataFrame | Add/Edit Sensor";
        MasterView::showHeader();
        SensorAddEditView::showDetails();
        MasterView::showFooter();
    }
    
    public static function showDetails() {
        $base = (array_key_exists('base', $_SESSION)) ? $_SESSION['base'] : "";
        $edit = null;
        
        if (array_key_exists('editSensor', $_SESSION) && !is_null($_SESSION['editSensor']))
            $edit = $_SESSION['editSensor'];
        
        echo '<h1>Add A Sensor</h1>';
        
        echo '<form action="addEdit_sensor" method="post">'."\n";
        echo 'Name:   <input type="text" name="name"';
            if (!is_null($edit)) { echo 'value="'.$edit->getName().'"'; }
        echo 'tabindex="1" required>'."\n";
        echo '<span class="error">'."\n";
            if (!is_null($edit)) { echo $edit->getError('name'); }
        echo '</span><br><br>'."\n";
        
        if (!is_null($edit)) {
            $units = $edit->getUnits();
            
            foreach ($units as $unit) {
                //SensorAddEditView::displayPreselectedUnit($unit);
            }
            
        } else {
            //SensorAddEditView::displayPreselectedUnit("");
        }
        
        echo 'Description:';
        echo '<br>';
        echo '<textarea name="description" maxlength="255" rows="2" cols="80">';
        echo '</textarea>';
    }
    
    public static function displayPreselectedUnit($unit) {
    
    }
}
?>