<?php

class MinmapDB extends mysqli {

    // single instance of self shared among all instances
    private static $instance = null;
    // db connection config vars
    private $user = "root";
    private $pass = "";
    private $dbName = "fichier_db8";
    private $dbHost = "localhost";
    private $con = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    // private constructor
    private function __construct() {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) {
            exit('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
        parent::set_charset('utf-8');
    }
// MOIS

    public function get_mois_lib($month) {
        $month = $this->query("SELECT lib_mois
									FROM mois
									WHERE mois_id = ". $month);

        if ($month->num_rows > 0){
            $row = $month->fetch_row();
            return $row[0];
        } else
            return null;
    }
	
// COMMENTAIRES
    public function get_nbre_commentaire_non_lu($userID) {
        $commission = $this->query("SELECT count(comment_id)
									FROM commentaires
									WHERE destinataire_id = ". $userID);

        if ($commission->num_rows > 0){
            $row = $commission->fetch_row();
            return $row[0];
        } else
            return null;
    }

//
// COMMISSIONS

    public function get_montant_commission($commissionID) {
        $commission = $this->query("SELECT sum(nombre_jour * montant) as total 
						FROM sessions, membres
						WHERE membres_commissions_commission_id = commissions_commission_id 
						AND membres_personnes_personne_id = personnes_personne_id
						AND mois between 01 AND 12
						AND annee = 2015 
						AND (membres_fonctions_fonction_id between 1 and 3 OR membres_fonctions_fonction_id = 40)
						AND membres_commissions_commission_id = "
                        . $commissionID);

        if ($commission->num_rows > 0){
            $row = $commission->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_montant_sous_commission($commissionID) {
        $commission = $this->query("SELECT sum(nombre_jour * montant) as total 
						FROM sessions, membres, commissions
						WHERE membres_commissions_commission_id = commissions_commission_id 
						AND membres_personnes_personne_id = personnes_personne_id
						AND membres_commissions_commission_id = commission_id
						AND mois between 01 AND 12
						AND annee = 2015 
						AND (membres_fonctions_fonction_id between 1 and 3 OR membres_fonctions_fonction_id = 141)
						AND commission_parent ="
                        . $commissionID);

        if ($commission->num_rows > 0){
            $row = $commission->fetch_row();
            return $row[0];
        } else
            return null;
    }
// FIN COMMISSIONS
    public function get_user_id_by_name($name) {
        $name = $this->real_escape_string($name);
        $domain = $this->query("SELECT user_id FROM users WHERE user_login = '"
                        . $name . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_user_groupe_by_name($name) {
        $name = $this->real_escape_string($name);
        $domain = $this->query("SELECT user_groupe_id FROM users WHERE user_login = '"
                        . $name . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_user_groupe_by_user_login($login) {
        $login = $this->real_escape_string($login);
        $wisher = $this->query("SELECT user_groupe_id FROM users WHERE user_login = '"
                        . $login . "'");

        if ($wisher->num_rows > 0){
            $row = $wisher->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_user_groupe_name_by_group_id($group_id){
        $domain = $this->query("SELECT user_groupe_lib FROM user_groupes WHERE user_groupe_id = "
                        . $group_id);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	
    public function get_user_rib_by_id($user_id) {
        //$name = $this->real_escape_string($name);
        $wisher = $this->query("SELECT concat(banque_code,agence_code, numero_compte, cle) FROM rib WHERE personne_id = "
                        . $user_id );

        if ($wisher->num_rows > 0){
            $row = $wisher->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_compteur_by_name($name) {
        $name = $this->real_escape_string($name);
        $wisher = $this->query("SELECT compteur FROM users WHERE user_login = '"
                        . $name . "'");

        if ($wisher->num_rows > 0){
            $row = $wisher->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_count_member_by_commission_id($commission_id) {
		$commission_id = $this->real_escape_string($commission_id);
        $domain = $this->query("SELECT count(personnes_personne_id) FROM membres
		WHERE commissions_commission_id = '". $commission_id . "'" );
        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_membre_fonction_by_personne_id($personne_id) {
        $domain = $this->query("SELECT fonctions_fonction_id FROM membres 
							   WHERE personnes_personne_id = ". $personne_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_person_id_by_name_and_cpte($firstname, $num_cpte) {
        $firstname = $this->real_escape_string($firstname);
		$num_cpte = $this->real_escape_string($num_cpte);
        $domain = $this->query("SELECT personnes.personne_id FROM personnes, rib WHERE personnes.personne_id = rib.personne_id 
							   AND personne_nom = '". $firstname . "' AND numero_compte = '". $num_cpte . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_localite_id_by_count_id($count, $value) {
        $domain = $this->query("SELECT localite_id FROM localites WHERE type_localite = ". $value . " AND localite_id = ". $count );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_commission_parent_id_by_commission_id($commission_id) {
        $domain = $this->query("SELECT commission_parent FROM commissions WHERE commission_id = " . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_type_id_by_commission_id($commission_id) {
        $domain = $this->query("SELECT type_commission_id FROM commissions WHERE commission_id = " . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	
	public function valid_membre_commission($commission_id) {
        $domain = $this->query("SELECT commission_id, commissions_commission_id, personne_nom, personne_id, personne_prenom, fonction_lib, checboxName, 
							   position, fonctions_fonction_id
							   FROM commissions, membres, fonctions, personnes
							   WHERE membres.commissions_commission_id = commissions.commission_id   
							   AND fonctions_fonction_id = fonctions.fonction_id   
							   AND personnes_personne_id = personne_id  
							   AND membres.fonctions_fonction_id BETWEEN 1 AND 3 
							   AND personnes.display = 1
							   AND personnes.add_commission = 2
							   AND commissions_commission_id = " . $commission_id );

        if ($domain->num_rows > 0){
            return 1;
        } else
            return 0;
    }
	
	public function get_structure_id_by_personne_id($personne_id) {
        $domain = $this->query("SELECT structure_id FROM personnes WHERE personne_id = " . $personne_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_jour_dossier_by_dossier_id($dossier_id) {
        $domain = $this->query("SELECT dossiers_jour FROM dossiers WHERE dossier_id = " . $dossier_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_structure_lib_by_structure_id($champ, $structure_id) {
		//$champ = $this->real_escape_string($champ);
        $domain = $this->query("SELECT code_structure FROM structures WHERE structure_id = " . $structure_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_dossier_ref_by_value($commission_id, $mois, $annee) {
		$mois = $this->real_escape_string($mois);
		$annee = $this->real_escape_string($annee);
        $domain = $this->query("SELECT dossier_ref FROM sessions, dossiers WHERE sessions.dossiers_dossier_id = dossiers.dossier_id 
						 AND membres_commissions_commission_id =" . $commission_id . " AND sessions.mois ='" . $mois . "' AND sessions.annee = '" . $annee . "'" );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_commission_lib_by_commission_id($commission_id) {
		//$champ = $this->real_escape_string($champ);
        $domain = $this->query("SELECT commission_lib FROM commission WHERE commission_id = " . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	
	public function get_structure_id_by_count_id($structure, $value) {
        $domain = $this->query("SELECT structure_id FROM structures WHERE structure_id = " . $structure . " AND minister = " . $value );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	
    public function get_wisher_id_by_name($firstname, $lastname) {
        $firstname = $this->real_escape_string($firstname);
		$lastname = $this->real_escape_string($lastname);
        $domain = $this->query("SELECT personne_id FROM personnes WHERE personne_nom = '"
                        . $firstname . "' AND personne_prenom = '"
                        . $lastname . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_user_group_id_by_user_id($user_id) {
        $domain = $this->query("SELECT user_groupe_id FROM users WHERE user_id = "
                        . $user_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_session_id_by_value($year, $month, $commission_id) {
        $year = $this->real_escape_string($year);
		$month = $this->real_escape_string($month);
        $domain = $this->query("SELECT count(membres_commissions_commission_id) as total FROM sessions WHERE mois = '" 
							   . $month . "' AND annee = '" 
							   . $year . "' AND membres_commissions_commission_id = " 
							   . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_count_id_by_localite_id($localite_id, $type_id, $nature_id) {
        $domain = $this->query("SELECT count(commission_id) FROM commissions WHERE localite_id = " 
							   . $localite_id . " AND type_commission_id = " . $type_id . " AND nature_id = " . $nature_id);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_count_sous_commission_by_commission_id($commission_id) {
        $domain = $this->query("SELECT count(commission_id) FROM commissions WHERE commission_parent = " . $commission_id);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_count_session_id_by_commission_id($commission_id) {
        $domain = $this->query("SELECT distinct(mois) FROM sessions WHERE membres_commissions_commission_id = " . $commission_id);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_count_sous_commission_id($commission_id) {
        $domain = $this->query("SELECT count(commission_id) FROM commissions WHERE commission_parent = " . $commission_id);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_nombre_jour_by_values($commission_id, $personne_id, $month, $year) {
		$year = $this->real_escape_string($year);
		//$month = $this->real_escape_string($month);
        $domain = $this->query("SELECT nombre_jour FROM sessions WHERE sessions.membres_personnes_personne_id = " . $personne_id 
							   . " AND sessions.membres_commissions_commission_id = " . $commission_id . " AND sessions.mois = " . $month 
							   . " AND sessions.annee = '" . $year 
							   . "' AND etat_validation = 2 ");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	    public function get_nombre_jour_sCom_by_values($commission_id, $personne_id, $month, $year) {
		$year = $this->real_escape_string($year);
		//$month = $this->real_escape_string($month);
        $domain = $this->query("SELECT count(sessions.Nombre_jour) FROM sessions, commissions 
								WHERE sessions.membres_commissions_commission_id = commissions.commission_id
								AND commissions.commission_parent = " . $commission_id 
								. " AND sessions.membres_personnes_personne_id = " . $personne_id 
								. " AND sessions.mois = " . $month 
								. " AND sessions.annee = '" . $year . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_somme_nombre_jour_by_values($commission_id, $personne_id, $month1, $month2, $year) {
		$year = $this->real_escape_string($year);
		//$month = $this->real_escape_string($month);
        $domain = $this->query("SELECT sum(nombre_jour) FROM sessions WHERE sessions.membres_personnes_personne_id = " . $personne_id 
							   . " AND sessions.membres_commissions_commission_id = " . $commission_id . " AND sessions.mois between " . $month1 
							   . " AND ". $month2 
							   .  "AND sessions.annee = '" . $year . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }

    public function get_localite_lib_by_localite_id($localite_id) {
        $domain = $this->query("SELECT localite_lib FROM localites WHERE localite_id = " 
							   . $localite_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_value_by_value_id($value_id) {		
        $domain = $this->query("SELECT structure_lib FROM structures WHERE structure_id = "
                        . $value_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_type_commission_lib_by_commission_id($type_commission_id) {
        $domain = $this->query("SELECT type_commission_lib FROM type_commissions WHERE type_commission_id = " 
							   . $type_commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_nature_lib_by_nature_id($nature_id) {
        $domain = $this->query("SELECT lib_nature FROM natures WHERE nature_id = " 
							   . $nature_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_personne_id_by_matricule($matricule) {
        $name = $this->real_escape_string($matricule);
        $domain = $this->query("SELECT personne_id FROM personnes WHERE personne_matricule = '"
                        . $matricule . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_personne_id_by_name_and_structure($name, $structure) {
        $name = $this->real_escape_string($name);
        $domain = $this->query("SELECT personne_id FROM personnes WHERE personne_nom = '"
                        . $name . "' AND structure_id = " . $structure );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	public function get_personne_name_by_person_id($personne_id) {
        $domain = $this->query("SELECT personne_nom, personne_prenom FROM personnes WHERE personne_id = "
                        . $personne_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0] . " " . $row[1];
        } else
            return null;
    }
	public function get_fonction_lib_by_fonction_id($fonction_id) {
        $domain = $this->query("SELECT fonction_lib FROM fonctions WHERE fonction_id = "
                        . $fonction_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_localite_id_by_commission_id($commission_id) {
        $domain = $this->query("SELECT localite_id FROM commissions WHERE commission_id = " . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_dossier_id_by_reference($reference) {
        $reference = $this->real_escape_string($reference);
        $domain = $this->query("SELECT dossier_id FROM dossiers WHERE dossier_ref = '"
                        . $reference . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_nombre_sous_commissions($commission_id) {
        $domain = $this->query("SELECT count(commission_id) FROM commissions WHERE commission_parent = "
                        . $commission_id );

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_value_id_by_value($value_id, $table, $search_value, $value) {
        $name = $this->real_escape_string($value);
        $domain = $this->query("SELECT " . $value_id . " FROM " . $table . " WHERE " . $search_value . " = '"
                        . $value . "'");

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	function afficher_etat_valide($comID, $mID, $aID) {
        $mID = $this->real_escape_string($mID);
		$aID = $this->real_escape_string($aID);
	    $domain = $this->query("SELECT etat_validation FROM sessions WHERE mois= '" . $mID 
							   . "' AND annee= '" . $aID 
							   . "' AND membres_commissions_commission_id = " . $comID);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
	function afficher_etat_control($comID, $mID, $aID) {
        $mID = $this->real_escape_string($mID);
		$aID = $this->real_escape_string($aID);
	    $domain = $this->query("SELECT userControlate FROM sessions WHERE mois= '" . $mID 
							   . "' AND annee= '" . $aID 
							   . "' AND membres_commissions_commission_id = " . $comID);

        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_wishes_by_wisher_id($wisherID) {
        return $this->query("SELECT id, description, due_date FROM wishes WHERE wisher_id=" . $wisherID);
    }
    public function get_month_by_month_id($monthID) {
		$monthID = $this->real_escape_string($monthID);
        $domain = $this->query("SELECT lib_mois FROM mois WHERE mois_id = '" . $monthID . "'" );
        if ($domain->num_rows > 0){
            $row = $domain->fetch_row();
            return $row[0];
        } else
            return null;
    }
    public function get_person_by_value($personne_matricule, $personne_nom) {
        return $this->query("SELECT person_id FROM personnes WHERE personne_matricule =" . $personne_matricule . " AND personne_nom like " . $personne_nom);
    }

    public function create_wisher($name, $password) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $this->query("INSERT INTO wishers (name, password) VALUES ('" . $name
                . "', '" . $password . "')");
    }

  /*  public function create_listing_action($UserId, $PageLink, $ModificationType, $DateCreation) {
        $PageLink = $this->real_escape_string($PageLink);
		$ModificationType = $this->real_escape_string($ModificationType);
        $this->query("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateCreation) VALUES (" . $UserId . ", '" 
				. $PageLink . "', '" 
				. $ModificationType . "', " 
				. $this->format_date_for_sql($DateCreation) . ")");
    }*/
    public function create_listing_action($UserId, $PageLink, $ModificationType, $DateCreation) {
        $PageLink = $this->real_escape_string($PageLink);
		$ModificationType = $this->real_escape_string($ModificationType);
        $this->query("INSERT INTO listing_action (UserId, PageLink, ModificationType, DateUpdate) VALUES (" . $UserId . ", '" 
				. $PageLink . "', '" 
				. $ModificationType . "', " 
				. $this->format_date_for_sql($DateCreation) . ")");
    }
    public function create_representant($commission_id, $dossier_id, $nombre_dossier, $count, $month, $year, $personne_id, $fonction_id, $variable, $DATE, $display) {		
        $month = $this->real_escape_string($month);
        $year = $this->real_escape_string($year);
        $variable = $this->real_escape_string($variable);		
        $display = $this->real_escape_string($display);			
        $this->query("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, jour, dateCreation, display) VALUES (" . $commission_id . ", " . $dossier_id . ", " . $nombre_dossier . ", " . $count . ", '" . $month . "', '" . $year . "', " . $personne_id . ", " . $fonction_id . ", '" . $variable . "', " . $this->format_date_for_sql($DATE) . ", '" . $display . "')");
    }
    public function verify_wisher_credentials($name, $password) {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $result = $this->query("SELECT 1 FROM users WHERE name = '"
                        . $name . "' AND password = '" . $password . "'");
        return $result->data_seek(0);
    }
    public function verify_session_credentials($commission, $month, $year) {
        $commission = $this->real_escape_string($commission);
        $month = $this->real_escape_string($month);
        $year = $this->real_escape_string($year);
        $result = $this->query("SELECT 1 FROM sessions WHERE membres_commissions_commission_id = '"
                        . $commission . "' AND mois = '" . $month . "' AND annee = '" . $year . "'");
        return $result->data_seek(0);
    }
    public function verify_commissions_representant($commission, $personne) {
        $result = $this->query("SELECT 1 FROM membres WHERE commissions_commission_id = "
                        . $commission . " AND personnes_personne_id = " . $personne );
        return $result->data_seek(0);
    }
    function insert_wish($wisherID, $description, $duedate) {
        $description = $this->real_escape_string($description);
        if ($this->format_date_for_sql($duedate)==null){
           $this->query("INSERT INTO wishes (wisher_id, description)" .
                " VALUES (" . $wisherID . ", '" . $description . "')");
        } else
        $this->query("INSERT INTO wishes (wisher_id, description, due_date)" .
                " VALUES (" . $wisherID . ", '" . $description . "', "
                . $this->format_date_for_sql($duedate) . ")");
    }
    
    function format_date_for_sql($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['year'] * 10000 + $dateParts['month'] * 100 + $dateParts['day'];
        }
    }
	

    public function update_wish($wishID, $description, $duedate) {
        $description = $this->real_escape_string($description);
        $this->query("UPDATE wishes SET description = '" . $description .
                "', due_date = " . $this->format_date_for_sql($duedate)
                . " WHERE id =" . $wishID);
    }

    public function update_users_compteur($compteur, $user_id, $duedate) {
        $this->query("UPDATE users SET compteur = " . $compteur
					 . ", dateUpdate = " . $this->format_date_for_sql($duedate)
					 . " WHERE user_id = " . $user_id);
    }
    public function update_session_person_day($nbre_jours, $jour, $duedate, $mois, $annee, $commission_id, $personne_id) { 
		$nbre_jours = $this->real_escape_string($nbre_jours);
		$jour = $this->real_escape_string($jour); 
		$mois = $this->real_escape_string($mois); 
		$annee = $this->real_escape_string($annee); 
        $this->query("UPDATE sessions SET nombre_jour = '" . $nbre_jours
					 . "', jour = '" . $jour
					 . "', dateUpdate = " . $this->format_date_for_sql($duedate). 
					 " WHERE membres_commissions_commission_id = " . $commission_id .
					 " AND mois = '" . $mois .
					 "' AND annee = '" . $annee .
					 "' AND membres_personnes_personne_id = " . $personne_id);
    }

    public function get_wish_by_wish_id($wishID) {
        return $this->query("SELECT id, description, due_date FROM wishes WHERE id = " . $wishID);
    }

    public function delete_wish($wishID) {
        $this->query("DELETE FROM wishes WHERE id = " . $wishID);
    }
	
	public function delete_record($table, $recordID) {
        $this->query("DELETE FROM '" . $table . "' WHERE id = " . $recordID);
    }

//------------------ personnes--------------
    function insert_personne($personne_matricule,$personne_nom,$personne_prenom,$personne_grade,$domaine_id,$personne_telephone,$structure_id,$sous_groupe_id,$fonction_id,$type_personne_id,$user_id,$dateCreation,$display) {
					   
		$personne_matricule = $this->real_escape_string($personne_matricule);
		$personne_nom = $this->real_escape_string($personne_nom);
		$personne_prenom = $this->real_escape_string($personne_prenom);
		$personne_grade = $this->real_escape_string($personne_grade);
		$personne_telephone = $this->real_escape_string($personne_telephone);
		$display = $this->real_escape_string($display);

        if ($this->format_date_for_sql($dateCreation)==null){
        $this->query("INSERT INTO personnes (personne_matricule, personne_nom, personne_prenom, personne_grade, domaine_id, personne_telephone, structure_id, sous_groupe_id, fonction_id, type_personne_id, user_id, display)" .
			 " VALUES ('" . $personne_matricule . "', '" . personne_nom . "', '" . personne_prenom . "', '" . personne_grade . "', " . domaine_id . ", '" 
						  . personne_telephone . "', " . structure_id . ", " . sous_groupe_id . ", " . fonction_id . ", " . type_personne_id . ", " 
						  . user_id . ", '" . display . "')");
        } else
        $this->query("INSERT INTO personnes (personne_matricule, personne_nom, personne_prenom, personne_grade, domaine_id, personne_telephone, structure_id, sous_groupe_id, fonction_id, type_personne_id, user_id, date_creation, display)" .
			 " VALUES ('" . $personne_matricule . "', '" . personne_nom . "', '" . personne_prenom . "', '" . personne_grade . "', " . domaine_id . ", '" 
					   . personne_telephone . "', " . structure_id . ", " . sous_groupe_id . ", " . fonction_id . ", " . type_personne_id . ", " 
					   . user_id . ", ". $this->format_date_for_sql($dateCreation) . ", '" . display . "')");

    }

    public function verify_personne_id($personne_id) {
        $result = $this->query("SELECT 1 FROM personnes WHERE personne_id = ". $personne_id );
        return $result->data_seek(0);
    }
//------------ Table RIB ----------------------
    function insert_rib($personne_id, $personne_matricule, $agence_cle, $banque_code, $agence_code, $numero_compte, $user_id, $cle, $dateCreation) {

					   $personne_matricule = $this->real_escape_string($personne_matricule);
					   $agence_cle = $this->real_escape_string($agence_cle);
                       $banque_code = $this->real_escape_string($banque_code);
                       $agence_code = $this->real_escape_string($agence_code);
                       $numero_compte = $this->real_escape_string($numero_compte);
                       $cle = $this->real_escape_string($cle);
					   

        if ($this->format_date_for_sql($dateCreation)==null){
        $this->query("INSERT INTO rib (personne_id, personne_matricule, agence_cle, banque_code, agence_code, numero_compte, user_id, cle)" . 
			   " VALUES (" . $personne_id . ", '" . $personne_matricule . "',  '" . $agence_cle . "',  '" . $banque_code . "',  '" . $agence_code . "',  '" 
						   . $numero_compte . "', " .$user_id . ",  '" . $cle . "')");
        } else
        $this->query("INSERT INTO rib (personne_id, personne_matricule, agence_cle, banque_code, agence_code, numero_compte, user_id, cle, date_creation)" . 
			   " VALUES (" . $personne_id . ", '" . $personne_matricule . "',  '" . $agence_cle . "',  '" . $banque_code . "',  '" . $agence_code . "',  '" 
						   . $numero_compte . "', " .$user_id . ",  '" . $cle . "', ". $this->format_date_for_sql($dateCreation) . ")");
					 

}

//-------------------- Table Session ----------------
    function insert_sessions($membres_commissions_commission_id, $dossiers_dossier_id, $nombre_dossier, $nombre_jour, $mois, $annee, $membres_personnes_personne_id, $membres_fonctions_fonction_id, $jour, $dateCreation, $user_id, $display) {
					   
					   $mois = $this->real_escape_string($personne_matricule);
					   $annee = $this->real_escape_string($personne_matricule);
					   $jour = $this->real_escape_string($personne_matricule);
					   $display = $this->real_escape_string($personne_matricule);

        if ($this->format_date_for_sql($dateCreation)==null){
        $this->query("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, $jour, user_id, display)" . 
		" VALUES (" . $membres_commissions_commission_id . ", " . $dossiers_dossier_id . ", " . $nombre_dossier . ", " . $nombre_jour . ", '" . $mois . 
				  "', '" . $annee . "', " . $membres_personnes_personne_id . ", " . $membres_fonctions_fonction_id . ", '" . $jour . 
				  "', " . $user_id . ", '" . $display . "')");
        } else
        $this->query("INSERT INTO sessions (membres_commissions_commission_id, dossiers_dossier_id, nombre_dossier, nombre_jour, mois, annee, membres_personnes_personne_id, membres_fonctions_fonction_id, $jour, dateCreation, user_id, display)" . 
		" VALUES (" . $membres_commissions_commission_id . ", " . $dossiers_dossier_id . ", " . $nombre_dossier . ", " . $nombre_jour . ", '" . $mois . 
				  "', '" . $annee . "', " . $membres_personnes_personne_id . ", " . $membres_fonctions_fonction_id . ", '" . $jour . 
				  "', ". $this->format_date_for_sql($dateCreation) . ", " . $user_id . ", '" . $display . "')");
    }

}

?>