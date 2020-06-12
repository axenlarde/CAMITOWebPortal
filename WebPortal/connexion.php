
<script type="text/javascript">

//We use the following to notify the user about a wrong entry
function interpreteError()
	{
	var wrongPassword = getUrlVars()["authenticationError"];
	if(wrongPassword == "badLogin")
		{
		enlightLogin();
		}
	else if(wrongPassword == "badPassword")
		{
		enlightPassword();
		}
	}

function submitForm(e, formulaire)
	{
	if((e.keyCode == 13) || (e.which == 13))
		{
		document.getElementById("fconnexion").submit();
		}
	}

function verif(formulaire)
	{
	if(formulaire.login.value == "")
		{
		enlightLogin();
		}
	else if(formulaire.password.value == "")
		{
		enlightPassword();
		}
	else
		{
		formulaire.submit();
		}
	}

function enlightLogin()
	{
	document.getElementById("wrongLogin").style.display = "block";
	document.getElementById("login").style.border = "2px solid red";
	}

function enlightPassword()
	{
	document.getElementById("wrongPass").style.display = "block";
	document.getElementById("password").style.border = "2px solid red";
	}

function inputSelect()
	{
	document.getElementById("login").style.border = "0px solid";
	document.getElementById("password").style.border = "0px solid";
	document.getElementById("wrongLogin").style.display = "none";
	document.getElementById("wrongPass").style.display = "none";
	}

function getUrlVars()
	{
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, 
		function(m,key,value)
			{
			vars[key] = value;
			});
	return vars;
	}

</script>


<p><b>Veuillez vous identifier pour accéder au portail : </b></p>

<div class="newTechGuyTable">
<form name="fconnexion" id="fconnexion" method=post action="authentication.php" onkeypress="submitForm(event)">
	<table>
		<tr>
			<td><b>Login : </b></td>
			<td><div id="login"><input type="text" name="login" id="loginInput" onclick="inputSelect()"></div></td>
			<td><div id="wrongLogin" style="color: red; font-weight: bold; display: none;">Login incorrect</div></td>
		</tr>
		<tr>
			<td><b>Mot de passe : </b></td>
			<td><div id="password"><input type="password" name="password" onclick="inputSelect()"></div></td>
			<td><div id="wrongPass" style="color: red; font-weight: bold; display: none;">Mot de passe incorrect</div></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="button" name="valider" value="Valider" onclick="verif(this.form)"></td>
			<td></td>
		</tr>
	</table>
</form>
</div>

<script type="text/javascript">

//We use the following to notify the user about a wrong entry
var wrongPassword = getUrlVars()["authenticationError"];
var login = getUrlVars()["login"];
if(window.location.href.indexOf("login") == -1)
	{
	login = "";
	}

if(wrongPassword == "badLogin")
	{
	enlightLogin();
	}
else if(wrongPassword == "badPassword")
	{
	enlightPassword();
	document.getElementById("loginInput").value = login;
	}
	

</script>

