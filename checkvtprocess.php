<?php
//connect to db
require_once('checkvt_priv/database.php');

// Get the inquiry data and assign them to variables
$var_incoming_url = filter_input(INPUT_GET, 'incoming_url');
$var_timestamp = gmdate("l F j\, Y \@ h:i:s A T"); // Prints the day(l), month(F), date(j), year(Y), time(h:i:s), AM or PM(A), timezone(T)
$vtbase_url = 'https://www.virustotal.com/gui/url/'; // VirusTotal base URL
$vtbase_url_minimal = 'https://www.virustotal.com/old-browsers/url/'; //VirusTotal base URL minimal interface edition

// We check to make sure the incoming URL is not empty
if (empty($var_incoming_url)) {
    echo "(Error): Incoming URL empty.";
    exit;
}

// We use the parse_url function to add the http protocol scheme on URLs that were entered manually or else we get error
$parse = parse_url($var_incoming_url);
if (empty($parse['scheme'])) {
	$var_incoming_url = 'http://' . ltrim($var_incoming_url, '/');
}


    //echo "<script>alert('debug incoming url $var_incoming_url');</script>";
    // We use the built-in curl function to take the incoming URL, use curl to make the HTTP POST request for us
    // then we allow it to use any protocol scheme (http or https), then we only want it to return the HEAD not body
    // then we follow all redirects, then we get the value of the URL as a string and not output it directly
    // finally we take that string value URL and we use with the curl effective URL function
    // the effective URL function is used to get the final URL the user would have been redirected to
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $var_incoming_url); //provide the URL to use in the request
    //curl_setopt($ch, CURLOPT_POST,1); //request an HTTP POST
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); //make post request specific for all subsequent requests
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); //use http or https
    curl_setopt($ch, CURLOPT_NOBODY, true); //do the download request without getting the body
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); //follow redirects
    curl_setopt($ch, CURLOPT_POSTREDIR, 3); //follow redirect with the same type of request both for 301 and 302 redirects.
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); //sets maximum of 10 seconds for timeout of request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true); //return the transfer as a string of the return value instead of outputting it directly
    curl_exec($ch);
    $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL); //get the last used URL
    curl_close($ch);

    //echo "<script>alert('debug: finalurl after curl: $final_url');</script>"; 
    //$vtfinal_url = $vtbase_url . hash('sha256', $final_url) . '/detection';
    $vtfinal_url = $vtbase_url_minimal . hash('sha256', $final_url);
    //$vtfinal_url_analysis = 'https://www.virustotal.com/gui/url-analysis/u-' . hash('sha256', $final_url) . '-' . time() . '/';

    //echo "<script>alert('debug: vtfinal url: $vtfinal_url');</script>";
    //echo "<script>alert('debug: vtfinal analysis: $vtfinal_url_analysis');</script>"; 

	// Insert the URL into the database
	$query = 'INSERT INTO urls_processed (URL_Orig_Address, URL_Final_Address, URL_VT_Address, URL_Processed_Date) VALUES (:incoming_url, :final_url, :vtfinal_url, :process_timestamp)';
    $statement = $db->prepare($query) or die ("Failed to prepare statement!");
    $statement->bindValue(':incoming_url', $var_incoming_url);
    $statement->bindValue(':final_url', $final_url);
    $statement->bindValue(':vtfinal_url', $vtfinal_url);
    $statement->bindValue(':process_timestamp', $var_timestamp);
    $statement->execute();
    $statement->closeCursor();

    //echo "<script>alert('debug: $vtfinal_url $vtbase_url');</script>";
    //echo "<script>alert('the final destination URL was found to be: $final_url');window.location.replace('$vtfinal_url');</script>";
    //header("Location: $vtfinal_url_analysis");
    header("Location: $vtfinal_url");
    exit;

?>