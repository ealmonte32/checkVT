<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");
require_once('checkvt_priv/database.php');

?>
<!DOCTYPE html>
<html>
<head>
<title>checkVT process</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<center>
<br>
<h2>checkVT processing...</h2>
<hr style="width: 320px; border: 1px dashed white">
<br>
<div style="width:85%;">
This page is used as the "processing" page because the whole process happens in a php/curl function.<br>
We have this function accept the URL and performs a parsing process on it, then sends you to the final destination URL.
</div>
<br>
<form action="checkvtprocess.php" method="get">
<p>
Enter the URL to process:
<br><input type="text" name="incoming_url" size="40" autofocus required><br>
<br><input type="submit" value="Submit"> <input type="reset" value="Clear">
</form>

<br><br><br>
<a href="faq.php">FAQ</a><br>
<b>checkVT beta version 1.3</b>
</center>
</body>
</html>