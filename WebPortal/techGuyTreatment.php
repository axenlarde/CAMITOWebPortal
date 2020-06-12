<?php
session_start(); // Création de la session

/******
 * Page used to process techGuy changes (Add/Delete/Update)
 */

include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=adminTechGuyList";

if((isset($_GET["action"])) && ($_GET["action"] == "add"))
	{
	/**
	 * We add the new entry
	 */
	/*$firstname = $_POST["firstname"];
	$lastname = $_POST["lastname"];
	$extension = $_POST["extension"];
	$email = $_POST["email"];
	$cucmid = $_GET["cucmid"];
	$salesforceid = $_GET["salesforceid"];
	$defaultbrowser = $_POST["defaultbrowser"];
	$incomingcallpopup = $_POST["incomingcallpopup"];
	$reverselookup = $_POST["reverselookup"];
	$emailreminder = $_POST["emailreminder"];
	
	//We prepare the update request
	$request = "<xml>
					<request>
						<type>addUser</type>
						<content>
							<user>
								<firstname>".$firstname."</firstname>
								<lastname>".$lastname."</lastname>
								<extension>".$extension."</extension>
								<email>".$email."</email>
								<cucmid>".$cucmid."</cucmid>
								<salesforceid>".$salesforceid."</salesforceid>
								<defaultbrowser>".$defaultbrowser."</defaultbrowser>
								<incomingcallpopup>".$incomingcallpopup."</incomingcallpopup>
								<reverselookup>".$reverselookup."</reverselookup>
								<emailreminder>".$emailreminder."</emailreminder>
							</user>
						</content>
					</request>
				</xml>";*/
		
	$salesforceid = $_GET["id"];
	$extension = $_GET["extension"];
	
	$request = "<xml>
					<request>
						<type>addUser</type>
						<content>
							<user>
								<salesforceid>".$salesforceid."</salesforceid>
								<extension>".$extension."</extension>
							</user>
						</content>
					</request>
				</xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "delete"))
	{
	/**
	 * We delete the user
	 */
	$id = $_GET["id"];
	
	//We prepare the delete request
	$request = "<xml><request><type>deleteUser</type><content><user><id>".$id."</id></user></content></request></xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "update"))
	{
	/**
	 * We update the user
	 */
	$id = $_GET["id"];
	$extension = $_POST["extension"];
	$defaultbrowser = $_POST["defaultbrowser"];
	$browseroptions = $_POST["browseroptions"];
	$incomingcallpopup = $_POST["incomingcallpopup"];
	$reverselookup = $_POST["reverselookup"];
	$emailreminder = $_POST["emailreminder"];
	
	$incomingcallpopup = ($incomingcallpopup == "on")?"true":"false";
	$reverselookup = ($reverselookup == "on")?"true":"false";
	$emailreminder = ($emailreminder == "on")?"true":"false";
	
	//We prepare the update request
	$request = "<xml><request><type>updateUser</type><content><user><id>".$id."</id><extension>".$extension."</extension><defaultbrowser>".$defaultbrowser."</defaultbrowser><browseroptions>".$browseroptions."</browseroptions><incomingcallpopup>".$incomingcallpopup."</incomingcallpopup><reverselookup>".$reverselookup."</reverselookup><emailreminder>".$emailreminder."</emailreminder></user></content></request></xml>";
	
	//Then we send it
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = file_get_contents($url, FALSE, $context);
	}

//To go back to the global paramaters administration page
header($urlToReturn);
exit;

?>