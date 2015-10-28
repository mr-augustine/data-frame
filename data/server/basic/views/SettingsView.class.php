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
        echo '<a href="/'.$base.'/sensor/show/all">Sensors</a><br>';
        echo '<br>';
        echo '<a href="/'.$base.'/display/configure">Display</a><br>';
    }
}
?>
