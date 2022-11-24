<?php
//connect to db
require('checkvt_priv/database.php');

// get the input data and assign them to variables
$var_incoming_url = filter_input(INPUT_GET, 'incoming_url');
$var_timestamp = gmdate("l F j\, Y \@ h:i:s A T"); // Prints the day(l), month(F), date(j), year(Y), time(h:i:s), AM or PM(A), timezone(T)
$vtbase_submit_url = 'https://www.virustotal.com/gui/search/'; // VirusTotal base submission URL

// We check to make sure the incoming URL is not empty
if (empty($var_incoming_url)) {
    echo "(Error): Incoming URL empty.";
    exit;
}

// we set the user agent to curl version (testing showed HTTP2 TLS requests only worked if curl agent was set)
$user_agent = 'curl/7.54.0';

// we remove and trim whitespace from the var_incoming_url using various methods
$var_incoming_url = preg_replace('/\s+/', ' ', $var_incoming_url);
$var_incoming_url = trim($var_incoming_url);

// we decode the url
$var_incoming_url = urldecode($var_incoming_url);

// just in case we decode the url again
$var_incoming_url = urldecode($var_incoming_url);

// we parse the url to be able to check for an empty http/https scheme
$parse = parse_url($var_incoming_url);

// add the http protocol scheme on URLs that are missing one or else we get error
if (empty($parse['scheme'])) {
	$var_incoming_url = 'http://' . ltrim($var_incoming_url);
}

// we use the built-in curl function to process the URL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $var_incoming_url); //provide the URL to use in the request
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); //set user agent
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); //use http or https
curl_setopt($ch, CURLOPT_ENCODING, ''); //with empty string, all supported encoding types is sent
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow redirects
curl_setopt($ch, CURLOPT_TIMEOUT, 10); //sets maximum of 10 seconds for timeout of request
curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); //return the transfer as a string of the return value instead of outputting it directly
curl_exec($ch);
$final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); //get the last used URL
curl_close($ch);

// we perform some manual encoding for '://'
$final_url = preg_replace('/:\/\//', '%253A%252F%252F', $final_url);

// we perform another encoding for the rest of the '/'
$final_url = preg_replace('/\//', '%252F', $final_url);

$vt_triggerscan = $vtbase_submit_url . $final_url; // trigger scan of full URL

// Insert the URL into the database
$query = 'INSERT INTO urls_processed (URL_Orig_Address, URL_Final_Address, URL_VT_Address, URL_Processed_Date) VALUES (:incoming_url, :final_url, :vt_triggerscan, :process_timestamp)';
$statement = $db->prepare($query) or die ("Failed to prepare statement!");
$statement->bindValue(':incoming_url', $var_incoming_url);
$statement->bindValue(':final_url', $final_url);
$statement->bindValue(':vt_triggerscan', $vt_triggerscan);
$statement->bindValue(':process_timestamp', $var_timestamp);
$statement->execute();
$statement->closeCursor();

header("Location: $vt_triggerscan");
exit;
?>
