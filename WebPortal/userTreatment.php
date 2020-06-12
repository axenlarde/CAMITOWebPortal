<?php
session_start(); // Création de la session

/******
 * Page used to process techGuy changes (Add/Delete/Update)
 */

include "sessionFound.php";
include "importUser.php";

$urlToReturn = "Location: mainpage.php?page=adminUsers";

if((isset($_GET["action"])) && ($_GET["action"] == "add"))
	{
	/**
	 * We add the new entry
	 */
	//We get the posted value
	$userID = $_POST["userID"];
	$lastName = $_POST["lastName"];
	$firstName = $_POST["firstName"];
	$groupID = $_POST["group"];
	$password = $_POST["password"];
	$password = md5($password);
	
	//We check that the user doesn't exist
	foreach($usersFile->user as $User)
		{
		if($User->userid->__toString() == $userID)
			{
			header($urlToReturn."&message=duplicateid");
			exit;
			}
		}
	
	//We get the group name
	$group = $groupsFile->group[intval($groupID)];
		
	$NewUserEntry = $usersFile->addChild("user");
	$NewUserEntry->addChild("userid",$userID);
	$NewUserEntry->addChild("lastname",$lastName);
	$NewUserEntry->addChild("firstname",$firstName);
	$NewUserEntry->addChild("accessgroup",$group->name);
	$NewUserEntry->addChild("password",$password);
	
	$NewDoc = new DOMDocument();
	$NewDoc->preserveWhiteSpace = false;
	$NewDoc->formatOutput = true;
	
	$NewDoc->loadXML($usersFile->asXML());
	$NewDoc->save("document/".$usersFileName);
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "delete"))
	{
	if((isset($_GET["index"])) && ($_GET["index"] != ""))
		{
		$user = $usersFile->user[intval($_GET["index"])];
		
		$dom = dom_import_simplexml($user);
		$dom->parentNode->removeChild($dom);
		
		$NewDoc = new DOMDocument();
		$NewDoc->preserveWhiteSpace = false;
		$NewDoc->formatOutput = true;
		
		$NewDoc->loadXML($usersFile->asXML());
		$NewDoc->save("document/".$usersFileName);
		}
	else
		{
		header($urlToReturn."&message=generalerror");
		exit;
		}
	}
else if((isset($_GET["action"])) && ($_GET["action"] == "update"))
	{
	/**
	 * Has to be writtent if needed
	 */
	}

//To go back to the global paramaters administration page
header($urlToReturn);
exit;

?>