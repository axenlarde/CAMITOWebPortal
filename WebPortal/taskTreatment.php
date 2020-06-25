<?php
session_start(); // Création de la session

/******
 * Page used to process migration process (update/rollback/reset)
 */

include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=branchMainAdmin";

if((isset($_GET["action"])) && (($_GET["action"] == "migrate") || ($_GET["action"] == "rollback") || ($_GET["action"] == "reset") || ($_GET["action"] == "cli") || ($_GET["action"] == "survey")))
	{
	if(isset($_SESSION['cart']))
		{
		if(empty($_SESSION['cart']))
			{
			header($urlToReturn.'&message=generalerror');
			exit;
			}
		}
	else
		{
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	$content = '';
	//We build the content according to the cart content
	foreach($_SESSION['cart'] as $item)
		{
		//First we add the main item
		$content .= '<itemid>'.$item.'</itemid>
				';
		}
		
	//Then we send the newTask request
	$request = '<xml>
			<request>
				<type>newTask</type>
				<content>
					<action>'.$_GET["action"].'</action>
					<ownerid>'.$_SESSION["login"][0].'</ownerid>
					<itemlist>
						'.$content.'
					</itemlist>
				</content>
			</request>
		</xml>';
	
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = @file_get_contents($url, FALSE, $context);
	
	if($resp === false)
		{
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	$searchResult = simplexml_load_string($resp);
	
	$taskID = $searchResult->reply->content->taskid;
	if(!empty($taskID))
		{
		$_SESSION['tasks'] = array();
		array_push($_SESSION["taks"], $taskID);
		
		//It all went well so we clear the cart
		$_SESSION['cart'] = array();
		
		//We go to the showTask page to see the task status
		header("Location: mainpage.php?page=showTask&taskID=".$taskID);
		exit;
		}
	else if($searchResult->reply->type == "error")
		{
		if(strpos($searchResult->reply->content->error, 'Max concurent task reached') !== false)
			{
			header($urlToReturn.'&message=maxtaskreached');
			exit;
			}
		}
	//Something went wrong
	header($urlToReturn.'&message=generalerror');
	exit;
	}
else
	{
	if(isset($_GET['taskID']))
		{
		if(empty($_GET['taskID']))
			{
			//Something went wrong
		header($urlToReturn.'&message=generalerror');
		exit;
			}
		}
	else
		{
		//Something went wrong
		header($urlToReturn.'&message=generalerror');
		exit;
		}
		
	//We create the action request
	$request = '<xml>
		<request>
			<type>setTask</type>
			<content>
				<task>
					<taskid>'.$_GET['taskID'].'</taskid>
					<action>'.$_GET['action'].'</action>
				</task>
			</content>
		</request>
	</xml>';
	
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => $request))
			);
	
	$resp = @file_get_contents($url, FALSE, $context);
	
	if($resp === false)
		{
		header($urlToReturn.'&message=generalerror');
		exit;
		}
	
	//Finally we open the xml content as String
	$searchResult = simplexml_load_string($resp);
	
	if($searchResult->reply->type == "success")
		{
		$_SESSION["taks"] = array();
		array_push($_SESSION["taks"], $taskID);
	
		header("Location: mainpage.php?page=showTask&taskID=".$_GET['taskID']);
		exit;
		}
	else if($searchResult->reply->type == "error")
		{
		if(strpos($searchResult->reply->content->error, 'Max concurent task reached') !== false)
			{
			header($urlToReturn.'&message=maxtaskreached');
			exit;
			}
		}
	//Something went wrong
	header($urlToReturn.'&message=generalerror');
	exit;
	}

//To go back to the main page
header($urlToReturn);
exit;

?>