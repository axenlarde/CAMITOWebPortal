<script type="text/javascript">
var index = 2;

function checkNewInput(form)
	{
	if(document.getElementById("userID").value == "")
		{
		alert("Le userID est obligatoire");
		}
	else if(document.getElementById("password").value != document.getElementById("retypepassword").value)
		{
		alert("Les mots de passe ne correspondent pas");
		}
	else
		{
		form.submit();
		}
	}

</script>

<?php
include "sessionFound.php";
include "importUser.php";

?>

<h3>
	<div class="navibar">
	<a href="mainpage.php?page=branchMainAdmin">Retour</a>
	>
	<a href="mainpage.php?page=adminUsers">Gestion des utilisateurs</a>
	>Ajout d'un nouvel utilisateur
	</div>
</h3>
<br>
<hr>
<h3><div class="title">Entrez les informations nécessaires :</div></h3>
<form name="NewUserForm" id="NewUserForm" method=post action="userTreatment.php?action=add">
	<div class="newTechGuyTable">
	<table>
		<tr>
			<td>
				<table id="userForm">
					<tr>
						<td>UserID* : </td>
						<td><input type="text" name="userID" id="userID"></td>
					</tr>
					<tr>
						<td>Nom : </td>
						<td><input type="text" name="lastName" id="lastName"></td>
					</tr>
					<tr>
						<td>Prénom : </td>
						<td><input type="text" name="firstName" id="firstName"></td>
					</tr>
					<tr>
						<td>Groupe : </td>
						<td>
							<select name="group" id="group">
								<?php 
									$index = 0;
									foreach($groupsFile->group as $group)
										{
										echo "<option value=\"".$index."\">".$group->name." : ".$group->description."</option>";
										$index++;
										}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Mot de passe : </td>
						<td><input type="password" name="password" id="password"></td>
					</tr>
					<tr>
						<td>Retaper le mot de passe : </td>
						<td><input type="password" name="retypepassword" id="retypepassword"></td>
					</tr>
				</table>
			</td>
			<td>
				<input type="button" name="Ajouter" value="Ajouter" onclick="checkNewInput(this.form)">
			</td>
		</tr>
	</table>
	</div>
</form>
