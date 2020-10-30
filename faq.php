<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");
require_once('checkvt_priv/database.php');

?>
<!DOCTYPE html>
<html>
<head>
<title>checkVT FAQ</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<center>
<br>
<h2>checkVT Frequently Asked Questions</h2>
<hr style="width: 320px; border: 1px dashed white">
<br>

<ul style="display: table; text-align: left; margin: 0 auto;">
<li>{Q} What is this website for, what is checkVT?</li>
{A} Please visit the GitHub page to learn more about this project: <a href="https://github.com/ealmonte32/checkVT">https://github.com/ealmonte32/checkVT</a><p>
<li>{Q} Why do I get the VirusTotal page saying "Item not found" when submitting some URLs?</li>
{A} This means the URL you submitted has never been analyzed by Virus Total.<br>If you want to have VT analyze it directly, you can simply submit the URL by entering it on the top left of the page where it says "URL, IP Address, domain..." <p>
<li>{Q} How can I check the suspicious links that VirusTotal or checkVT cant find the final destination for?</li>
{A} This answer is a little complicated so I will try to write something up on the GitHub page if I have time..<p>
<li>{Q} Does this service collect any personal information from my computer?</li>
{A} Nope, this simply collects the original URL entered and the final destination URL for research and statistical reporting.
</ul>
<br>

<br><br><br>
<a href=".">Back</a><br>
<b>checkVT beta version 1.3</b>
</center>
</body>
</html>