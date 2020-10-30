<?php
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1");
header("X-Content-Type-Options: nosniff");

//user functions
session_cache_limiter('nocache'); //do not use on production internet facing website
session_start();
require_once('database.php');
require_once('error_handling.php');

//login button action
if (isset($_POST['login_button'])) {
	$login_email = filter_input(INPUT_POST, 'login_email', FILTER_SANITIZE_EMAIL);
	$login_password = filter_input(INPUT_POST, 'login_password');
	$hashed_login_password = sha1(sha1($login_password)); // hashing the hash is a key streching technique
	$errors = array();
	login();
}

//the login function
function login() {
	global $db, $login_email, $hashed_login_password, $errors;

	// make sure form is filled properly
	if (empty($login_email)) {
		array_push($errors, "Log error: Email is required");
		echo "Error: Email is required.<br>";
	}
	if (empty($hashed_login_password)) {
		array_push($errors, "Log error: Password is required");
		echo "Error: Password is required.<br>";
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {

		// run the query on the customer table
		$login_check = $db->query("SELECT * FROM almontee3_CUSTOMERS WHERE Email='$login_email' AND Password='$hashed_login_password'");
		$login_results = $login_check->fetchAll(PDO::FETCH_ASSOC);

		if (count($login_results) == 1) { // if a user with entered email and password match is found

			//return the array results
			foreach ($login_results as $user){
				if ($user['UserType'] == 'customer') {
					$_SESSION['user'] = $user;
					$_SESSION['fname'] = $user['FirstName'];
					$_SESSION['lname'] = $user['LastName'];
					$_SESSION['customerid'] = $user['CustomerID'];
					$_SESSION['email'] = $user['Email'];
					$_SESSION['ship_address'] = $user['Address'];
                    $_SESSION['ship_city'] = $user['City'];
                    $_SESSION['ship_state'] = $user['State'];
                    $_SESSION['ship_zip'] = $user['Zip'];
					$_SESSION['vehiclemodel'] = $user['VehicleModel'];
					$_SESSION['vehicleyear'] = $user['VehicleYear'];
					$_SESSION['telephone'] = $user['Telephone'];
					$_SESSION['dob'] = $user['DOB'];
					header("location: customer_dashboard.php");
					exit;
				}
			}

		} // if no user is found or password doesnt match
		else {
			echo "<center><h4>(Error): User Not Found or Incorrect Password.</h4></center>";
		}
	}
}

?>