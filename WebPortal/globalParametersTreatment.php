<?php
session_start(); // Création de la session

/******
 * Page used to treat a new parameters value
 *
 */

include "sessionFound.php";

$newValue = false;
$filename = "document/xmlFiles/OverallSettings.xml";
$xml=simplexml_load_file($filename) or die("Error");

if(isset($_POST['maxwaitingtime']))
	{
	$maxwaitingtime = $_POST['maxwaitingtime'];
	$xml->misc->agenttimeout = $maxwaitingtime;
	$newValue = true;
	}
	
if($newValue)
	{
	$xml->asXML($filename);
	}

//To go back to the global paramaters administration page
header("Location: mainpage.php?page=adminGlobalParameters");
exit;

?>