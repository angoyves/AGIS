   
<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

$user_id = (MinmapDB::getInstance()->get_user_id_by_name($_POST['user_name']));
$compteur = (MinmapDB::getInstance()->get_compteur_by_name($_POST['user_name']));

$personneNomIsUnique = true;
$personneNomIsEmpty = false;
$numeroCompteIsUnique = true;
$numeroCompteIsEmpty = false;
$personneMatriculeIsUnique = true;
$personneMatriculeIsEmpty = false;
$cleIsEmpty = false;
$groupeIsEmpty = false;
$structureIsEmpty = false;
$fonctionIsEmpty = false;
$agenceIsEmpty = false;
$date = date('Y-m-d H:i:s');

?>
<?php
$colname_rsUserID = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_rsUserID = $_SESSION['MM_Username'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUserID = sprintf("SELECT user_id FROM users WHERE user_login = %s", GetSQLValueString($colname_rsUserID, "text"));
$rsUserID = mysql_query($query_rsUserID, $MyFileConnect) or die(mysql_error());
$row_rsUserID = mysql_fetch_assoc($rsUserID);
$totalRows_rsUserID = mysql_num_rows($rsUserID);

if ($_SERVER['REQUEST_METHOD'] == "POST"){
	
	//$matricule = $_POST['personne_mat1'] . " " . $_POST['personne_mat2'] . "-" . $_POST['personne_mat3'];
    
	if (array_key_exists("back", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: index.php');
        exit;
    }
	if (array_key_exists("back2", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: verify.php?nameID=variableURL');
        exit;
    }
	if (array_key_exists("back3", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: create_personnes.php');
        exit;
    }
	if (array_key_exists("back4", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
        header('Location: agence.php');
        exit;
    }
	if (array_key_exists("back5", $_POST)) {
        /** The Back to the List key was pressed.
         * Code redirects the user to the editWishList.php */
		  $goTo = "index.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$goTo .= (strpos($goTo, '?')) ? "&" : "?";
			$goTo .= $_SERVER['QUERY_STRING'];
		  }
		  header(sprintf("Location: %s", $updateGoTo));
    }
    /** Check whether the user has filled in the wisher's name in the text field "user" */
    if ($_POST['personne_nom']== ""){
        $personneNomIsEmpty = true;
    }
   // if ($_POST['personne_matricule']==""){
	if ($_POST['personne_matricule']=="" ){
        $personneMatriculeIsEmpty = true;
    }
	
    if ($_POST['numero_compte']==""){
    	$numeroCompteIsEmpty = true;
    }
	
    if ($_POST['cle']==""){
        $cleIsEmpty = true;
    }
	if ($_POST['sous_groupe_id']==""){	
		$groupeIsEmpty = true;
	}
	if ($_POST['agence_code']==""){	
		$agenceIsEmpty = true;
	}
	if ($_POST['structure_id']==""){	
		$structureIsEmpty = true;
	}
	if ($_POST['fonction_id']==""){	
		$fonctionIsEmpty = true;
	}

    /** Create database connection */

   // $personneID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_nom, $_POST['personne_nom']);
    $personneID = MinmapDB::getInstance()->get_person_id_by_name_and_cpte($_POST['personne_nom'], $_POST['numero_compte']);
    if ($personneID) {
        $personneNomIsUnique = false;
    }

   // $matriculeID = MinmapDB::getInstance()->get_value_id_by_value(personne_id, personnes, personne_matricule, $_POST['personne_matricule']);
    $matriculeID = MinmapDB::getInstance()->get_personne_id_by_matricule($_POST['personne_matricule']);
    if ($matriculeID && ($_GET[personneID]==1 || $_GET[personneID]==2)) {
        $personneMatriculeIsUnique = false;
    }

    $compteID = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'numero_compte', $_POST['numero_compte']);
  // $compteID = MinmapDB::getInstance()->get_wisher_id_by_name($_POST['numero_compte']);
    if ($compteID) {
        $numeroCompteIsUnique = false;
    }
	
if (!$personneNomIsEmpty && $personneNomIsUnique && !$personneMatriculeIsEmpty && $personneMatriculeIsUnique && !$numeroCompteIsEmpty && $numeroCompteIsUnique && !$cleIsEmpty && !$groupeIsEmpty && !$agenceIsEmpty && !$structureIsEmpty && !$fonctionIsEmpty){
	
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL1 = sprintf("INSERT INTO personnes (personne_matricule, personne_nom, personne_prenom, personne_grade, domaine_id, personne_telephone, structure_id, sous_groupe_id, fonction_id, type_personne_id, user_id, date_creation, display) VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                      /* GetSQLValueString($_POST['personne_id'], "int"),*/
                       GetSQLValueString($_POST['personne_matricule'], "text"),
                       GetSQLValueString(strtoupper($_POST['personne_nom']), "text"),
                       GetSQLValueString(ucfirst($_POST['personne_prenom']), "text"),
                       GetSQLValueString($_POST['personne_grade'], "text"),
					   GetSQLValueString($_POST['domaine_id'], "int"),
                       GetSQLValueString($_POST['personne_telephone'], "text"),
                       GetSQLValueString($_POST['structure_id'], "int"),
                       GetSQLValueString($_POST['sous_groupe_id'], "int"),
                       GetSQLValueString($_POST['fonction_id'], "int"),
                       GetSQLValueString($_POST['type_personne_id'], "int"),
					   GetSQLValueString($row_rsUserID['user_id'], "int"),
                       GetSQLValueString($date, "date"),
                       GetSQLValueString($_POST['display'], "text"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result1 = mysql_query($insertSQL1, $MyFileConnect) or die(mysql_error());
  $Personne_id = MinmapDB::getInstance()->get_value_id_by_value('personne_id', 'personnes', 'personne_nom', $_POST['personne_nom']); 
  $agence_cle = $_POST['banque_code'] . "" . $_POST['agence_code'];
  $insertSQL2 = sprintf("INSERT INTO rib (personne_id, personne_matricule, agence_cle, banque_code, agence_code, numero_compte, user_id, cle, date_creation) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($Personne_id, "int"),
					   GetSQLValueString($_POST['personne_matricule'], "text"),
					   GetSQLValueString($agence_cle, "text"),
                       GetSQLValueString($_POST['banque_code'], "text"),
                       GetSQLValueString($_POST['agence_code'], "text"),
                       GetSQLValueString($_POST['numero_compte'], "text"),
					   GetSQLValueString($row_rsUserID['user_id'], "int"),
                       GetSQLValueString($_POST['cle'], "text"),
                       GetSQLValueString($date, "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result2 = mysql_query($insertSQL2, $MyFileConnect) or die(mysql_error());
  
  $compteur = $compteur + 1;
  $updateSQL = sprintf("UPDATE users SET  compteur=%s WHERE user_id=%s",
						   GetSQLValueString($compteur, "int"),
						   GetSQLValueString($user_id, "int"));
	
  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Results = mysql_query($updateSQL, $MyFileConnect) or die(mysql_error());
  
  $insertSQL = sprintf("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateCreation) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($user_id, "int"),
                       GetSQLValueString('sample23.php?comID='.$_GET['comID'].'&menuID='.$_GET[menuID].'&valid=mb&lID='.$_GET['lID'].'&mID='.$_GET['mID'], "text"),
                       GetSQLValueString('A saisie une personne avec l\'ID :'.$Personne_id.', dans le fichier.', "text"),
                       GetSQLValueString(date('Y-m-d H:i:s'), "date"));

  mysql_select_db($database_MyFileConnect, $MyFileConnect);
  $Result3 = mysql_query($insertSQL, $MyFileConnect) or die(mysql_error());
  
  if (isset($_GET['page']) && $_GET['page'] == "new_membre2"){
  $insertGoTo = "new_membres2.php";  
  } elseif (isset($_GET['page']) && $_GET['page'] == "new_session"){
  $insertGoTo = "new_membres.php";
  } elseif (isset($_GET['page']) && $_GET['page'] == "new_representant"){
  $insertGoTo = "new_representant.php";
  } else {
  $insertGoTo = "show_personnes.php";
  }
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
}
}

$colname_rsStructures = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsStructures = $_GET['personneID'];
  $colname_rsFonctionID = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsStructures = sprintf("SELECT structure_id, structure_lib, code_structure FROM structures WHERE type_structure_id = %s AND display = '1' ORDER BY structure_lib ASC", GetSQLValueString($colname_rsStructures, "int"));
$rsStructures = mysql_query($query_rsStructures, $MyFileConnect) or die(mysql_error());
$row_rsStructures = mysql_fetch_assoc($rsStructures);
$totalRows_rsStructures = mysql_num_rows($rsStructures);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGroupe = "SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1'";
$rsSousGroupe = mysql_query($query_rsSousGroupe, $MyFileConnect) or die(mysql_error());
$row_rsSousGroupe = mysql_fetch_assoc($rsSousGroupe);
$totalRows_rsSousGroupe = mysql_num_rows($rsSousGroupe);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsFonction = sprintf("SELECT fonction_id, fonction_lib FROM fonctions WHERE groupe_fonction_id = %s AND display = '1'",GetSQLValueString($colname_rsFonctionID, "int"));
$rsFonction = mysql_query($query_rsFonction, $MyFileConnect) or die(mysql_error());
$row_rsFonction = mysql_fetch_assoc($rsFonction);
$totalRows_rsFonction = mysql_num_rows($rsFonction);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsTypePersonne = "SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE display = '1'";
$rsTypePersonne = mysql_query($query_rsTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
$totalRows_rsTypePersonne = mysql_num_rows($rsTypePersonne);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanques = "SELECT banque_code, banque_lib FROM banques WHERE display = '1'";
$rsBanques = mysql_query($query_rsBanques, $MyFileConnect) or die(mysql_error());
$row_rsBanques = mysql_fetch_assoc($rsBanques);
$totalRows_rsBanques = mysql_num_rows($rsBanques);



$colname_rsBanqueq = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsBanqueq = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsBanqueq = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsBanqueq, "text"));
$rsBanqueq = mysql_query($query_rsBanqueq, $MyFileConnect) or die(mysql_error());
$row_rsBanqueq = mysql_fetch_assoc($rsBanqueq);
$totalRows_rsBanqueq = mysql_num_rows($rsBanqueq);


$colname_rsSelectTypePersonne = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSelectTypePersonne = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelectTypePersonne = sprintf("SELECT type_personne_id, type_personne_lib FROM type_personnes WHERE type_personne_id = %s", GetSQLValueString($colname_rsSelectTypePersonne, "int"));
$rsSelectTypePersonne = mysql_query($query_rsSelectTypePersonne, $MyFileConnect) or die(mysql_error());
$row_rsSelectTypePersonne = mysql_fetch_assoc($rsSelectTypePersonne);
$totalRows_rsSelectTypePersonne = mysql_num_rows($rsSelectTypePersonne);

$colname_rsSousGrouepByUrl = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsSousGrouepByUrl = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousGrouepByUrl = sprintf("SELECT groupes.groupe_id,groupes.groupe_lib,sous_groupes.sous_groupe_id,sous_groupes.sous_groupe_lib FROM groupes, sous_groupes WHERE sous_groupes.groupe_id = groupes.groupe_id AND  sous_groupes.display = '1' AND  sous_groupes.groupe_id = %s", GetSQLValueString($colname_rsSousGrouepByUrl, "int"));
$rsSousGrouepByUrl = mysql_query($query_rsSousGrouepByUrl, $MyFileConnect) or die(mysql_error());
$row_rsSousGrouepByUrl = mysql_fetch_assoc($rsSousGrouepByUrl);
$totalRows_rsSousGrouepByUrl = mysql_num_rows($rsSousGrouepByUrl);

$colname_rsSelBanqueUrl = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsSelBanqueUrl = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSelBanqueUrl = sprintf("SELECT banque_code, banque_lib FROM banques WHERE banque_code = %s", GetSQLValueString($colname_rsSelBanqueUrl, "text"));
$rsSelBanqueUrl = mysql_query($query_rsSelBanqueUrl, $MyFileConnect) or die(mysql_error());
$row_rsSelBanqueUrl = mysql_fetch_assoc($rsSelBanqueUrl);
$totalRows_rsSelBanqueUrl = mysql_num_rows($rsSelBanqueUrl);

$colname_rsGroupe = "-1";
if (isset($_GET['personneID'])) {
  $colname_rsGroupe = $_GET['personneID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsGroupe = sprintf("SELECT sous_groupe_id, sous_groupe_lib, groupe_id FROM sous_groupes WHERE groupe_id = %s", GetSQLValueString($colname_rsGroupe, "int"));
$rsGroupe = mysql_query($query_rsGroupe, $MyFileConnect) or die(mysql_error());
$row_rsGroupe = mysql_fetch_assoc($rsGroupe);
$totalRows_rsGroupe = mysql_num_rows($rsGroupe);

$colname_rsAgences = "-1";
if (isset($_GET['banqueID'])) {
  $colname_rsAgences = $_GET['banqueID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAgences = sprintf("SELECT agence_code, agence_lib FROM agences WHERE banque_code = %s AND display = '1' ORDER BY agence_code", GetSQLValueString($colname_rsAgences, "text"));
$rsAgences = mysql_query($query_rsAgences, $MyFileConnect) or die(mysql_error());
$row_rsAgences = mysql_fetch_assoc($rsAgences);
$totalRows_rsAgences = mysql_num_rows($rsAgences);

$colname_rsDomaines = "-1";
if (isset($_GET['domID'])) {
  $colname_rsDomaines = $_GET['domID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsDomaines = sprintf("SELECT domaine_id, domaine_lib FROM domaines_activites WHERE domaine_id = %s", GetSQLValueString($colname_rsDomaines, "int"));
$rsDomaines = mysql_query($query_rsDomaines, $MyFileConnect) or die(mysql_error());
$row_rsDomaines = mysql_fetch_assoc($rsDomaines);
$totalRows_rsDomaines = mysql_num_rows($rsDomaines);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>
 
<!--  checkbox styling script -->
<script src="js/jquery/ui.core.js" type="text/javascript"></script>
<script src="js/jquery/ui.checkbox.js" type="text/javascript"></script>
<script src="js/jquery/jquery.bind.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$('input').checkBox();
	$('#toggle-all').click(function(){
 	$('#toggle-all').toggleClass('toggle-checked');
	$('#mainform input[type=checkbox]').checkBox('toggle');
	return false;
	});
});
</script>  


<![if !IE 7]>

<!--  styled select box script version 1 -->
<script src="js/jquery/jquery.selectbox-0.5.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect').selectbox({ inputClass: "selectbox_styled" });
});
</script>
 

<![endif]>


<!--  styled select box script version 2 --> 
<script src="js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_form_1').selectbox({ inputClass: "styledselect_form_1" });
	$('.styledselect_form_2').selectbox({ inputClass: "styledselect_form_2" });
});
</script>

<!--  styled select box script version 3 --> 
<script src="js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.styledselect_pages').selectbox({ inputClass: "styledselect_pages" });
});
</script>

<!--  styled file upload script --> 
<script src="js/jquery/jquery.filestyle.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
$(function() {
	$("input.file_1").filestyle({ 
	image: "images/forms/upload_file.gif",
	imageheight : 29,
	imagewidth : 78,
	width : 300
	});
});
</script>

<!-- Custom jquery scripts -->
<script src="js/jquery/custom_jquery.js" type="text/javascript"></script>
 
<!-- Tooltips -->
<script src="js/jquery/jquery.tooltip.js" type="text/javascript"></script>
<script src="js/jquery/jquery.dimensions.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$('a.info-tooltip ').tooltip({
		track: true,
		delay: 0,
		fixPNG: true, 
		showURL: false,
		showBody: " - ",
		top: -35,
		left: 5
	});
});
</script> 

