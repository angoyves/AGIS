<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
 
     <title>Fancy Sliding Form with jQuery</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta name="description" content="Fancy Sliding Form with jQuery" />
        <meta name="keywords" content="jquery, form, sliding, usability, css3, validation, javascript"/>
        <link rel="stylesheet" href="../../css/style.css" type="text/css" media="screen"/>
		  <link rel="stylesheet" type="text/css" media="all" href="fancybox/jquery.fancybox.css">
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script type="text/javascript" src="C:/Program Files (x86)/EasyPHP-5.3.9/www/gestion/JS/sliding.form.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <script type="text/javascript" src="fancybox/jquery.fancybox.js?v=2.0.6"></script>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1" />
 
        <style type="text/css">
            body{
                margin: 0px;
                text-align:center;
                background-color:#ebe9d7;
                font-family:Arial, Sans-Serif;
                font-size:0.75em;
            }
            .box 
            {
            	margin: 0px auto;
            	width:960px;
            	background-color:#ffffff;
            	text-align:left;
                position: relative;
            }
            .header
            {
            	height:100px;
            }
            .header h1
            {
            	margin:0px;
            	padding:30px;
            }
            h2 {color: #99723b; font-size: 16px; font-family: "Lucida Fax", Georgia, Times, "Times New Roman", Helvetica, Verdana; margin:0; padding:0; font-weight:normal;}
            .menu
            {
            	height:30px;
            	border-top:solid 1px #dccfbb;
            	border-bottom:solid 1px #dccfbb;
            	background-color:#4d3a24;
            }
            .menu div
            {
            	padding:10px 30px;
            }
            .menu a
            {
            	margin:0px 10px;
            	color:#fecd28;
            	text-decoration: none;
            }
            .content
            {
             	padding:10px 30px;
            }
            #contactFormContainer
            {
	position: absolute;
	left: 308px;
	float: right;
	top: 297px;
            }
            #contactForm
            {
            	height:277px;
            	width:351px;
            	background-image: url(../bkg.jpg);
            	display:none;
            }
            #contactForm fieldset
            {
            	padding:30px;
            	border:none;
            }      
            #contactForm label
            {
            	display:block;
            	color:#ffc400;
            }      
            #contactForm input[type=text]
            {
            	display:block;
            	border:solid 1px #4d3a24;
            	width:100%;
            	margin-bottom:10px;
            	height:24px;
            }  
            #contactForm textarea
            {
            	display:block;
            	border:solid 1px #4d3a24;
            	width:100%;
            	margin-bottom:10px;
            }  
            #contactForm input[type=submit]
            {
            	background-color:#4d3a24;
            	border:solid 1px #23150c;
            	color:#fecd28;
            	padding:5px;
            }                
            #contactLink
            {
            	height:40px;
            	width:351px;
            	background-image: url(../slidein_button.png);
            	display:block;
            	cursor:pointer;
            }
            #messageSent
            {
            	color:#ff9933;
            	display:none;
            }
            a {color: #62791d; text-decoration:none;}
.grey {color: #999999; text-decoration:none;}
a:hover {text-decoration: none; text-decoration:underline;}
a.img:hover {background-color: #ffffff;}
.Style3 {
	font-size: 16px;
	color: #000000;
	font-weight: bold;
}
        .Style4 {color: #FFFFFF}
        .Style5 {font-weight: bold}
.Style9 {
	font-size: 24px;
	font-weight: bold;
	color: #000000;
}
 
        .Style6 {font-size: 16px; color: #FFFFFF; font-weight: bold; }
.Style8 {	font-size: 16px;
	font-weight: bold;
}
a { color: #3a51b2; text-decoration: none; }
a:hover { text-decoration: underline; }
 
h2 { font-size: 1.8em; line-height: 1.9em; margin-bottom: 15px;  }
 
p { color: #656565; font-size: 1.2em; margin-bottom: 10px; }
 
#wrapper { width: 50px; margin: 0 auto; background-color:#4d3a24;}
 
#inline { display: none; width: 600px; }
 
label { margin-right: 12px; margin-bottom: 9px; font-family: Georgia, serif; color: #646464; font-size: 1.2em; }
 
.txt { 
display: inline-block; 
color: #676767;
width: 420px; 
font-family: Arial, Tahoma, sans-serif; 
margin-bottom: 10px; 
border: 1px dotted #ccc; 
padding: 5px 9px;
font-size: 1.2em;
line-height: 1.4em;
}
 
        </style>
          <if lte IE 7]>
<script type="text/javascript" src="../../JS/menu.js"></script> 
<!-- le  script qui affiche le pop up et envoie vers la page de traitement du formulaire pop up     -->
<script type="text/javascript">
 
 
	$(document).ready(function() {
		$(".modalbox").fancybox();
		$("#contact").submit(function() { return false; });
        $("#send").on("click", function(){
			$.ajax({
					type: 'POST',
					url: 'sendmessage.php',
					data: $("#contact").serialize(),
					success: function(data) {
						if(data == "true") {
							$("#contact").fadeOut("fast", function(){
								$(this).before("<p><strong>Success! Your feedback has been sent, thanks :)</strong></p>");
 
 
							});
						}
					}
				});
 
		});
}
 
	);
 
</script>
 
 
 
    </head>
<body >
<div class="box">
  <div class="header">
            <table width="958" height="101">
              <tr>
                <th width="128" scope="col"><img src="../../images/cour%20des%20comptes.jpg"" width="130" height="100" /></th>
                <th width="687" scope="col"> <p><img src="../../images/imagesCAQNJTFB.jpg" width="657" height="100" /></p></th>
                <th width="127" scope="col"><img src="../../images/imagesCAW3N2NJ.jpg" width="129" height="100" /></th>
              </tr>
            </table>
  </div>
  <div id="menu1">
	  <ul class="niveau1">
	    <li>
	      <a href="#">Fichier</a>
	      <ul class="niveau2">
	        <li><a href="personnelle.php">Personnelle</a></li>
      <li><a href="lieu.php">Lieux</a></li>
      <li><a href="langue.php">Langues</a></li>
        <li><a href="diplome.php">Diplomes</a></li>
      <li><a href="nationalite.php">Nationalit?s</a></li>
      <li><a href="ss.php">Caisse SS</a></li>
       <li><a href="mutuelle.php">Mutuelles</a></li>
        <li><a href="affectation.php">Affectations</a></li>
      <li><a href="voie.php">Voie</a></li>
      <li><a href="">Type</a>
	          <ul class="niveau3">
	           <li><a href="typeabscence.php">Type Abscences</a>
               <li><a href="typeconge.php">Type Cong?</a></li>
               <li><a href="typesanction.php">Type Sanctions</a></li>
               <li><a href="typecorp.php">Type Corp</a></li>
	          </ul>
	        </li>
	         <li><a href="#">Detail</a>
              <ul class="niveau3">
	          <li><a href="detailcop.php">Corp</a></li>
       <li><a href="detailgrade.php">Grade</a></li>
	          </ul>
 
 
             </li>
	      </ul>
	    </li>
	    <li>
	      <a href="">Traitement</a>
	      <ul class="niveau2">
	        <li><a href="../traitement/carriere.php">Carrieres</a></li>
             <li><a href="../traitement/grade.php">Grades</a></li>
             <li><a href="../traitement/structure.php">Structure(Affectation)</a></li>
             <li><a href="../traitement/conge.php">Cong?s</a></li>
             <li><a href="../traitement/absence.php">Absences</a></li>
             <li><a href="../traitement/sanction.php">Sanctions</a></li>
 
 
	      </ul>
	    </li>
	    <li>
	      <a href="#">Generateur de decisions</a>
	      <ul class="niveau2">
	        <li><a href="../generateur/conception.php">conception</a></li>       
	        <li><a href="../generateur/edition.php">edition</a></li>
	      </ul>
	    </li>
          <ul class="niveau1">
	    <li>
	      <a href="#">Les Etats</a>
	      <ul class="niveau2">
      <li><a href="#">Etat</a>
	          <ul class="niveau3">
	           <li><a href="../etat/etatindiv.php">Etat individuelle</a>
               <li><a href="../etat/etatcollect.php">Etat Collectif</a></li>
               <li><a href="../etat/etattable.php">Etat Table</a></li>
               <li><a href="../etat/etatreliquat.php">Etat Reliquat</a></li>
                 <li><a href="../etat/etatnominatifg.php">Etat nominatif global</a></li>
               <li><a href="../etat/etatstatistique.php">Etat Statistique</a></li>
	          </ul>
	        </li>
	    <li><a href="#">Tableau</a>
              <ul class="niveau3">
	          <li><a href="../etat/tabavance.php">Tableau avancement</a></li>
       <li><a href="../etat/tabconfirm.php">Tableau confirmation</a></li>
	        </ul>
	        </li>
               <li><a href="../etat/effectifbudge.php">Effectif budgetaire</a></li>
               <li><a href="../etat/decompteposte.php">Decompte poste</a></li>
 
          </ul>
 
 
            </li>
	      </ul>
	    </li>
 
  </ul>
  </div>
 
          <div class="content">
                <div id="lipsum">
                    <h2>&nbsp;</h2>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <p>&nbsp;</p>
                    <form name="form1" id="form1" method="post" action="../traitement/fichier/tcorp.php">
                      <table width="714" height="103" border="0" align="center"  bgcolor="#4D3A24">
                        <tr>
                          <td height="51" colspan="2"><span class="Style6">Code
                                <input name="code" type="text" id="code" />
                          </span></td>
                          <td width="388"><span class="Style8">Type de corp
                            <select name="select">
 
							<?php 
 
  include('../../connexion.php'); 
 $sql="SELECT libelle FROM lieu "; 
 
 $req=mysql_query($sql); 
 
while($res=mysql_fetch_array($req)){ 
 echo"  <option>$res[0]  <option>";
} 
 
   mysql_close($connection); 
  ?>
 
                         </select>
 
							<!-- voici le lien     autre -->
 
                          </span>
                            <div id="wrapper">
                              <span class="Style8"><a class="modalbox" href="#inline">Autre</a></span>
                          </div>
 
						  <!-- voici la fin du  lien     autre -->
 
 
 
						  </td>
                        </tr>
                        <input name="code_encien" type="hidden" id="code_encien"  />
                        <input name="libelle_encien" type="hidden" id="libelle_encien"   />
                        <input name="libelleA_encien" type="hidden" id="libelleA_encien"   />
                        <tr>
                          <td width="100" height="46"><input type="submit" name="Submit2" value="Modifier" /></td>
                          <td width="212" ><input type="reset" name="Submit3" value="Annuller" /></td>
                          <td>&nbsp;</td>
                        </tr>
                      </table>
                  </form>
 
 
	<!--                             le formulaire du pop up                              -->	  
      <div id="inline">
	             <h2>Send us a Message</h2>
 
	<form id="contact" name="contact" action="#" method="post">
		<label for="email">Your E-mail</label>
		<input type="text" id="email" name="email" class="txt">
		<br>
		<label for="msg">Enter a Message</label>
		<input type="text"  id="msg" name="msg" class="txt">
 
		<button id="send" onClick="window.opener.location.reload();">Send E-mail</button>
 
	</form>
      </div>
	 <!--                       voila fin du formulaire du pop up                                            -->
                    <p class="Style9">&nbsp;</p>
                    <p class="Style9"><a href="../../modification/type%20corp/modifier_tcorp.php">Modifier un corp </a></p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
                    <p class="Style5">&nbsp;</p>
  </div>
  </div>
</div>
</body>
</html>