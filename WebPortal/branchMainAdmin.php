<?php
/******
 * Page used to display the main admin menu
 */

include "sessionFound.php";

//We contact the server to get the task data
$request = '<xml>
				<request>
					<type>getTask</type>
					<content>
						<taskid></taskid>
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

$taskInProgress = true;

if($resp === false)
	{
	$taskInProgress = false;
	}
else
	{
	$searchResult = simplexml_load_string($resp);
	$task = $searchResult->reply->content->task;
	
	if(empty($task->itemlist->item))
		{
		$taskInProgress = false;
		}
	}

?>

<table class="mainmenu">
	<tr><td><a href="mainpage.php?page=newTask">Nouvelle tâche</a></td></tr>
	<?php
	if($taskInProgress)
		{
		echo '<tr><td><a href="mainpage.php?page=showTask">Afficher la tâche en cours</a></td></tr>';
		}
	?>
	<tr><td><a href="mainpage.php?page=displayLog">Afficher les logs</a></td></tr>
	<?php
	
	//If the user is an admin we display the "user admin menu"
	$group = $_SESSION['login'][3];
	
	if($group == "Admin")
		{
		//This user is an admin
		echo '
			<tr>
				<td><a href="mainpage.php?page=adminUsers">Gestion des administrateurs</a></td>
			</tr>
			';
		}
	?>
</table>