<!--  date picker script -->
<link rel="stylesheet" href="css/datePicker.css" type="text/css" />
<script src="js/jquery/date.js" type="text/javascript"></script>
<script src="js/jquery/jquery.datePicker.js" type="text/javascript"></script>
<script type="text/javascript" charset="utf-8">
        $(function()
{

// initialise the "Select date" link
$('#date-pick')
	.datePicker(
		// associate the link with a date picker
		{
			createButton:false,
			startDate:'01/01/2005',
			endDate:'31/12/2020'
		}
	).bind(
		// when the link is clicked display the date picker
		'click',
		function()
		{
			updateSelects($(this).dpGetSelected()[0]);
			$(this).dpDisplay();
			return false;
		}
	).bind(
		// when a date is selected update the SELECTs
		'dateSelected',
		function(e, selectedDate, $td, state)
		{
			updateSelects(selectedDate);
		}
	).bind(
		'dpClosed',
		function(e, selected)
		{
			updateSelects(selected[0]);
		}
	);
	
var updateSelects = function (selectedDate)
{
	var selectedDate = new Date(selectedDate);
	$('#d option[value=' + selectedDate.getDate() + ']').attr('selected', 'selected');
	$('#m option[value=' + (selectedDate.getMonth()+1) + ']').attr('selected', 'selected');
	$('#y option[value=' + (selectedDate.getFullYear()) + ']').attr('selected', 'selected');
}
// listen for when the selects are changed and update the picker
$('#d, #m, #y')
	.bind(
		'change',
		function()
		{
			var d = new Date(
						$('#y').val(),
						$('#m').val()-1,
						$('#d').val()
					);
			$('#date-pick').dpSetSelected(d.asString());
		}
	);

// default the position of the selects to today
var today = new Date();
updateSelects(today.getTime());

// and update the datePicker to reflect it...
$('#d').trigger('change');
});
</script>

