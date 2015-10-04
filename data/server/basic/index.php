<?php
    include("includer.php");

    $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

    $urlPieces = split("/", $url);

    if (count($urlPieces) < 2)
        $control = "none";
    else
        $control = $urlPieces[2];

    switch ($control) {
        case "home":
            HomeView::show();
            break;
        case "about":
            AboutView::show();
            break;
        default:
            HomeView::show();
    };
?>
