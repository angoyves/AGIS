<?php 
if (!function_exists("GetMontantFonction")) {
function GetMontantFonction($theCommission_id, $thefonction_id_id ) 
{

  switch ($theCommission_id) {
    case 1:
	  if ($thefonction_id_id == 1) {
		  $montant = 150000;
	  } elseif ($fonction_id == 2) {
		  $montant = 100000;
	  } else {
		  
		  $montant = 80000;  
	  }
      break;    
    case 2:
    case 3:
    case 4:
	  if ($thefonction_id == 1) {
		  $montant = 100000;
	  } elseif ($fonction == 2) {
		  $montant = 75000;
	  } else {
		  
		  $montant = 50000;  
	  }
      break;
  }
  return $montant;
}
}

?>