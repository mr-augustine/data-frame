<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Integration tests for About View</title>
</head>
<body>
<h1>About View Tests</h1>

<?php
include_once("../views/AboutView.class.php");
?>

<h2>It should call show() without crashing</h2>
<?php
AboutView::show();
?>
</body>
</html>
