<?php
session_start();
/****
 * File used to authenticate users once they have submit the form
 * 
 * We are going to get here the login and password of the user and then find
 * them in the user xml file. If we find the user the access is granted. If not the
 * user is redirected to authentication page
 */
?>

<!DOCTYPE html>
<html>
<body>

<?php

$login = $_POST['login'];
$password = $_POST['password'];
//we need the MD5 hash of the password
$password = md5($password);

//We read the xml file
$xml=simplexml_load_file("document/users.xml") or die("Error");
$users = $xml->user;//Users is an array of user

foreach($users as $user)
	{
	if($login == $user->userid)
		{
		if($password == $user->password)
			{
			$tab = array();
			$tab[0] = (string) $user->userid;
			$tab[1] = (string) $user->lastname;
			$tab[2] = (string) $user->firstname;
			$tab[3] = (string) $user->accessgroup;
			
			$_SESSION['login'] = $tab;
			
			header("Location: mainpage.php?page=branchMainAdmin");
			exit;
			}
		else
			{
			header("Location: mainpage.php?authenticationError=badPassword&login=".$login);
			exit;
			}
		}
	}

//If we reach this point we return to the mainpage
header("Location: mainpage.php?authenticationError=badLogin");
exit;
?>

</body>
</html>