<?php

class HomeView {

    public static function show() {
        $_SESSION['headertitle'] = "DataFrame | Home";
        MasterView::showHeader();
        HomeView::showDetails();
        MasterView::showFooter();
    }
    
    public function showDetails() {
        $base = (array_key_exists('base', $_SESSION)) ? $_SESSION['base'] : "";
        
        echo '<h1>This is the DataFrame Home Page!</h1>';
        echo '<section>';
        echo '<a href="settings">Settings</a>';
        echo '</section>';
    }
}
?>