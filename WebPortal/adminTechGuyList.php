
<script type="text/javascript">


function checkNewTechGuy(count, maxEntry)
	{
	if(count >= maxEntry)
		{
		alert("Vous ne pouvez pas ajouter d'utilisateur : Vous avez atteint le nombre maximum");
		}
	else
		{
		window.location = "mainpage.php?page=newTechGuy";
		}
	}

function checkRemoveTechGuy(id)
	{
	if(confirm("Etes vous sûr de vouloir supprimer cet utilisateur ?"))window.location = "techGuyTreatment.php?action=delete&id="+id;
	}

function checkEditTechGuy(id)
	{
	window.location = "mainpage.php?page=updateTechGuy&id="+id;
	}

function showTechGuy(id)
	{
	window.location = "mainpage.php?page=showTechGuy&id="+id;
	}

</script>


<?php
/** 
 * Page used to display the user List
 */
include "sessionFound.php";

/****
 * We fetch the user list
 */
//We contact the server to get the user list
$context = stream_context_create(
		array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type: text/xml',
						'content' => '<xml><request><type>getUserList</type></request></xml>'))
		);

$resp = file_get_contents($url, FALSE, $context);

//Finally we open the xml content as String
$techGuyFile = simplexml_load_string($resp);

$MaxTechGuy = 200;

?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Gestion des utilisateurs</div></h3>
<br>
<h3>Liste des utilisateurs : </h3>
<hr>
	<div class="forwardlist">
	<table>
		<tr>
			<td><b>Nom</b></td>
			<td><b>Prénom</b></td>
			<td><b>Numéro</b></td>
			<td><b>Statut</b></td>
			<td><b></b></td>
			<td><b></b></td>
			<td><b></b></td>
		</tr>
	<?php
	/**
	 * First we display the current user entries
	 */
	$index = 0;
	while(true)
		{
		if($index >= $MaxTechGuy)break;//Just a security
		
		$techGuy = $techGuyFile->users->user[$index];
		
		$status = "<div class='forwarddate'>Inconnu</div>";
		
		if(isset($techGuy))
			{
			if($techGuy->status == "true")
				{
				$status = "<div class='tabconnected'>Connecté</div>";
				}
			else
				{
				$status = "<div class='tabdisconnected'>Déconnecté</div>";
				}
				
			echo "
	 			<tr>
	 				<td><div class=\"forwarddate\">".$techGuy->lastname."</div></td>
					<td><div class=\"forwarddate\">".$techGuy->firstname."</div></td>
					<td><div class=\"forwarddate\">".$techGuy->extension."</div></td>
					<td>".$status."</td>
					<td><div class=\"forwardaction\"><input type=\"button\" name=\"removeTechGuy\" value=\"X\" title=\"Supprimer\" onclick=\"checkRemoveTechGuy('".$techGuy->id."')\"></div></td>
					<td><div class=\"forwardaction\"><input type=\"button\" name=\"editTechGuy\" value=\"Editer\" title=\"Editer\" onclick=\"checkEditTechGuy('".$techGuy->id."')\"></div></td>
					<td><div class=\"forwardaction\"><input type=\"button\" name=\"showTechGuy\" value=\"O\" title=\"Afficher\" onclick=\"showTechGuy('".$techGuy->id."')\"></div></td>
	 			</tr>
 				";
			}
		else
			{
			break;
			}
		$index++;
		}
	
	?>
	</table>
	</div>
<?php
if($index > $MaxTechGuy)
	{
	echo "<h4>Désolé, il n'est pas possible d'afficher plus d'utilisateur</h4>";
	}
?>
<br><br><hr>
<?php
echo "
	<table><tr><td>Nombre total : ".$index."</td></tr></table>
	<div class=\"addnewbutton\"><input type=\"button\" name=\"addNewTechGuy\" value=\"Ajouter\" title=\"Ajouter\" onclick=\"checkNewTechGuy(".$index.",".$MaxTechGuy.")\"></div>
	";
?>




