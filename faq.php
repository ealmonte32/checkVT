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
<title>checkVT FAQ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
</head>
<body>
<center>
<h2>checkVT Frequently Asked Questions</h2>
<hr style="width: 320px; border: 1px dashed white">
<br>

<ul style="display: table; text-align: left; margin: 0 auto;">
<li>{Q} What is this website for, what is checkVT?</li>
{A} Please visit the GitHub page to learn more about this project: <a href="https://github.com/ealmonte32/checkVT">https://github.com/ealmonte32/checkVT</a><p>
<li>{Q} Why do I get the VirusTotal page saying "Item not found" when submitting some URLs?</li>
{A} This means the URL you submitted has never been analyzed by VirusTotal.<br>If you want to have VT analyze it, you can simply submit the URL by entering it on the top left of the page where it says "URL, IP Address, domain..." <p>
<li>{Q} How can I check the suspicious links that VirusTotal or checkVT cant find the final destination for?</li>
{A} This answer is a little complicated so I will try to write something up on the GitHub page if I have time..<p>
<li>{Q} Does this website/tool collect any personal information from my computer?</li>
{A} Nope, this simply collects the original URL you entered and the final destination URL for research purposes and statistical reporting.
</ul>
<br><br><br>
<a href=".">Back</a><br>
<b>checkVT version 1.0.3</b>
<br>
</center>
</body>
</html>