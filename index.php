<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");
require_once('checkvt_priv/database.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>checkVT</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
<center>
<img src="checkvt_icon-64x64.png" class="rounded-corners">
<hr style="width: 320px; border: 1px dashed white">
<div style="width:85%;">
This website hosts the "processing" page because the URL parsing process happens behind the scenes in PHP/curl functions.<br>
These functions accept the URL and perform a parsing process on it, then sends you to the VirusTotal destination.<br>
The browser extension does this automatically for you, but you can also manually enter a URL below to use this tool.
</div>
<br>
<form action="checkvtprocess.php" method="get">
<p>
Enter the URL to process:
<br><input type="text" name="incoming_url" size="40" autofocus required><br>
<br><input type="submit" class="button" value="Submit"> <input type="reset" class="button" value="Clear">
</form>
<br>
<a href="faq.php">FAQ</a><br>
<b>checkVT version 2.0.0</b>
<br><br>
</center>
</body>
</html>