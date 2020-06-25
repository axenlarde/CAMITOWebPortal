
<script type="text/javascript">

function addNew(id, search)
	{
	window.location = "shoppingCartTreatment.php?action=add&itemID="+id+"&search="+search;
	}

function deleteItem(id, search)
	{
	window.location = "shoppingCartTreatment.php?action=delete&itemID="+id+"&search="+search;
	}

function showItem(id)
	{
	window.location = "mainpage.php?page=showItem&id="+id;
	}

function validateSearch()
	{
	var search = document.getElementById("search").value;
	window.location = "mainpage.php?page=newTask&search="+search;
	}

function searchOnKeyPress(event)
	{
	if (event.keyCode == 13 || event.which == 13)
		{
		var search = document.getElementById("search").value;
		window.location = "mainpage.php?page=newTask&search="+search;
		}
	}

function action(type)
	{
	window.location = "mainpage.php?page=taskTreatment&action="+type;
	}

function emptyCart(search)
	{
	window.location = "shoppingCartTreatment.php?action=emptycart&search="+search;
	}

function addAll(search)
	{
	window.location = "shoppingCartTreatment.php?action=addAll&search="+search;
	}

</script>


<?php
/** 
 * Page used to setup a new task
 */
include "sessionFound.php";

/****
 * We fetch the office and device list
 */
//We contact the server to get the item list

$lastSearch = "Rechercher..";
if(isset($_GET["search"]))
	{
	$searchContent = $_GET["search"];
	$request = '<xml>
				<request>
					<type>search</type>
					<content>
						<search>'.$searchContent.'</search>
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
		header('Location: mainpage.php?page=branchMainAdmin&message=generalerror');
		exit;
		}
	
	//Finally we open the xml content as String
	$searchResult = simplexml_load_string($resp);
	
	$lastSearch = $_GET["search"];
	}

$MaxResult = 1200;
?>
<h3><div class="navibar"><a href="mainpage.php?page=branchMainAdmin">Retour</a>>Nouvelle Tâche</div></h3>

<div class="search">
<table>
	<tr>
		<td><input type="text" name="search" id="search" placeholder="<?php echo $lastSearch ?>" onkeypress="searchOnKeyPress(event)"></td>
		<td><button type="submit" onclick="validateSearch()">GO</button>
		<?php
		if(isset($_GET["search"]))
			{
			if(!empty($_GET['search']))
				{
				echo '<button type="submit" onclick="addAll(\''.$_GET["search"].'\')">Tout Ajouter</button>';
				}
			}
		if(isset($_SESSION['cart']))
			{
			if(!empty($_SESSION['cart']))
				{
				echo '
					<button type="submit" onclick="emptyCart(\''.$_GET['search'].'\')">Tout Retirer</button>
					<button type="submit" onclick="action(\'migrate\')">Migration</button>
					<button type="submit" onclick="action(\'rollback\')">Retour arrière</button>
					<button type="submit" onclick="action(\'reset\')">Reset</button>
					<button type="submit" onclick="action(\'cli\')">Cli</button>
					<button type="submit" onclick="action(\'survey\')">Audit</button>
					';
				}
			}
		?>
		</td>
	</tr>
</table>
</div>

<?php
//We check if we ask for a research
if(isset($_GET["search"]))
	{
	$officeCount = count($searchResult->reply->content->offices->office);
	$lofficeCount = 0;
	
	foreach($searchResult->reply->content->offices->office as $o)
		{
		$lofficeCount += count($o->linkedoffices->linkedoffice);
		}
	
	if($officeCount > 0)
		{
		echo'<h3>Résultat de la recherche : </h3><hr>';
		}
	
	if($officeCount > 0)
		{
		if($lofficeCount != 0)echo'<h4>'.$officeCount.' sites (+'.$lofficeCount.' sites liés) trouvés : </h4>';
		else echo'<h4>'.$officeCount.' sites trouvés : </h4>';
		
		echo '
		<div class="forwardlist">
		<table>
			<tr>
				<td><b>CODA</b></td>
				<td><b>Nom</b></td>
				<td><b>Type</b></td>
				<td><b>Pole</b></td>
				<td><b>Lot</b></td>
				<td><b>Statut</b></td>
				<td><b>Ajouter</b></td>
			</tr>
			';
		
		$index = 0;
		while(true)
			{
			if($index >= $MaxResult)
				{
				$index++;
				break;//Just a security
				}
			
			$office = $searchResult->reply->content->offices->office[$index];
			
			if(isset($office))
				{
				echo '<tr>
		 				<td><div class="forwarddate">'.$office->coda.'</div></td>
						<td><div class="forwarddate">'.$office->name.'</div></td>
						<td><div class="forwarddate">'.$office->type.'</div></td>
						<td><div class="forwarddate">'.$office->pole.'</div></td>
						<td><div class="forwarddate">'.$office->lot.'</div></td>
 					';
				if($office->status == "migrated")
					{
					echo '<td><div class="forwardstatusok">Migré</div></td>';
					}
				else
					{
					echo '<td><div class="forwarddate">Pas encore migré</div></td>';
					}
				if((isset($_SESSION['cart'])) && in_array($office->id,$_SESSION['cart']))
					{
					echo '
						<td><div class="forwardstatusnok"><input type="button" name="supprimer" value="X" title="supprimer" onclick="deleteItem(\''.$office->id.'\',\''.$_GET['search'].'\')"></div></td>
		 			</tr>
					';
					}
				else
					{
					echo '
						<td><div class="forwardaction"><input type="button" name="Ajouter" value="+" title="Ajouter" onclick="addNew(\''.$office->id.'\',\''.$_GET['search'].'\')"></div></td>
		 			</tr>
					';
					}
					
				//We manage linked office
				$lindex = 0;
				while(true)
					{
					$linkedOffice = $office->linkedoffices->linkedoffice[$lindex];
					if(isset($linkedOffice))
						{
						echo '<tr>
				 				<td><div class="devicelistfirst">'.$linkedOffice->coda.'</div></td>
								<td><div class="devicelistfirst">'.$linkedOffice->name.'</div></td>
								<td><div class="devicelistfirst">'.$linkedOffice->type.'</div></td>
								<td><div class="devicelistfirst">'.$linkedOffice->pole.'</div></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>';
						}
					else
						{
						break;
						}
					$lindex++;
					}
				}
			else
				{
				break;
				}
			$index++;
			}
		
		echo'</table>
		</div>';
		
		if($index > $MaxResult)
			{
			echo "<h4>Désolé, il n'est pas possible d'afficher plus d'entrée (max ".$MaxResult.")</h4>";
			}
		}
	
	echo'<br><br><hr>';
	}
else
	{
	//We display the default page without in progress search
	echo '<br><br>&nbspAucune recherche en cours';
	}
?>




