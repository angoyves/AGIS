<?php 
	  require_once('Connections/MyFileConnect.php'); 
?>
<?php
	  require_once('includes/db.php');
	  require('fonction_db.php');
	  require("src/inc/design.inc.php");
	  require("src/inc/biblio.inc.php");
	  require("src/inc/mysql_biblio.inc.php");
	  require("src/inc/db.php");
	  require('includes/MyFonction.php');
?>
<?php

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_rsMenu = 10;
$pageNum_rsMenu = 0;
if (isset($_GET['pageNum_rsMenu'])) {
  $pageNum_rsMenu = $_GET['pageNum_rsMenu'];
}
$startRow_rsMenu = $pageNum_rsMenu * $maxRows_rsMenu;

mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsMenu = "SELECT * FROM menus WHERE display = '2'";
$query_limit_rsMenu = sprintf("%s LIMIT %d, %d", $query_rsMenu, $startRow_rsMenu, $maxRows_rsMenu);
$rsMenu = mysql_query($query_limit_rsMenu, $MyFileConnect) or die(mysql_error());
$row_rsMenu = mysql_fetch_assoc($rsMenu);

if (isset($_GET['totalRows_rsMenu'])) {
  $totalRows_rsMenu = $_GET['totalRows_rsMenu'];
} else {
  $all_rsMenu = mysql_query($query_rsMenu);
  $totalRows_rsMenu = mysql_num_rows($all_rsMenu);
}
$totalPages_rsMenu = ceil($totalRows_rsMenu/$maxRows_rsMenu)-1;

$queryString_rsMenu = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsMenu") == false && 
        stristr($param, "totalRows_rsMenu") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsMenu = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsMenu = sprintf("&totalRows_rsMenu=%d%s", $totalRows_rsMenu, $queryString_rsMenu);
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
	<a href=""><img src="images/shared/logo.png" width="156" height="40" alt="" /></a>
	</div>
	<!-- end logo -->
	
	<!--  start top-search -->
	<div id="top-search">
		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
		<td><input type="text" value="Search" onblur="if (this.value=='') { this.value='Search'; }" onfocus="if (this.value=='Search') { this.value=''; }" class="top-search-inp" /></td>
		<td>
		<select  class="styledselect">
			<option value=""> All</option>
			<option value=""> Products</option>
			<option value=""> Categories</option>
			<option value="">Clients</option>
			<option value="">News</option>
		</select> 
		</td>
		<td>
		<input type="image" src="images/shared/top_search_btn.gif"  />
		</td>
		</tr>
		</table>
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
		<?php do { ?>
		<ul class="select"><li><a href="#nogo"><b><?php echo $row_rsMenu['menu_lib']; ?></b><!--[if IE 7]><!--></a><!--<![endif]-->
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
        <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
		                    
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
		<h1>Add product</h1>
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
			<h2>Sub Heading </h2>
			<h3>Local Heading</h3>
			<p>&nbsp;            
			<table border="1">
			  <tr>
			    <td>menu_id</td>
			    <td>menu_lib</td>
			    <td>user_groupe_id</td>
			    <td>lien</td>
			    <td>image</td>
			    <td>menu_description</td>
			    <td>display</td>
			    <td>dateCreation</td>
			    <td>dateUpdate</td>
			    </tr>
			  <?php do { ?>
			    <tr>
			      <td><a href="detailmenu.php?recordID=<?php echo $row_rsMenu['menu_id']; ?>"> <?php echo $row_rsMenu['menu_id']; ?>&nbsp; </a></td>
			      <td><?php echo $row_rsMenu['menu_lib']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['user_groupe_id']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['lien']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['image']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['menu_description']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['display']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['dateCreation']; ?>&nbsp; </td>
			      <td><?php echo $row_rsMenu['dateUpdate']; ?>&nbsp; </td>
			      </tr>
			    <?php } while ($row_rsMenu = mysql_fetch_assoc($rsMenu)); ?>
			  </table>
            <br />
            <table border="0">
              <tr>
                <td><?php if ($pageNum_rsMenu > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_rsMenu=%d%s", $currentPage, 0, $queryString_rsMenu); ?>">Premier</a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_rsMenu > 0) { // Show if not first page ?>
                    <a href="<?php printf("%s?pageNum_rsMenu=%d%s", $currentPage, max(0, $pageNum_rsMenu - 1), $queryString_rsMenu); ?>">Précédent</a>
                    <?php } // Show if not first page ?></td>
                <td><?php if ($pageNum_rsMenu < $totalPages_rsMenu) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_rsMenu=%d%s", $currentPage, min($totalPages_rsMenu, $pageNum_rsMenu + 1), $queryString_rsMenu); ?>">Suivant</a>
                    <?php } // Show if not last page ?></td>
                <td><?php if ($pageNum_rsMenu < $totalPages_rsMenu) { // Show if not last page ?>
                    <a href="<?php printf("%s?pageNum_rsMenu=%d%s", $currentPage, $totalPages_rsMenu, $queryString_rsMenu); ?>">Dernier</a>
                    <?php } // Show if not last page ?></td>
              </tr>
            </table>
Enregistrements <?php echo ($startRow_rsMenu + 1) ?> à <?php echo min($startRow_rsMenu + $maxRows_rsMenu, $totalRows_rsMenu) ?> sur <?php echo $totalRows_rsMenu ?>
</p>
			
			Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. 
			<br />
			<br />
			Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. 
			<br />
			<br />
			Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et Lorem ipsum dolor sit amet consectetur 
			adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  dolore magna aliqua. 
			
			
			</div>
			<!--  end table-content  -->
	
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
<!-- <div id="footer-pad">&nbsp;</div> -->
	<!--  start footer-left -->
	<div id="footer-left">
	Admin Skin &copy; Copyright Internet Dreams Ltd. <a href="">www.netdreams.co.uk</a>. All rights reserved.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->
 
</body>
</html>
<?php
mysql_free_result($rsMenu);
?>