<!-- MUST BE THE LAST SCRIPT IN <HEAD></HEAD></HEAD> png fix -->
<script src="js/jquery/jquery.pngFix.pack.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
$(document).pngFix( );
});
</script>
<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
</head>

<body>
<div id="page-top-outer">
	<div id="page-top">
    	<div id="logo">
        	<a href=""><img src="images/logo/logo_minmap.gif" width="60" height="60" alt="" /></a>
        </div>
        <div id="top-search">
        <table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td><input type="text" value="Rechercher" onblur="if (this.value=='') { this.value='Rechercher'; }" onfocus="if (this.value=='Rechercher') { this.value=''; }" class="top-search-inp" /></td>
		<td>
		 
		<select  class="styledselect">
			<option value="">Tous</option>
			<option value="">Fichier</option>
			<option value="">Commission</option>
			<option value="">Sessions</option>
			<option value="">Paiements</option>
		</select> 
		 
		</td>
		<td>
		<input type="image" src="images/shared/top_search_btn.gif"  />
		</td>
		</tr>
		</table>
        </div>
        <!--  end top-search -->
 		<div class="clear">Zone blanche</div>
    </div>
</div>
<!-- Fin: page-top-outer -->
	
<div class="clear">&nbsp;</div>

<div class="nav-outer-repeat">
	<div class="nav-outer">
    	<div id="nav-right">
        	<div class="nav-divider"></div>
            <div class="showhide-account"><img src="images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
            <div class="nav-divider"></div>
            <a id="logout" href=""><img src="images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
            <div class="clear"></div>
            <!-- Ici sont definis les sous menus de menu My Account-->
            <div class="account-content">
            	<div class="account-drop-inner">
                	<a href="" id="acc-settings">Settings</a>
                    <div class="clear">&nbsp;</div>
                    <div class="acc-line">&nbsp;</div>
                    <a href="" id="acc-details">Personal details </a>
                    <div class="clear">&nbsp;</div>
                    <div class="acc-line">&nbsp;</div>
                    <a href="" id="acc-project">Project details</a>
                    <div class="clear">&nbsp;</div>
                    <div class="acc-line">&nbsp;</div>
                    <a href="" id="acc-inbox">Inbox</a>
                    <div class="clear">&nbsp;</div>
                    <div class="acc-line">&nbsp;</div>
                    <a href="" id="acc-stats">Statistics</a>
                </div>
            </div>
            <!-- Fin sous menu My account -->
        </div>
        
		<!--  start nav -->
		<div class="nav">
		<div class="table">
		
		<ul class="select"><li><a href="#nogo"><b>Tableau de bord</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href="#nogo">Dashboard Details 1</a></li>
				<li><a href="#nogo">Dashboard Details 2</a></li>
				<li><a href="#nogo">Dashboard Details 3</a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		                    
		<ul class="current"><li><a href="#nogo"><b>Fichier</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub show">
			<ul class="sub">
				<li class="sub_show"><a href="#nogo">Tout le Fichier</a></li>
				<li><a href="#nogo">Fichier des Experts</a></li>
				<li><a href="#nogo">Personnes sans RIB</a></li>
                <li><a href="#nogo">Ajouter une personne</a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Commissions</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href="#nogo">Categories Details 1</a></li>
				<li><a href="#nogo">Categories Details 2</a></li>
				<li><a href="#nogo">Categories Details 3</a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Sessions</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href="#nogo">Ajouter une session</a></li>
				<li><a href="#nogo">Afficher details</a></li>
				<li><a href="#nogo">Immpression Recapitulatifs</a></li>
			 
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="nav-divider">&nbsp;</div>
		
		<ul class="select"><li><a href="#nogo"><b>Paiements</b><!--[if IE 7]><!--></a><!--<![endif]-->
		<!--[if lte IE 6]><table><tr><td><![endif]-->
		<div class="select_sub">
			<ul class="sub">
				<li><a href="#nogo">News details 1</a></li>
				<li><a href="#nogo">News details 2</a></li>
				<li><a href="#nogo">News details 3</a></li>
			</ul>
		</div>
		<!--[if lte IE 6]></td></tr></table></a><![endif]-->
		</li>
		</ul>
		
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
		</div>
		<!--  start nav -->
        
 </div>
 <div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->
