<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Jeu.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/connectionBDD.php');

// Fonction qui retourne la liste des jeux disponibles
function getListJeux() {
	$listJeux = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id , j.nom
		FROM er_jeu j");
		
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = new Jeu($lignes->nom); 
		$jeu->id = $lignes->id;
	
		$listJeux[] = $jeu;
	}
		
	return $listJeux;
}

// Fonction qui retourne le jeu correspondant à l'identifiant donné
function getJeuById($idJeu) {
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id , j.nom
		FROM er_jeu j
		WHERE j.id = :idJeu");
	
	$sqlQuery->execute(array('idJeu' => $idJeu));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le jeu associé
		$jeu = new Jeu($lignes->nom); 
		$jeu->id = $lignes->id;
	}

	return $jeu;
}

// Fonction qui supprime un jeu ayant l'identifiant donné
function deleteJeu($idJeu){
	// On récupère le jeu à supprimer
	$jeu = getJeuById($idJeu);
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE from er_jeu where id = :idJeu");
	
	$resultat = $sqlQuery->execute(array('idJeu' => $idJeu));
	
	return $resultat;
}

// Permet d'enregistrer un jeu
function saveJeu($jeu) {
	$idJeu = null;
	if(isset($jeu->id) ){
		$idJeu = $jeu->id;
	}

	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_jeu (id, nom) values (:idJeu, :nomJeu) 
	ON DUPLICATE KEY UPDATE 
	nom = :nomJeu");
	
	$resultat = $sqlQuery->execute(array('idJeu' => $idJeu, 'nomJeu' => $jeu->nom));
	
	if($resultat) {
		// Si tout c'est bien passé on commit
		$connexionBDD->commit();
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}
	return $resultat;
}

?>