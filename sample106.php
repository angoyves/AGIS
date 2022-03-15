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

$currentPage = $_SERVER["PHP_SELF"];


if (isset($_GET['txtChamp']) && $_GET['txtChamp'] != "" && isset($_GET['txtSearch']) && $_GET['txtSearch'] != "" ) { 
  $colname_rsPersonne = $_GET['txtChamp'];
  $text_rsPersonne = $_GET['txtSearch'];
  
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAffichePerson = sprintf("SELECT * FROM personnes WHERE $colname_rsPersonne LIKE %s AND display = '1' ORDER BY personne_nom ASC", GetSQLValueString("%" . $text_rsPersonne . "%", "text"));
$rsAffichePerson = mysql_query($query_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);
$totalRows_rsAffichePerson = mysql_num_rows($rsAffichePerson); 

} else {

$maxRows_rsAffichePerson = 20;
if (isset($_GET['nbr_reg'])){
	$maxRows_rsAffichePerson = $_GET['nbr_reg'];
	}
$pageNum_rsAffichePerson = 0;
if (isset($_GET['pageNum_rsAffichePerson'])) {
  $pageNum_rsAffichePerson = $_GET['pageNum_rsAffichePerson'];
}
$startRow_rsAffichePerson = $pageNum_rsAffichePerson * $maxRows_rsAffichePerson;
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsAffichePerson = "SELECT * FROM personnes, rib WHERE personnes.personne_id = rib.personne_id AND personnes.display = '1' ORDER BY personnes.date_creation DESC";
$query_limit_rsAffichePerson = sprintf("%s LIMIT %d, %d", $query_rsAffichePerson, $startRow_rsAffichePerson, $maxRows_rsAffichePerson);
$rsAffichePerson = mysql_query($query_limit_rsAffichePerson, $MyFileConnect) or die(mysql_error());
$row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson);
if (isset($_GET['totalRows_rsAffichePerson'])) {
  $totalRows_rsAffichePerson = $_GET['totalRows_rsAffichePerson'];
} else {
  $all_rsAffichePerson = mysql_query($query_rsAffichePerson);
  $totalRows_rsAffichePerson = mysql_num_rows($all_rsAffichePerson);
}
$totalPages_rsAffichePerson = ceil($totalRows_rsAffichePerson/$maxRows_rsAffichePerson)-1;
}

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsShowPersonInactiv = "SELECT * FROM personnes WHERE display = '0' ORDER BY dateUpdate DESC";
$rsShowPersonInactiv = mysql_query($query_rsShowPersonInactiv, $MyFileConnect) or die(mysql_error());
$row_rsShowPersonInactiv = mysql_fetch_assoc($rsShowPersonInactiv);
$totalRows_rsShowPersonInactiv = mysql_num_rows($rsShowPersonInactiv);

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '1' ORDER BY menu_id ASC";
$rsMenu = mysql_query($query_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);
$totalRows_rsMenu = mysql_num_rows($rsMenu);

$colname_rsSousMenu = "-1";
if (isset($_GET['menuID'])) {
  $colname_rsSousMenu = $_GET['menuID'];
}
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsSousMenu = sprintf("SELECT * FROM sous_menus WHERE menu_id = %s AND display = '1' ORDER BY position ASC", GetSQLValueString($colname_rsSousMenu, "int"));
$rsSousMenu = mysql_query($query_rsSousMenu, $MyFileConnect) or die(mysql_error());
$row_rsSousMenu = mysql_fetch_assoc($rsSousMenu);
$totalRows_rsSousMenu = mysql_num_rows($rsSousMenu);

$queryString_rsAffichePerson = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsAffichePerson") == false && 
        stristr($param, "totalRows_rsAffichePerson") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsAffichePerson = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsAffichePerson = sprintf("&totalRows_rsAffichePerson=%d%s", $totalRows_rsAffichePerson, $queryString_rsAffichePerson);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Internet Dreams</title>
<link rel="stylesheet" href="css/screen.css" type="text/css" media="screen" title="default" />
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->

<!--  jquery core -->
<script src="js/jquery/jquery-1.4.1.min.js" type="text/javascript"></script>

<!--  checkbox styling script -->
<script src="js/jquery/ui.core.js" type="text/javascript"></script>
<script src="js/jquery/ui.checkbox.js" type="text/javascript"></script>s
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
          image: "images/forms/choose-file.gif",
          imageheight : 21,
          imagewidth : 78,
          width : 310
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
</head>
<body> 
<!-- Start: page-top-outer -->
<div id="page-top-outer">    

