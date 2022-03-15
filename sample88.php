<?php 
	require_once('Connections/MyFileConnect.php'); 
	require('fpdf/fpdf.php');
?>
<?php 
class PDF extends FPDF
{
// en-tête
function Header()
{
    //Police Arial gras 15
    $this->SetFont('Arial','B',14);
    //Décalage à droite
    $this->Cell(80);
    //Titre
    $this->Cell(30,10,'Mon joli fichier PDF',0,0,'C');
    //Saut de ligne
    $this->Ln(20);
}

// pied de page
function Footer()
{
    //Positionnement à 1,5 cm du bas
    $this->SetY(-15);
    //Police Arial italique 8
    $this->SetFont('Arial','I',8);
    //Numéro de page
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}
// création du pdf
$pdf=new PDF();
$pdf->SetAuthor('Un grand écrivain');
$pdf->SetTitle('Mon joli fichier');
$pdf->SetSubject('Un exemple de création de fichier PDF');
$pdf->SetCreator('fpdf_cybermonde_org');
$pdf->AliasNBPages();
$pdf->AddPage();

// requête
mysql_select_db($database_MyFileConnect, $MyFileConnect);
$query_rsUser = "SELECT * FROM users WHERE display = '1' ORDER BY user_id ASC";
$rsUser = mysql_query($query_rsUser, $MyFileConnect) or die(mysql_error());
$row_rsUser = mysql_fetch_array($rsUser);
$totalRows_rsUser = mysql_num_rows($rsUser);

//$sql = mysql_query("SELECT * FROM users ORDER BY user_id",$MyFileConnect);

// on boucle  
/*    while ($row = mysql_fetch_array($sql)) {
        $id = $row["user_id"];
        $titre = $row["user_name"];
        $description = $row["user_lastname"];
        // titre en gras
        $pdf->SetFont('Arial','B',10);
        $pdf->Write(5,$titre);
        $pdf->Ln();
        // description
        $pdf->SetFont('Arial','',10);
        $pdf->Write(3,$description);
        $pdf->Ln();
        $pdf->Ln();
    }*/
	while ($row_rsUser = mysql_fetch_array($rsUser)) {
      	$user_name = $row_rsUser['user_name'];
      	$user_lastname = $row_rsUser['user_lastname'];
      	$user_login = $row_rsUser['user_login'];
        // titre en gras
        $pdf->SetFont('Arial','B',10);
        $pdf->Write(5,$user_name);
        $pdf->Ln();
        // description
        $pdf->SetFont('Arial','',10);
        $pdf->Write(3,$user_lastname);
        $pdf->Ln();
        $pdf->Ln();
	} 

// sortie du fichier
$pdf->Output('monfichier.pdf', 'I');
?>