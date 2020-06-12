
<?php
include "sessionFound.php";

$urlToReturn = "Location: mainpage.php?page=adminTechGuyList";

$techGuyID = $_GET["id"];
$techGuy;

if(isset($techGuyID))
	{
	//We contact the server to get the user data
	$context = stream_context_create(
			array(
					'http' => array(
							'method' => 'POST',
							'header' => 'Content-type: text/xml',
							'content' => '<xml><request><type>getUser</type><content><user><id>'.$techGuyID.'</id></user></content></request></xml>'))
			);

	$resp = file_get_contents($url, FALSE, $context);
	
	//Finally we open the xml content as String
	$techGuyFile = simplexml_load_string($resp);
	$techGuy = $techGuyFile->user;
	}
else
	{
	header($urlToReturn."&message=idnotfound");
	exit;
	}

?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	>
	<a href="mainpage.php?page=adminTechGuyList">Gestion des utilisateurs</a>
	> Détail d'un utilisateur
	</div>
</h3>
<hr>
<h3><div class="title">Détail de l'utilisateur : </div></h3>
<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="techGuyForm">
					<tr>
						<td>Nom : </td>
						<td></td>
						<td><?php echo $techGuy->lastname?></td>
					</tr>
					<tr>
						<td>Prénom : </td>
						<td></td>
						<td><?php echo $techGuy->firstname?></td>
					</tr>
					<tr>
						<td>Extension : </td>
						<td></td>
						<td><?php echo $techGuy->extension?></td>
					</tr>
					<tr>
						<td>Email : </td>
						<td></td>
						<td><?php echo $techGuy->email?></td>
					</tr>
					<tr>
						<td>Browser par défaut : </td>
						<td></td>
						<td><?php echo $techGuy->defaultbrowser?></td>
					</tr>
					<tr>
						<td>Options du browser : </td>
						<td></td>
						<td><?php echo $techGuy->browseroptions?></td>
					</tr>
					<tr>
						<td>Envoi d'email : </td>
						<td></td>
						<td><?php echo $techGuy->emailreminder?></td>
					</tr>
					<tr>
						<td>Résolution du nom sur le téléphone : </td>
						<td></td>
						<td><?php echo $techGuy->reverselookup?></td>
					</tr>
					<tr>
						<td>Popup Saleforce : </td>
						<td></td>
						<td><?php echo $techGuy->incomingcallpopup?></td>
					</tr>
					<tr>
						<td>Statut du client : </td>
						<td></td>
						<td>
						<?php
						if($techGuy->status == "true")
							{
							echo "<div class='connected'>Connecté</div>";
							}
						else
							{
							echo "<div class='disconnected'>Déconnecté</div>";
							}
						?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