<!-- Start: page-top -->
<div id="page-top">

	<!-- start logo -->
	<div id="logo">
	<a href=""><img src="images/logo/logo_minmap.gif" width="69" height="69" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<!--  start top-search -->
	<div id="top-search">
    <form id="form1" name="form1" method="get" action="sample106.php?col=<?php $_GET['txtChamp'] ?>&txt=<?php $_GET['txtSearch'] ?>">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td><input name="txtSearch" type="text" value="Rechercher" onblur="if (this.value=='') { this.value='Rechercher'; }" onfocus="if (this.value=='Rechercher') { this.value=''; }" class="top-search-inp" /></td>
		<td>
		<select name="txtChamp"  class="styledselect">
			<option value="personne_nom"> Default</option>
			<option value="personne_nom">Nom</option>
			<option value="Personne_prenom">Prenom</option>
			<option value="">Matricule</option>
			<option value="">RIB</option>
		</select> 
		</td>
		<td>
		<input type="image" src="images/shared/top_search_btn.gif"  />
		</td>
		</tr>
		</table>
     </form>
	</div>
 	<!--  end top-search -->
 	<div class="clear"></div>

</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->
	
<div class="clear">&nbsp;</div>
 
<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat"> 
<!--  start nav-outer -->
<div class="nav-outer"> 

		<!-- start nav-right -->
		<div id="nav-right">
		
			<div class="nav-divider">&nbsp;</div>
			<div class="showhide-account"><img src="images/shared/nav/nav_myaccount.gif" width="93" height="14" alt="" /></div>
			<div class="nav-divider">&nbsp;</div>
			<a href="" id="logout"><img src="images/shared/nav/nav_logout.gif" width="64" height="14" alt="" /></a>
			<div class="clear">&nbsp;</div>
		
			<!--  start account-content -->	
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
			<!--  end account-content -->
		
		</div>
		<!-- end nav-right -->


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
				<li class="sub_show"><a href="#nogo">Fichier Global</a></li>
				<li class="#nogo"><a href="#nogo">Repertoire des experts</a></li>
				<li class="#nogo"><a href="#nogo">Representants des Maitres d'ouvrage</a></li>
                <li class="#nogo"><a href="sample105.php">Ajouter une personne</a></li>
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
				<li><a href="#nogo">Clients Details 1</a></li>
				<li><a href="#nogo">Clients Details 2</a></li>
				<li><a href="#nogo">Clients Details 3</a></li>
			 
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
 