<div class="clear"></div>

<!-- start content-outer -->
<div id="content-outer">

	<!-- start content -->
	<div id="content">
    <div id="page-heading"><h1>Ajouter une personne</h1></div>
    
    <!--..........................................-->
    
    
    

        
    
    
    <!-- .........................................-->
    
    <table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
        <tr>
            <th rowspan="3" class="sized"><img src="images/shared/side_shadowleft.jpg" width="20" height="300" alt="" /></th>
            <th class="topleft"></th>
            <td id="tbl-border-top">&nbsp;</td>
            <th class="topright"></th>
            <th rowspan="3" class="sized"><img src="images/shared/side_shadowright.jpg" width="20" height="300" alt="" /></th>
        </tr>
        <tr>
            <td id="tbl-border-left"></td>
            <td>
            
                <form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
                  <table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                    <tr valign="baseline">
                      <td valign="top">Selectionne la banque <span class="error">*</span> :</td>
                      <td colspan="3">
                      <select class="styledselect_form_1" name="jumpMenu" id="jumpMenu" onchange="MM_jumpMenu('parent',this,0)">
                        <option value="" <?php if (!(strcmp("", $_GET['banqueID']))) {echo "selected=\"selected\"";} ?>>[Select banque...]</option>
                        <?php
                do {  
                ?>
                        <option value="sample103.php?<?php if(isset($_GET['menuID'])) { echo "menuID=" . $_GET['menuID'] . "&"; } ?>banqueID=<?php echo $row_rsBanques['banque_code']?>&page=<?php echo $_GET['page']; ?>&action=<?php echo $_GET['action']; ?>&comID=<?php echo $_GET['comID']; ?>"<?php if (!(strcmp($row_rsBanques['banque_code'], $_GET['banqueID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rsBanques['banque_code'] . " : " . $row_rsBanques['banque_lib'] ?></option>
                        <?php
                } while ($row_rsBanques = mysql_fetch_assoc($rsBanques));
                  $rows = mysql_num_rows($rsBanques);
                  if($rows > 0) {
                      mysql_data_seek($rsBanques, 0);
                      $row_rsBanques = mysql_fetch_assoc($rsBanques);
                  }
                ?>
                      </select>
                        <input type="hidden" name="banque_code" value="<?php echo $_GET[banqueID]; ?>" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td align="right" nowrap="nowrap">&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <?php if ( isset($_GET['banqueID'])) { // Show if recordset empty ?>
                    <tr valign="baseline">
                      <th align="right" valign="middle" nowrap="nowrap">TYPE PERSONNE <span class="error">*</span> :</th>
                      <td colspan="3" valign="middle"><?php //if (empty($_GET['personneID'])) { // Show if recordset empty ?>
                        <select  name="jumpMenu2" id="jumpMenu2" onchange="MM_jumpMenu('parent',this,0)">
                          <option value=""  <?php if (!(strcmp("", $_GET['personneID']))) {echo "selected=\"selected\"";} ?>>[Select type personne...]</option>
                          <?php
                do {  
                ?>
                          <option value="sample103.php?menuID=<?php echo $_GET['menuID'] ?>&&banqueID=<?php echo $_GET['banqueID'] ?>&amp;personneID=<?php echo $row_rsTypePersonne['type_personne_id']?>&page=<?php echo $_GET['page']; ?>&action=<?php echo $_GET['action']; ?>&comID=<?php echo $_GET['comID']; ?>"
                          <?php if (!(strcmp($row_rsTypePersonne['type_personne_id'], $_GET['personneID']))) {echo "selected=\"selected\"";} ?>><?php echo strtoupper(htmlentities($row_rsTypePersonne['type_personne_lib']))?></option>
                          <?php
                } while ($row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne));
                  $rows = mysql_num_rows($rsTypePersonne);
                  if($rows > 0) {
                      mysql_data_seek($rsTypePersonne, 0);
                      $row_rsTypePersonne = mysql_fetch_assoc($rsTypePersonne);
                  }
                ?>
                        </select>
                        <?php //} // Show if recordset empty ?>
                        <?php if (isset($_GET[personneID])) { // Show if recordset not empty ?>
                        <input type="hidden" name="type_personne_id" value="<?php echo $_GET[personneID]; ?>" size="32" />
                        <?php } // Show if recordset not empty ?>
                        <?php echo $_POST['type_personne_id'] ?></td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                    <?php if (isset($_GET['personneID'])) { // Show if recordset empty ?>
                    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
                    <tr valign="baseline">
                      <th nowrap="nowrap" align="right">MATRICULE <span class="error"><strong>*</strong></span> :</th>
                      <td colspan="3"><input name="personne_matricule" type="text" value="<?php if ( isset($_POST['personne_matricule'])) { echo $_POST['personne_matricule'];} ?>" size="12" maxlength="8" />
                        (ex :XXXXXX-X)
                        <?php
                            /** Display error messages if "user" field is empty or there is already a user with that name*/
                            if ($personneMatriculeIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the MATRICULE, please!</div>
                        <?php }
                            if (!$personneMatriculeIsUnique) { ?>
                        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This MATRICULE already exists. Please check the spelling and try again</div>
                        <?php   } ?></td>
                    </tr>
                    <?php } else { ?>
                    <input name="personne_matricule" type="hidden" value="XXXXXX-X" size="12" maxlength="9" />
                    <?php } ?>
                    <tr valign="baseline">
                      <th nowrap="nowrap" align="right">NOM <span class="error">*</span> :</th>
                      <td><input type="text" name="personne_nom" value="<?php if ( isset($_POST['personne_nom'])) { echo $_POST['personne_nom'];} ?>" size="32"/>
                        <br/>
                        <?php
                            /** Display error messages if "user" field is empty or there is already a user with that name*/
                            if ($personneNomIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NAME, please!</div>
                        <?php  }
                            if (!$personneNomIsUnique) { ?>
                        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This person already exists. Please check the spelling and try again<?php $link = "detail_personnes.php?recordID=" . $personneID;?>
                        <a href="#" onclick="<?php popup($link, "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></div>
                        <!--<input type="submit" name="back2" value="Verifier"/>-->
                        <?php    }
                            ?></td>
                      <th align="right">PRENOM :</th>
                      <td><input type="text" name="personne_prenom" value="<?php if ( isset($_POST['personne_prenom'])) { echo $_POST['personne_prenom'];} ?>" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
                      <input name="domaine_id" type="hidden" value="1" />
                      <th nowrap="nowrap" align="right">GRADE :</th>
                      <td><input type="text" name="personne_grade" value="<?php if ( isset($_POST['personne_grade'])) { echo $_POST['personne_grade'];} ?>" size="32" /></td>
                      <?php } else { ?>
                      <input name="personne_grade" type="hidden" value="Certifié" />
                      <th align="right" nowrap="nowrap">DOMAINE D'ACTIVITE :</th>
                      <td><input type="hidden" name="domaine_id" value="<?php if ( isset($_GET['domID'])) { echo $_GET['domID'];} else {echo "1";} ?>" size="32" />
                        <input name="domaine_id2" type="text" value="<?php echo $row_rsDomaines['domaine_lib']; ?>" size="32" readonly="readonly" />
                        <a href="#" onclick="<?php popup("popup_domaines.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a>
                        <?php           
                    $showGoTo = "afficher_domaines.php";
                  if (isset($_SERVER['QUERY_STRING'])) {
                    $showGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
                    $showGoTo .= $_SERVER['QUERY_STRING'];
                  }
                ?>
                        <a href="#" onclick="<?php popup($showGoTo, "610", "500"); ?>"><img src="images/img/b_view.png" alt="" width="16" height="16" align="absmiddle" /></a></td>
                      <?php }// Show if recordset empty ?>
                      <th>TELEPHONE :</th>
                      <td><input type="text" name="personne_telephone" value="<?php if ( isset($_POST['personne_telephone'])) { echo $_POST['personne_telephone'];} ?>" size="32" /></td>
                    </tr>
                    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2 || $_GET[personneID]==4) { // Show if recordset empty ?>
                    <tr valign="baseline">
                      <th nowrap="nowrap" align="right">STRUCTURE <span class="error">*</span> :</th>
                      <td colspan="3"><select name="structure_id">
                        <option value="">:: Selectionner </option>
                        <?php
                do {  
                ?>
                        <option value="<?php echo $row_rsStructures['structure_id']?>" <?php if (!(strcmp($row_rsStructures['structure_id'], htmlentities($_POST['structure_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo strtoupper($row_rsStructures['structure_lib'] . "-" . $row_rsStructures['code_structure'])?></option>
                        <?php
                } while ($row_rsStructures = mysql_fetch_assoc($rsStructures));
                  $rows = mysql_num_rows($rsStructures);
                  if($rows > 0) {
                      mysql_data_seek($rsStructures, 0);
                      $row_rsStructures = mysql_fetch_assoc($rsStructures);
                  }
                ?>
                      </select>
                        <a href="#" onclick="<?php popup("new_structures.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($structureIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the STRUCTURE, please</div>
                        <?php  } ?></td>
                    </tr>
                    <?php } else { ?>
                    <input name="structure_id" type="hidden" value="342" />
                    <?php }// Show if recordset empty ?>
                    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
                    <tr valign="baseline">
                      <th align="right" nowrap="nowrap">GROUPE <span class="error">*</span> :</th>
                      <td colspan="3"><select name="sous_groupe_id" id="groupe_id">
                        <option selected="selected" value="">:: Selectionner</option>
                        <?php
                do {  
                ?>
                        <option value="<?php echo $row_rsGroupe['sous_groupe_id']?>" <?php if (!(strcmp($row_rsGroupe['sous_groupe_id'], htmlentities($_POST['sous_groupe_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsGroupe['sous_groupe_lib'])?></option>
                        <?php
                } while ($row_rsGroupe = mysql_fetch_assoc($rsGroupe));
                  $rows = mysql_num_rows($rsGroupe);
                  if($rows > 0) {
                      mysql_data_seek($rsGroupe, 0);
                      $row_rsGroupe = mysql_fetch_assoc($rsGroupe);
                  }
                ?>
                      </select>
                        <a href="#" onclick="<?php popup("popup_groupes.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($groupeIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the GROUPE, please</div>
                        <?php  } ?></td>
                    </tr>
                    <?php } else { ?>
                    <input name="sous_groupe_id" type="hidden" value="33"/>
                    <?php } ?>
                    <?php if ($_GET[personneID]==1 || $_GET[personneID]==2) { // Show if recordset empty ?>
                    <tr valign="baseline">
                      <th nowrap="nowrap" align="right">FONCTION <span class="error">*</span> :</th>
                      <td colspan="3"><select name="fonction_id">
                        <option value="" >::Selectionner</option>
                        <?php
                do {  
                ?>
                        <option value="<?php echo $row_rsFonction['fonction_id']?>" <?php if (!(strcmp($row_rsFonction['fonction_id'], htmlentities($_POST['fonction_id'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo htmlentities($row_rsFonction['fonction_lib'])?></option>
                        <?php
                } while ($row_rsFonction = mysql_fetch_assoc($rsFonction));
                  $rows = mysql_num_rows($rsFonction);
                  if($rows > 0) {
                      mysql_data_seek($rsFonction, 0);
                      $row_rsFonction = mysql_fetch_assoc($rsFonction);
                  }
                ?>
                      </select>
                        <a href="#" onclick="<?php popup("create_fonction.php", "610", "500"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle"/></a><br/>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($fonctionIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Select the FONCTION, please</div>
                        <?php }
                            ?></td>
                    </tr>
                    <?php } else { ?>
                    <input name="fonction_id" type="hidden" value="35"/>
                    <?php } ?>
                    <tr valign="baseline">
                      <td align="right" nowrap="nowrap">&nbsp;</td>
                      <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr valign="baseline">
                      <th align="right" valign="top" nowrap="nowrap">AGENCE <span class="error">*</span> :</th>
                      <td colspan="3" align="left" valign="top"><select name="agence_code" id="select">
                        <option selected="selected" value="">:: Selectionner</option>
                        <?php
                do {  
                ?>
                        <option value="<?php echo $row_rsAgences['agence_code']?>" <?php if (!(strcmp($row_rsAgences['agence_code'], htmlentities($_POST['agence_code'], ENT_COMPAT, 'utf-8')))) {echo "SELECTED";} ?>><?php echo $row_rsAgences['agence_code'] . " : " . $row_rsAgences['agence_lib']?></option>
                        <?php
                } while ($row_rsAgences = mysql_fetch_assoc($rsAgences));
                  $rows = mysql_num_rows($rsAgences);
                  if($rows > 0) {
                      mysql_data_seek($rsAgences, 0);
                      $row_rsAgences = mysql_fetch_assoc($rsAgences);
                  }
                ?>
                      </select>
                        <?php           
                    $showGoToAgence = "create_agence.php";
                  if (isset($_SERVER['QUERY_STRING'])) {
                    $showGoToAgence .= (strpos($showGoToAgence, '?')) ? "&" : "?";
                    $showGoToAgence .= $_SERVER['QUERY_STRING'];
                  }
                ?>
                        <a href="#" onclick="<?php popup($showGoToAgence, "610", "300"); ?>"><img src="images/img/b_snewtbl.png" alt="" width="16" height="16" align="absmiddle" /></a>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($agenceIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Selected AGENCE, please</div>
                        <?php  }
                            ?></td>
                    </tr>
                    <tr valign="baseline">
                      <th align="right" nowrap="nowrap">N° COMPTE <span class="error">*</span> :</th>
                      <td>
                      <?php if (isset($_GET['banqueID']) && ($_GET['banqueID']=='XXXXX')){ ?>
                      <input name="numero_compte" type="text" value="<?php echo ( isset($_POST['numero_compte'])) ?  $_POST['numero_compte'] : 'xxxxxxxxxxx'; ?>" size="32" maxlength="11" />  
                      <?php  } else { ?>
                      <input name="numero_compte" type="text" value="<?php if ( isset($_POST['numero_compte'])) { echo $_POST['numero_compte'];} ?>" size="32" maxlength="11" />
                      <?php } ?>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($numeroCompteIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the NUMERO COMPTE, please</div>
                        <?php     }
                            if (!$numeroCompteIsUnique) { ?>
                        <div class="error"><img src="images/img/s_error.png" alt="" width="16" height="16" align="absmiddle" />This NUMERO COMPTE already exists. Please check the spelling and try again</div>
                        <?php   }
                            ?>
                        (11 chiffres)</td>
                      <th align="right">CLE <span class="error">*</span> :</th>
                      <td>
                      <?php if (isset($_GET['banqueID']) && ($_GET['banqueID']=='XXXXX')){ ?>
                      <input name="cle" type="text" value="<?php echo ( isset($_POST['cle']))? $_POST['cle'] : 'xx' ?>" size="10" maxlength="2" />
                      <?php  } else { ?>
                      <input name="cle" type="text" value="<?php if ( isset($_POST['cle'])) { echo $_POST['cle'];} ?>" size="10" maxlength="2" />
                      <?php } ?>
                        <?php
                             /** Display error messages if the "password" field is empty */
                            if ($cleIsEmpty) { ?>
                        <div class="control"><img src="images/img/b_comment.png" alt="" width="16" height="16" align="absmiddle" />Enter the KEY, please</div>
                        <?php  }
                            ?>
                        (2 chiffres)</td>
                    </tr>
                    <tr valign="baseline">
                      <td align="right" nowrap="nowrap">&nbsp;</td>
                      <td colspan="3"><input type="submit" value="Ins&eacute;rer un enregistrement"/>
                        <input type="reset" name="back5" value="Reinitialiser"/></td>
                    </tr>
                    <?php } // Show if recordset empty ?>
                  </table>
                  <input type="hidden" name="date_creation" value="" />
                  <input type="hidden" name="MM_insert" value="form1" />
                  <input type="hidden" name="display" value="1" size="32" />
                  <input type="hidden" name="personne_id" value="" size="32" />
                  <input type="hidden" name="user_name" value="<?php echo $_SESSION['MM_Username']; ?>" size="32" />
                </form>
            
            
            
            
            
            
            
            
            <!--  start content-table-inner -->
            <div id="content-table-inner">
            
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr valign="top">
            <td>
            
            <!--  start related-activities -->
            <div id="related-activities">
                
                <!--  start related-act-top -->
                <div id="related-act-top">
                <img src="images/forms/header_related_act.gif" width="271" height="43" alt="" />
                </div>
                <!-- end related-act-top -->
                
                <!--  start related-act-bottom -->
                <div id="related-act-bottom">
                
                    <!--  start related-act-inner -->
                    <div id="related-act-inner">
                    
                        <div class="left"><a href=""><img src="images/forms/icon_plus.gif" width="21" height="21" alt="" /></a></div>
                        <div class="right">
                            <h5>Add another product</h5>
                            Lorem ipsum dolor sit amet consectetur
                            adipisicing elitsed do eiusmod tempor.
                            <ul class="greyarrow">
                                <li><a href="">Click here to visit</a></li> 
                                <li><a href="">Click here to visit</a> </li>
                            </ul>
                        </div>
                        
                        <div class="clear"></div>
                        <div class="lines-dotted-short"></div>
                        
                        <div class="left"><a href=""><img src="images/forms/icon_minus.gif" width="21" height="21" alt="" /></a></div>
                        <div class="right">
                            <h5>Delete products</h5>
                            Lorem ipsum dolor sit amet consectetur
                            adipisicing elitsed do eiusmod tempor.
                            <ul class="greyarrow">
                                <li><a href="">Click here to visit</a></li> 
                                <li><a href="">Click here to visit</a> </li>
                            </ul>
                        </div>
                        
                        <div class="clear"></div>
                        <div class="lines-dotted-short"></div>
                        
                        <div class="left"><a href=""><img src="images/forms/icon_edit.gif" width="21" height="21" alt="" /></a></div>
                        <div class="right">
                            <h5>Edit categories</h5>
                            Lorem ipsum dolor sit amet consectetur
                            adipisicing elitsed do eiusmod tempor.
                            <ul class="greyarrow">
                                <li><a href="">Click here to visit</a></li> 
                                <li><a href="">Click here to visit</a> </li>
                            </ul>
                        </div>
                        <div class="clear"></div>
                        
                    </div>
                    <!-- end related-act-inner -->
                    <div class="clear"></div>
                
                </div>
                <!-- end related-act-bottom -->
            
            </div>
            <!-- end related-activities -->
            
            </td>
            </tr>
            <tr>
            <td><img src="images/shared/blank.gif" width="695" height="1" alt="blank" /></td>
            <td></td>
            </tr>
            </table>
             
            <div class="clear"></div>
             
            
            </div>
            <!--  end content-table-inner  -->
            </td>
            <td id="tbl-border-right"></td>
            </tr>
            <tr>
                <th class="sized bottomleft"></th>
                <td id="tbl-border-bottom">&nbsp;</td>
                <th class="sized bottomright"></th>
            </tr>
            </table>
    
    
    </div>
    <!-- start content..........END -->
</div>
<!-- start content-outer........................END -->

<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer -->

 

<div class="clear">&nbsp;</div>
    
<!-- start footer -->         
<div id="footer">
	<!--  start footer-left -->
	<div id="footer-left">
	Copyright Layette.system Ltd. <a href="">www.lafayette.cm</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
</body>
</html>