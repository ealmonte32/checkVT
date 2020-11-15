<?php
//connect to db
require_once('checkvt_priv/database.php');

// get the input data and assign them to variables
$var_incoming_url = filter_input(INPUT_GET, 'incoming_url');
$var_timestamp = gmdate("l F j\, Y \@ h:i:s A T"); // Prints the day(l), month(F), date(j), year(Y), time(h:i:s), AM or PM(A), timezone(T)
$vtbase_url = 'https://www.virustotal.com/gui/url/'; // VirusTotal base URL

// we set the user agent to the current users device
//$user_agent = $_SERVER['HTTP_USER_AGENT'];
$user_agent = 'curl/7.54.0'; //testing purposes

// We check to make sure the incoming URL is not empty
if (empty($var_incoming_url)) {
    echo "(Error): Incoming URL empty.";
    exit;
}

// we remove and trim whitespace from the var_incoming_url using various methods
$var_incoming_url = preg_replace('/\s+/', ' ', $var_incoming_url);
$var_incoming_url = trim($var_incoming_url);

// we decode the url
$var_incoming_url = urldecode($var_incoming_url);

// we replace any spaces in the path with + character
$var_incoming_url = str_replace(' ', '+', $var_incoming_url);

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
    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent); //set user agent automatically to current user one
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); //use http or https
    curl_setopt($ch, CURLOPT_ENCODING, ""); //with empty string, all supported encoding types is sent
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow redirects
    curl_setopt($ch, CURLOPT_TIMEOUT, 15); //sets maximum of 15 seconds for timeout of request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); //return the transfer as a string of the return value instead of outputting it directly
    curl_exec($ch);
    $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); //get the last used URL
    curl_close($ch);

    // we parse the URL to only take the domain host part because there are more results of analyzed host URLs in Virus Total
    // than with full URLs with random and infinitely changing paths which give item-not-found errors
    $parse = parse_url($final_url); // we parse the effective final URL and only use the scheme and host
    $final_url = $parse['scheme'] . '://' . $parse['host'] . '/'; //we need ending slash for hashed URL

    $vtfinal_url = $vtbase_url . hash('sha256', $final_url) . '/details'; // we set to details tab to show user some helpful info

    // Insert the URL into the database
    $query = 'INSERT INTO urls_processed (URL_Orig_Address, URL_Final_Address, URL_VT_Address, URL_Processed_Date) VALUES (:incoming_url, :final_url, :vtfinal_url, :process_timestamp)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':incoming_url', $var_incoming_url);
    $statement->bindValue(':final_url', $final_url);
    $statement->bindValue(':vtfinal_url', $vtfinal_url);
    $statement->bindValue(':process_timestamp', $var_timestamp);
    $statement->execute();
    $statement->closeCursor();

    header("Location: $vtfinal_url");
    exit;
?>
