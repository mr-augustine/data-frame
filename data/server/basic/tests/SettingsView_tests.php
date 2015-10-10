<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Integration tests for Settings View</title>
</head>
<body>
<h1>Settings View Tests</h1>

<?php
include_once("../views/SettingsView.class.php");
?>

<h2>It should call show() without crashing</h2>
<?php
SettingsView::show();
?>
</body>
</html>
