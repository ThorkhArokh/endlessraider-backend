<?php
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/connectionBDD.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Evenement.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/TypeEvt.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Joueur.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/class/Droit.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/endlessraider-backend/persistance/jeuDao.php');

// Fonction qui retourne l'intégralité des événements
function getAllEvents() {
	$listEvent = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT
	id, 
	nom, 
	description, 
	DATE_FORMAT(e.dateDebut, '%d/%m/%Y') AS dateDebut, 
	DATE_FORMAT(e.dateDebut, '%H:%i') AS heureDebut,
	DATE_FORMAT(e.dateFin, '%d/%m/%Y') AS dateFin, 
	DATE_FORMAT(e.dateFin, '%H:%i') AS heureFin,
	idJeu,
	idTypeEvt, t.code as codeTypeEvt, t.nom as nomTypeEvt
	FROM er_event e, er_typejeu t 
	WHERE e.dateDebut > CURDATE() 
	AND e.idTypeEvt = t.id 
	ORDER BY e.dateDebut DESC");

	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		$typeEvt = new TypeJeu($lignes->idTypeEvt, $lignes->codeTypeEvt, $lignes->nomTypeEvt);
	
		$event = new Evenement($lignes->nom, $lignes->dateDebut);
		$event->id = $lignes->id;
		$event->jeu = getJeuById($lignes->idJeu);
		$event->heureDebut = $lignes->heureDebut;
		$event->dateFin = $lignes->dateFin;
		$event->heureFin = $lignes->heureFin;
		$event->desc = $lignes->description;
		$event->type = $typeEvt;
		$event->listParticipants = getListParticipants($lignes->id);
		
		$listEvent[] = $event;
	}
	
	return $listEvent;
}

// Fonction qui ramène les participants à un événement
function getListParticipants($idEvent) {
	$listParticipants = array();
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery =  $connexionBDD->prepare("SELECT 
	e.idJoueur, j.nom as nomJoueur, j.idDroit, d.nom as nomDroit
	FROM er_evtjoueur e, er_joueur j, er_droit d
	WHERE e.idJoueur = j.id AND j.idDroit = d.id AND e.idEvt = :idEvent");
	
	$sqlQuery->execute(array( 'idEvent' => $idEvent ));
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ)) {
		$droit = new Droit($lignes->idDroit, $lignes->nomDroit);
		$joueur = new Joueur($lignes->idJoueur, $lignes->nomJoueur, $droit);
		
		$listParticipants[] = $joueur;
	}
	
	return $listParticipants;
}

// Fonction qui récupère tous les types d'évent possible
function getAllTypesEvt() {
	$listTypeEvt = array();
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("SELECT id, code, nom
		FROM er_typeevt");
		
	$sqlQuery->execute();
	while($lignes=$sqlQuery->fetch(PDO::FETCH_OBJ))
    {
		// On créé le type d'évent
		$typeEvt = new TypeEvt($lignes->id, $lignes->code, $lignes->nom);
		$listTypeEvt[] = $typeEvt;
	}
	
	return $listTypeEvt;
}

// Fonction qui enregistre un événement
function saveEvent($event) {
	
	$idEvent = null;
	if(isset($event->id) ){
		$idEvent = $event->id;
	}
	
	$descEvent = null;
	if(isset($event->desc) ){
		$descEvent = $event->desc;
	}
		
	// Transcription de la date et de l'heure de début
	$tmp = explode("/", $event->dateDebut);
	$dateDebut_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
	if(isset($event->heureDebut)) {
		$dateDebut_iso = $dateDebut_iso.' '.$event->heureDebut;
	} else {
		$dateDebut_iso = $dateDebut_iso.' 00:00:00';
	}
	
	// Transcription de la date et de l'heure de fin
	$tmp = explode("/", $event->dateFin);
	$dateFin_iso = $tmp[2]."-".$tmp[1]."-".$tmp[0];
	if(isset($event->heureFin)) {
		$dateFin_iso = $dateFin_iso.' '.$event->heureFin;
	} else {
		$dateFin_iso = $dateFin_iso.' 00:00:00';
	}
	
	// on créé la requête SQL
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_event (
	id, 
	nom, 
	description, 
	dateDebut,
	dateFin
	idJeu,
	idTypeEvt) 
	values (:idEvent,
	:nomEvent, 
	:descEvent, 
	:dateDebutIso,
	:dateFinIso,
	:jeuId,
	:typeEvtId)
	ON DUPLICATE KEY UPDATE 
	nom = :nomEvent,
	description = :descEvent,
	dateDebut = :dateDebutIso,
	dateFin = :dateFinIso,
	idJeu = :jeuId,
	idTypeEvt = :typeEvtId");
		
	$resultat = $sqlQuery->execute(array( 
			'idEvent' => $idEvent,
			'nomEvent' => $event->nom,
			'descEvent' => $descEvent,
			'dateDebutIso' => $dateDebut_iso,
			'dateFinIso' => $dateFin_iso,
			'jeuId' => $event->jeu->id,
			'typeEvtId' => $event->type->id
		)
	);
	if($resultat) {
		// Si tout c'est bien passé on commit
		$connexionBDD->commit();
	} else {
		// Erreur on effectue un rollback
		$connexionBDD->rollBack();
	}
	
	return $resultat;
}

// Fonction qui permet d'inscrire un participant à un événement
function inscriptionEvent($idEvent, $idJoueur) {
	
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("INSERT INTO er_evtJoueur (
	idEvt,
	idJoueur)
	VALUES (
	:idEvt,
	:idJoueur)");
	
	$resultat = $sqlQuery->execute(array( 
			'idEvt' => $idEvent,
			'idJoueur' => $idJoueur
		)
	);
	
	return $resultat;
}

// Fonction qui permet de désinscrire un utilisateur d'un event
function desinscriptionEvent($idEvent, $idJoueur) {
	$connexionBDD = getConnexionBDD();
	$sqlQuery = $connexionBDD->prepare("DELETE FROM er_evtJoueur 
	WHERE idEvt = :idEvenement 
	AND idJoueur = :idJoueur");
	
	$resultat = $sqlQuery->execute(array( 
			'idEvenement' => $idEvent,
			'idJoueur' => $idJoueur
		)
	);
	
	return $resultat;
}

// Permet de supprimer un événement ainsi que toutes les inscriptions effectuées dessus
function suppressionEvent($idEvent) {
	$connexionBDD = getConnexionBDD();
	$connexionBDD->beginTransaction();
	$sqlQueryDeleteInscription = $connexionBDD->prepare("DELETE FROM er_evtJoueur 
	WHERE idEvt = :idEvenement");
	
	$resultatDeleteInscription = $sqlQueryDeleteInscription->execute(array( 
			'idEvenement' => $idEvent
		)
	);
	
	if($resultatDeleteInscription) {
		$sqlQueryDeleteEvent = $connexionBDD->prepare("DELETE FROM er_event 
		WHERE id = :idEvenement");
		
		$resultat = $sqlQueryDeleteEvent->execute(array( 
			'idEvenement' => $idEvent
			)
		);
		
		if($resultat) {
			// Si tout c'est bien passé on commit
			$connexionBDD->commit();
		} else {
			// Erreur on effectue un rollback
			$connexionBDD->rollBack();
		}
	}
}

?>