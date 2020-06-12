<script type="text/javascript">

function checkNewInput(index, id)
	{
	var extension = document.getElementById("extension"+index).value;
	
	if(!/^\d{4}$/.test(extension))
		{
		alert("Le numéro est incorrect");
		}
	else
		{
		window.location = "techGuyTreatment.php?action=add&id="+id+"&extension="+extension;
		}
	}

</script>

<?php
include "sessionFound.php";

/****
 * We fetch the salesforce user list
 */
//We contact the server to get the user list
$context = stream_context_create(
		array(
				'http' => array(
						'method' => 'POST',
						'header' => 'Content-type: text/xml',
						'content' => '<xml><request><type>getSalesforceUsers</type></request></xml>'))
		);

$resp = file_get_contents($url, FALSE, $context);

//Finally we open the xml content as String
$SFUserList = simplexml_load_string($resp);

?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>><a href="mainpage.php?page=adminTechGuyList">Gestion des utilisateurs</a>
	>Ajout d'un nouvel utilisateur
	</div>
</h3>
<br>
<hr>
<h3>Liste des utilisateurs salesforce : </h3>
<hr>
	<div class="forwardlist">
	<table>
		<tr>
			<td><b>Nom</b></td>
			<td><b>Prénom</b></td>
			<td><b>Extension</b></td>
			<td><b></b></td>
		</tr>
		<?php
	/**
	 * We display the fetched values
	 */
	$index = 0;
	while(true)
		{
		$techGuy = $SFUserList->salesforceusers->salesforceuser[$index];
		
		if(isset($techGuy))
			{
			echo "
	 			<tr>		
			 		<td><div class=\"forwarddate\">".$techGuy->lastname."</div></td>
					<td><div class=\"forwarddate\">".$techGuy->firstname."</div></td>
					<td><div class=\"newTechGuyTable\"><input type=\"text\" name=\"extension".$index."\" id=\"extension".$index."\" title=\"Extension\"></div></td>
					<td><div class=\"forwardaction\"><input type=\"button\" name=\"add\" value=\"Ajouter\" title=\"Ajouter\" onclick=\"checkNewInput(".$index.",'".$techGuy->salesforceid."')\"></div></td>
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
<br><br><hr>
<?php
echo "<table><tr><td>Nombre total : ".$index."</td></tr></table>";
?>
