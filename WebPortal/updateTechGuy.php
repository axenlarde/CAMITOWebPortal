<script type="text/javascript">

function checkNewInput(form)
	{
	var extension = document.getElementById("extension").value;
	
	if(!/^\d{4}$/.test(extension))
		{
		alert("Le numéro est incorrect");
		}
	else
		{
		form.submit();
		}
	}

</script>

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
	>Edition d'un utilisateur
	</div>
</h3>
<br>
<hr>
<h3><div class="title">Edition de l'utilisateur : </div></h3>
<form name="NewTechGuyForm" id="NewTechGuyForm" method=post action="techGuyTreatment.php?action=update&id=<?php echo $techGuyID?>">
	<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="techGuyForm">
					<tr>
						<td>Nom : </td>
						<td></td>
						<td><input type="text" readonly="readonly" name="lastName" id="lastName" value="<?php echo $techGuy->lastname?>"></td>
					</tr>
					<tr>
						<td>Prénom : </td>
						<td></td>
						<td><input type="text" readonly="readonly" name="firstName" id="firstName" value="<?php echo $techGuy->firstname?>"></td>
					</tr>
					<tr>
						<td>Extension : </td>
						<td></td>
						<td><input type="text" name="extension" id="extension" value="<?php echo $techGuy->extension?>"></td>
					</tr>
					<tr>
						<td>Email : </td>
						<td></td>
						<td><input type="text" readonly="readonly" name="email" id="email" value="<?php echo $techGuy->email?>"></td>
					</tr>
					<tr>
						<td>Browser par défaut : </td>
						<td></td>
						<td><input type="text" name="defaultbrowser" id="defaultbrowser" value="<?php echo $techGuy->defaultbrowser?>"></td>
					</tr>
					<tr>
						<td>Options du browser : </td>
						<td></td>
						<td><input type="text" name="browseroptions" id="browseroptions" value="<?php echo $techGuy->browseroptions?>"></td>
					</tr>
					<tr>
						<td>Envoi d'email : </td>
						<td></td>
						<td><input type="checkbox" name="emailreminder" id="emailreminder" <?php if($techGuy->emailreminder == "true")echo 'checked'?>></td>
					</tr>
					<tr>
						<td>Résolution du nom sur le téléphone : </td>
						<td></td>
						<td><input type="checkbox" name="reverselookup" id="reverselookup" <?php if($techGuy->reverselookup == "true")echo 'checked'?>></td>
					</tr>
					<tr>
						<td>Popup Saleforce : </td>
						<td></td>
						<td><input type="checkbox" name="incomingcallpopup" id="incomingcallpopup" <?php if($techGuy->incomingcallpopup == "true")echo 'checked'?>></td>
					</tr>
				</table>
			</td>
			<td>
				<input type="button" name="Modifier" value="Modifier" onclick="checkNewInput(this.form)">
			</td>
		</tr>
	</table>
	</div>
</form>
