
<script type="text/javascript">

function action(type, taskID)
	{
	window.location = "mainpage.php?page=taskTreatment&action="+type+"&taskID="+taskID;
	}

setTimeout(function(){window.location.reload(1);}, 5000);

</script>

<?php
include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=branchMainAdmin";

$taskID = @$_GET["taskID"];
$task;

//We contact the server to get the task data
$request = '<xml>
			<request>
				<type>getTask</type>
				<content>
					<taskid>'.$taskID.'</taskid>
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
	header($urlToReturn.'&message=unknowntaskid');
	exit;
	}

//Finally we open the xml content as String
$searchResult = simplexml_load_string($resp);
$task = $searchResult->reply->content->task;

if(empty($task->itemlist->item))
	{
	header($urlToReturn.'&message=unknowntaskid');
	exit;
	}

function getVerboseStatus($status)
	{
	if($status == "preaudit") return '<div class="forwarddate">Audit préparatoire</div>';
	else if($status == "update") return '<div class="forwarddate">Migration</div>';
	else if($status == "rollback") return '<div class="forwarddate">Retour arrière</div>';
	else if($status == "reset") return '<div class="forwarddate">Reset</div>';
	else if($status == "postaudit") return '<div class="forwardstatusok">Audit post opération</div>';
	else if($status == "done") return '<div class="forwardstatusok">Terminé</div>';
	else if($status == "error") return '<div class="forwardstatusnok">Erreur</div>';
	return '<div class="forwarddate">'.$status.'</div>';
	}
	
function getVerboseDesc($desc)
	{
	if($desc == "Reachable : true") return '<div class="forwardstatusok">Ping OK</div>';
	else if($desc == "Reachable : false") return '<div class="forwardstatusnok">Ping KO</div>';
	else if($desc == "Reachable : unknown") return '<div class="forwarddate">Ping en attente</div>';
	else if($desc == "Reachable : true, Error found") return '<div class="forwardstatusnok">Ping OK, Erreur</div>';
	else if($desc == "Reachable : false, Error found") return '<div class="forwardstatusnok">Ping KO, Erreur</div>';
	else if($desc == "Reachable : unknown, Error found") return '<div class="forwardstatusnok">Ping en attente, Erreur</div>';
	return '<div class="forwarddate">'.$desc.'</div>';
	}

function isOffice($itemType, $info)
	{
	if($itemType == "office") return '<div class="officestyle">'.$info.'</div>';
	return '<div class="forwarddate">'.$info.'</div>';
	}
	
function startButtonEnable($status)
	{
	if($status == "preaudit") return '';
	else return 'disabled';
	}
	
function pauseButtonEnable($status)
	{
	if($status == "preaudit") return 'disabled';
	else if($status == "postaudit") return 'disabled';
	else if($status == "done") return 'disabled';
	else if($status == "error") return 'disabled';
	else return '';
	}
	
function stopButtonEnable($status)
	{
	if($status == "done") return 'disabled';
	else if($status == "error") return 'disabled';
	else return '';
	}
	
function startButtonCSS($status)
	{
	if($status == "preaudit") return '<div class="forwardaction">';
	else return '<div class="forwardactiondisable">';
	}
	
function pauseButtonCSS($status)
	{
	if($status == "preaudit") return '<div class="forwardactiondisable">';
	else if($status == "postaudit") return '<div class="forwardactiondisable">';
	else if($status == "done") return '<div class="forwardactiondisable">';
	else if($status == "error") return '<div class="forwardactiondisable">';
	else return '<div class="forwardaction">';
	}
	
function stopButtonCSS($status)
	{
	if($status == "done") return '<div class="forwardactiondisable">';
	else if($status == "error") return '<div class="forwardactiondisable">';
	else return '<div class="forwardaction">';
	}
?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	> Etat de la tâche
	</div>
</h3>
<hr>
<h3><div class="title">Détail de la tâche en cours : </div></h3>
<h4>Rappel : Appuyez sur START pour démarrer la tâche et STOP pour l'arrêter</h4>
<table>
	<tr>
		<td><?php echo startButtonCSS($task->overallstatus); ?><input type="button" name="Start" value="Start" title="start" onclick="action('start','<?php echo $task->id?>')" <?php echo startButtonEnable($task->overallstatus); ?>></div></td>
		<td><?php echo stopButtonCSS($task->overallstatus); ?><input type="button" name="Stop" value="Stop" title="Stop" onclick="action('stop','<?php echo $task->id?>')" <?php echo stopButtonEnable($task->overallstatus); ?>></div></td>
		<td><?php echo pauseButtonCSS($task->overallstatus); ?><input type="button" name="Pause" value="Pause" title="Pause" onclick="action('pause','<?php echo $task->id?>')" <?php echo pauseButtonEnable($task->overallstatus); ?>></div></td>
	</tr>
	<tr>
		<td>Statut de la tâche : <?php echo getVerboseStatus($task->overallstatus); ?></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>Liste des éléments : </td>
		<td></td>
		<td></td>
	</tr>
</table>
<div class="forwardlist">
	<table>
		<tr>
			<td>Information</td>
			<td>Action en cours</td>
			<td>Description</td>
		</tr>
		<?php
		foreach($task->itemlist->item as $item)
			{
			echo '<tr>
					<td>'.isOffice($item->type,$item->info).'</td>
					<td>'.getVerboseStatus($item->status).'</td>
					<td>'.getVerboseDesc($item->desc).'</td>
				</tr>
				';
			}
		?>
	</table>
</div>
