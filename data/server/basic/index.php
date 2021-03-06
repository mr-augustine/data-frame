<?php
    include("includer.php");

    $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    // Parse the url into Control, Action, and Arguments
    list($fill, $base, $ctrl_act_args) =
        explode('/', $url, 3) + array("", "", "");
    list($control, $action, $arguments) =
        explode('/', $ctrl_act_args, 3) + array("", "", "");

    $_SESSION['base'] = $base;
    $_SESSION['control'] = $control;
    $_SESSION['action'] = $action;
    $_SESSION['arguments'] = $arguments;
    
    switch ($control) {
        case "about":
            AboutView::show();
            break;
        case "home":
            HomeView::show();
            break;
        case "settings":
            SettingsView::show();
            break;
        case "sensor":
            SensorController::run();
            break;
        default:
            HomeView::show();
    };
?>
