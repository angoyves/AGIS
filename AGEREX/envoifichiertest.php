<?php
 
//
// Provient de la documentation PHP de la fonction ini_get()
//
function return_bytes($val) {
   $val = trim($val);
   $last = strtolower($val{strlen($val)-1});
   switch($last) {
       // The 'G' modifier is available since PHP 5.1.0
       case 'g':
           $val *= 1024;
       case 'm':
           $val *= 1024;
       case 'k':
           $val *= 1024;
   }
 
   return $val;
}
 
define('MAX_FILE_SIZE', return_bytes(ini_get('post_max_size')));
 
if(!empty($_FILES)){
    //
    // Debug
    //
    echo '<pre>';
    print_r($_FILES);
    echo '</pre>';
}
 
?>
 
 
<form method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data">
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
    <label>Fichier joint : <input type="file" name="file" /></label><br /><br />
 
    <input type="submit" value="Envoyer" />
    <input type="reset" value="Rétablir" />
</form>