<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
<!-- start content -->
<div id="content">

	<!--  start page-heading -->
	<div id="page-heading">
		<h1>Fichier global</h1>
	</div>
	<!-- end page-heading -->

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
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content">
		
		 
				<!--  start product-table ..................................................................................... -->
				<form id="mainform" action="">
				<table border="0" width="100%" cellpadding="0" cellspacing="0" id="product-table">
				<tr>
					<th class="table-header-check"><a id="toggle-all" ></a> </th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">Nom et Prenom</a>	</th>
					<th class="table-header-repeat line-left minwidth-1"><a href="">RIB</a></th>
					<th class="table-header-repeat line-left"><a href="">Email</a></th>
					<th class="table-header-repeat line-left"><a href="">Matricule</a></th>
					<th class="table-header-repeat line-left"><a href="">Adresse</a></th>
					<th class="table-header-options line-left"><a href="">Options</a></th>
				</tr>
				<?php $counter=0; do  { $counter++; ?>
    			<tr bgcolor="<?php echo alter_color($counter, "#eee", "#FFF"); ?>">
					<td><input  type="checkbox"/></td>
					<td><a href="#" onclick="<?php popup($showGoToPersonnes2, "700", "200"); ?>">
					  <?php if (isset($_GET['txtSearch']) && $_GET['txtSearch'] != "" && ((isset($_GET['txtChamp']) && $_GET['txtChamp'] == "personne_nom") || (isset($_GET['txtChamp']) && $_GET['txtChamp'] == "personne_prenom"))) {echo str_ireplace($_GET['txtSearch'],'<span style="background-color:yellow">'. strtoupper($_GET['txtSearch']) .'</span>',strtoupper(htmlentities($row_rsAffichePerson['personne_nom'] . " " . ucfirst($row_rsAffichePerson['personne_prenom']))));
	  } else { echo strtoupper(htmlentities($row_rsAffichePerson['personne_nom'] . " " . ucfirst($row_rsAffichePerson['personne_prenom'])));} ?>
&nbsp;</a></td>
					<td><a href="#" onclick="<?php popup($showGoToPersonnes1, "700", "400"); ?>"><?php $user_rib = MinmapDB::getInstance()->get_user_rib_by_id($row_rsAffichePerson['personne_id']);
	  if (isset($user_rib) && $user_rib <> 'xxxxxxxxxxxxxxxxxxxxxxx'){
		  echo $user_rib; }else{ echo 'Cliquez pour saisir le RIB'; } ?></td>
					<td>&nbsp;</td>
					<td><?php if (isset($row_rsAffichePerson['personne_matricule']) && $row_rsAffichePerson['personne_matricule'] != "XXXXXX-X") {echo $row_rsAffichePerson['personne_matricule']; } else {echo 'Absent'; } ?></td>
					<td>&nbsp;</td>
					<td class="options-width">
					<a href="" title="Edit" class="icon-1 info-tooltip"></a>
					<a href="del_personnes.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&menuID=<?php echo $_GET['menuID']; ?>" onClick="return confirm('Etes vous sur de vouloir supprimer cet enregistrement?');" title="Edit" class="icon-2 info-tooltip"></a>
                    <?php if (isset($_SESSION['MM_UserGroup']) && $_SESSION['MM_UserGroup'] == '1'){ ?>
      				<a href="maj_personnes.php?recordID=<?php echo $row_rsAffichePerson['personne_id']; ?>&&map=personnes&menuID=<?php echo $_GET['menuID']; ?>" title="Edit" class="icon-5 info-tooltip"></a><?php } ?>
					</td>
				</tr>
                <?php } while ($row_rsAffichePerson = mysql_fetch_assoc($rsAffichePerson)); ?>
				</table>
				<!--  end product-table................................... --> 
				</form>
			</div>
			<!--  end content-table  -->
		
			<!--  start actions-box ............................................... -->
			<div id="actions-box">
				<a href="" class="action-slider"></a>
				<div id="actions-box-slider">
					<a href="" class="action-edit">Edit</a>
					<a href="" class="action-delete">Delete</a>
				</div>
				<div class="clear"></div>
			</div>
			<!-- end actions-box........... -->
			
			<!--  start paging..................................................... -->
			<table border="0" cellpadding="0" cellspacing="0" id="paging-table">
			<tr>
			<td>
				<?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, 0, $queryString_rsAffichePerson); ?>" class="page-far-left"></a>
      <?php } // Show if not first page ?>
				<?php if ($pageNum_rsAffichePerson > 0) { // Show if not first page ?>
      <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, max(0, $pageNum_rsAffichePerson - 1), $queryString_rsAffichePerson); ?>" class="page-left"></a>
      <?php } // Show if not first page ?>
				<div id="page-info">Page <strong><?php echo ($startRow_rsAffichePerson + 1) ?> à <?php echo min($startRow_rsAffichePerson + $maxRows_rsAffichePerson, $totalRows_rsAffichePerson) ?></strong> / <?php echo $totalRows_rsAffichePerson ?></div>
                <?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, min($totalPages_rsAffichePerson, $pageNum_rsAffichePerson + 1), $queryString_rsAffichePerson); ?>" class="page-right"></a><?php } // Show if not last page ?>
				<?php if ($pageNum_rsAffichePerson < $totalPages_rsAffichePerson) { // Show if not last page ?>
      <a href="<?php printf("%s?pageNum_rsAffichePerson=%d%s", $currentPage, $totalPages_rsAffichePerson, $queryString_rsAffichePerson); ?>" class="page-far-right"></a>
      <?php } // Show if not last page ?>
			</td>
			<td>
			<select  class="styledselect_pages">
				<option value="">Number of rows</option>
				<option value="">1</option>
				<option value="">2</option>
				<option value="">3</option>
			</select>
			</td>
			</tr>
			</table>
			<!--  end paging................ -->
			
			<div class="clear"></div>
		 
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>
	<div class="clear">&nbsp;</div>

</div>
<!--  end content -->
<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->

<div class="clear">&nbsp;</div>
    
<!-- start footer -->         
<div id="footer">
	<!--  start footer-left -->
	<div id="footer-left">
	
	Admin Skin &copy; Copyright Internet Dreams Ltd. <span id="spanYear"></span> <a href="">www.netdreams.co.uk</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
</html>