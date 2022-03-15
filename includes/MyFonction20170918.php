<?php 

function SurligneTXT($keyword, $texte){
 return str_replace($keyword, '<span style="color:green;"><b>'.$keyword.'</b></span>', $texte);
}

function GetValueMonth($theValue) 
{
   switch ($theValue) {
    case 1:
      $theValue = '01';
      break;
    case 01:
      $theValue = '01';
      break;
    case 02:
      $theValue = '02';
      break;
    case 2:
      $theValue = '02';
      break; 
    case 3:
      $theValue = '03';
      break; 
    case 4:
      $theValue = '04';
      break; 
    case 5:
      $theValue = '05';
      break; 
    case 6:
      $theValue = '06';
      break; 
    case 7:
      $theValue = '07';
      break; 
    case 8:
      $theValue = '08';
      break;
    case 9:
      $theValue = '09';
      break;
    case 10:
      $theValue = '10';
      break;
    case 11:
      $theValue = '11';
      break;
    case 12:
      $theValue = '12';
      break;
  }
  return $theValue;
}
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
function GetSomme($count, $somme2, $value) 
{
   switch ($count) {
    case $count:
	  $somme = 0;
      $somme = $somme + $value;
      break;   
  }
  return $somme;
}

function GetValueList($theValue) 
{
switch ($theValue){
case 01 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 02 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28');break;
case 03 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 04 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 05 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 06 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 07 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 8 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 9 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 10 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
case 11 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30');break;
case 12 : 
$list = array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');break;
}
  return $list;
}

function GetMontantValue($theCommission_type_id)  
{

  switch ($theCommission_type_id) {
    case 1:
		  $montant = 100000;  
      break;    
    case 2:
		  $montant = 75000;  
      break;
    case 3:
		  $montant = 75000;  
      break;   
    case 4:
		  $montant = 75000;  
      break;
    default:
		  $montant = 75000;  
      break;
  }
  return $montant;
}
function GetMontantMembre($typeCommission, $fonctionMembre)
{
	switch ($typeCommission){
		case 1:
		  switch ($fonctionMembre) {
				case 1:
					  $montant = 150000;  
				  break;
				case 2:
					  $montant = 80000;  
				  break;
				default:
					  $montant = 100000;  
				  break;
			  }
		break;
		case 2:
    	case 3:
    	case 4:
		   switch ($fonctionMembre) {
				case 1:
					  $montant = 100000;  
				  break;
				case 2:
					  $montant = 50000;  
				  break;
				default:
					  $montant = 75000;  
				  break;
			  }
         break;
		 case 6:
		    switch ($fonctionMembre) {
				case 1:
					  $montant = 100000;  
				  break;
				case 2:
					  $montant = 80000;  
				  break;
				default:
					  $montant = 75000;  
				  break;
			  }
        break;
	}
  return $montant;
}


function GetMontantIndemnite($typeCommission_id){
 switch ($typeCommission_id) {
    case 1:
      $montant_indemnite = 600000;
      break;
	case 2:
      $montant_indemnite = 600000;
      break;
	case 3:
      $montant_indemnite = 150000;
      break;
	case 4:
      $montant_indemnite = 150000;
      break;
	case 5:
      $montant_indemnite = 150000;
      break;
	default :
      $montant_indemnite = 0;
      break;
  }
  return $montant_indemnite;
}
 // trouver le montant par commission
 

function GetMontantFonction($theCommission_type_id, $thefonction_id )  
{

  switch ($theCommission_type_id) {
    case 1:
	  if ($thefonction_id == 1) {
		  $montant = 150000;
	  } elseif ($thefonction_id == 2) {
		  $montant = 80000;
	  } else {
		  
		  $montant = 100000;  
	  }
      break;    
    case 2:
    case 3:
    case 4:
	  if ($thefonction_id == 1) {
		  $montant = 100000;
	  } elseif ($thefonction_id == 2) {
		  $montant = 50000;
	  } else {
		  $montant = 75000;  
	  }
      break;
    case 6:
	  if ($thefonction_id == 1) {
		  $montant = 100000;
	  } elseif ($thefonction_id == 2) {
		  $montant = 80000;
	  } else {
		  $montant = 75000;  
	  }
      break;
  }
  return $montant;
}

function GetMontantMembreSousCommission($theCommission_type_id, $thefonction_id, $nbre_offre, $taux )  
{

  switch ($theCommission_type_id) {
	case 1:
			if ($taux<500000000){
				if ($nbre_offre <=10){
				  if ($thefonction_id == 1) {
					  $montant = 125000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 110000;
				  } else {
					  
					  $montant = 100000;  
				  }
				} elseif ($nbre_offre >10){
				  if ($thefonction_id == 1) {
					  $montant = 175000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 150000;
				  } else {
					  
					  $montant = 100000;  
				  }
				}
			} elseif ((500000000<$taux) && ($taux<5000000000)) {
				if ($nbre_offre<=5){
				  if ($thefonction_id == 1) {
					  $montant = 200000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 175000;
				  } else {
					  $montant = 150000;  
				  }
				} elseif ($nbre_offre>5){
				  if ($thefonction_id == 1) {
					  $montant = 225000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 200000;
				  } else {
					  $montant = 175000;  
				  }
				}
			} elseif ((5000000000<$taux) && ($taux<=20000000000)) {
				if ($nbre_offre<=3){
				  if ($thefonction_id == 1) {
					  $montant = 450000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 400000;
				  } else {
					  $montant = 350000;  
				  }
				}
				elseif ($nbre_offre>3){
				  if ($thefonction_id == 1) {
					  $montant = 550000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 500000;
				  } else {
					  $montant = 450000;  
				  }
				}
			} elseif ($taux>20000000000) {
				if ($nbre_offre<=2){
				  if ($thefonction_id == 1) {
					  $montant = 750000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 700000;
				  } else {
					  $montant = 650000;  
				  }
				} elseif ($nbre_offre>2){
				  if ($thefonction_id == 1) {
					  $montant = 850000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 800000;
				  } else {
					  $montant = 750000;  
				  }
				}
			}
	break;
	case 2 :
	case 4 :
	case 141 :
		  if ($thefonction_id == 1) {
			  $montant = 100000;
		  } elseif ($thefonction_id == 141) {
			  $montant = 80000;
		  } else {
			  
			  $montant = 75000;  
		  }
	break;
	case 3 :
	  if ($taux<500000000) {
		if ($nombreoffre <=10){
				  if ($thefonction_id == 1) {
					  $montant = 125000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 110000;
				  } else {
					  $montant = 100000;  
				  }			
		 }
		elseif ($nombreoffre >10){
				  if ($thefonction_id == 1) {
					  $montant = 175000;
				  } elseif ($thefonction_id == 141) {
					  $montant = 150000;
				  } else {
					  $montant = 125000;  
				  }
		 }
	  }
	  
	break;

  }
  return $montant;
}

?>