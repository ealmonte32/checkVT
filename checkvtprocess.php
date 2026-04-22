<?php
//connect to db
require('checkvt_priv/database.php');

date_default_timezone_set('America/New_York');

// get the input data and assign them to variables
$var_incoming_url = filter_input(INPUT_GET, 'incoming_url');
$var_timestamp = date("l F j\, Y \@ h:i:s A T"); // Prints the day(l), month(F), date(j), year(Y), time(h:i:s), AM or PM(A), timezone(T)
$vtbase_submit_url = 'https://www.virustotal.com/gui/search/'; // VirusTotal base submission URL

// We check to make sure the incoming URL is not empty
if (empty($var_incoming_url)) {
    echo "(Error): Incoming URL empty.";
    exit;
}

// on previous versions we set the user agent to curl version because testing showed HTTP2 TLS requests only worked if curl agent was set
//$user_agent = 'curl/7.54.0';
// but some phishing and malware sites are detecting automated platforms that use lib curl and redirecting the request to a non-phishing site to trick the automated tool
// by setting the user-agent as if we were using the Mozilla web browser, we try to make sure that the redirect actually sends the phishing URL in the headers
// this change has a huge improvement in defending against phishing sites that would otherwise send a generic URL to VirusTotal instead of the phishing one.
$user_agent = 'Mozilla/5.0';

// we remove and trim whitespace from the var_incoming_url using various methods
$var_incoming_url = preg_replace('/\s+/', ' ', $var_incoming_url);
$var_incoming_url = trim($var_incoming_url);

// we remove brackets since most people add them to prevent automatic links when posting websites or URLs they don't want you to visit
$var_incoming_url = str_replace(['[', ']'], '', $var_incoming_url);

// we need to decode the url before parsing it through curl
$var_incoming_url = urldecode($var_incoming_url);

// we parse the url to be able to check for an empty http/https scheme
$parse = parse_url($var_incoming_url);

// add the http protocol scheme on URLs that are missing one or else we get error
if (empty($parse['scheme'])) {
	$var_incoming_url = 'http://' . ltrim($var_incoming_url);
}

// we use the built-in curl function to process the URL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$var_incoming_url"); //provide the URL to use in the request
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //dont try to verify peer certificate
curl_setopt($ch, CURLOPT_USERAGENT, "$user_agent"); //set user agent
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); //use http or https
curl_setopt($ch, CURLOPT_ENCODING, ''); //with empty string, all supported encoding types is sent
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow redirects
curl_setopt($ch, CURLOPT_TIMEOUT, 15); //sets maximum seconds for timeout of request
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //return the transfer as a string of the return value instead of outputting it directly
curl_exec($ch);
$final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); //get the last used URL
curl_close($ch);

$final_url_nonencoded = $final_url;

// VT search path requires url encoded twice
$final_url = rawurlencode($final_url);
// second encode
$final_url = rawurlencode($final_url);

$vt_triggerscan = $vtbase_submit_url . $final_url . '%1F'; // trigger scan of full URL with added ASCII unit separator which is required to properly submit certain URLs

// Insert the URL into the database
$query = 'INSERT INTO urls_processed (URL_Orig_Address, URL_Final_Address, URL_VT_Address, URL_Processed_Date) VALUES (:incoming_url, :final_url, :vt_triggerscan, :process_timestamp)';
$statement = $db->prepare($query) or die ("Failed to prepare statement!");
$statement->bindValue(':incoming_url', $var_incoming_url);
$statement->bindValue(':final_url', $final_url_nonencoded);
$statement->bindValue(':vt_triggerscan', $vt_triggerscan);
$statement->bindValue(':process_timestamp', $var_timestamp);
$statement->execute();
$statement->closeCursor();

header("Location: $vt_triggerscan");
exit;
?>
