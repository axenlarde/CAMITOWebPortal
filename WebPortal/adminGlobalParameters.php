
<script type="text/javascript">

function verif(formulaire)
	{
	formulaire.submit();
	}
</script>


<?php
/** 
 * Page used to set the global parameters
 */
include "sessionFound.php";

/****
 * We get the xml parameters to display
 */
//First we read the xml file
$xml=simplexml_load_file("document/xmlFiles/OverallSettings.xml") or die("Error");

?>
<h3><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Gestion des paramètres</h3>
<br>
<br>
<form name="globalParametersForm" id="globalParametersForm" method=post action="globalParametersTreatment.php" onkeypress="submitForm(event)">
	<table>
		<tr>
			<td>Temps d'attente maximum : </td>
			<td><b><?php echo intval($xml->misc->agenttimeout)?></b> secondes</td>
			<td>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</td>
			<td>Nouvelle valeur : </td>
			<td><select name="maxwaitingtime" id="maxwaitingtime">
				<?php
				for($i=10; $i<=300; $i+=5)
					{
					echo "<option value=\"".$i."\">".$i."</option>";
					}
				?>
			</select></td>
			<td><input type="button" name="modifier" value="modifier" onclick="verif(this.form)"></td>
		</tr>
	</table>
</form>



