<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/connectionBDD.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Joueur.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Droit.php');

function getUserId() {
	try {
		$userId = "";
		foreach ($_COOKIE as $key => $value) {
			if (substr($key, 0, 7) == "phpbb3_" && substr($key, -2) == "_u") {
				$userId = $value;
				break;
			}
		}
		return $userId;
	}  catch (Exception $e) {
		throw $e;
	}
}

// Méthode qui récupère les informations d'un utilisateur via son identifiant
function getInfosUserEndless($user_id) {
	try {
		$connexionBDD = getConnexionBDD();
		$sqlQuery = $connexionBDD->prepare("SELECT u.username as nom FROM phpbb_users u where u.user_id = :userId");
		$sqlQuery->execute(array('userId' => $user_id));
		while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
		{
			$userName = $lignes->nom;
		}
	
		return $userName;
	}  catch (Exception $e) {
		throw $e;
	}
}

function addUserWithId($id) {
	try {
		$login = getInfosUserEndless($id);

			$connexionBDD = getConnexionBDD();
			$sqlQuery = $connexionBDD->prepare("INSERT INTO er_joueur (id, nom, idDroit) VALUES (:id, :nom, :idDroit)");
			
			$resultat = $sqlQuery->execute(array(
					'id' => $id,
					'nom' => $login,
					'idDroit' => 1
			));
			
			return $resultat;

	}  catch (Exception $e) {
		throw $e;
	}
}

// Fonction qui ramène un utilisateur selon son login
function getUserByName($nom) {
	try {
		$connexionBDD = getConnexionBDD();
		$sqlQuery = $connexionBDD->prepare("SELECT j.id, j.nom as nomJoueur, j.idDroit, d.nom as nomDroit FROM er_joueur j, er_droit d WHERE nom = :nomUser AND j.idDroit = d.id");
		
		$sqlQuery->execute(array('nomUser' => $nom));
		while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
	    {
			$droit = new Droit($lignes->idDroit, $lignes->nomDroit);
			$joueur = new Joueur($lignes->id, $lignes->nomJoueur, $droit);
	    } 
	
		return $joueur;
	}  catch (Exception $e) {
		throw $e;
	}
}

// Fonction qui retourne un utilisateur complet selon son identifiant
function getUserById($id) {
	try {
		// on créé la requête SQL
		$connexionBDD = getConnexionBDD();
		$sqlQuery = $connexionBDD->prepare("SELECT j.id, j.nom as nomJoueur, j.idDroit, d.nom as nomDroit FROM er_joueur j, er_droit d WHERE j.id = :idUser AND j.idDroit = d.id");
		$sqlQuery->execute(array('idUser' => $id));
		while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
	    {
			$droit = new Droit($lignes->idDroit, $lignes->nomDroit);
			$joueur = new Joueur($lignes->id, $lignes->nomJoueur, $droit);
	    } 
		
		return $joueur;
	}  catch (Exception $e) {
		throw $e;
	}
}

// fonction qui récupère tous les utilisateurs/joueurs
function getAllJoueurs() {
	$listeJoueurs = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT j.id, j.nom as nomJoueur, j.idDroit, d.nom as nomDroit FROM er_joueur j, er_droit d");
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$droit = new Droit($lignes->idDroit, $lignes->nomDroit);
		$joueur = new Joueur($lignes->id, $lignes->nomJoueur, $droit);
		
		// On ajoute l'utilisateur à la liste
		$listeJoueurs[] = $joueur;
    } 
	
	return $listeJoueurs;
}

function updateRoleUser($role, $idUser) {

	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("UPDATE er_joueur set idDroit = :role where id = :idUser");

	$resultat = $sqlQuery->execute(array('idUser' => $idUser, 'role' => $role));

	return $resultat;
}

function getAllRoles() {
	$listeRoles = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, nom FROM er_droit");
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$droit = new Droit($lignes->id, $lignes->nom);
		
		// On ajoute l'utilisateur à la liste
		$listeRoles[] = $droit;
    } 
	
	return $listeRoles;
}

?>